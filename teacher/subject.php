<?php
session_start();
include_once '../process/process_check_authorize.php';
include_once '../process/connector.php';
$user_id = $_SESSION['user_id'];

$delSuccess = false;
$delWarn = false;
$delFailed = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (@$_POST['DEL'] == 'del_sub') {
        $sub_id = $_POST['sub_id'];
        $sql = "SELECT * FROM `tb_questions` WHERE `sub_id`=$sub_id";
        $result_qt = mysqli_query($link, $sql);
        $count_qt = mysqli_num_rows($result_qt);
        if ($count_qt > 0) {
            $delWarn = true;
            $msg = "This subject already use in question";
        } else {
            $sql = "DELETE FROM `tb_subjects` WHERE sub_id=$sub_id";
            if (!mysqli_query($link, $sql)) {
                $delFailed = true;
                $msg = "Error :: " . $sql . "\n" . mysqli_error($link);
            } else {
                $delSuccess = true;
            }
        }
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
                    <table class="table no-margin table-bordered table-hover">
                        <thead>
                        <tr class="bg-gray">
                            <th style="width: 10px">No.</th>
                            <th>Subject Title</th>
                            <th>Description</th>
                            <th>Level / Time</th>
                            <th>Pass %</th>
                            <th>Status</th>
                            <th style="width: 200px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM `tb_subjects` WHERE `teacher_id`=$user_id";
                            $result = mysqli_query($link, $sql);
                            $no = 0;
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?=@++$no?></td>
                                    <td><?=htmlspecialchars($row['title'])?></td>
                                    <td><?=htmlspecialchars($row['description'])?></td>
                                    <td>
                                        <?php
                                        if ($row['level'] == 1) {
                                            echo "<span class=\"text-center badge bg-green\">Easiest</span>";
                                        } else if ($row['level'] == 2) {
                                            echo "<span class=\"text-center badge bg-blue\">Normal</span>";
                                        } else if ($row['level'] == 3) {
                                            echo "<span class=\"text-center badge bg-orange\">Difficult</span>";
                                        } else {
                                            echo "<span class=\"text-center badge bg-red\">Most Difficult</span>";
                                        }
                                        ?>
                                        <br/>
                                        <span class="text-center badge bg-teal-active">
                                        <?= htmlspecialchars($row['give_minute']) ?> Minute
                                    </td>
                                    <td>
                                        <span class='text-blue text-bold'><?= htmlspecialchars($row['pass_percent']) ?> %</span>
                                    </td>
                                    <td>
                                        <?php
                                        if ($row['status'] == 1) {
                                            echo "<span class=\"text-center badge bg-green\">Available</span>";
                                        } else {
                                            echo "<span class=\"text-center badge bg-orange\">Not Available</span>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                            <a href="edit-subject.php?sub_id=<?= htmlspecialchars($row['sub_id']) ?>"
                                               class="btn btn-sm bg-orange-active">EDIT <i
                                                        class="fa fa-pencil"></i></a> &nbsp;
                                            <button type="button"
                                                    data-toggle="modal"
                                                    data-target="#deleteItem"
                                                    data-s-id="<?= htmlspecialchars($row['sub_id']) ?>"
                                                    class="btn bg-red btn-sm show-btn-del">DEL <i
                                                        class="fa fa-times-circle"></i>
                                            </button>
                                        </form>
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

    <div class="modal fade" id="deleteItem" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="modal-body text-center">
                        <br/>
                        <p class="text-gray" style="font-size: 70px">
                            <i class="fa fa-question-circle-o"></i>
                        </p>
                        <h3 class="text-primary">Do you want to delete this subject ?</h3>
                        <br/>
                        <input type="hidden" id="DEL" name="DEL" value=""/>
                        <input type="hidden" name="sub_id" class="sub_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn bg-gray-active"><i class="fa fa-ban"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn bg-red delBtn" id="del_sub"><i class="fa fa-check-circle"></i>
                            Delete
                        </button>
                    </div>
                </form>

            </div>
        </div>
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
if ($delSuccess) {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#modal-success-del-subject").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-success-del-subject" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-green" style="font-size: 70px">
                        <i class="fa fa-check-circle"></i>
                    </p>
                    <h3 class="text-info">Delete Subject Successfully</h3>
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
if ($delWarn) {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#modal-warn-del-subject").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-warn-del-subject" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-gray" style="font-size: 70px">
                        <i class="fa fa-info-circle"></i>
                    </p>
                    <h3 class="text-info">This Subject already use</h3>
                    <p><?= htmlspecialchars($msg) ?></p>
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
if ($delFailed) {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#modal-failed-del-subject").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-failed-del-subject" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-red" style="font-size: 70px">
                        <i class="fa fa-times-circle"></i>
                    </p>
                    <h3 class="text-info">Delete Subject Unsuccessfully</h3>
                    <p><?= htmlspecialchars($msg) ?></p>
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
    $('button.show-btn-del').on('click', function () {
        let sId = $(this).data('s-id');
        $('input.sub_id').val(sId);
    });

    $(document).ready(function () {
        $('.delBtn').click(function () {
            let ButtonID = $(this).attr('id');
            $('#DEL').val(ButtonID);
        });
    });
</script>
</body>
</html>