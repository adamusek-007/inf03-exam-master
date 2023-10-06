<?php
include("../database/connection.php");
class Form {
    public static array $expected_fields_names = ["content", "c-answer", "w-answer-1", "w-answer-2", "w-answer-3"];

}
class Image {
    public static array $supported_file_types = ["image/jpeg", "image/jpg", "image/png"];
    private bool|null $is_attached = null;
    private bool|null $is_type_correct = null;
    function __construct() {
        
    }
}
class FormValidator
{
    private bool $data_completition;
    private bool|null $is_image_attached = null;

    private bool|null $is_image_type_correct = null;

    function set_data_completition(bool $data_complete)
    {
        $this->data_completition = $data_complete;
    }
    function get_data_completition(): bool
    {
        return $this->data_completition;
    }
    function set_is_image_attached(bool $is_image_attached)
    {
        $this->is_image_attached = $is_image_attached;
    }
    function get_image_attachness(): bool
    {
        return $this->is_image_attached;
    }
    function set_is_image_type_correct(bool $is_image_type_correct)
    {
        $this->is_image_type_correct = $is_image_type_correct;
    }
    function get_is_image_type_correct(): bool
    {
        return $this->is_image_type_correct;
    }
    function check_data_completion()
    {
        foreach (Form::$expected_fields_names as $field_name) {
            if (!isset($_POST[$field_name]) || empty($_POST[$field_name])) {
                $this->set_data_completition(false);
                return;
            }
        }
        $this->set_data_completition(true);
    }
    function check_is_image_attached()
    {
        $this->set_is_image_attached(
            array_key_exists("image", $_FILES) &&
            array_key_exists("type", $_FILES["image"]) &&
            $_FILES["image"]["type"] !== ""
        );
    }
    function check_is_image_type_correct()
    {
        $uploaded_file_type = $_FILES["image"]["type"];
        $this->set_is_image_type_correct(in_array($uploaded_file_type, Image::$supported_file_types));
    }
    function __construct()
    {
        $this->check_data_completion();
        if ($this->get_data_completition()) {
            $this->check_is_image_attached();
            if ($this->get_image_attachness()) {
                $this->check_is_image_type_correct();
            }
        }
    }

}
class QuestionInserter
{
    public static string $file_upload_dir = "../resources/images/";

    function __construct(FormValidator $form_validator)
    {
        if ($form_validator->get_data_completition()) {
            $this->internal_proceed($form_validator);
        } else {
            echo "Wymagane pola nie są wypełnione.";
            exit;
        }
    }
    function internal_proceed($form_validator)
    {
        if ($form_validator->get_image_attachness()) {
            if ($form_validator->get_is_image_type_correct()) {
                $this->upload_image();
                $this->insert_data(true);
            } else {
                echo "Typ załączonego obrazu nie jest obsługiwany. \n Obsługiwane typy plików to: PNG, JPEG, JPG.";
            }
        } else {
            $this->insert_data(false);
        }
    }
    function upload_image()
    {
        $target_file_dir = QuestionInserter::$file_upload_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_dir);
    }

    function insert_data(bool $has_image)
    {
        try {
            $connection = get_database_connection();

            $connection->query($this->get_question_insert_query($has_image));
            $question_id = $this->get_latest_question_id();
            $connection->query($this->get_answers_insert_query($question_id));
            $response = array(
                "status" => "success",
                "message" => "Question successfully inserted."
            );
        } catch (Exception $e) {
            $response = array(
                "status" => "error",
                "message" => "An error occurred. Please try again later."
            );
        }
        header('Content-Type: application/json');
        echo json_encode($response);
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
// $form_data = new FormData();
$form_validator = new FormValidator();
new QuestionInserter($form_validator);
?>