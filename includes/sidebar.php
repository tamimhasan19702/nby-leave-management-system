<aside id="slide-out" class="side-nav white fixed">
    <div class="side-nav-wrapper">
        <div class="sidebar-profile">
            <div class="sidebar-profile-image">
                <?php 
    // Get the employee ID from the session
    $eid = $_SESSION['eid'];

    // Debugging output
    echo "Employee ID: " . htmlentities($eid); 

    // Prepare the SQL statement to fetch employee details
    $sql = "SELECT FirstName, LastName, EmailId, Image FROM tblemployees WHERE id = :eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':eid', $eid, PDO::PARAM_INT); // Use PDO::PARAM_INT for integer IDs
    $query->execute();

    // Fetch the single result
    $result = $query->fetch(PDO::FETCH_ASSOC); // Fetch as associative array

    if ($result) {
        // Extract the values from the result
        $firstName = $result['FirstName'];
        $lastName = $result['LastName'];
        $emailId = $result['EmailId'];
        $image = $result['Image'] ; // Default image if none

        // Display the employee details
        echo '<img class="profile-image" src="' . htmlentities($image) . '" alt="' . htmlentities($firstName . ' ' . $lastName) . '" style="max-width: 100px; max-height: 100px;">';
        echo '<p>' . htmlentities($firstName . " " . $lastName) . '</p>';
        echo '<span>' . htmlentities($emailId) . '</span>';
    } else {
        echo "No employee found with this ID."; // Handle case where no results are found
    }
    ?>
            </div>

        </div>

        <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion">
            <li class="no-padding"><a class="waves-effect waves-grey" href="dashboard.php"><i
                        class="material-icons">settings_input_svideo</i>Dashboard</a></li>
            <li class="no-padding"><a class="waves-effect waves-grey" href="profile.php"><i
                        class="material-icons">account_box</i>My Profile</a></li>
            <li class="no-padding"><a class="waves-effect waves-grey" href="emp-changepassword.php"><i
                        class="material-icons">settings_input_svideo</i>Change Password</a></li>
            <li class="no-padding">
                <a class="collapsible-header waves-effect waves-grey"><i class="material-icons">apps</i>Leaves<i
                        class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                <div class="collapsible-body">
                    <ul>
                        <li><a href="apply-leave.php">Apply Leave</a></li>
                        <li><a href="leavehistory.php">Leave History</a></li>
                    </ul>
                </div>
            </li>
            <li class="no-padding">
                <a class="waves-effect waves-grey" href="logout.php"><i class="material-icons">exit_to_app</i>Sign
                    Out</a>
            </li>
        </ul>
        <div class="footer">
            <p class="copyright">NBYIT Leave Management System</p>
        </div>
    </div>
</aside>