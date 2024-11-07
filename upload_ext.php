<?php
session_start();
$conn = new mysqli("localhost", "root", "", "viswa");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve selected class and subject
    $selectedClass = $_POST['selected_class'];
    $selectedSubject = $_POST['selected_subject'];

    if (empty($selectedClass) || empty($selectedSubject)) {
        echo "<script>alert('Class and Subject are required.'); window.history.back();</script>";
        exit;
    }

    // Loop through all submitted external marks
    foreach ($_POST as $key => $value) {
        // Check if the key is for an external mark (starts with 'external_mark_')
        if (strpos($key, 'external_mark_') === 0) {
            // Extract the registration number from the key
            $regno = str_replace('external_mark_', '', $key);
            $ext_mark = intval($value); // Convert external mark to integer

            // Fetch int_mark1, int_mark2, int_mark3, sem_mark, and ass_mark from the marks table
            $marks_query = "SELECT int_mark1, int_mark2, int_mark3, sem_mark, ass_mark, ext_mark 
                            FROM marks 
                            WHERE Regno = '$regno' AND sub_code = '$selectedSubject'";
            $marks_result = $conn->query($marks_query);

            if ($marks_result->num_rows > 0) {
                $marks_row = $marks_result->fetch_assoc();
                
               if ((empty($marks_row['int_mark1']) || empty($marks_row['int_mark2']) || empty($marks_row['int_mark3']) || empty($marks_row['ass_mark']) || empty($marks_row['sem_mark']))) {
                    echo "<script>alert('Please Upload Internal,Assignment and Seminar Marks first.'); window.history.back();</script>";
                    exit;
                }
                if (!empty($marks_row['ext_mark'])) {
                    echo "<script>alert('External marks have already been uploaded.'); window.history.back();</script>";
                    exit;
                }
                
                // Retrieve internal marks, sem mark, and assignment mark
                $int_mark1 = intval($marks_row['int_mark1']);
                $int_mark2 = intval($marks_row['int_mark2']);
                $int_mark3 = intval($marks_row['int_mark3']);
                $sem_mark = intval($marks_row['sem_mark']);
                $ass_mark = intval($marks_row['ass_mark']);
                
                // Get the best two internal marks
                $internal_marks = [$int_mark1, $int_mark2, $int_mark3];
                rsort($internal_marks); // Sort in descending order
                $best_two_internals = array_slice($internal_marks, 0, 2); // Take the best two

                // Calculate the average of the best two internal marks
                $avg_internal = array_sum($best_two_internals) / 2;

                // Calculate total marks out of 25 (internal marks + sem_mark + ass_mark)
                $total_out_of_25 = $avg_internal + $sem_mark + $ass_mark;

                // Calculate the final total by adding external marks
                $final_total = $total_out_of_25; // This is out of 25 marks
                $total_with_ext = $final_total + $ext_mark; // Adding external marks
                $total_with_ext = round($total_with_ext);
                
                // Determine if the student passes or needs to reappear
                $result = ($total_with_ext >= 50 && $ext_mark >= 38) ? 'Pass' : 'Reappear';

                // Update the marks table with the final total and result
                $update_query = "UPDATE marks 
                                 SET ext_mark = $ext_mark, total = $total_with_ext, result = '$result' 
                                 WHERE Regno = '$regno' AND sub_code = '$selectedSubject'";

                if (!$conn->query($update_query)) {
                    echo "<script>alert('Error updating marks: " . $conn->error . "'); window.history.back();</script>";
                    exit;
                }
            } else {
                echo "<script>alert('First Upload Internal Marks.'); window.history.back();</script>";
                exit;
            }
        }
    }

    // If no errors, show success message and redirect
    echo "<script>
            alert('External marks and total marks successfully updated!');
            window.location.href = 'uploadexternalmarks.php';
          </script>";
}

$conn->close();
?>
