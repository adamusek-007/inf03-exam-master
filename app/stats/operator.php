<?php
function is_get_request()
{
    return "GET" == $_SERVER['REQUEST_METHOD'];
}
function check_get_request_size()
{
    return sizeof($_GET);
}
function check_get_variable_name()
{
    return array_key_exists("question-id", $_GET);
}
function check_get_variable_data_type()
{
    $pattern = '/[0-9]+/';
    return preg_match($pattern, $_GET['question-id']);
}
function get_questions_cards_view($connection)
{
    $sql = "CALL getQuestionsCardsView();";
    $result = $connection->query($sql);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        foreach ($row as $columnName => $columnValue) {
            ${$columnName} = $columnValue;
        }
        include("questions-question-card.php");
    }
}
$connection = get_database_connection();
if (is_get_request()) {
    $get_size = check_get_request_size();
    if ($get_size == 1) {
        if (check_get_variable_name()) {
            if (check_get_variable_data_type()) {
                get_questions_cards_view($connection);    
            }
        } else {
            get_questions_cards_view($connection);
        }
    } else {
        get_questions_cards_view($connection);
    }
} else {
    get_questions_cards_view($connection);
}