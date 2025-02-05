<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{

// code for update the read notification status
$isread=1;
$did=intval($_GET['leaveid']);  
date_default_timezone_set('Asia/Kolkata');
$admremarkdate=date('Y-m-d G:i:s ', strtotime("now"));
$sql="update tblleaves set IsRead=:isread where id=:did";
$query = $dbh->prepare($sql);
$query->bindParam(':isread',$isread,PDO::PARAM_STR);
$query->bindParam(':did',$did,PDO::PARAM_STR);
$query->execute();

// code for action taken on leave
if (isset($_POST['update'])) {
    // Get form inputs
    $did = intval($_POST['leaveid']); // Get Leave ID from the form
    $description = trim($_POST['description']); // Sanitize input
    $status = intval($_POST['status']); // Ensure it's an integer

    // Check if leave ID is valid
    if ($did > 0) {
        // Set timezone and current date-time
        date_default_timezone_set('Asia/Kolkata');
        $admremarkdate = date('Y-m-d G:i:s', strtotime("now"));

        // Update Query
        $sql = "UPDATE tblleaves 
                SET AdminRemark = :description, 
                    Status = :status, 
                    AdminRemarkDate = :admremarkdate 
                WHERE id = :did";
        $query = $dbh->prepare($sql);

        // Bind parameters
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->bindParam(':admremarkdate', $admremarkdate, PDO::PARAM_STR);
        $query->bindParam(':did', $did, PDO::PARAM_INT);

        // Execute the query
        if ($query->execute()) {
            $msg = "Leave updated successfully.";
            header('Location: leaves.php');
            exit(); 
        } else {
            $msg = "Failed to update leave. Please try again.";
        }
    } else {
        $msg = "Invalid Leave ID.";
    }

   
}


 ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Title -->
    <title>Admin | Leave Details </title>

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

    <link href="../assets/plugins/google-code-prettify/prettify.css" rel="stylesheet" type="text/css" />
    <!-- Theme Styles -->
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
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


    <?php 
    $lid = intval($_GET['leaveid']);
    $sql = "SELECT tblleaves.id as lid,
                   tblemployees.FirstName,
                   tblemployees.LastName,
                   tblemployees.EmpId,
                   tblemployees.id,
                   tblemployees.Gender,
                   tblemployees.Phonenumber,
                   tblemployees.EmailId,
                   tblemployees.AnnualLeave,
                   tblemployees.SickLeave,
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
    $cnt = 1;
    if ($query->rowCount() > 0) {
        foreach ($results as $result) {         
    ?>


    <main class="mn-inner">
        <div class="row">

            <div class="col s12 nby-view-profile">
                <h1 class="nby-title">Leave Details</h1>
                <a href="viewprofile.php?empid=<?php echo htmlentities($result->id); ?>" class="btn">View Profile</a>
            </div>

            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">

                        <?php if($msg){?><div class="succWrap"><strong>SUCCESS</strong> :
                            <?php echo htmlentities($msg); ?> </div><?php }?>
                        <table id="example" class="display responsive-table ">


                            <tbody>

                                <tr>
                                    <td style="font-size:16px;"><b>Employe Name :</b></td>
                                    <td><a href="viewprofile.php?empid=<?php echo htmlentities($result->id); ?>"
                                            target="_blank">
                                            <?php echo htmlentities($result->FirstName . " " . $result->LastName); ?></a>
                                    </td>
                                    <td style="font-size:16px;"><b>Emp Id :</b></td>
                                    <td><?php echo htmlentities($result->EmpId); ?></td>
                                    <td style="font-size:16px;"><b>Gender :</b></td>
                                    <td><?php echo htmlentities($result->Gender); ?></td>
                                </tr>

                                <tr>
                                    <td style="font-size:16px;"><b>Emp Email id :</b></td>
                                    <td><?php echo htmlentities($result->EmailId); ?></td>
                                    <td style="font-size:16px;"><b>Emp Contact No. :</b></td>
                                    <td><?php echo htmlentities($result->Phonenumber); ?></td>
                                    <td style="font-size:16px;"><b>Duration :</b></td>
                                    <td><?php echo htmlentities($result->Duration); ?></td>
                                </tr>

                                <tr>
                                    <td style="font-size:16px;"><b>Leave Type :</b></td>
                                    <td><?php echo htmlentities($result->LeaveType); ?></td>
                                    <td style="font-size:16px;"><b>Leave Date :</b></td>
                                    <td>
                                        <span style="font-weight:600">From - </span> <?php 
                $fromDate = htmlentities($result->FromDate);
                echo $fromDate; 
            ?> (<?php echo date('l', strtotime($fromDate)); ?>)<span style="font-weight:600"> - To - </span>
                                        <?php  
                $toDate = htmlentities($result->ToDate);
                echo $toDate; 
            ?> (<?php echo date('l', strtotime($toDate)); ?>)
                                    </td>
                                    <td style="font-size:16px;"><b>Posting Date</b></td>
                                    <td><?php echo htmlentities($result->PostingDate); ?></ td>
                                </tr>


                                <tr>
                                    <td style="font-size:16px;"><b>Annual Leave Left:</b></td>
                                    <td><?php echo htmlentities($result->AnnualLeave); ?></td>
                                    <td style="font-size:16px;"><b>Sick Leave Left:</b></td>
                                    <td><?php echo htmlentities($result->SickLeave); ?></td>
                                </tr>


                                <tr>
                                    <td style="font-size:16px;"><b>Employe Leave Description:</b></td>
                                    <td colspan="5"><?php echo htmlentities($result->Description); ?></td>
                                </tr>



                                <tr>
                                    <td style="font-size:16px;"><b>Leave Status:</b></td>
                                    <td colspan="5"><?php 
            $stats = $result->Status;
            if ($stats == 1) {
                echo '<span style="color: green">Approved</span>';
            } elseif ($stats == 2) {
                echo '<span style="color: red">Rejected</span>';
            } else {
                echo '<span style="color: blue">Waiting for approval</span>';
            }
            ?></td>
                                </tr>

                                <tr>
                                    <td style="font-size:16px;"><b>Admin Remark:</b></td>
                                    <td colspan="5"><?php
            if ($result->AdminRemark == "") {
                echo "No Admin Remark added";  
            } else {
                echo htmlentities($result->AdminRemark);
            }
            ?></td>
                                </tr>


                                <tr>
                                    <td style="font-size:16px;"><b>
                                            <?php if ($result->Status == "1") { echo '<span >Approved By</span>'; } elseif ($result->Status == "2") { echo '<span >Rejected By</span>'; } else { echo '<span >Waiting for approval</span>'; }?></b>
                                    </td>
                                    <td colspan="5">
                                        <?php
        if ($result->Status == "1") {
            // If approved, show the admin's name
            $adminUsername = $_SESSION['alogin']; 
            $sql = "SELECT FirstName, LastName FROM admin WHERE UserName = :username";
            $query = $dbh->prepare($sql);
            $query->bindParam(':username', $adminUsername, PDO::PARAM_STR);
            $query->execute();
            $resultAdmin = $query->fetch(PDO::FETCH_OBJ);
            echo  htmlentities($resultAdmin->FirstName . " " . $resultAdmin->LastName);
        } elseif ($result->Status == "2") {
            // If rejected, show the admin's name
            $adminUsername = $_SESSION['alogin']; 
            $sql = "SELECT FirstName, LastName FROM admin WHERE UserName = :username";
            $query = $dbh->prepare($sql);
            $query->bindParam(':username', $adminUsername, PDO::PARAM_STR);
            $query->execute();
            $resultAdmin = $query->fetch(PDO::FETCH_OBJ);
            echo  htmlentities($resultAdmin->FirstName . " " . $resultAdmin->LastName);
        } else {
            echo "NA"; // If the status is neither approved nor rejected
        }
        ?>
                                    </td>
                                </tr>


                                <tr>
                                    <td style="font-size:16px;"><b>Admin Action Taken Date:</b></td>
                                    <td colspan="5"><?php
            if ($result->AdminRemarkDate == "") {
                echo "NA";  
            } else {
                echo htmlentities($result->AdminRemarkDate);
            }
            ?></td>
                                </tr>

                                <tr>
                                    <td colspan="5">
                                        <!-- Modal Trigger -->
                                        <a class="modal-trigger waves-effect waves-light btn" href="#modal1">Take
                                            Action</a>

                                        <!-- Modal Form -->
                                        <form name="adminaction" method="post" action="">
                                            <!-- Modal Structure -->
                                            <div id="modal1" class="modal modal-fixed-footer" style="height: 60%">
                                                <div class="modal-content" style="width:90%">
                                                    <h4>Leave Take Action</h4>

                                                    <!-- Hidden Input to Pass Leave ID -->
                                                    <input type="hidden" name="leaveid"
                                                        value="<?php echo isset($_GET['leaveid']) ? $_GET['leaveid'] : ''; ?>">

                                                    <!-- Status Dropdown -->
                                                    <select class="browser-default" name="status" required>
                                                        <option value="">Choose your option</option>
                                                        <option value="1" style="color:green">Approved</option>
                                                        <option value="2" style="color:red">Rejected</option>
                                                    </select>

                                                    <!-- Description Textarea -->
                                                    <p>
                                                        <textarea id="textarea1" name="description"
                                                            class="materialize-textarea" placeholder="Description"
                                                            maxlength="500"></textarea>
                                                    </p>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="modal-footer" style="width:90%">
                                                    <input type="submit"
                                                        class="waves-effect waves-light btn blue m-b-xs" name="update"
                                                        value="Submit">
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>

                                <?php } ?>
                                </form>
                                </tr>
                                <?php $cnt++; } ?>
                            </tbody>
                        </table>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    </div>
    <div class="left-sidebar-hover"></div>

    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
    <script src="../assets/js/pages/table-data.js"></script>
    <script src="assets/js/pages/ui-modals.js"></script>
    <script src="assets/plugins/google-code-prettify/prettify.js"></script>

</body>

</html>
<?php } ?>