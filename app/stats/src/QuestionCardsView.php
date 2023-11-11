<?php
class QuestionsCardsView
{
    private string $summary_sql = "CALL getSummaryStats();";
    private string $sql = "CALL getQuestionsCardsView();";

    function get_each_question_stats($connection): void
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

    function get_summary_stats($connection): void
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