<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {
    // Update notice status if status parameter is set
    if(isset($_GET['status']) && isset($_GET['noticeid'])) {
        $nid = intval($_GET['noticeid']);
        $status = $_GET['status'] == '1' ? '0' : '1'; // Toggle status
        $sql = "UPDATE notices SET status=:status WHERE id=:nid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':nid', $nid, PDO::PARAM_STR);
        $query->execute();
        $msg = "Notice status updated Successfully";
    }

    if(isset($_POST['update'])) {
        $nid = intval($_GET['noticeid']);    
        $subject = $_POST['subject'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $file_path = $_POST['file_path'];
        $status = $_POST['status'];
        $department_id = $_POST['department_id']; // Get department ID

        $sql = "UPDATE notices SET subject=:subject, title=:title, description=:description, file_path=:file_path, status=:status, department_id=:department_id WHERE id=:nid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':subject', $subject, PDO::PARAM_STR);
        $query->bindParam(':title', $title, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':file_path', $file_path, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':department_id', $department_id, PDO::PARAM_STR); // Bind department ID
        $query->bindParam(':nid', $nid, PDO::PARAM_STR);
        $query->execute();
        $msg = "Notice updated Successfully";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin | Edit Notice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
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
                <div class="page-title">Edit Notice</div>
            </div>
            <div class="col s12 m12 l6">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <form class="col s12" name="editnotice" method="post">
                                <?php if($error) { ?>
                                <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?></div>
                                <?php } else if($msg) { ?>
                                <div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?></
                                        div>
                                    <?php } ?>

                                    <?php 
                                $nid = intval($_GET['noticeid']);
                                $sql = "SELECT * FROM notices WHERE id=:nid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':nid', $nid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0) {
                                    foreach($results as $result) { 
                                ?>
                                    <div class="row">
                                        <div class="input-field col s12">
                                            <input id="subject" type="text" class="validate" name="subject"
                                                value="<?php echo htmlentities($result->subject); ?>" required>
                                            <label for="subject">Notice Subject</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="title" type="text" class="validate" name="title"
                                                value="<?php echo htmlentities($result->title); ?>">
                                            <label for="title">Notice Title</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <textarea id="description" class="materialize-textarea"
                                                name="description"><?php echo htmlentities($result->description); ?></textarea>
                                            <label for="description">Notice Description</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input id="file_path" type="text" class="validate" name="file_path"
                                                value="<?php echo htmlentities($result->file_path); ?>">
                                            <label for="file_path">File Path</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <select name="department_id" id="department">
                                                <option value="0" selected>All Departments</option>
                                                <?php
                                            // Fetch departments from the database
                                            $sql = "SELECT id, DepartmentName FROM tbldepartments";
                                            $stmt = $dbh->prepare($sql);
                                            $stmt->execute();
                                            $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            
                                            foreach ($departments as $department) {
                                                // Ensure the selected department ID is correctly set
                                                $selected = ($result->department_id == $department['id']) ? 'selected' : '';
                                                echo '<option value="' . htmlentities($department['id']) . '" ' . $selected . '>' . htmlentities($department['DepartmentName']) . '</option>';
                                            }
                                            ?>
                                            </select>
                                            <label for="department">Department</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <select name="status" required>
                                                <option value="1"
                                                    <?php echo $result->status == '1' ? 'selected' : ''; ?>>
                                                    Active</option>
                                                <option value="0"
                                                    <?php echo $result->status == '0' ? 'selected' : ''; ?>>
                                                    Inactive</option>
                                            </select>
                                            <label>Status</label>
                                        </div>
                                    </div>
                                    <?php 
                                    } 
                                } 
                                ?>
                                    <div class="input-field col s12">
                                        <button type="submit" name="update"
                                            class="waves-effect waves-light btn indigo m-b-xs">UPDATE</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="left-sidebar-hover"></div>
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
    <script src="../assets/js/pages/form_elements.js"></script>
</body>

</html>
<?php } ?>