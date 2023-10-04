<?php
include("../database/connection.php");
function check_data_completion(): bool
{
    $form_fields = ["content", "c-answer", "w-answer-1", "w-answer-2", "w-answer-3"];

    foreach ($form_fields as $field_name) {
        if (!isset($_POST[$field_name]) || empty($_POST[$field_name])) {
            return false;
        }
    }
    return true;
}
function check_image_attachment(): bool
{
    if ($_FILES["image"]["name"] == "") {
        return false;
    } else {
        return true;
    }
}
function check_image_type_correctness(): bool
{
    $supported_file_types = ["image/jpeg", "image/jpg", "image/png"];
    $uploaded_file_type = $_FILES["image"]["type"];
    return in_array($uploaded_file_type, $supported_file_types);
}

function upload_image()
{
    $target_dir = "../resources/images/";
    $target_file_dir = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_dir);
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
function get_latest_question_id(){
    $connection = get_database_connection();
    $result = $connection->query("CALL getLatestAddedQuestionId();");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    return intval($row['id']);
}
function insert_data(bool $has_image)
{
    $connection = get_database_connection();
    
    $q_question_insert = get_question_insert_query($has_image);
    $connection->query($q_question_insert);

    $question_id = get_latest_question_id();
    $q_answers_insert = get_answers_insert_query($question_id);
    $connection->query($q_answers_insert);
    echo 0;
}

if (check_data_completion()) {
    if (check_image_attachment()) {
        if (check_image_type_correctness()) {
            upload_image();
            insert_data(true);
        } else {
            echo "Typ załączonego obrazu nie jest obsługiwany. \n Obsługiwane typy plików to: PNG, JPEG, JPG.";
            exit;
        }
    } else {
        insert_data(false);
    }
} else {
    echo "Wymagane pola nie są wypełnione.";
    exit;
}
?>