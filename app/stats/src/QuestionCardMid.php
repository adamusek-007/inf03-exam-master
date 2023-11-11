<?php

class QuestionCardMid
{
    function generate_svg_chart(): void
    {

    }
    function __construct($connection)
    {
        $question_id = $_GET["question-id"];
        $sql = "SELECT `reply_date_time`, `answer_id`, `answer_correctness` FROM `v_all_replies_data` WHERE {$question_id};";
        $this->generate_svg_chart();
    }
}