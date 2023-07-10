<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <title>Odpowiadaj</title>
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="styles/answer-question.css">
</head>

<body>
    <?php
    include("connection.php");
    session_start();
    $connector = new Connector();
    $connection = $connector->getConnectionToDatabase();
    if (isset($_POST["mode"])) {
        $mode = $_SESSION["mode"] = $_POST["mode"];
    } else {
        $mode = $_SESSION["mode"];
    }
    $query = getQuery($mode);

    $result = $connection->query($query);
    $rowCount = $result->num_rows;
    if ($rowCount == 0) {
        $result_avability = false;
        $result = $connection->query("SELECT * FROM `questions` WHERE `repetition_time` < now() OR `repetition_time` IS NULL ORDER BY RAND() LIMIT 1;");
    } else {
        $result_avability = true;
    }

    $row = $result->fetch(PDO::FETCH_ASSOC);
    $ans1 = htmlspecialchars(str_replace("\"", "'", $row['c_answer']));
    $ans2 = htmlspecialchars(str_replace("\"", "'", $row['w_answer_1']));
    $ans3 = htmlspecialchars(str_replace("\"", "'", $row['w_answer_2']));
    $ans4 = htmlspecialchars(str_replace("\"", "'", $row['w_answer_3']));
    $answers_array = array();
    array_push($answers_array, $ans1, $ans2, $ans3, $ans4);

    function getQuery($mode)
    {
        if ($mode == "random") {
            return "SELECT * FROM `questions` ORDER BY RAND() LIMIT 1;";
        } else if ($mode == "worst") {
            return "SELECT * FROM `questions` WHERE `u_c_answers`/`u_views` < 0.9 ORDER BY RAND() LIMIT 1;";
        } else if ($mode == "optimal") {
            return "SELECT * FROM `questions` WHERE `u_views`!=0 AND `repetition_time` < now() ORDER BY `repetition_time` ASC LIMIT 1;";
        }
    }

    ?>
    <form id="form" action="answer-question.php" method="post" class="first-site-form">

        <?php
        // if ($result_avability != true) {
        //     echo "<label>Pytania z podanych warunków są nie dostępne</label>";
        // }
        ?>
        <label for="user-answer">
            <?php echo htmlspecialchars("{$row['title']}") ?>
        </label>
        <?php
        if ($row['has_img'] == 1) {
            $img = $row['img_path'];
            echo "<img alt=\"zadanie\" src='./{$img}.jpg'>";
        }
        ?>
        <fieldset id="user-answer">
            <legend>Wybierz odpowiedź</legend>
            <?php
            foreach ($answers_array as $a) {
                $random_array_id = array_rand($answers_array, 1);
                $value = $answers_array[$random_array_id];
                unset($answers_array[$random_array_id]);
                echo '<input type="submit" onclick="checkAndSubmit(this)" name="user-answer" value="' . $value . '">' . "<br>";
            }
            ?>
            <input type="submit" onclick="checkAndSubmit(this)" name="user-answer" value="Nie wiem">
        </fieldset>
        <input id="q-id-input" class="display-none" value="<?php echo $row["id"]; ?>">
        <input id="correct-answer" value="<?php echo $ans1 ?>" class="display-none">
    </form>
    <details id="question-details">
        <div id="views">
            Wyświetleń:
            <?php
            echo $row["u_views"];
            ?>
        </div>
        <div id="c-answers">
            Poprawnych odpowiedzi:
            <?php
            echo $row["u_c_answers"];
            ?>
        </div>
        <div id="c-answers-streak">
            Passa poprawnych odpowiedzi:
            <?php
            echo $row["u_c_answers_streak"];
            ?>
        </div>
        <div id="q-id">
            Id pytania:
            <?php
            echo $row["id"];
            ?>
        </div>
    </details>
    <script src="check-answer-and-submit.js"></script>
</body>

</html>