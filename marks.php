<?php
// Start the session to access logged-in student details
session_start();

// Check if the student is logged in (e.g., Regno is set in the session)
if (!isset($_SESSION['id'])) {
    echo "Please log in to view your mark details.";
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

// Query to fetch the student's mark details along with the subject name
$query = "SELECT DISTINCT s.sub_name, m.int_mark1, m.int_mark2, m.int_mark3, m.ass_mark, m.sem_mark, m.ext_mark, m.result 
          FROM marks m
          JOIN sub_det s ON m.sub_code = s.sub_code
          WHERE m.Regno = '$regno'";

$result = $conn->query($query);

// Check if any mark details were found
if ($result && $result->num_rows > 0) {
    $marks = $result->fetch_all(MYSQLI_ASSOC);

    // Check if there are any failed results
    $hasFailed = false;
    foreach ($marks as $mark) {
        if (isset($mark['result']) && $mark['result'] === 'Reappear') {
            $hasFailed = true;
            break;
        }
    }

} else {
    echo "No mark details found for this registration number.";
    exit;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Mark Details</title>
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

<h2>Student Mark Details</h2>

<?php if ($hasFailed): ?>
    <!-- Display student mark details in a table -->
    <table>
        <tr>
            <th>Subject Name</th>
            <th>Internal Mark 1</th>
            <th>Internal Mark 2</th>
            <th>Internal Mark 3</th>
            <th>Assessment Mark</th>
            <th>Seminar Mark</th>
            <th>External Mark</th>
            <th>Result</th>
        </tr>
        
        <?php foreach ($marks as $mark): ?>
        <tr>
            <td><b><?php echo $mark['sub_name']; ?></b></td>
            <td><?php echo $mark['int_mark1']; ?></td>
            <td><?php echo $mark['int_mark2']; ?></td>
            <td><?php echo $mark['int_mark3']; ?></td>
            <td><?php echo $mark['ass_mark']; ?></td>
            <td><?php echo $mark['sem_mark']; ?></td>
            <td><?php echo $mark['ext_mark']; ?></td>
            <td><b><?php echo $mark['result']; ?></b></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <h3 class="success-message">Congratulations! No backlog. Keep it up!</h3>
<?php endif; ?>

</body>
</html>
