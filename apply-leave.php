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
        $duration = intval($_POST['duration']); // Get duration in days
        $description = $_POST['description'];  
        $username = $_POST['username'];
        $emailId = $_POST['emailid'];
        $phonenumber = $_POST['phonenumber'];
        $status = 0;
        $isread = 0;
    
        // Create DateTime object for FromDate
        $fromDateTime = new DateTime($fromdate);
    
        // Calculate ToDate based on FromDate and Duration
        if ($duration == 1) {
            $todate = $fromDateTime->format('d-m-Y'); // Same as FromDate
        } else {
            $fromDateTime->modify("+".($duration - 1)." days"); // Add (duration - 1) days to FromDate
            $todate = $fromDateTime->format('d-m-Y'); // Format as d-m-Y
        }
    
        $fromdate = (new DateTime($fromdate))->format('d-m-Y'); // Format fromdate as d-m-Y
    
        // Create duration string with suffix
        $durationString = $duration . ' ' . ($duration > 1 ? 'days' : 'day');
    
        // Check if the description already contains the duration
        if (strpos($description, 'Duration:') === false) {
            // Append duration to description only if it's not already included
            $description .= " (Duration: $durationString)";
        }
    
        if ($duration <= 0) {
            $error = "Duration must be greater than 0";
        } else {
            // Insert into database
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
                                                    <span style="font-weight:bold;">Leave Date</span>
                                                    <input id="fromdate" name="fromdate" type="date" required
                                                        onchange="calculateToDate()">
                                                    <div id="fromdate-weekday" style="font-weight:bold;"></div>
                                                </div>

                                                <div class="input-field col s12">
                                                    <span style="font-weight:bold;">Duration (in days)</span>
                                                    <input id="duration" name="duration" type="number" min="1" required
                                                        oninput="calculateToDate()">
                                                </div>

                                                <div class="input-field col s12">
                                                    <span style="font-weight:bold;">To Date</span>
                                                    <input id="todate" name="todate" type="date" readonly>
                                                    <div id="todate-weekday" style="font-weight:bold;"></div>
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
    function calculateToDate() {
        const fromDateInput = document.getElementById('fromdate');
        const durationInput = document.getElementById('duration');
        const toDateInput = document.getElementById('todate');

        const fromDate = new Date(fromDateInput.value);
        const duration = parseInt(durationInput.value, 10);

        // Display the weekday for the From Date
        displayWeekday(fromDate, 'fromdate-weekday');

        if (!isNaN(fromDate) && duration > 0) {
            // Calculate the To Date
            const toDate = new Date(fromDate);
            toDate.setDate(fromDate.getDate() + duration - 1);

            // Set the value of the To Date input
            toDateInput.value = toDate.toISOString().split('T')[0]; // Format to YYYY-MM-DD

            // Display the weekday for the To Date
            displayWeekday(toDate, 'todate-weekday');
        } else {
            // Clear the To Date and its weekday if inputs are invalid
            toDateInput.value = '';
            document.getElementById('todate-weekday').innerText = '';
        }
    }

    function displayWeekday(date, elementId) {
        if (!isNaN(date)) {
            const weekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            const weekday = weekdays[date.getDay()];
            document.getElementById(elementId).innerText = `Selected Day: ${weekday}`;
        } else {
            document.getElementById(elementId).innerText = '';
        }
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