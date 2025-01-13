<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['emplogin'])==0)
    {   
header('location:index.php');
}
else{
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>Employee | Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="
        " />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="assets/plugins/metrojs/MetroJs.min.css" rel="stylesheet">
    <link href="assets/plugins/weather-icons-master/css/weather-icons.min.css" rel="stylesheet">


    <!-- Theme Styles -->
    <link href="assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />

</head>

<body>
    <?php include('includes/header.php');?>

    <?php include('includes/sidebar.php');?>

    <main class="mn-inner">
        <div class="">
            <div class="row no-m-t no-m-b">




                <a href="leavehistory.php" target="blank">
                    <div class="col s12 m12 l4">


                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">Total Leaves</span>
                                <?php $eid=$_SESSION['eid'];
$sql = "SELECT id from  tblleaves where empid ='$eid'";
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

                <a href="leavehistory.php" target="blank">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">Approved Leaves</span>
                                <?php
$sql = "SELECT id from  tblleaves where Status=1 and empid ='$eid'";
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



                <a href="leavehistory.php" target="blank">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">New Leaves Applications</span>
                                <?php
$sql = "SELECT id from  tblleaves where Status=0 and empid ='$eid'";
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


                <a href="leavehistory.php" target="blank">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">Enjoyed Leaves</span>
                                <div class="nby-remaining">
                                    <span class="stats-counter">


                                        <?php
                if (isset($_SESSION['eid'])) {
                    $employee_id = $_SESSION['eid'];

                    // Fetch the current leave balance and initial leave values
                    $sql = "SELECT AnnualLeave, SickLeave FROM tblemployees WHERE id = :employee_id";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
                    $query->execute();

                    $result = $query->fetch(PDO::FETCH_ASSOC);

                    $EnjoyedAnnualLeave = 22 - $result['AnnualLeave'];
                    $EnjoyedSickLeave = 7 - $result['SickLeave'];
                    
                    if ($result) {
                        $annualLeave = $result['AnnualLeave'];
                        $sickLeave = $result['SickLeave'];
                        
                    } else {
                        // Handle case where no employee is found
                        echo "No employee found.";
                    }
                } else {
                    // Handle case where session is not set
                    echo "User  not logged in.";
                }
                ?>

                                        <p>Annual Leave</p>
                                        <span class="counter"
                                            id="enjoyedAnnualLeave"><?php echo htmlentities($EnjoyedAnnualLeave);?></span>
                                    </span>
                                    <span class="stats-counter">
                                        <p>Sick Leave</p>
                                        <span class="counter"
                                            id="enjoyedSickLeave"><?php echo htmlentities($EnjoyedSickLeave);?></span>
                                    </span>
                                </div>
                            </div>
                            <div class="progress stats-card-progress">
                                <div class="success" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="leavehistory.php" target="blank">
                    <div class="col s12 m12 l4">
                        <div class="card stats-card">
                            <div class="card-content">
                                <span class="card-title">Remaining Leaves</span>


                                <?php 
if (isset($_SESSION['eid'])) {
    $employee_id = $_SESSION['eid'];
    
    $sql = "SELECT * FROM tblleaves WHERE empid = :employee_id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT); // Bind the employee ID
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all matching rows as an associative array

    $totalDuration = 0; // Variable to store the sum of durations

    foreach ($result as $row) {
        // Extract the numeric value of duration (assuming format like "2 days")
        preg_match('/\d+/', $row['Duration'], $matches);
        $duration = isset($matches[0]) ? (int)$matches[0] : 0;

        $totalDuration += $duration; // Add to total
    }

    $remainingAnnualLeave = 22 - $totalDuration;
    $remainingSickLeave = 7 - $totalDuration;

    echo "<br><strong>Total Duration:</strong> " . $totalDuration . " days";

} else {
    echo "Employee ID is not set in the session.";
}
?>





                                <div class="nby-remaining">
                                    <span class="stats-counter">
                                        <p>Annual Leave</p>
                                        <span class="counter"
                                            id="remainingAnnualLeave"><?php echo htmlentities($annualLeave); ?></span>
                                    </span>
                                    <span class="stats-counter">
                                        <p>Sick Leave</p>
                                        <span class="counter"
                                            id="remainingSickLeave"><?php echo htmlentities($sickLeave); ?></span>
                                    </span>
                                </div>
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
                                        <th width="50">No</th>
                                        <th width="200">Employe Name</th>
                                        <th width="120">Leave Type</th>
                                        <th width="180">Posting Date</th>
                                        <th width="180">Status</th>
                                        <th width="180">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $sql ="SELECT tblleaves.id as lid,tblemployees.FirstName,tblemployees.LastName,tblemployees.EmpId,tblemployees.id,tblleaves.LeaveType,tblleaves.PostingDate,tblleaves.Status from tblleaves join tblemployees on tblleaves.empid=tblemployees.id where tblleaves.empid='$eid' order by lid desc limit 6";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{         
      ?>

                                    <tr>
                                        <td> <b><?php echo htmlentities($cnt);?></b></td>
                                        <td><a href="profile.php"
                                                target="_blank"><?php echo htmlentities($result->FirstName." ".$result->LastName);?>(<?php echo htmlentities($result->EmpId);?>)</a>
                                        </td>
                                        <td><?php echo htmlentities($result->LeaveType);?></td>
                                        <td><?php echo htmlentities($result->PostingDate);?></td>
                                        <td><?php $stats=$result->Status;
                                        if($stats==1){
                                             ?>
                                            <span style="color: green">Approved</span>
                                            <?php } if($stats==2)  { ?>
                                            <span style="color: red">Not Approved</span>
                                            <?php } if($stats==0)  { ?>
                                            <span style="color: blue">waiting for approval</span>
                                            <?php } ?>


                                        </td>


                                        <td><a href="leave-details.php?leaveid=<?php echo htmlentities($result->lid);?>"
                                                class="waves-effect waves-light btn blue m-b-xs"> View Details</a></td>
                                    </tr>
                                    <?php $cnt++;} }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    </div>



    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/plugins/waypoints/jquery.waypoints.min.js"></script>
    <script src="assets/plugins/counter-up-master/jquery.counterup.min.js"></script>
    <script src="assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="assets/plugins/chart.js/chart.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.time.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.symbol.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.resize.min.js"></script>
    <script src="assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="assets/plugins/curvedlines/curvedLines.js"></script>
    <script src="assets/plugins/peity/jquery.peity.min.js"></script>
    <script src="assets/js/alpha.min.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>

</body>

</html>
<?php } ?>