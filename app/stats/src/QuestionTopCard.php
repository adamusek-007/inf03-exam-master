<?php
class QuestionCardTop
{
    private string $question_content;

    private string $question_image;

    private array $question_answers;

    public function get_question_answers(): array
    {
        return $this->question_answers;
    }
    public function get_question_content(): string
    {
        return $this->question_content;
    }
    public function get_question_image(): string
    {
        return $this->question_image;
    }
    function set_question_content_and_image($question_id, $connection): void
    {
        $sql = "SELECT `questions`.`content` as `question_content`, `questions`.`image_path` as `question_image` FROM `questions` WHERE `id` = {$question_id};";
        $row = $connection->query($sql)->fetch(PDO::FETCH_ASSOC);
        $this->question_content = $row['question_content'];
        $this->question_image = $row['question_image'];
    }

    function set_question_answers($question_id, $connection): void
    {
        $sql = "CALL getQuestionAnswers({$question_id});";
        $result = $connection->query($sql);
        $this->question_answers = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($this->question_answers, new Answer($row['id'], $row['content'], $row['is_correct']));
        }
    }

    function __construct($connection)
    {
        $question_id = $_GET["question-id"];
        $this->set_question_content_and_image($question_id, $connection);
        $this->set_question_answers($question_id, $connection);
    }
}