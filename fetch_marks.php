<?php
$conn = new mysqli("localhost", "root", "", "viswa");

if (isset($_POST['class']) && isset($_POST['subject_code'])) {
    $class = $_POST['class'];
    $subject_code = $_POST['subject_code'];
    
    // Initialize the base query for fetching student data
    $query = "SELECT s.Regno, s.Name, m.int_mark1, m.int_mark2, m.int_mark3, m.ass_mark, m.sem_mark, m.ext_mark, m.result
              FROM students s
              JOIN marks m ON s.Regno = m.regno
              WHERE s.class = '$class' AND m.sub_code = '$subject_code'";

    // Check if result is set and append to the query if it is
    if (isset($_POST['result']) && $_POST['result'] !== '') {
        $result_filter = $_POST['result'];
        $query .= " AND m.result = '$result_filter'";
    }

    // Execute the query for fetching student data
    $result = $conn->query($query);
    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    // Query to count the number of passes
    $pass_query = "SELECT COUNT(*) AS pass_count 
                   FROM students s
                   JOIN marks m ON s.Regno = m.regno
                   WHERE s.class = '$class' AND m.sub_code = '$subject_code' AND m.result = 'Pass'";
    $pass_result = $conn->query($pass_query);
    $pass_count = $pass_result->fetch_assoc()['pass_count'];

    // Query to count the number of fails
    $fail_query = "SELECT COUNT(*) AS fail_count 
                   FROM students s
                   JOIN marks m ON s.Regno = m.regno
                   WHERE s.class = '$class' AND m.sub_code = '$subject_code' AND m.result = 'Reappear'";
    $fail_result = $conn->query($fail_query);
    $fail_count = $fail_result->fetch_assoc()['fail_count'];

    // Return JSON response with students, pass, and fail counts
    echo json_encode([
        'students' => $students,
        'pass_count' => $pass_count,
        'fail_count' => $fail_count
    ]);
}
?>
