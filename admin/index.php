<?php
session_start();
include('includes/config.php');
if(isset($_POST['signin']))
{
    $username_or_email = $_POST['username_or_email'];
    $password = md5($_POST['password']);

    // Update the SQL query to check both UserName and EmailId
    $sql = "SELECT UserName, Password FROM admin WHERE (UserName=:username_or_email OR EmailId=:username_or_email) AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username_or_email', $username_or_email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0)
    {
        $_SESSION['alogin'] = $username_or_email; // Store the username or email in session
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>Employee leave management system | Admin</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
</head>

<body class="signin-page">

    <div class="mn-content valign-wrapper nbyit-admin">
        <main class="mn-inner container">
            <div class="row"></div>
            <div class="nbyit-logo">
                <img src="../assets/images/Logo-of-NBY-IT.webp" alt="nbyit">
                <a href="../index.php" style="color:#000;">
                    <h2 class="nby-backlink admin">↩ Back to Main Menu</h2>
                </a>
            </div>
            <h4 class="nby-title admin">NBYIT Employee Leave Management System </a>
            </h4>
            <h2 class="nby-subtitle admin">Admin Login</h2>
            <div class="valign">
                <div class="column nby-signin">
                    <div class="col s12 m6 l4 offset-l4 offset-m3">
                        <div class="card white darken-1">
                            <div class="card-content ">
                                <span class="card-title">Sign In</span>
                                <div class="row">
                                    <form class="col s12" name="signin" method="post">
                                        <div class="input-field col s12">
                                            <input id="username_or_email" type="text" name="username_or_email"
                                                class="validate" autocomplete="off" required>
                                            <label for="username_or_email">Username or Email</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="password" type="password" class="validate" name="password"
                                                autocomplete="off" required>
                                            <label for="password">Password</label>
                                            <span toggle="#password" class="fa fa-eye toggle-password"
                                                style="cursor: pointer;"></span>
                                        </div>
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

    <!-- Include Font Awesome for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- JavaScript to toggle password visibility -->
    <script>
    const togglePassword = document.querySelector('.toggle-password');
    const passwordInput = document.querySelector('#password');

    togglePassword.addEventListener('click', function() {
        // Toggle the type attribute
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        // Toggle the eye icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
    </script>


    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/js/alpha.min.js"></script>

</body>

</html>