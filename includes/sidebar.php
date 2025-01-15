<aside id="slide-out" class="side-nav white fixed">
    <div class="side-nav-wrapper">
        <div class="sidebar-profile">
            <div class="sidebar-profile-image">
                <?php 
                $eid = $_SESSION['eid'];
                $sql = "SELECT FirstName, LastName, EmailId, Image FROM tblemployees WHERE id = :eid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                $query->execute();
                
                // Fetch the single result directly
                if ($query->rowCount() > 0) {
                    $result = $query->fetch(PDO::FETCH_OBJ);
                    $firstName = $result->FirstName;
                    $lastName = $result->LastName;
                    $emailId = $result->EmailId;
                    $image = $result->Image;
                ?>

                <img src=" <?php echo $image; ?>" class="circle" alt="Profile Image">




                <p><?php echo htmlentities($firstName . " " . $lastName); ?></p>
                <span><?php echo htmlentities($emailId); ?></span>
                <!-- Updated to display EmailId -->
                <?php 
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