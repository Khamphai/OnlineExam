<?php
session_start();
if ($_SESSION['state'] === 'login') {
    if ($_SESSION['role'] === 'student') {
        header("Location: student/index.php");
    } else if ($_SESSION['role'] === 'teacher') {
        header("Location: teacher/index.php");
    } else if ($_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
    } else{
        session_unset();
        header("Location: login.php");
    }
}else{
    header("Location: login.php");
}
