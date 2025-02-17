<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{

    $adminUserName = $_SESSION['alogin'];
    
    $sql = "SELECT id, FirstName, LastName, UserName, EmailId, Image FROM admin WHERE UserName = :username"; // Adjust the query to match your database schema
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $adminUsername, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    // Check if the result is found
    if ($result) {
        $adminId = $result->id;
        $adminUserName = $result->UserName;
        $adminName = $result->FirstName . ' ' . $result->LastName;
        $adminEmail = $result->EmailId;
        $adminImage = $result->Image;
    } else {
        $adminEmail = "Email not found"; // Handle case where email is not found
        $adminUserName = "User not found"; // Handle case where username is not found
        $adminImage = "default.png"; // Default image if not found
    }


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>Admin | Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="
        " />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/metrojs/MetroJs.min.css" rel="stylesheet">
    <link href="../assets/plugins/weather-icons-master/css/weather-icons.min.css" rel="stylesheet">


    <!-- Theme Styles -->
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />

</head>

<body>
    <?php include('includes/header.php');?>

    <?php include('includes/sidebar.php');?>

    <main class="mn-inner">
        <div class="">
            <div class="row no-m-t no-m-b">

                <h1 class="nby-title">Welcome, <?php echo $adminName; ?></h1>

                <a href="manageemployee.php" target="blank">

                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">

                                <span class="card-title">Total NBY IT Employees</span>
                                <span class="stats-counter">
                                    <?php
$sql = "SELECT id from tblemployees";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$empcount=$query->rowCount();
?>

                                    <span class="counter"><?php echo htmlentities($empcount);?></span></span>
                            </div>
                            <div class="progress stats-card-progress">
                                <div class="determinate" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="managedepartments.php" target="blank">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">

                                <span class="card-title">Listed Departments </span>
                                <?php
$sql = "SELECT id from tbldepartments";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$dptcount=$query->rowCount();
?>
                                <span class="stats-counter"><span
                                        class="counter"><?php echo htmlentities($dptcount);?></span></span>
                            </div>
                            <div class="progress stats-card-progress">
                                <div class="determinate" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </a>


                <a href="managenotice.php" target="blank">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">Listed Notices</span>
                                <?php
$sql = "SELECT id from  notices";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$noticount=$query->rowCount();
?>
                                <span class="stats-counter"><span
                                        class="counter"><?php echo htmlentities($noticount);?></span></span>

                            </div>
                            <div class="progress stats-card-progress">
                                <div class="determinate" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </a>



                <a href="leaves.php" target="blank">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">Total Leaves</span>
                                <?php
$sql = "SELECT id from  tblleavestest";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$totalleaves=$query->rowCount();
?>
                                <span class="stats-counter"><span
                                        class="counter"><?php echo htmlentities($totalleaves);?></span></span>

                            </div>
                            <div class="progress stats-card-progress">
                                <div class="success" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="approvedleave-history.php" target="blank">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">Approved Leaves</span>
                                <?php
$sql = "SELECT id from  tblleavestest where Status=1";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$approvedleaves=$query->rowCount();
?>
                                <span class="stats-counter"><span
                                        class="counter"><?php echo htmlentities($approvedleaves);?></span></span>

                            </div>
                            <div class="progress stats-card-progress">
                                <div class="success" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </a>



                <a href="pending-leavehistory.php" target="blank">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">New Leaves Applications</span>
                                <?php
$sql = "SELECT id from  tblleavestest where Status=0";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$approvedleaves=$query->rowCount();
?>
                                <span class="stats-counter"><span
                                        class="counter"><?php echo htmlentities($approvedleaves);?></span></span>

                            </div>
                            <div class="progress stats-card-progress">
                                <div class="success" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </a>


            </div>



            <div class="row no-m-t no-m-b">
                <div class="col s15 m12 l12">
                    <div class="card invoices-card">
                        <div class="card-content">
                            <span class="card-title">Latest Leave Applications</span>
                            <table id="example" class="display responsive-table ">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th width="200">Employee Name</th>
                                        <th width="120">Leave Type</th>
                                        <th width="180">Posting Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php 
                        $sql = "SELECT tblleavestest.id as lid, 
                                       tblemployees.FirstName, 
                                       tblemployees.LastName, 
                                       tblemployees.EmpId, 
                                       tblleavestest.LeaveType, 
                                       tblleavestest.PostingDate, 
                                       tblleavestest.Status 
                                FROM tblleavestest 
                                JOIN tblemployees ON tblleavestest.empid = tblemployees.id 
                                ORDER BY lid DESC 
                                LIMIT 6";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;
                        if ($query->rowCount() > 0) {
                            foreach ($results as $result) {         
                        ?>
                                    <tr>
                                        <td><b><?php echo htmlentities($cnt); ?></b></td>
                                        <td>
                                            <a href="viewprofile.php?empid=<?php echo htmlentities($result->empid); ?>"
                                                target="_blank">
                                                <?php echo htmlentities($result->FirstName . " " . $result->LastName); ?>(<?php echo htmlentities($result->EmpId); ?>)
                                            </a>
                                        </td>
                                        <td><?php echo htmlentities($result->LeaveType); ?></td>
                                        <td><?php echo htmlentities((new DateTime($result->PostingDate))->format('d-m-Y - h:i A - (l)')); ?>
                                        </td>
                                        <td>
                                            <?php 
                                $stats = $result->Status;
                                if ($stats == 1) {
                                    echo '<span style="color: green">Approved</span>';
                                } elseif ($stats == 2) {
                                    echo '<span style="color: red">Not Approved</span>';
                                } elseif ($stats == 0) {
                                    echo '<span style="color: blue">Waiting for approval</span>';
                                }
                                ?>
                                        </td>
                                        <td>
                                            <a href="leave-details.php?leaveid=<?php echo htmlentities($result->lid); ?>"
                                                class="waves-effect waves-light btn blue m-b-xs">View Details</a>
                                        </td>
                                    </tr>
                                    <?php 
                                $cnt++;
                            }
                        } 
                        ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



    </main>

    </div>



    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/plugins/waypoints/jquery.waypoints.min.js"></script>
    <script src="../assets/plugins/counter-up-master/jquery.counterup.min.js"></script>
    <script src="../assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="../assets/plugins/chart.js/chart.min.js"></script>
    <script src="../assets/plugins/flot/jquery.flot.min.js"></script>
    <script src="../assets/plugins/flot/jquery.flot.time.min.js"></script>
    <script src="../assets/plugins/flot/jquery.flot.symbol.min.js"></script>
    <script src="../assets/plugins/flot/jquery.flot.resize.min.js"></script>
    <script src="../assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="../assets/plugins/curvedlines/curvedLines.js"></script>
    <script src="../assets/plugins/peity/jquery.peity.min.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
    <script src="../assets/js/pages/dashboard.js"></script>

</body>

</html>
<?php } ?>