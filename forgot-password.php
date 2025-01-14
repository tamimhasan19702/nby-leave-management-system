<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Code for change password 
if (isset($_POST['change'])) {
    $newpassword = md5($_POST['newpassword']);
    $confirmpassword = md5($_POST['confirmpassword']);
    $empid = $_POST['empid'];
    $email = $_POST['emailid'];

    // Check if new password and confirm password match
    if ($newpassword === $confirmpassword) {
        // Verify employee ID and email
        $sql = "SELECT id FROM tblemployees WHERE EmailId=:email and EmpId=:empid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':empid', $empid, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            // Update password
            $con = "UPDATE tblemployees SET Password=:newpassword WHERE EmpId=:empid";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1->bindParam(':empid', $empid, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();
            $msg = "Your Password has been successfully changed";
            header("Location: index.php"); // Redirect to index.php
            exit();
        } else {
            $msg = "Invalid Employee ID or Email. Please try again.";
        }
    } else {
        $msg = "Passwords do not match. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>NBYIT | Password Recovery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- Fixed font link -->
    <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">

    <!-- Theme Styles -->
    <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />
    <style>
    .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }

    .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }
    </style>
</head>

<body>
    <div class="loader-bg"></div>
    <div class="loader">
        <div class="preloader-wrapper big active">
            <div class="spinner-layer spinner-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
            <div class="spinner-layer spinner-spinner-teal lighten-1">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle- clipper right">
                    <div class="circle"></div>
                </div>
            </div>
            <div class="spinner-layer spinner-yellow">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
            <div class="spinner-layer spinner-green">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mn-content fixed-sidebar">
        <header class="mn-header navbar-fixed">
            <nav class="cyan darken-1">
                <div class="nav-wrapper row">
                    <section class="material-design-hamburger navigation-toggle">
                        <a href="#" data-activates="slide-out"
                            class="button-collapse show-on-large material-design-hamburger__icon">
                            <span class="material-design-hamburger__layer"></span>
                        </a>
                    </section>
                    <div class="header-title col s3">
                        <span class="chapter-title">NBYIT | Employee Leave Management System</span>
                    </div>
                </div>
            </nav>
        </header>

        <aside id="slide-out" class="side-nav white fixed">
            <div class="side-nav-wrapper">
                <ul class="nby-sidebar-menu nby-collapsible nby-collapsible-accordion" data-collapsible="accordion">
                    <li><a class="" href="index.php"><i class="material-icons">account_box</i>Employee Login</a></li>
                    <li><a class="" href="forgot-password.php"><i class="material-icons">account_box</i>Password
                            Recovery</a></li>
                    <li><a class="" href="admin/"><i class="material-icons">account_box</i>Admin Login</a></li>
                </ul>
                <div class="footer">
                    <p class="copyright">NBYIT Leave Management System</p>
                </div>
            </div>
        </aside>

        <main class="mn-inner">
            <div class="row">
                <div class="col s12">
                    <div class="col s12 m6 l8 offset-l2 offset-m3">
                        <img src="./assets/images/Logo-of-NBY-IT.webp" alt="nby" class="responsive-img">
                        <h4 class="nby-title">NBYIT Employee Leave Management System</h4>
                        <h2 class="nby-subtitle">Employee Password Recovery</h2>
                    </div>

                    <div class="col s12 m6 l8 offset-l2 offset-m3">
                        <div class="card white darken-1">



                            <div class="card-content ">
                                <span class="card-title" style="font-size:20px;">Employee details</span>
                                <?php if ($msg) { ?>
                                <div class="succWrap"><strong>Success </strong> : <?php echo htmlentities($msg); ?>
                                </div>
                                <?php } ?>
                                <div class="row">
                                    <form class="col s12" name="signin" method="post">
                                        <div class="input-field col s12">
                                            <input id="empid" type="text" name="empid" class="validate"
                                                autocomplete="off" required>
                                            <label for="empid">Employee Id</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="emailid" type="text" class="validate" name="emailid"
                                                autocomplete="off" required>
                                            <label for="emailid">Email id</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="newpassword" type="password" name="newpassword" class="validate"
                                                autocomplete="off" required>
                                            <label for="newpassword">New Password</label>
                                            <span class="eye-icon" onclick="togglePassword()"><i
                                                    class="material-icons">visibility</i></span>
                                        </div>
                                        <style>
                                        .eye-icon {
                                            position: absolute;
                                            right: 10px;
                                            top: 10px;
                                            cursor: pointer;
                                        }
                                        </style>
                                        <script>
                                        function togglePassword() {
                                            var x = document.getElementById("newpassword");
                                            if (x.type === "password") {
                                                x.type = "text";
                                            } else {
                                                x.type = "password";
                                            }
                                        }
                                        </script>
                                        <div class="input-field col s12">
                                            <input id="confirmpassword" type="password" name="confirmpassword"
                                                class="validate" required>
                                            <label for="confirmpassword">Confirm Password </label>
                                            <span class="eye-icon" onclick="toggleConfirmPassword()"><i
                                                    class="material-icons">visibility</i></span>
                                        </div>
                                        <style>
                                        .eye-icon {
                                            position: absolute;
                                            right: 10px;
                                            top: 10px;
                                            cursor: pointer;
                                        }
                                        </style>
                                        <script>
                                        function toggleConfirmPassword() {
                                            var x = document.getElementById("confirmpassword");
                                            if (x.type === "password") {
                                                x.type = "text";
                                            } else {
                                                x.type = "password";
                                            }
                                        }
                                        </script>
                                        <div class="col s12 right-align m-t-sm">
                                            <input type="submit" name="change" value="Change Password"
                                                class="waves-effect waves-light btn teal">
                                        </div>
                                    </form>
                                </div>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div class="left-sidebar-hover"></div>

    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/js/alpha.min.js"></script>
</body>

</html>