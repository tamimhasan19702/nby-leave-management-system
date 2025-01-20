<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
} else {
    $eid = intval($_GET['empid']);
    
    // Fetch the employee details
    $sql = "SELECT * FROM tblemployees WHERE id = :eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    // Check if the employee exists
    if (!$result) {
        echo "Employee not found.";
        exit();
    }

    // Save each result value in separate variables
    $employeeId = $result->EmpId;
    $firstName = $result->FirstName;
    $lastName = $result->LastName;
    $email = $result->EmailId;
    $username = $result->Username;
    $phoneNumber = $result->Phonenumber;
    $gender = $result->Gender;
    $dob = $result->Dob;
    $department = $result->Department;
    $address = $result->Address;
    $city = $result->City;
    $country = $result->Country;
    $image = $result->Image;
    $status = $result->Status;
    $regDate = $result->RegDate;
    $annualLeave = $result->AnnualLeave;
    $sickLeave = $result->SickLeave;

    // Fetch the employee's task list
    $taskSql = "SELECT * FROM tasklist WHERE empId = :eid"; // Assuming you have a tasks table
    $taskQuery = $dbh->prepare($taskSql);
    $taskQuery->bindParam(':eid', $eid, PDO::PARAM_INT);
    $taskQuery->execute();
    $tasks = $taskQuery->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>Admin | View Employee Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- Updated to HTTPS -->
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
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12 nby-view-profile">
                <h1 class="nby-title">View Employee Profile</h1>
                <a href="editemployee.php?empid=<?php echo htmlentities($employeeId); ?>" class="btn">Edit Employee</a>
            </div>

            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Employee Details</span>
                        <table class="striped">
                            <tbody>
                                <tr>
                                    <td><b>Employee Code:</b></td>
                                    <td><?php echo htmlentities($employeeId); ?></td>
                                    <td><b>Image:</b></td>
                                    <td><img src="<?php echo htmlentities($image); ?>" alt="Employee Image"
                                            style="width: 50px; height: 50px;"></td>
                                </tr>
                                <tr>
                                    <td><b>First Name:</b></td>
                                    <td><?php echo htmlentities($firstName); ?></td>
                                    <td><b>Annual Leave:</b></td>
                                    <td><?php echo htmlentities($annualLeave); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Last Name:</b></td>
                                    <td><?php echo htmlentities($lastName); ?></td>
                                    <td><b>Sick Leave:</b></td>
                                    <td><?php echo htmlentities($sickLeave); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Username:</b></td>
                                    <td><?php echo htmlentities($username); ?></td>
                                    <td><b>Status:</b></td>
                                    <td><?php echo htmlentities($status); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Email:</b></td>
                                    <td><?php echo htmlentities($email); ?></td>
                                    <td><b>Registration Date:</b></td>
                                    <td><?php echo htmlentities($regDate); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Mobile Number:</b></td>
                                    <td><?php echo htmlentities($phoneNumber); ?></td>
                                    <td><b>Department:</b></td>
                                    <td><?php echo htmlentities($department); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Gender:</b></td>
                                    <td><?php echo htmlentities($gender); ?></td>
                                    <td><b>Date of Birth:</b></td>
                                    <td><?php echo htmlentities($dob); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Address:</b></td>
                                    <td><?php echo htmlentities($address); ?></td>
                                    <td><b>City:</b></td>
                                    <td><?php echo htmlentities($city); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Country:</b></td>
                                    <td><?php echo htmlentities($country); ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l12 ">

                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Employee Logs</span>
                            <table id="logTable" class="display responsive-table">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Employee ID</th>
                                        <th>Log Date</th>
                                        <th>Login Time</th>
                                        <th>Logout Time</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

$query = "SELECT tblemployeelogs.id, tblemployeelogs.LogDate, tblemployeelogs.LoginTime, tblemployeelogs.LogoutTime, tblemployees.FirstName, tblemployees.LastName
FROM tblemployeelogs
INNER JOIN tblemployees ON tblemployeelogs.EmpId = tblemployees.id
WHERE tblemployeelogs.EmpId = :eid"; // Filter by employee ID
$stmt = $dbh->prepare($query);
$stmt->bindParam(':eid', $eid, PDO::PARAM_INT); // Bind the employee ID
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $srNo = 1; // Initialize serial number
                foreach ($logs as $log) {
                    echo "<tr>";
                    echo "<td>" . $srNo++ . "</td>";
                    echo "<td>" . htmlentities($log['FirstName'] . ' ' . $log['LastName']) . "</td>";
                    echo "<td>" . htmlentities($log['LogDate']) . "</td>";
                    echo "<td>" . htmlentities($log['LoginTime']) . "</td>";
                    echo "<td>" . (empty($log['LogoutTime']) ? 'Not Logged Out' : htmlentities($log['LogoutTime'])) . "</td>";
                    
                    echo "</tr>";
                }
                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


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
                                        
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/js/alpha.min.js"></script>

</body>

</html>