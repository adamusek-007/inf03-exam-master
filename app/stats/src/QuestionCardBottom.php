<?php
class QuestionCardBottom
{
    private array $replies;

    public function get_replies(): array
    {
        return $this->replies;
    }

    function __construct($connection)
    {
        $question_id = $_GET["question-id"];
        $sql = "SELECT `reply_date_time`, `answer_id`, `answer_correctness` FROM `v_all_replies_data` WHERE `question_id` =%d;";
        $sql = sprintf($sql, $question_id);
        $result = $connection->query($sql);
        $this->replies = [];
        $i = 1;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($this->replies, new Reply($row['answer_id'], $row['answer_correctness'], $row['reply_date_time'], $i++));
        }

    }
}