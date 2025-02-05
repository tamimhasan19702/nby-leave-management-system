<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for development
ini_set('display_errors', 1);
include('includes/config.php');

// Check if the user is logged in
if (strlen($_SESSION['emplogin']) == 0) {   
    header('location:index.php'); // Redirect to login page if not logged in
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $empId = $_SESSION['eid']; // Assuming you store employee ID in session
    $complaintTitle = $_POST['complaint_title'];
    $complaint = $_POST['complaint'];
    
    // Prepare the SQL query
    $sql = "INSERT INTO `complaints` (`empId`, `complaint_title`, `complaint`) VALUES (:empId, :complaint_title, :complaint)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':empId', $empId, PDO::PARAM_INT);
    $query->bindParam(':complaint_title', $complaintTitle, PDO::PARAM_STR);
    $query->bindParam(':complaint', $complaint, PDO::PARAM_STR);

    // Execute the query
    if ($query->execute()) {
        $msg = "Complaint submitted successfully";
    } else {
        $errorMessage = "Error submitting complaint.";
    }
}


if (isset($_GET['id'])) {
    $complaintId = $_GET['id'];
    $empId = $_SESSION['eid']; // Assuming you want to check if the user is authorized to delete

    // Prepare the SQL statement to delete the complaint
    $sql = "DELETE FROM complaints WHERE id = :id AND empId = :empId";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $complaintId, PDO::PARAM_INT);
    $query->bindParam(':empId', $empId, PDO::PARAM_INT);

    if ($query->execute()) {
        $msg = "Complaint deleted successfully";
    } else {
        $msg = "Error deleting complaint";
    }
}

// Fetch complaints
$empId = $_SESSION['eid'];
$sql = "SELECT * FROM complaints WHERE empId = :empId";
$query = $dbh->prepare($sql);
$query->bindParam(':empId', $empId, PDO::PARAM_INT);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee | Complaint Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
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
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <?php if(isset($msg)){?><div class="succWrap"><strong>SUCCESS</strong> :
                            <?php echo htmlentities($msg); ?> </div><?php } ?>
                        <?php if (isset($errorMessage)) { ?>
                        <div class="errorWrap"><?php echo $errorMessage; ?></div>
                        <?php } ?>
                        <form method="POST" action="">
                            <div class="input-field nby-complaint">
                                <span for="complaint_title">Complaint Subject</span>
                                <input type="text" id="complaint_title" name="complaint_title" required>
                            </div>
                            <div class="input-field nby-complaint">
                                <span for="complaint">Write your Complaint</span>
                                <textarea id="complaint" name="complaint" class="materialize-textarea"
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn">Submit Complaint</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Your Complaints</span>
                        <?php if (isset($msg)): ?>
                        <div class="alert">
                            <?php echo htmlentities($msg); ?>
                        </div>
                        <?php endif; ?>
                        <table class="responsive-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Complaint Subject</th>
                                    <th>Complaint</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                        $count = 1;
                        if ($query->rowCount() > 0) {
                            foreach ($results as $result) { ?>
                                <tr>
                                    <td style="width: 5%"><?php echo $count++; ?></td>
                                    <td style="width: 25%"><?php echo htmlentities($result->complaint_title); ?></td>
                                    <td style="width: 65%">
                                        <?php echo htmlentities($result->complaint); ?>
                                    </td>
                                    <td style="width: 10%">
                                        <a href="emp-complain.php?id=<?php echo $result->id; ?>"
                                            class="waves-effect waves-light btn red"
                                            onclick="return confirm('Are you sure you want to delete this complaint?');">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    </td>
                                </tr>
                                <?php }
                        } else { ?>
                                <tr>
                                    <td colspan="4">No complaints found.</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
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
</body>

</html>