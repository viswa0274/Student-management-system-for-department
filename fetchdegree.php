<?php

$conn = new mysqli("localhost", "root", "", "viswa");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$depQuery = "SELECT DISTINCT dep_name FROM department";
$depResult = $conn->query($depQuery);

$departments = [];
if ($depResult->num_rows > 0) {
    while ($row = $depResult->fetch_assoc()) {
        $departments[] = $row;
    }
}


$degreeQuery = "SELECT DISTINCT degree FROM department";
$degreeResult = $conn->query($degreeQuery);

$degrees = [];
if ($degreeResult->num_rows > 0) {
    while ($row = $degreeResult->fetch_assoc()) {
        $degrees[] = $row;
    }
}


$response = [
    'departments' => $departments,
    'degrees' => $degrees
];


echo json_encode($response);

$conn->close();
?>
