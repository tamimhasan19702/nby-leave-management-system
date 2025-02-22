<?php 


error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {
    // Fetch the details of the logged-in admin
    $adminUsername = $_SESSION['alogin']; 
    
    // Assuming this is the username stored in session
    $sql = "SELECT id, UserName, EmailId, Image FROM admin WHERE UserName = :username"; // Adjust the query to match your database schema
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $adminUsername, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    // Check if the result is found
    if ($result) {
        $adminId = $result->id;
        $adminUserName = $result->UserName;
        $adminEmail = $result->EmailId;
        $adminImage = $result->Image;
    } else {
        $adminEmail = "Email not found"; // Handle case where email is not found
        $adminUserName = "User not found"; // Handle case where username is not found
        $adminImage = "default.png"; // Default image if not found
    }
?>


<aside id="slide-out" class="side-nav white fixed">
    <div class="side-nav-wrapper">
        <div class="sidebar-profile">
            <div class="sidebar-profile-image">
                <img src="<?php echo $adminImage; ?>" class="circle" alt="">
            </div>


            <div class="sidebar-profile-info">
                <p><?php echo htmlentities($adminUserName); ?></p>
                <p><?php echo htmlentities($adminEmail); ?></p>
            </div>
        </div>

        <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion">
            <li class="no-padding"><a class="waves-effect waves-grey" href="dashboard.php"><i
                        class="material-icons">settings_input_svideo</i>Dashboard</a></li>


            <li class="no-padding"><a class="waves-effect waves-grey" href="employeelogs.php"><i
                        class="material-icons">login</i>Employee Logs</a></li>

            <?php 
$complains = "SELECT id, empid, complaint_title, complaint, created_at, isread FROM complaints";
$complainsQuery = $dbh->prepare($complains);
$complainsQuery->execute();
$complainsResult = $complainsQuery->fetchAll(PDO::FETCH_ASSOC);
$today = date('Y-m-d');
$newComplain = 0;

// Count new complaints with isread = 0
foreach ($complainsResult as $complain) {
    if (date('Y-m-d', strtotime($complain['created_at'])) == $today && $complain['isread'] == 0) {
        $newComplain++;
    }
}

// Show the new complaint banner if there are new complaints
if ($newComplain > 0) {
    echo '<li class="no-padding"><a class="waves-effect waves-grey" href="complains.php"><i class="material-icons">notes</i>All Complains
        <span class="badge red white-text">'.$newComplain.' New</span>
    </a></li>';
} else {
    echo '<li class="no-padding"><a class="waves-effect waves-grey" href="complains.php"><i class="material-icons">notes</i>All Complains</a></li>';
}
?>

            <li class="no-padding">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">desktop_windows</i>Leave
                    Management<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li><a href="leaves.php">All Leaves </a></li>
                        <li><a href="pending-leavehistory.php">Pending Leaves </a></li>
                        <li><a href="approvedleave-history.php">Approved Leaves</a></li>
                        <li><a href="notapproved-leaves.php">Not Approved Leaves</a></li>
                    </ul>
                </div>
            </li>




            <li class="no-padding">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">article</i>Notice<i
                        class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li><a href="addnotice.php">Add Notice</a></li>
                        <li><a href="managenotice.php">Manage Notice</a></li>
                    </ul>
                </div>
            </li>

            <li class="no-padding">
                <a class="collapsible-header waves-effect waves-grey"><i
                        class="material-icons">account_box</i>Employees<i
                        class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li><a href="addemployee.php">Add Employee</a></li>
                        <li><a href="manageemployee.php">Manage Employee</a></li>
                    </ul>
                </div>
            </li>






            <li class="no-padding">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">apps</i>Department<i
                        class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li><a href="adddepartment.php">Add Department</a></li>
                        <li><a href="managedepartments.php">Manage Department</a></li>
                    </ul>
                </div>
            </li>

            <li class="no-padding">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">code</i>Leave
                    Type<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li><a href="addleavetype.php">Add Leave Type</a></li>
                        <li><a href="manageleavetype.php">Manage Leave Type</a></li>
                    </ul>
                </div>
            </li>


            <li class="no-padding"><a class="waves-effect waves-grey" href="profile.php"><i
                        class="material-icons">account_box</i>My Profile</a></li>


            <li class="no-padding"><a class="waves-effect waves-grey" href="changepassword.php"><i
                        class="material-icons">settings_input_svideo</i>Change Password</a></li>


            <li class="no-padding">
                <a class="waves-effect waves-grey" href="logout.php"><i class="material-icons">exit_to_app</i>Sign
                    Out</a>
            </li>
        </ul>
        <div class="footer">
            <p class="copyright">NBY IT Leave Management System</p>
        </div>
    </div>
</aside>

<?php } ?>