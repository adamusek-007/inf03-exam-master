<?php
$connection = get_database_connection();
$sql = "SELECT * FROM `v_questions_cards`;";
$result = $connection->query($sql);
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $columnName => $columnValue) {
        ${$columnName} = $columnValue;
    }
    include("card.php");
}