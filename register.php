<?php
session_start();
session_unset();
include_once 'process/connector.php';
$msg = "";
$status = false;
$full_name = @$_POST['full_name'];
$terms = @$_POST['terms'];
$email = @$_POST['email'];
$passwd = @$_POST['passwd'];
$re_passwd = @$_POST['re_passwd'];
if (isset($_POST['submit'])) {
    if (empty($full_name) || empty($terms) || empty($email) || empty($passwd) || empty($re_passwd)) {
        $msg = "Input form must not be empty";
    }else if ($passwd !== $re_passwd) {
        $msg = "Your input password mismatch";
    }else{
        $em = mysqli_real_escape_string($link, $email);
        $ps = mysqli_real_escape_string($link, $passwd);
        $fn = mysqli_real_escape_string($link, $full_name);
        $sql = "INSERT INTO `tb_users` (`user_id`, `email`, `password`, `fullname`, `role`, `status`)
            VALUES (NULL, '" . $em . "', '" . $ps . "', '" . $fn . "', 'student', 1)";
        if (mysqli_query($link, $sql)) {
            $status = true;
        } else {
            $msg = "Error: " . $sql . "<br>" . mysqli_error($link);
        }
        mysqli_error($link);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Register</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="assets/plugins/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/plugins/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="assets/plugins/iCheck/all.css">
    <link rel="stylesheet" href="assets/css/exam.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
    <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="form-group has-feedback">
                <input type="text" name="full_name" class="form-control" value="<?=$full_name?>" placeholder="Full name" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="email" name="email" class="form-control" value="<?=$email?>" placeholder="Email" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="passwd" class="form-control" placeholder="Password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="re_passwd" class="form-control" placeholder="Retype password" required>
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="terms" class="flat-red" value="accepted" required <?php if ($terms == 'accepted') echo 'checked'; ?>> &nbsp; I agree to the <a
                                    href="#" class="text-center text-green">terms</a>
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <input type="submit" class="btn bg-green btn-block" name="submit" value="Register"/>
                </div>
            </div>
        </form>
        <a href="login.php" class="text-center text-green">I already have a membership</a>
    </div>

    <?php include_once 'loading.php'; ?>
</div>

<script src="assets/plugins/jquery/dist/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="assets/plugins/iCheck/icheck.min.js"></script>
<?php
if ($status) {
?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#modal-success-register").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-success-register" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-green" style="font-size: 70px">
                        <i class="fa fa-check-circle"></i>
                    </p>
                    <h3 class="text-info">Congrats! Register Successfully</h3>
                    <br/><br/>
                    <a href="login.php" class="btn bg-gray-active">
                        Go to Login <i class="fa fa-chevron-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.loading').show();
        $('.overlay').show();
        setTimeout("callback()", 300);
    });

    function callback() {
        $('.loading').hide();
        $('.overlay').hide();
    }
</script>
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
