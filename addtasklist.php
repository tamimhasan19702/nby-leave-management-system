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

// Handle form submission for adding a new task
if (isset($_POST['addTask'])) {
    $taskName = $_POST['taskName'];
    $taskDescription = $_POST['taskDescription'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $notes = $_POST['notes'];
    $status = $_POST['status']; // Get status from form
    $empId = $eid; // Employee ID from session
    $progress = 0; // Default progress

    // Prepare SQL statement to insert new task
    $sql = "INSERT INTO tasklist (EmpId, TaskName, TaskDescription, Status, Progress, Notes, StartDate, EndDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $dbh->prepare($sql);
    $stmt->bind_param("isssssss", $empId, $taskName, $taskDescription, $status, $progress, $notes, $startDate, $endDate);

    if ($stmt->execute()) {
        $successMessage = "Task added successfully!";
    } else {
        $successMessage = "Error adding task: " . $stmt->error;
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

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- Updated link -->
    <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Theme Styles -->
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
    </style>

</head>

<body>
    <?php include('includes/header.php');?>
    <?php include('includes/sidebar.php');?>

    <main class="mn-inner nby-inner">
        <div class="row">
            <div class="col s 12">
                <h1 class="nby-title">Tasklist</h1>
            </div>
        </div>

        <!-- New Task Form -->
        <div class="row">
            <form method="POST" action="">
                <div class="input-field col s12">
                    <input type="text" name="taskName" required>
                    <label for="taskName">Task Name</label>
                </div>
                <div class="input-field col s12">
                    <input type="text" name="taskDescription">
                    <label for="taskDescription">Task Description</label>
                </div>
                <div class="input-field col s12">
                    <span>Start Date</span>
                    <input type="date" name="startDate" required>
                </div>

                <div class="input-field col s12">
                    <span>Status</span>
                    <select name="status" required>
                        <option value="0">Starting</option>
                        <option value="1">In Progress</option>
                        <option value="2">Pending</option>
                        <option value="3">Completed</option>
                    </select>
                </div>

                <div class="input-field col s12">
                    <span>End Date</span>
                    <input type="date" name="endDate">
                </div>
                <div class="input-field col s12">
                    <textarea name="notes" class="materialize-textarea"></textarea>
                    <label for="notes">Notes (optional)</label>
                </div>
                <div class="input-field col s12">
                    <button type="submit" name="addTask" class="btn">Add Task</button>
                </div>
            </form>
        </div>

        <!-- Success Modal -->
        <div id="successModal" class="modal modal-success">
            <div class="modal-content">
                <h4>Success</h4>
                <p><?php echo $successMessage; ?></p>
            </div>
        </div>

    </main>

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