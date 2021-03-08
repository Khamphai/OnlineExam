<?php
session_start();
include_once '../process/process_check_authorize.php';
include_once '../process/connector.php';
$user_id = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Subject</title>
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
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">list subject</li>
            </ol>
            <a href="add-subject.php" class="btn btn-lg bg-blue" style="margin-top: 10px;">
                <i class="fa fa-plus-circle"></i> &nbsp; Add Subject
            </a>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Subject Items</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-gray">
                            <th style="width: 10px">No.</th>
                            <th>Subject Title</th>
                            <th>Description</th>
                            <th>Level</th>
                            <th>Give Minute</th>
                            <th>Pass %</th>
                            <th>Use Count</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tb_subjects";
                            $result = mysqli_query($link, $sql);
                            $no = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?=@++$no?></td>
                                    <td><?=htmlspecialchars($row['title'])?></td>
                                    <td><?=htmlspecialchars($row['description'])?></td>
                                    <td><?=htmlspecialchars($row['level'])?></td>
                                    <td><?=htmlspecialchars($row['give_minute'])?></td>
                                    <td><?=htmlspecialchars($row['pass_percent'])?></td>
                                    <td><?=htmlspecialchars($row['use_count'])?></td>
                                    <td><?=htmlspecialchars($row['status'])?></td>
                                    <td>
                                        <a href="edit-subject.php?sub_id=<?=htmlspecialchars($row['sub_id'])?>"><span class="btn bg-yellow">Edit</span></a>
                                        <a href="delete-subject.php?sub_id=<?=htmlspecialchars($row['sub_id'])?>"><span class="btn bg-red" onclick="return confirm('Are you sure?')">Delete</span></a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
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