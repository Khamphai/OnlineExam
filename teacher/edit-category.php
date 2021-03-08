<?php
session_start();
include_once '../process/process_check_authorize.php';
include_once '../process/connector.php';
$user_id = $_SESSION['user_id'];

// UPDATE
$msg = "";
$statusSuccess = false;
$name = $description = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cat_id = $_POST['cat_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    if (empty($cat_id)) {
        header("Location: category.php");
    } else if (empty($name)) {
        $msg = "Please input category name";
    } else if (empty($description)) {
        $msg = "Please input category description";
    } else {
        $sql = "UPDATE `tb_category` SET name='$name', description='$description' WHERE cat_id=$cat_id AND teacher_id=$user_id";
        if (!mysqli_query($link, $sql)) {
            $msg = "ERROR: Could not able to execute $sql : " . mysqli_error($link);
        } else {
            $cat_id = $name = $description = "";
            $statusSuccess = true;
        }
        mysqli_close($link);
    }
} else {
    $cat_id = @$_GET['cat_id'];
    $sql = "SELECT * FROM tb_category WHERE cat_id=$cat_id AND teacher_id=$user_id";
    $result = mysqli_query($link, $sql);
    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count_edit = mysqli_num_rows($result);
    if ($count_edit <= 0) {
        header('Location: category.php');
    }
    $cat_id = $data['cat_id'];
    $name = $data['name'];
    $description = $data['description'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Edit Category</title>
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
                <li class="active"><a href="#"><i class="fa fa-folder"></i> <span>Category</span></a></li>
                <li><a href="subject.php"><i class="fa fa-folder-open"></i> <span>Subject</span></a></li>
                <li><a href="question.php"><i class="fa fa-plus-circle"></i> <span>Question</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Category
                <small>Teacher</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">edit category</li>
            </ol>
            <a href="category.php" class="btn bg-orange-active" style="margin-top: 10px;">
                <i class="fa fa-chevron-circle-left"></i>&nbsp; Back
            </a>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <?php
            if ($msg != "") {
                ?>
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
                <input type="hidden" value="<?= htmlspecialchars($cat_id); ?>" name="cat_id"/>
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h4 class="box-title">Edit your category</h4>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label for="name">Category</label>
                            <input type="text" class="form-control input-lg" name="name"
                                   value="<?= htmlspecialchars($name) ?>" placeholder="Enter Category">
                        </div>
                        <div class="form-group">
                            <label for="name">Description</label>
                            <textarea rows="5" class="form-control" name="description"
                                      placeholder="Enter Description"><?= htmlspecialchars($description) ?></textarea>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <div class="box box-solid">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="pull-left" style="font-size: xx-large">Click <b>[Edit]</b> to update Category</span>
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
<script src="../assets/js/adminlte.min.js"></script>
<?php
if ($statusSuccess) {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#modal-success-edit-category").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-success-edit-category" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-green" style="font-size: 70px">
                        <i class="fa fa-check-circle"></i>
                    </p>
                    <h3 class="text-info">Edit Category Successfully</h3>
                    <br/><br/>
                    <a href="category.php" class="btn bg-gray-active">
                        Go to list category <i class="fa fa-chevron-circle-right"></i>
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
        $("#success-alert").fadeTo(5000, 500).slideUp(500, function () {
            $("#success-alert").slideUp(500);
        });
    });
</script>
</body>
</html>