<?php

$conn = new mysqli("localhost", "root", "", "viswa");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['department']) && isset($_POST['category'])) {
    $department = $_POST['department'];
    $category = $_POST['category'];


    $query = "SELECT DISTINCT class FROM department WHERE dep_name = '$department' AND degree = '$category'";
    $result = $conn->query($query);

  
    if ($result->num_rows > 0) {
        echo '<option value="" disabled selected>Select the class</option>';
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['class'] . "'>" . $row['class'] . "</option>";
        }
    } else {

        echo '<option value="">No Classes Available</option>';
    }


    $conn->close();
} else {

    echo '<option value="">Invalid Request</option>';
}
?>
