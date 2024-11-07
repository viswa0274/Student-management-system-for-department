<?php
//include 'staffheader.html';
session_start();
$conn = new mysqli("localhost", "root", "", "viswa");

$staffID = $_SESSION['id'];

// Fetch distinct classes handled by the logged-in staff
$classes_query = "SELECT DISTINCT class FROM subject WHERE staffid='$staffID' ORDER BY class ASC";
$classes_result = mysqli_query($conn, $classes_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Assignment and Seminar Marks</title>
    <style>
        /* Basic styling for the page */
        body {
            background-color: #f4f4f9;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .form-container {
            width: 70%;
            max-width: 1000px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #007bff;
            font-size: 28px;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .filters label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .filters select, .filters button {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            width: 100%;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.3s;
        }

        td input {
            width: 90%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
		.nav-buttons {
            position: fixed;
            top: 20px;
            width: calc(100% - 40px);
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
            box-sizing: border-box;
            z-index: 1000;
        }

        .nav-buttons a {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .nav-buttons .back-button {
            background-color: #28a745;
        }

        .nav-buttons .back-button:hover {
            background-color: #218838;
        }

        .nav-buttons .home-button {
            background-color: #17a2b8;
        }

        .nav-buttons .home-button:hover {
            background-color: #138496;
        }
		.nav-buttons .back-button {
            background-color: #28a745;
			width:5%;
        }

        .nav-buttons .back-button:hover {
            background-color: #218838;
        }
    </style>
    <script>
        // Function to fetch subjects based on selected class
        function fetchSubjects(className) {
            if (className === '') {
                document.getElementById('subject').innerHTML = '<option value="">Select a Subject</option>';
                document.getElementById('students').getElementsByTagName('tbody')[0].innerHTML = ''; // Clear students table
                return;
            }

            // Fetch subjects
            fetch('fetch_subjects.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'class=' + encodeURIComponent(className)
            })
            .then(response => response.json())
            .then(data => {
                let subjectSelect = document.getElementById('subject');
                subjectSelect.innerHTML = '<option value="">Select a Subject</option>';
                data.subjects.forEach(subj => {
                    subjectSelect.innerHTML += '<option value="' + subj.sub_code + '">' + subj.sub_name + '</option>';
                });
            })
            .catch(error => {
                console.error('Error fetching subjects:', error);
                document.getElementById('error-message').innerText = 'Error fetching subjects.';
            });

            // Fetch students for the selected class
            fetch('fetchstudents.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'class=' + encodeURIComponent(className)
            })
            .then(response => response.json())
            .then(data => {
                let studentsTable = document.getElementById('students').getElementsByTagName('tbody')[0];
                studentsTable.innerHTML = ''; // Clear previous rows
                if (data.students.length > 0) {
                    data.students.forEach(student => {
                        let row = document.createElement('tr');
                        row.innerHTML = `<td>${student.Regno}</td>
                                         <td>${student.Name}</td>
                                         <td><input type="number" name="ass_mark_${student.Regno}" max="5" required /></td>
                                         <td><input type="number" name="sem_mark_${student.Regno}" max="5" required /></td>`;
                        studentsTable.appendChild(row);
                    });
                } else {
                    studentsTable.innerHTML = '<tr><td colspan="4">No students found.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching students:', error);
                document.getElementById('error-message').innerText = 'Error fetching students.';
            });
        }

        // Set hidden fields before form submission
        function setHiddenFields() {
            document.getElementById('selected-class').value = document.getElementById('class-filter').value;
            document.getElementById('selected-subject').value = document.getElementById('subject').value;
        }
    </script>
</head>
<body>
<div class="nav-buttons">
       <button class="back-button" onclick="window.history.back();">Back</button>
        <a href="staffpanel.php" class="home-button">Home</a>
    </div>
    <div class="form-container">
        <h2>Upload Assignment and Seminar Marks</h2>
        <form method="POST" action="upload_marks.php" onsubmit="setHiddenFields()">
            <div class="filters">
                <div>
                    <label for="class-filter">Class</label>
                    <select id="class-filter" name="class" onchange="fetchSubjects(this.value)">
                        <option value="">Select a Class</option>
                        <?php while ($row = mysqli_fetch_assoc($classes_result)) { ?>
                            <option value="<?php echo $row['class']; ?>"><?php echo $row['class']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a Subject</option>
                    </select>
                </div>
            </div>

            <table id="students">
                <thead>
                    <tr>
                        <th>Register No</th>
                        <th>Name</th>
                        <th>Assignment Marks</th>
                        <th>Seminar Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4">No students available. Select a class and subject to view students.</td>
                    </tr>
                </tbody>
            </table>

            <input type="hidden" id="selected-class" name="selected_class" value="" />
            <input type="hidden" id="selected-subject" name="selected_subject" value="" />

            <button type="submit">Upload Assignment & Seminar Marks</button>
        </form>
        <div id="error-message" style="color: red; text-align: center; margin-top: 10px;"></div>
    </div>
</body>
</html>
