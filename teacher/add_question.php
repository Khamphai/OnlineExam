<?php
session_start();
if (empty($_SESSION['cat_id']) || empty($_SESSION['sub_id'])) {
    header('Location: question.php');
}
include_once '../process/connector.php';
$category_id = $_SESSION['cat_id'];
$subject_id = $_SESSION['sub_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['Clicked'] == 'add_qt'){
        echo "Add question";
    }else if ($_POST['Clicked'] == 'search') {
        echo "Search";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Add Question</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="../assets/plugins/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/plugins/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="../assets/plugins/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="../assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../assets/css/skins/skin-green.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-green sidebar-mini">
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
                <li><a href="index.php"><i class="fa fa-link"></i> <span>Monitor</span></a></li>
                <li><a href="category.php"><i class="fa fa-link"></i> <span>Category</span></a></li>
                <li><a href="subject.php"><i class="fa fa-link"></i> <span>Subject</span></a></li>
                <li class="active"><a href="#"><i class="fa fa-link"></i> <span>Question</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Question
                <small>Teacher</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Add question</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->

            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="question.php" class="btn btn-lg bg-orange-active pull-left"><i class="fa fa-chevron-circle-left"></i>&nbsp; Back</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-success">
                <div class="box-header with-border">
                    <h4 class="box-title"> Add Question for Examination</h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">

                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
            <!-- /.box -->

        </section>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">PHP - Online Exam</div>
        <strong>Copyright &copy; 2021 <a href="#">SWG9</a>.</strong> All rights reserved.
    </footer>
</div>

<script src="../assets/plugins/jquery/dist/jquery.min.js"></script>
<script src="../assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../assets/plugins/select2/dist/js/select2.full.min.js"></script>
<script src="../assets/js/adminlte.min.js"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    })
</script>
<script>
    $(document).ready(function() {
        $('#category-dropdown').on('change', function() {
            let category_id = this.value;
            $.ajax({
                url: "subject-by-category.php",
                type: "POST",
                data: {
                    category_id: category_id
                },
                cache: false,
                success: function(result){
                    $("#subject-dropdown").prop('disabled', false)
                    $("#subject-dropdown").html(result);
                }
            });
        });
        $('#subject-dropdown').on('change', function() {
            let subject_id = this.value;
            if (subject_id !== "") {
                $("#search").prop('disabled', false)
                $("#add_qt").prop('disabled', false)
            }else{
                $("#search").prop('disabled', true)
                $("#add_qt").prop('disabled', true)
            }
        });
    });
    $(document).ready(function()
    {
        $('.clickBtn').click(function()
        {
            let ButtonID = $(this).attr('id');
            $('#Clicked').val(ButtonID);
        });
    });
</script>
</body>
</html>