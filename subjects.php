<?php
$conn = new mysqli("localhost", "root", "", "viswa");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

	
// $staff_query = "SELECT staffid FROM staff";
// $staff_result = $conn->query($staff_query);

$dept_query = "SELECT DISTINCT dep_name FROM department";
$dept_result = $conn->query($dept_query);

if (isset($_POST['allocate'])) {
    $staffid = $_POST['staffid'];
    $class = $_POST['class'];
    $sub_id = $_POST['sub_id'];
    $sub_name = $_POST['sub_name'];
    $sub_type = $_POST['sub_type'];
    $semester = $_POST['semester'];

    $allocation_query = "INSERT INTO subject (staffid, class, sub_code, sem) 
                         VALUES ('$staffid', '$class', '$sub_id', '$semester')";

    if ($conn->query($allocation_query) === TRUE) {
        echo "<script>alert('Subject allocated successfully!');</script>";
    } else {
        echo "Error: " . $allocation_query . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allocate Subject to Staff</title>
	<style>
	body{
		background: url('ii.jpg') no-repeat center center fixed;
    background-size: cover;
	 font-family: 'Roboto', sans-serif;
	}
    .form-container {
    width: 800px;
    height: 550px; /* Adjust height automatically based on content */
    margin: 50px auto;
	margin-top:1px;
    padding: 20px;
    background-color: white;
	background: url('ii.jpg') no-repeat center center fixed;
    background-size: cover;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    position: relative; /* To position the home button correctly */
}

h2 {
    text-align: center;
    color: #C5001A;
}

form {
    display: flex;
    flex-direction: column;
}

.table-container {
	margin-top:1px;
    margin-bottom: 20px; /* Space below the table */
}

table {
    width: 100%;
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

input[type="text"], select {
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
    width: 20%;
	text-align: center;
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



    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>


<button class="back-button" onclick="window.history.back()">Back</button><div class="form-container">
<button class="home-button" onclick="window.location.href='adminpanel.php'">Home</button>

    
    <form action="allocatesubject.php" method="POST">
	<h2>Allocate Subject to Staff</h2>
     <table>
             <tr>
                  <td>
        <div class="form-group">
            <label for="department">Department:</label>
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
        </div></td><td><div class="form-group">
            <label for="class">Class:</label>
            <select id="class" name="class" required>
                <option value="">Select Class</option>
            </select>
        </div></td></tr><tr><td>

        
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="UG">UG</option>
                <option value="PG">PG</option>
            </select>
        </div></td><td><div class="form-group">
            <label for="sub_name">Subject Name:</label>
            <select id="sub_name" name="sub_name" required>
                <option value="">Select Subject Name</option>
            </select>
        </div></td></tr><tr><td>

 
        <div class="form-group">
            <label for="staffid">Staff ID:</label>
            <select id="staffid" name="staffid" required>
                <option value="">Select Staff ID</option>
                <!-- <?php
                // if ($staff_result->num_rows > 0) {
                //     while ($row = $staff_result->fetch_assoc()) {
                //         echo "<option value='" . $row['staffid'] . "'>" . $row['staffid'] . "</option>";
                //     }
                // } else {
                //     echo "<option value=''>No Staff Available</option>";
                // }
                ?> -->
            </select>
        </div></td><td><div class="form-group">
            <label for="sub_id">Subject ID:</label>
            <input type="text" id="sub_id" name="sub_id" readonly>
        </div></td></tr><tr><td>

        <div class="form-group">
            <label for="staffname">Staff Name:</label>
            <input type="text" id="staffname" name="staffname" readonly>
        </div></td><td>

       
        
		
		

        
        

        
        

        <div class="form-group">
            <label for="sub_type">Subject Type:</label>
            <input type="text" id="sub_type" name="sub_type" readonly>
        </div></td></tr><tr><td>

        
        <div class="form-group">
            <label for="semester">Semester:</label>
            <select id="semester" name="semester" required></select>
        </div></td><td></td></tr><tr><td colspan="2"><button type="submit" name="allocate">Allocate Subject</button></td></tr></table>

        
    </form>
</div>

<script>
$(document).ready(function() {
    $('#staffid').change(function() {
        var staffid = $(this).val();

        if (staffid != "") {
            $.ajax({
                url: 'getstaffdetails.php',
                method: 'POST',
                data: { staffid: staffid },
                dataType: 'json',
                success: function(response) {
                    $('#staffname').val(response.Name);
                    cate();
                }
            });
        } else {
            $('#staffname').val('');
        }
    });

    function cate() {
        var department = $('#department').val();
        var category = $('#category').val();
        if (department != "" && category != "") {
            $.ajax({
                url: 'getstaffdetails.php',
                method: 'POST',
                data: { dep_name: department, category: category },
                dataType: 'json',
                success: function(response) {
                    $('#class').empty().append('<option value="">Select Class</option>');
                    $.each(response.classes, function(index, cls) {
                        $('#class').append('<option value="' + cls + '">' + cls + '</option>');
                    });

                    $('#sub_name').empty().append('<option value="">Select Subject Name</option>');
                    $.each(response.subjects, function(index, subject) {
                        $('#sub_name').append('<option value="' + subject + '">' + subject + '</option>');
                    });

                    $('#sub_id').val('');
                    $('#sub_type').val('');
                },
                error: function(res) {
                    console.log('Error fetching data.' + res);
                }
            });
        } else {
            $('#class').empty().append('<option value="">Select Class</option>');
            $('#sub_name').empty().append('<option value="">Select Subject Name</option>');
            $('#sub_id').val('');
            $('#sub_type').val('');
        }
    }

    $('#category').change(function() {
        var department = $('#department').val();
        var cate = $('#category').val();
        console.log("inside");
        console.log(cate, department);
        if (department != "") {
            $.ajax({
                url: 'getstaffdetails.php',
                method: 'POST',
                data: { fetch_staff_by_department: true, department: department ,category:cate},
                dataType: 'json',
                success: function(response) {
                    $('#staffid').empty().append('<option value="">Select Staff ID</option>');
                    $.each(response, function(index, staffid) {
                        $('#staffid').append('<option value="' + staffid + '">' + staffid + '</option>');
                    });
                },
                error: function() {
                    console.log("Error fetching staff based on department.");
                }
            });
        } else {
            $('#staffid').empty().append('<option value="">Select Staff ID</option>');
        }
    });


    $('#staffid').change(function() {
        var staffid = $(this).val();

        if (staffid != "") {
            $.ajax({
                url: 'getstaffdetails.php',
                method: 'POST',
                data: { fetch_staff_details: true, staffid: staffid },
                dataType: 'json',
                success: function(response) {
                    $('#staffname').val(response.Name);
                },
                error: function() {
                    console.log("Error fetching staff details.");
                }
            });
        } else {
            $('#staffname').val('');
        }
    });


    $('#sub_name').change(function() {
        var subName = $(this).val();
        var department = $('#department').val();

        if (subName != "" && department != "") {
            $.ajax({
                url: 'getstaffdetails.php',
                method: 'POST',
                data: { sub_name: subName, department: department },
                dataType: 'json',
                success: function(response) {
                    $('#sub_id').val(response.sub_code);
                    $('#sub_type').val(response.sub_type);
                }
            });
        } else {
            $('#sub_id').val('');
            $('#sub_type').val('');
        }
    });

    function updateSemesterOptions() {
        var today = new Date();
        var month = today.getMonth() + 1;

        var semesterOptions = ['Sem-2', 'Sem-4'];
        if (month >= 7 && month <= 11) {
            semesterOptions = ['Sem-1', 'Sem-3'];
        }

        var semesterSelect = $('#semester');
        semesterSelect.empty();
        semesterSelect.append('<option value="">Select Semester</option>');
        $.each(semesterOptions, function(index, value) {
            semesterSelect.append('<option value="' + value + '">' + value + '</option>');
        });
    }

    updateSemesterOptions();
});
</script>

</body>
</html>