<?php
class QuestionReplyProcessor
{
    private string $user_reply_answer;
    private int $question_id;
    private bool $is_reply_correct;
    private string $correct_answer;

    function add_question_reply(): void
    {
        $connection = get_database_connection();
        $query = "CALL addQuestionReply({$this->question_id}, \"{$this->user_reply_answer}\");";
        $connection->query($query);
    }
    function get_answer_correctness(): bool
    {
        $connection = get_database_connection();
        $query = "CALL getAnswerCorrectness({$this->question_id}, \"{$this->user_reply_answer}\");";
        $row = $connection->query($query)->fetch(PDO::FETCH_ASSOC);
        return $row['is_correct'];
    }
    function get_correct_answer(): void
    {
        $connection = get_database_connection();
        $query = "CALL getQuestionCorrectAnswer({$this->question_id});";
        $row = $connection->query($query)->fetch(PDO::FETCH_ASSOC);
        $correct_answer = $row['content'];
        $this->correct_answer = $correct_answer;
    }

    function unset_cookie(): void
    {
        setcookie("question_id", "", time() - 1);
    }
    function escape_slashes(): void
    {
        $this->user_reply_answer = str_replace("\"", '\"', $_POST['user_reply_answer']);
    }
    function get_question_id(): void
    {
        $this->question_id = intval($_COOKIE['question_id']);
    }
    function __construct()
    {
        $this->escape_slashes();
        $this->get_question_id();
        $this->add_question_reply();
        $this->is_reply_correct = $this->get_answer_correctness();
        $this->get_correct_answer();
        $json = json_encode(
            array(
                "reply_correctness" => $this->is_reply_correct,
                "correct_answer" => $this->correct_answer,
                "user_reply_answer" => $this->user_reply_answer
            )
        );
        echo $json;
    }
}