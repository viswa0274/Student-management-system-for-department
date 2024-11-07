<?php 
session_start();
if(isset($_SESSION['id'])){

include 'header.html';
 ?>
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
			background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;
            justify-content: center;
            align-items: center;
            background-color: white;
            font-family: Arial, sans-serif;
        }
        .welcome-text {
            font-size: 2em;
			margin-left:20%;
			
            font-weight: bold;
            color: black; 
        }
    </style>
</head>
<body>
</body>
</html><?php }else{
	header('location:adminlogin.php');
}?>
