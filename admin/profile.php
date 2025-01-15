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
                <p><strong>Admin ID:</strong> <?php echo htmlentities($adid); ?></p>
                <p><strong>Name:</strong> <?php echo htmlentities($firstName) .  ' ' . htmlentities($lastName); ?></p>
                <p><strong>Email ID:</strong> <?php echo htmlentities($emailId); ?></p>
                <p><strong>Last Updated:</strong> <?php echo htmlentities($updationDate); ?></p>
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