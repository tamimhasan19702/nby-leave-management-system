<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
} else {
    if(isset($_POST['add'])) {
        $empid = $_POST['empcode'];
        $fname = $_POST['firstName'];
        $lname = $_POST['lastName'];   
        $email = $_POST['email']; 
        $username = $_POST['username']; 
        $password = md5($_POST['password']); 
        $gender = $_POST['gender']; 
        $dob = $_POST['dob']; 
        $department = $_POST['department']; 
        $address = $_POST['address']; 
        $city = $_POST['city']; 
        $country = $_POST['country']; 
        $mobileno = $_POST['mobileno']; 
        $status = 1;
        $annualLeave = $_POST['annual_leave'];
        $sickLeave = $_POST['sick_leave'];

        // Get the profile picture link
        $imageData = isset($_POST['profilepic']) && !empty($_POST['profilepic']) ? $_POST['profilepic'] : '../assets/images/NBY_IT_SOLUTION_LOGO_SYMBLE-removebg-preview.png'; // Set your default image link here

        // Updated SQL query to include Image
        $sql = "INSERT INTO tblemployees(EmpId, FirstName, LastName, EmailId, Username, Password, Gender, Dob, Department, Address, City, Country, Phonenumber, Status, Image) 
                VALUES(:empid, :fname, :lname, :email, :username, :password, :gender, :dob, :department, :address, :city, :country, :mobileno, :status, :image)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':empid', $empid, PDO::PARAM_STR);
        $query->bindParam(':fname', $fname, PDO::PARAM_STR);
        $query->bindParam(':lname', $lname, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->bindParam(':gender', $gender, PDO::PARAM_STR);
        $query->bindParam(':dob', $dob, PDO::PARAM_STR);
        $query->bindParam(':department', $department , PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':city', $city, PDO::PARAM_STR);
        $query->bindParam(':country', $country, PDO::PARAM_STR);
        $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':image', $imageData, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId) {
            $msg = "Employee record added Successfully";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>Admin | Add Employee</title>
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
    <script type="text/javascript">
    function valid() {
        if (document.addemp.password.value != document.addemp.confirmpassword.value) {
            alert("New Password and Confirm Password Field do not match  !!");
            document.addemp.confirmpassword.focus();
            return false;
        }
        return true;
    }
    </script>

    <script>
    function checkAvailabilityEmpid() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'empcode=' + $("#empcode").val(),
            type: "POST",
            success: function(data) {
                $("#empid-availability").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }
    </script>

    <script>
    function checkAvailabilityEmailid() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'emailid=' + $("#email").val(),
            type: "POST",
            success: function(data) {
                $("#emailid-availability").html(data);
                $("#loaderIcon").hide();
            },
            error: function() {}
        });
    }
    </script>

</head>

<body>
    <?php include('includes/header.php');?>
    <?php include('includes/sidebar.php');?>
    <main class="mn-inner">
        <div class="row">
            <div class="col s12">
                <div class="page-title">Add employee</div>
            </div>
            <div class="col s12 m12 l12">
                <div class="card">
                    <div class="card-content">
                        <h3>Employee Info</h3>



                        <form id="example-form" method="post" name="addemp">
                            <div>

                                <section>
                                    <div class="wizard-content">
                                        <div class="row">
                                            <div class="col m6">
                                                <div class="row">
                                                    <?php if($error){?><div class="errorWrap">
                                                        <strong>ERROR</strong>:<?php echo htmlentities($error); ?>
                                                    </div><?php } 
                                                    else if($msg){?><div class="succWrap">
                                                        <strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?>
                                                    </div>
                                                    <?php }?>



                                                    <div class="input-field col s12">
                                                        <span for="profilepic">Profile Picture Link</span>
                                                        <div id="image-preview"></div>
                                                        <input name="profilepic" id="profilepic" type="text"
                                                            class="validate" onchange="checkImageLink(this.value)">
                                                        <span id="profilepic-availability"
                                                            style="font-size:12px;"></span>
                                                        <button type="button" onclick="previewImage()"
                                                            class="waves-effect waves-light btn indigo m-b-xs">Preview
                                                            Image</button>
                                                        <button type="button" onclick="removeImage()"
                                                            class="waves-effect waves-light btn red m-b-xs">Remove
                                                            Image</button>
                                                    </div>

                                                    <script>
                                                    function checkImageLink(link) {
                                                        var img = new Image();
                                                        img.onload = function() {
                                                            $("#profilepic-availability").html("");
                                                        };
                                                        img.onerror = function() {
                                                            $("#profilepic-availability").html(
                                                                "<span style='color:red'>Warning: This image link is not accessible</span>"
                                                            );
                                                        };
                                                        img.src = link;
                                                    }

                                                    function previewImage() {
                                                        var link = document.getElementById('profilepic').value;
                                                        var imgHtml = '<img src="' + link +
                                                            '" alt="Image Preview" style="width: 100px; height: auto;">';
                                                        document.getElementById('image-preview').innerHTML = imgHtml;
                                                    }

                                                    function removeImage() {
                                                        document.getElementById('profilepic').value = '';
                                                        document.getElementById('image-preview').innerHTML = '';
                                                        $("#profilepic-availability").html("");
                                                    }
                                                    </script>



                                                    <div class="input-field col s12">
                                                        <span for="empcode">Employee Code(Must be unique)</span>
                                                        <input name="empcode" id="empcode"
                                                            onBlur="checkAvailabilityEmpid()" type="text"
                                                            autocomplete="off" required>
                                                        <span id="empid-availability" style="font-size:12px;"></span>
                                                    </div>

                                                    <div class="input-field col s12">
                                                        <span for="username">Username (Must be unique)</span>
                                                        <input name="username" id="username" type="text"
                                                            autocomplete="off" required>
                                                        <span id="username-availability" style="font-size:12px;"></span>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <span for="firstName">First name</span>
                                                        <input id="firstName" name="firstName" type="text" required>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <span for="lastName">Last name</span>
                                                        <input id="lastName" name="lastName" type="text"
                                                            autocomplete="off" required>
                                                    </div>

                                                    <div class="input-field col s12">
                                                        <span for="email">Email</span>
                                                        <input name="email" type="email" id="email"
                                                            onBlur="checkAvailabilityEmailid()" autocomplete="off"
                                                            required>
                                                        <span id="emailid-availability" style="font-size:12px;"></span>
                                                    </div>

                                                    <div class="input-field col s12">
                                                        <span for="password">Password</span>
                                                        <input id="password" name="password" type="password"
                                                            autocomplete="off" required>
                                                        <span class="eye-icon" onclick="togglePassword()"><i
                                                                class="material-icons">visibility</i></span>
                                                    </div>

                                                    <style>
                                                    .eye-icon {
                                                        position: absolute;
                                                        right: 10px;
                                                        top: 10px;
                                                        cursor: pointer;
                                                    }
                                                    </style>

                                                    <script>
                                                    function togglePassword() {
                                                        var x = document.getElementById("password");
                                                        if (x.type === "password") {
                                                            x.type = "text";
                                                        } else {
                                                            x.type = "password";
                                                        }
                                                    }
                                                    </script>

                                                    <div class="input-field col s12">
                                                        <span for="confirm">Confirm password</span>
                                                        <input id="confirm" name="confirmpassword" type="password"
                                                            autocomplete="off" required>
                                                        <span class="eye-icon" onclick="toggleConfirmPassword()"><i
                                                                class="material-icons">visibility</i></span>
                                                    </div>

                                                    <style>
                                                    .eye-icon {
                                                        position: absolute;
                                                        right: 10px;
                                                        top: 10px;
                                                        cursor: pointer;
                                                    }
                                                    </style>

                                                    <script>
                                                    function toggleConfirmPassword() {
                                                        var x = document.getElementById("confirm");
                                                        if (x.type === "password") {
                                                            x.type = "text";
                                                        } else {
                                                            x.type = "password";
                                                        }
                                                    }
                                                    </script>
                                                </div>
                                            </div>

                                            <div class="col m6">
                                                <div class="row">
                                                    <div class="input-field col m6 s12">
                                                        <select name="gender" autocomplete="off"
                                                            class="browser-default">
                                                            <option value="">Gender...</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <span for="birthdate">Birthdate</span>
                                                        <input id="birthdate" name="dob" type="text" autocomplete="off">
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <select name="department" autocomplete="off"
                                                            class="browser-default">
                                                            <option value="">Department...</option>
                                                            <?php 
                                                            $sql = "SELECT DepartmentName from tbldepartments";
                                                            $query = $dbh->prepare($sql);
                                                            $query->execute();
                                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                            if($query->rowCount() > 0)
                                                            {
                                                                foreach($results as $result)
                                                                { ?>
                                                            <option
                                                                value="<?php echo htmlentities($result->DepartmentName);?>">
                                                                <?php echo htmlentities($result->DepartmentName);?>
                                                            </option>
                                                            <?php }
                                                            } ?>
                                                        </select>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <span for="address">Address</span>
                                                        <input id="address" name="address" type="text"
                                                            autocomplete="off" required>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <span for="city">City/Town</span>
                                                        <input id="city" name="city" type="text" autocomplete="off"
                                                            required>
                                                    </div>

                                                    <div class="input-field col m6 s12">
                                                        <span for="country">Country</span>
                                                        <input id="country" name="country" type="text"
                                                            autocomplete="off" required>
                                                    </div>

                                                    <div class="input-field col s12">
                                                        <span for="phone">Mobile number</span>
                                                        <input id="phone" name="mobileno" type="tel" maxlength="10"
                                                            autocomplete="off" required>
                                                    </div>


                                                    <div class="input-field col s12">
                                                        <span for="annual_leave">Set Annual Leave</span>
                                                        <input id="annual_leave" name="annual_leave" type="number"
                                                            autocomplete="off" required>
                                                    </div>

                                                    <div class="input-field col s12">
                                                        <span for="sick_leave">Set Sick Leave</span>
                                                        <input id="sick_leave" name="sick_leave" type="number"
                                                            autocomplete="off" required>
                                                    </div>


                                                    <div class="input-field col s12">
                                                        <button type="submit" name="add" onclick="return valid();"
                                                            id="add"
                                                            class="waves-effect waves-light btn indigo m-b-xs">ADD</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>
    <div class="left-sidebar-hover"></div>

    <!-- Javascripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
    <script src="../assets/js/pages/form_elements.js"></script>

</body>

</html>
<?php } ?>