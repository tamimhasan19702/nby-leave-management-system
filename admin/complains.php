<?php
session_start();
include('includes/config.php');

// Check if the user is logged in
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Handle deletion of a complaint

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin | View Complaints</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <?php include('includes/header.php'); ?>
    <?php include('includes/sidebar.php'); ?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12 m12 l12">

                <?php 
            

            if (isset($_GET['cid'])) {
                $complaintId = $_GET['cid'];
                $deleteSql = "DELETE FROM complaints WHERE id = :id";
                $deleteQuery = $dbh->prepare($deleteSql);
                $deleteQuery->bindParam(':id', $complaintId, PDO::PARAM_INT);
                if ($deleteQuery->execute()) {
                    echo "<script>alert('Complaint deleted successfully.'); window.location.href='complains.php'</script>";
                } else {
                    echo "<script>alert('Error deleting complaint.');</script>";
                }
            }
            
            // Fetch complaints with employee names
            $sql = "SELECT c.id, c.complaint_title, c.complaint, c.created_at, e.FirstName, e.LastName 
                    FROM complaints c 
                    JOIN tblemployees e ON c.empId = e.id 
                    ORDER BY c.created_at DESC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            ?>

                <table class="display responsive-table">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Employee Name</th>
                            <th>Complaint Title</th>
                            <th>Complaint</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cnt = 1;
                        if ($query->rowCount() > 0) {
                            foreach ($results as $result) { ?>
                        <tr>
                            <td><?php echo htmlentities($cnt); ?></td>
                            <td><?php echo htmlentities($result->FirstName . ' ' . $result->LastName); ?></td>
                            <td><?php echo htmlentities($result->complaint_title); ?></td>
                            <td><?php echo htmlentities($result->complaint); ?></td>
                            <td><?php echo date('d-m-Y - h:i A - l', strtotime($result->created_at)); ?></td>
                            <td>
                                <div class="comp-button">
                                    <a href="?cid=<?php echo $result->id; ?>"
                                        onclick="return confirm('Do you really want to delete this complaint?');">
                                        <i class="material-icons" style="color: red;">delete_forever</i>
                                    </a>


                                    <?php 
// Assuming you have a database connection in $dbh

$complainId = $result->id;

// Check if the action is set in the URL
if (isset($_GET['action']) && $_GET['action'] === 'mark-read' && isset($_GET['cid'])) {
    $cid = $_GET['cid'];

    // Update the read status in the database
    $updateReadStatus = "UPDATE complaints SET isread = 1 WHERE id = :id";
    $updateQuery = $dbh->prepare($updateReadStatus);
    $updateQuery->bindParam(':id', $cid, PDO::PARAM_INT);
    
    if ($updateQuery->execute()) {
        // Redirect back to the same page after updating
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Handle the error if the update fails
        echo "Error updating read status.";
    }
}

// Determine the current read status
$complainIsRead = "SELECT isread FROM complaints WHERE id = :id";
$complainsQuery = $dbh->prepare($complainIsRead);
$complainsQuery->bindParam(':id', $complainId, PDO::PARAM_INT);
$complainsQuery->execute();
$complainStatus = $complainsQuery->fetch(PDO::FETCH_ASSOC);

// Display the button to mark as read
?>

                                    <a href="?cid=<?php echo $complainId; ?>&action=mark-read"
                                        class="waves-effect waves-light btn blue">
                                        <i class="material-icons">done</i> Mark as Read
                                    </a>

                                </div>
                            </td>
                        </tr>
                        <?php 
                                $cnt++;
                            }
                        } else { ?>
                        <tr>
                            <td colspan="6">No complaints found.</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
</body>

</html>