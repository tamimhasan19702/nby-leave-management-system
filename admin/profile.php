<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {
    
    $adminUsername = $_SESSION['alogin']; // Assuming this is the username stored in session
    $sql = "SELECT id, UserName, EmailId, Image, FirstName, LastName, adid, updationDate FROM admin WHERE UserName = :username";
    
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $adminUsername, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    // Check if the result is found
    if ($result) {
        $id = $result['id'];
        $username = $result['User Name'];
        $emailId = $result['EmailId'];
        $image = $result['Image'];
        $firstName = $result['FirstName'];
        $lastName = $result['LastName'];
        $adid = $result['adid'];
        $updationDate = $result['updationDate'];
    } else {
        // Handle case where no admin info is found
        $errorMessage = "No admin information found.";
    }

    // Handle image link update
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['imageLink'])) {
        $newImageLink = $_POST['imageLink'];
        
        // Update the image link in the database
        $updateSql = "UPDATE admin SET Image = :image WHERE UserName = :username";
        $updateQuery = $dbh->prepare($updateSql);
        $updateQuery->bindParam(':image', $newImageLink, PDO::PARAM_STR);
        $updateQuery->bindParam(':username', $adminUsername, PDO::PARAM_STR);
        
        if ($updateQuery->execute()) {
            $successMessage = "Image updated successfully!";
            $image = $newImageLink; // Update the image variable for preview
        } else {
            $errorMessage = "Failed to update image.";
        }
    }

    // Handle image removal
    if (isset($_POST['removeImage'])) {
        $defaultImage = '../assets/images/default.png'; // Path to your default image
        $updateSql = "UPDATE admin SET Image = :image WHERE UserName = :username";
        $updateQuery = $dbh->prepare($updateSql);
        $updateQuery->bindParam(':image', $defaultImage, PDO::PARAM_STR);
        $updateQuery->bindParam(':username', $adminUsername, PDO::PARAM_STR);
        
        if ($updateQuery->execute()) {
            $successMessage = "Image removed successfully!";
            $image = $defaultImage; // Update the image variable for preview
        } else {
            $errorMessage = "Failed to remove image.";
        }
    }
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
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
    <style>
    .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }

    .succ Wrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
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
            </div>
        </div>

        <div class="row profile-info">

            <div class="col s12 m6">
                <h5>Personal Information</h5>
                <table>
                    <tr>
                        <td><strong>Admin ID:</strong></td>
                        <td><?php echo htmlentities($adid); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td><?php echo htmlentities($firstName) . ' ' . htmlentities($lastName); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email ID:</strong></td>
                        <td><?php echo htmlentities($emailId); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Last Updated:</strong></td>
                        <td><?php echo htmlentities($updationDate); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a class="waves-effect waves-light btn modal-trigger" href="#editProfileModal">Edit</a>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Edit Profile Modal -->

            <?php 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editProfile'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailId = $_POST['emailId'];

    // Validate the input data
    if (empty($firstName) || empty($lastName) || empty($emailId)) {
        $errorMessage = "All fields are required.";
    } else if (!filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email ID.";
    } else {
        // Update the admin information in the database
        $updateProfileSql = "UPDATE admin SET FirstName = :firstName, LastName = :lastName, EmailId = :emailId WHERE UserName = :username";
        $updateProfileQuery = $dbh->prepare($updateProfileSql);
        $updateProfileQuery->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $updateProfileQuery->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $updateProfileQuery->bindParam(':emailId', $emailId, PDO::PARAM_STR);
        $updateProfileQuery->bindParam(':username', $adminUsername, PDO::PARAM_STR);

        if ($updateProfileQuery->execute()) {
            // Redirect to the same page to refresh
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $errorMessage = "Failed to update profile.";
        }
    }
}

            ?>

            <div id="editProfileModal" class="modal">
                <div class="modal-content">
                    <h4>Edit Profile</h4>
                    <form method="post" action="">
                        <div class="nby-input-field">
                            <label for="firstName">First Name</label>
                            <input type="text" name="firstName" value="<?php echo htmlentities($firstName); ?>"
                                required>
                        </div>
                        <div class="nby-input-field">
                            <label for="lastName">Last Name</label>
                            <input type="text" name="lastName" value="<?php echo htmlentities($lastName); ?>" required>
                        </div>
                        <div class="nby-input-field">
                            <label for="emailId">Email ID</label>
                            <input type="email" name="emailId" value="<?php echo htmlentities($emailId); ?>" required>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" name="editProfile"
                                class="waves-effect waves-light btn">Update</button>
                            <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col s12 m6">
                <h5>Profile Picture</h5>
                <p>
                    <img id="profileImage" src="<?php echo htmlentities($image); ?>" alt="Profile Image"
                        style="width: 200px; height: auto;" />
                </p>
                <form method="post" action="">
                    <div class="nby-input-field">
                        <label for="imageLink">Image Link</label>
                        <input type="text" id="imageLink" name="imageLink" placeholder="Enter image URL"
                            value="<?php echo htmlentities($image); ?>" required>
                    </div>
                    <button type="submit" class="waves-effect waves-light btn">Update Image</button>
                </form>
                <form method="post" action="" style="margin-top: 20px;">
                    <button type="submit" name="removeImage" class="waves-effect waves-light btn red">Remove
                        Image</button>
                </form>
            </div>


            <?php if (isset($successMessage)): ?>
            <div class="succWrap"><?php echo htmlentities($successMessage); ?></div>
            <?php elseif (isset($errorMessage)): ?>
            <div class="errorWrap"><?php echo htmlentities($errorMessage); ?></div>
            <?php endif; ?>
    </main>

    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/js/alpha.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#imageLink').on('input', function() {
            var imageLink = $(this).val();
            $('#profileImage').attr('src', imageLink);
        });
    });
    </script>

</body>

</html>

<?php } ?>