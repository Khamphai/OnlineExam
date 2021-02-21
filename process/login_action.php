<?php
session_start();

$email = @$_POST['email'];
$password = @$_POST['password'];
$remember = @$_POST['remember'];

if(!empty($email) && !empty($password))
{

}

echo $email;
echo $password;