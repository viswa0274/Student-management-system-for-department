<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "viswa";
session_start();
$a=$_SESSION['id'];
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$class = $_POST['class'];

$subject_query = "SELECT DISTINCT sub_code FROM subject WHERE class='$class' AND staffid='$a'";
$subject_result = $conn->query($subject_query);

$subjects = [];

if ($subject_result->num_rows > 0) {
    while ($row = $subject_result->fetch_assoc()) {
        $sub_code = $row['sub_code'];
       
        $name_query = "SELECT DISTINCT sub_name FROM sub_det WHERE sub_code='$sub_code'";
        $name_result = $conn->query($name_query);
        if ($name_result->num_rows > 0) {
            while ($name_row = $name_result->fetch_assoc()) {
                $subjects[] = [
                    'sub_code' => $sub_code,
                    'sub_name' => $name_row['sub_name']
                ];
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode(['subjects' => $subjects]);

$conn->close();
?>
