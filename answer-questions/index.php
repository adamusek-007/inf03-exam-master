<?php
include ("answer-questions.php")
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
    <!DOCTYPE html>
    <html lang="pl-PL">

    <head>
        <title>Odpowiadaj</title>
        <meta http-equiv="X-Ua-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <link rel="stylesheet" href="../styles/answer-question.css">
    </head>

    <body>

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