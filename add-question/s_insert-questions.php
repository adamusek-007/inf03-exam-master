<?php
include("../connection.php");
function checkIsDataComplete()
{
    $form_fields = ["content", "c-answer", "w-answer-1", "w-answer-2", "w-answer-3"];

    foreach ($form_fields as $field_name) {
        if (!isset($_POST[$field_name]) || empty($_POST[$field_name])) {
            return false;
        }
    }
    return true;
}
function checkIsImageAttached()
{
    if ($_FILES["image"]["name"] == "") {
        return false;
    } else {
        return true;
    }
}
function checkIsImageTypeCorrect()
{
    $supported_file_types = ["image/jpeg", "image/jpg", "image/png"];
    $uploaded_file_type = $_FILES["image"]["type"];
    return in_array($uploaded_file_type, $supported_file_types);
}
function uploadImage()
{
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $result = move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
}

function insertQuestion($query)
{
    $connector = new Connector();
    $connection = $connector->getConnectionToDatabase();
    $connection->query($query);
    $result = $connection->query("CALL getLatestAddedQuestionId();");
    $row = $result->fetch(PDO::FETCH_ASSOC);
    return $row['id'];
}
function getAnswersInsertQuery($question_id)
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
function insertAnswers($query)
{
    $connector = new Connector();
    $connection = $connector->getConnectionToDatabase();
    $result = $connection->query($query);
    $row = $result->fetch();
}
function getQuestionInsertQuery($has_image)
{
    $q_add_question = "CALL addQuestion(\"%s\", %d, \"%s\");";
    if ($has_image == 0) {
        return sprintf($q_add_question, $_POST['content'], $has_image, 'NULL');
    } else {
        return sprintf($q_add_question, $_POST['content'], $has_image, $_FILES["image"]["name"]);
    }
}
function insertData($has_image)
{
    $query = getQuestionInsertQuery($has_image);
    $question_id = insertQuestion($query);
    $query = getAnswersInsertQuery($question_id);
    insertAnswers($query);
}

$is_data_complete = checkIsDataComplete();
if ($is_data_complete) {
    $is_image_attached = checkIsImageAttached();
    if ($is_image_attached) {
        $is_image_type_correct = checkIsImageTypeCorrect();
        if ($is_image_type_correct) {
            uploadImage();
            insertData(1);
        } else {
            echo "Errno: 1: Typ pliku nie jest obsługiwany.";
            exit;
        }
    } else {
        insertData(0);
    }
} else {
    echo "Errno: 2 - Wymagane pola nie są wypełnione.";
    exit;
}
?>