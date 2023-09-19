<?php
include("../connection.php");
function check_data_completion()
{
    $form_fields = ["content", "c-answer", "w-answer-1", "w-answer-2", "w-answer-3"];

    foreach ($form_fields as $field_name) {
        if (!isset($_POST[$field_name]) || empty($_POST[$field_name])) {
            return false;
        }
    }
    return true;
}
function check_image_attachment()
{
    if ($_FILES["image"]["name"] == "") {
        return false;
    } else {
        return true;
    }
}
function check_image_type_correctness()
{
    $supported_file_types = ["image/jpeg", "image/jpg", "image/png"];
    $uploaded_file_type = $_FILES["image"]["type"];
    return in_array($uploaded_file_type, $supported_file_types);
}
function upload_image()
{
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $result = move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
}

function insert_question($query)
{
    $connection = get_database_connection();
    $connection->query($query);
    $result = $connection->query("CALL getLatestAddedQuestionId();");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    return $row['id'];
}
function get_answers_insert_query($question_id)
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
function insert_answers($query)
{
    $connection = get_database_connection();
    $result = $connection->query($query);
}
function get_question_insert_query($has_image)
{
    $q_add_question = "CALL addQuestion(\"%s\", %d, \"%s\");";
    if ($has_image == 0) {
        return sprintf($q_add_question, $_POST['content'], $has_image, 'NULL');
    } else {
        return sprintf($q_add_question, $_POST['content'], $has_image, $_FILES["image"]["name"]);
    }
}
function insert_data($has_image)
{
    $query = get_question_insert_query($has_image);
    $question_id = insert_question($query);
    $query = get_answers_insert_query($question_id);
    insert_answers($query);
    echo 0;
}

$is_data_complete = check_data_completion();
if ($is_data_complete) {
    $is_image_attached = check_image_attachment();
    if ($is_image_attached) {
        $is_image_type_correct = check_image_type_correctness();
        if ($is_image_type_correct) {
            upload_image();
            insert_data(1);
        } else {
            echo "Typ załączonego obrazu nie jest obsługiwany. \n Obsługiwane typy plików to: PNG, JPEG, JPG.";
            exit;
        }
    } else {
        insert_data(0);
    }
} else {
    echo "Wymagane pola nie są wypełnione.";
    exit;
}
?>