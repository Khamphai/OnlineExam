<?php
session_start();
include_once '../process/process_check_authorize.php';
include_once '../process/connector.php';
$user_id = $_SESSION['user_id'];

// INSERT
$msg = "";
$statusSuccess = false;
$sub_id = $title = $description = $use_count = $level = "";
$give_minute = $pass_percent = $cat_id = $status = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sub_id = @$_POST['sub_id'];
    $title = @$_POST['title'];
    $description = @$_POST['description'];
    $use_count = @$_POST['use_count'];
    $level = @$_POST['level'];
    $give_minute = @$_POST['give_minute'];
    $pass_percent = @$_POST['pass_percent'];
    $cat_id = @$_POST['cat_id'];
    $status = @$_POST['status'];

    if (empty($sub_id)) {
        header("Location: subject.php");
    } else if (empty($title)) {
        $msg = "Please enter subject title";
    } else if (empty($description)) {
        $msg = "Please enter description";
    } else if (empty($use_count)) {
        $msg = "Please enter use count";
    } else if (empty($level)) {
        $msg = "Please choose level";
    } else if (empty($give_minute)) {
        $msg = "Please enter give minute";
    } else if (empty($pass_percent)) {
        $msg = "Please enter pass percent";
    } else if (empty($cat_id)) {
        $msg = "Please select category";
    } else {

        empty($status) ? $status = 0 : $status = 1;
        $sql = "UPDATE `tb_subjects` SET `title`='$title', `description`='$description', `level`=$level, `give_minute`=$give_minute, `pass_percent`=$pass_percent, `use_count`=$use_count, `status`=$status, `cat_id`=$cat_id, `teacher_id`=$user_id WHERE sub_id=$sub_id";
        if (!mysqli_query($link, $sql)) {
            $msg = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        } else {
            $sub_id = $title = $description = $use_count = $level = "";
            $give_minute = $pass_percent = $cat_id = $status = "";
            $statusSuccess = true;
        }
        mysqli_close($link);
    }
} else {
    $sub_id = @$_GET['sub_id'];
    $sql = "SELECT * FROM `tb_subjects` WHERE `sub_id`=$sub_id AND `teacher_id`=$user_id";
    $result = mysqli_query($link, $sql);
    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count_edit = mysqli_num_rows($result);
    if ($count_edit <= 0) {
        header('Location: subject.php');
    } else {
        $sub_id = $data['sub_id'];
        $title = $data['title'];
        $description = $data['description'];
        $use_count = $data['use_count'];
        $level = $data['level'];
        $give_minute = $data['give_minute'];
        $pass_percent = $data['pass_percent'];
        $cat_id = $data['cat_id'];
        $status = $data['status'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Edit Subject</title>
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
                <li><a href="index.php"><i class="fa fa-bar-chart"></i> <span>Monitor</span></a></li>
                <li><a href="category.php"><i class="fa fa-folder"></i> <span>Category</span></a></li>
                <li class="active"><a href="#"><i class="fa fa-folder-open"></i> <span>Subject</span></a></li>
                <li><a href="question.php"><i class="fa fa-plus-circle"></i> <span>Question</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Subject
                <small>Teacher</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
                <li class="active">edit subject</li>
            </ol>
            <a href="subject.php" class="btn bg-orange-active" style="margin-top: 10px;">
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

            <!--------------------------
              | Your Page Content Here |
              -------------------------->

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="hidden" value="<?=htmlspecialchars($sub_id); ?>" name="sub_id"/>
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h4 class="box-title">Edit your subject</h4>
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
                                    <label for="name">Subject Title</label>
                                    <input type="text" class="form-control" name="title"
                                           value="<?=htmlspecialchars($title)?>"
                                           placeholder="Enter Subject Title">
                                </div>
                                <div class="form-group">
                                    <label for="name">Description</label>
                                    <input type="text" class="form-control" name="description"
                                           value="<?=htmlspecialchars($description)?>"
                                           placeholder="Enter Description">
                                </div>
                                <div class="form-group">
                                    <label for="name">Use Count</label>
                                    <input type="number" class="form-control" name="use_count"
                                           value="<?=htmlspecialchars($use_count)?>"
                                           placeholder="Enter Use Count">
                                </div>
                                <div class="form-group">
                                    <label>Level</label><br/>
                                    <input type="radio" name="level" class="flat-red"
                                           value="1" <?php if (strpos($level, '1') !== false) echo 'checked'; ?>>&nbsp;<span
                                            class="text-center badge bg-green">Easiest</span>&nbsp;&nbsp;
                                    <input type="radio" name="level" class="flat-red"
                                           value="2" <?php if (strpos($level, '2') !== false) echo 'checked'; ?>>&nbsp;<span
                                            class="text-center badge bg-blue">Normal</span>&nbsp;&nbsp;
                                    <input type="radio" name="level" class="flat-red"
                                           value="3" <?php if (strpos($level, '3') !== false) echo 'checked'; ?>>&nbsp;<span
                                            class="text-center badge bg-orange">Difficult</span>&nbsp;&nbsp;
                                    <input type="radio" name="level" class="flat-red"
                                           value="4" <?php if (strpos($level, '4') !== false) echo 'checked'; ?>>&nbsp;<span
                                            class="text-center badge bg-red">Most Difficult</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Give Minute</label>
                                    <input type="number" class="form-control" name="give_minute"
                                           value="<?=htmlspecialchars($give_minute)?>"
                                           placeholder="Enter Give Minute">
                                </div>
                                <div class="form-group">
                                    <label for="name">Pass Percent</label>
                                    <input type="number" class="form-control" name="pass_percent"
                                           value="<?=htmlspecialchars($pass_percent)?>"
                                           placeholder="Enter Pass Percent">
                                </div>
                                <div class="form-group">
                                    <label for="name">Category</label>
                                    <select name="cat_id" class="form-control select2">
                                        <option value="">Select Category</option>
                                        <?php
                                        $sql = "select cat_id, name from tb_category";
                                        $result = mysqli_query($link, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <option value="<?= $row['cat_id']; ?>" <?php if (@$cat_id === $row['cat_id']) echo "selected"; ?>><?= $row['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label><br/>
                                    <input type="checkbox" name="status" class="flat-red" value="1"
                                           id="status" <?php if (strpos($status, '1') !== false) echo 'checked'; ?>>&nbsp;&nbsp;
                                    Available to Test
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box -->

                <div class="box box-solid">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="pull-left"
                                      style="font-size: xx-large">Click <b>[Edit]</b> to update Subject</span>
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
            $("#modal-success-edit-subject").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-success-edit-subject" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-green" style="font-size: 70px">
                        <i class="fa fa-check-circle"></i>
                    </p>
                    <h3 class="text-info">Edit Subject Successfully</h3>
                    <br/><br/>
                    <a href="subject.php" class="btn bg-gray-active">
                        Go to list subject <i class="fa fa-chevron-circle-right"></i>
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