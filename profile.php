<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Check if the user is logged in
if (!isset($_SESSION['eid'])) {
    header('location: index.php'); // Redirect to login page if not logged in
    exit();
}

// Get the employee ID from the session
$eid = $_SESSION['eid'];

// Initialize a variable to hold the success message
$successMessage = "";

// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editProfile'])) {
    // Get the updated values from the form
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailId = $_POST['emailId'];
    $username = $_POST['username']; 
    $dob = $_POST['dob']; 
    $department = $_POST['department'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $phoneNumber = $_POST['phoneNumber'];

    // Prepare the SQL statement to update the employee details
    $sql = "UPDATE tblemployees 
            SET FirstName = :firstName, LastName = :lastName, EmailId = :emailId, Username = :username, Dob = :dob, 
            Department = :department, Address = :address, City = :city, Country = :country, Phonenumber = :phoneNumber 
            WHERE id = :eid";

    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_INT);
    $query->bindParam(':firstName', $firstName, PDO::PARAM_STR);
    $query->bindParam(':lastName', $lastName, PDO::PARAM_STR);
    $query->bindParam(':emailId', $emailId, PDO::PARAM_STR);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':dob', $dob, PDO::PARAM_STR);
    $query->bindParam(':department', $department, PDO::PARAM_STR);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':city', $city, PDO::PARAM_STR);
    $query->bindParam(':country', $country, PDO::PARAM_STR);
    $query->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);

    // Execute the query
    $query->execute();

    // Display a success message
    $successMessage = "Profile updated successfully.";
}

// Prepare the SQL statement to fetch employee details
$sql = "SELECT EmpId, FirstName, LastName, EmailId, Gender, Dob, Department, Address, City, Country, Phonenumber, Username 
        FROM tblemployees 
        WHERE id = :eid";

$query = $dbh->prepare($sql);
$query->bindParam(':eid', $eid, PDO::PARAM_INT);
$query->execute();

// Fetch the employee details
$result = $query->fetch(PDO::FETCH_OBJ);

if ($result) {
    // Store the employee details in variables
    $empId = $result->EmpId;
    $firstName = $result->FirstName;
    $lastName = $result->LastName;
    $emailId = $result->EmailId;
    $gender = $result->Gender;
    $dob = $result->Dob;
    $department = $result->Department;
    $address = $result->Address;
    $city = $result->City;
    $country = $result->Country;
    $phoneNumber = $result->Phonenumber;
    $username = $result->Username;

} else {
    echo "No employee found with the given ID.";
}


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
        $remoteFilePath = "/hrm.nbysoft.com/assets/uploads/" . $fileName;
        if (ftp_put($conn_id, $remoteFilePath, $tempFilePath, FTP_BINARY)) {
            // Construct the image URL
            $imageUrl = "https://hrm.nbysoft.com/assets/uploads/" . $fileName;

            // Update the Image field in the database
            $updateImageSql = "UPDATE tblemployees SET Image = :imageUrl WHERE id = :eid";
            $updateImageQuery = $dbh->prepare($updateImageSql);
            $updateImageQuery->bindParam(':imageUrl', $imageUrl, PDO::PARAM_STR);
            $updateImageQuery->bindParam(':eid', $eid, PDO::PARAM_INT);

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
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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

    .modal-success {
        display: none;
    }

    #imagePreview {
        width: 200px;
        height: auto;
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
                <img style="width: 200px; height: auto;" class="nby-img" src="<?php echo $image; ?>"
                    alt="<?php echo $firstName . ' ' . $lastName; ?>">

                <form action="" method="post" enctype="multipart/form-data">
                    <div class="nby-input-field">
                        <label for="profileImage">Upload Profile Image</label>
                        <input type="file" name="profileImage" accept="image/*" required>
                    </div>

                    <button type="submit" name="uploadImage" class="waves-effect waves-light btn">Upload</button>
                </form>
            </div>
        </div>

        <div class="row profile-info">
            <div class="col s12">
                <h5>Profile Information</h5>
                <table class="profile-table">
                    <tr>
                        <td><strong>Employee ID:</strong></td>
                        <td><?php echo htmlentities($empId); ?></td>
                    </tr>
                    <tr>
                        <td><strong>First Name:</strong></td>
                        <td><?php echo htmlentities($firstName); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Last Name:</strong></td>
                        <td><?php echo htmlentities($lastName); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email ID:</strong></td>
                        <td><?php echo htmlentities($emailId); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Gender:</strong></td>
                        <td><?php echo htmlentities($gender); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Date of Birth:</strong></td>
                        <td><?php echo htmlentities($dob); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Department:</strong></td>
                        <td><?php echo htmlentities($department); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Address:</strong></td>
                        <td><?php echo htmlentities($address); ?></td>
                    </tr>
                    <tr>
                        <td><strong>City:</strong></td>
                        <td><?php echo htmlentities($city); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Country:</strong></td>
                        <td><?php echo htmlentities($country); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Phone Number:</strong></td>
                        <td><?php echo htmlentities($phoneNumber); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Username:</strong></td>
                        <td><?php echo htmlentities($username); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <button class="waves-effect waves-light btn modal-trigger" data-target="editModal">Edit Profile</button>


        <!-- Edit Profile Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <h4 class="nby-modal-title">Edit Profile</h4>
                <form method="post" action="" enctype="multipart/form-data">
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
                        <label for="username">Username</label>
                        <input type="text" name="username" value="<?php echo htmlentities($username); ?>" required
                            class="short-input">
                    </div>
                    <div class="nby-input-field">
                        <label for="dob">Date of Birth</label>
                        <input type="text" name="dob" value="<?php echo htmlentities($dob); ?>" required
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
                        <button type="submit" name="editProfile" class="waves-effect waves-light btn">Update</button>
                        <a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function previewImage() {
            const imageUrl = document.getElementById('profileImage').value;
            const imagePreview = document.getElementById('imagePreview');
            const imageError = document.getElementById('imageError');

            // Reset error message
            imageError.style.display = 'none';

            // Create a new Image object to check if the URL is valid
            const img = new Image();
            img.onload = function() {
                console.log('Image loaded successfully:', imageUrl);
                imagePreview.src = imageUrl;
                imagePreview.style.display = 'block'; // Show the image
            };
            img.onerror = function() {
                console.error('Image failed to load:', imageUrl);
                imageError.style.display = 'block';
                imagePreview.src = ''; // Clear the preview
            };
            img.src = imageUrl; // Set the source to trigger loading
        }
        </script>
        <!-- Javascripts -->
        <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="assets/js/alpha.min.js"></script>

        <script>
        $(document).ready(function() {
            $('.modal').modal();
        });
        </script>

</body>

</html>