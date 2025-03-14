<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['emplogin']) == 0) {   
    header('location:index.php');
} else {
    // Handle delete request
    if (isset($_GET['del'])) {
        $deleteid = intval($_GET['del']);
        $sql = "DELETE FROM tblleavestest WHERE id = :deleteid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':deleteid', $deleteid, PDO::PARAM_INT);
        if ($query->execute()) {
            $msg = "Leave application deleted successfully.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee | Leave History</title>
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
                        <span class="card-title">Leave History</span>
                        <?php if($msg){?><div class="succWrap"><strong>SUCCESS</strong> :
                            <?php echo htmlentities($msg); ?> </div><?php }?>
                        <?php if($error){?><div class="errorWrap"><strong>ERROR</strong> :
                            <?php echo htmlentities($error); ?> </div><?php }?>
                        <table id="example" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="120">Leave Type</th>
                                    <th>Leave Dates</th>
                                    <th>Posting Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php 
                            $eid = $_SESSION['eid'];
                            $sql = "SELECT id as lid, LeaveType, LeaveDates, Duration, PostingDate, AdminRemarkDate, AdminRemark, Status FROM tblleavestest WHERE empid=:eid";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            $cnt = 1;
                            if($query->rowCount() > 0) {
                                foreach($results as $result) {
                            ?>
                                <tr>
                                    <td><?php echo htmlentities($cnt);?></td>
                                    <td><?php echo htmlentities($result->LeaveType);?></td>
                                    <td>
                                        <?php 
                                    $leaveDates = json_decode($result->LeaveDates);
                                    if (is_array($leaveDates)) {
                                        foreach ($leaveDates as $date) {
                                            echo date('d-m-Y - (l)', strtotime($date)) . "<br>";
                                        }
                                    }
                                    ?>
                                    </td>
                                    <td><?php echo date('d-m-Y - h:i A - (l)', strtotime($result->PostingDate)); ?></td>
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
                                        <a href="leave-details.php?leaveid=<?php echo htmlentities($result->lid);?>"
                                            class="waves-effect waves-light btn blue m-b-xs">View Details</a>

                                        <?php if ($result->Status == 0) { ?>
                                        <a href="leavehistory.php?del=<?php echo htmlentities($result->lid);?>"
                                            onclick="return confirm('Do you want to delete this record?');"
                                            class="waves-effect waves-light btn red m-b-xs">Delete</a>
                                        <?php } ?>
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
<?php } ?>