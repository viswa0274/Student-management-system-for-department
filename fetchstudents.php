<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "viswa";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$class = $_POST['class'];

$query = "SELECT Regno, Name FROM students WHERE Class='$class'";
$result = $conn->query($query);

$students = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode(['students' => $students]);

$conn->close();
?>
