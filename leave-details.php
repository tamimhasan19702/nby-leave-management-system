<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for development
include('includes/config.php');

if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['apply'])) {
        $empid = $_SESSION['eid'];
        $leavetype = $_POST['leavetype'];
        $fromdate = $_POST['fromdate'];  
        $duration = intval($_POST['duration']); // Get duration in days
        $description = $_POST['description'];  
        $username = $_POST['username'];
        $emailId = $_POST['emailid'];
        $phonenumber = $_POST['phonenumber'];
        $status = 0;
        $isread = 0;

        // Calculate ToDate based on FromDate and Duration
        $fromDateTime = new DateTime($fromdate);
        $fromDateTime->modify("+$duration days"); // Add duration to FromDate
        $todate = $fromDateTime->format('d-m-Y'); // Format as d-m-Y
        $fromdate = (new DateTime($fromdate))->format('d-m-Y'); // Format fromdate as d-m-Y

        // Create duration string with suffix
        $durationString = $duration ;

        if ($duration <= 0) {
            $error = "Duration must be greater than 0";
        } else {
            $sql = "INSERT INTO tblleaves (LeaveType, ToDate, FromDate, Description, Status, IsRead, empid, Username, EmailId, Phonenumber, Duration) 
                    VALUES (:leavetype, :todate, :fromdate, :description, :status, :isread, :empid, :username, :emailid, :phonenumber, :duration)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':leavetype', $leavetype, PDO::PARAM_STR);
            $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
            $query->bindParam(':todate', $todate, PDO::PARAM_STR);
            $query->bindParam(':description', $description, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->bindParam(':isread', $isread, PDO::PARAM_STR);
            $query->bindParam(':empid', $empid, PDO::PARAM_STR);
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->bindParam(':emailid', $emailId, PDO::PARAM_STR);
            $query->bindParam(':phonenumber', $phonenumber, PDO::PARAM_STR);
            $query->bindParam(':duration', $durationString, PDO::PARAM_STR); // Bind duration with suffix
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                $msg = "Leave applied successfully";
                header('location:leavehistory.php'); // Redirect to leave history page
                exit(); // Ensure no further code is executed after redirection
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }

    $lid = intval($_GET['leaveid']);
    $sql = "SELECT tblleaves.id as lid, 
                   tblemployees.FirstName, 
                   tblemployees.LastName, 
                   tblemployees.EmpId, 
                   tblemployees.Gender, 
                   tblemployees.Phonenumber, 
                   tblemployees.EmailId,
                   tblemployees.Image, 
                   tblleaves.LeaveType, 
                   tblleaves.ToDate, 
                   tblleaves.FromDate, 
                   tblleaves.Description, 
                   tblleaves.PostingDate, 
                   tblleaves.Status, 
                   tblleaves.AdminRemark, 
                   tblleaves.AdminRemarkDate,
                   tblleaves.Duration 
            FROM tblleaves 
            JOIN tblemployees ON tblleaves.empid = tblemployees.id 
            WHERE tblleaves.id = :lid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':lid', $lid, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

 $leaveDetails = []; // Initialize an array to hold leave details

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
            $leaveDetails['EmployeeName'] = !empty($result->FirstName) && !empty($result->LastName) ? htmlentities($result->FirstName . " " . $result->LastName) : null;
            $leaveDetails['EmpId'] = !empty($result->EmpId) ? htmlentities($result->EmpId) : null;
            $leaveDetails['Gender'] = !empty($result->Gender) ? htmlentities($result->Gender) : null;
            $leaveDetails['EmailId'] = !empty($result->EmailId) ? htmlentities($result->EmailId) : null;
            $leaveDetails['Phonenumber'] = !empty($result->Phonenumber) ? htmlentities($result->Phonenumber) : null;
            $leaveDetails['LeaveType'] = !empty($result->LeaveType) ? htmlentities($result->LeaveType) : null;
            $leaveDetails['Image'] = !empty($result->Image) ? htmlentities($result->Image) : null;
            
            if (!empty($result->FromDate)) {
                $fromDateTime = new DateTime($result->FromDate);
                $leaveDetails['FromDate'] = $fromDateTime->format('d-m-Y') . ' (' . $fromDateTime->format('l') . ')';
            }
            
            if (!empty($result->ToDate)) {
                $toDateTime = new DateTime($result->ToDate);
                $leaveDetails['ToDate'] = $toDateTime->format('d-m-Y') . ' (' . $toDateTime->format('l') . ')';
            }
            
            $leaveDetails['Description'] = !empty($result->Description) ? htmlentities($result->Description) : null;
            
            if (!empty($result->PostingDate)) {
                $postingDateTime = new DateTime($result->PostingDate);
                $leaveDetails['PostingDate'] = $postingDateTime->format('d-m-Y') . ' (' . $postingDateTime->format('l') . ') - ' . $postingDateTime->format('h:i A');
            }
            
            $leaveDetails['Status'] = isset($result->Status) ? (int)$result->Status : null;
            $leaveDetails['AdminRemark'] = !empty($result->AdminRemark) ? htmlentities($result->AdminRemark) : "waiting for Approval";
            
            if (!empty($result->AdminRemarkDate)) {
                $adminRemarkDateTime = new DateTime($result->AdminRemarkDate);
                $leaveDetails['AdminRemarkDate'] = $adminRemarkDateTime->format('d-m-Y') . ' (' . $adminRemarkDateTime->format('l') . ') - ' . $adminRemarkDateTime->format('h:i A');
            } else {
                $leaveDetails['AdminRemarkDate'] = "NA";
            }
            
            $leaveDetails['Duration'] = !empty($result->Duration) ? htmlentities($result->Duration) : "NA";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee | Leave Details</title>
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
    <link href="assets/plugins/google-code-prettify/prettify.css" rel="stylesheet" type="text/css" />
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
            <div class="col s12">
                <div class="page-title" style="font-size:24px;">Leave Details</div>
            </div>

            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Leave Details</span>
                        <?php if (isset($msg) && $msg) { ?>
                        <div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div>
                        <?php } ?>
                        <div style="margin-bottom: 20px;">
                            <img src="<?php echo $leaveDetails['Image'] ?? 'N/A'; ?>" alt="Employee Image"
                                style="width: 100px; height: auto;">
                        </div>
                        <table id="example" class="display responsive-table ">
                            <tbody>
                                <tr>
                                    <td style="font-size:16px;"> <b>Employee Name :</b></td>
                                    <td><?php echo $leaveDetails['EmployeeName'] ?? 'N/A'; ?></td>
                                    <td style="font-size:16px;"><b>Emp Id :</b></td>
                                    <td><?php echo $leaveDetails['EmpId'] ?? 'N/A'; ?></td>
                                    <td style="font-size:16px;"><b>Gender :</b></td>
                                    <td><?php echo $leaveDetails['Gender'] ?? 'N/A'; ?></td>
                                </tr>

                                <tr>
                                    <td style="font-size:16px;"><b>Emp Email id :</b></td>
                                    <td><?php echo $leaveDetails['EmailId'] ?? 'N/A'; ?></td>
                                    <td style="font-size:16px;"><b>Emp Contact No. :</b></td>
                                    <td><?php echo $leaveDetails['Phonenumber'] ?? 'N/A'; ?></td>
                                    <td style="font-size:16px;"><b>Leave Duration:</b></td>
                                    <td><?php echo $leaveDetails['Duration'] ? $leaveDetails['Duration']  : 'N/A'; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-size:16px;"><b>Leave Type :</b></td>
                                    <td><?php echo $leaveDetails['LeaveType'] ?? 'N/A'; ?></td>
                                    <td style="font-size:16px;"><b>Leave Date :</b></td>
                                    <td><span style="font-weight:600">From -</span>
                                        <?php echo $leaveDetails['FromDate'] ?? 'N/A'; ?>
                                        (<?php echo $leaveDetails['FromDateWeekday'] ?? 'N/A'; ?>)<span
                                            style="font-weight:600"> - To
                                            -</span>
                                        <?php echo $leaveDetails['ToDate'] ?? 'N/A'; ?>
                                        (<?php echo $leaveDetails['ToDateWeekday'] ?? 'N/A'; ?>)</td>
                                    <td style="font-size:16px;"><b>Posting Date</b></td>
                                    <td><?php echo $leaveDetails['PostingDate'] ?? 'N/A'; ?></td>
                                </tr>

                                <tr>
                                    <td style="font-size:16px;"><b>Employee Leave Description :</b></td>
                                    <td colspan="5"><?php echo $leaveDetails['Description'] ?? 'N/A'; ?></td>
                                </tr>

                                <tr>
                                    <td style="font-size:16px;"><b>Leave Status :</b></td>
                                    <td colspan="5">
                                        <?php 
                                        $stats = $leaveDetails['Status'] ?? null;
                                        if ($stats === 1) {
                                            echo "<span style='color: green'>Approved</span>";
                                        } elseif ($stats === 2) {
                                            echo "<span style='color: red'>Not Approved</span>";
                                        } elseif ($stats === 0) {
                                            echo "<span style='color: blue'>Waiting for approval</span>";
                                        } else {
                                            echo "<span style='color: gray'>Status not available</span>";
                                        }?>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="font-size:16px;"><b>Admin Remark:</b></td>
                                    <td colspan="5"><?php echo $leaveDetails['AdminRemark'] ?? 'N/A'; ?></td>
                                </tr>

                                <tr>
                                    <td style="font-size:16px;"><b>Admin Action taken date:</b></td>
                                    <td colspan="5"><?php echo $leaveDetails['AdminRemarkDate'] ?? 'N/A'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>
    <div class="left-sidebar-hover"></div>

    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src=" assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/alpha.min.js"></script>
    <script src="assets/js/pages/table-data.js"></script>
    <script src="assets/js/pages/ui-modals.js"></script>
    <script src="assets/plugins/google-code-prettify/prettify.js"></script>

</body>

</html>