<?php
session_start();
include_once '../process/process_check_authorize.php';
include_once '../process/connector.php';
unset($_SESSION['cat_id']);
unset($_SESSION['sub_id']);
$category_id = @$_POST['category'];
$subject_id = @$_POST['subject'];
$user_id = $_SESSION['user_id'];
$search = false;
$delSuccess = false;
$delWarn = false;
$delFailed = false;
$view = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (@$_POST['CLICKED'] == 'add_qt') {
        $_SESSION['cat_id'] = $category_id;
        $_SESSION['sub_id'] = $subject_id;
        header("Location: add_question.php");
    } else if (@$_POST['CLICKED'] == 'search') {
        $search = true;
        $sql = "SELECT A.TITLE AS SUB_TITLE, A.DESCRIPTION AS SUB_DESC, A.GIVE_MINUTE AS SUB_TIME, B.NAME AS CAT_NAME, B.DESCRIPTION AS CAT_DESC
                    FROM TB_SUBJECTS A INNER JOIN TB_CATEGORY B
                    ON(A.CAT_ID=B.CAT_ID)
                    WHERE A.SUB_ID = $subject_id AND B.CAT_ID = $category_id AND (A.TEACHER_ID = $user_id OR B.TEACHER_ID = $user_id);";
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_assoc($result);
    } else if (@$_POST['DEL'] == 'del_qt') {
        $q_id = $_POST['q_id'];
        $sql = "SELECT * FROM tb_score WHERE q_id = $q_id";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count_del = mysqli_num_rows($result);
        if ($count_del == 1) {
            $delWarn = true;
        } else {
            $sql = "DELETE FROM tb_questions WHERE q_id = $q_id";
            $result = mysqli_query($link, $sql);
            if (!mysqli_query($link, $sql)) {
                $delFailed = true;
                $msg = "<span>Error </span>" . $sql . "<br>" . mysqli_error($link);
            } else {
                $delSuccess = true;
            }
        }
    } else if (@$_POST['VIEW'] == 'v_qt') {

        // Find Question via ID
        $q_id = @$_POST['q_id'];
        $v_sql = "SELECT * FROM TB_QUESTIONS WHERE Q_ID = $q_id";
        $v_result = mysqli_query($link, $v_sql);
        $v_row = mysqli_fetch_array($v_result, MYSQLI_ASSOC);
        $v_count = mysqli_num_rows($v_result);
        if ($v_count == 1) {
            $view = true;
        }
    } else if (@$_POST['VIEW'] == 'v_s_qt') {
        $search = true;
        $sql = "SELECT A.TITLE AS SUB_TITLE, A.DESCRIPTION AS SUB_DESC, A.GIVE_MINUTE AS SUB_TIME, B.NAME AS CAT_NAME, B.DESCRIPTION AS CAT_DESC
                    FROM TB_SUBJECTS A INNER JOIN TB_CATEGORY B
                    ON(A.CAT_ID=B.CAT_ID)
                    WHERE A.SUB_ID = $subject_id AND B.CAT_ID = $category_id AND (A.TEACHER_ID = $user_id OR B.TEACHER_ID = $user_id);";
        $result = mysqli_query($link, $sql);
        $data = mysqli_fetch_assoc($result);

        // Find Question via ID
        $q_id = @$_POST['q_id'];
        $v_sql = "SELECT * FROM TB_QUESTIONS WHERE Q_ID = $q_id";
        $v_result = mysqli_query($link, $v_sql);
        $v_row = mysqli_fetch_array($v_result, MYSQLI_ASSOC);
        $v_count = mysqli_num_rows($v_result);
        if ($v_count == 1) {
            $view = true;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Question</title>
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
                <li><a href="category.php"><i class="fa fa-link"></i> <span>Category</span></a></li>
                <li><a href="subject.php"><i class="fa fa-link"></i> <span>Subject</span></a></li>
                <li class="active"><a href="#"><i class="fa fa-plus-circle"></i> <span>Question</span></a></li>
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
                <li class="active">list question</li>
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
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form-inline">
                                <select name="category" class="form-control input-lg pull-left"
                                        style="margin-right: 5px" id="category-dropdown" style="width: 250px;">
                                    <option value="" selected="selected">Select Category</option>
                                    <?php
                                    $sql = "SELECT CAT_ID, NAME FROM TB_CATEGORY WHERE TEACHER_ID = $user_id";
                                    $result = mysqli_query($link, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <option value="<?= $row['CAT_ID']; ?>">
                                            <?= $row['NAME']; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                &nbsp;
                                <select name="subject" class="form-control input-lg pull-left" id="subject-dropdown"
                                        style="width: 250px;" disabled>
                                    <option value="">Select Subject</option>
                                </select>
                                <input type="hidden" id="CLICKED" name="CLICKED" value=""/>
                                <a href="question.php" style="margin-left: 5px"
                                   class="btn btn-lg pull-right bg-gray <?php if ($search == false) echo 'disabled'; ?>">
                                    <li class="fa fa-refresh <?php if ($search == true) echo 'fa-spin'; ?>"></li>
                                    &nbsp; RESET
                                </a>
                                <button type="submit" class="btn btn-lg bg-orange-active pull-right clickBtn" id="search"
                                        disabled><i class="fa fa-search"></i>&nbsp; SEARCH
                                </button>
                                <button type="submit" class="btn btn-lg bg-blue pull-right clickBtn"
                                        style="margin-right: 5px" id="add_qt" disabled><i class="fa fa-plus-circle"></i>&nbsp;
                                    ADD QUESTION
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-success">
                <div class="box-header with-border">
                    <h4 class="box-title">Questions for Examination</h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <?php
                    if ($search) {
                        ?>
                        <div class="table-responsive">
                            <table class="table no-margin table-bordered table-hover">
                                <thead>
                                <tr class="bg-gray-active">
                                    <th colspan="6">
                                    <span style='font-weight: lighter !important;'>
                                        <span class="text-center badge bg-teal-active">Category</span> &nbsp; <b>: &nbsp;<?= htmlspecialchars($data['CAT_NAME']) ?></b> &nbsp;&nbsp;
                                        <span class="text-center badge bg-teal-active">Subject</span> &nbsp; <b>: &nbsp;<?= htmlspecialchars($data['SUB_TITLE']) ?></b>
                                    </span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th style="width: 10px">No.</th>
                                    <th style="width: 50%">Question</th>
                                    <th>Level</th>
                                    <th>Answer Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                <?php
                                $sql = "SELECT A.*, B.*, C.* FROM TB_QUESTIONS A
                                                INNER JOIN TB_SUBJECTS B ON A.SUB_ID = B.SUB_ID
                                                INNER JOIN TB_CATEGORY C ON B.CAT_ID = C.CAT_ID
                                            WHERE B.SUB_ID = $subject_id AND C.CAT_ID = $category_id AND B.TEACHER_ID = $user_id";
                                $result = mysqli_query($link, $sql);
                                $count = mysqli_num_rows($result);
                                $no = 0;
                                if ($count > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td>
                                                <?= @++$no; ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($row['q_title']) ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['q_difficulty'] == 1) {
                                                    echo "<span class=\"text-center badge bg-green\">Easiest</span>";
                                                } else if ($row['q_difficulty'] == 2) {
                                                    echo "<span class=\"text-center badge bg-blue\">Normal</span>";
                                                } else if ($row['q_difficulty'] == 3) {
                                                    echo "<span class=\"text-center badge bg-orange\">Difficult</span>";
                                                } else {
                                                    echo "<span class=\"text-center badge bg-red\">Most Difficult</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['q_answer_type'] == 1) {
                                                    echo "<span class=\"text-center badge bg-green\">Single</span>";
                                                } else {
                                                    echo "<span class=\"text-center badge bg-blue\">Multiple</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['q_status'] == 1) {
                                                    echo "<span class=\"text-center badge bg-green\">Available</span>";
                                                } else {
                                                    echo "<span class=\"text-center badge bg-orange\">Not Available</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                                    <a href="#" class="btn btn-sm bg-orange-active">EDIT <i
                                                                class="fa fa-pencil"></i></a> &nbsp;
                                                    <button type="button"
                                                            data-toggle="modal"
                                                            data-target="#deleteItem"
                                                            data-q-id="<?= htmlspecialchars($row['q_id']) ?>"
                                                            class="btn bg-red btn-sm show-btn-del">DEL <i
                                                                class="fa fa-times-circle"></i>
                                                    </button> &nbsp;
                                                    <input type="hidden" name="VIEW" value="v_s_qt">
                                                    <input type="hidden" name="category" value="<?=$category_id?>">
                                                    <input type="hidden" name="subject" value="<?=$subject_id?>">
                                                    <input type="hidden" name="q_id" value="<?= htmlspecialchars($row['q_id']) ?>">
                                                    <button type="submit" class="btn btn-sm bg-gray-active">VIEW &nbsp;
                                                        <i class="fa fa-bars"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <p class="alert bg-danger" style="font-size: large; margin-top: 20px;">
                                                Not found the question
                                            </p>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        <?php
                    } else {
                        ?>
                        <!-- View ALL-->
                        <?php
                        $sql = "SELECT A.SUB_ID, A.TITLE AS SUB_TITLE, A.DESCRIPTION AS SUB_DESC, A.GIVE_MINUTE AS SUB_TIME, B.CAT_ID, B.NAME AS CAT_NAME, B.DESCRIPTION AS CAT_DESC
                                FROM TB_SUBJECTS A INNER JOIN TB_CATEGORY B
                                ON(A.CAT_ID=B.CAT_ID) 
                                WHERE (A.TEACHER_ID = $user_id OR B.TEACHER_ID = $user_id);";
                        $result = mysqli_query($link, $sql);
                        $count = mysqli_num_rows($result);
                        if ($count > 0) {
                            while ($data = mysqli_fetch_assoc($result)) {
                                ?>
                                <div class="table-responsive">
                                    <table class="table no-margin table-bordered table-hover">
                                        <thead>
                                        <tr class="bg-gray-active">
                                            <th colspan="6">
                                        <span style='font-weight: lighter !important;'>
                                            <span class="text-center badge bg-teal-active">Category</span> &nbsp; <b>: &nbsp;<?= htmlspecialchars($data['CAT_NAME']) ?></b> &nbsp;&nbsp;
                                            <span class="text-center badge bg-teal-active">Subject</span> &nbsp; <b>: &nbsp;<?= htmlspecialchars($data['SUB_TITLE']) ?></b>
                                        </span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th style="width: 10px">No.</th>
                                            <th style="width: 50%">Question</th>
                                            <th>Level</th>
                                            <th>Answer Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        <?php
                                        $subject_id = $data['SUB_ID'];
                                        $category_id = $data['CAT_ID'];
                                        $sql = "SELECT A.*, B.*, C.* FROM TB_QUESTIONS A
                                                INNER JOIN TB_SUBJECTS B ON A.SUB_ID = B.SUB_ID
                                                INNER JOIN TB_CATEGORY C ON B.CAT_ID = C.CAT_ID
                                            WHERE B.SUB_ID = $subject_id AND C.CAT_ID = $category_id AND B.TEACHER_ID = $user_id";
                                        $result2 = mysqli_query($link, $sql);
                                        $count_sub = mysqli_num_rows($result2);
                                        $no = 0;
                                        if ($count_sub > 0) {
                                            while ($row = mysqli_fetch_assoc($result2)) { ?>
                                                <tr>
                                                    <td>
                                                        <?= @++$no; ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($row['q_title']) ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($row['q_difficulty'] == 1) {
                                                            echo "<span class=\"text-center badge bg-green\">Easiest</span>";
                                                        } else if ($row['q_difficulty'] == 2) {
                                                            echo "<span class=\"text-center badge bg-blue\">Normal</span>";
                                                        } else if ($row['q_difficulty'] == 3) {
                                                            echo "<span class=\"text-center badge bg-orange\">Difficult</span>";
                                                        } else {
                                                            echo "<span class=\"text-center badge bg-red\">Most Difficult</span>";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($row['q_answer_type'] == 1) {
                                                            echo "<span class=\"text-center badge bg-green\">Single</span>";
                                                        } else {
                                                            echo "<span class=\"text-center badge bg-blue\">Multiple</span>";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($row['q_status'] == 1) {
                                                            echo "<span class=\"text-center badge bg-green\">Available</span>";
                                                        } else {
                                                            echo "<span class=\"text-center badge bg-orange\">Not Available</span>";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                                            <a href="#" class="btn btn-sm bg-orange-active">EDIT <i
                                                                        class="fa fa-pencil"></i></a> &nbsp;
                                                            <button type="button"
                                                                    data-toggle="modal"
                                                                    data-target="#deleteItem"
                                                                    data-q-id="<?= htmlspecialchars($row['q_id']) ?>"
                                                                    class="btn bg-red btn-sm show-btn-del">DEL <i
                                                                        class="fa fa-times-circle"></i>
                                                            </button> &nbsp;
                                                            <input type="hidden" name="VIEW" value="v_qt">
                                                            <input type="hidden" name="q_id" value="<?= htmlspecialchars($row['q_id']) ?>">
                                                            <button type="submit" class="btn btn-sm bg-gray-active">VIEW &nbsp;
                                                                <i class="fa fa-bars"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <p class="alert bg-danger"
                                                       style="font-size: large; margin-top: 20px;">
                                                        Not found the question
                                                    </p>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                                <?php
                            }
                        } else {
                            ?>
                            <p class="alert bg-danger text-center" style="font-size: large; margin-top: 20px;">
                                Question not found
                            </p>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
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
                        <h3 class="text-primary">Do you want to delete this question ?</h3>
                        <br/>
                        <input type="hidden" id="DEL" name="DEL" value=""/>
                        <input type="hidden" name="q_id" class="q_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn bg-gray-active"><i class="fa fa-ban"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn bg-red delBtn" id="del_qt"><i class="fa fa-check-circle"></i>
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
            $("#modal-success-del-question").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-success-del-question" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-green" style="font-size: 70px">
                        <i class="fa fa-check-circle"></i>
                    </p>
                    <h3 class="text-info">Delete Question Successfully</h3>
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
            $("#modal-success-warn-question").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-success-warn-question" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-gray" style="font-size: 70px">
                        <i class="fa fa-info-circle"></i>
                    </p>
                    <h3 class="text-info">This Question already use</h3>
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
            $("#modal-failed-del-question").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-failed-del-question" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-red" style="font-size: 70px">
                        <i class="fa fa-times-circle"></i>
                    </p>
                    <h3 class="text-info">Delete Question Unsuccessfully</h3>
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
<?php
if ($view) {
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.loading').hide();
        $('.overlay').hide();
        $("#modal-view-question").modal('show');
    });
</script>
<div class="modal fade" id="modal-view-question" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <br/><br/>
                <div class="table-responsive">
                    <table class="table no-margin table-bordered table-hover">
                        <thead>
                        <tr>
                            <th colspan="3">
                                <h4><b>[Question]</b></h4>
                                <p style="font-weight: lighter !important;"><?=htmlspecialchars($v_row['q_title']);?></p>
                                <h4><b>[Answer]</b></h4>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th class="text-center" style="width: 150px;">No.</th>
                            <th>List Answer</th>
                            <th class="text-center" style="width: 150px;">Correct Answer</th>
                        </tr>
                        <?php if ($v_row['q_sel1']) {?>
                            <tr
                                <?php
                                if (strpos($v_row['q_answer'], '1') !== false) {
                                    echo "class='bg-success'";
                                }
                                ?>
                            >
                                <td class="text-center" style="width: 50px;">1</td>
                                <td><?= htmlspecialchars($v_row['q_sel1']) ?></td>
                                <td class="text-center" style="width: 140px; font-size: large">
                                    <?php if (strpos($v_row['q_answer'], '1') !== false ) echo "<i class='fa fa-check-circle text-green'></i>"; else echo "<i class='fa fa-times-circle text-red'></i>" ?>
                                </td>
                            </tr>
                        <?php }if ($v_row['q_sel2']) {?>
                            <tr
                                <?php
                                if (strpos($v_row['q_answer'], '2') !== false) {
                                    echo "class='bg-success'";
                                }
                                ?>
                            >
                                <td class="text-center" style="width: 50px;">2</td>
                                <td><?= htmlspecialchars($v_row['q_sel2']) ?></td>
                                <td class="text-center" style="width: 140px; font-size: large">
                                    <?php if (strpos($v_row['q_answer'], '2') !== false ) echo "<i class='fa fa-check-circle text-green'></i>"; else echo "<i class='fa fa-times-circle text-red'></i>" ?>
                                </td>
                            </tr>
                        <?php }if ($v_row['q_sel3']) {?>
                            <tr
                                <?php
                                    if (strpos($v_row['q_answer'], '3') !== false) {
                                        echo "class='bg-success'";
                                    }
                                ?>
                            >
                                <td class="text-center" style="width: 50px;">3</td>
                                <td><?= htmlspecialchars($v_row['q_sel3']) ?></td>
                                <td class="text-center" style="width: 140px; font-size: large">
                                    <?php if (strpos($v_row['q_answer'], '3') !== false ) echo "<i class='fa fa-check-circle text-green'></i>"; else echo "<i class='fa fa-times-circle text-red'></i>" ?>
                                </td>
                            </tr>
                        <?php }if ($v_row['q_sel4']) {?>
                            <tr
                                <?php
                                    if (strpos($v_row['q_answer'], '4') !== false) {
                                        echo "class='bg-success'";
                                    }
                                ?>
                            >
                                <td class="text-center" style="width: 50px;">4</td>
                                <td><?= htmlspecialchars($v_row['q_sel4']) ?></td>
                                <td class="text-center" style="width: 140px; font-size: large">
                                    <?php if (strpos($v_row['q_answer'], '4') !== false ) echo "<i class='fa fa-check-circle text-green'></i>"; else echo "<i class='fa fa-times-circle text-red'></i>" ?>
                                </td>
                            </tr>
                        <?php }if ($v_row['q_sel5']) {?>
                            <tr
                                <?php
                                    if (strpos($v_row['q_answer'], '5') !== false) {
                                        echo "class='bg-success'";
                                    }
                                ?>
                            >
                                <td class="text-center" style="width: 50px;">5</td>
                                <td><?= htmlspecialchars($v_row['q_sel5']) ?></td>
                                <td class="text-center" style="width: 140px; font-size: large">
                                    <?php if (strpos($v_row['q_answer'], '5') !== false ) echo "<i class='fa fa-check-circle text-green'></i>"; else echo "<i class='fa fa-times-circle text-red'></i>" ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <th colspan="1" class="text-center" style="width: 150px;">Explain</th>
                            <td colspan="2" class="text-orange"><?=htmlspecialchars($v_row['q_explain'])?></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <div class="modal-footer">
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
        let qId = $(this).data('q-id');
        $('input.q_id').val(qId);
    });
</script>
<script>
    $(document).ready(function () {
        $('#category-dropdown').on('change', function () {
            let category_id = this.value;
            $.ajax({
                url: "subject-by-category.php",
                type: "POST",
                data: {
                    category_id: category_id,
                    u_id: <?=$user_id?>
                },
                cache: false,
                success: function (result) {
                    $("#subject-dropdown").prop('disabled', false)
                    $("#search").prop('disabled', true)
                    $("#add_qt").prop('disabled', true)
                    $("#subject-dropdown").html(result);
                }
            });
        });
        $('#subject-dropdown').on('change', function () {
            let subject_id = this.value;
            if (subject_id !== "") {
                $("#search").prop('disabled', false)
                $("#add_qt").prop('disabled', false)
            } else {
                $("#search").prop('disabled', true)
                $("#add_qt").prop('disabled', true)
            }
        });
    });
    $(document).ready(function () {
        $('.clickBtn').click(function () {
            let ButtonID = $(this).attr('id');
            $('#CLICKED').val(ButtonID);
        });
    });

    $(document).ready(function () {
        $('.delBtn').click(function () {
            let ButtonID = $(this).attr('id');
            $('#DEL').val(ButtonID);
        });
    });
</script>
<!--View Loading-->
<script type="text/javascript">
    $(document).on('submit', 'form#question', function (event) {
        $('.loading').show();
        $('.overlay').show();
    });
</script>
</body>
</html>