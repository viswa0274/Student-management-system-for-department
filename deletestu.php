<?php

$conn = new mysqli("localhost", "root", "", "viswa");

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}


if (isset($_POST['delete'])) {
    $regno = $_POST['id']; // Get the registration number of the student to delete

    
    $sql = "DELETE FROM students WHERE Regno = ?";

    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $regno);

   
    if ($stmt->execute()) {
        // If the query was successful, redirect to the main page with a success message
        echo "<script>
            alert('Student record deleted successfully.');
            window.location.href = 'studetails.php'; 
        </script>";
    } else {
       
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
