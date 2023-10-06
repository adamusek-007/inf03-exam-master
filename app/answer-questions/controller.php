<?php
include("../database/connection.php");
class Question
{
    private string $query = "CALL getRandomQuestion();";
    private int $id;
    private string $content;
    private string|null $image_path;

    public function set_id(int $id)
    {
        $this->id = $id;
    }
    public function get_id(): int
    {
        return $this->id;
    }
    public function set_content(string $content)
    {
        $this->content = $content;
    }
    public function get_content(): string
    {
        return $this->content;
    }
    public function set_image_path(string|null $path)
    {
        $this->image_path = $path;
    }
    public function get_image_path(): string
    {
        return $this->image_path;
    }
    public function print_image()
    {
        if (!is_null($this->image_path)) {
            echo "<img alt=\"zdjecie do zadania\" src=\"../resources/images/{$this->image_path}\"'>";
        }
    }

    function __construct()
    {
        $connection = get_database_connection();
        $row = $connection->query($this->query)->fetch(PDO::FETCH_ASSOC);
        $this->set_id(intval($row['id']));
        $this->set_content($row['content']);
        $this->set_image_path($row['image_path']);
        setcookie("question_id", $this->get_id());
    }
}

class Answers
{
    private string $query = "CALL getQuestionAnswers(%d);";
    private array $array = [];
    function set_array($result)
    {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($this->array, $row['content']);
        }
    }
    function print()
    {
        foreach ($this->array as $answer) {
            $answer = htmlspecialchars($answer);
            echo "<input type=\"button\" class=\"submit\" name=\"user-answer\" value=\"{$answer}\"><br>";
        }
    }
    function __construct(int $question_id)
    {
        $connection = get_database_connection();
        $this->query = sprintf($this->query, $question_id);
        $result = $connection->query($this->query);
        $this->set_array($result);
        shuffle($this->array);
    }

}
$question = new Question();
$answers = new Answers($question->get_id());