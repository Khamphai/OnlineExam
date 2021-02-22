<?php
session_start();
include_once 'process/connector.php';

// check state
if ($_SESSION['state'] === 'login') {
    header("Location: index.php");
}

$msg = "";
$email = @$_POST['email'];
$password = @$_POST['password'];
$remember = @$_POST['remember'];
if (isset($_POST['submit'])) {

    if(empty(trim($_POST["email"]))){
        $msg = "Please enter your email.";
    }else if(empty(trim($_POST["password"]))){
        $msg = "Please enter your password.";
    } else {
        $email = stripcslashes($email);
        $password = stripcslashes($password);
        $email = mysqli_real_escape_string($link, $email);
        $password = mysqli_real_escape_string($link, $password);

        $sql = "SELECT * FROM `tb_users` WHERE `email` = '$email' AND password = '$password'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);

        if($count == 1){

            if ($row['status'] != 1) {
                $msg = "Your user has been disabled";
            }else {
                if ($row['role'] === 'student') {
                    $_SESSION['state'] = "login";
                    $_SESSION['full_name'] = $row['fullname'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['role'] = $row['role'];
                    header("Location: student/index.php");
                } else if ($row['role'] === 'teacher') {
                    $_SESSION['state'] = "login";
                    $_SESSION['full_name'] = $row['fullname'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['role'] = $row['role'];
                    header("Location: teacher/index.php");
                } else if ($row['role'] === 'admin') {
                    $_SESSION['state'] = "login";
                    $_SESSION['full_name'] = $row['fullname'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['role'] = $row['role'];
                    header("Location: admin/index.php");
                } else{
                    $msg = "Invalid Role user";
                }
            }
        } else{
            $msg = "Login failed. Invalid username or password";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Exam | Log in</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="assets/plugins/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/plugins/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="assets/plugins/iCheck/all.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Exam</b> Online</a>
  </div>
    <?php
    if ($msg != "") { ?>
        <div class="alert alert-warning alert-dismissible" id="success-alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-warning"></i> Alert!</h4>
            <?= $msg ?>
        </div>
    <?php } ?>
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="login.php" method="post">
      <div class="form-group has-feedback">
        <input type="email" name="email" value="<?=$email?>"  class="form-control" placeholder="Email" required>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
                <input type="checkbox" name="remember" value="remember" class="flat-red" <?php if ($remember == 'remember') echo 'checked'; ?>> &nbsp; Remember Me
            </label>
          </div>
        </div>
        <div class="col-xs-4">
          <input type="submit" name="submit" class="btn bg-green btn-block" value="Sign In">
        </div>
      </div>
    </form>
      
    <a href="register.php" class="text-center text-green">Register a new membership</a>
  </div>
</div>

<script src="assets/plugins/jquery/dist/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
      $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
      });

      $("#success-alert").fadeTo(5000, 500).slideUp(500, function () {
          $("#success-alert").slideUp(500);
      });
  });
</script>
</body>
</html>
