<?php
session_start();
if (empty($_SESSION['category_id']) || empty($_SESSION['subject_id']) || empty($_SESSION['answers'])) {
    header('Location: index.php');
}
include_once 'connector.php';
$cat_id = $_SESSION['category_id'];
$sub_id = $_SESSION['subject_id'];
$answers = $_SESSION['answers'];
$action = $_SESSION['action'];

$time_use = $_SESSION['time']; // second e.g 300 sec
$give_second = $_SESSION['give_minute'] * 60; // minute e.g 5 minute = 300 sec
if ($action !== "submit") {
    $test_minute = $give_second;
}if ($give_second == $time_use) {
    $test_minute = $give_second;
}else {
    $test_minute = $give_second - $time_use;
}

$state = true;

// TODO :: Insert to tb_test_result
$ts_date = date("Y-m-d");
$user_id = $_SESSION['user_id'];
$sql = "INSERT INTO `tb_test_result` (`test_id`, `test_date`, `test_minute`, `user_id`, `sub_id`) VALUES (NULL, CAST('". $ts_date ."' AS DATE), $test_minute, $user_id, $sub_id)";
if (mysqli_query($link, $sql)) {
    $last_test_id = mysqli_insert_id($link);
    $_SESSION['test_id'] = $last_test_id;
} else {
    $state = false;
    echo "Error: " . $sql . "<br>" . mysqli_error($link);
}
// TODO :: Loop Insert to tb_score
$sql = "INSERT INTO `tb_score` (`sc_id`, `sc_choice`, `sc_answer`, `sc_judge`, `test_id`, `q_id`) VALUES ";
$counts = sizeof($answers);
foreach ($answers as $key => $value){
    $sc_choice = $answers[$key]['ans_sel'];
    $sc_answer = $answers[$key]['ans_col'];
    $q_id = $answers[$key]['q_id'];
    if ($sc_choice === $sc_answer) {
        $sc_judge = "Good";
    }else{
        $sc_judge = "N.G";
    }

    $value = "(NULL, '$sc_choice', '$sc_answer', '$sc_judge', $last_test_id, $q_id)";
    $sql .= $counts > 1 ? $value . "," : $value;
    $counts -= 1;
}
if (!mysqli_query($link, $sql)) {
    $state = false;
    echo "Error: " . $sql . "<br>" . mysqli_error($link);
}
mysqli_close($link);
if ($state) {
    header('Location: ../student/test_result.php');
}