<?php
class ViewGenereator
{
    const GET_ARRAY_SIZE = 1;
    const REQUEST_METHOD = 'GET';
    private function get_view_generating_type(): string
    {
        if ($this->is_get_request() && $this->is_array_size_correct($this::GET_ARRAY_SIZE, $_GET)) {
            if ($this->is_variable_name_correct() && $this->is_variable_data_type_correct()) {
                return "question";
            }
        }
        return "questions";

    }
    private function is_array_size_correct(int $expected_size, array $array): bool
    {
        return $expected_size === sizeof($array);
    }
    private function is_get_request(): bool
    {
        return $this::REQUEST_METHOD == $_SERVER['REQUEST_METHOD'];
    }
    private function is_variable_name_correct(): bool
    {
        return array_key_exists("question-id", $_GET);
    }
    private function is_variable_data_type_correct(): bool
    {
        $pattern = '/[0-9]+/';
        return preg_match($pattern, $_GET['question-id']);
    }
    function __construct($connection)
    {
        $generating_type = $this->get_view_generating_type();
        if ($generating_type == "questions") {
            new QuestionsCardsView($connection);
        } else {
            new QuestionCardView($connection);
        }
    }
}