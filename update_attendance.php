<?php

session_start();
ob_start();  

$conn = new mysqli("localhost", "root", "", "viswa");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $regno = $_POST['regno'];
    $days_present = $_POST['days_present'];
    $ss = $_SESSION['ss'];  // Subject code from session


    if (!empty($regno) && !empty($days_present) && !empty($ss)) {
        
        $query = "SELECT days_pre, att_per FROM attendance WHERE Regno = ? AND sub_code = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $regno, $ss);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $attendanceData = $result->fetch_assoc();
            $prev_days_present = $attendanceData['days_pre'];
            $prev_att_per = $attendanceData['att_per'];

            
            if ($prev_att_per > 0) {
                $total_days = $prev_days_present / ($prev_att_per / 100);

                
                $new_attendance_percentage = ($days_present / $total_days) * 100;

                
                $update_query = "UPDATE attendance SET days_pre = ?, att_per = ? 
                                 WHERE Regno = ? AND sub_code = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("idis", $days_present, $new_attendance_percentage, $regno, $ss);

                if ($stmt->execute()) {
                    
                    echo "<script>alert('Attendance successfully updated.'); window.location.href = 'viewattendance.php';</script>";
                } else {
                   
                    echo "<script>alert('Failed to update attendance.'); window.location.href = 'updateattendance.php';</script>";
                }
            } else {
                echo "<script>alert('Cannot calculate total days due to zero previous attendance percentage.'); window.location.href = 'updateattendance.php';</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('No attendance record found for this student and subject.'); window.location.href = 'updateattendance.php';</script>";
        }
    } else {
        
        echo "<script>alert('Missing required fields.'); window.location.href = 'updateattendance.php';</script>";
    }
} else {
  
    echo "<script>alert('Invalid request method.'); window.location.href = 'updateattendance.php';</script>";
}

$conn->close();
ob_end_flush();  
?>
