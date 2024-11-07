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

$subject_query = "SELECT DISTINCT s.sub_code, d.sub_name FROM subject s
                  JOIN sub_det d ON s.sub_code = d.sub_code
                  WHERE s.class='$class'";
$subject_result = $conn->query($subject_query);

$subjects = [];

if ($subject_result->num_rows > 0) {
    while ($row = $subject_result->fetch_assoc()) {
        $subjects[] = [
            'sub_code' => $row['sub_code'],
            'sub_name' => $row['sub_name']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode(['subjects' => $subjects]);

$conn->close();
?>
