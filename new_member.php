<!DOCTYPE html>
<html>
<head>
    <title>Milk Society Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
           // background-color: white;
            border-radius: 8px;
            //box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            width: 100%;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        input[type="file"] {
            padding: 5px;
        }
        input[type="submit"] {
            grid-column: span 3;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        @media (max-width: 768px) {
            .container {
                width: 90%;
            }
            form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Milk Society Registration Form</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>
            </div>
            <div class="form-group">
                <label for="place">Place:</label>
                <input type="text" id="place" name="place" required>
            </div>
            <div class="form-group">
                <label for="taluk">Taluk:</label>
                <input type="text" id="taluk" name="taluk" required>
            </div>
            <div class="form-group">
                <label for="district">District:</label>
                <input type="text" id="district" name="district" required>
            </div>
            <div class="form-group">
                <label for="pincode">Pincode:</label>
                <input type="text" id="pincode" name="pincode" required>
            </div>
            <div class="form-group">
                <label for="occupation">Occupation:</label>
                <input type="text" id="occupation" name="occupation" required>
            </div>
            <div class="form-group">
                <label for="photo">Upload Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="no_of_cows">Number of Cows:</label>
                <input type="number" id="no_of_cows" name="no_of_cows" required>
            </div>
            <div class="form-group">
                <label for="annual_income">Annual Income:</label>
                <input type="number" id="annual_income" name="annual_income" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>



<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vishwaa";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data with validation
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $age = isset($_POST['age']) ? $_POST['age'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $place = isset($_POST['place']) ? $_POST['place'] : '';
    $taluk = isset($_POST['taluk']) ? $_POST['taluk'] : '';
    $district = isset($_POST['district']) ? $_POST['district'] : '';
    $pincode = isset($_POST['pincode']) ? $_POST['pincode'] : '';
    $occupation = isset($_POST['occupation']) ? $_POST['occupation'] : '';
    $no_of_cows = isset($_POST['no_of_cows']) ? $_POST['no_of_cows'] : '';
    $annual_income = isset($_POST['annual_income']) ? $_POST['annual_income'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : ''; // Consider hashing for security
    $status = "pending";

    // Handle the photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = $_FILES['photo']['name'];
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_folder = "uploads/" . basename($photo);
        
        // Attempt to move the uploaded file
        if (move_uploaded_file($photo_tmp_name, $photo_folder)) {
            echo "Photo uploaded successfully.<br>";
        } else {
            echo "Error: Failed to upload the photo.<br>";
            $photo = ''; // Clear photo name if upload fails
        }
    } else {
        $photo = ''; // No photo uploaded or error occurred
    }

    // Check if phone number already exists
    if (!empty($phone)) {
        $sql = "SELECT * FROM member WHERE phone = '$phone'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Phone number exists, show error
            echo "Error: The phone number already exists!";
        } else {
            // Phone number doesn't exist, proceed with registration
            $sql = "INSERT INTO member (name, age, email, phone, address, place, taluk, district, pincode, occupation, no_of_cows, annual_income, username, password, photo, status)
                    VALUES ('$name', '$age', '$email', '$phone', '$address', '$place', '$taluk', '$district', '$pincode', '$occupation', '$no_of_cows', '$annual_income', '$username', '$password', '$photo', '$status')";

            if ($conn->query($sql) === TRUE) {
                echo "Registration successful!";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "Error: Phone number is required!";
    }
}

$conn->close();
?>
