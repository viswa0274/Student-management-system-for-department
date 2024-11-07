<?php
session_start();
require 'E:/xampp/htdocs/New folder/phpmailer/vendor/phpmailer/phpmailer/src/Exception.php';
require 'E:/xampp/htdocs/New folder/phpmailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'E:/xampp/htdocs/New folder/phpmailer/vendor/phpmailer/phpmailer/src/SMTP.php';
require 'E:/xampp/htdocs/New folder/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Twilio\Rest\Client;

$conn = new mysqli("localhost", "root", "", "viswa");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$subject = isset($_POST['selected-subject']) ? $_POST['selected-subject'] : '';
$tot_mark = isset($_POST['hidden-total-internal-marks']) ? $_POST['hidden-total-internal-marks'] : '';
$total_days = isset($_POST['hidden-total-days']) ? intval($_POST['hidden-total-days']) : '';
$internal_type = isset($_POST['hidden-internal-type']) ? intval($_POST['hidden-internal-type']) : ''; // Internal 1, 2, or 3
$class = isset($_POST['selected-class']) ? $_POST['selected-class'] : '';

if (empty($subject) || empty($total_days) || empty($internal_type) || empty($class)) {
    echo "<script>alert('Please select a subject, internal type, class, and enter the total number of days.'); window.location.href = 'attendance.php';</script>";
    exit();
}

$subject_query = $conn->prepare("SELECT sub_name FROM sub_det WHERE sub_code = ?");
$subject_query->bind_param("s", $subject);
$subject_query->execute();
$subject_result = $subject_query->get_result()->fetch_assoc();
$subject_query->close();

$subject_name = $subject_result['sub_name'] ?? 'Unknown Subject'; 

$student_query = $conn->prepare("SELECT Regno, Name, email, Parent_phone_no FROM students WHERE class = ?");
$student_query->bind_param("s", $class);
$student_query->execute();
$students = $student_query->get_result()->fetch_all(MYSQLI_ASSOC);
$student_query->close();

if (empty($students)) {
    echo "<script>alert('No students found for the selected class.'); window.location.href = 'attendance.php';</script>";
    exit();
}

$mark_column = '';
$attendance_days_column = '';
$attendance_percentage_column = '';

if ($internal_type == 1) {
    $mark_column = 'int_mark1';
    $tt = 'int_tot1';
    $attendance_days_column = 'days_pre';
    $attendance_percentage_column = 'att_per';
} elseif ($internal_type == 2) {
    $mark_column = 'int_mark2';
    $tt = 'int_tot2';
    $attendance_days_column = 'days_pre1';
    $attendance_percentage_column = 'att_per1';
} elseif ($internal_type == 3) {
    $mark_column = 'int_mark3';
    $tt = 'int_tot3';
    $attendance_days_column = 'days_pre2';
    $attendance_percentage_column = 'att_per2';
}
if ($internal_type == 1) {
    $check_marks_query = $conn->prepare("SELECT int_mark2, int_mark3 FROM marks WHERE sub_code = ? AND Regno = ?");
    
    foreach ($students as $student) {
        $regno = $student['Regno'];
        $check_marks_query->bind_param("ss", $subject, $regno);
        $check_marks_query->execute();
        $marks_result = $check_marks_query->get_result()->fetch_assoc();

        if (!empty($marks_result['int_mark2']) && !empty($marks_result['int_mark3'])) {
            echo "<script>alert('Internal Mark 1 Already uploaded'); window.location.href = 'attendance.php';</script>";
            exit();
        }
    }
}

// Check for internal type 2
if ($internal_type == 2) {
    $check_marks_query = $conn->prepare("SELECT int_mark1, int_mark3 FROM marks WHERE sub_code = ? AND Regno = ?");
    
    foreach ($students as $student) {
        $regno = $student['Regno'];
        $check_marks_query->bind_param("ss", $subject, $regno);
        $check_marks_query->execute();
        $marks_result = $check_marks_query->get_result()->fetch_assoc();

        if (empty($marks_result['int_mark1']) && empty($marks_result['int_mark3'])) {
            echo "<script>alert('Internal mark 1 not uploaded or interal mark 2 already uploaded.'); window.location.href = 'attendance.php';</script>";
            exit();
        }
    }
}
if ($internal_type == 3) {
    // Check if internal marks for types 1 and 2 already exist
    $check_marks_query = $conn->prepare("SELECT int_mark1, int_mark2 FROM marks WHERE sub_code = ? AND Regno = ?");
    
    foreach ($students as $student) {
        $regno = $student['Regno'];
        $check_marks_query->bind_param("ss", $subject, $regno);
        $check_marks_query->execute();
        $marks_result = $check_marks_query->get_result()->fetch_assoc();

        if (empty($marks_result['int_mark1']) && empty($marks_result['int_mark2'])) {
            echo "<script>alert('Internal Mark 1 and Internal Mark 2 must be uploaded before uploading Internal Mark 3 .'); window.location.href = 'attendance.php';</script>";
            exit();
        }
    }
}
foreach ($students as $student) {
    $regno = $student['Regno'];
    $name = $student['Name'];
    $email = $student['email'];
    $parent_mobile = $student['Parent_phone_no'];
    $days_present_key = 'days_present_' . $regno;
    $internal_mark_key = 'internal_mark_' . $regno;

    $days_present = isset($_POST[$days_present_key]) ? intval($_POST[$days_present_key]) : 0;
    $att_per = ($total_days > 0) ? ($days_present / $total_days) * 100 : 0;

    
    $att_per_rounded = round($att_per, 0);

    $internal_mark = isset($_POST[$internal_mark_key]) ? intval($_POST[$internal_mark_key]) : 0;

   
    $converted_mark = ($tot_mark > 0) ? ($internal_mark / $tot_mark) * 15 : 0;
	$converted_mark = round($converted_mark);

    
    $check_mark_query = $conn->prepare("SELECT $mark_column FROM marks WHERE Regno = ? AND sub_code = ?");
    $check_mark_query->bind_param("ss", $regno, $subject);
    $check_mark_query->execute();
    $existing_mark = $check_mark_query->get_result()->fetch_assoc();
    $check_mark_query->close();

    if ($existing_mark && $existing_mark[$mark_column] != 0) {
        echo "<script>alert('Internal Mark $internal_type already uploaded.'); window.location.href = 'attendance.php';</script>";
        exit();
    }

    // Insert converted mark into the database
    $insert_marks_query = $conn->prepare("INSERT INTO marks (Regno, sub_code, $mark_column) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE $mark_column = ?");
    $insert_marks_query->bind_param("ssii", $regno, $subject, $converted_mark, $converted_mark);
    if (!$insert_marks_query->execute()) {
        die("Error executing marks insertion query: " . $insert_marks_query->error);
    }
    $insert_marks_query->close();

    // Insert attendance information
    $insert_attendance_query = $conn->prepare("INSERT INTO attendance (Regno, sub_code, $attendance_days_column, $attendance_percentage_column) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE $attendance_days_column = ?, $attendance_percentage_column = ?");
    $insert_attendance_query->bind_param("ssddid", $regno, $subject, $days_present, $att_per_rounded, $days_present, $att_per_rounded);
    if ($insert_attendance_query->execute()) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = ''; 
            $mail->Password = ''; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('', 'Dept of Computer Science');
            $mail->addAddress($email, $name); 

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Internal Marks and Attendance Notification for $subject_name"; // Use subject name
            $mail->Body = "
    <html>
    <head>
        <style>
            .content { font-family: Arial, sans-serif; color: #333; }
            .highlight { font-weight: bold; color: #007BFF; }
            .greeting { font-size: 16px; }
            .details { font-size: 14px; margin-top: 10px; }
        </style>
    </head>
    <body>
        <div class='content'>
            <p class='greeting'>Dear <strong>$name</strong> (Regno: <strong>$regno</strong>),</p>
            <p class='details'>
                Your <span class='highlight'>Internal $internal_type mark</span> for <span class='highlight'>$subject_name</span> is: <strong>$converted_mark</strong> (out of 15).<br>
                Total number of Working days: <strong>$total_days</strong>.<br>
                No. of days present: <strong>$days_present</strong>.<br>
                Your attendance percentage is: <strong>$att_per_rounded%</strong>.
            </p>
            <p>Thank you.</p>
        </div>
    </body>
    </html>";

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo};";
        }

        $sid = '';
        $token = '';
        $twilio_number = ''; // Your Twilio phone number

        $client = new Client($sid, $token);

        try {
            $message_body = "Dear Parent,\n\n"
                . "Student Name: $name\n"
                . "Regno: $regno\n"
                . "Subject: $subject_name\n"
                . "Internal $internal_type Mark: $converted_mark (out of 15)\n"
                . "Attendance Percentage: $att_per_rounded%\n\n"
                . "Best regards,\n";

            $message = $client->messages->create(
                $parent_mobile, 
                [
                    'from' => $twilio_number,
                    'body' => $message_body
                ]
            );
        } catch (Exception $e) {
            echo "SMS could not be sent. Error: " . $e->getMessage();
        }

    } else {
        die("Error executing attendance insertion query: " . $insert_attendance_query->error);
    }
}

$conn->close();
echo "<script>alert('Marks and attendance details uploaded successfully.'); window.location.href = 'attendance.php';</script>";
?>
