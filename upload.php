<?php
// Include the database configuration file
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $employee_id = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : null;
    $current_image = isset($_POST['current_image']) ? $_POST['current_image'] : null;

    // Check if a file is uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        // File upload variables
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_name = basename($_FILES['profile_picture']['name']);
        $file_size = $_FILES['profile_picture']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Allowed file extensions
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file extension
        if (!in_array($file_ext, $allowed_extensions)) {
            die('Error: Invalid file format. Only JPG, JPEG, PNG, and GIF are allowed.');
        }

        // Validate file size (limit: 2MB)
        if ($file_size > 2 * 1024 * 1024) {
            die('Error: File size exceeds the limit of 2MB.');
        }

        // Define the target directory and file path
        $target_dir = __DIR__ . '/assets/images/';
        $new_file_name = $target_dir . uniqid('profile_', true) . '.' . $file_ext;

        // Ensure the target directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Create directory if it doesn't exist
        }

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($file_tmp, $new_file_name)) {
            die('Error: Unable to move the uploaded file. Check directory permissions.');
        }

        // Convert file path to a relative path (for database storage)
        $relative_file_path = 'assets/images/' . basename($new_file_name);

        // Delete the old image if it exists
        if (!empty($current_image) && file_exists(__DIR__ . '/' . $current_image)) {
            unlink(__DIR__ . '/' . $current_image);
        }

        // Update the database with the new image path
        $sql = "UPDATE employees SET profile_picture = :profile_picture WHERE id = :employee_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':profile_picture', $relative_file_path, PDO::PARAM_STR);
        $query->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);

        if ($query->execute()) {
            echo 'Success: Profile picture updated successfully.';
        } else {
            echo 'Error: Failed to update the database.';
        }
    } else {
        echo 'Error: No file uploaded or file upload error.';
    }
} else {
    echo 'Error: Invalid request method.';
}
?>