<?php
include 'header.html';
$conn = new mysqli("localhost", "root", "", "viswa");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $staffID = $_POST['id'];
        $delete_query = "DELETE FROM staff WHERE staffid='$staffID'";

        if ($conn->query($delete_query) === TRUE) {
            echo "<script>alert('Staff deleted successfully');</script>";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } elseif ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['StaffID'])) {
            $staffID = $data['StaffID'];
            $name = $data['Name'];
            $phone_no = $data['Phone_no'];
            $email = $data['email'];
            $department = $data['department'];
            $category = $data['category'];

            $update_query = "UPDATE staff SET Name='$name', Phone_no='$phone_no', email='$email', department='$department', category='$category' WHERE staffid='$staffID'";

            if ($conn->query($update_query) === TRUE) {
                echo "<script>alert('Staff updated successfully');</script>";
            } else {
                echo json_encode(['message' => 'Error updating record: ' . $conn->error]);
            }
        }
    }
}

// Get selected filters
$selected_department = isset($_POST['department']) ? $_POST['department'] : '';
$selected_degree = isset($_POST['degree']) ? $_POST['degree'] : '';

// Build SQL query based on selected filters
$s = "SELECT * FROM staff WHERE 1";

if ($selected_department != '') {
    $s .= " AND department = '$selected_department'";
}

if ($selected_degree != '') {
    $s .= " AND category = '$selected_degree'"; // Assuming 'category' is used as a degree in your database
}

$s .= " ORDER BY staffid";
$r = mysqli_query($conn, $s);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <style>
        body { background-color: white; color: #333333; margin: 0;
background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;		padding: 0; font-family: 'Arial', sans-serif; }
        main { background-color: white; 
		background: url('ii.jpg') no-repeat center center fixed;
            background-size: cover;padding: 20px; margin-left: 21%; margin-top: 5%; color: #333333; }
        .table-container { width: 98%; margin: 0 auto; border-collapse: collapse; height: 400px; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; min-width: 1000px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #990011; color: #ffffff; font-size: 16px; text-transform: uppercase; letter-spacing: 1px; position: sticky; top: 0; z-index: 1; }
        td { background-color: #f9f9f9; color: #333333; }
        button { padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; font-size: 16px; color: #ffffff; }
        .edit-button { background-color: #007bff; }
		input.editable {
            width: 100%; 
            box-sizing: border-box; 
            padding: 8px;
            font-size: 14px;
        }
        .edit-button:hover { background-color: #0069d9; }
		.back-button-container {
            text-align: center;
            margin-top: 20px;
        }
		
select {
    width: 200px; 
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    color: #333;
    background-color: #fff;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
    outline: none;
    cursor: pointer;
    transition: border-color 0.3s, box-shadow 0.3s;
}

select:focus {
    border-color: #007bff;
    box-shadow: 0px 2px 4px rgba(0, 123, 255, 0.5);
}


option {
    padding: 10px;
    background-color: #fff;
    color: #333;
}


.filter-container {
    margin-bottom: 20px;
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
        .update-button { background-color: #28a745; display: none; }
        .update-button:hover { background-color: #218838; }
        .delete-button { background-color: #dc3545; margin-left: 5px; }
        .delete-button:hover { background-color: #c82333; }
    </style>
    <script>
        function enableEditing(rowId) {
            let row = document.getElementById('row-' + rowId);
            let cells = row.querySelectorAll('td');

            cells.forEach(function(cell, index) {
                if (index > 0 && index < 6) {
                    let value = cell.innerText;
                    cell.innerHTML = '<input class="editable" type="text" value="' + value + '">';
                }
            });

            row.querySelector('.edit-button').style.display = 'none';
            row.querySelector('.update-button').style.display = 'inline-block';
        }
function goBack() {
            window.history.back();
        }
        function submitUpdate(rowId) {
            let row = document.getElementById('row-' + rowId);
            let cells = row.querySelectorAll('td');
            let updatedValues = {
                "StaffID": cells[0].innerText,
                "Name": cells[1].querySelector('input').value,
                "Phone_no": cells[2].querySelector('input').value,
                "email": cells[3].querySelector('input').value,
                "department": cells[4].querySelector('input').value,
                "category": cells[5].querySelector('input').value
            };
			cells[1].innerHTML = updatedValues["Name"];
                    cells[2].innerHTML = updatedValues["Phone_no"];
                    cells[3].innerHTML = updatedValues["email"];
                    cells[4].innerHTML = updatedValues["department"];
                    cells[5].innerHTML = updatedValues["category"];
                    
                    row.querySelector('.edit-button').style.display = 'inline-block';
                    row.querySelector('.update-button').style.display = 'none';

            fetch('', {
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
    </script>
</head>
<body>
    <main>
        <h2 align="center">Staff Details</h2>
        <div class="filter-container">
            <form action="" method="POST">
                <label for="department-filter">Select Department:</label>
                <select name="department" id="department-filter" onchange="this.form.submit()">
                    <option value="" <?php echo (!isset($_POST['department']) || $_POST['department'] == '') ? 'selected' : ''; ?>>All Departments</option>
                    <?php
                        $conn = new mysqli("localhost", "root", "", "viswa");

                        if (!$conn) {
                            die("Connection Failed: " . mysqli_connect_error());
                        }

                        
                        $dep_query = "SELECT DISTINCT dep_name FROM department";
                        $dep_result = mysqli_query($conn, $dep_query);

                        while ($row = mysqli_fetch_assoc($dep_result)) {
                            $department_name = $row['dep_name'];
                            echo "<option value=\"$department_name\" " . (isset($_POST['department']) && $_POST['department'] == $department_name ? 'selected' : '') . ">$department_name</option>";
                        }

                        mysqli_close($conn);
                    ?>
                </select>

                <label for="degree-filter">Select Degree:</label>
                <select name="degree" id="degree-filter" onchange="this.form.submit()">
                    <option value="" <?php echo (!isset($_POST['degree']) || $_POST['degree'] == '') ? 'selected' : ''; ?>>All Degrees</option>
                    <?php
                        if (isset($_POST['department']) && $_POST['department'] != '') {
                            $selected_department = $_POST['department'];

                            $conn = new mysqli("localhost", "root", "", "viswa");

                            if (!$conn) {
                                die("Connection Failed: " . mysqli_connect_error());
                            }

                            
                            $deg_query = "SELECT DISTINCT degree FROM department WHERE dep_name = '$selected_department'";
                            $deg_result = mysqli_query($conn, $deg_query);

                            while ($row = mysqli_fetch_assoc($deg_result)) {
                                $degree_name = $row['degree'];
                                echo "<option value=\"$degree_name\" " . (isset($_POST['degree']) && $_POST['degree'] == $degree_name ? 'selected' : '') . ">$degree_name</option>";
                            }

                            mysqli_close($conn);
                        }
                    ?>
                </select>
            </form>
        </div>
        <div class="table-container">
            <table>
                <tr align="Center">
                    <th>Staff ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Category</th>
                    <th>Edit</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
                <?php if (mysqli_num_rows($r) > 0): ?>
                    <?php while ($row = mysqli_fetch_array($r)): ?>
                        <tr id="row-<?php echo $row["staffid"]; ?>">
                            <td><?php echo $row["staffid"]; ?></td>
                            <td class='nn'><?php echo $row["Name"]; ?></td>
                            <td><?php echo $row["Phone_no"]; ?></td>
                            <td><?php echo $row["email"]; ?></td>
                            <td><?php echo $row["department"]; ?></td>
                            <td><?php echo $row["category"]; ?></td>
                            <td>
                                <button class='edit-button' type='button' onclick='enableEditing("<?php echo $row["staffid"]; ?>")'>Edit</button>
                            </td>
                            <td>
                                <button class='update-button' type='button' onclick='submitUpdate("<?php echo $row["staffid"]; ?>")'>Update</button>
                            </td>
                            <td>
                                <form action="" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $row["staffid"]; ?>">
                                    <button type='submit' name='delete' class='delete-button'>Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9">No results found.</td></tr>
                <?php endif; ?>
            </table>
        </div>
        
    </main>
</body>
</html>


