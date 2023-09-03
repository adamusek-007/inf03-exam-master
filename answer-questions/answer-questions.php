<?php
include("../connection.php");
session_start();
class DatabaseOperator {
    public $connection;
    private $generating_mode;
    private $is_mode_chosen;
    private $query;

public function checkIsModeChosen()
{
    $this.$is_mode_chosen = isset($_POST["mode"]);
}
public function getQuery()
{
    if ($this.$generating_mode == "random") {
        $this.$query = "SELECT * FROM getRandomQuestion;";
    } else {
        $this.$query = "SELECT * FROM getRandomQuestion;";
    }
}
public function getGeneratingMode()
{
    if ($this.$is_mode_chosen) {
        $this.$generating_mode = $_SESSION["mode"] = $_POST["mode"];
    } else {
        $this.$generating_mode =  $_SESSION["mode"];
    }
}
public function getRow($result)
{
    return $result->fetch(PDO::FETCH_ASSOC);
}
public function setConnection() {
    $this.$connection = getConnectionToDatabase();
}

};
$db_operator = new DatabaseOperator();
$db_operator->checkIsModeChosen();
$db_operator->getGeneratingMode();
$db_operator->setConnection();
$db_operator->connection;
$question_id = 

$result = $connection->query($query);
$row = getRow($result);
$question_id = $row['id'];

// $ans4 = htmlspecialchars(str_replace("\"", "'", $row['w_answer_3']));
$answers_array = array();
array_push($answers_array, $ans1, $ans2, $ans3, $ans4);