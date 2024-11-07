<?php
$data = json_decode(file_get_contents('php://input'), true);

$regno = $data['Regno'];
$name = $data['Name'];
$dob = $data['DOB'];
$gender = $data['gender'];
$dep = $data['department'];
$deg = $data['degree'];
$class = $data['Class'];
$phone = $data['Phone_no'];
$parent_phone = $data['Parent_phone_no'];
$address = $data['Address'];
$email = $data['email'];

$conn = new mysqli("localhost", "root", "", "viswa");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$update_query = "UPDATE students SET Name='$name', DOB='$dob', gender='$gender',department='$dep',degree='$deg', Class='$class', Phone_no='$phone', Parent_phone_no='$parent_phone', Address='$address', email='$email' WHERE Regno='$regno'";

if (mysqli_query($conn, $update_query)) {
    echo json_encode(['message' => 'Record updated successfully']);
} else {
    echo json_encode(['message' => 'Error updating record: ' . mysqli_error($conn)]);
}

mysqli_close($conn);
?>
