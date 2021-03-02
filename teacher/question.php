<?php
session_start();
include_once '../process/connector.php';
$category_id = @$_POST['category'];
$subject_id = @$_POST['subject'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['Clicked'] == 'add_qt'){
        $_SESSION['cat_id'] = $category_id;
        $_SESSION['sub_id'] = $subject_id;
        header("Location: add_question.php");
    }else if ($_POST['Clicked'] == 'search') {
        echo "Search";
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
    <link rel="stylesheet" href="../assets/plugins/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="../assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../assets/css/skins/skin-green.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
                <li><a href="index.php"><i class="fa fa-link"></i> <span>Monitor</span></a></li>
                <li><a href="category.php"><i class="fa fa-link"></i> <span>Category</span></a></li>
                <li><a href="subject.php"><i class="fa fa-link"></i> <span>Subject</span></a></li>
                <li class="active"><a href="#"><i class="fa fa-link"></i> <span>Question</span></a></li>
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
                                <select name="category" class="form-control input-lg pull-left" style="margin-right: 5px" id="category-dropdown" style="width: 250px;">
                                    <option value="" selected="selected">Select Category</option>
                                    <?php
                                    $sql = "SELECT CAT_ID, NAME FROM TB_CATEGORY";
                                    $result = mysqli_query($link, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <option value="<?= $row['CAT_ID']; ?>" <?php if ($category_id == $row['CAT_ID']) echo "selected"; ?>>
                                            <?= $row['NAME']; ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                &nbsp;
                                <select name="subject" class="form-control input-lg pull-left" id="subject-dropdown" style="width: 250px;" disabled>
                                    <option value="">Select Subject</option>
                                </select>
                                <input type="hidden" id="Clicked" name="Clicked" value=""/>
                                <button type="submit" class="btn btn-lg bg-gray-active pull-right clickBtn"  id="search" disabled><i class="fa fa-search"></i>&nbsp; SEARCH</button>
                                <button type="submit" class="btn btn-lg bg-blue pull-right clickBtn" style="margin-right: 5px" id="add_qt" disabled><i class="fa fa-plus-circle"></i>&nbsp; ADD QUESTION</button>
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
                    <div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Level / Time</th>
                                <th>Pass %</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th>View</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $user_id = $_SESSION['user_id'];
                            $sql = "SELECT A.TEST_ID, A.CREATED_AT AS TEST_DATE, A.TEST_MINUTE, B.TITLE AS SUB_TITLE, B.LEVEL AS LEVEL,
                                           B.GIVE_MINUTE AS SUB_TIME, B.PASS_PERCENT,
                                           C.NAME AS CAT_NAME FROM TB_TEST_RESULT A
                                               INNER JOIN TB_SUBJECTS B ON (A.SUB_ID=B.SUB_ID)
                                               INNER JOIN TB_CATEGORY C ON(B.CAT_ID=C.CAT_ID)
                                    WHERE A.USER_ID = $user_id ORDER BY A.TEST_ID DESC";
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
                                        $mark_percent = round($percent/$count_score);
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
                                            <td>
                                                <?php
                                                if ($row['LEVEL'] == 1) {
                                                    echo "<span class=\"text-center badge bg-green\">Easiest</span>";
                                                } else if ($row['level'] == 2) {
                                                    echo "<span class=\"text-center badge bg-blue\">Normal</span>";
                                                } else if ($row['level'] == 3) {
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
                                            <td><a href="#" class="btn btn-sm bg-gray-active">GO &nbsp; <i
                                                            class="fa fa-bars"></i></a></td>
                                        </tr>
                                        <?php
                                    }
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
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">PHP - Online Exam</div>
        <strong>Copyright &copy; 2021 <a href="#">SWG9</a>.</strong> All rights reserved.
    </footer>
</div>

<script src="../assets/plugins/jquery/dist/jquery.min.js"></script>
<script src="../assets/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../assets/plugins/select2/dist/js/select2.full.min.js"></script>
<script src="../assets/js/adminlte.min.js"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    })
</script>
<script>
    $(document).ready(function() {
        $('#category-dropdown').on('change', function() {
            let category_id = this.value;
            $.ajax({
                url: "subject-by-category.php",
                type: "POST",
                data: {
                    category_id: category_id
                },
                cache: false,
                success: function(result){
                    $("#subject-dropdown").prop('disabled', false)
                    $("#search").prop('disabled', true)
                    $("#add_qt").prop('disabled', true)
                    $("#subject-dropdown").html(result);
                }
            });
        });
        $('#subject-dropdown').on('change', function() {
            let subject_id = this.value;
            if (subject_id !== "") {
                $("#search").prop('disabled', false)
                $("#add_qt").prop('disabled', false)
            }else{
                $("#search").prop('disabled', true)
                $("#add_qt").prop('disabled', true)
            }
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
</body>
</html>