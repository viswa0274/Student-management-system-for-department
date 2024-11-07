<?php
$conn = new mysqli("localhost", "root", "", "viswa");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['dep_name']) && isset($_POST['category'])) {
    $dep_name = $_POST['dep_name'];
    $category = $_POST['category'];

    // Fetch classes based on department and category
    $classes_query = "SELECT DISTINCT class FROM department WHERE dep_name = ? AND degree = ? ORDER BY ASC";
    $stmt = $conn->prepare($classes_query);
    $stmt->bind_param('ss', $dep_name, $category);
    $stmt->execute();
    $classes_result = $stmt->get_result();
    
    // Fetch subjects based on department and category
    $subjects_query = "SELECT sub_name FROM sub_det WHERE department = ? AND category = ?";
    $stmt2 = $conn->prepare($subjects_query);
    $stmt2->bind_param('ss', $dep_name, $category);
    $stmt2->execute();
    $subjects_result = $stmt2->get_result();
    
    $classes = array();
    while ($row = $classes_result->fetch_assoc()) {
        $classes[] = $row['class'];
    }
    
    $subjects = array();
    while ($row = $subjects_result->fetch_assoc()) {
        $subjects[] = array('sub_name' => $row['sub_name']);
    }

    echo json_encode(array('classes' => $classes, 'subjects' => $subjects));
    exit();
}

$conn->close();
?>
