<?php
include("../database/connection.php");
class Question
{
    private string $query = "CALL getRandomQuestion();";
    private int $id;
    private string $content;
    private string|null $image_path;

    public function get_id(): int
    {
        return $this->id;
    }
    public function get_content(): string
    {
        return $this->content;
    }
    public function get_image_path(): string
    {
        return $this->image_path;
    }
    public function print_image()
    {
        if (!is_null($this->image_path)) {
            $image_path = $this->image_path;
            include("image-component.php");
        }
    }

    function set_random_question()
    {
        $connection = get_database_connection();
        $row = $connection->query($this->query)->fetch(PDO::FETCH_ASSOC);
        $this->id = intval($row['id']);
        $this->content = htmlspecialchars($row['content']);
        $this->image_path = $row['image_path'];
    }

    function __construct()
    {
        $this->set_random_question();
        setcookie("question_id", $this->get_id());
    }
}
class Answers
{
    private string $query_pattern = "CALL getQuestionAnswers(%d);";
    private string $query = "";
    private array $array = [];
    function set_array(string $query)
    {
        $connection = get_database_connection();
        $result = $connection->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($this->array, htmlspecialchars($row['content']));
        }
        shuffle($this->array);
    }
    function print()
    {
        foreach ($this->array as $answer) {
            include("answer-component.php");
        }
    }
    function set_query(int $question_id)
    {
        $this->query = sprintf($this->query_pattern, $question_id);
    }
    function __construct(int $question_id)
    {
        $this->set_query($question_id);
        $this->set_array($this->query);
    }

}
$question = new Question();
$answers = new Answers($question->get_id());