<?php

class ViewGenereator
{
    private function get_view_generation_type() {
        if ($this->is_get_request()) {
            $get_size = $this->check_get_request_size();
            if ($get_size == 1) {
                if ($this->check_get_variable_name()) {
                    if ($this->check_get_variable_data_type()) {
                        return "question";
                    }
                } else {
                    return "questions";
                }
            } else {
                return "questions";
            }
        } else {
            return "questions";
        }
    }
    private function is_get_request()
    {
        return "GET" == $_SERVER['REQUEST_METHOD'];
    }
    private function check_get_request_size()
    {
        return sizeof($_GET);
    }
    private function check_get_variable_name()
    {
        return array_key_exists("question-id", $_GET);
    }
    private function check_get_variable_data_type()
    {
        $pattern = '/[0-9]+/';
        return preg_match($pattern, $_GET['question-id']);
    }
    function __construct($connection)
    {
        $generating_type = $this->get_view_generation_type();
        if ($generating_type == "questions"){
            new QuestionsCardsView($connection);
        } else {
            new QuestionCardView($connection);
        }
    }
}
class QuestionCardView
{
    private function get_question_card_view($connection, $question_id)
    {
        $sqls = [
            "CALL getQuestionAnswers({$question_id})",
            "SELECT `reply_date_time`, `answer_id`, `answer_correctness` FROM v_everything WHERE {$question_id};"
        ];
        foreach ($sqls as $sql) {

        }
    }
    function __construct($connection) {

    }
}
class QuestionsCardsView
{
    private $sql = "CALL getQuestionsCardsView();";

    function __construct($connection){
        $result = $connection->query($this->sql);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            include("questions-question-card.php");
        }
    }
}
$connection = get_database_connection();
$view_generator = new ViewGenereator($connection);