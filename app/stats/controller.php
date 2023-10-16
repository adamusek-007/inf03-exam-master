<?php
class ViewGenereator
{
    const GET_ARRAY_SIZE = 1;
    const REQUEST_METHOD = 'GET';
    private function get_view_generating_type()
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
    private function is_variable_name_correct():bool
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
class QuestionsCardsView
{
    private $summary_sql = "CALL getSummaryStats();";
    private $sql = "CALL getQuestionsCardsView();";

    function get_each_question_stats($connection)
    {
        $result = $connection->query($this->sql);
        echo "<main>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $correct_procentage = $correct_replies / ($correct_replies + $incorrect_replies) * 100;
            $incorrect_procentage = $incorrect_replies / ($correct_replies + $incorrect_replies) * 100;
            include("questions-question-card.php");
        }
        echo "</main>";
    }

    function get_summary_stats($connection)
    {
        $row = $connection->query($this->summary_sql)->fetch(PDO::FETCH_ASSOC);
        ;
        extract($row);
        if ($total_replies != 0) {
            $total_correct_procentage = $total_correct_replies / ($total_replies) * 100;
            $total_incorrect_procentage = $total_incorrect_replies / ($total_replies) * 100;
            include("summary-stats-card.php");
        }
    }

    function __construct($connection)
    {
        $this->get_summary_stats($connection);
        $this->get_each_question_stats($connection);
    }

}
class QuestionCardView
{
    private $question_id;

    private function genreate_view($connection)
    {
        $top = new QuestionCardTop($connection);
        $mid = new QuestionCardMid($connection);
        $bottom = new QuestionCardBottom($connection);

        $question_content = $top->get_question_content();
        $question_image = $top->get_question_image();
        $question_answers = $top->get_question_answers();

        $replies = $bottom->get_replies();

        include("./question-card.php");
    }
    function __construct($connection)
    {
        $this->question_id = $_GET["question-id"];
        $this->genreate_view($connection);
    }
}
class QuestionCardTop
{
    private $question_content;

    private $question_image;

    private $question_answers;

    public function get_question_answers()
    {
        return $this->question_answers;
    }
    public function get_question_content()
    {
        return $this->question_content;
    }
    public function get_question_image()
    {
        return $this->question_image;
    }
    function set_question_content_and_image($question_id, $connection)
    {
        $sql = "SELECT `questions`.`content` as `question_content`, `questions`.`image_path` as `question_image` FROM `questions` WHERE `id` = {$question_id};";
        $row = $connection->query($sql)->fetch(PDO::FETCH_ASSOC);
        $this->question_content = $row['question_content'];
        $this->question_image = $row['question_image'];
    }

    function set_question_answers($question_id, $connection)
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
class QuestionCardMid
{
    function generate_svg_chart()
    {

    }
    function __construct($connection)
    {
        $question_id = $_GET["question-id"];
        $sql = "SELECT `reply_date_time`, `answer_id`, `answer_correctness` FROM `v_all_replies_data` WHERE {$question_id};";
        $this->generate_svg_chart();
    }
}
class QuestionCardBottom
{
    private $replies;

    public function get_replies()
    {
        return $this->replies;
    }

    function __construct($connection)
    {
        $question_id = $_GET["question-id"];
        $sql = "SELECT `reply_date_time`, `answer_id`, `answer_correctness` FROM `v_all_replies_data` WHERE `question_id` = {$question_id};";
        $result = $connection->query($sql);
        $this->replies = [];
        $i = 1;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($this->replies, new Reply($row['answer_id'], $row['answer_correctness'], $row['reply_date_time'], $i++));
        }

    }
}
class Answer
{
    public $id;
    public $content;
    public $is_correct;
    function __construct($id, $content, $is_correct)
    {
        $this->id = $id;
        $this->content = $content;
        $this->is_correct = $is_correct;
    }
}
class Reply
{
    public $number;
    public $answer_id;
    public $answer_correcness;
    public $reply_date_time;

    function __construct($answer_id, $answer_correctnes, $reply_date_time, $number)
    {
        $this->answer_id = $answer_id;
        $this->answer_correcness = $answer_correctnes;
        $this->reply_date_time = $reply_date_time;
        $this->number = $number;
    }
}
$connection = get_database_connection();
$view_generator = new ViewGenereator($connection);