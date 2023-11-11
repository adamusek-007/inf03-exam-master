<?php

class Answers
{
    private string $query_pattern = "CALL getQuestionAnswers(%d);";
    private string $query = "";
    private array $array = [];
    function set_array(string $query): void
    {
        $connection = get_database_connection();
        $result = $connection->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($this->array, htmlspecialchars($row['content']));
        }
        shuffle($this->array);
    }
    function print(): void
    {
        foreach ($this->array as $answer) {
            include("answer-component.php");
        }
    }
    function set_query(int $question_id): void
    {
        $this->query = sprintf($this->query_pattern, $question_id);
    }
    function __construct(int $question_id)
    {
        $this->set_query($question_id);
        $this->set_array($this->query);
    }

}