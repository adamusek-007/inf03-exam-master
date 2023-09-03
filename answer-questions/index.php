<?php
include("answer-questions.php");
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
    <form id="form" action="answer-question.php" method="post" class="first-site-form">
        <label for="user-answer">
            <?=htmlspecialchars($ques_content)?>
        </label>
        <?php
        if (!is_null($row['image_path'])) {
            $img = $row['image_path'];
            echo "<img alt=\"zdjecie do zadania\" src=\"../images/{$img}\"'>";
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
    </form>
    <script src="check-answer-and-submit.js"></script>
</body>

</html>