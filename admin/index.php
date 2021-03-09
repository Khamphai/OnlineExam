<?php
session_start();
include_once '../process/process_check_authorize.php';
include_once '../process/connector.php';
$user_id = $_SESSION['user_id'];

$sql_all = "SELECT COUNT(*) AS USER_CNT FROM tb_users";
$result_all = mysqli_query($link, $sql_all);
$data_all = mysqli_fetch_assoc($result_all);

$msg = "";
$delSuccess = false;
$delWarn = false;
$delFailed = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (@$_POST['DEL'] == 'del_user') {
        $id = $_POST['user_id'];
        // check from category
        $sql = "SELECT * FROM `tb_category` WHERE `teacher_id`=$id";
        $result_cat = mysqli_query($link, $sql);
        $count_cat = mysqli_num_rows($result_cat);
        if ($count_cat > 0) {
            $delWarn = true;
            $msg = "This user has relation with category";
        } else {
            // check from subject
            $sql = "SELECT * FROM `tb_subjects` WHERE `teacher_id`=$id";
            $result_sub = mysqli_query($link, $sql);
            $count_sub = mysqli_num_rows($result_sub);
            if ($count_sub > 0) {
                $delWarn = true;
                $msg = "This user has relation with subject";
            } else {
                // check from test result
                $sql = "SELECT * FROM `tb_test_result` WHERE `user_id`=$id";
                $result_test = mysqli_query($link, $sql);
                $count_test = mysqli_num_rows($result_test);
                if ($count_test > 0) {
                    $delWarn = true;
                    $msg = "This user has relation with test result";
                } else {
                    $sql = "DELETE FROM `tb_users` WHERE `user_id`=$id";
                    if (!mysqli_query($link, $sql)) {
                        $id = "";
                        $delFailed = true;
                        $msg = "Error :: " . $sql . "\n" . mysqli_error($link);
                    } else {
                        $id = "";
                        $delSuccess = true;
                    }
                }
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
    <title>Exam | Admin</title>
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
                <li class="active"><a href="#"><i class="fa fa-user-circle"></i> <span>Users</span></a></li>
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
                <li class="active">list user</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <!--------------------------
              | Your Page Content Here |
              -------------------------->

            <?php
            print_r($data_cnt);
            ?>

            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>
                                <?php
                                if (count($data_all) > 0) {
                                    echo htmlspecialchars($data_all['USER_CNT']);
                                }else{
                                    echo 0;
                                }
                                ?>
                            </h3>

                            <p>Total Users</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-android-contacts"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3>
                                1
                            </h3>

                            <p>Admin</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-android-contact"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3>2</h3>

                            <p>Teacher</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-android-contact"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-orange">
                        <div class="inner">
                            <h3>4</h3>

                            <p>Student</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-android-contact"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>

            <div class="box box-success">
                <div class="box-header with-border">
                    <h4 class="box-title">Users management</h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin table-hover">
                            <thead>
                            <tr class="bg-gray">
                                <th class="text-center">No.</th>
                                <th>Full Name</th>
                                <th>E-Mail</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Last Update</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "SELECT user_id, email, fullname, role, status, updated_at FROM tb_users";
                            $result = mysqli_query($link, $sql);
                            $count = mysqli_num_rows($result);
                            $no = 0;
                            if ($count > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr
                                        <?php
                                        if ($row['user_id'] == $user_id) {
                                            echo "class='bg-success'";
                                        }
                                        ?>
                                    >
                                        <td class="text-center"><?= @++$no ?></td>
                                        <td><?= htmlspecialchars($row['fullname']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            if (htmlspecialchars($row['role']) == 'admin') {
                                                echo "<span class=\"text-center badge bg-blue\">Admin</span>";
                                            } else if (htmlspecialchars($row['role']) == 'teacher') {
                                                echo "<span class=\"text-center badge bg-teal\">Teacher</span>";
                                            } else if (htmlspecialchars($row['role']) == 'student') {
                                                echo "<span class=\"text-center badge bg-orange\">Student</span>";
                                            } else {
                                                echo "<span class=\"text-center badge bg-red\">Unknown</span>";
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if (htmlspecialchars($row['status']) == 1) {
                                                echo "<span class=\"text-center badge bg-green\">Active</span>";
                                            } else {
                                                echo "<span class=\"text-center badge bg-red\">In-Active</span>";
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center"><?= htmlspecialchars($row['updated_at']) ?></td>
                                        <td class="text-center">
                                            <?php
                                            if ($row['user_id'] != $user_id) {
                                                ?>
                                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                                    <a href="edit-user.php?user_id=<?=htmlspecialchars($row['user_id']) ?>"
                                                       class="btn btn-sm bg-orange-active">EDIT <i
                                                                class="fa fa-pencil"></i></a> &nbsp;
                                                    <button type="button"
                                                            data-toggle="modal"
                                                            data-target="#deleteItem"
                                                            data-u-id="<?= htmlspecialchars($row['user_id']) ?>"
                                                            class="btn bg-red btn-sm show-btn-del">DEL <i
                                                                class="fa fa-times-circle"></i>
                                                    </button>
                                                </form>
                                                <?php
                                            } else {
                                                echo "No Action";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <p class="alert bg-danger" style="font-size: large; margin-top: 20px;">
                                            Not found the user
                                        </p>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                        <h3 class="text-primary">Do you want to delete this user ?</h3>
                        <br/>
                        <input type="hidden" id="DEL" name="DEL" value=""/>
                        <input type="hidden" name="user_id" class="u_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn bg-gray-active"><i class="fa fa-ban"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn bg-red delBtn" id="del_user"><i class="fa fa-check-circle"></i>
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
            $("#modal-success-del-user").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-success-del-user" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-green" style="font-size: 70px">
                        <i class="fa fa-check-circle"></i>
                    </p>
                    <h3 class="text-info">Delete User Successfully</h3>
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
            $("#modal-warn-del-user").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-warn-del-user" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-gray" style="font-size: 70px">
                        <i class="fa fa-info-circle"></i>
                    </p>
                    <h3 class="text-info">This User already use</h3>
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
            $("#modal-failed-del-user").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-failed-del-user" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-red" style="font-size: 70px">
                        <i class="fa fa-times-circle"></i>
                    </p>
                    <h3 class="text-info">Delete User Unsuccessfully</h3>
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
        let uId = $(this).data('u-id');
        $('input.u_id').val(uId);
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