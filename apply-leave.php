<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['emplogin']) == 0) {   
    header('location:index.php');
} else {
    if (isset($_POST['apply'])) {
        $empid = $_SESSION['eid'];
        $leavetype = $_POST['leavetype'];
        $leaveDates = json_encode($_POST['leave_dates']); // Store leave dates as JSON
        $description = $_POST['description'];  
        $username = $_POST['username'];
        $emailId = $_POST['emailid'];
        $phonenumber = $_POST['phonenumber'];
        $status = 0;
        $isread = 0;

        // Insert into database
        $sql = "INSERT INTO tblleavestest (LeaveType, LeaveDates, Description, Status, IsRead, empid, Username, EmailId, Phonenumber) 
                VALUES (:leavetype, :leavedates, :description, :status, :isread, :empid, :username, :emailid, :phonenumber)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':leavetype', $leavetype, PDO::PARAM_STR);
        $query->bindParam(':leavedates', $leaveDates, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':isread', $isread, PDO::PARAM_STR);
        $query->bindParam(':empid', $empid, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':emailid', $emailId, PDO::PARAM_STR);
        $query->bindParam(':phonenumber', $phonenumber, PDO::PARAM_STR);
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
    <title>Employee | Apply Leave</title>
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
        display: none;
        /* Hide by default */
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
                    <h3 class="nby-title">Apply Leave Form</h3>
                    <form id="example-form" method="post" name="addemp">
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
                                                        <option value="<?php echo htmlentities($result->LeaveType); ?>">
                                                            <?php echo htmlentities($result->LeaveType); ?></option>
                                                        <?php }
 } ?>
                                                    </select>
                                                </div>

                                                <div class="input-field col s12">
                                                    <span style="font-weight:bold;">Leave Dates</span>
                                                    <div id="date_fields">
                                                        <div class="date-field-container">
                                                            <input id="leave_dates" name="leave_dates[]" type="date"
                                                                required>
                                                        </div>
                                                    </div>

                                                    <div id="additional_dates"></div>
                                                    <button type="button" onclick="addDateField()"
                                                        class="waves-effect waves-light btn indigo m-b-xs">Add Another
                                                        Date</button>
                                                </div>

                                                <div class="input-field col m12 s12">
                                                    <span style="font-weight:bold;">Description</span>
                                                    <textarea id="textarea1" name="description"
                                                        class="materialize-textarea" length="500"></textarea>
                                                </div>

                                                <button type="submit" name="apply" id="apply"
                                                    class="waves-effect waves-light btn indigo m-b-xs">Apply</button>
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
        };

        dateFieldContainer.appendChild(newDateField);
        dateFieldContainer.appendChild(removeIcon);
        additionalDatesDiv.appendChild(dateFieldContainer);

        // Show the remove icon
        removeIcon.style.display = 'inline';
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