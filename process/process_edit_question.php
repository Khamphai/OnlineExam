<?php
session_start();
if (empty($_GET['qID'])) {
    header("Location: ../teacher/question.php");
}else{
    $_SESSION['q_id'] = $_GET['qID'];
    header("Location: ../teacher/edit_question.php");
}
