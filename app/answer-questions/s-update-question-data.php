<?php
include("../database/connection.php");
class QuestionReplyProcessor
{
    private string $user_reply_answer;
    private int $question_id;
    private bool $is_reply_correct;
    private string $correct_answer;

    function addQuestionReply()
    {
        $connection = get_database_connection();
        $query = "CALL addQuestionReply({$this->question_id}, \"{$this->user_reply_answer}\");";
        $connection->query($query);
    }
    function getAnswerCorrectness(): bool
    {
        $connection = get_database_connection();
        $query = "CALL getAnswerCorrectness({$this->question_id}, \"{$this->user_reply_answer}\");";
        $row = $connection->query($query)->fetch(PDO::FETCH_ASSOC);
        return $row['is_correct'];
    }
    function getCorrectAnswer()
    {
        $connection = get_database_connection();
        $query = "CALL getQuestionCorrectAnswer({$this->question_id});";
        $row = $connection->query($query)->fetch(PDO::FETCH_ASSOC);
        $correct_answer = $row['content'];
        $this->correct_answer = $correct_answer;
    }

    function unsetCookie()
    {
        setcookie("question_id", "", time() - 1);
    }

    function __construct()
    {
        $this->user_reply_answer = str_replace("\"", '\"', $_POST['user_reply_answer']);
        $this->question_id = intval($_COOKIE['question_id']);
        $this->unsetCookie();
        $this->addQuestionReply();
        $this->is_reply_correct = $this->getAnswerCorrectness();
        $this->getCorrectAnswer();
        $json = json_encode(array("reply_correctness" => $this->is_reply_correct, "correct_answer" => $this->correct_answer));
        echo $json;
    }
}
new QuestionReplyProcessor();