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

    if(isset($_POST['add'])) {
        $subject = $_POST['subject'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $file_path = $_POST['file_path'];

        // Insert notice into the database
        $sql = "INSERT INTO notices (subject, title, description, file_path) VALUES (:subject, :title, :description, :file_path)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':subject', $subject, PDO::PARAM_STR);
        $query->bindParam(':title', $title, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':file_path', $file_path, PDO::PARAM_STR);
        
        try {
            if ($query->execute()) {
                $lastInsertId = $dbh->lastInsertId();
                if($lastInsertId) {
                    $msg = "Notice Created Successfully";
                    // Clear the input fields
                    $subject = '';
                    $title = '';
                    $description = '';
                    $file_path = '';
                    
                    // Redirect to managenotice.php
                    header('location:managenotice.php');
                    exit(); // Ensure no further code is executed
                }
            } else {
                $error = "Something went wrong. Please try again";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage(); // Capture any SQL errors
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                                        <span style="font-weight: bold" for="title">Title</span>
                                        <input id="title" type="text" class="validate" name="title"
                                            value="<?php echo htmlentities($title); ?>" required>
                                    </div>
                                    <div class="input-field col s12">
                                        <span style="font-weight: bold" for="subject">Subject</span>
                                        <input id="subject" type="text" class="validate" name="subject"
                                            value="<?php echo htmlentities($subject); ?>">
                                    </div>
                                    <div class="input-field col s12">
                                        <span style="font-weight: bold" for="description">Description</span>
                                        <textarea id="description" class="materialize-textarea expandable"
                                            name="description"><?php echo htmlentities($description); ?></textarea>
                                    </div>
                                    <div class="input-field col s12">
                                        <span style="font-weight: bold" for="file_path">File Path Link (optional)</span>
                                        <input id="file_path" type="text" class="validate" name="file_path"
                                            value="<?php echo htmlentities($file_path); ?>">
                                    </div>

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