<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {   
    header('location:index.php');
} else { 
    // Pagination setup
    $limit = 20; // Number of logs per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $limit; // Calculate offset for SQL query

    // Fetch total logs for pagination
    $totalQuery = "SELECT COUNT(*) FROM tblemployeelogs";
    $totalStmt = $dbh->prepare($totalQuery);
    $totalStmt->execute();
    $totalLogs = $totalStmt->fetchColumn();
    $totalPages = ceil($totalLogs / $limit); // Calculate total pages

    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>Admin | Manage Departments</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Theme Styles -->
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
            <div class="card">
                <div class="card-content">
                    <h1 class="nby-title">Employee Logs</h1>
                    <table id="logTable" class="display responsive-table">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Employee Name</th>
                                <th>Log Date (Day)</th> <!-- Updated header -->
                                <th>Login Time</th>
                                <th>Logout Time</th>
                                <th>Working Time</th>
                                <th>Action</th> <!-- New column for action -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                // Fetch all logs with pagination
                $query = "SELECT tblemployeelogs.id, tblemployeelogs.LogDate, tblemployeelogs.LoginTime, tblemployeelogs.LogoutTime, tblemployees.id AS empId, tblemployees.FirstName, tblemployees.LastName
                          FROM tblemployeelogs
                          INNER JOIN tblemployees ON tblemployeelogs.EmpId = tblemployees.id
                          ORDER BY tblemployeelogs.LogDate DESC, tblemployeelogs.LoginTime DESC
                          LIMIT :offset, :limit"; // Order by date and time

                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':offset', $offset, PDO::PARAM_INT); // Bind the offset
                $stmt->bindParam(':limit', $limit, PDO::PARAM_INT); // Bind the limit
                $stmt->execute();
                $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $srNo = $offset + 1; // Initialize serial number based on offset
                foreach ($logs as $log) {
                    // Format LogDate and get the day of the week
                    $logDateTime = new DateTime($log['LogDate']);
                    $logDate = $logDateTime->format('d-m-Y');
                    $dayOfWeek = $logDateTime->format('l'); // Get the full textual representation of the day

                    // Format LoginTime and LogoutTime
                    $loginTime = (new DateTime($log['LoginTime']))->format('h:i A');
                    $logoutTime = (new DateTime($log['LogoutTime']))->format('h:i A');

                    // Calculate Working Time
                    $workingTime = '';
                    if (!empty($log['LoginTime']) && !empty($log['LogoutTime'])) {
                        $loginDateTime = new DateTime($log['LoginTime']);
                        $logoutDateTime = new DateTime($log['LogoutTime']);
                        $interval = $loginDateTime->diff($logoutDateTime);
                        $workingTime = $interval->format('%h hours %i minutes');
                    } else {
                        $workingTime = 'Not Logged Out';
                    }

                    echo "<tr>";
                    echo "<td>" . $srNo++ . "</td>";
                    echo "<td>" . htmlentities($log['FirstName'] . ' ' . $log['LastName']) . "</td>";
                    echo "<td>" . htmlentities($logDate . " (" . $dayOfWeek . ")") . "</td>"; // Updated to include day of the week
                    echo "<td>" . htmlentities($loginTime) . "</td>";
                    echo "<td>" . (empty($log['LogoutTime']) ? 'Not Logged Out' : htmlentities($logoutTime)) . "</td>";
                    echo "<td>" . htmlentities($workingTime) . "</td>";
                    echo "<td><a href='viewprofile.php?empid=" . htmlentities($log['empId']) . "' class='waves-effect waves-light btn'>View Profile</a></td>"; // Button to view profile
                    echo "</tr>";
                }
                ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="left-sidebar-hover"></div>

    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
    <script src="../assets/js/pages/table-data.js"></script>

</body>

</html>
<?php } ?>