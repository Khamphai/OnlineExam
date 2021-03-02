<?php
session_start();
$test_id = @$_GET['vTestID'];
$user_id = @$_GET['vUserID'];
if (empty($test_id) || empty($user_id)) {
    header('Location: review.php');
}
include_once '../process/connector.php';

// Load info has relation
$sql = "SELECT U.USER_ID, U.FULLNAME AS FULL_NAME, A.CREATED_AT AS TEST_DATE, A.TEST_MINUTE, B.TITLE AS SUB_TITLE, B.DESCRIPTION AS SUB_DESC, 
        B.GIVE_MINUTE AS SUB_TIME, B.PASS_PERCENT, C.NAME AS CAT_NAME, C.DESCRIPTION AS CAT_DESC FROM TB_TEST_RESULT A
            INNER JOIN TB_USERS U ON (A.USER_ID=U.USER_ID)
            INNER JOIN TB_SUBJECTS B ON (A.SUB_ID=B.SUB_ID)
            INNER JOIN TB_CATEGORY C ON(B.CAT_ID=C.CAT_ID)
        WHERE A.TEST_ID = $test_id AND A.USER_ID = $user_id";
$result = mysqli_query($link, $sql);
$data = mysqli_fetch_assoc($result);
$count_main = mysqli_num_rows($result);
if($count_main != 1) header('Location: review.php');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Monitor Detail</title>
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
                <li class="active"><a href="#"><i class="fa fa-bar-chart"></i> <span>Monitor</span></a></li>
                <li><a href="category.php"><i class="fa fa-link"></i> <span>Category</span></a></li>
                <li><a href="subject.php"><i class="fa fa-link"></i> <span>Subject</span></a></li>
                <li><a href="question.php"><i class="fa fa-link"></i> <span>Question</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Monitor
                <small>Teacher</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">monitor detail</li>
            </ol>
            <a href="index.php" class="btn btn-sm bg-orange-active" style="margin-top: 10px;">
                <i class="fa fa-chevron-circle-left"></i> Back
            </a>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div class="row">
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-orange-active">
                            <i class="fa fa-user-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-number">
                                <span class="text-center badge bg-aqua-active">
                                    <?=htmlspecialchars('User')?>
                                </span>
                                <?= htmlspecialchars($data['USER_ID']) ?>
                                :
                                <?= htmlspecialchars($data['FULL_NAME']) ?>
                            </span>
                            <span class="info-box-more">
                                <span class="text-center badge bg-gray-active">
                                    Test Date: <?=htmlspecialchars($data['TEST_DATE'])?>
                                </span>
                            </span>
                            <span class="info-box-more">
                                <span class="text-center badge bg-gray-active">
                                    Use Time: <?=htmlspecialchars(gmdate("H:i:s", $data['TEST_MINUTE']))?>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-green">
                            <i class="fa fa-check-circle"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-number"><?= htmlspecialchars($data['CAT_NAME']) ?></span>
                            <span class="info-box-more">
                                <span class="text-center badge bg-aqua-active">
                                    <?=htmlspecialchars('Category')?>
                                </span>
                            </span>
                            <span class="info-box-more">
                                <span class="text-center badge bg-gray-active">
                                    Pass Percent: <?=htmlspecialchars($data['PASS_PERCENT'])?> %
                                </span>
                            </span>
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
                            <span class="text-center badge bg-aqua-active">
                                <?=htmlspecialchars('Subject')?>
                            </span>
                            </span>
                            <span class="info-box-more">
                            <span class="text-center badge bg-gray-active">
                                Time: <?= htmlspecialchars($data['SUB_TIME']) ?> Minute
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-success">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        View Detail your Testing
                    </h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <?php
                    $sql = "SELECT B.*, A.* FROM TB_SCORE A INNER JOIN TB_QUESTIONS B ON(A.Q_ID=B.Q_ID) WHERE A.TEST_ID = $test_id";
                    $result = mysqli_query($link, $sql);
                    $count = mysqli_num_rows($result);
                    $no = 0;
                    $percent = 0;
                    if ($count > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="table-responsive">
                        <table class="table no-margin table-bordered">
                            <thead>
                            <tr>
                                <th colspan="4">
                                    <h4><b>[Question <?=@++$no?> of <?=$count?>]</b></h4>
                                    <p style="font-weight: lighter !important;"><?=$row['q_title'];?></p>
                                    <h4><b>[Answer]</b></h4>
                                    <?php
                                    if ($row['sc_judge'] === 'Good') {
                                        $percent += 100;
                                        echo "<p style='font-weight: lighter !important;'>Judge: <span class=\"text-center badge bg-green\">Good</span></p>";
                                    } else if($row['sc_judge'] === 'N.G'){
                                        echo "<p style='font-weight: lighter !important;'>Judge: <span class=\"text-center badge bg-orange\">N.G</span></p>";
                                    } else {
                                        echo "<p style='font-weight: lighter !important;'>Judge: <span class=\"text-center badge bg-red\">Bad</span></p>";
                                    }
                                    ?>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th class="text-center" style="width: 50px;">No.</th>
                                <th class="text-center" style="width: 100px;">Your Choice</th>
                                <th>List Answer</th>
                                <th class="text-center" style="width: 140px;">Correct Answer</th>
                            </tr>
                            <?php if ($row['q_sel1']) {?>
                                <tr
                                    <?php
                                    if (strpos($row['sc_choice'], '1') !== false) {
                                        if (strpos($row['q_answer'], '1') !== false) {
                                            echo "class='bg-success'";
                                        }else{
                                            echo "class='bg-danger'";
                                        }
                                    }
                                    ?>
                                >
                                    <td class="text-center" style="width: 50px;">1</td>
                                    <td class="text-center" style="width: 100px;">
                                        <label>
                                            <?php if ($row['q_answer_type'] == 1) { ?>
                                                <input type="radio" class="flat-red" value="1" <?php if (strpos($row['sc_choice'], '1') !== false ) echo 'checked'; else echo "disabled"; ?>>
                                            <?php }else{ ?>
                                                <input type="checkbox" class="flat-red" value="1" <?php if (strpos($row['sc_choice'], '1') !== false ) echo 'checked'; else echo "disabled"; ?>>
                                            <?php } ?>
                                        </label>
                                    </td>
                                    <td><?= htmlspecialchars($row['q_sel1']) ?></td>
                                    <td class="text-center" style="width: 140px; font-size: large">
                                        <?php if (strpos($row['q_answer'], '1') !== false ) echo "<i class='fa fa-check-circle text-green'></i>"; else echo "<i class='fa fa-times-circle text-red'></i>" ?>
                                    </td>
                                </tr>
                            <?php }if ($row['q_sel2']) {?>
                                <tr
                                    <?php
                                    if (strpos($row['sc_choice'], '2') !== false) {
                                        if (strpos($row['q_answer'], '2') !== false) {
                                            echo "class='bg-success'";
                                        }else{
                                            echo "class='bg-danger'";
                                        }
                                    }
                                    ?>
                                >
                                    <td class="text-center" style="width: 50px;">2</td>
                                    <td class="text-center" style="width: 100px;">
                                        <label>
                                            <?php if ($row['q_answer_type'] == 1) { ?>
                                                <input type="radio" class="flat-red" value="2" <?php if (strpos($row['sc_choice'], '2') !== false ) echo 'checked'; else echo "disabled"; ?>>
                                            <?php }else{ ?>
                                                <input type="checkbox" class="flat-red" value="2" <?php if (strpos($row['sc_choice'], '2') !== false ) echo 'checked'; else echo "disabled"; ?>>
                                            <?php } ?>
                                        </label>
                                    </td>
                                    <td><?= htmlspecialchars($row['q_sel2']) ?></td>
                                    <td class="text-center" style="width: 140px; font-size: large">
                                        <?php if (strpos($row['q_answer'], '2') !== false ) echo "<i class='fa fa-check-circle text-green'></i>"; else echo "<i class='fa fa-times-circle text-red'></i>" ?>
                                    </td>
                                </tr>
                            <?php }if ($row['q_sel3']) {?>
                                <tr
                                    <?php
                                    if (strpos($row['sc_choice'], '3') !== false) {
                                        if (strpos($row['q_answer'], '3') !== false) {
                                            echo "class='bg-success'";
                                        }else{
                                            echo "class='bg-danger'";
                                        }
                                    }
                                    ?>
                                >
                                    <td class="text-center" style="width: 50px;">3</td>
                                    <td class="text-center" style="width: 100px;">
                                        <label>
                                            <?php if ($row['q_answer_type'] == 1) { ?>
                                                <input type="radio" class="flat-red" value="3" <?php if (strpos($row['sc_choice'], '3') !== false ) echo 'checked'; else echo "disabled"; ?>>
                                            <?php }else{ ?>
                                                <input type="checkbox" class="flat-red" value="3" <?php if (strpos($row['sc_choice'], '3') !== false ) echo 'checked'; else echo "disabled"; ?>>
                                            <?php } ?>
                                        </label>
                                    </td>
                                    <td><?= htmlspecialchars($row['q_sel3']) ?></td>
                                    <td class="text-center" style="width: 140px; font-size: large">
                                        <?php if (strpos($row['q_answer'], '3') !== false ) echo "<i class='fa fa-check-circle text-green'></i>"; else echo "<i class='fa fa-times-circle text-red'></i>" ?>
                                    </td>
                                </tr>
                            <?php }if ($row['q_sel4']) {?>
                                <tr
                                    <?php
                                    if (strpos($row['sc_choice'], '4') !== false) {
                                        if (strpos($row['q_answer'], '4') !== false) {
                                            echo "class='bg-success'";
                                        }else{
                                            echo "class='bg-danger'";
                                        }
                                    }
                                    ?>
                                >
                                    <td class="text-center" style="width: 50px;">4</td>
                                    <td class="text-center" style="width: 100px;">
                                        <label>
                                            <?php if ($row['q_answer_type'] == 1) { ?>
                                                <input type="radio" class="flat-red" value="4" <?php if (strpos($row['sc_choice'], '4') !== false ) echo 'checked'; else echo "disabled"; ?>>
                                            <?php }else{ ?>
                                                <input type="checkbox" class="flat-red" value="4" <?php if (strpos($row['sc_choice'], '4') !== false ) echo 'checked'; else echo "disabled"; ?>>
                                            <?php } ?>
                                        </label>
                                    </td>
                                    <td><?= htmlspecialchars($row['q_sel4']) ?></td>
                                    <td class="text-center" style="width: 140px; font-size: large">
                                        <?php if (strpos($row['q_answer'], '4') !== false ) echo "<i class='fa fa-check-circle text-green'></i>"; else echo "<i class='fa fa-times-circle text-red'></i>" ?>
                                    </td>
                                </tr>
                            <?php }if ($row['q_sel5']) {?>
                                <tr
                                    <?php
                                    if (strpos($row['sc_choice'], '5') !== false) {
                                        if (strpos($row['q_answer'], '5') !== false) {
                                            echo "class='bg-success'";
                                        }else{
                                            echo "class='bg-danger'";
                                        }
                                    }
                                    ?>
                                >
                                    <td class="text-center" style="width: 50px;">5</td>
                                    <td class="text-center" style="width: 100px;">
                                        <label>
                                            <?php if ($row['q_answer_type'] == 1) { ?>
                                                <input type="radio" class="flat-red" value="5" <?php if (strpos($row['sc_choice'], '5') !== false ) echo 'checked'; else echo "disabled"; ?>>
                                            <?php }else{ ?>
                                                <input type="checkbox" class="flat-red" value="5" <?php if (strpos($row['sc_choice'], '5') !== false ) echo 'checked'; else echo "disabled"; ?>>
                                            <?php } ?>
                                        </label>
                                    </td>
                                    <td><?= htmlspecialchars($row['q_sel5']) ?></td>
                                    <td class="text-center" style="width: 140px; font-size: large">
                                        <?php if (strpos($row['q_answer'], '5') !== false ) echo "<i class='fa fa-check-circle text-green'></i>"; else echo "<i class='fa fa-times-circle text-red'></i>" ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th colspan="2" class="text-center" style="width: 150px;">Explain</th>
                                <td colspan="2" class="text-orange"><?=htmlspecialchars($row['q_explain'])?></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

                    <?php }} else { ?>
                        <p class="alert bg-danger text-center" style="font-size: large; margin-top: 20px;">
                            Answer not found
                        </p>
                    <?php } ?>
                </div>
            </div>
            <!-- /.box -->

            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            $pass_percent = (int) $data['PASS_PERCENT'];
                            $mark_percent = round($percent/$count);
                            if ($mark_percent >= $pass_percent) {
                                $judge = "<span class='text-green text-bold'>PASSED</span>";
                            }else{
                                $judge = "<span class='text-red text-bold'>FAILED</span>";
                            }
                            ?>
                            <span class="pull-left" style="font-size: xx-large">Mark percent : <b><?= $mark_percent ?>%</b> [<?= $judge ?>]</span>
                            <a href="index.php" class="btn btn-lg bg-green pull-right"><i class="fa fa-check-circle"></i>&nbsp; Finished</a>
                        </div>
                    </div>
                </div>
            </div>

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
            radioClass: 'iradio_flat-green'
        });

        $("#success-alert").fadeTo(2500, 500).slideUp(500, function () {
            $("#success-alert").slideUp(500);
        });
    });
</script>
</body>
</html>