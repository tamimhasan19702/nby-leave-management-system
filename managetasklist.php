<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/config.php');

// Check if the user is logged in
if (strlen($_SESSION['emplogin']) == 0) {   
    header('location:index.php'); // Redirect to login page if not logged in
    exit();
}

// Get the employee ID from the session
$eid = $_SESSION['eid'];

// Fetch all tasks for the logged-in employee
$sql = "SELECT * FROM tasklist WHERE EmpId = :empId";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':empId', $eid, PDO::PARAM_INT);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee | Task List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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

    <main class="mn-inner">
        <div class="row">
            <div class="col s12 nby-task-title">
                <h1 class="nby-title">Manage Task List</h1>
                <a href="addtasklist.php" class="btn">Add New Task</a> <!-- Button to add task -->
            </div>
        </div>

        <div class="row">
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Task List</span>
                        <table id="taskTable" class="display responsive-table">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Task Name</th>
                                    <th>Task Description</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Notes</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th> <!-- New column for actions -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $srNo = 1; // Initialize serial number
                                foreach ($tasks as $task) {
                                    echo "<tr>";
                                    echo "<td>" . $srNo++ . "</td>";
                                    echo "<td>" . htmlentities($task['TaskName']) . "</td>";
 echo "<td>" . htmlentities($task['TaskDescription']) . "</td>";
 echo "<td>";
 switch ($task['Status']) {
     case 0:
         echo "Starting";
         break;
     case 1:
         echo "In Progress";
         break;
     case 2:
         echo "Pending";
         break;
     case 3:
         echo "Completed";
         break;
     default:
         echo "Unknown Status";
         break;
 }
 echo "</td>";
                                    echo "<td>";
                                    // Determine progress based on status
                                    switch ($task['Status']) {
                                        case 0:
                                            echo "Starting (25%)";
                                            echo '<div class="progress"><div class="determinate" style="width: 25%"></div></div>';
                                            break;
                                        case 1:
                                            echo "In Progress (50%)";
                                            echo '<div class="progress"><div class="determinate" style="width: 50%"></div></div>';
                                            break;
                                        case 2:
                                            echo "Pending (50%)";
                                            echo '<div class="progress"><div class="determinate" style="width: 50%"></div></div>';
                                            break;
                                        case 3:
                                            echo "Completed (100%)";
                                            echo '<div class="progress"><div class="determinate" style="width: 100%"></div></div>';
                                            break;
                                        default:
                                            echo "Unknown Status";
                                            break;
                                    }
                                    echo "</td>";
                                    echo "<td>" . htmlentities($task['Notes']) . "</td>";
                                    echo "<td>" . htmlentities($task['StartDate']) . "</td>";
                                    echo "<td>" . htmlentities($task['EndDate']) . "</td>";
                                    echo "<td><a href='edittasklist.php?empId=" . $eid . "&taskId=" . $task['id'] . "' class='btn'>Edit</a></td>"; // Edit button
                                    echo "</tr>";
                                }
                                ?>
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
    <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/alpha.min.js"></script>
    <script src="assets/js/pages/table-data.js"></script>

</body>

</html>