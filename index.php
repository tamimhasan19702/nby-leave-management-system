<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_POST['signin'])) {
    // Retrieve username or email and password from the form
    $username_or_email = $_POST['username_or_email']; // Ensure this matches the input name
    $password = md5($_POST['password']); // Hash the password for security

    // Update the SQL query to check both EmailId and EmpId
    $sql = "SELECT EmailId, Password, Status, id FROM tblemployees WHERE (EmailId = :username_or_email OR EmpId = :username_or_email) AND Password = :password";
    $query = $dbh->prepare($sql);
    
    // Bind parameters
    $query->bindParam(':username_or_email', $username_or_email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    
    // Execute the query
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    // Check if any results were returned
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $status = $result->Status;
            $_SESSION['eid'] = $result->id; // Store employee ID in session
        }
        
        // Check the status of the account
        if ($status == 0) {
            $msg = "Your account is Inactive. Please contact admin.";
        } else {
            $_SESSION['emplogin'] = $username_or_email; // Store username or email in session
            echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
        }
    } else {
        // If no results, show an alert for invalid details
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>NBYIT | Employee Leave Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="assets/css/materialdesign.css" rel="stylesheet">
    <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">

    <!-- Theme Styles -->
    <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />
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
                <div class="circle-clipper right">
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
                        <h2 class="nby-subtitle">Employee Login</h2>
                    </div>

                    <div class="col s12 m6 l8 offset-l2 offset-m3">
                        <div class="card white darken-1">
                            <div class="card-content">
                                <span class="card-title" style="font-size:20px;">Employee Login</span>
                                <?php if($msg){?><div class="errorWrap"><strong>Error</strong> :
                                    <?php echo htmlentities($msg); ?> </div><?php }?>
                                <div class="row">
                                    <form class="col s12" name="signin" method="post">
                                        <div class="input-field col s12">
                                            <span for="username_or_email">Email Address or Employee ID</span>
                                            <input id="username_or_email" type="text" name="username_or_email"
                                                class="nby-validate" autocomplete="off" required>
                                        </div>
                                        <div class="input-field col s12">
                                            <span for="password">Password</span>
                                            <input id="password" type="password" class="validate" name="password"
                                                autocomplete="off" required>
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
                                            var x = document.getElementById("password");
                                            if (x.type === "password") {
                                                x.type = "text";
                                            } else {
                                                x.type = "password";
                                            }
                                        }
                                        </script>
                                        <div class="col s12 right-align m-t-sm">
                                            <input type="submit" name="signin" value="Sign in"
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
    <script src="assets/plugins/waypoints/jquery.waypoints.min.js"></script>
    <script src="assets/plugins/counter-up-master/jquery.counterup.min.js"></script>
    <script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/plugins/chart.js/chart.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.time.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.symbol.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.resize.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="assets/plugins/curvedlines/curvedLines.js"></script>
    <script src="assets/plugins/peity/jquery.peity.min.js"></script>
    <script src="assets/js/alpha.min.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>
</body>

</html>