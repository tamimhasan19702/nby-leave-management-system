<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {
    // Initialize variables to hold form data
    $subject = '';
    $title = '';
    $description = '';
    $file_path = '';
    $department_id = '';

    if(isset($_POST['add'])) {
        $subject = $_POST['subject'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $file_path = $_POST['file_path'];
        $department_id = $_POST['department_id'];

        // Check if department_id is set and is a valid integer
        if (!isset($department_id) || !is_numeric($department_id)) {
            $error = "Invalid department selected.";
        } else {
            // Insert notice into the database
            $sql = "INSERT INTO notices (subject, title, description, file_path, department_id) VALUES (:subject, :title, :description, :file_path, :department_id)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':subject', $subject, PDO::PARAM_STR);
            $query->bindParam(':title', $title, PDO::PARAM_STR);
            $query->bindParam(':description', $description, PDO::PARAM_STR);
            $query->bindParam(':file_path', $file_path, PDO::PARAM_STR);
            $query->bindParam(':department_id', $department_id, PDO::PARAM_INT);
            
            if ($query->execute()) {
                $lastInsertId = $dbh->lastInsertId();
                if($lastInsertId) {
                    $msg = "Notice Created Successfully";
                    // Clear the input fields
                    $subject = '';
                    $title = '';
                    $description = '';
                    $file_path = '';
                    $department_id = '0'; // Reset to default value for department
                }
            } else {
                $error = "Something went wrong. Please try again";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin | Add Notice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <meta name="description" content="Responsive Admin Dashboard Template" />
    <meta name="keywords" content="admin,dashboard" />
    <meta name="author" content="Steelcoders" />

    <!-- Styles -->
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
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
    <?php include('includes/header.php');?>
    <?php include('includes/sidebar.php');?>

    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Add New Notice </div>
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <form class="col s12" name="addnotice" method="post">
                                <?php if($error) { ?>
                                <div class="errorWrap">
                                    <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                                </div>
                                <?php } else if($msg) { ?>
                                <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
                                </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <span for="title">Title</span>
                                        <input id="title" type="text" class="validate" name="title"
                                            value="<?php echo htmlentities($title); ?>" required>
                                    </div>
                                    <div class="input-field col s12">
                                        <span for="subject">Subject</span>
                                        <input id="subject" type="text" class="validate" name="subject"
                                            value="<?php echo htmlentities($subject); ?>">
                                    </div>
                                    <div class="input-field col s12">
                                        <span for="description">Description</span>
                                        <textarea id="description" class="materialize-textarea expandable"
                                            name="description"><?php echo htmlentities($description); ?></textarea>
                                    </div>
                                    <div class="input-field col s12">
                                        <span for="file_path">File Path Link</span>
                                        <input id="file_path" type="text" class="validate" name="file_path"
                                            value="<?php echo htmlentities($file_path); ?>">
                                    </div>

                                    <!-- <div class="input-field col s12">
                                        <select name="department_id" id="department" required>
                                            <option value="0" selected>All Departments</option>

                                            <?php
                                            // Fetch departments from the database
                                            $sql = "SELECT id, DepartmentName FROM tbldepartments";
                                            $stmt = $dbh->prepare($sql);
                                            $stmt->execute();
                                            $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            
                                            foreach ($departments as $department) {
                                                // Ensure the selected department ID is correctly set
                                                $selected = ($department_id == $department['id']) ? 'selected' : '';
                                                echo '<option value="' . htmlentities($department['id']) . '" ' . $selected . '>' . htmlentities($department['DepartmentName']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <span for="department">Department</span>
                                    </div> -->

                                    <div class="input-field col s12">
                                        <button type="submit" name="add"
                                            class="waves-effect waves-light btn indigo m-b-xs">ADD</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div class="left-sidebar-hover"></div>

    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
</body>

</html>

<?php } ?>