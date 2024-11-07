<?php
$conn = new mysqli("localhost", "root", "", "viswa");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['allocate'])) {
    $staffid = $_POST['staffid'];
    $class = $_POST['class'];
    $sub_id = $_POST['sub_id'];
    $semester = $_POST['semester'];

    
    $s = "SELECT * FROM subject WHERE staffid='$staffid' AND sub_code='$sub_id' AND sem='$semester' AND class='$class'";
    $re = mysqli_query($conn, $s);

    if (mysqli_num_rows($re) > 0) {
        
        echo "<script>
                if (confirm('Subject already allocated to this staff. Do you want to update?')) {
                    window.location.href='allocatesubject.php?update=1&staffid=$staffid&class=$class&sub_id=$sub_id&semester=$semester';
                } else {
                    window.location.href='subjects.php';
                }
              </script>";
    } else {
        
        $allocation_query = "INSERT INTO subject (staffid, class, sub_code, sem) 
                             VALUES ('$staffid', '$class', '$sub_id', '$semester')";

        if ($conn->query($allocation_query) === TRUE) {
            echo "<script>alert('Subject allocated successfully!'); window.location.href='subjects.php';</script>";
        } else {
            echo "Error: " . $allocation_query . "<br>" . $conn->error;
        }
    }
}


if (isset($_GET['update']) && $_GET['update'] == 1) {
    $staffid = $_GET['staffid'];
    $class = $_GET['class'];
    $sub_id = $_GET['sub_id'];
    $semester = $_GET['semester'];

    $update_query = "UPDATE subject 
                     SET class='$class' 
                     WHERE staffid='$staffid' AND sub_code='$sub_id' AND sem='$semester'";

    if ($conn->query($update_query) === TRUE) {
        echo "<script>alert('Subject allocation updated successfully!'); window.location.href='subjects.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
