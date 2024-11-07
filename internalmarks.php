<?php
session_start();
$conn = new mysqli("localhost", "root", "", "viswa");

$staffID = $_SESSION['id'];

$classes_query = "SELECT DISTINCT class FROM subject WHERE staffid='$staffID'";
$classes_result = mysqli_query($conn, $classes_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Attendance</title>
    <style>
        body {
            background-color: white;
            color: #333;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .form-container {
            width: 70%;
            max-width: 1000px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            //box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        h2 {
            text-align: center;
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .filters select, .filters input, .filters button {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
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
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
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
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #eaeaea;
        }

        .editable {
            background-color: #f9f9f9;
        }
		.top-buttons {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 20px;
        }

        .top-buttons a {
            padding: 12px 20px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .top-buttons a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
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

            //document.getElementById('total-marks').addEventListener('input', updateTotalMarks);

            document.getElementById('upload-form').addEventListener('submit', function(event) {
                event.preventDefault(); 
                const studentsData = updateTotalMarks();
                if (studentsData.length > 0) {
                    sendMarksToServer(studentsData);
                }
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
                studentsTable.innerHTML = '';
                if (data.students.length > 0) {
                    data.students.forEach(student => {
                        let row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${student.Regno}</td>
                            <td>${student.Name}</td>
                            <td><input type="number" class="editable internal-mark1" value="${student.internalmark1}" min="0" required /></td>
                            <td><input type="number" class="editable internal-mark2" value="${student.internalmark2}" min="0" required /></td>
                            <td><input type="number" class="editable internal-mark3" value="${student.internalmark3}" min="0" required /></td>
                            <td><input type="number" class="editable assignment" value="${student.assignment}" min="0" max="5" required /></td>
                            <td><input type="number" class="editable seminar" value="${student.seminar}" min="0" max="5" required /></td>
                            `;
                        studentsTable.appendChild(row);

                        // Add event listener for dynamic total calculation
                       // row.querySelectorAll('input').forEach(input => {
                         //   input.addEventListener('input', updateTotalMarks);
                       // });
                    });
                } else {
                    studentsTable.innerHTML = '<tr><td colspan="7">No records found</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching student details:', error);
                document.getElementById('error-message').innerText = 'Error fetching student details.';
            });
        }
		function sendMarksToServer(studentsData) {
			console.log(studentsData);
    fetch('upload_marks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            subject_code: document.getElementById('subject').value,
            students: studentsData
        })
    })
    .then(response => response.text())
    .then(message => {
        // Display the server response message
        alert(message);
        if (message.includes("successfully")) {
            // Optionally, you might want to reset the form or clear the data
            document.getElementById('upload-form').reset();
            document.getElementById('students').getElementsByTagName('tbody')[0].innerHTML = '';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error uploading marks.');
    });
}


        function updateTotalMarks() {
    const totalMarksInput = document.getElementById('total-marks').value.trim();

    let exceeded = false; 
    const studentsData = [];

    document.querySelectorAll('#students tbody tr').forEach(row => {
        let regno = row.querySelector('td').textContent; // Assuming regno is in the first cell
        let internalMark1 = parseFloat(row.querySelector('.internal-mark1').value) || 0;
        let internalMark2 = parseFloat(row.querySelector('.internal-mark2').value) || 0;
        let internalMark3 = parseFloat(row.querySelector('.internal-mark3').value) || 0;
        let assignment = parseFloat(row.querySelector('.assignment').value) || 0;
        let seminar = parseFloat(row.querySelector('.seminar').value) || 0;

        // Check if any individual internal mark exceeds the total internal marks
        if (internalMark1 > totalMarksInput || internalMark2 > totalMarksInput || internalMark3 > totalMarksInput) {
            exceeded = true;
			alert('Internal Marks should not be greater than total marks');
			exit();
        }

        // Calculate total internal marks by taking the best two internal marks
        let internalMarks = [internalMark1, internalMark2, internalMark3];
        internalMarks.sort((a, b) => b - a); // Sort in descending order
        let bestTwoSum = internalMarks[0] + internalMarks[1]; // Sum of the best two marks
         console.log("best: "+bestTwoSum);
        // Scale the best two internal marks to 15
        let scaledInternalMarks = (bestTwoSum / (totalMarksInput * 2)) * 15;
         console.log("Scaled: "+ scaledInternalMarks);
        // Calculate total marks
        let totalMarks = scaledInternalMarks + assignment + seminar;
            console.log("Total : "+ totalMarks);
        studentsData.push({
            regno: regno,
            totalMarks: totalMarks
        });
    });

    if (exceeded) {
        alert('Some individual marks exceed the total marks value.');
    } else {
        sendMarksToServer(studentsData);
    }
}



    </script>
</head>
<body>

        <div class="top-buttons">
            <a href="javascript:history.back()">Back</a>
            <a href="staffpanel.php">Home</a>
        </div>
    <div class="form-container">
        <h2>Upload Internal Marks</h2>
        <form id="upload-form" action="upload_marks.php" method="POST">
            <div class="filters">
                <select id="class-filter">
                    <option value="">Select a Class</option>
                    <?php while($row = mysqli_fetch_assoc($classes_result)): ?>
                        <option value="<?= htmlspecialchars($row['class']) ?>"><?= htmlspecialchars($row['class']) ?></option>
                    <?php endwhile; ?>
                </select>
                <select id="subject">
                    <option value="">Select a Subject</option>
                </select>
                <input type="number" id="total-marks" placeholder="Enter Total Internal Marks" min="0" />
            </div>
            <div id="error-message" class="error"></div>

            <table id="students">
                <thead>
                    <tr>
                        <th>Register Number</th>
                        <th>Name</th>
                        <th>Internal Mark 1</th>
                        <th>Internal Mark 2</th>
                        <th>Internal Mark 3</th>
                        <th>Assignment</th>
                        <th>Seminar</th>
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>
           <center> <button  type="submit">Upload Marks</button></center>
        </form>
    </div>
</body>
</html>