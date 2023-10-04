<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <title>Odpowiadaj</title>
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="../resources/styles/answer-question.css">
</head>

<body>
    <form id="form" method="post" class="first-site-form">
        <label for="user-answer">
            <?=$question->get_content()?>
        </label>
        <?=$question->print_image()?>
        <fieldset id="user-answer">
            <legend>Wybierz odpowiedź</legend>
            <?php $answers->print()?>
        </fieldset>
    </form>
    <details>
        <p>Id pytania: <?=$question->get_id()?></p>
    </details>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="check-answer-and-submit.js"></script>
</body>

</html>