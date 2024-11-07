<?php include 'header.html'; 
session_start();
if (isset($_SESSION['id'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <style>
       body{
		background: url('ii.jpg') no-repeat center center fixed;
    background-size: cover;
	 font-family: 'Roboto', sans-serif;
	}
    .form-container {
    width: 800px;
    height: 550px; /* Adjust height automatically based on content */
    margin: 50px auto;
	margin-top:1px;
    padding: 20px;
    background-color: white;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    position: relative; /* To position the home button correctly */
}
        .container {
            display: flex;
            color:black;
			font-family: 'Roboto', sans-serif;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
            margin-left: 20%;
            margin-top: 4%;
            gap: 50px;
        }

        .update-password-container {
           // background-color: wh;
            color:black;
			font-family: 'Roboto', sans-serif;
            padding: 20px;
            border-radius: 10px;
           // box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
        }

        .update-password-container h2 {
            text-align: center;
             color:black;
            margin-bottom: 20px;
        }

        .update-password-container label {
            display: block;
			font-family: 'Roboto', sans-serif;
            margin-bottom: 10px;
             color:black;
            font-weight: bold;
        }

        .update-password-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #333;
            background-color: white;
            color: #333;
            border-radius: 5px;
            font-size: 16px;
        }

        .update-password-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .update-password-container input[type="submit"]:hover {
            background-color: #388e3c;
        }

        .update-password-container input[type="password"]:focus {
            border-color: #4caf50;
            outline: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="update-password-container">
            <h2>Update Password</h2>
            <form action="" method="POST">
                <label for="current-password">Current Password</label>
                <input type="password" id="current-password" name="current_password" required>
                
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new_password" required>
                
                <label for="confirm-password">Confirm New Password</label>
                <input type="password" id="confirm-password" name="confirm_password" required>
                
                <input type="submit" name="sub" value="Update Password">
            </form>
        </div>
    </div>
</body>
</html>
<?php
if (isset($_POST['sub'])) {
	$a = $_SESSION['id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $conn = new mysqli("localhost", "root", "", "viswa");

    if ($conn->connect_error) {
        die("Connection Failed: " . $conn->connect_error);
    }

    $email = $_SESSION['id'];
    
    $stmt = $conn->prepare("SELECT Password FROM admin WHERE emailid = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password = $row['Password'];
        
        if ($current_password != $stored_password) {
            echo "<script>alert('Current Password is incorrect!');</script>";
        } elseif ($new_password != $confirm_password) {
            echo "<script>alert('New Password and Confirm Password do not match!');</script>";
        } else {
            $stmt = $conn->prepare("UPDATE admin SET Password = ? WHERE emailid = ?");
            $stmt->bind_param("ss", $new_password, $email);
            if ($stmt->execute()) {
                echo "<script>alert('Password Updated Successfully!'); window.location.href='adminpanel.php';</script>";
            } else {
                echo "<script>alert('Error updating password!');</script>";
            }
        }
    } else {
        echo "<script>alert('User not found!');</script>";
    }

    $stmt->close();
    $conn->close();
}
} else {
    header('Location: adminlogin.php');
    
}
?>
