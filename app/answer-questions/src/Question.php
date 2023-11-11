<?php

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
    public function print_image(): void
    {
        if (!is_null($this->image_path)) {
            $image_path = $this->image_path;
            include("image-component.php");
        }
    }
    private function set_random_question(): void
    {
        $connection = get_database_connection();
        $row = $connection->query($this->query)->fetch(PDO::FETCH_ASSOC);
        $this->id = intval($row['id']);
        $this->content = htmlspecialchars($row['content']);
        $this->image_path = $row['image_path'];
    }

    public function __construct()
    {
        $this->set_random_question();
        setcookie("question_id", $this->get_id());
    }
}