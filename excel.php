<?php 
session_start();
include 'header.html';

if(isset($_SESSION['id'])){

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head> 
	<meta charset="utf-8">
	<title>Import Excel To MySQL</title>
	<style>
		/* Body styling */
		body {
			font-family: Arial, sans-serif;
			background-color: #f4f4f4;
			margin: 0;
			padding: 0;
			background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
		}

		/* Form container */
		form {
			background-color: #fff;
			padding: 40px;
			border-radius: 8px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
			max-width: 500px;
			width: 100%;
			text-align: center;
		}

		/* Input file field styling */
		input[type="file"] {
			margin-bottom: 20px;
			padding: 10px;
			font-size: 16px;
			width: 100%;
			border: 1px solid #ccc;
			border-radius: 4px;
		}

		/* Button styling */
		button {
			background-color: #4CAF50;
			color: white;
			padding: 10px 15px;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			font-size: 16px;
			width: 100%;
		}

		button:hover {
			background-color: #45a049;
		}

		/* For smaller screens */
		@media (max-width: 600px) {
			form {
				width: 90%;
			}
		}
	</style>
</head>
<body>
	<form class="" action="" method="post" enctype="multipart/form-data">
		<h2>Import Excel File</h2>
		<input type="file" name="excel" required>
		<button type="submit" name="import">Import</button>
	</form>
</body>
</html>

<?php
$conn = mysqli_connect("localhost", "root", "", "viswa");

if(isset($_POST["import"])){
	$fileName = $_FILES["excel"]["name"];
	$fileExtension = explode('.', $fileName);
    $fileExtension = strtolower(end($fileExtension));

	// Allowed file types
	$allowedExtensions = ['xls', 'xlsx', 'csv'];

	if (!in_array($fileExtension, $allowedExtensions)) {
		echo "<script>alert('Invalid file format. Please upload an Excel file.');</script>";
	} else {
		$newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;
		$targetDirectory = "uploads/" . $newFileName;
		move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

		error_reporting(0);
		ini_set('display_errors', 0);

		require 'excelReader/excel_reader2.php';
		require 'excelReader/SpreadsheetReader.php';

		$reader = new SpreadsheetReader($targetDirectory);
		$firstRow = true;
		$validData = true;
		$rows = [];

		// Validate all rows except the first row (heading) for empty fields
		foreach($reader as $key => $row){
			if ($firstRow) {
				$firstRow = false;
				continue; // Skip the first row (header)
			}

			// Check if any field in the row is empty
			if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[4]) || 
				empty($row[5]) || empty($row[6]) || empty($row[7]) || empty($row[8]) || empty($row[9]) || 
				empty($row[10])) {
				$validData = false;
				echo "<script>alert('Upload Failed: One or more fields in the Excel file are empty.');</script>";
				break;
			} else {
				$rows[] = $row; // Store valid rows for later insertion
			}
		}

		// If all data is valid, perform insertions for each row except the heading
		if ($validData && !empty($rows)) {
			foreach($rows as $row){
				$reg = $row[0];
				$name = $row[1];
				$dob = $row[2];
				$gender = $row[3];
				$dep = $row[4];
				$deg = $row[5];
				$class = $row[6];
				$phone = $row[7];
				$email = $row[8];
				$par = '+91' . $row[9];
				$address = $row[10];

				$a = mysqli_query($conn, "INSERT INTO students VALUES('$reg', '$name', '$dob', '$gender','$dep','$deg','$class','$phone','$email','$par','$address')");
			}

			if($a){
				echo "<script>alert('Successfully Uploaded');</script>";
			} else {
				echo "<script>alert('Upload Failed: Database error.');</script>";
			}
		}
	}
}} else {
	header('location:adminlogin.php');
}
?>
