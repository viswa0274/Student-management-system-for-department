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
    <title>Upload Marks</title>
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
                const resultFilter = document.getElementById('result-filter').value; // Get result filter value
                fetchDetails(className, subjectCode, resultFilter);
            });

            document.getElementById('result-filter').addEventListener('change', function() {
                const className = document.getElementById('class-filter').value;
                const subjectCode = document.getElementById('subject').value;
                const resultFilter = this.value;
                fetchDetails(className, subjectCode, resultFilter);
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

        function fetchDetails(className, subjectCode, resultFilter) {
    if (className === '' || subjectCode === '') {
        document.getElementById('students').getElementsByTagName('tbody')[0].innerHTML = '';
        document.getElementById('pass-count').innerText = 'Pass: 0';
        document.getElementById('fail-count').innerText = 'Fail: 0';
        return;
    }

    fetch('fetch_marks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'class=' + encodeURIComponent(className) + '&subject_code=' + encodeURIComponent(subjectCode) + '&result=' + encodeURIComponent(resultFilter)
    })
    .then(response => response.json())
    .then(data => {
        let studentsTable = document.getElementById('students').getElementsByTagName('tbody')[0];
        studentsTable.innerHTML = ''; // Clear previous rows

        // Populate students
        if (data.students.length > 0) {
            data.students.forEach(student => {
                let row = document.createElement('tr');
                row.innerHTML = `
                    <td>${student.Regno}</td>
                    <td>${student.Name}</td>
                    <td>${student.int_mark1}</td>
                    <td>${student.int_mark2}</td>
                    <td>${student.int_mark3}</td>
                    <td>${student.ass_mark}</td>
                    <td>${student.sem_mark}</td>
                    <td>${student.ext_mark}</td>
                    <td>${student.result}</td>
                `;
                studentsTable.appendChild(row);
            });
        } else {
            studentsTable.innerHTML = '<tr><td colspan="9">No students found.</td></tr>';
        }

        // Update pass and fail counts
        document.getElementById('pass-count').innerText = 'Total No.of.Pass: ' + data.pass_count;
        document.getElementById('fail-count').innerText = 'Total No.of.Fail: ' + data.fail_count;
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
	
        <h2>Mark List</h2>
		<div id="pass-count" style="font-weight: bold; margin-top: 20px;">Total No.of.Pass: 0</div>
<div id="fail-count" style="font-weight: bold; margin-top: 10px;">Total No.of.Fail: 0</div>
        <form action="" method="POST">
            <div class="filters">
                <label for="class-filter">Select Class:</label>
                <select name="class" id="class-filter">
                    <option value="">Select a Class</option>
                    <?php while ($class = mysqli_fetch_assoc($classes_result)): ?>
                        <option value="<?= $class['class'] ?>"><?= $class['class'] ?></option>
                    <?php endwhile; ?>
                </select>
                
                <label for="subject">Select Subject:</label>
                <select name="subject" id="subject">
                    <option value="">Select a Subject</option>
                </select>
                
                <label for="result-filter">Filter by Result:</label>
                <select name="result" id="result-filter">
                    <option value="">All</option>
                    <option value="pass">Pass</option>
                    <option value="Reappear">Fail</option>
                </select>
            </div>
            <div id="error-message" class="error"></div>
            <table id="students">
                <thead>
                    <tr>
                        <th>Regno</th>
                        <th>Name</th>
                        <th>Internal Mark 1</th>
                        <th>Internal Mark 2</th>
                        <th>Internal Mark 3</th>
                        <th>Assignment Mark</th>
                        <th>Semester Mark</th>
                        <th>External Mark</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic Content will be inserted here -->
                </tbody>
            </table>
			

        </form>
    </div>
</body>
</html>
