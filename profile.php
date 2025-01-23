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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    $profileImage = $_POST['profileImage']; // Get the new profile image link

    // Prepare the SQL statement to update employee details
    $updateSql = "UPDATE tblemployees SET FirstName = :firstName, LastName = :lastName, EmailId = :emailId, 
                  Username = :username, Dob = :dob, Department = :department, Address = :address, 
                  City = :city, Country = :country, Phonenumber = :phoneNumber, Image = :profileImage 
                  WHERE id = :eid";

    $updateQuery = $dbh->prepare($updateSql);
    $updateQuery->bindParam(':firstName', $firstName);
    $updateQuery->bindParam(':lastName', $lastName);
    $updateQuery->bindParam(':emailId', $emailId);
    $updateQuery->bindParam(':username', $username); // Bind the username
    $updateQuery->bindParam(':dob', $dob); // Bind the date of birth
    $updateQuery->bindParam(':department', $department);
    $updateQuery->bindParam(':address', $address);
    $updateQuery->bindParam(':city', $city);
    $updateQuery->bindParam(':country', $country);
    $updateQuery->bindParam(':phoneNumber', $phoneNumber);
    $updateQuery->bindParam(':profileImage', $profileImage); // Bind the profile image link
    $updateQuery->bindParam(':eid', $eid, PDO::PARAM_INT);
    
    if ($updateQuery->execute()) {
        $successMessage = "Profile updated successfully!";
    } else {
        echo "<div class='errorWrap'>Error updating profile. Please try again.</div>";
    }
}

// Prepare the SQL statement to fetch employee details
$sql = "SELECT EmpId, FirstName, LastName, EmailId, Gender, Dob, Department, Address, City, Country, Phonenumber, Username, Image 
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
    $image = $result->Image;
} else {
    echo "No employee found with the given ID.";
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
                <form method="post" action="">
                    <input type="hidden" name="eid" value="<?php echo htmlentities($eid); ?>">
                    <div class="nby-input-field">
                        <label for="profileImage">Profile Picture Link</label>
                        <input type="url" name="profileImage" id="profileImage"
                            value="<?php echo htmlentities($image); ?>" required class="short-input"
                            oninput="previewImage()">
                    </div>
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
                        <button type="submit" class="waves-effect waves-light btn">Update</button>
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
                // If the image loads successfully, set the preview
                imagePreview.src = imageUrl;
            };
            img.onerror = function() {
                // If the image fails to load, show an error message
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