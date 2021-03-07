<?php
session_start();
include_once '../process/process_check_authorize.php';
include_once '../process/connector.php';
unset($_SESSION['category_id']);
unset($_SESSION['subject_id']);
unset($_SESSION['question']);
unset($_SESSION['q_count']);
unset($_SESSION['give_minute']);
unset($_SESSION['action']);
unset($_SESSION['answers']);
unset($_SESSION['time']);
unset($_SESSION['start_test']);
unset($_SESSION['end_test']);
$cat_id = @$_GET['id'];
if (!empty($cat_id)) {
    $_SESSION['category_id'] = $cat_id;
    header('Location: choose_subject.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Student</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../assets/plugins/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/plugins/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="../assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../assets/css/skins/skin-green.min.css">
    <link rel="stylesheet" href="../assets/css/exam.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
                    <p><?=htmlspecialchars($_SESSION['full_name'])?></p>
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
                <li class="active"><a href="#"><i class="fa fa-cube"></i> <span>Exam</span></a></li>
                <li><a href="index.php"><i class="fa fa-list-alt"></i> <span>Review</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Exam
                <small>Test System</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Category</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="pull-left text-light-blue" style="font-size: x-large">&nbsp;<i class="fa fa-check-circle"></i>&nbsp; Choose Category for start Testing</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php
                $sql = "SELECT CAT_ID AS ID, NAME AS CAT_NAME, DESCRIPTION AS CAT_DESC FROM TB_CATEGORY";
                $result = mysqli_query($link, $sql);
                $no = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="info-box">
                            <a href="choose_category.php?id=<?=$row['ID']?>">
                        <span class="info-box-icon bg-green">
                                <i class="fa fa-check-circle"></i>
                        </span>
                            </a>

                            <div class="info-box-content">
                                <span class="info-box-number"><?=htmlspecialchars($row['CAT_NAME'])?></span>
                                <span class="info-box-more"><?=htmlspecialchars($row['CAT_DESC'])?></span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <?php
                }
                mysqli_close($link);
                ?>
            </div>
            <!-- /.row -->
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
</body>
</html>