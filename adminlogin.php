<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
            background: url('ii.jpg') no-repeat center center fixed; 
            background-size: cover; 
        }

        .container {
            background-color: #eee; /* Light, semi-transparent white */
            padding: 40px; /* Increased padding for better spacing */
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            width: 500px;
            height: 400px; /* Increased height */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #003366; /* Dark blue for contrast */
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
			font-weight : bold;
            color:black;; /* Dark blue for consistency */
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9; /* Light background for inputs */
            color: #333;
            box-sizing: border-box;
            height: 40px;
        }

        button {
            width: 30%;
            padding: 10px;
            background-color: #4CAF50; /* Green button */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px; /* Added margin for spacing */
        }

        button:hover {
            background-color: darkgreen;
        }

        .error-message {
            color: red;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" autocomplete="off" required>
            </div>
            <button type="submit" name="sub">Login</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();
if (isset($_POST['sub'])) {
    $n = $_POST['username'];
    $p = $_POST['password'];
    $conn = mysqli_connect("localhost", "root", "", "viswa");
    if (!$conn) {
        die("Connection Failed:" . mysqli_connect_error());
    }
    $s = "SELECT * from admin where emailid='$n' and Password='$p';";
    $r = mysqli_query($conn, $s);
    if (mysqli_num_rows($r) > 0) {
        while ($row = mysqli_fetch_array($r)) {
            if ($row['emailid'] == $n && $row['Password'] == $p) {
                $_SESSION['id'] = $row['emailid'];
                header("Location:adminpanel.php");
            }
        }
    } else {
        ?><script> alert("Login Failed!! Please try again.");</script><?php
    }
}
?>
