<?php
session_start();
include_once '../process/process_check_authorize.php';
include_once '../process/connector.php';
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Teacher</title>
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
                <li><a href="category.php"><i class="fa fa-folder"></i> <span>Category</span></a></li>
                <li><a href="subject.php"><i class="fa fa-folder-open"></i> <span>Subject</span></a></li>
                <li><a href="question.php"><i class="fa fa-plus-circle"></i> <span>Question</span></a></li>
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
                <li class="active">monitor score test</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3>150</h3>

                            <p>Total Student</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-android-desktop"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>53<sup style="font-size: 20px">%</sup></h3>

                            <p>Bounce Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>44</h3>

                            <p>User Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>65</h3>

                            <p>Unique Visitors</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>

            <div class="box box-success">
                <div class="box-header with-border">
                    <h4 class="box-title">Review student score Examination</h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Subject</th>
                                <th>Date / Use Time</th>
                                <th>Student Name</th>
                                <th>Question</th>
                                <th>Level / Time</th>
                                <th>Pass %</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>View</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql = "SELECT U.USER_ID, U.FULLNAME AS FULL_NAME, A.TEST_ID, A.CREATED_AT AS TEST_DATE, A.TEST_MINUTE, A.TEST_COUNT_QUESTION AS CNT_QT, A.ALL_QUESTION AS CNT_QT_ALL, B.TITLE AS SUB_TITLE, B.LEVEL AS LEVEL,
                                           B.GIVE_MINUTE AS SUB_TIME, B.PASS_PERCENT,
                                           C.NAME AS CAT_NAME FROM TB_TEST_RESULT A
                                               INNER JOIN TB_USERS U ON (A.USER_ID=U.USER_ID)
                                               INNER JOIN TB_SUBJECTS B ON (A.SUB_ID=B.SUB_ID)
                                               INNER JOIN TB_CATEGORY C ON(B.CAT_ID=C.CAT_ID)
                                    WHERE B.TEACHER_ID = $user_id
                                    ORDER BY A.TEST_ID DESC";
                            $result = mysqli_query($link, $sql);
                            $no = 0;
                            $count = mysqli_num_rows($result);
                            if ($count > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $test_id = $row['TEST_ID'];
                                    $sql_score = "SELECT SC_CHOICE, SC_ANSWER, SC_JUDGE FROM TB_SCORE where TEST_ID = $test_id";
                                    $result_score = mysqli_query($link, $sql_score);
                                    $count_score = mysqli_num_rows($result_score);
                                    $percent = 0;
                                    if ($count_score > 0) {
                                        while ($row_score = mysqli_fetch_assoc($result_score)) {
                                            if ($row_score['SC_JUDGE'] === 'Good') {
                                                $percent += 100;
                                            }
                                        }
                                        $pass_percent = (int) $row['PASS_PERCENT'];
                                        $mark_percent = round($percent/$row['CNT_QT_ALL']);
                                        if ($mark_percent >= $pass_percent) {
                                            $judge = "<span class='text-green text-bold'>PASSED</span>";
                                        }else{
                                            $judge = "<span class='text-red text-bold'>FAILED</span>";
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <?= @++$no; ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($row['SUB_TITLE']) ?>
                                                <br/>
                                                <span class="text-center badge bg-gray-active">
                                                <?= htmlspecialchars($row['CAT_NAME']) ?>
                                            </span>
                                            </td>
                                            <td>
                                            <span class="text-center badge bg-blue-active">
                                                Test Date: <?= htmlspecialchars($row['TEST_DATE']) ?>
                                            </span>
                                                <br/>
                                                <span class="text-center badge bg-blue-active">
                                                Use Time: <?= htmlspecialchars(gmdate("H:i:s", $row['TEST_MINUTE'])) ?>
                                            </span>
                                            </td>
                                            <td><?=htmlspecialchars($row['FULL_NAME'])?></td>
                                            <td>
                                                <span class="<?php if($row['CNT_QT'] == $row['CNT_QT_ALL']) echo 'text-blue'; else echo 'text-red'; ?> text-bold"><?=htmlspecialchars($row['CNT_QT'])?></span> / <span class="text-blue text-bold"><?=htmlspecialchars($row['CNT_QT_ALL'])?></span>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['LEVEL'] == 1) {
                                                    echo "<span class=\"text-center badge bg-green\">Easiest</span>";
                                                } else if ($row['LEVEL'] == 2) {
                                                    echo "<span class=\"text-center badge bg-blue\">Normal</span>";
                                                } else if ($row['LEVEL'] == 3) {
                                                    echo "<span class=\"text-center badge bg-orange\">Difficult</span>";
                                                } else {
                                                    echo "<span class=\"text-center badge bg-red\">Most Difficult</span>";
                                                }
                                                ?>
                                                <br/>
                                                <span class="text-center badge bg-teal-active">
                                        <?= htmlspecialchars($row['SUB_TIME']) ?> Minute
                                    </span>
                                            </td>
                                            <td>
                                                <span class='text-green text-bold'><?= htmlspecialchars($row['PASS_PERCENT']) ?> %</span>
                                            </td>
                                            <td>
                                                <span class='text-blue text-bold'><?= $mark_percent ?>%</span>
                                            </td>
                                            <td>
                                                <?=$judge;?>
                                            </td>
                                            <td><a href="monitor_detail.php?vTestID=<?=$test_id?>&vUserID=<?=$row['USER_ID']?>" class="btn btn-sm bg-gray-active">GO &nbsp; <i
                                                            class="fa fa-bars"></i></a></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <p class="alert bg-danger" style="font-size: large; margin-top: 20px;">
                                            Not found the exam result
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

        </section>
        <!-- /.content -->

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
</body>
</html>