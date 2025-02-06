<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {
    // Initialize variables to hold form data
    $departmentName = '';
    $departmentShortName = '';
    $departmentCode = '';
    
    if(isset($_POST['add'])) {
        $departmentName = $_POST['departmentName'];
        $departmentShortName = $_POST['departmentShortName'];
        $departmentCode = $_POST['departmentCode'];

        // Insert department into the database
        $sql = "INSERT INTO tbldepartments (DepartmentName, DepartmentShortName, DepartmentCode) VALUES (:departmentName, :departmentShortName, :departmentCode)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':departmentName', $departmentName, PDO::PARAM_STR);
        $query->bindParam(':departmentShortName', $departmentShortName, PDO::PARAM_STR);
        $query->bindParam(':departmentCode', $departmentCode, PDO::PARAM_STR);
        $query->execute();
        
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId) {
            $msg = "Department Created Successfully";
            // Clear the input fields
            $departmentName = '';
            $departmentShortName = '';
            $departmentCode = '';
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin | Add New Department</title>
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
                <div class="page-title">Add New Department</div>
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <form class="col s12" name="adddepartment" method="post">
                                <?php if($error) { ?>
                                <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities ($error); ?>
                                </div>
                                <?php } else if($msg) { ?>
                                <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div>
                                <?php } ?>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <span style="font-weight: bold" for="departmentName">Department Name</span>
                                        <input id="departmentName" type="text" class="validate" name="departmentName"
                                            value="<?php echo htmlentities($departmentName); ?>" required>
                                    </div>
                                    <div class="input-field col s12">
                                        <span style="font-weight: bold" for="departmentShortName">Department Short
                                            Name</span>
                                        <input id="departmentShortName" type="text" class="validate"
                                            name="departmentShortName"
                                            value="<?php echo htmlentities($departmentShortName); ?>" required>
                                    </div>
                                    <div class="input-field col s12">
                                        <span style="font-weight: bold" for="departmentCode">Department Code</span>
                                        <input id="departmentCode" type="text" class="validate" name="departmentCode"
                                            value="<?php echo htmlentities($departmentCode); ?>" required>
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