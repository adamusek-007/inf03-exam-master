<?php
include("../connection.php");

$q_question = "SELECT * FROM getRandomQuestion;";
$connection = getConnectionToDatabase();
$result = $connection->query($q_question);
$row = $result->fetch(PDO::FETCH_ASSOC);
$ques_content = $row['content'];
$ques_id = $row['id'];
setcookie("ques_id", "", time() -1);
setcookie("ques_id", $ques_id);
$image_path = $row['image_path'];
$q_answers = "CALL getAnswersRelatedToQuestion({$ques_id});";
$result = $connection->query($q_answers);

$answers_array = getAnswersArray($result);
shuffle($answers_array);
function getAnswersArray($result)
{
    $i = 0;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $answers_array[$i] = $row['content'];
        $i++;
    }
    return $answers_array;
}
function printAnswers($answers_array)
{
    foreach ($answers_array as $answer) {
        $answer = htmlspecialchars($answer);
        echo "<input type=\"button\" onclick=\"checkAndSubmit(this.value)\" name=\"user-answer\" value=\"{$answer}\"><br>";
    }
}

function printImage($image_path) {
    if (!is_null($image_path)) {
        echo "<img alt=\"zdjecie do zadania\" src=\"../images/{$image_path}\"'>";
    }
}