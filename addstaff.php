<?php 
session_start();

if (isset($_SESSION['id'])) {
include 'header.html';
    $conn = new mysqli("localhost", "root", "", "viswa");

    if(!$conn) {
        die("Connection Failed:".mysqli_connect_error());
    }

    $dept_query = "SELECT DISTINCT dep_name FROM department";
    $dept_result = mysqli_query($conn, $dept_query);

    if(isset($_POST['Sub'])) {
        $a = $_POST['staffid'];
        $b = $_POST['name'];
        $e = $_POST['emailid'];
        $f = $_POST['Phoneno'];
        $g = $_POST['password'];
        $h = $_POST['department']; 
        $j = $_POST['category'];

        $s = "INSERT INTO `staff` (`staffid`, `Name`, `email`, `Phone_no`, `Password`, `department`,`category`) 
              VALUES ('$a', '$b', '$e', '$f', '$g', '$h','$j')";

        if(mysqli_query($conn, $s)) {
			echo "<script>alert('Staff added successfully');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();  
        } else {
            echo "<script>alert('Error adding staff');</script>";
        }
    }

   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { background-color: white;
		background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;}
        .form-container {
    //background-color: #FDF6F6;
    padding: 20px;
    border-radius: 5px;
    margin-top: 1%;
	//background: url('ii.jpg') no-repeat center center fixed;
            //background-size: cover;
    margin-left: auto;
    margin-right: auto;
    width: 300px;
    text-align: center;
    color: black;
   // box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
        h2 { margin-bottom: 20px; color: black; }
        h3 { color: white; }
        .form-group {
            margin-bottom: 14px;
            text-align: left;
            color: black;
        }
        label {
            display: block;
			color: black;
            margin-bottom: 5px;
           // color: #C5001A;
            font-weight: bold; /* Make the label text bold */
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="tel"], select, .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: white;
            color: black;
            box-sizing: border-box;
            height: 40px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: green;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background-color: darkgreen; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Add Staff Details</h2>
        <form action="addstaff.php" method="post">
            <div class="form-group">
                <label for="staffid">Staff ID:</label>
                <input type="text" id="staffid" name="staffid" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label for="emailid">Email ID:</label>
                <input type="email" id="emailid" name="emailid" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label for="Phoneno">Phone Number:</label>
                <input type="tel" id="Phoneno" name="Phoneno" pattern="[0-9]{10}" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label for="department">Department:</label>
                <select id="department" name="department" required>
                    <option value="">Select Department</option>
                    <?php
                    if (mysqli_num_rows($dept_result) > 0) {
                        while($dept_row = mysqli_fetch_assoc($dept_result)) {
                            echo "<option value='" . $dept_row['dep_name'] . "'>" . $dept_row['dep_name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No Departments Available</option>";
                    }
                    ?>
                </select></div>
                <div class="form-group">
                <label for="category">Degree:</label>
                <select id="category" name="category" required>
                    <option value="">Select Degree</option>
                    <option value="UG">UG</option>
                    <option value="PG">PG</option>
                </select>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="Sub">Add Staff</button>
        </form>
    </div>
</body>
</html>

<?php
} else {
    header("Location:adminlogin.php");
    exit();
}
?>
