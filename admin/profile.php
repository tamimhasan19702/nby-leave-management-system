<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
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
        $username = $result['UserName'];
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

    // Handle profile update
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editProfile'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $emailId = $_POST['emailId'];
        $newUsername = $_POST['username']; // Get the new username

        // Validate the input data
        if (empty($firstName) || empty($lastName) || empty($emailId) || empty($newUsername)) {
            $errorMessage = "All fields are required.";
        } else if (!filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Invalid email ID.";
        } else {
            // Update the admin information in the database
            $updateProfileSql = "UPDATE admin SET FirstName = :firstName, LastName = :lastName, EmailId = :emailId, UserName = :username WHERE UserName = :oldUsername";
            $updateProfileQuery = $dbh->prepare($updateProfileSql);
            $updateProfileQuery->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $updateProfileQuery->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $updateProfileQuery->bindParam(':emailId', $emailId, PDO::PARAM_STR);
            $updateProfileQuery->bindParam(':username', $newUsername, PDO::PARAM_STR);
            $updateProfileQuery->bindParam(':oldUsername', $adminUsername, PDO::PARAM_STR);

            if ($updateProfileQuery->execute()) {
                // Update session variable if username changed
                if ($adminUsername !== $newUsername) {
                    $_SESSION['alogin'] = $newUsername;
                }
                // Redirect to the same page to refresh
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $errorMessage = "Failed to update profile.";
            }
        }
    }

    // Handle profile image upload
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profileImage'])) {
        // FTP connection details
        $ftp_server = "premium34.web-hosting.com";
        $ftp_username = "tareq@nbyit.com";
        $ftp_password = "tamim19702021";

        // File details
        $file = $_FILES['profileImage'];
        $fileName = basename($file['name']);
        $tempFilePath = $file['tmp_name'];

        // Connect to FTP server
        $conn_id = ftp_connect($ftp_server);
        if (!$conn_id) {
            die("Couldn't connect to $ftp_server");
        }

        // Login to FTP server
        if (@ftp_login($conn_id, $ftp_username, $ftp_password)) {
            // Upload file to FTP server
            $remoteFilePath = "/hrm.nbysoft.com/admin/assets/uploads/" . $fileName;
            if (ftp_put($conn_id, $remoteFilePath, $tempFilePath, FTP_BINARY)) {
                // Construct the image URL
                $imageUrl = "https://hrm.nbysoft.com/admin/assets/uploads/" . $fileName;

                // Update the Image field in the database
                $updateImageSql = "UPDATE admin SET Image = :imageUrl WHERE UserName = :username";
                $updateImageQuery = $dbh->prepare($updateImageSql);
                $updateImageQuery->bindParam(':imageUrl', $imageUrl, PDO::PARAM_STR);
                $updateImageQuery->bindParam(':username', $adminUsername, PDO::PARAM_STR);

                if ($updateImageQuery->execute()) {
                    // Redirect to the same page to refresh
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "<div class='errorWrap'>Failed to update the Image URL in the database.</div>";
                }
            } else {
                echo "<div class='errorWrap'>Failed to upload file.</div>";
            }
        } else {
            echo "<div class='errorWrap'>FTP login failed.</div>";
        }

        // Close FTP connection
        ftp_close($conn_id);
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
                        <td><strong>Username:</strong></td>
                        <td><?php echo htmlentities($username); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email ID:</strong></td>
                        <td><?php echo htmlentities($emailId); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Last Updated:</strong></td>
                        <td><?php echo date('d-m-Y - h:i A - l', strtotime($updationDate)); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a class="waves-effect waves-light btn modal-trigger" href="#editProfileModal">Edit</a>
                        </td>
                    </tr>
                </table>
            </div>

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
                            <label for="username">New Username</label>
                            <input type="text" name="username" value="<?php echo htmlentities($username ); ?>" required>
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



                <form method="post" action="" enctype="multipart/form-data">
                    <div class="nby-input-field">
                        <label for="profileImage">Upload Profile Image</label>
                        <input type="file" name="profileImage" accept="image/*" required>
                    </div>
                    <button type="submit" class="waves-effect waves-light btn">Update Image</button>
                </form>

            </div>

            <?php if (isset($successMessage)): ?>
            <div class="succWrap"><?php echo htmlentities($successMessage); ?></div>
            <?php elseif (isset($errorMessage)): ?>
            <div class="errorWrap"><?php echo htmlentities($errorMessage); ?></div>
            <?php endif; ?>
        </div>
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