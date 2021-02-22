<?php
session_start();
if (empty($_SESSION['category_id']) || empty($_SESSION['subject_id']) || empty($_SESSION['answers']) || empty($_SESSION['test_id'])) {
    header('Location: index.php');
}
include_once '../process/connector.php';
$cat_id = $_SESSION['category_id'];
$sub_id = $_SESSION['subject_id'];
$answers = $_SESSION['answers'];
$test_id = $_SESSION['test_id'];
$q_count = $_SESSION['q_count'];

// Load info has relation
$sql = "SELECT A.CREATED_AT AS TEST_DATE, A.TEST_MINUTE, B.TITLE AS SUB_TITLE, B.DESCRIPTION AS SUB_DESC, 
        B.GIVE_MINUTE AS SUB_TIME, B.PASS_PERCENT, C.NAME AS CAT_NAME, C.DESCRIPTION AS CAT_DESC FROM TB_TEST_RESULT A
           INNER JOIN TB_SUBJECTS B ON (A.SUB_ID=B.SUB_ID)
           INNER JOIN TB_CATEGORY C ON(B.CAT_ID=C.CAT_ID)
        WHERE A.TEST_ID = $test_id AND B.SUB_ID = $sub_id AND B.CAT_ID = $cat_id";
$result = mysqli_query($link, $sql);
$data = mysqli_fetch_assoc($result);

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
    <link rel="stylesheet" href="../assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../assets/css/skins/skin-green.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

    <header class="main-header">

        <a href="index.php" class="logo">
            <span class="logo-mini"><b>E</b>xam</span>
            <span class="logo-lg"><b>Exam</b> Online</span>
        </a>

        <nav class="navbar navbar-static-top" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 10 notifications</li>
                            <li>
                                <ul class="menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>

                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../assets/img/user2-160x160.jpg" class="user-image" alt="User Image">
                            <span class="hidden-xs">Khamphai KNVS</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="../assets/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                                <p>
                                    Khamphai KNVS - SWG9
                                    <small>Member since Feb. 2021</small>
                                </p>
                            </li>
                            <li class="user-body">
                                <div class="row">

                                </div>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Log off</a>
                                </div>
                                <div class="pull-right">
                                    <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">

        <section class="sidebar">

            <div class="user-panel">
                <div class="pull-left image">
                    <img src="../assets/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>Khamphai KNVS</p>
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
                <li class="active"><a href="#"><i class="fa fa-link"></i> <span>Exam</span></a></li>
                <li><a href="review.php"><i class="fa fa-link"></i> <span>Review</span></a></li>
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
                <li class="active">Test Result</li>
            </ol>
        </section>

        <!-- Main content -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <section class="content container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-orange">
                                <i class="fa fa-user-circle"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-number"><?= htmlspecialchars('1: Khamphai') ?></span>
                                <span class="info-box-more">
                                <span class="text-center badge bg-aqua-active">
                                    <?=htmlspecialchars('User')?>
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h4 class="box-title">Your result testing score</h4>
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
                                    <th>Q No.</th>
                                    <th>Your Choice</th>
                                    <th>Correct Answer</th>
                                    <th>Judge</th>
                                    <th>Explain/Review</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "SELECT * FROM TB_SCORE WHERE TEST_ID = $test_id";
                                $result = mysqli_query($link, $sql);
                                $no = 0;
                                $percent = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?=@++$no?>
                                        </td>
                                        <td><?=htmlspecialchars($row['sc_choice'])?></td>
                                        <td><?=htmlspecialchars($row['sc_answer'])?></td>
                                        <td>
                                            <?php
                                            if ($row['sc_judge'] === 'Good') {
                                                $percent += 100;
                                                echo "<span class=\"text-center badge bg-green\">Good</span>";
                                            } else if($row['sc_judge'] === 'N.G'){
                                                echo "<span class=\"text-center badge bg-orange\">N.G</span>";
                                            } else {
                                                echo "<span class=\"text-center badge bg-red\">Bad</span>";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm bg-gray-active">GO &nbsp; <i class="fa fa-bars"></i></a>
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
                    <div class="box-footer with-border">
                        <?php
                        $pass_percent = (int) $data['PASS_PERCENT'];
                        $mark_percent = round($percent/$q_count);
                        if ($mark_percent >= $pass_percent) {
                            $judge = "<span class='text-green text-bold'>PASSED</span>";
                        }else{
                            $judge = "<span class='text-red text-bold'>FAILED</span>";
                        }

                        ?>
                        <h4 class="box-title">Mark <b><?= $mark_percent ?>%</b> [<?= $judge ?>]</h4>
                    </div>
                </div>
                <!-- /.box -->

                <div class="row pull-right">
                    <div class="col-md-12">
                        <a href="index.php" class="btn btn-lg bg-green"><i class="fa fa-check-circle"></i>&nbsp; Finished</a>
                    </div>
                </div>
            </section>
        </form>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">PHP - Online Exam</div>
        <strong>Copyright &copy; 2021 <a href="#">SWG9</a>.</strong> All rights reserved.
    </footer>
</div>

<script src="../assets/plugins/jquery/dist/jquery.min.js"></script>
<script src="../assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../assets/js/adminlte.min.js"></script>
</body>
</html>