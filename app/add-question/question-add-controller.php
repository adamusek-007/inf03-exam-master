<?php
include("../database/connection.php");
class Image
{
    public static array $supported_file_types = ["image/jpeg", "image/jpg", "image/png"];
    private bool|null $is_attached = null;
    private bool|null $is_type_correct = null;
    private string $name = "";
    private function set_attachness(bool $is_attached)
    {
        $this->is_attached = $is_attached;
    }
    public function get_attachness(): bool
    {
        return $this->is_attached;
    }
    private function set_type_correctness(bool $is_type_correct)
    {
        $this->is_type_correct = $is_type_correct;
    }
    public function get_type_correctness(): bool
    {
        return $this->is_type_correct;
    }
    private function set_name(string $name)
    {
        $this->name = $name;
    }
    public function get_name(): string
    {
        return $this->name;
    }
    private function check_is_image_attached()
    {
        $this->set_attachness(
            array_key_exists("image", $_FILES) &&
            array_key_exists("type", $_FILES["image"]) &&
            $_FILES["image"]["type"] !== ""
        );
    }

    private function check_is_type_correct()
    {
        $uploaded_file_type = $_FILES["image"]["type"];
        $this->set_type_correctness(in_array($uploaded_file_type, Image::$supported_file_types));
    }
    public function upload()
    {
        $target_file_dir = QuestionInserter::$file_upload_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_dir);
    }


    public function __construct()
    {
        $this->check_is_image_attached();
        if ($this->get_attachness()) {
            $this->check_is_type_correct();
            if ($this->get_type_correctness()) {
                $this->set_name($_FILES["image"]["name"]);
            }
        }
    }
}
class FormDataValidator
{
    private bool $data_completition;

    public static array $expected_data_fields =
        [
            "content",
            "c-answer",
            "w-answer-1",
            "w-answer-2",
            "w-answer-3"
        ];

    private function set_data_completition(bool $data_complete)
    {
        $this->data_completition = $data_complete;
    }
    public function get_data_completition(): bool
    {
        return $this->data_completition;
    }
    private function check_data_completion()
    {
        foreach ($this::$expected_data_fields as $field_name) {
            if (!isset($_POST[$field_name]) || empty($_POST[$field_name])) {
                $this->set_data_completition(FALSE);
                return;
            }
        }
        $this->set_data_completition(TRUE);
    }
    public function __construct()
    {
        $this->check_data_completion();
    }
}
class QuestionInserter
{
    public static string $file_upload_dir = "../resources/images/";

    public function __construct(FormDataValidator $form_validator)
    {
        if ($form_validator->get_data_completition()) {
            $this->internal_proceed();
        } else {
            $this->create_response("error", "Wymagane pola nie sa wypelnione.");
        }
    }
    private function internal_proceed()
    {
        $image = new Image();

        if ($image->get_attachness()) {
            if ($image->get_type_correctness()) {
                $image->upload();
                $this->insert_data(TRUE);
            } else {
                $this->create_response("error", "Typ załączonego obrazu nie jest obsługiwany. \n Obsługiwane typy plików to: PNG, JPEG, JPG.");
            }
        } else {
            $this->insert_data(FALSE);
        }
    }

    private function insert_data(bool $has_image)
    {
        try {
            $connection = get_database_connection();
            $connection->query($this->get_question_insert_query($has_image));
            $question_id = $this->get_latest_question_id();
            $connection->query($this->get_answers_insert_query($question_id));
            $this->create_response("success", "Pytanie pomyślnie dodane do bazy danych");
        } catch (Exception $e) {
            $this->create_response("error", "Wystąpił błąd: {$e}");
        }

    }
    private function create_response($status, $message)
    {
        $response = array(
            "status" => $status,
            "message" => $message
        );
        $JSON_response = json_encode($response);
        echo $JSON_response;

    }
    function get_question_insert_query(bool $has_image): string
    {
        $add_question_query = "CALL addQuestion(\"%s\", %d, \"%s\");";
        if ($has_image) {
            return sprintf($add_question_query, $_POST['content'], $has_image, $_FILES["image"]["name"]);
        } else {
            return sprintf($add_question_query, $_POST['content'], $has_image, 'NULL');
        }
    }
    function get_answers_insert_query(int $question_id)
    {
        $query = "";
        $q_add_answer = "CALL addAnswer(%d, \"%s\", %d);";
        $answers_fields = ["c-answer", "w-answer-1", "w-answer-2", "w-answer-3"];
        foreach ($answers_fields as $answer) {
            if ($answer == "c-answer") {
                $query = $query . sprintf($q_add_answer, $question_id, $_POST[$answer], 1);
            } else {
                $query = $query . sprintf($q_add_answer, $question_id, $_POST[$answer], 0);
            }
        }
        return $query;

    }
    function get_latest_question_id()
    {
        $connection = get_database_connection();
        $row = $connection->query("CALL getLatestAddedQuestionId();")->fetch(PDO::FETCH_ASSOC);
        return intval($row['id']);
    }
}
$form_validator = new FormDataValidator();
new QuestionInserter($form_validator);
?>