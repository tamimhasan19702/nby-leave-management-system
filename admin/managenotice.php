<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else { 
    if(isset($_GET['del'])) {
        $id = $_GET['del'];
        $sql = "DELETE FROM notices WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $msg = "Notice record deleted";
    }

    // Toggle status logic
    if(isset($_GET['status']) && isset($_GET['id'])) {
        $id = $_GET['id'];
        $status = $_GET['status'] == '1' ? '0' : '1'; // Toggle status
        $sql = "UPDATE notices SET status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->execute();
        $msg = "Notice status updated";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin | Manage Notice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="../assets/plugins/materialize/css/materialize.min.css" />
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/plugins/material-preloader/css/materialPreloader.min.css" rel="stylesheet">
    <link href="../assets/plugins/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../assets/css/alpha.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css" />
    <style>
    .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
    }

    .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
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
                <div class="page-title">Manage Notice</div>
            </div>
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Notice Info</span>
                        <?php if($msg) { ?><div class="succWrap"><strong>SUCCESS</strong> :
                            <?php echo htmlentities($msg); ?> </div><?php } ?>
                        <table id="example" class="display responsive-table ">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Notice Subject</th>
                                    <th>Notice Title</th>
                                    <th>Notice</th>
                                    <th>File link</th>
                                    <th>Created at</th>
                                    <th>Active Status</th>
                                    <th>Mode</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT * FROM notices";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if($query->rowCount() > 0) {
                                    foreach($results as $result) { 
                                ?>
                                <tr>
                                    <td><?php echo htmlentities($cnt);?></td>
                                    <td><?php echo htmlentities($result->subject);?></td>
                                    <td><?php echo htmlentities($result->title);?></td>
                                    <td><?php echo htmlentities($result->description);?></td>
                                    <td><?php echo htmlentities($result->file_path);?></td>
                                    <td><?php echo htmlentities($result->created_at);?></td>
                                    <td>
                                        <a
                                            href="managenotice.php?status=<?php echo htmlentities($result->status);?>&id=<?php echo htmlentities($result->id);?>">
                                            <button class="btn"
                                                style="background-color: <?php echo $result->status == '1' ? 'green' : 'red'; ?> !important;">
                                                <?php echo htmlentities($result->status == '1' ? 'Active' : 'Inactive'); ?>
                                            </button>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="editnotice.php?noticeid=<?php echo htmlentities($result->id);?>">
                                            <i class="material-icons">mode_edit</i>
                                        </a>
                                        <a href="managenotice.php?del=<?php echo htmlentities($result->id);?>"
                                            onclick="return confirm('Do you want to delete');">
                                            <i class="material-icons">delete_forever</i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    $cnt++;
                                    } 
                                } 
                                ?>
                            </tbody>
                        </table>
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
    <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
    <script src="../assets/js/pages/table-data.js"></script>
</body>

</html>
<?php } ?>