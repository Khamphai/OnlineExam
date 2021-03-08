<header class="main-header">

    <a href="../index.php" class="logo">
        <span class="logo-mini"><b>E</b>xam</span>
        <span class="logo-lg"><b>Exam</b> Online</span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown messages-menu">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" onclick='window.location.reload(true);'>
                        <i class="fa fa-refresh fa-spin"></i> &nbsp; Refresh
                    </a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="../assets/img/user2-160x160.jpg" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?=htmlspecialchars($_SESSION['full_name'])?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="../assets/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                            <p>
                                <?=htmlspecialchars($_SESSION['full_name'])?>
                                <br>
                                <?=htmlspecialchars($_SESSION['email'])?>
                                <small>Member since <?=date("d-m-Y", strtotime($_SESSION['created_at']))?></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="profile.php" class="btn bg-blue">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="../process/process_logout.php" class="btn bg-gray-active">Logout</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>