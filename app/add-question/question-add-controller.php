<?php
include("../database/connection.php");
include("./src/FormFieldsValidator.php");
include("./src/Image.php");
include("./src/QuestionInserter.php");
$form_validator = new FormFieldsValidator();
new QuestionInserter($form_validator);
?>