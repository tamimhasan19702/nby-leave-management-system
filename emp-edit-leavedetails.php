<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['emplogin']) == 0) {   
    header('location:index.php');
} else {
    // Fetch leave details if leaveid is set
    $leaveid = intval($_GET['leaveid']);
    $sql = "SELECT * FROM tblleavestest WHERE id = :leaveid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':leaveid', $leaveid, PDO::PARAM_INT);
    $query->execute();
    $leaveDetails = $query->fetch(PDO::FETCH_OBJ);

    // Check if leave details were found
    if (!$leaveDetails) {
        echo "<script>alert('Leave details not found.'); window.location.href='leavehistory.php';</script>";
        exit();
    }

    // Handle form submission for updating leave details
    if (isset($_POST['update'])) {
        $leavetype = $_POST['leavetype'];
        $leaveDates = json_encode($_POST['leave_dates']); // Store leave dates as JSON
        $description = $_POST['description'];  
        $status = 0; // Set status to 0 (Waiting for Approval)
        $empid = $leaveDetails->empid; // Keep the same employee ID

        // Update leave details in the database
        $sql = "UPDATE tblleavestest SET LeaveType = :leavetype, LeaveDates = :leavedates, Description = :description, Status = :status WHERE id = :leaveid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':leavetype', $leavetype, PDO::PARAM_STR);
        $query->bindParam(':leavedates', $leaveDates, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);
        $query->bindParam(':leaveid', $leaveid, PDO::PARAM_INT);

        if ($query->execute()) {
            echo "<script>M.toast({html: 'Leave details updated successfully. The status has been set to \"Waiting for Approval\".'});</script>";
            header('location:leavehistory.php'); // Redirect to leave history page
            exit(); // Ensure no further code is executed after redirection
        } else {
            $error = "Something went wrong. Please try again";
        }
    }

    // Fetch employee data
    $empid = $_SESSION['eid']; // Get employee ID from session
    $sql = "SELECT Username, EmailId, Phonenumber FROM tblemployees WHERE id = :empid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':empid', $empid, PDO::PARAM_INT);
    $query->execute();
    $employeeData = $query->fetch(PDO::FETCH_ASSOC);

    // Extract values
    $username = $employeeData['Username'] ?? '';
    $emailId = $employeeData['EmailId'] ?? '';
    $phonenumber = $employeeData['Phonenumber'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee | Edit Leave</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
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

    .remove-icon {
        cursor: pointer;
        color: red;
        margin-left: 10px;
        font-size: 20px;
        display: inline;
        /* Show by default */
    }

    .date-field-container {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        /* Space between date fields */
    }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>
    <main class="mn-inner">

        <div class="col s12 m12 l8">
            <div class="card">
                <div class="card-content">
                    <h3 class="nby-title">Edit Leave Form</h3>
                    <form id="example-form" method="post" name="editleave">
                        <div>
                            <section>
                                <div class="wizard-content">
                                    <div class="row">
                                        <div class="col m12">
                                            <div class="row">
                                                <?php if ($error) { ?>
                                                <div class="errorWrap"><strong>ERROR</strong>:
                                                    <?php echo htmlentities($error); ?> </div>
                                                <?php } else if ($msg) { ?>
                                                <div class="succWrap"><strong>SUCCESS</strong>:
                                                    <?php echo htmlentities($msg); ?> </div>
                                                <?php } ?>

                                                <div class="input-field col s12">
                                                    <span style="font-weight:bold;">Username</span>
                                                    <input id="username" name="username" type="text"
                                                        value="<?php echo htmlentities($username); ?>" required>
                                                </div>

                                                <div class="input-field col s12">
                                                    <span style="font-weight:bold;">Email ID</span>
                                                    <input id="emailid" name="emailid" type="email"
                                                        value="<?php echo htmlentities($emailId); ?>" required>
                                                </div>

                                                <div class="input-field col s12">
                                                    <span style="font-weight:bold;">Phone Number</span>
                                                    <input id="phonenumber" name="phonenumber" type="text"
                                                        value="<?php echo htmlentities($phonenumber); ?>" required>
                                                </div>

                                                <div class="input-field col s12">
                                                    <span style="font-weight:bold;">Leave Type</span>
                                                    <select name="leavetype" autocomplete="off" class="browser-default"
                                                        required>
                                                        <option value="">Select leave type...</option>
                                                        <?php 
                                                            $sql = "SELECT LeaveType from tblleavetype";
                                                            $query = $dbh->prepare($sql);
                                                            $query->execute();
                                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                            if ($query->rowCount() > 0) {
                                                                foreach ($results as $result) { ?>
                                                        <option value="<?php echo htmlentities($result->LeaveType); ?>"
                                                            <?php echo ($result->LeaveType == $leaveDetails->LeaveType) ? 'selected' : ''; ?>>
                                                            <?php echo htmlentities($result->LeaveType); ?></option>
                                                        <?php }
 } ?>
                                                    </select>
                                                </div>

                                                <div class="input-field col s12">
                                                    <span style="font-weight:bold;">Leave Dates</span>
                                                    <div id="date_fields">
                                                        <?php
                                                        $leaveDates = json_decode($leaveDetails->LeaveDates);
                                                        // Ensure only one leave date is shown by default
                                                        echo '<div class="date-field-container"><input name="leave_dates[]" type="date" value="' . htmlentities($leaveDates[0]) . '" required>';
                                                        if (count($leaveDates) > 1) {
                                                            echo '<span class="material-icons remove-icon" onclick="removeDateField(this)">close</span>';
                                                        }
                                                        echo '</div>';
                                                        ?>
                                                    </div>

                                                    <div id="additional_dates"></div>
                                                    <button type="button" onclick="addDateField()"
                                                        class="waves-effect waves-light btn indigo m-b-xs">Add Another
                                                        Date</button>
                                                </div>

                                                <div class="input-field col m12 s12">
                                                    <span style="font-weight:bold;">Description</span>
                                                    <textarea id="textarea1" name="description"
                                                        class="materialize-textarea" length="500"
                                                        required><?php echo htmlentities($leaveDetails->Description); ?></textarea>
                                                </div>

                                                <div class="warning-message"
                                                    style="color: orange; font-weight: bold; font-size: 16px;">
                                                    Note: Updating leave details will change the status to "Waiting for
                                                    Approval".
                                                </div>

                                                <button type="submit" name="update" id="update"
                                                    class="waves-effect waves-light btn indigo m-b-xs"
                                                    style="margin-top: 20px;">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>
    <div class="left-sidebar-hover"></div>

    <script>
    function addDateField() {
        const additionalDatesDiv = document.getElementById('additional_dates');
        const dateFieldContainer = document.createElement('div');
        dateFieldContainer.className = 'date-field-container';

        const newDateField = document.createElement('input');
        newDateField.type = 'date';
        newDateField.name = 'leave_dates[]';

        const removeIcon = document.createElement('span');
        removeIcon.className = 'material-icons remove-icon';
        removeIcon.textContent = 'close'; // Cross icon
        removeIcon.onclick = function() {
            additionalDatesDiv.removeChild(dateFieldContainer);
            updateRemoveIcons();
        };

        dateFieldContainer.appendChild(newDateField);
        dateFieldContainer.appendChild(removeIcon);
        additionalDatesDiv.appendChild(dateFieldContainer);
        updateRemoveIcons();
    }

    function removeDateField(element) {
        const dateFieldContainer = element.parentElement;
        dateFieldContainer.parentElement.removeChild(dateFieldContainer);
        updateRemoveIcons();
    }

    function updateRemoveIcons() {
        const dateFieldContainers = document.querySelectorAll('.date-field-container');
        dateFieldContainers.forEach((container, index) => {
            const removeIcon = container.querySelector('.remove-icon');
            if (dateFieldContainers.length > 1) {
                removeIcon.style.display = 'inline';
            } else {
                removeIcon.style.display = 'none';
            }
        });
    }
    </script>

    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/js/alpha.min.js"></script>
</body>

</html>
<?php } ?>