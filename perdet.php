<?php
// Start the session to access logged-in student details
session_start();

// Check if the student is logged in (e.g., Regno is set in the session)
if (!isset($_SESSION['id'])) {
    echo "Please log in to view your details.";
    exit;
}
include 'studentheader.html';
// Get the student's Regno from the session
$regno = $_SESSION['id'];

// Database connection
$conn = new mysqli("localhost", "root", "", "viswa");

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch the student details
$query = "SELECT Regno, Name, gender, department, degree, Class, Phone_no, email, Parent_phone_no, Address 
          FROM students 
          WHERE Regno = '$regno'";

$result = $conn->query($query);

// Check if the student details were found
if ($result && $result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    echo "No student details found for this registration number.";
    exit;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Details</title>
    <style>
	body {
            background-color: #f9f9f9; /* Light gray background for the body */
            font-family: 'Arial', sans-serif;
            text-align: center; /* Center the heading */
            color: #333; /* Dark gray text color */
        }
        table {
            width: 60%;
            margin: 20px auto;
			margin-left:30%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Student Details</h2>

<!-- Display student details in a table -->
<table>
    <tr>
        <th>Register Number:</th>
        <td><?php echo $student['Regno']; ?></td>
    </tr>
    <tr>
        <th>Name:</th>
        <td><?php echo $student['Name']; ?></td>
    </tr>
    <tr>
        <th>Gender:</th>
        <td><?php echo $student['gender']; ?></td>
    </tr>
    <tr>
        <th>Department:</th>
        <td><?php echo $student['department']; ?></td>
    </tr>
    <tr>
        <th>Degree:</th>
        <td><?php echo $student['degree']; ?></td>
    </tr>
    <tr>
        <th>Class:</th>
        <td><?php echo $student['Class']; ?></td>
    </tr>
    <tr>
        <th>Phone Number:</th>
        <td><?php echo $student['Phone_no']; ?></td>
    </tr>
    <tr>
        <th>Email:</th>
        <td><?php echo $student['email']; ?></td>
    </tr>
    <tr>
        <th>Parent's Phone Number:</th>
        <td><?php echo $student['Parent_phone_no']; ?></td>
    </tr>
    <tr>
        <th>Address:</th>
        <td><?php echo $student['Address']; ?></td>
    </tr>
</table>

</body>
</html>
