<?php
include("../database/connection.php");
$answer = $_POST['usr_respo'];
$ques_id = $_COOKIE['ques_id'];
setcookie("ques_id", "", time() - 1, "/");

$answer = str_replace("\"", '\"', $answer);

$connection = get_database_connection();

$query = "CALL addQuestionReply({$ques_id}, \"{$answer}\");";
$connection->query($query);
$query = "CALL getAnswerCorrectness({$ques_id}, \"{$answer}\");";
$stmt = $connection->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$is_correct = $row['is_correct'];
unset($stmt);
echo $is_correct;
if (!$is_correct) {
    $query = "CALL getQuestionCorrectAnswer({$ques_id});";
    $stmt2 = $connection->query($query);
    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
    $correct_answer = $row['content'];
    echo "{$correct_answer}";
}