<?php
include("../database/connection.php");
include("./src/Answers.php");
include("./src/Question.php");

$question = new Question();
$answers = new Answers($question->get_id());