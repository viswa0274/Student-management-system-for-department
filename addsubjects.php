<?php
include 'header.html';
session_start();
if(isset($_SESSION['id'])){



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "viswa");

    $subject_code = $_POST['subject_code'];
    $subject_name = $_POST['subject_name'];
    $subject_type = $_POST['subject_type'];
    $department = $_POST['department'];
    $degree = $_POST['degree'];

   
    $checkQuery = "SELECT * FROM sub_det WHERE sub_code = ? AND sub_name = ?";
    if ($stmt = $conn->prepare($checkQuery)) {
        $stmt->bind_param("ss", $subject_code, $subject_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
           
            echo "<script>alert('Subject Already Inserted');</script>";
        } else {
           
            $insertQuery = "INSERT INTO sub_det (sub_code, sub_name, sub_type, department, category) 
                            VALUES (?, ?, ?, ?, ?)";

            if ($insertStmt = $conn->prepare($insertQuery)) {
         
                $insertStmt->bind_param("sssss", $subject_code, $subject_name, $subject_type, $department, $degree);

        
                if ($insertStmt->execute()) {
                    echo "<script>alert('Subject Added Successfully');</script>";
                } else {
                    echo "Error: " . $insertStmt->error;
                }

               
                $insertStmt->close();
            } else {
                echo "Error preparing insert statement: " . $conn->error;
            }
        }

       
        $stmt->close();
    } else {
        echo "Error preparing check statement: " . $conn->error;
    }

    
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
			background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
        }

        .form-container {
            background-color: rose;
            padding: 20px;
			margin-left:9%;
            border-radius: 10px;
            //box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 100%;
			
            max-width: 500px;
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            color: #990011;
            margin-bottom: 20px;
            font-size: 24px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="text"]:focus, select:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add Subject</h2>

    <form id="subjectForm" action="" method="POST">
        <div class="form-group">
            <label for="subject_code">Subject Code:</label>
            <input type="text" id="subject_code" name="subject_code" autocomplete="off" required>
        </div>

        <div class="form-group">
            <label for="subject_name">Subject Name:</label>
            <input type="text" id="subject_name" name="subject_name" autocomplete="off" required>
        </div>

        <div class="form-group">
            <label for="type">Subject Type:</label>
			<input type="text" id="subject_type" name="subject_type" autocomplete="off" required>
            
        </div>

        <div class="form-group">
            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <option value="" disabled selected>Select Department</option>
            </select>
        </div>

        <div class="form-group">
            <label for="degree">Degree:</label>
            <select id="degree" name="degree" required>
                <option value="" disabled selected>Select Degree</option>
            </select>
        </div>

        <button type="submit">Add Subject</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        fetchDepartmentDegree();

        function fetchDepartmentDegree() {
            $.ajax({
                url: 'fetchdegree.php',
                method: 'GET',
                success: function(response) {
                    var data = JSON.parse(response);

                    $.each(data.departments, function(index, dep) {
                        $('#department').append('<option value="' + dep.dep_name + '">' + dep.dep_name + '</option>');
                    });

                    $.each(data.degrees, function(index, degree) {
                        $('#degree').append('<option value="' + degree.degree + '">' + degree.degree + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching department and degree: " + xhr.responseText);
                }
            });
        }
    });
</script>

</body>
</html><?php }else{
header ('location:adminlogin.php');}?>
