<?php
session_start();
include_once '../process/process_check_authorize.php';
if (empty($_SESSION['category_id'])) {
    header('Location: choose_category.php');
}
include_once '../process/connector.php';
$cat_id = $_SESSION['category_id'];
$sql = "SELECT CAT_ID AS ID, NAME AS CAT_NAME, DESCRIPTION AS CAT_DESC FROM TB_CATEGORY WHERE CAT_ID = $cat_id";
$result = mysqli_query($link, $sql);
$category = mysqli_fetch_assoc($result);
$count = 0;

// Start testing
$msg = "";
$subId = @$_POST['subId'];
if (isset($_POST['submit'])) {
    if (empty($subId)) {
        $msg = "Please select subject before start testing...";
    } else {
        $_SESSION['subject_id'] = $subId;
        setcookie("refcoke", "", 1);
        header('Location: start_testing.php');
    }
}

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
    <link rel="stylesheet" href="../assets/plugins/iCheck/all.css">
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
                <li class="active">Subjects</li>
            </ol>
            <a href="choose_category.php" class="btn bg-orange" style="margin-top: 10px;">
                <i class="fa fa-chevron-circle-left"></i> Back
            </a>
        </section>

        <!-- Main content -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <section class="content container-fluid">

            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green">
                                <i class="fa fa-check-circle"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-number"><?=htmlspecialchars($category['CAT_NAME'])?></span>
                            <span class="info-box-more"><?=htmlspecialchars($category['CAT_DESC'])?></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            if ($msg != "") {
            ?>
            <div class="alert alert-warning alert-dismissible" id="success-alert">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                <?=$msg?>
            </div>
            <?php } ?>

            <div class="box box-success">
                <div class="box-header with-border">
                    <h4 class="box-title">Please make your choice ans press <b>[Start Testing...]</b> button to start the test</h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Subject Title</th>
                                <th>Description</th>
                                <th>Level</th>
                                <th>Pass %</th>
                                <th>Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "SELECT * FROM TB_SUBJECTS WHERE CAT_ID = $cat_id AND status=1";
                            $result = mysqli_query($link, $sql);
                            $count = mysqli_num_rows($result);
                            if ($count > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                                <td>
                                    <label>
                                        <input type="radio" name="subId" class="flat-red"  value="<?= $row['sub_id']; ?>">
                                    </label>
                                </td>
                                <td><?=htmlspecialchars($row['title'])?></td>
                                <td><?=htmlspecialchars($row['description'])?></td>
                                <td>
                                    <?php
                                    if ($row['level'] == 1) {
                                        echo "<span class=\"text-center badge bg-green\">Easiest</span>";
                                    }else if($row['level'] == 2){
                                        echo "<span class=\"text-center badge bg-blue\">Normal</span>";
                                    }else if($row['level'] == 3){
                                        echo "<span class=\"text-center badge bg-orange\">Difficult</span>";
                                    }else {
                                        echo "<span class=\"text-center badge bg-red\">Most Difficult</span>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <span class='text-blue text-bold'><?= htmlspecialchars($row['pass_percent']) ?> %</span>
                                </td>
                                <td>
                                    <span class="text-center badge bg-aqua-active">
                                        <?=htmlspecialchars($row['give_minute'])?> Minute
                                    </span>
                                </td>
                            </tr>
                            <?php
                            }
                            }else{
                            ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <p class="alert bg-danger" style="font-size: large; margin-top: 20px;" >
                                        This Category don't have subject
                                    </p>
                                </td>
                            </tr>
                            <?php
                            }
                            mysqli_close($link);
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
            <!-- /.box -->

            <?php if ($count > 0) { ?>
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" name="submit" class="btn btn-lg bg-blue pull-right" value="Start Testing...">
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </section>
        </form>

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
<script>
    $(function () {
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass   : 'iradio_flat-green'
        });

        $("#success-alert").fadeTo(5000, 500).slideUp(500, function(){
            $("#success-alert").slideUp(500);
        });
    });
</script>
</body>
</html>