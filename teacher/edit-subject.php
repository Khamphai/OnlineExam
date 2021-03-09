<?php
include_once '../process/connector.php';

$sub_id = @$_GET['sub_id'];

// Edit
if (isset($_POST['submit'])) {

    $sub_id = $_POST['sub_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $level = $_POST['level'];
    $give_minute = $_POST['give_minute'];
    $pass_percent = $_POST['pass_percent'];
    $use_count = $_POST['use_count'];
    $status = $_POST['status'];
    $cat_id = $_POST['cat_id'];
    $teacher_id = $_POST['teacher_id'];


    if (!empty($sub_id) && !empty($title) && !empty($description) && !empty($level) && !empty($give_minute) && !empty($pass_percent) && !empty($use_count) && !empty($status) && !empty($cat_id) && !empty($teacher_id)) {

        $sql = "UPDATE `tb_subjects` SET title='$title', description='$description', level='$level', give_minute='$give_minute', pass_percent='$pass_percent', use_count='$use_count', status='$status', cat_id='$cat_id', teacher='$teacher_id' WHERE sub_id=$sub_id";
        if (!mysqli_query($link, $sql)){
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }else{
            header('Location: category.php');
        }
        mysqli_close($link);
    } else {
        echo "some field is empty";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Edit-Subject</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../assets/plugins/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/plugins/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="../assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../assets/css/skins/skin-green.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

    <header class="main-header">

        <a href="index.php" class="logo">
            <span class="logo-mini"><b>E</b>xam</span>
            <span class="logo-lg"><b>Exam</b> Online</span>
        </a>

        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 10 notifications</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>

                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../assets/img/user2-160x160.jpg" class="user-image" alt="User Image">
                            <span class="hidden-xs">Khouthnarin MNV</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="../assets/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                                <p>
                                    Khouthnarin MNV - SWG9
                                    <small>Member since Feb. 2021</small>
                                </p>
                            </li>
                            <li class="user-body">
                                <div class="row">

                                </div>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Log off</a>
                                </div>
                                <div class="pull-right">
                                    <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">

        <section class="sidebar">

            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../assets/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>Khouthnarin MNV</p>
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
                <li><a href="index.php"><i class="fa fa-desktop"></i> <span>Monitor</span></a></li>
                <li><a href="category.php"><i class="fa fa-columns"></i> <span>Category</span></a></li>
                <li><a href="subject.php"><i class="fa fa-clipboard"></i> <span>Subject</span></a></li>
                <li><a href="question.php"><i class="fa fa-list-alt"></i> <span>Question</span></a></li>
            </ul>

        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Edit-Subject
                <small>Teacher</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-clipboard"></i>Subject</a></li>
                <li class="active">Edit</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">

                        <!-- /.box-header -->

                        <!-- form start -->
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                            <input type="hidden" value="<?=htmlspecialchars($sub_id);?>" name="sub_id"/>
                            <?php
                            $sql = "SELECT * FROM tb_subjects WHERE sub_id=$sub_id";
                            $result = mysqli_query($link, $sql);
                            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            ?>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="name">Subject Title</label>
                                    <input type="text" class="form-control" name="title"
                                           value="<?=htmlspecialchars($data['title']);?>"
                                           placeholder="Enter Subject Title">
                                </div>
                                <div class="form-group">
                                    <label for="name">Description</label>
                                    <input type="text" class="form-control" name="description"
                                           value="<?=htmlspecialchars($data['description']);?>"
                                           placeholder="Enter Description">
                                </div>
                                <div class="form-group">
                                    <label for="name">Level</label>
                                    <input type="number" class="form-control" name="level"
                                           value="<?=htmlspecialchars($data['level']);?>"
                                           placeholder="Enter Level">
                                </div>
                                <div class="form-group">
                                    <label for="name">Give Minute</label>
                                    <input type="number" class="form-control" name="give_minute"
                                           value="<?=htmlspecialchars($data['give_minute']);?>"
                                           placeholder="Enter Give Minute">
                                </div>
                                <div class="form-group">
                                    <label for="name">Pass Percent%</label>
                                    <input type="number" class="form-control" name="pass_percent"
                                           value="<?=htmlspecialchars($data['pass_percent']);?>"
                                           placeholder="Enter Pass Percent%">
                                </div>
                                <div class="form-group">
                                    <label for="name">Use Count</label>
                                    <input type="number" class="form-control" name="use_count"
                                           value="<?=htmlspecialchars($data['use_count']);?>"
                                           placeholder="Enter Use Count">
                                </div>
                                <div class="form-group">
                                    <label for="name">Status</label>
                                    <input type="number" class="form-control" name="status"
                                           value="<?=htmlspecialchars($data['status']);?>"
                                           placeholder="Enter Status">
                                </div>

                                <label for="name">Category</label>
                                <select name="cat_id" class="form-control select2">
                                    <option>Select Category</option>
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

                                <div class="form-group">
                                    <label for="name">Teacher</label>
                                    <input type="number" class="form-control" name="teacher_id"
                                           value="<?=htmlspecialchars($data['teacher_id']);?>"
                                           placeholder="2">
                                </div>

                                <div class="box-footer">
                                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                                </div>
                        </form>
                    </div>
                    <!-- /.box -->

                    <!-- Form Element sizes -->

                </div>
                <!--/.col (left) -->
                <!-- right column -->

            </div>
            <!-- /.row -->


        </section>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">PHP - Online Exam</div>
        <strong>Copyright &copy; 2021 <a href="#">SWG9</a>.</strong> All rights reserved.
    </footer>
</div>

<script src="../assets/plugins/jquery/dist/jquery.min.js"></script>
<script src="../assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../assets/js/adminlte.min.js"></script>
</body>
</html>