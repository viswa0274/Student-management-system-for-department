<?php
$conn = new mysqli("localhost", "root", "", "viswa");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$response = array();

if (isset($_POST['staffid'])) {
    $staffid = $_POST['staffid'];
    $staff_query = "SELECT Name, department, category FROM staff WHERE staffid = ?";
    $stmt = $conn->prepare($staff_query);
    $stmt->bind_param("s", $staffid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $staffDetails = $result->fetch_assoc();
        echo json_encode($staffDetails);
    } else {
        echo json_encode([]);
    }
    $stmt->close();
}

if (isset($_POST['dep_name']) && isset($_POST['category'])) {
    $department = $_POST['dep_name'];
    $category = $_POST['category'];
   
    $class_query = "SELECT class FROM department WHERE dep_name = ? AND degree = ?";
    $stmt = $conn->prepare($class_query);
    $stmt->bind_param("ss", $department, $category);
    $stmt->execute();
    $result = $stmt->get_result();
    $classes = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($classes,$row['class']);
            
        }
    } else {
       $classes=["No class Available"];
    }
  
    
    $response['classes'] = $classes;

 
    $subject_query = "SELECT DISTINCT sub_name FROM sub_det WHERE department = ?";
    $stmt = $conn->prepare($subject_query);
    $stmt->bind_param("s", $department);
    $stmt->execute();
    $result = $stmt->get_result();

    $subjects = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            array_push($subjects,$row['sub_name']);
            
        }
    } else {
       $subjects=["bro"];
    }
    
    $response['subjects'] = $subjects;

    echo json_encode($response);
    $stmt->close();
}

if (isset($_POST['sub_name']) && isset($_POST['department'])) {
    $sub_name = $_POST['sub_name'];
    $department = $_POST['department'];

    $subject_query = "SELECT sub_code, sub_type FROM sub_det WHERE sub_name = ? AND department = ?";
    $stmt = $conn->prepare($subject_query);
    $stmt->bind_param("ss", $sub_name, $department);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $subjectDetails = $result->fetch_assoc();
        echo json_encode($subjectDetails);
    } else {
        echo json_encode([]);
    }
    $stmt->close();
}

if (isset($_POST['fetch_staff_by_department'])) {
    $department = $_POST['department'];
    $cate = $_POST['category'];
    $staff_query = "SELECT DISTINCT staffid FROM staff WHERE department = '$department' AND category ='$cate' ";
    $staff_result = $conn->query($staff_query);

    $staff_list = array();
    if ($staff_result->num_rows > 0) {
        while ($row = $staff_result->fetch_assoc()) {
            $staff_list[] = $row['staffid'];
        }
    }

    echo json_encode($staff_list);
    exit();
}


if (isset($_POST['fetch_staff_details'])) {
    $staffid = $_POST['staffid'];
    $staff_query = "SELECT Name FROM staff WHERE staffid = '$staffid'";
    $staff_result = $conn->query($staff_query);

    $staff_details = array();
    if ($staff_result->num_rows > 0) {
        $staff_details = $staff_result->fetch_assoc();
    }

    echo json_encode($staff_details);
    exit();
}

$conn->close();
?>