<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for development
include('includes/config.php');

if (strlen($_SESSION['emplogin']) == 0) {
    header('location:index.php');
} else {
    $lid = intval($_GET['leaveid']);
    $sql = "SELECT tblleavestest.id as lid, 
                   tblemployees.FirstName, 
                   tblemployees.LastName, 
                   tblemployees.EmpId, 
                   tblemployees.Gender, 
                   tblemployees.Phonenumber, 
                   tblemployees.EmailId,
                   tblemployees.Image, 
                   tblleavestest.LeaveType, 
                   tblleavestest.LeaveDates, 
                   tblleavestest.Description, 
                   tblleavestest.PostingDate, 
                   tblleavestest.Status, 
                   tblleavestest.AdminRemark, 
                   tblleavestest.AdminRemarkDate,
                   tblleavestest.Duration 
            FROM tblleavestest 
            JOIN tblemployees ON tblleavestest.empid = tblemployees.id 
            WHERE tblleavestest.id = :lid";
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

            // Decode LeaveDates JSON
            $leaveDates = json_decode($result->LeaveDates);
            $formattedLeaveDates = [];
            if (is_array($leaveDates)) {
                foreach ($leaveDates as $date) {
                    $dateTime = new DateTime($date);
                    $formattedLeaveDates[] = $dateTime->format('d-m-Y') . ' (' . $dateTime->format('l') . ')';
                }
            }
            $leaveDetails['LeaveDates'] = implode(", ", $formattedLeaveDates);

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
                        <div
                            style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                            <img src="<?php echo $leaveDetails['Image'] ?? 'N/A'; ?>" alt="Employee Image"
                                style="width: 100px; height: auto;">


                            <a href="emp-edit-leavedetails.php?leaveid=<?php echo $lid; ?>"
                                class="waves-effect waves-light btn indigo m-b-xs">Edit Leave Details</a>

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
                                    <td style="font-size:16px;"><b>Leave Dates :</b></td>
                                    <td><?php echo $leaveDetails['LeaveDates'] ?? 'N/A'; ?></td>
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
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/alpha.min.js"></script>
    <script src="assets/js/pages/table-data.js"></script>
    <script src="assets/js/pages/ui-modals.js"></script>
    <script src="assets/plugins/google-code-prettify/prettify.js"></script>

</body>

</html>