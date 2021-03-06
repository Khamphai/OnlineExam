<?php
session_start();
include_once '../process/process_check_authorize.php';
date_default_timezone_set("Asia/Bangkok");
if (empty($_SESSION['category_id']) || empty($_SESSION['subject_id'])) {
    header('Location: choose_category.php');
}
include_once '../process/connector.php';
$cat_id = $_SESSION['category_id'];
$sub_id = $_SESSION['subject_id'];
$refresh = "no";

// Load info has relation
$sql = "SELECT A.TITLE AS SUB_TITLE, A.DESCRIPTION AS SUB_DESC, A.GIVE_MINUTE AS SUB_TIME,
       B.NAME AS CAT_NAME, B.DESCRIPTION AS CAT_DESC FROM TB_SUBJECTS A INNER JOIN TB_CATEGORY B
            ON(A.CAT_ID=B.CAT_ID) WHERE A.SUB_ID = $sub_id AND B.CAT_ID = $cat_id";
$result = mysqli_query($link, $sql);
$data = mysqli_fetch_assoc($result);

// Load questions
$sql = "SELECT * FROM TB_QUESTIONS WHERE SUB_ID = $sub_id AND Q_STATUS = TRUE";
$result = mysqli_query($link, $sql);
$array = array();
while($row = mysqli_fetch_assoc($result)){
    $array[] = $row;
}
$count = mysqli_num_rows($result);
if ($count != 0) {
    $_SESSION['question'] = $array;
}
$question = $_SESSION['question'];
$_SESSION['q_count'] = count($question);
$_SESSION['give_minute'] = $data['SUB_TIME'];
$_SESSION['action'] = "next";

$msg = "";
$state = 0;
$qId = @$_POST['question_id'];
$index = @$_POST['question_index'];;
$ans_type = @$_POST['answer_type'];
$ans_col = @$_POST['ans_col'];
$ans_sel = @$_POST['ans_sel'];
$time_count = @$_POST['time_on'];
if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = array();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_SESSION["csrf_token"] == $_POST['csrf_token'])) {
    $state = 1;
    // store count last time
    $_SESSION['time'] = (int) $time_count;

    if (empty($_POST['CLICKED'])) {
        $msg = "Please wait until initialized";
    } else if ($_POST['CLICKED'] === 'prev'){
        $index -= 1;
    }else if (empty($ans_sel)) {
        $msg = "Please choose the answer";
    }else{
        $counts = sizeof($ans_sel);
        if ($ans_type != 1) {
            foreach ($ans_sel as $value) {
                $ans .= $counts > 1 ? $value . "," : $value;
                $counts -= 1;
            }
        }else{
            $ans = $ans_sel;
        }

        if ($_POST['CLICKED'] === 'next') {
            if (!empty($_SESSION['answers'][$index]['ans_sel'])) {
                $_SESSION['answers'][$index]['ans_sel'] = $ans;
            }else{
                $answer = ["ans_col" => $ans_col, "q_id" => $qId, "ans_sel" => $ans];
                array_push($_SESSION['answers'], $answer);
            }
            $index += 1;
        } else if ($_POST['CLICKED'] === 'smt'){
            if (!empty($_SESSION['answers'][$index]['ans_sel'])) {
                $_SESSION['answers'][$index]['ans_sel'] = $ans;
            }else{
                $answer = ["ans_col" => $ans_col, "q_id" => $qId, "ans_sel" => $ans];
                array_push($_SESSION['answers'], $answer);
            }
            if ($_SESSION['q_count'] != count($_SESSION['answers'])) {
                $msg = "Invalid answer some question!";
            }else{
                $_SESSION['action'] = "submit";
                // TODO :: Process submit answer
                header("Location: ../process/process_score.php");
            }
        }else{
            $msg = "Please wait until initialized";
        }
    }
}
if ($index == null) { $index = 0; }
if (!isset($_SESSION['time'])) {
    $_SESSION['time'] = $data['SUB_TIME'] * 60; // Time to Seconds;
}

if (!isset($_SESSION['start_test']) || !isset($_SESSION['end_test'])) {
    $startTime = date("h:i:s");
    $_SESSION['start_test'] = $startTime;
    $minute = "+".$data['SUB_TIME'] ." minutes";
    $endTime = strtotime($minute, strtotime($startTime));
    $_SESSION['end_test'] = date('h:i:s', $endTime);
}

//echo "Start Testing " . $_SESSION['start_test'] . "<br>";
//echo "End Testing " .$_SESSION['end_test'] . "<br>";

$time_on = $_SESSION['time'];
header( "refresh:$time_on; url=../process/process_score.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Testing</title>
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
<body class="hold-transition skin-green fixed sidebar-mini" onload="checkFirstVisit()">
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
                <li class="active">Testing</li>
            </ol>
            <button type="button"
                    data-toggle="modal"
                    data-target="#stopItem"
                    class="btn bg-red"
                    style="margin-top: 10px;">
                <i class="fa fa-times-circle"></i> Stop Testing
            </button>
        </section>

        <!-- Main content -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id="testing">
            <section class="content container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-green">
                                <i class="fa fa-check-circle"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?= htmlspecialchars($data['CAT_NAME']) ?></span>
                                <span class="info-box-more"><?=htmlspecialchars($data['CAT_DESC'])?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-blue">
                                <i class="fa fa-check-circle"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?= htmlspecialchars($data['SUB_TITLE']) ?></span>
                                <span class="info-box-more">
                                Time: <span class="text-center badge bg-aqua-active">
                                    <?= htmlspecialchars($data['SUB_TIME']) ?> Minute
                                </span>
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-aqua-active">
                                <i class="fa fa-clock-o"></i>
                            </span>
                            <div class="info-box-icon"
                                 style="background: #FFFFFF !important; margin-left: 15px !important; text-align: center !important;">
                                <span id="countdown"><?php echo floor($time_on); ?></span>&nbsp;<small>seconds.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <?php if ($count > 0) { ?>
                    <div class="box-header with-border">
                        <h4 class="box-title">
                            <b>[Question <?=$index + 1?> of <?=$_SESSION['q_count']?>]</b> <br/><br/>
                            <?=$question[$index]['q_title'];?>
                        </h4>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <?php
                            if ($msg != "") { ?>
                                <div class="alert alert-warning alert-dismissible" id="success-alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-warning"></i> Alert!</h4>
                                    <?= $msg ?>
                                </div>
                            <?php } ?>
                            <table class="table no-margin table-bordered">
                                <thead>
                                <tr>
                                    <th colspan="3"><h4><b>[Answer]</b></h4></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php $_SESSION["csrf_token"] = md5(rand(0,10000000)).time();?>
                                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION["csrf_token"]);?>">
                                <input type="hidden" name="question_index" value="<?=$index?>"/>
                                <input type="hidden" name="question_id" value="<?=$question[$index]['q_id']?>"/>
                                <input type="hidden" name="answer_type" value="<?=$question[$index]['q_answer_type']?>"/>
                                <input type="hidden" name="ans_col" value="<?=$question[$index]['q_answer']?>"/>
                                <input type="hidden" name="time_on" id="time_count"/>
                                <?php if ($question[$index]['q_sel1']) {?>
                                    <tr>
                                        <td class="text-center" style="width: 50px;">1</td>
                                        <td class="text-center" style="width: 50px;">
                                            <label>
                                                <?php if ($question[$index]['q_answer_type'] == 1) { ?>
                                                    <input type="radio" name="ans_sel" class="flat-red" onclick="onClickHandler()" value="1" <?php if (strpos($_SESSION['answers'][$index]['ans_sel'], '1') !== false ) echo 'checked'; ?>>
                                                <?php }else{ ?>
                                                    <input type="checkbox" name="ans_sel[]" class="flat-red" onclick="onClickHandler()" value="1" <?php if (strpos($_SESSION['answers'][$index]['ans_sel'], '1') !== false ) echo 'checked'; ?>>
                                                <?php } ?>
                                            </label>
                                        </td>
                                        <td><?= htmlspecialchars($question[$index]['q_sel1']) ?></td>
                                    </tr>
                                <?php }if ($question[$index]['q_sel2']) {?>
                                    <tr>
                                        <td class="text-center" style="width: 50px;">2</td>
                                        <td class="text-center" style="width: 50px;">
                                            <label>
                                                <?php if ($question[$index]['q_answer_type'] == 1) { ?>
                                                    <input type="radio" name="ans_sel" class="flat-red" value="2" <?php if (strpos($_SESSION['answers'][$index]['ans_sel'], '2') !== false ) echo 'checked'; ?>>
                                                <?php }else{ ?>
                                                    <input type="checkbox" name="ans_sel[]" class="flat-red" value="2" <?php if (strpos($_SESSION['answers'][$index]['ans_sel'], '2') !== false ) echo 'checked'; ?>>
                                                <?php } ?>
                                            </label>
                                        </td>
                                        <td><?= htmlspecialchars($question[$index]['q_sel2']) ?></td>
                                    </tr>
                                <?php }if ($question[$index]['q_sel3']) {?>
                                    <tr>
                                        <td class="text-center" style="width: 50px;">3</td>
                                        <td class="text-center" style="width: 50px;">
                                            <label>
                                                <?php if ($question[$index]['q_answer_type'] == 1) { ?>
                                                    <input type="radio" name="ans_sel" class="flat-red" value="3" <?php if (strpos($_SESSION['answers'][$index]['ans_sel'], '3') !== false ) echo 'checked'; ?>>
                                                <?php }else{ ?>
                                                    <input type="checkbox" name="ans_sel[]" class="flat-red" value="3" <?php if (strpos($_SESSION['answers'][$index]['ans_sel'], '3') !== false ) echo 'checked'; ?>>
                                                <?php } ?>
                                            </label>
                                        </td>
                                        <td><?= htmlspecialchars($question[$index]['q_sel3']) ?></td>
                                    </tr>
                                <?php }if ($question[$index]['q_sel4']) {?>
                                    <tr>
                                        <td class="text-center" style="width: 50px;">4</td>
                                        <td class="text-center" style="width: 50px;">
                                            <label>
                                                <?php if ($question[$index]['q_answer_type'] == 1) { ?>
                                                    <input type="radio" name="ans_sel" class="flat-red" value="4" <?php if (strpos($_SESSION['answers'][$index]['ans_sel'], '4') !== false ) echo 'checked'; ?>>
                                                <?php }else{ ?>
                                                    <input type="checkbox" name="ans_sel[]" class="flat-red" value="4" <?php if (strpos($_SESSION['answers'][$index]['ans_sel'], '4') !== false ) echo 'checked'; ?>>
                                                <?php } ?>
                                            </label>
                                        </td>
                                        <td><?= htmlspecialchars($question[$index]['q_sel4']) ?></td>
                                    </tr>
                                <?php }if ($question[$index]['q_sel5']) {?>
                                    <tr>
                                        <td class="text-center" style="width: 50px;">5</td>
                                        <td class="text-center" style="width: 50px;">
                                            <label>
                                                <?php if ($question[$index]['q_answer_type'] == 1) { ?>
                                                    <input type="radio" name="ans_sel" class="flat-red" value="5" <?php if (strpos($_SESSION['answers'][$index]['ans_sel'], '5') !== false ) echo 'checked'; ?>>
                                                <?php }else{ ?>
                                                    <input type="checkbox" name="ans_sel[]" class="flat-red" value="5" <?php if (strpos($_SESSION['answers'][$index]['ans_sel'], '5') !== false ) echo 'checked'; ?>>
                                                <?php } ?>
                                            </label>
                                        </td>
                                        <td><?= htmlspecialchars($question[$index]['q_sel5']) ?></td>
                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>

                    <?php }else{ ?>
                        <div class="box-body text-center">
                            <p class="alert bg-danger" style="font-size: large; margin-top: 20px;">
                                Question not found
                            </p>
                        </div>
                    <?php } ?>
                </div>
                <!-- /.box -->

                <?php if ($count > 0) { ?>
                <div class="box box-solid">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="CLICKED" name="CLICKED" value=""/>
                                <?php if ($index != 0) { ?>
                                    <button type="submit" class="btn bg-orange pull-left clickBtn" id="prev"><i class="fa fa-chevron-circle-left"></i>&nbsp; Previous</button>
                                <?php } ?>
                                <?php if ($index + 1 != $_SESSION['q_count']) { ?>
                                    <button type="submit" class="btn bg-blue pull-right clickBtn" id="next">Next &nbsp;<i class="fa fa-chevron-circle-right"></i></button>
                                <?php } else { ?>
                                    <button type="submit" class="btn bg-green pull-right clickBtn" id="smt">Submit &nbsp;<i class="fa fa-check-circle"></i></button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>

            </section>
        </form>

        <?php include_once '../loading.php'; ?>
    </div>

    <div class="modal fade" id="stopItem" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="modal-body text-center">
                        <br/>
                        <p class="text-gray" style="font-size: 70px">
                            <i class="fa fa-question-circle-o"></i>
                        </p>
                        <h3 class="text-primary">Do you want to stop this testing ?</h3>
                        <br/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn bg-gray-active"><i class="fa fa-ban"></i>
                            Cancel
                        </button>
                        <a href="choose_category.php" class="btn bg-red">
                            <i class="fa fa-check-circle"></i> Stop!
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRefresh" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <br/><br/>
                    <p class="text-gray" style="font-size: 70px">
                        <i class="fa fa-info-circle"></i>
                    </p>
                    <h3 class="text-info">You have been refresh browser</h3>
                    <br/><br/>
                    <a href="choose_category.php" class="btn bg-gray-active"><i class="fa fa-ban"></i>
                        OK
                    </a>
                </div>
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
<script type="text/javascript">
    function checkFirstVisit() {
        let state = <?=$state?>;
        if(document.cookie.indexOf('refcoke')===-1) {
            // cookie doesn't exist, create it now
            document.cookie = 'refcoke=1';
        } else {
            if (state === 0) {
                // not first visit, so alert
                $("#modalRefresh").modal('show');
            }
        }
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.loading').show();
        $('.overlay').show();
        setTimeout("callback()", 800);
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
            radioClass: 'iradio_flat-green'
        });

        $("#success-alert").fadeTo(2500, 500).slideUp(500, function () {
            $("#success-alert").slideUp(500);
        });
    });
    $(function() {
        $('.clickBtn').click(function()
        {
            let ButtonID = $(this).attr('id');
            $('#CLICKED').val(ButtonID);
        });
    });
</script>
<!--View Loading-->
<script type="text/javascript">
    $(document).on('submit', 'form#testing', function (event) {
        $('.loading').show();
        $('.overlay').show();
    });
</script>
<script type="text/javascript">
    $(function () {
        let timeLeft = <?php echo $time_on; ?>, cinterval;
        let timeDec = function (){
            timeLeft--;
            document.getElementById('countdown').innerHTML = timeLeft;
            document.getElementById('time_count').value = timeLeft;
            if(timeLeft === 0){
                clearInterval(cinterval);
            }
        };
        cinterval = setInterval(timeDec, 1000);
    });

    function disable_f5(e)
    {
        if ((e.which || e.keyCode) === 116)
        {
            e.preventDefault();
        }
    }

    $(document).ready(function(){
        $(document).bind("keydown", disable_f5);
    });
</script>
</body>
</html>