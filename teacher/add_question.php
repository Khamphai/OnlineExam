<?php
session_start();
include_once '../process/process_check_authorize.php';
if (empty($_SESSION['cat_id']) || empty($_SESSION['sub_id'])) {
    header('Location: question.php');
}
include_once '../process/connector.php';
$category_id = $_SESSION['cat_id'];
$subject_id = $_SESSION['sub_id'];
$user_id = $_SESSION['user_id'];

// Load data relation
$sql = "SELECT A.TITLE AS SUB_TITLE, A.DESCRIPTION AS SUB_DESC, A.GIVE_MINUTE AS SUB_TIME,
                B.NAME AS CAT_NAME, B.DESCRIPTION AS CAT_DESC FROM TB_SUBJECTS A INNER JOIN TB_CATEGORY B
                ON(A.CAT_ID=B.CAT_ID) WHERE A.SUB_ID = $subject_id AND B.CAT_ID = $category_id AND (A.TEACHER_ID = $user_id OR B.TEACHER_ID = $user_id);";
$result = mysqli_query($link, $sql);
$data = mysqli_fetch_assoc($result);

$msg = "";
$statusSuccess = false;
$statusFailed = false;
$delSuccess = false;
$delWarn = false;
$delFailed = false;
$view = false;

// question info
$title = @$_POST['title'];
$difficult = @$_POST['difficult'];
$answer_type = @$_POST['answer_type'];
$status = @$_POST['status'];
$desc = @$_POST['desc'];

// Answer info
$answer1 = @$_POST['answer1'];
$answer2 = @$_POST['answer2'];
$answer3 = @$_POST['answer3'];
$answer4 = @$_POST['answer4'];
$answer5 = @$_POST['answer5'];
$correct_answer = @$_POST['correct_answer'];
$explain = @$_POST['explain'];
$ans_sel = "";

// Handle clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (@$_POST['DEL'] == 'del_qt') {
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
    } else if (@$_POST['VIEW'] == 'v_a_qt') {

        // Find Question via ID
        $q_id = @$_POST['q_id'];
        $v_sql = "SELECT * FROM TB_QUESTIONS WHERE Q_ID = $q_id";
        $v_result = mysqli_query($link, $v_sql);
        $v_row = mysqli_fetch_array($v_result, MYSQLI_ASSOC);
        $v_count = mysqli_num_rows($v_result);
        if ($v_count == 1) {
            $view = true;
        }
    } else {
        if (empty($title)) {
            $msg = "Please enter question title";
        } else if (empty($difficult)) {
            $msg = "Please select difficulty";
        } else if (empty($answer_type)) {
            $msg = "Please select answer type";
        } else if (empty($answer1) || empty($answer2)) {
            $msg = "Please enter answer choice more than one choice";
        } else if (empty($correct_answer)) {
            $msg = "Please select the correct answer";
        } else {
            $input_val = "";
            !empty($answer1) ? $input_val .= "1" : $input_val .= "";
            !empty($answer2) ? $input_val .= "2" : $input_val .= "";
            !empty($answer3) ? $input_val .= "3" : $input_val .= "";
            !empty($answer4) ? $input_val .= "4" : $input_val .= "";
            !empty($answer5) ? $input_val .= "5" : $input_val .= "";

            empty($status) ? $status = 0 : $status = 1;

            $count_ans = sizeof($correct_answer);
            $cnt_ans = $count_ans;
            if ($count_ans > 1) {
                foreach ($correct_answer as $value) {
                    // Multiple
                    if (strpos("$input_val", "$value") === false) {
                        $msg = "multiple";
                    }
                    $ans_sel .= $count_ans > 1 ? $value . "," : $value;
                    $count_ans -= 1;
                }
            } else {
                $ans_sel = $correct_answer[0];
                // Single
                if (!preg_match("/{$ans_sel}/i", $input_val)) {
                    $msg = "single";
                }
            }

            // TODO :: check input answer field and correct selected are matching ?
            if ($msg === 'single') {
                $msg = "Your input answer choice and select correct answer (single choice) not matching";
            } else if ($msg === 'multiple') {
                $msg = "Your input answer choice and select correct answer (multiple choice) not matching";
            } else {
                // TODO :: check answer type is 1 or 2 (1=single, 2=multiple)
                // answer type is multiple && correct answer is single
                if (strpos($answer_type, '2') !== false && $cnt_ans <= 1) {
                    $msg = "Incorrect! Your answer type is multiple but correct answer is single";
                } // answer type is single && correct answer is multiple
                else if (strpos($answer_type, '1') !== false && $cnt_ans > 1) {
                    $msg = "Incorrect! Your answer type is single but correct answer is multiple";
                } else {
                    $sql = "INSERT INTO `tb_questions` (`q_id`, `q_title`, `q_desc`, `q_difficulty`, `q_answer_type`, `q_sel1`, `q_sel2`, `q_sel3`, `q_sel4`, `q_sel5`, `q_fig1`, `q_fig2`, `q_fig3`, `q_fig4`, `q_fig5`, `q_answer`, `q_explain`, `q_status`, `sub_id`) VALUES ";
                    $sql .= "(NULL, '$title', '$desc', $difficult, $answer_type, '$answer1', '$answer2', '$answer3', '$answer4', '$answer5', '', '', '', '', '', '$ans_sel', '$explain', $status, $subject_id)";
                    if (!mysqli_query($link, $sql)) {
                        $statusFailed = true;
                        $msg = "<span>Error </span>" . $sql . "<br>" . mysqli_error($link);
                    } else {
                        $statusSuccess = true;
                        // Reset old data
                        // question info
                        $title = "";
                        $difficult = "";
                        $answer_type = "";
                        $status = "";
                        $desc = "";
                        // Answer info
                        $answer1 = "";
                        $answer2 = "";
                        $answer3 = "";
                        $answer4 = "";
                        $answer5 = "";
                        $correct_answer = "";
                        $explain = "";
                        $ans_sel = "";
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
    <title>Exam | Add Question</title>
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
                <li><a href="subject.php"><i class="fa fa-folder-open"></i> <span>Subject</span></a></li>
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
                <li class="active">Add question</li>
            </ol>
            <a href="question.php" class="btn bg-orange-active" style="margin-top: 10px;">
                <i class="fa fa-chevron-circle-left"></i>&nbsp; Back
            </a>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div class="box box-success collapsed-box">
                <div class="box-header with-border">
                    <h4 class="box-title">Questions Added</h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-plus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
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
                                                <a href="../process/process_edit_question.php?qID=<?=htmlspecialchars($row['q_id'])?>" class="btn btn-sm bg-orange-active">EDIT <i
                                                            class="fa fa-pencil"></i></a> &nbsp;
                                                <button type="button"
                                                        data-toggle="modal"
                                                        data-target="#deleteItem"
                                                        data-q-id="<?= htmlspecialchars($row['q_id']) ?>"
                                                        class="btn bg-red btn-sm show-btn-del">DEL <i
                                                            class="fa fa-times-circle"></i>
                                                </button> &nbsp;
                                                <input type="hidden" name="VIEW" value="v_a_qt">
                                                <input type="hidden" name="category" value="<?=$category_id?>">
                                                <input type="hidden" name="subject" value="<?=$subject_id?>">
                                                <input type="hidden" name="q_id" value="<?= htmlspecialchars($row['q_id']) ?>">
                                                <button type="submit" class="btn btn-sm bg-gray-active">VIEW &nbsp;
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <p class="alert bg-danger" style="font-size: large; margin-top: 20px;">
                                            Not found the question
                                        </p>
                                    </td>
                                </tr>
                            <?php }
                            mysqli_close($link);
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
            <!-- /.box -->

            <?php
            if ($msg != "") {
                ?>
                <div class="alert alert-warning alert-dismissible" id="success-alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                    <?= $msg ?>
                </div>
            <?php } ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Question Info</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="title">Question Title</label>
                                    <input type="text" name="title" class="form-control" id="title"
                                           value="<?= $title ?>"
                                           placeholder="Enter Question Title">
                                </div>
                                <div class="form-group">
                                    <label>Difficulty</label><br/>
                                    <input type="radio" name="difficult" class="flat-red"
                                           value="1" <?php if (strpos($difficult, '1') !== false) echo 'checked'; ?>>&nbsp;<span
                                            class="text-center badge bg-green">Easiest</span>&nbsp;&nbsp;
                                    <input type="radio" name="difficult" class="flat-red"
                                           value="2" <?php if (strpos($difficult, '2') !== false) echo 'checked'; ?>>&nbsp;<span
                                            class="text-center badge bg-blue">Normal</span>&nbsp;&nbsp;
                                    <input type="radio" name="difficult" class="flat-red"
                                           value="3" <?php if (strpos($difficult, '3') !== false) echo 'checked'; ?>>&nbsp;<span
                                            class="text-center badge bg-orange">Difficult</span>&nbsp;&nbsp;
                                    <input type="radio" name="difficult" class="flat-red"
                                           value="4" <?php if (strpos($difficult, '4') !== false) echo 'checked'; ?>>&nbsp;<span
                                            class="text-center badge bg-red">Most Difficult</span>
                                </div>
                                <div class="form-group">
                                    <label>Answer Type</label><br/>
                                    <input type="radio" name="answer_type" class="flat-red"
                                           value="1" <?php if (strpos($answer_type, '1') !== false) echo 'checked'; ?>>&nbsp;<span
                                            class="text-center badge bg-green">Single</span>&nbsp;&nbsp;
                                    <input type="radio" name="answer_type" class="flat-red"
                                           value="2" <?php if (strpos($answer_type, '2') !== false) echo 'checked'; ?>>&nbsp;<span
                                            class="text-center badge bg-blue">Multiple</span>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label><br/>
                                    <input type="checkbox" name="status" class="flat-red" value="1"
                                           id="status" <?php if (strpos($status, '1') !== false) echo 'checked'; ?>>&nbsp;&nbsp;
                                    Available to Test
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="desc" rows="3" placeholder="Enter ..."><?php
                                        if ($desc) echo $desc;
                                        ?></textarea>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!--/.col (left) -->

                    <!-- right column -->
                    <div class="col-md-6">
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Answer Info</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <label>Input Answer Choice</label><br/>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <input type="text" name="answer1" class="form-control" id="ans1"
                                                   value="<?= $answer1 ?>" placeholder="Enter Answer choice 1">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="checkbox" name="correct_answer[]" id="cr_ans1" class="flat-red"
                                                   value="1" <?php if (strpos($ans_sel, '1') !== false) echo 'checked'; ?>>
                                            &nbsp;<span class="text-center badge bg-teal-active"
                                                        style="font-size: 10px">Correct Answer</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <input type="text" name="answer2" class="form-control" id="ans2"
                                                   value="<?= $answer2 ?>" placeholder="Enter Answer choice 2">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="checkbox" name="correct_answer[]" id="cr_ans2" class="flat-red"
                                                   value="2" <?php if (strpos($ans_sel, '2') !== false) echo 'checked'; ?>>
                                            &nbsp;<span class="text-center badge bg-teal-active"
                                                        style="font-size: 10px">Correct Answer</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <input type="text" name="answer3" class="form-control" id="ans3"
                                                   value="<?= $answer3 ?>" placeholder="Enter Answer choice 3">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="checkbox" name="correct_answer[]" id="cr_ans3" class="flat-red"
                                                   value="3" <?php if (strpos($ans_sel, '3') !== false) echo 'checked'; ?>>
                                            &nbsp;<span class="text-center badge bg-teal-active"
                                                        style="font-size: 10px">Correct Answer</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <input type="text" name="answer4" class="form-control" id="ans4"
                                                   value="<?= $answer4 ?>" placeholder="Enter Answer choice 4">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="checkbox" name="correct_answer[]" id="cr_ans4" class="flat-red"
                                                   value="4" <?php if (strpos($ans_sel, '4') !== false) echo 'checked'; ?>>
                                            &nbsp;<span class="text-center badge bg-teal-active"
                                                        style="font-size: 10px">Correct Answer</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <input type="text" name="answer5" class="form-control" id="ans5"
                                                   value="<?= $answer5 ?>" placeholder="Enter Answer choice 5">
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="checkbox" name="correct_answer[]" id="cr_ans5" class="flat-red"
                                                   value="5" <?php if (strpos($ans_sel, '5') !== false) echo 'checked'; ?>>
                                            &nbsp;<span class="text-center badge bg-teal-active"
                                                        style="font-size: 10px">Correct Answer</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Explain</label>
                                    <textarea class="form-control" name="explain" rows="2" placeholder="Enter ..."><?php
                                        if ($explain) echo $explain;
                                        ?></textarea>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->

                <div class="box box-solid">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="pull-left" style="font-size: xx-large">Click <b>[Save]</b> to add Question</span>
                                <button type="submit" class="btn btn-lg bg-blue pull-right">
                                    <i class="fa fa-check-circle"></i>&nbsp; Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

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
<script src="../assets/plugins/iCheck/icheck.min.js"></script>
<script src="../assets/js/adminlte.min.js"></script>
<?php
if ($statusSuccess) {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#modal-success-add-question").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-success-add-question" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-green" style="font-size: 70px">
                        <i class="fa fa-check-circle"></i>
                    </p>
                    <h3 class="text-info">Add Question Successfully</h3>
                    <br/><br/>
                    <button type="button" data-dismiss="modal" class="btn bg-blue"><i class="fa fa-plus-circle"></i>
                        Add more question
                    </button>
                    <a href="question.php" class="btn bg-gray-active">
                        Go to list question <i class="fa fa-chevron-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
if ($statusFailed) {
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#modal-failed-add-question").modal('show');
        });
    </script>
    <div class="modal fade" id="modal-failed-add-question" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-red" style="font-size: 70px">
                        <i class="fa fa-times-circle"></i>
                    </p>
                    <h3 class="text-info">Add Question Unsuccessfully</h3>
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
                                    <span style="font-weight: lighter !important; font-size: 12px" class="text-blue">
                                        <span class="text-center badge bg-gray-active">Description</span>&nbsp; <?=$v_row['q_desc'];?>
                                    </span>
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
        setTimeout("callback()", 400);
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
<script type="text/javascript">
    $(function () {
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });

        $("#success-alert").fadeTo(15000, 500).slideUp(500, function () {
            $("#success-alert").slideUp(500);
        });
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