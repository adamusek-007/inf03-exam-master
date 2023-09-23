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
    <link rel="stylesheet" href="../resources/styles/answer-question.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
</head>

<body>
    <form id="form" action="index.php" method="post" class="first-site-form">
        <label for="user-answer">
            <?=htmlspecialchars($ques_content)?>
        </label>
        <?=print_image($image_path)?>
        <fieldset id="user-answer">
            <legend>Wybierz odpowiedź</legend>
            <?=print_answers($answers_array)?>
            <input type="button" onclick="(checkAndSubmit(this.value))" name="user-answer" value="Nie wiem">
        </fieldset>
    </form>
    <details>
        <p>Id pytania: <?=$ques_id?></p>
    </details>
    <script src="check-answer-and-submit.js"></script>
</body>

</html>