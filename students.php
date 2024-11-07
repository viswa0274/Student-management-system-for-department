<?php 
#include 'header.html'; 
session_start();
if(isset($_SESSION['id'])){
	
	 $conn = new mysqli("localhost", "root", "", "viswa");
$dept_query = "SELECT DISTINCT dep_name FROM department";
$dept_result = $conn->query($dept_query);
    if (isset($_POST['sub'])) {
        
        $conn = new mysqli("localhost", "root", "", "viswa");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        $regno = $_POST['regno'];
        $name = $_POST['name'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $class = $_POST['class'];
        $phone = $_POST['phone'];
		$dep = $_POST['department'];
        $deg = $_POST['category'];
        $parent_phone = '+91' . $_POST['parent_phone'];
        $address = $_POST['address'];
        $email = $_POST['email'];

        $check_sql = "SELECT * FROM students WHERE Regno = '$regno'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            echo "<script>alert('Register number already exists!'); window.location.href='students.php';</script>";
        } else {
            $sql = "INSERT INTO students (Regno, Name, DOB, gender, department, degree, Class, Phone_no, Parent_phone_no, Address, email) 
                    VALUES ('$regno', '$name', '$dob', '$gender', '$dep', '$deg', '$class', '$phone', '$parent_phone', '$address', '$email')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Student details added successfully!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details Form</title>
    <style>
	body{
		 background: url('ii.jpg') no-repeat center center fixed;
    background-size: cover;
	 font-family: 'Roboto', sans-serif;
	}
      .-form-container {
    width: 500px;
    height: 550px; 
    margin: 50px auto;
    
    
    background: url('ii.jpg') no-repeat center center fixed;
    background-size: cover;
    border-radius: 5px;
    position: relative; /* To position the home button correctly */
}


h2 {
    text-align: center;
	
    color: #C5001A;
}

form {
	border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
}
.formcontainer{
	width: 800px;
    height: 650px; /* Adjust height automatically based on content */
    margin: 10px auto;
	margin-top:5px;
    padding: 15px;
    background-color: white;
	background: url('ii.jpg') no-repeat center center fixed;
    background-size: cover;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
position: relative;}
.table-container {
	background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;
	margin-top:1px;
	
    margin-bottom: 20px; /* Space below the table */
}

table {
    width: 90%;
	
    border-collapse: collapse;
     /* Increase height of the table */
}

td, th {
    padding: 10px;
    vertical-align: top; /* Align text to the top */
}

.form-group {
    margin-bottom: 15px;
	
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

input[type="text"],input[type="tel"],input[type="email"],textarea, select {
    width: 100%;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.home-button {
    width: 80px;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1000;
}

.home-button:hover {
    background-color: #0056b3;
}

button {
    width: 30%;
    padding: 10px;
    background-color: green;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: darkgreen;
}

.back-button {
    width: 80px;
    padding: 10px;
    background-color: #c5001a;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.back-button:hover {
    background-color: darkred;
}
.top-buttons {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 20px;
        }

        .top-buttons a {
            padding: 12px 20px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .top-buttons a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<body>
<div class="top-buttons">
            <a href="javascript:history.back()">Back</a>
            <a href="adminpanel.php">Home</a>
        </div>
    
        <div class="b-form-container">
            
            <form action="" method="POST" class="formcontainer">
    <h2>Student Details Form</h2>
                <table align="center">
    
    </tr>
    <!-- 1st row: Register Number and Class -->
    <tr>
        <td>
            <div class="b-input-group">
                <label for="regno">Register Number</label>
                <input type="text" id="regno" name="regno" autocomplete="off" required>
            </div>
        </td>
        <td>
            <div class="b-input-group">
                <label for="class">Class</label>
                <select id="class" name="class" required>
                    <option value="" disabled selected>Select the class</option>
                </select>
            </div>
        </td>
    </tr>
    <!-- 2nd row: Name and Phone Number -->
    <tr>
        <td>
            <div class="b-input-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" autocomplete="off" required>
            </div>
        </td>
        <td>
            <div class="b-input-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" autocomplete="off" required>
            </div>
        </td>
    </tr>
    <!-- 3rd row: DOB and Parent's Phone Number -->
    <tr>
        <td>
            <div class="b-input-group">
                <label for="dob">DOB</label>
                <input type="date" id="dob" value="2000-01-01" name="dob" required>
            </div>
        </td>
        <td>
            <div class="b-input-group">
                <label for="parent_phone">Parent's Phone Number</label>
                <input type="tel" id="parent_phone" name="parent_phone" autocomplete="off" required>
            </div>
        </td>
    </tr>
    <!-- 4th row: Gender and Address -->
    <tr>
        <td>
            <div class="b-input-group b-radio-group">
                <label>Gender</label>
                <label><input type="radio" name="gender" value="male" required> Male</label>
                <label><input type="radio" name="gender" value="female" required> Female</label>
            </div>
        </td>
        <td>
            <div class="b-input-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="4" autocomplete="off" required></textarea>
            </div>
        </td>
    </tr>
    <!-- 5th row: Department and Email -->
    <tr>
        <td>
            <div class="b-input-group">
                <label for="department">Department</label>
                <select id="department" name="department" required>
                    <option value="">Select Department</option>
                    <?php
                                    if ($dept_result->num_rows > 0) {
                                        while ($row = $dept_result->fetch_assoc()) {
                                            echo "<option value='" . $row['dep_name'] . "'>" . $row['dep_name'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No Departments Available</option>";
                                    }
                                    ?>
                </select>
            </div>
        </td>
        <td>
            <div class="b-input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" autocomplete="off" required>
            </div>
        </td>
    </tr>
    <!-- 6th row: Category and Empty -->
    <tr>
        <td>
            <div class="b-input-group">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="UG">UG</option>
                    <option value="PG">PG</option>
                </select>
            </div>
        </td>
        <td>
            <!-- Empty column -->
        </td>
    </tr>
    <!-- Submit button -->
    <tr>
        <td colspan="2" style="text-align: center;">
            <button type="submit" name="sub" class="b-button">Submit</button>
        </td>
    </tr>
</table>

               
            </form></div>
        
</body>


    <script>
        $(document).ready(function() {
            $('#category').change(function() {
                var department = $('#department').val();
                var category = $('#category').val();
                
                console.log("inside");
                if(department && category) {
                    $.ajax({
                        url: 'classes.php', 
                        method: 'POST',
                        data: {department: department, category: category},
                        success: function(response) {
                           console.log(response);
                            $('#class').html(response);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
<?php 
} else {
    header('Location: adminlogin.php');
}
?>