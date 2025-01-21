<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['emplogin'])==0) {   
    header('location:index.php');
} else {
    // Code for change password 
    if(isset($_POST['change'])) {
        $password = md5($_POST['password']);
        $newpassword = md5($_POST['newpassword']);
        $confirmpassword = md5($_POST['confirmpassword']);
        $username = $_SESSION['emplogin'];

        // Check if new password and confirm password match
        if ($newpassword !== $confirmpassword) {
            $error = "New password and confirm password do not match.";
        } else {
            // Check if the current password is correct
            $sql = "SELECT Password FROM tblemployees WHERE EmailId=:username and Password=:password";
            $query = $dbh->prepare($sql);
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            $query->execute();

            if($query->rowCount() > 0) {
                // Update the password
                $con = "UPDATE tblemployees SET Password=:newpassword WHERE EmailId=:username";
                $chngpwd1 = $dbh->prepare($con);
                $chngpwd1->bindParam(':username', $username, PDO::PARAM_STR);
                $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
                $chngpwd1->execute();

                // Set success message
                $msg = "Your Password successfully changed";
            } else {
                // Set error message for wrong current password
                $error = "Your current password is wrong";    
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee | Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
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

    .field-icon {
        float: right;
        margin-top: -25px;
        margin-right: 8px;
        position: relative;
        z-index: 2;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <?php include('includes/header.php');?>
    <?php include('includes/sidebar.php');?>
    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Change Password</div>
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <form class="col s12" name="chngpwd" method="post">

                                <?php if($error) { ?>
                                <div class="errorWrap">
                                    <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                                </div>
                                <?php } else if($msg) { ?>
                                <div class="succWrap">
                                    <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
                                </div>
                                <?php } ?>

                                <div class="input-field col s12">
                                    <input id="password" type="password" class="validate" autocomplete="off"
                                        name="password" required>
                                    <label for="password">Current Password</label>
                                    <span toggle="#password" class="fa fa-eye-slash field-icon toggle-password"></span>
                                </div>

                                <div class="input-field col s12">
                                    <input id="newpassword" type="password" name="newpassword" class="validate"
                                        autocomplete="off" required>
                                    <label for="newpassword">New Password</label>
                                    <span toggle="#newpassword"
                                        class="fa fa-eye-slash field-icon toggle-password"></span>
                                </div>

                                <div class="input-field col s12">
                                    <input id="confirmpassword" type="password" name="confirmpassword" class="validate"
                                        autocomplete="off" required>
                                    <label for="confirmpassword">Confirm Password</label>
                                    <span toggle="#confirmpassword"
                                        class="fa fa-eye-slash field-icon toggle-password"></span>
                                </div>

                                <div class="input-field col s12">
                                    <button type="submit" name="change"
                                        class="waves-effect waves-light btn indigo m-b-xs">Change</button>
                                </div>
                            </form>

                            <script>
                            $(document).ready(function() {
                                $(".toggle-password").click(function() {
                                    $(this).toggleClass("fa-eye fa-eye-slash");
                                    var input = $($(this).attr("toggle"));
                                    if (input.attr("type") == "password") {
                                        input.attr("type", "text");
                                    } else {
                                        input.attr("type", "password");
                                    }
                                });
                            });
                            </script>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="left-sidebar-hover"></div>

    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/js/alpha.min.js"></script>
    <script src="assets/js/pages/form_elements.js"></script>
</body>

</html>
<?php } ?>