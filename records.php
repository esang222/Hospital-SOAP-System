<?php
include 'config.php';  // Ensure this file contains the database connection

// Check if 'patient_id' is set in the URL query string
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    // Fetch the latest SOAP note data for the given patient
    $query = "SELECT sn.*, p.full_name FROM soap_notes sn 
              JOIN patients p ON sn.patient_id = p.id 
              WHERE sn.patient_id = ? 
              ORDER BY sn.created_at DESC LIMIT 1";  // Limit to the latest note

    // Prepare the statement
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        // Query preparation failed
        echo "Error: " . $conn->error;
        exit;
    }

    $stmt->bind_param('i', $patient_id);  // Bind the patient_id as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if data is retrieved
    if ($result->num_rows == 0) {
        echo "No records found for patient ID: " . htmlspecialchars($patient_id);
        exit;
    }
} else {
    echo "No patient ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>SOAP Notes History</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Lexend", serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #f9f9f9;
        }

        .sidebar {
            width: 320px;
            background-color: #176B87;
            color: white;
            padding: 15px;
            height: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column; 
        }

        .profile {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            justify-content: center;
            flex-direction: column;
        }

        .profile-icon img {
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
            margin-right: 10px;
        }

        .profile-name {
            font-size: 18px;
            padding-top: 10px;
        }

        aside ul {
            list-style: none;
            padding: 0;
            flex-grow: 1; 
        }

        aside ul li:hover {
            border: 1px;
            border-radius: 10px;
            background-color: #668C9CFF;
        }

        aside ul li {
            padding: 25px 10px;
        }

        aside ul li a {
            color: white;
            text-decoration: none;
            font-size: 22px;
            padding: 10px;
        }

        aside ul li i {
            font-size: 26px;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px 50px;
            box-sizing: border-box;
            overflow-y: auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #176B87;
            padding: 20px;
            color: white;
            border-radius: 20px;
        }

        header h1 {
            margin: 0;
            font-weight: 600;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
        }

        th, td {
            padding: 12px;
        }

        th {
            background-color: #176B87;
            color: white;
        }

        .view-btn {
            background-color: #2d98da;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .view-btn:hover {
            background-color: #1e66a7;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="profile">
        <div class="profile-icon">
            <img src="img/hehe.jpg" alt="Profile Image">
        </div>
        <div class="profile-name">Admin01</div>
    </div>
    <aside>
        <ul>
            <li><i class="fa-solid fa-house"></i> <a href="dashboard.php">Dashboard</a></li>
            <li><i class="fa-solid fa-hospital-user"></i> <a href="patients.php">Patient Management</a></li>
            <li><i class="fa-solid fa-calendar-check"></i> <a href="appointment.php">Appointments</a></li>
            <li><i class="fa-solid fa-notes-medical"></i> <a href="Subjective.php">SOAP Notes</a></li>
            <li><i class="fa-solid fa-laptop-medical"></i> <a href="records.php">Records</a></li>
            <li><i class="fa-solid fa-gear"></i> <a href="#">Settings</a></li>
            <li><i class="fa-solid fa-right-from-bracket"></i> <a href="login.php">Logout</a></li>
        </ul>
    </aside>
</div>

<div class="main-content">
    <header>
        <h1>SOAP Notes Records</h1>
    </header>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Subjective</th>
                    <th>Objective</th>
                    <th>Assessment</th>
                    <th>Plan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['subjective']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['objective']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['assessment']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['plan']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
