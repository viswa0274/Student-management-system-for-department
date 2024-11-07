<?php
// Start the session to access logged-in student details
session_start();

// Check if the student is logged in (e.g., Regno is set in the session)
if (!isset($_SESSION['id'])) {
    echo "Please log in to view your attendance details.";
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

// Query to fetch the student's attendance details along with the subject name
$query = "SELECT DISTINCT s.sub_name, a.att_per, a.att_per1, a.att_per2 
          FROM attendance a
          JOIN sub_det s ON a.sub_code = s.sub_code
          WHERE a.Regno = '$regno'";

$result = $conn->query($query);

// Check if any attendance details were found
if ($result && $result->num_rows > 0) {
    $attendance = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "No attendance details found for this registration number.";
    exit;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Attendance Details</title>
    <style>
        body {
            background-color: #f9f9f9; /* Light gray background for the body */
            font-family: 'Arial', sans-serif;
            text-align: center; /* Center the heading */
            color: #333; /* Dark gray text color */
        }

        h2 {
            margin-top: 20px;
            color: #2c3e50; /* Dark blue color for the heading */
        }

        table {
            width: 60%;
            margin: 20px auto; 
			margin-left:30%;/* Center the table */
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        table, th, td {
            border: 1px solid #ccc; /* Light gray border */
        }

        th, td {
            padding: 12px; /* Increased padding for better spacing */
            text-align: left;
        }

        th {
            background-color: green; /* Blue background for table header */
            color: white; /* White text in header */
        }

        tr:nth-child(even) {
            background-color: #ecf0f1; /* Light gray for even rows */
        }

        tr:hover {
            background-color: #d1ecf1; /* Light blue hover effect */
        }

        .success-message {
            font-size: 18px;
            margin: 20px 0;
            color: #27ae60; /* Green color for success message */
        }
    </style>
</head>
<body>

<h2>Student Attendance Details</h2>

<!-- Display student attendance details in a table -->
<table>
    <tr>
        <th>Subject Name</th>
        <th>Phase 1 Attendance Percentage</th>
        <th>Phase 2 Attendance Percentage</th>
        <th>Phase 3 Attendance Percentage</th>
        <th>Total Attendance Percentage</th>
    </tr>
    
   <?php foreach ($attendance as $record): ?>
    <tr>
        <td><?php echo $record['sub_name']; ?></td>
        <td><?php echo round($record['att_per']); ?>%</td>
        <td><?php echo round($record['att_per1']); ?>%</td>
        <td><?php echo round($record['att_per2']); ?>%</td>
        <td>
            <?php
            // Calculate total attendance percentage and round it
            $total_attendance = ($record['att_per'] + $record['att_per1'] + $record['att_per2']) / 3;
            echo round($total_attendance) . "%"; // Round to the nearest whole number
            ?>
        </td>
    </tr>
<?php endforeach; ?>

</table>

</body>
</html>
