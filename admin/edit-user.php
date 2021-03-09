<?php
session_start();
include_once '../process/process_check_authorize.php';
include_once '../process/connector.php';

// INSERT
$msg = "";
$statusSuccess = false;
$user_id = $full_name = $email = $passwd = $role = $status = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_POST['user_id'];
    $full_name = @$_POST['full_name'];
    $email = @$_POST['email'];
    $passwd = @$_POST['passwd'];
    $role = @$_POST['role'];
    $status = @$_POST['status'];

    if (empty($user_id)) {
        header("Location: index.php");
    } else if (empty($full_name)) {
        $msg = "Please enter your full name.";
    } else if (strlen($full_name) <= 5) {
        $msg = "Your full name must be more than 5 chars.";
    } else if (strlen($full_name) >= 50) {
        $msg = "Your full name must be less than 50 chars.";
    } else if (empty(trim($email))) {
        $msg = "Please enter your email.";
    } else if (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", trim($email))) {
        $msg = "Your email incorrect format.";
    } else if (!empty(trim($passwd)) && !preg_match("/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{4,8}$/", trim($passwd))) {
        $msg = "The password should be include 4-8 char and numeric.";
    } else if (empty(trim($role))) {
        $msg = "Please select role of user.";
    } else {

        $user_id = stripcslashes($user_id);
        $full_name = stripcslashes($full_name);
        $email = stripcslashes($email);
        $passwd = stripcslashes($passwd);
        $role = stripcslashes($role);
        $status = stripcslashes($status);
        $id = mysqli_real_escape_string($link, $user_id);
        $fn = mysqli_real_escape_string($link, $full_name);
        $em = mysqli_real_escape_string($link, $email);
        $ro = mysqli_real_escape_string($link, $role);
        $st = mysqli_real_escape_string($link, $status);
        $ps = mysqli_real_escape_string($link, $passwd);
        $ps = md5($ps); // encrypt to md5 algorithm

        empty($st) ? $st = 0 : $st = 1;

        // check id of user
        $sql = "SELECT * FROM `tb_users` WHERE `user_id`=$id";
        $result = mysqli_query($link, $sql);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            if (empty($passwd)) {
                $sql = "UPDATE `tb_users` SET `email`='$em', `fullname`='$fn', `role`='$ro', `status`=$st, `updated_at`=now() WHERE `user_id`=$id";
            } else {
                $sql = "UPDATE `tb_users` SET `email`='$em', `password`='$ps', `fullname`='$fn', `role`='$ro', `status`=$st, `updated_at`=now() WHERE `user_id`=$id";
            }
            if (!mysqli_query($link, $sql)) {
                $msg = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
            } else {
                $user_id = $full_name = $email = $passwd = $role = $status = "";
                $statusSuccess = true;
            }
        }else{
            $msg = "Error to update user!";
        }
        mysqli_close($link);
    }
}else {
    $user_id = @$_GET['user_id'];
    $sql = "SELECT * FROM `tb_users` WHERE user_id=$user_id";
    $result = mysqli_query($link, $sql);
    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count_edit = mysqli_num_rows($result);
    if ($count_edit <= 0) {
        header('Location: index.php');
    } else {
        $user_id = $data['user_id'];
        $full_name = $data['fullname'];
        $email = $data['email'];
        $role = $data['role'];
        $status = $data['status'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Edit User</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../assets/plugins/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/plugins/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="../assets/plugins/iCheck/all.css">
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
                <li class="active"><a href="index.php"><i class="fa fa-user-circle"></i> <span>Users</span></a></li>
                <li><a href="add-user.php"><i class="fa fa-plus-circle"></i> <span>Add User</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                User
                <small>Administrator</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">edit user</li>
            </ol>
            <a href="index.php" class="btn bg-orange-active" style="margin-top: 10px;">
                <i class="fa fa-chevron-circle-left"></i>&nbsp; Back
            </a>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <?php
            if ($msg != "") { ?>
                <div class="alert alert-warning alert-dismissible" id="success-alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                    <?= $msg ?>
                </div>
            <?php } ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="hidden" value="<?=htmlspecialchars($user_id); ?>" name="user_id"/>
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit User</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control input-lg" value="<?=htmlspecialchars($full_name)?>" name="full_name" placeholder="Enter Full Name">
                                </div>
                                <div class="form-group">
                                    <label>E-Mail</label>
                                    <input type="text" class="form-control input-lg" value="<?=htmlspecialchars($email)?>" name="email" placeholder="Enter E-Mail">
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label><br/>
                                    <input type="checkbox" name="status" class="flat-red" value="1"
                                           id="status" <?php if (strpos($status, '1') !== false) echo 'checked'; ?>>&nbsp;&nbsp;
                                    Available to login
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control input-lg" value="<?=htmlspecialchars($passwd)?>" name="passwd" placeholder="Enter Password">
                                </div>
                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" class="form-control input-lg">
                                        <option value="">Select role of user</option>
                                        <option value="admin" <?php if ($role == 'admin') echo 'selected';?>>Admin</option>
                                        <option value="teacher" <?php if ($role == 'teacher') echo 'selected';?>>Teacher</option>
                                        <option value="student" <?php if ($role == 'student') echo 'selected';?>>Student</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box box-solid">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="pull-left"
                                      style="font-size: xx-large">Click <b>[Edit]</b> to update User</span>
                                <button type="submit" class="btn btn-lg bg-green pull-right">
                                    <i class="fa fa-check-circle"></i>&nbsp; Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
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
<script src="../assets/plugins/iCheck/icheck.min.js"></script>
<script src="../assets/js/adminlte.min.js"></script>
<?php
if ($statusSuccess) {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#modal-success-edit-user").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-success-edit-user" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-green" style="font-size: 70px">
                        <i class="fa fa-check-circle"></i>
                    </p>
                    <h3 class="text-info">Edit User Successfully</h3>
                    <br/><br/>
                    <a href="index.php" class="btn bg-gray-active">
                        Go to list user <i class="fa fa-chevron-circle-right"></i>
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
        setTimeout("callback()", 600);
    });

    function callback() {
        $('.loading').hide();
        $('.overlay').hide();
    }
</script>
<script type="text/javascript">
    $(function () {
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });

        $("#success-alert").fadeTo(7000, 500).slideUp(500, function () {
            $("#success-alert").slideUp(500);
        });
    });
</script>
</body>
</html>