<?php session_start();

if(isset($_SESSION['id'])){
	?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            background-color: white;
            color: #333333;
            margin: 0;
            padding: 0;
			background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Arial', sans-serif;
        }

        main {
            background-color: white;
            padding: 20px;
			background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;
            margin-top: 5%;
            color: #333333;
        }

        .table-container {
            width: 98%;
            margin: 0 auto;
			
            border-collapse: collapse;
            height: 400px;
            overflow: hidden;
            overflow-y: auto;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 1000px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #990011;
            color: #ffffff;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        td {
            background-color: #f9f9f9;
            color: #333333;
        }

        tr:nth-child(even) td {
            background-color: #f1f1f1;
        }

        tr:hover td {
            background-color: #f4faff;
            transition: background-color 0.3s ease;
        }

        button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            color: #ffffff;
        }

        input.editable {
            width: 100%; 
            box-sizing: border-box; 
            padding: 8px;
            font-size: 14px;
        }

        .edit-button {
            background-color: #007bff;
        }

        .edit-button:hover {
            background-color: #0069d9;
        }

        .update-button {
            background-color: #28a745;
        }

        .update-button:hover {
            background-color: #218838;
        }

        .delete-button {
            background-color: #dc3545;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        .filter-container {
            display: flex;
            color: black;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            padding-left: 10px;
        }

        .filter-container form {
            margin-left: 20px;
        }

        .filter-container select {
            padding: 8px 12px;
            font-size: 16px;
            border: 1px solid #555555;
            border-radius: 5px;
            background-color: #ffffff;
            color: #333333;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .filter-container select:hover {
            background-color: #f1f1f1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nn {
            width: 200px;
        }

        .dd {
            width: 130px;
        }

        .address-field {
            width: 300px;
        }

        .editable {
            background-color: #e8f0fe;
        }

        
        .back-button-container {
            text-align: center;
            margin-top: 20px;
        }

        .back-button-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back-button-container button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function enableEditing(rowId) {
            let row = document.getElementById('row-' + rowId);
            let cells = row.querySelectorAll('td');

            cells.forEach(function(cell, index) {
                if (index > 0 && index <= 10) { 
                    let value = cell.innerText;
                    cell.innerHTML = '<input class="editable" type="text" value="' + value + '">';
                }
            });

          
            row.querySelector('.edit-button').style.display = 'none';
            row.querySelector('.update-button').style.display = 'inline-block';
        }

        function submitUpdate(rowId) {
            let row = document.getElementById('row-' + rowId);
            let cells = row.querySelectorAll('td');
            let updatedValues = {};

            updatedValues["Regno"] = cells[0].innerText; 
            updatedValues["Name"] = cells[1].querySelector('input').value;
            updatedValues["DOB"] = cells[2].querySelector('input').value;
            updatedValues["gender"] = cells[3].querySelector('input').value;
			updatedValues["department"] = cells[4].querySelector('input').value;
			updatedValues["degree"] = cells[5].querySelector('input').value;
            updatedValues["Class"] = cells[6].querySelector('input').value;
            updatedValues["Phone_no"] = cells[7].querySelector('input').value;
            updatedValues["Parent_phone_no"] = cells[8].querySelector('input').value;
            updatedValues["Address"] = cells[9].querySelector('input').value;
            updatedValues["email"] = cells[10].querySelector('input').value;

            fetch('updatestu.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(updatedValues),
            })
            .then(response => response.json())
            .then(data => {
                alert('Data updated successfully!');
                location.reload(); 
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }

        function goBack() {
            window.history.back();
        }
		function gohome() {
            window.location = 'adminpanel.php';
        }
    </script>
</head>
<body>
    <main>
        <div class="filter-container">
            <h2>Student Details</h2>
            <form action="" method="POST">
                <label for="department-filter">Select Department:</label>
                <select name="department" id="department-filter" onchange="this.form.submit()">
                    <option value="" <?php echo (!isset($_POST['department']) || $_POST['department'] == '') ? 'selected' : ''; ?>>All</option>
                    <?php
                        $conn = new mysqli("localhost", "root", "", "viswa");

                        if (!$conn) {
                            die("Connection Failed: " . mysqli_connect_error());
                        }

                        $dep_query = "SELECT DISTINCT dep_name FROM department";
                        $dep_result = mysqli_query($conn, $dep_query);

                        while ($row = mysqli_fetch_assoc($dep_result)) {
                            $dep_name = $row['dep_name'];
                            echo "<option value=\"$dep_name\" " . (isset($_POST['department']) && $_POST['department'] == $dep_name ? 'selected' : '') . ">$dep_name</option>";
                        }

                        mysqli_close($conn);
                    ?>
                </select>

                <label for="class-filter">Select Class:</label>
                <select name="class" id="class-filter" onchange="this.form.submit()">
                    <option value="" <?php echo (!isset($_POST['class']) || $_POST['class'] == '') ? 'selected' : ''; ?>>All</option>
                    <?php
                        if (isset($_POST['department']) && $_POST['department'] != '') {
                            $selected_department = $_POST['department'];

                            $conn = new mysqli("localhost", "root", "", "viswa");

                            if (!$conn) {
                                die("Connection Failed: " . mysqli_connect_error());
                            }

                            $class_query = "SELECT DISTINCT class FROM department WHERE dep_name = '$selected_department'";
                            $class_result = mysqli_query($conn, $class_query);

                            while ($row = mysqli_fetch_assoc($class_result)) {
                                $class_name = $row['class'];
                                echo "<option value=\"$class_name\" " . (isset($_POST['class']) && $_POST['class'] == $class_name ? 'selected' : '') . ">$class_name</option>";
                            }

                            mysqli_close($conn);
                        }
                    ?>
                </select>
            </form>
        </div>

        <div class="table-container">
            <?php
                $selected_department = isset($_POST['department']) ? $_POST['department'] : '';
                $selected_class = isset($_POST['class']) ? $_POST['class'] : '';

                $conn = new mysqli("localhost", "root", "", "viswa");

                if (!$conn) {
                    die("Connection Failed: " . mysqli_connect_error());
                }

                // Modify the query to filter based on both department and class
                $query = "SELECT * FROM students WHERE 1=1";
                if ($selected_department != '') {
                    $query .= " AND department = '$selected_department'";
                }
                if ($selected_class != '') {
                    $query .= " AND Class = '$selected_class'";
                }
                $query .= " ORDER BY Class, Regno";
                
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    echo "<table>
                            <tr>
                                <th>Reg No</th>
                                <th>Name</th>
                                <th>DOB</th>
                                <th>Gender</th>
								<th>Dep</th>
								<th>Degree</th>
                                <th>Class</th>
                                <th>Phone Number</th>
                                <th>Parent's Phone_no</th>
                                <th class='address-field'>Address</th>
                                <th>Email</th>
                                <th>Edit</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>";

                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr id='row-" . $row["Regno"] . "'>
                                <td>" . $row["Regno"] . "</td>
                                <td class='nn'>" . $row["Name"] . "</td>
                                <td class='dd'>" . $row["DOB"] . "</td>
                                <td>" . $row["gender"] . "</td>
								<td class='dd'>" . $row["department"] . "</td>
								<td class='dd'>" . $row["degree"] . "</td>
                                <td class='dd'>" . $row["Class"] . "</td>
                                <td>" . $row["Phone_no"] . "</td>
                                <td>" . $row["Parent_phone_no"] . "</td>
                                <td class='address-field'>" . $row["Address"] . "</td>
                                <td>" . $row["email"] . "</td>
                                <td>
                                    <button class='edit-button' type='button' onclick='enableEditing(\"" . $row["Regno"] . "\")'>Edit</button>
                                </td>
                                <td>
                                    <button class='update-button' style='display:none;' type='button' onclick='submitUpdate(\"" . $row["Regno"] . "\")'>Update</button>
                                </td>
                                <td>
                                    <form action='deletestu.php' method='POST' style='display:inline;'>
                                        <input type='hidden' name='id' value='" . $row["Regno"] . "'>
                                        <button class='delete-button' type='submit' name='delete' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No results found.";
                }

                mysqli_close($conn);
            ?>
        </div>

        <div class="back-button-container">
            <button onclick="goBack()">Back</button>
        </div>
		<div class="back-button-container">
            <button onclick="gohome()">Home</button>
        </div>
    </main>
</body>
</html>
<?php } else {
    header('location:adminlogin.php');
} ?>