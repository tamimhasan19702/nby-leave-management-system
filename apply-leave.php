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
        $fromdate = $_POST['fromdate'];  
        $todate = $_POST['todate'];
        $description = $_POST['description'];  
        $username = $_POST['username'];
        $emailId = $_POST['emailid'];
        $phonenumber = $_POST['phonenumber'];
        $status = 0;
        $isread = 0;

        // Calculate duration
        $fromDateTime = new DateTime($fromdate);
        $toDateTime = new DateTime($todate);
        $durationDays = $fromDateTime->diff($toDateTime)->days; // Get the difference in days

        // Format duration
        $duration = $durationDays . ' days'; // Format as "X days"

        if ($fromdate > $todate) {
            $error = "ToDate should be greater than FromDate";
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
            $query->bindParam(':duration', $duration, PDO::PARAM_STR); // Bind formatted duration
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                $msg = "Leave applied successfully";
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }


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
                                                    <input id="username" name="username" type="text"
                                                        value="<?php echo htmlentities($username); ?>" required>
                                                    <label for="username">Username</label>
                                                </div>

                                                <div class="input-field col s12">
                                                    <input id="emailid" name="emailid" type="email"
                                                        value="<?php echo htmlentities($emailId); ?>" required>
                                                    <label for="emailid">Email ID</label>
                                                </div>

                                                <div class="input-field col s12">
                                                    <input id="phonenumber" name="phonenumber" type="text"
                                                        value="<?php echo htmlentities($phonenumber); ?>" required>
                                                    <label for="phonenumber">Phone Number</label>
                                                </div>

                                                <div class="input-field col s12">
                                                    <select name="leavetype" autocomplete="off" class="browser-default">
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

                                                <div class="input-field col m6 s12">
                                                    <span>From Date</span>
                                                    <input id="fromdate" name="fromdate" type="date" required
                                                        onchange="calculateDuration()">
                                                </div>

                                                <div class="input-field col m6 s12">
                                                    <span>To Date</span>
                                                    <input id="todate" name="todate" type="date" required
                                                        onchange="calculateDuration()">
                                                </div>

                                                <div class="input-field col s12">
                                                    <span>Duration: <span id="durationDisplay">0</span> days</span>
                                                </div>

                                                <div class="input-field col m12 s12">
                                                    <label for="description">Description</label>
                                                    <textarea id="textarea1" name="description"
                                                        class="materialize-textarea" length="500" required></textarea>
                                                </div>



                                            </div>
                                            <button type="submit" name="apply" id="apply"
                                                class="waves-effect waves-light btn indigo m-b-xs">Apply</button>
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

    <!-- Javascripts -->
    <script src="assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="assets/js/alpha.min.js"></script>
    <script src="assets/js/pages/form_elements.js"></script>
    <script src="assets/js/pages/form-input-mask.js"></script>
    <script src="assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>

    <script>
    function calculateDuration() {
        const fromDate = document.getElementById('fromdate').value;
        const toDate = document.getElementById('todate').value;

        if (fromDate && toDate) {
            const start = new Date(fromDate);
            const end = new Date(toDate);
            const duration = Math.ceil((end - start) / (1000 * 60 * 60 * 24)); // Calculate duration in days

            document.getElementById('durationDisplay').innerText = duration >= 0 ? duration : 0; // Display duration
        } else {
            document.getElementById('durationDisplay').innerText = 0; // Reset if dates are not selected
        }
    }
    </script>
</body>

</html>
<?php } ?>