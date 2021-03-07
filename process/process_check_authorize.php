<?php
session_start();
if (empty($_SESSION['user_id'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
}
