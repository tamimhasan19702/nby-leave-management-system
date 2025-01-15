<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {


    

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>NBYIT | My Profile</title>
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
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }

    .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }

    .modal-success {
        display: none;
    }
    </style>
</head>

<body>
    <?php include('includes/header.php');?>
    <?php include('includes/sidebar.php');?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <h1 class="nby-title">Profile</h1>
                <img class="nby-img" src="assets/images/<?php echo $image; ?>"
                    alt="<?php echo $firstName . ' ' . $lastName; ?>">
            </div>
        </div>

        <div class="row profile-info">
            <div class="col s12 m6">
                <h5>Personal Information</h5>
                <p><strong>Employee ID:</strong> <?php echo htmlentities($empId); ?></p>
                <p><strong>First Name:</strong> <?php echo htmlentities($firstName); ?></p>
                <p><strong>Last Name:</strong> <?php echo htmlentities($lastName); ?></p>
                <p><strong>Email ID:</strong> <?php echo htmlentities($emailId); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlentities($gender); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlentities($dob); ?></p>
            </div>
            <div class="col s12 m6">
                <h5>Contact Information</h5>
                <p><strong>Department:</strong> <?php echo htmlentities($department); ?></p>
                <p><strong>Address:</strong> <?php echo htmlentities($address); ?></p>
                <p><strong>City:</strong> <?php echo htmlentities($city); ?></p>
                <p><strong>Country:</strong> <?php echo htmlentities($country); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlentities($phoneNumber); ?></p>
                <p><strong>Username:</strong> <?php echo htmlentities($username); ?></p>
            </div>
        </div>
        <button class="waves-effect waves-light btn modal-trigger" data-target="editModal">Edit Profile</button>

        <!-- Success Modal -->
        <div id="successModal" class="modal modal-success">
            <div class="modal-content">
                <h4>Success</h4>
                <p><?php echo $successMessage; ?></p>
            </div>
        </div>
    </main>

    <!-- Edit Profile Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h4 class="nby-modal-title">Edit Profile</h4>
            <form method="post" action="">
                <input type="hidden" name="eid" value="<?php echo htmlentities($eid); ?>">
                <div class="nby-input-field">
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" value="<?php echo htmlentities($firstName); ?>" required
                        class="short-input">
                </div>
                <div class="nby-input-field">
                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" value="<?php echo htmlentities($lastName); ?>" required
                        class="short-input">
                </div>
                <div class="nby-input-field">
                    <label for="emailId">Email ID</label>
                    <input type="email" name="emailId" value="<?php echo htmlentities($emailId); ?>" required
                        class="short-input">
                </div>
                <div class="nby-input-field">
                    <label for="department">Department</label>
                    <input type="text" name="department" value="<?php echo htmlentities($department); ?>" required
                        class="short-input">
                </div>
                <div class="nby-input-field">
                    <label for="address">Address</label>
                    <input type="text" name="address" value="<?php echo htmlentities($address); ?>" required
                        class="short-input">
                </div>
                <div class="nby-input-field">
                    <label for="city">City</label>
                    <input type="text" name="city" value="<?php echo htmlentities($city); ?>" required
                        class="short-input">
                </div>
                <div class="nby-input-field">
                    <label for="country">Country</label>
                    <input type="text" name="country" value="<?php echo htmlentities($country); ?>" required
                        class="short-input">
                </div>
                <div class="nby-input-field">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="text" name="phoneNumber" value="<?php echo htmlentities($phoneNumber); ?>" required
                        class="short-input">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="waves-effect waves-light btn">Update</button>
                    <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/js/alpha.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.modal').modal();

        // Show success modal if there is a success message
        <?php if (!empty($successMessage)): ?>
        $('#successModal').modal('open'); // Open the success modal
        setTimeout(function() {
            $('#successModal').modal('close'); // Close after 3 seconds
        }, 3000); // Close after 3 seconds
        <?php endif; ?>
    });
    </script>

</body>

</html>

<?php } ?>