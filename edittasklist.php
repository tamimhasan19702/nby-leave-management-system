<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/config.php');

// Check if the user is logged in
if (!isset($_SESSION['eid'])) {
    header('location: index.php'); // Redirect to login page if not logged in
    exit();
}

// Get the employee ID and task ID from the URL
$empId = isset($_GET['empId']) ? intval($_GET['empId']) : 0;
$taskId = isset($_GET['taskId']) ? intval($_GET['taskId']) : 0;

// Initialize a variable to hold the success message
$successMessage = "";

// Fetch the specific task details
$sql = "SELECT * FROM tasklist WHERE EmpId = :empId AND id = :taskId";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':empId', $empId, PDO::PARAM_INT);
$stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the task exists
if (!$task) {
    echo "Task not found.";
    exit();
}

// Handle form submission for updating the task
if (isset($_POST['updateTask'])) {
    $taskName = $_POST['taskName'];
    $taskDescription = $_POST['taskDescription'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $notes = $_POST['notes'];
    $status = $_POST['status']; // Get status from form
    $progress = $task['Progress']; // Keep the existing progress

    // Prepare SQL statement to update the task
    $sql = "UPDATE tasklist SET TaskName = :taskName, TaskDescription = :taskDescription, Status = :status, Progress = :progress, Notes = :notes, StartDate = :startDate, EndDate = :endDate WHERE EmpId = :empId AND id = :taskId";
    $stmt = $dbh->prepare($sql);
    
    // Bind parameters
    $stmt->bindValue(':taskName', $taskName, PDO::PARAM_STR);
    $stmt->bindValue(':taskDescription', $taskDescription, PDO::PARAM_STR);
    $stmt->bindValue(':status', $status, PDO::PARAM_STR);
    $stmt->bindValue(':progress', $progress, PDO::PARAM_INT);
    $stmt->bindValue(':notes', $notes, PDO::PARAM_STR);
    $stmt->bindValue(':startDate', $startDate, PDO::PARAM_STR);
    $stmt->bindValue(':endDate', $endDate, PDO::PARAM_STR);
    $stmt->bindValue(':empId', $empId, PDO::PARAM_INT);
    $stmt->bindValue(':taskId', $taskId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $successMessage = "Task updated successfully!";
        header('location:managetasklist.php'); // Redirect to task list page
        exit();
    } else {
        $successMessage = "Error updating task: " . $stmt->errorInfo()[2]; // Get error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>NBYIT | Edit Task</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
            <div class="col s12">
                <h1 class="nby-title">Edit Task</h1>
            </div>
        </div>

        <!-- Edit Task Form -->
        <div class="row">
            <form method="POST" action="">
                <div class="input-field col s12">
                    <input type="text" name="taskName" value="<?php echo htmlentities($task['TaskName']); ?>" required>
                    <label for="taskName">Task Name</label>
                </div>
                <div class="input-field col s12">
                    <input type="text" name="taskDescription"
                        value="<?php echo htmlentities($task['TaskDescription']); ?>">
                    <label for="taskDescription">Task Description</label>
                </div>
                <div class="input-field col s12">
                    <span>Start Date</span>
                    <input type="date" name="startDate" value="<?php echo htmlentities($task['StartDate']); ?>"
                        required>
                </div>

                <div class="input-field col s12">
                    <span>Status</span>
                    <select name="status" required class="browser-default">
                        <option value="0" <?php echo ($task['Status'] == 0) ? 'selected' : ''; ?>>Starting</option>
                        <option value="1" <?php echo ($task['Status'] == 1) ? 'selected' : ''; ?>>In Progress</option>
                        <option value="2" <?php echo ($task['Status'] == 2) ? 'selected' : ''; ?>>Pending</option>
                        <option value="3" <?php echo ($task['Status'] == 3) ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>

                <div class="input-field col s12">
                    <span>End Date</span>
                    <input type="date" name="endDate" value="<?php echo htmlentities($task['EndDate']); ?>">
                </div>
                <div class="input-field col s12">
                    <textarea name="notes"
                        class="materialize-textarea"><?php echo htmlentities($task['Notes']); ?></textarea>
                    <label for="notes">Notes (optional)</label>
                </div>
                <div class="input-field col s12">
                    <button type="submit" name="updateTask" class="btn">Update Task</button>
                </div>
            </form>
        </div>

    </main>

    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/js/alpha.min.js"></script>

</body>

</html>