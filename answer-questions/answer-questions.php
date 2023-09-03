<?php
include("../connection.php");
session_start();

function checkIsModeChosen()
{
    return isset($_POST["mode"]);
}
function getQuery($mode)
{
    if ($mode == "random") {
        return "SELECT * FROM getRandomQuestion;";
    } else {
        return "SELECT * FROM getRandomQuestion;";
    }
    // } else if ($mode == "worst") {
    //     return "SELECT * FROM `questions` WHERE `u_c_answers`/`u_views` < 0.9 ORDER BY RAND() LIMIT 1;";
    // } else if ($mode == "optimal") {
    //     return "SELECT * FROM `questions` WHERE `u_views`!=0 AND `repetition_time` < now() ORDER BY `repetition_time` ASC LIMIT 1;";
    // }
}
function getGeneratingMode($modeChosen) {
    if ($modeChosen) {
        return $_SESSION["mode"] = $_POST["mode"];
    } else {
        return $_SESSION["mode"];
    }
}
$modeChosen = checkIsModeChosen();
$generating_mode = getGeneratingMode($modeChosen);
$connection = getConnectionToDatabase();
$query = getQuery($generating_mode);
$result = $connection->query($query);
function getRow($result) {
    return $result->fetch(PDO::FETCH_ASSOC);    
}
// $ans4 = htmlspecialchars(str_replace("\"", "'", $row['w_answer_3']));
$answers_array = array();
array_push($answers_array, $ans1, $ans2, $ans3, $ans4);