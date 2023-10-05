<?php
include("../database/connection.php");
class QuestionInserter
{
    public static array $form_fields = ["content", "c-answer", "w-answer-1", "w-answer-2", "w-answer-3"];
    public static array $supported_file_types = ["image/jpeg", "image/jpg", "image/png"];
    public static string $file_upload_dir = "../resources/images/";

    private bool $data_completition;

    function set_data_completition(bool $data_complete)
    {
        $this->data_completition = $data_complete;
    }
    function get_data_completition(): bool
    {
        return $this->data_completition;
    }

    function check_data_completion()
    {
        foreach (QuestionInserter::$form_fields as $field_name) {
            if (array_key_exists($field_name, $_POST)) {
                if (!isset($_POST[$field_name]) || empty($_POST[$field_name])) {
                    $this->set_data_completition(false);
                    break;
                }
            } else {
                $this->set_data_completition(false);
            }
        }
        $this->set_data_completition(true);
    }

    function check_image_attachment(): bool
    {
        if (array_key_exists("image", $_FILES) && array_key_exists("name", $_FILES["image"]) && $_FILES["image"]["type"] != "") {
            return true;
        }
        return false;
    }
    function check_image_type_correctness(): bool
    {
        $uploaded_file_type = $_FILES["image"]["type"];
        return in_array($uploaded_file_type, QuestionInserter::$supported_file_types);
    }

    function __construct()
    {
        $this->check_data_completion();
        if ($this->data_completition) {
            if ($this->check_image_attachment()) {
                if ($this->check_image_type_correctness()) {
                    $this->upload_image();
                    $this->insert_data(true);
                } else {
                    echo "Typ załączonego obrazu nie jest obsługiwany. \n Obsługiwane typy plików to: PNG, JPEG, JPG.";
                    exit;
                }
            } else {
                $this->insert_data(false);
            }
        } else {
            echo "Wymagane pola nie są wypełnione.";
            exit;
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

            $q_question_insert = $this->get_question_insert_query($has_image);
            $connection->query($q_question_insert);

            $question_id = $this->get_latest_question_id();
            $q_answers_insert = $this->get_answers_insert_query($question_id);
            $connection->query($q_answers_insert);
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
        // NOT SURE aobut header
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
        $result = $connection->query("CALL getLatestAddedQuestionId();");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return intval($row['id']);
    }
}
new QuestionInserter();
?>