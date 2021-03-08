<?php
session_start();
include_once '../process/process_check_authorize.php';
include_once '../process/connector.php';
$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];
$email = $_SESSION['email'];
$old_passwd = $new_passwd = "";

$msg = "";
$statusSuccess = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $old_passwd = $new_passwd = "";
    $full_name = @$_POST['full_name'];
    $old_passwd = @$_POST['old_passwd'];
    $new_passwd = @$_POST['new_passwd'];
    if (empty($full_name)) {
        $msg = "Please enter your full name.";
    } else if (strlen($full_name) <= 5) {
        $msg = "Your full name must be more than 5 chars.";
    } else if (strlen($full_name) >= 50) {
        $msg = "Your full name must be less than 50 chars.";
    } else if (empty(trim($old_passwd))) {
        $msg = "Please enter your old password.";
    } else if (empty(trim($new_passwd))) {
        $msg = "Please enter your new password.";
    } else if (!preg_match("/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{4,8}$/", trim($new_passwd))) {
        $msg = "The new password should be include 4-8 char and numeric.";
    } else if (trim($old_passwd) == trim($new_passwd)) {
        $msg = "The old password and new password should be same.";
    } else {
        $full_name = stripcslashes($full_name);
        $old_passwd = stripcslashes($old_passwd);
        $new_passwd = stripcslashes($new_passwd);
        $ops = mysqli_real_escape_string($link, $old_passwd);
        $nps = mysqli_real_escape_string($link, $new_passwd);
        $fn = mysqli_real_escape_string($link, $full_name);
        $ops = md5($ops); // encrypt old password to md5 algorithm
        $nps = md5($nps); // encrypt new password to md5 algorithm

        $sql = "SELECT * FROM `tb_users` WHERE `user_id`=$user_id AND `email`='$email' AND password='$ops'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            $sql = "UPDATE `tb_users` SET `fullname`='$full_name', `password`='$nps' WHERE `user_id`=$user_id";
            if (mysqli_query($link, $sql)) {
                $statusSuccess = true;
                $_SESSION['full_name'] = $full_name;
                $old_passwd = $new_passwd = "";
            } else {
                $msg = "Error: " . $sql . "<br>" . mysqli_error($link);
            }
        }else{
            $msg = "Please check your enter old password";
        }
        mysqli_close($link);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Profile</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../assets/plugins/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/plugins/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="../assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../assets/css/skins/skin-green.min.css">
    <link rel="stylesheet" href="../assets/css/exam.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-green fixed sidebar-mini">
<div class="wrapper">

    <?php include_once 'header.php'; ?>
    <aside class="main-sidebar">

        <section class="sidebar">

            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../assets/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p><?= htmlspecialchars($_SESSION['full_name']) ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
                </div>
            </form>

            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">HEADER</li>
                <li><a href="index.php"><i class="fa fa-bar-chart"></i> <span>Monitor</span></a></li>
                <li><a href="category.php"><i class="fa fa-folder"></i> <span>Category</span></a></li>
                <li><a href="subject.php"><i class="fa fa-folder-open"></i> <span>Subject</span></a></li>
                <li><a href="question.php"><i class="fa fa-plus-circle"></i> <span>Question</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header container">
            <h1>
                Profile
                <small>Teacher</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">show profile</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->

            <?php
            if ($msg != "") { ?>
                <div class="alert alert-warning alert-dismissible" id="success-alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                    <?= $msg ?>
                </div>
            <?php } ?>

            <div class="box box-success">
                <div class="box-header with-border">
                    <h4 class="box-title">Your user profile</h4>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label>Full Name</label>
                                    <input type="text" name="full_name" class="form-control input-lg" value="<?=$full_name?>" placeholder="Enter Full name">
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control input-lg" value="<?=$email?>" disabled>
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label>Old Password</label>
                                    <input type="password" name="old_passwd" value="<?=$old_passwd?>" class="form-control input-lg" placeholder="Enter Old Password">
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <label>New Password</label>
                                    <input type="password" name="new_passwd" value="<?=$new_passwd?>" class="form-control input-lg" placeholder="Enter New password">
                                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-lg bg-green pull-right">
                                    <i class="fa fa-check-circle"></i>&nbsp; Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.box -->

        </section>

        <?php include_once '../loading.php'; ?>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">PHP - Online Exam</div>
        <strong>Copyright &copy; 2021 <a href="#">SWG9</a>.</strong> All rights reserved.
    </footer>
</div>

<script src="../assets/plugins/jquery/dist/jquery.min.js"></script>
<script src="../assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../assets/js/adminlte.min.js"></script>
<?php
if ($statusSuccess) {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#modal-success-update-profile").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-success-update-profile" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-green" style="font-size: 70px">
                        <i class="fa fa-check-circle"></i>
                    </p>
                    <h3 class="text-info">Update Profile Successfully</h3>
                    <br/><br/>
                    <button type="button" data-dismiss="modal" class="btn bg-gray-active"><i class="fa fa-ban"></i>
                        Close
                    </button>
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
        setTimeout("callback()", 600);
    });

    function callback() {
        $('.loading').hide();
        $('.overlay').hide();
    }
</script>
<script type="text/javascript">
    $(function () {
        $("#success-alert").fadeTo(7000, 500).slideUp(500, function () {
            $("#success-alert").slideUp(500);
        });
    });
</script>
</body>
</html>
