<?php
//include 'staffheader.html';
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
    <title>Upload Attendance and Internal Marks</title>
    <style>
        /* Styling for form, table, etc. */
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

        .filters select, .filters input, .filters button {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            width: 100%;
        }

        .filters select, .filters input {
            transition: border-color 0.3s ease;
        }

        .filters select:hover, .filters input:hover {
            border-color: #007bff;
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

        .attendance-percentage {
            font-weight: bold;
            color: #007bff;
        }

        /* Add some animation for input focus */
        input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .error-message {
            color: red;
            text-align: center;
            font-size: 14px;
            margin-top: 15px;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 20px;
            }

            .filters {
                flex-direction: column;
            }

            table {
                font-size: 14px;
            }
        }
		.nav-buttons {
            position: fixed;
            top: 20px;
            width: calc(100% - 40px); /* Adjust based on the button widths */
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

        .nav-buttons a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .nav-buttons .back-button {
            background-color: #28a745;
			width:5%;
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
    </style>
    <script>
    // Fetch subjects for the selected class
    function fetchSubjects(className) {
        if (className === '') {
            document.getElementById('subject').innerHTML = '<option value="">Select a Subject</option>';
            document.getElementById('students').getElementsByTagName('tbody')[0].innerHTML = ''; // Clear students table
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
row.innerHTML = `<td>${student.Regno}</td><td>${student.Name}</td>
                 <td><input type="number" name="days_present_${student.Regno}" value="" class="editable" min="0"/></td>
                 <td><input type="number" name="internal_mark_${student.Regno}" value="" class="editable" /></td>
                 <td class="attendance-percentage">0%</td>`;
studentsTable.appendChild(row);

                });
            } else {
                studentsTable.innerHTML = '<tr><td colspan="5">No students found.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching students:', error);
            document.getElementById('error-message').innerText = 'Error fetching students.';
        });
    }

    function setMaxInternalMarks() {
    let totalInternalMarks = document.getElementById('total-internal-marks').value;
    let internalMarkInputs = document.querySelectorAll('input[name^="internal_mark"]');
    let totalDays = document.getElementById('total-days').value;
    let daysPresentInputs = document.querySelectorAll('input[name^="days_present_"]');

    internalMarkInputs.forEach(input => {
        input.setAttribute('max', totalInternalMarks);
    });

    daysPresentInputs.forEach(input => {
        input.setAttribute('max', totalDays);
    });
}


   function setHiddenFields() {
        document.getElementById('selected-class').value = document.getElementById('class-filter').value;
        document.getElementById('selected-subject').value = document.getElementById('subject').value;
        document.getElementById('hidden-total-days').value = document.getElementById('total-days').value;
        document.getElementById('hidden-internal-type').value = document.getElementById('internal-type').value;
        // Set the total internal marks
        document.getElementById('hidden-total-internal-marks').value = document.getElementById('total-internal-marks').value;
    }

    function calculateAttendancePercentage() {
        const totalDays = parseFloat(document.getElementById('total-days').value);
        if (isNaN(totalDays) || totalDays <= 0) {
            document.querySelectorAll('#students tbody .attendance-percentage').forEach(cell => {
                cell.textContent = '0%';
            });
            return;
        }

        let rows = document.querySelectorAll('#students tbody tr');
        rows.forEach(row => {
            let daysPresentInput = row.querySelector('input[name^="days_present_"]');
            let attendancePercentageCell = row.querySelector('.attendance-percentage');
            let daysPresent = parseFloat(daysPresentInput.value) || 0;

            let percentage = (daysPresent / totalDays) * 100;
            attendancePercentageCell.textContent = percentage.toFixed(2) + '%';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('#students').addEventListener('input', function(event) {
            if (event.target && event.target.matches('input[name^="days_present_"]')) {
                calculateAttendancePercentage();
            }
        });

        document.getElementById('total-days').addEventListener('input', function() {
            calculateAttendancePercentage();
        });

        document.getElementById('total-internal-marks').addEventListener('input', function() {
            setMaxInternalMarks(); // Adjust max value for internal marks
        });
    });
    </script>
</head>
<body>
<div class="nav-buttons">
         <button class="back-button" onclick="window.history.back();">Back</button>
        <a href="staffpanel.php" class="home-button">Home</a>
    </div>

    
    <div class="form-container">
        <h2>Upload Attendance and Internal Marks</h2>
        <form action="process_attendance.php" method="POST" onsubmit="setHiddenFields()">
        <div class="filters">
            <label for="class-filter">Select Class:</label>
            <select name="class" id="class-filter" onchange="fetchSubjects(this.value)">
                <option value="">Select a Class</option>
                <?php while ($row = mysqli_fetch_assoc($classes_result)): ?>
                    <option value="<?php echo $row['class']; ?>"><?php echo $row['class']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="subject-filter">Select Subject:</label>
            <select name="subject" id="subject">
                <option value="">Select a Subject</option>
            </select>

            <label for="internal-type">Select Internal:</label>
            <select name="internal-type" id="internal-type">
                <option value="1">Internal 1</option>
                <option value="2">Internal 2</option>
                <option value="3">Internal 3</option>
            </select>

            <label for="total-days">Total No. of Days:</label>
            <input type="number" name="total-days" id="total-days" value="0" required />

            <label for="total-internal-marks">Total Internal Marks:</label>
            <input type="number" name="total-internal-marks" id="total-internal-marks" value="0" required />
        </div>

        <table id="students">
            <thead>
                <tr>
                    <th>Register No.</th>
                    <th>Student Name</th>
                    <th>Days Present</th>
                    <th>Internal Mark</th>
                    <th>Attendance %</th>
                </tr>
            </thead>
            <tbody>
                <!-- Student data will be dynamically populated here -->
            </tbody>
        </table>
<input type="hidden" name="hidden-total-internal-marks" id="hidden-total-internal-marks" />
        <input type="hidden" name="selected-class" id="selected-class" />
        <input type="hidden" name="selected-subject" id="selected-subject" />
        <input type="hidden" name="hidden-total-days" id="hidden-total-days" />
        <input type="hidden" name="hidden-internal-type" id="hidden-internal-type" />

        <button type="submit">Submit</button>
    </form>
    <div id="error-message" class="error-message"></div>
</div>

</body>
</html>