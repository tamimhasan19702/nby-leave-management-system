<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['emplogin'])==0) {   
    header('location:index.php');
} else {
    if (isset($_POST['change'])) {
        $currentPassword = md5($_POST['password']);
        $newPassword = md5($_POST['newpassword']);
        $confirmPassword = md5($_POST['confirmpassword']);
        $employeeId = $_SESSION['eid'];

        // Fetch the current password from the database
        $sql = "SELECT Password FROM tblemployees WHERE id=:eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':eid', $employeeId, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // Check if the current password matches
        if ($result && $result['Password'] === $currentPassword) {
            // Check if new password and confirm password match
            if ($newPassword === $confirmPassword) {
                // Update the password
                $sql = "UPDATE tblemployees SET Password=:newpassword WHERE id=:eid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':newpassword', $newPassword, PDO::PARAM_STR);
                $query->bindParam(':eid', $employeeId, PDO::PARAM_STR);
                $query->execute();

                // Clear the session
                session_unset();
                session_destroy();

                // Redirect to index.php
                header('location:index.php');
                exit; // Always call exit after header redirection
            } else {
                $error = "New password and confirm password do not match";
            }
        } else {
            $error = "Current password is incorrect";
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
                                    <span style="font-weight: bold" for="password">Current Password</span>
                                    <input id="password" type="password" class="validate" autocomplete="off"
                                        name="password" required>
                                    <span toggle="#password" class="fa fa-eye-slash field-icon toggle-password"></span>
                                </div>

                                <div class="input-field col s12">
                                    <span style="font-weight: bold" for="newpassword">New Password</span>
                                    <input id="newpassword" type="password" name="newpassword" class="validate"
                                        autocomplete="off" required>
                                    <span toggle="#newpassword"
                                        class="fa fa-eye-slash field-icon toggle-password"></span>
                                </div>

                                <div class="input-field col s12">
                                    <span style="font-weight: bold" for="confirmpassword">Confirm Password</span>
                                    <input id="confirmpassword" type="password" name="confirmpassword" class="validate"
                                        autocomplete="off" required>
                                    <span toggle="#confirmpassword"
                                        class="fa fa-eye-slash field-icon toggle-password"></span>
                                </div>

                                <div class="input-field col s12">
                                    <button type="submit" name="change"
                                        class="waves-effect waves-light btn indigo m-b-xs">Change</button>
                                </div>
                            </form>

                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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