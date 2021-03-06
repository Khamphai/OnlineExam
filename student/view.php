<?php
session_start();
$test_id = @$_GET['vTestID'];
$q_id = @$_GET['vQID'];
if (empty($test_id) || empty($q_id)) {
    header('Location: test_result.php');
}
if (empty($_SESSION['category_id']) || empty($_SESSION['subject_id']) || empty($_SESSION['answers']) || empty($_SESSION['test_id'])) {
    header('Location: index.php');
}
include_once '../process/connector.php';

// Load info has relation
$sql = "SELECT B.*, A.* FROM TB_SCORE A INNER JOIN TB_QUESTIONS B ON(A.Q_ID=B.Q_ID) WHERE A.TEST_ID = $test_id AND A.Q_ID = $q_id";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$count = mysqli_num_rows($result);
if($count != 1) header('Location: test_result.php');

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Exam | Review</title>
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
                <li class="active">Review Answer</li>
            </ol>
            <a href="test_result.php" class="btn bg-orange-active" style="margin-top: 10px;">
                <i class="fa fa-chevron-circle-left"></i> Back
            </a>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h4 class="box-title">
                        Review Testing Answer
                    </h4>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin table-bordered">
                            <thead>
                            <tr>
                                <th colspan="4">
                                    <h4><b>[Question]</b></h4>
                                    <p style="font-weight: lighter !important;"><?=$row['q_title'];?></p>
                                    <h4><b>[Answer]</b></h4>
                                    <?php
                                    if ($row['sc_judge'] === 'Good') {
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
                </div>
            </div>
            <!-- /.box -->

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
    });
</script>
</body>
</html>