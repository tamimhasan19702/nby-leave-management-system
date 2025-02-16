<?php 
session_start();
include('includes/config.php');

// Check if the user is logged in
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit();
}

// Check if a complaint ID is provided
if (isset($_GET['compid']) && is_numeric($_GET['compid'])) {
    $compid = intval($_GET['compid']); // Get the complaint ID and ensure it's an integer

    // Prepare the SQL statement to get the current isread status
    $statusQuery = "SELECT isread FROM complaints WHERE id = :compid";
    $statusStmt = $dbh->prepare($statusQuery);
    $statusStmt->bindParam(':compid', $compid, PDO::PARAM_INT);
    $statusStmt->execute();
    $complaint = $statusStmt->fetch(PDO::FETCH_ASSOC);

    if ($complaint) {
        // Toggle the isread status
        $newStatus = ($complaint['isread'] == 0) ? 1 : 0; // Toggle between 0 and 1

        // Prepare the SQL statement to update the isread status
        $updateQuery = "UPDATE complaints SET isread = :status WHERE id = :compid";
        $updateStmt = $dbh->prepare($updateQuery);
        $updateStmt->bindParam(':status', $newStatus, PDO::PARAM_INT);
        $updateStmt->bindParam(':compid', $compid, PDO::PARAM_INT);

        // Execute the update query
        if ($updateStmt->execute()) {
            // Redirect back to the complaints page after updating
            header('location:complains.php?msg=Complaint marked as ' . ($newStatus ? 'read' : 'unread') . ' successfully.');
            exit();
        } else {
            // Handle the error if the update fails
            header('location:complains.php?msg=Error marking complaint as ' . ($newStatus ? 'read' : 'unread') . '.');
            exit();
        }
    } else {
        // If the complaint does not exist
        header('location:complains.php?msg=Complaint not found.');
        exit();
    }
} else {
    // If no valid complaint ID is provided, redirect with an error message
    header('location:complains.php?msg=Invalid complaint ID.');
    exit();
}
?>