<?php

// Initialize
$host = 'localhost';
$user = 'root';
$pass = 'root';
$db = 'ONLINE_EXAM_DB';
$port = 8889;

// Connect to MySQL database
$link = mysqli_connect(
    $host, $user, $pass, $db, $port
) or die("Can't connect to database");

// Character set to `UTF-8`
mysqli_set_charset($link, "UTF-8");
