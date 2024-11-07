<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "viswa";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data and store subject code in session
$_SESSION['ss'] = $_POST['subject_code'];
$class = $_POST['class'];
$ss = $_SESSION['ss'];  // Use subject code from session

// Prepare and execute query to fetch students for the given class
$students_query = $conn->prepare("SELECT Regno, Name FROM students WHERE Class = ?");
$students_query->bind_param("s", $class);
$students_query->execute();
$students_result = $students_query->get_result();

$students = [];

// Fetch students and their attendance data for all three phases
if ($students_result->num_rows > 0) {
    while ($row = $students_result->fetch_assoc()) {
        $regno = $row['Regno'];

        // Initialize variables for attendance percentage for all phases
        $att_per1 = 0;
        $att_per2 = 0;
        $att_per3 = 0;

        // Prepare and execute attendance query for all phases
        $attendance_query = $conn->prepare("
            SELECT att_per, att_per1, att_per2 
            FROM attendance 
            WHERE Regno = ? AND sub_code = ?");
        $attendance_query->bind_param("ss", $regno, $ss);
        $attendance_query->execute();
        $attendance_result = $attendance_query->get_result();

        if ($attendance_result->num_rows > 0) {
            $attendance_row = $attendance_result->fetch_assoc();
            $att_per1 = $attendance_row['att_per'];
            $att_per2 = $attendance_row['att_per1'];
            $att_per3 = $attendance_row['att_per2'];
        }

        // Add student data along with attendance percentages for all phases
        $students[] = [
            'Regno' => $row['Regno'],
            'Name' => $row['Name'],
            'att_per' => $att_per1,
            'att_per1' => $att_per2,
            'att_per2' => $att_per3
        ];
    }
}

// Return the data in JSON format
echo json_encode(['students' => $students]);

// Close the connection
$conn->close();
?>
