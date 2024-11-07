<?php
session_start();
$conn = new mysqli("localhost", "root", "", "viswa");

// Check for POST request method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class = $_POST['class'];

    // Prepare a statement to check if marks already exist
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM marks WHERE Regno = ?");
    // Prepare an update statement for assignment and seminar marks
    $update_stmt = $conn->prepare("UPDATE marks SET ass_mark = ?, sem_mark = ? WHERE Regno = ?");

    // Array to store registration numbers of students with already uploaded marks
    $uploaded_regnos = [];

    // Loop through each student mark entry
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'ass_mark_') === 0) { // Assignment marks
            $regno = str_replace('ass_mark_', '', $key);
            $ass_mark = intval($value);

            // Check for the corresponding seminar mark
            $sem_key = 'sem_mark_' . $regno;
            $sem_mark = isset($_POST[$sem_key]) ? intval($_POST[$sem_key]) : 0; // Default to 0 if not set

            // Check if the marks already exist
            $check_stmt->bind_param("s", $regno);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();

            if ($count > 0) {
                // If marks exist, add the regno to the uploaded_regnos array
                $uploaded_regnos[] = $regno;
            } else {
                // Execute the update statement
                $update_stmt->bind_param("iis", $ass_mark, $sem_mark, $regno);
                $update_stmt->execute();
            }
        }
    }

    // Prepare the alert message
    if (!empty($uploaded_regnos)) {
        // Convert the array of registration numbers to a comma-separated string
        $regnos_str = implode(", ", $uploaded_regnos);
        echo "<script>alert('Marks already uploaded');</script>";
    }

    // Redirect or display a success message if no uploaded marks
    if (empty($uploaded_regnos)) {
        echo "<script>alert('Marks uploaded successfully!'); window.location.href='upload_marks.php';</script>";
    } else {
        // Optionally, you can redirect to the same page if there are uploaded marks
        echo "<script>window.location.href='upload_marks.php';</script>";
    }
} 

// Close the statements
$check_stmt->close();
$update_stmt->close();
$conn->close();
?>
