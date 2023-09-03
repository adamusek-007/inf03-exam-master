<?php
include("connection.php");

$u_answer = $_GET["answer"];
$q_id = $_GET["q-id"];
$c_answer = $_GET["c-answer"];

$connection = getConnectionToDatabase();

$query = "SELECT `u_views`, `u_c_answers`, `u_c_answers_streak` FROM `questions` WHERE `id`= {$q_id}";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);
$views = $row["u_views"];
$c_answers = $row["u_c_answers"];
$c_answers_streak = $row["u_c_answers_streak"];
$views += 1;
if ($u_answer == $c_answer) {
    $c_answers += 1;
    $c_answers_streak += 1;
    
} else {
    $c_answers_streak = 0;
}
$formated_date_time = getNextRepeatTime($c_answers_streak);
$update_query = "UPDATE `questions` SET `u_views`=\"{$views}\",`u_c_answers`=\"{$c_answers}\", `u_c_answers_streak`=\"{$c_answers_streak}\", `repetition_time`=\"{$formated_date_time}\" WHERE `id`= {$q_id};";
mysqli_query($connection, $update_query);

function getNextRepeatTime($c_answers_streak)
        {
            $next_repeat_time = new DateTime("now");
            if ($c_answers_streak == 0 OR $c_answers_streak==null) {
                $next_repeat_time->add(new DateInterval("PT1M"));
            } else if ($c_answers_streak == 1) {
                $next_repeat_time->add(new DateInterval("PT5M"));
            } else if ($c_answers_streak == 2) {
                $next_repeat_time->add(new DateInterval("PT15M"));
            } else if ($c_answers_streak == 3) {
                $next_repeat_time->add(new DateInterval("PT30M"));
            } else if ($c_answers_streak == 4) {
                $next_repeat_time->add(new DateInterval("PT1H"));
            } else if ($c_answers_streak == 5) {
                $next_repeat_time->add(new DateInterval("PT3H"));
            } else if ($c_answers_streak == 6) {
                $next_repeat_time->add(new DateInterval("PT6H"));
            } else if ($c_answers_streak == 7) {
                $next_repeat_time->add(new DateInterval("PT12H"));
            } else if ($c_answers_streak == 8) {
                $next_repeat_time->add(new DateInterval("PT16H"));
            } else if ($c_answers_streak == 9) {
                $next_repeat_time->add(new DateInterval("P2D"));
            } else if ($c_answers_streak == 10) {
                $next_repeat_time->add(new DateInterval("P3D"));
            } else if ($c_answers_streak == 11) {
                $next_repeat_time->add(new DateInterval("P1W"));
            } else if ($c_answers_streak == 12) {
                $next_repeat_time->add(new DateInterval("P2W"));
            } else if ($c_answers_streak == 13) {
                $next_repeat_time->add(new DateInterval("P1M"));
            } else if ($c_answers_streak == 13) {
                $next_repeat_time->add(new DateInterval("P3M"));
            }

            $formated_date_time = $next_repeat_time->format("Y-m-d H:i:s");
            return $formated_date_time;
        }

?>