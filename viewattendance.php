<?php
session_start();
$conn = new mysqli("localhost", "root", "", "viswa");

$staffID = $_SESSION['id'];

$classes_query = "SELECT DISTINCT class FROM subject WHERE staffid='$staffID' ORDER BY class ASC";
$classes_result = mysqli_query($conn, $classes_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Attendance</title>
     <style>
        /* Global Styles */
        body {
            background-color: #f4f6f9;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .form-container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        h2 {
            text-align: center;
            color: #007bff;
            font-size: 28px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .filters {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }

        .filters label {
            font-size: 16px;
            color: #333;
            margin-right: 10px;
        }

        .filters select {
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            color: #333;
            width: 100%;
        }

        .filters select:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 15px 20px;
            text-align: left;
            font-size: 16px;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #007bff;
            color: #fff;
            text-transform: uppercase;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) {
            background-color: #fff;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .error {
            color: red;
            font-size: 16px;
            margin-top: 15px;
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

        @media (max-width: 768px) {
            .filters {
                flex-direction: column;
                gap: 20px;
                padding: 0 20px;
            }

            .form-container {
                width: 100%;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('class-filter').addEventListener('change', function() {
                const className = this.value;
                fetchSubjects(className);
            });

            document.getElementById('subject').addEventListener('change', function() {
                const className = document.getElementById('class-filter').value;
                const subjectCode = this.value;
                fetchDetails(className, subjectCode);
            });
        });

        function fetchSubjects(className) {
            if (className === '') {
                document.getElementById('subject').innerHTML = '<option value="">Select a Subject</option>';
                document.getElementById('students').getElementsByTagName('tbody')[0].innerHTML = '';
                return;
            }

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
                    subjectSelect.innerHTML += `<option value="${subj.sub_code}">${subj.sub_name}</option>`;
                });
            })
            .catch(error => {
                console.error('Error fetching subjects:', error);
                document.getElementById('error-message').innerText = 'Error fetching subjects.';
            });
        }

        function fetchDetails(className, subjectCode) {
            if (className === '' || subjectCode === '') {
                document.getElementById('students').getElementsByTagName('tbody')[0].innerHTML = '';
                return;
            }

            fetch('fetch_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'class=' + encodeURIComponent(className) + '&subject_code=' + encodeURIComponent(subjectCode)
            })
            .then(response => response.json())
            .then(data => {
                let studentsTable = document.getElementById('students').getElementsByTagName('tbody')[0];
                studentsTable.innerHTML = ''; // Clear previous rows
                if (data.students.length > 0) {
                    data.students.forEach(student => {
                        let row = document.createElement('tr');
                        row.innerHTML = `<td>${student.Regno}</td><td>${student.Name}</td>
                                         <td>${student.att_per}%</td>
                                         <td>${student.att_per1}%</td>
                                         <td>${student.att_per2}%</td>`;
                        studentsTable.appendChild(row);
                    });
                } else {
                    studentsTable.innerHTML = '<tr><td colspan="5">No students found.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching details:', error);
                document.getElementById('error-message').innerText = 'Error fetching details.';
            });
        }
    </script>
</head>
<body>
    <div class="nav-buttons">
         <button class="back-button" onclick="window.history.back();">Back</button>
		 <a href="staffpanel.php" class="home-button">Home</a>
    </div>
    <div class="form-container">
        <h2>Attendance Details</h2>
        <form action="" method="POST">
            <div class="filters">
                <label for="class-filter">Select Class:</label>
                <select name="class" id="class-filter">
                    <option value="">Select a Class</option>
                    <?php while ($row = mysqli_fetch_assoc($classes_result)): ?>
                        <option value="<?php echo $row['class']; ?>"><?php echo $row['class']; ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="subject-filter">Select Subject:</label>
                <select name="subject" id="subject">
                    <option value="">Select a Subject</option>
                </select>
            </div>

            <table id="students">
                <thead>
                    <tr>
                        <th>Regno</th>
                        <th>Name</th>
                        <th>Phase 1 Attendance</th>
                        <th>Phase 2 Attendance</th>
                        <th>Phase 3 Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Student rows will be populated here via fetchDetails -->
                </tbody>
            </table>
            <div id="error-message" class="error"></div>
        </form>
    </div>
</body>
</html>
