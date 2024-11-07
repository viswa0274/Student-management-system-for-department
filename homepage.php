<?php
session_start();

  

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Backlog Management System</title>
    <link rel="stylesheet" href="homepage.css">
    <style>
        body {
            background-color: #f4f4f4; 
            color: #002C54; 
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        header {
            background-color: #002C54;
            color: white;
            padding: 20px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header h1 {
            margin: 0;
        }
	
        nav {
			
            margin-top: 10px;
        }

        nav a {
            color:  #408EC6;
			font-weight: bold;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        nav a:hover {
            background-color: #001A36;
            color: #f4f4f4;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .intro {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Georgia', serif; /* Elegant serif font */
            font-size: 1.2em; /* Slightly larger text */
            line-height: 1.6; /* Improved readability */
            color: #002C54; /* Dark blue text color */
        }

        .intro h2 {
            margin-bottom: 10px;
            color: #002C54; /* Dark blue text color */
        }

        .features {
            margin: 20px 0;
            text-align: center;
        }

        .features ul {
            list-style-type: none;
            padding: 0;
        }

        .features li {
            margin: 10px 0;
            color: #002C54; /* Dark blue text color */
        }

        footer {
            background-color: #e2e2e2; /* Light grey background */
            color: #002C54; /* Dark blue text color */
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Student Backlog Management System</h1>
        
    </header>
    <div class="container">
	<nav>
            <a href="adminlogin.php">Admin Login</a>
            <a href="stafflogin.php">Staff Login</a>
            <a href="studentlogin.php">Student Login</a>
            
        </nav>
        <section class="intro">
           
			
			<br>
            <p>This system is designed to help students and staff manage academic backlogs efficiently. Whether you are an admin, staff member, or student, this system offers various features to streamline the backlog management process.</p>
        </section>
        
        <section id="features" class="features">
            
        </section>
    </div>
    <footer>
        <p>&copy; 2024 Student Backlog Management System | All Rights Reserved</p>
    </footer>
</body>
</html>
