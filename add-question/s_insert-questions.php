<?php
include("connection.php");

$target_dir = "zdjecia/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);
$result = move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

if ($result) {
    echo "Image uploaded successfully!";
} else {
    echo "Image upload failed.";
}

$file_size = $_FILES["image"]["size"];
if ($file_size <= 0) {
    $file_attached = false;
    $img_path = "brak";
} else {
    $file_attached = true;
    $img_path = $target_file;
}

$content = $_POST["title"];
$correct_answer = $_POST["c-answer"];
$w_answer1 = $_POST["w-answer-1"];
$w_answer2 = $_POST["w-answer-2"];
$w_answer3 = $_POST["w-answer-3"];

$query = "INSERT INTO `questions` (`title`, `has_img`, `img_path`, `correct_answer`, `wrong_answer1`, `wrong_answer2`, `wrong_answer3`) VALUES ('{$content}', '{$file_attached}', '{$img_path}', '{$correct_answer}', '{$w_answer1}', '{$w_answer2}', '{$w_answer3}')";

$connector = new Connector();
$connection = $connector->getConnectionToDatabase();
mysqli_query($connection, $query);
mysqli_close($connection);

?>