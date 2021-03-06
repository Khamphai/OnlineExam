<?php
session_start();
include_once '../process/process_check_authorize.php';
if (empty($_SESSION['q_id'])) {
    header('Location: question.php');
}
include_once '../process/connector.php';
$q_id = $_SESSION['q_id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT A.*, B.TITLE AS SUB_TITLE, C.NAME AS CAT_NAME FROM TB_QUESTIONS A
            INNER JOIN TB_SUBJECTS B ON A.SUB_ID = B.SUB_ID
            INNER JOIN TB_CATEGORY C ON B.CAT_ID = C.CAT_ID
        WHERE A.Q_ID = $q_id AND B.TEACHER_ID = $user_id";
$result = mysqli_query($link, $sql);
$data = mysqli_fetch_assoc($result);
$count_edit = mysqli_num_rows($result);
if ($count_edit != 1) header('Location: question.php');

$msg = "";
$statusSuccess = false;
$statusFailed = false;

// question info
$title = $data['q_title'];
$difficult = $data['q_difficulty'];
$answer_type = $data['q_answer_type'];
$status = $data['q_status'];
$desc = $data['q_desc'];

// Answer info
$answer1 = $data['q_sel1'];
$answer2 = $data['q_sel2'];
$answer3 = $data['q_sel3'];
$answer4 = $data['q_sel4'];
$answer5 = $data['q_sel5'];
$ans_sel = $data['q_answer'];
$explain = $data['q_explain'];

// Handle clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // question info
    $title = $difficult = $answer_type = $status =  $desc = $correct_answer = $explain = $ans_sel = "";
    // Answer info
    $answer1 = $answer2 = $answer3 = $answer4 = $answer5 = "";

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
                $sql = "UPDATE `tb_questions` SET q_title='$title', q_desc='$desc', q_difficulty=$difficult, q_answer_type=$answer_type, q_sel1='$answer1', q_sel2='$answer2', q_sel3='$answer3', q_sel4='$answer4', q_sel5='$answer5', q_fig1='', q_fig2='', q_fig3='', q_fig4='', q_fig5='', q_answer='$ans_sel', q_explain='$explain', q_status=$status WHERE q_id=$q_id";
                if (!mysqli_query($link, $sql)) {
                    $statusFailed = true;
                    $msg = "<span>Error </span>" . $sql . "<br>" . mysqli_error($link);
                } else {
                    $statusSuccess = true;
                    // Reset old data
                    // question info
                    $title = $difficult = $answer_type = $status =  $desc = $correct_answer = $explain = $ans_sel = "";
                    // Answer info
                    $answer1 = $answer2 = $answer3 = $answer4 = $answer5 = "";
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
    <title>Exam | Edit Question</title>
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
                <li class="active">Edit question</li>
            </ol>
            <a href="question.php" class="btn bg-orange-active" style="margin-top: 10px;">
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

            <div class="box box-success">
                <div class="box-header with-border">
                    <span style='font-weight: lighter !important;'>
                        <span class="text-center badge bg-teal-active">Category</span> &nbsp; <b>: &nbsp;<?= htmlspecialchars($data['CAT_NAME']) ?></b> &nbsp;&nbsp;
                        <span class="text-center badge bg-teal-active">Subject</span> &nbsp; <b>: &nbsp;<?= htmlspecialchars($data['SUB_TITLE']) ?></b>
                    </span>
                </div>
            </div>

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
                                <span class="pull-left" style="font-size: xx-large">Click <b>[Edit]</b> to update Question</span>
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
                    <h3 class="text-info">Edit Question Successfully</h3>
                    <br/><br/>
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
                    <h3 class="text-info">Edit Question Unsuccessfully</h3>
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
        setTimeout("callback()", 400);
    });

    function callback() {
        $('.loading').hide();
        $('.overlay').hide();
    }
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
</script>
</body>
</html>