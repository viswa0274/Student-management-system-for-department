<?php
session_start();
$conn = new mysqli("localhost", "root", "", "viswa");

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected class and subject from hidden fields
$selected_class = $_POST['selected_class'];
$selected_subject = $_POST['selected_subject'];

// Fetch all students for the selected class
$students_query = "SELECT Regno FROM students WHERE class='$selected_class'";
$students_result = $conn->query($students_query);

// Initialize variables to track errors and existing marks

$existing_marks = [];
$successful_uploads = 0;

// Process marks for each student
if ($students_result->num_rows > 0) {
    while ($row = $students_result->fetch_assoc()) {
        $regno = $row['Regno'];
        // Get the marks from the POST request
        $assignment_marks = isset($_POST["ass_mark_$regno"]) ? (int)$_POST["ass_mark_$regno"] : 0;
        $seminar_marks = isset($_POST["sem_mark_$regno"]) ? (int)$_POST["sem_mark_$regno"] : 0;

        // Check if the internal marks exist in the database
        $check_query = "SELECT * FROM marks WHERE Regno = '$regno' AND sub_code = '$selected_subject'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            $row = $check_result->fetch_assoc();
			$existing_int_mark1 = (int)$row['int_mark1'];
            $existing_int_mark2 = (int)$row['int_mark2'];
			$existing_int_mark3 = (int)$row['int_mark3'];
            
            $existing_ass_mark = (int)$row['ass_mark'];
            $existing_sem_mark = (int)$row['sem_mark'];

            if ($existing_ass_mark > 0 || $existing_sem_mark > 0) {
                // Both seminar and assignment marks already uploaded
                $existing_marks[] = $regno;
            } else {
                // Update the existing row with seminar and assignment marks
                $update_query = "UPDATE marks 
                                 SET ass_mark = $assignment_marks, sem_mark = $seminar_marks 
                                 WHERE Regno = '$regno' 
                                 AND sub_code = '$selected_subject'";
                
                if ($conn->query($update_query)) {
                    $successful_uploads++;
                } else {
                    $upload_errors[] = "Error updating marks for Regno: $regno - " . $conn->error;
                }
            }
        }
    }
}
$upload_errors[] = "Uploaded successfully";
// Prepare messages
if (!empty($existing_marks)) {
    $existing_marks_message = "Assignment and Seminar marks already uploaded ";
} else {
    $existing_marks_message = "";
}

if (!empty($upload_errors)) {
    $upload_errors_message = "" . implode(", ", $upload_errors);
} else {
    $upload_errors_message = "";
}

if ($successful_uploads > 0 && empty($existing_marks) && empty($upload_errors)) {
    echo "<script>alert('Marks uploaded successfully for all students!'); window.location.href = 'uploadassmarks.php';</script>";
} else {
    $final_message = $existing_marks_message . "\\n" . $upload_errors_message;
    echo "<script>alert('$final_message'); window.location.href = 'uploadassmarks.php';</script>";
}

// Close the database connection
$conn->close();
// Close the database connection
$conn->close();
?>
