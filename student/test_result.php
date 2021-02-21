<?php
session_start();
if (empty($_SESSION['category_id']) || empty($_SESSION['subject_id']) || empty($_SESSION['answers'])) {
    header('Location: index.php');
}
include_once '../process/connector.php';
$cat_id = $_SESSION['category_id'];
$sub_id = $_SESSION['subject_id'];
$answers = $_SESSION['answers'];

// Load info has relation
$sql = "SELECT A.TITLE AS SUB_TITLE, A.DESCRIPTION AS SUB_DESC, A.GIVE_MINUTE AS SUB_TIME,
       B.NAME AS CAT_NAME, B.DESCRIPTION AS CAT_DESC FROM TB_SUBJECTS A INNER JOIN TB_CATEGORY B
            ON(A.CAT_ID=B.CAT_ID) WHERE A.SUB_ID = $sub_id AND B.CAT_ID = $cat_id";
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
    <link rel="stylesheet" href="../assets/plugins/iCheck/all.css">
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
                <li class="active">Testing</li>
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
                        <h4 class="box-title">Mark <b>50%</b> [FAILED]</h4>
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
                                    <th>Explain</th>
                                    <th>Review</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql = "SELECT * FROM TB_SUBJECTS WHERE CAT_ID = $cat_id";
                                $result = mysqli_query($link, $sql);
                                $no = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?=@++$no?>
                                        </td>
                                        <td><?=htmlspecialchars($row['title'])?></td>
                                        <td><?=htmlspecialchars($row['description'])?></td>
                                        <td>
                                            <?php
                                            if ($row['level'] == 1) {
                                                echo "<span class=\"text-center badge bg-green\">Easiest</span>";
                                            }else if($row['level'] == 2){
                                                echo "<span class=\"text-center badge bg-blue\">Normal</span>";
                                            }else if($row['level'] == 3){
                                                echo "<span class=\"text-center badge bg-orange\">Difficult</span>";
                                            }else {
                                                echo "<span class=\"text-center badge bg-red\">Most Difficult</span>";
                                            }
                                            ?>
                                        </td>
                                        <td><?=htmlspecialchars($row['pass_percent'])?> %</td>
                                        <td>
                                <span class="text-center badge bg-aqua-active">
                                    <?=htmlspecialchars($row['give_minute'])?> Minute
                                </span>
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

                <div class="row">
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
<script src="../assets/plugins/iCheck/icheck.min.js"></script>
<script src="../assets/js/adminlte.min.js"></script>
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

    $(document).ready(function()
    {
        $('.clickBtn').click(function()
        {
            let ButtonID = $(this).attr('id');
            $('#Clicked').val(ButtonID);
        });
    });
</script>
<script type="text/javascript">
    (function () {
        let timeLeft = <?php echo $time_on; ?>, cinterval;
        let timeDec = function (){
            timeLeft--;
            document.getElementById('countdown').innerHTML = timeLeft;
            if(timeLeft === 0){
                clearInterval(cinterval);
            }
        };
        cinterval = setInterval(timeDec, 1000);
    })();
</script>
</body>
</html>