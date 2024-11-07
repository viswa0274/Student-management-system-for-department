<?php include 'header.html'; 
session_start();
if(isset($_SESSION['id'])){
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
           // background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            display: flex;
			background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 50px;
			margin-left:40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
			width : 50%;
            max-width: 450px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e0e0e0;
        }
        .container:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }
        h2 {
            margin-bottom: 25px;
            color: #333;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .option-btn {
            display: inline-block;
            width: 80%;
            padding: 12px;
            margin: 15px 0;
            background-color: #CC313D;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            font-size: 18px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .option-btn:hover {
            background-color: #45a049;
        }
        .or {
            margin: 20px 0;
            font-weight: bold;
            color: #666;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Choose an Option</h2>
        <a href="excel.php" class="option-btn">Upload Details Via Excel File</a>
        <div class="or">OR</div>
        <a href="students.php" class="option-btn">Add Details Manually</a>
    </div>
</body>
</html>
<?php 
} else {
    header('location:adminlogin.php');
}
?>
