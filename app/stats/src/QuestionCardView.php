<?php
class QuestionCardView
{
    private $question_id;

    private function genreate_view($connection): void
    {
        $top = new QuestionCardTop($connection);
        $mid = new QuestionCardMid($connection);
        $bottom = new QuestionCardBottom($connection);

        $question_content = $top->get_question_content();
        $question_image = $top->get_question_image();
        $question_answers = $top->get_question_answers();

        $replies = $bottom->get_replies();

        include("./question-card.php");
    }
    function __construct($connection)
    {
        $this->question_id = $_GET["question-id"];
        $this->genreate_view($connection);
    }
}