<?php
include("../connection.php");

$q_question = "SELECT * FROM getRandomQuestion;";
$connection = getConnectionToDatabase();
$result = $connection->query($q_question);
$row = $result->fetch(PDO::FETCH_ASSOC);
$ques_content = $row['content'];
$ques_id = $row['id'];
$image_path = $row['image_path'];
$q_answers = "CALL getAnswersRelatedToQuestion({$ques_id});";
$result = $connection->query($q_answers);

$answers_array = getAnswersArray($result);

function getAnswersArray($result)
{
    $i = 0;
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $answers_array[$i] = $row['content'];
        $i++;
    }
    return $answers_array;
}
function randomizeAnswers($answers_array) {
    return array_rand($answers_array);
}
function printAnswers($answers_array)
{
    
    foreach ($answers_array as $answer) {
        $content = $answer->getContent();
        echo "<input type=\"submit\" onclick=\"checkAndSubmit(this)\" name=\"user-answer\" value=\"{$content}\"><br>";
    }
}