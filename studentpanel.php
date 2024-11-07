<?php session_start();
if(isset($_SESSION['id'])){
include 'studentheader.html'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Welcome Page</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
			background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;
            background-color: white;
            font-family: Arial, sans-serif;
        }
        .welcome-text {
            font-size: 2em;
			margin-left:1%;
			
            font-weight: bold;
            color: black; 
        }
    </style>
</head>
<body>
    <div class="welcome-text">Welcome </div>
</body>
</html>
<?php
}
else{
	header('location:stafflogin.php');
}?>