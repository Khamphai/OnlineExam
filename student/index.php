<?php
session_start();
if ($_SESSION['state'] === 'login') {
    if ($_SESSION['role'] === 'student') {
        unset($_SESSION['category_id']);
        unset($_SESSION['subject_id']);
        unset($_SESSION['question']);
        unset($_SESSION['q_count']);
        unset($_SESSION['give_minute']);
        unset($_SESSION['action']);
        unset($_SESSION['answers']);
        unset($_SESSION['time']);
        unset($_SESSION['start_test']);
        unset($_SESSION['end_test']);
        header("Location: review.php");
    } else {
        session_unset();
        header("Location: login.php");
    }
}else{
    session_unset();
    header("Location: login.php");
}