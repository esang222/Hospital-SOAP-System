<?php

include 'config.php';
$profileImage = 'img/hehe.jpg';
$adminName = 'Admin01';

// Connect to database
// $conn = new mysqli("localhost", "root", "", "hospital_soap_system");
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// // Fetch SOAP notes
// $sql = "SELECT id, patient_name, date, diagnosis FROM soap_notes ORDER BY date DESC";
// $result = $conn->query($sql);
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
        }

        .profile-name {
            font-size: 18px;
            padding-top: 10px;
        }

        aside ul {
            list-style: none;
            padding: 0;
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
            margin-bottom: 30px;
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
            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image">
        </div>
        <div class="profile-name"><?php echo htmlspecialchars($adminName); ?></div>
    </div>
    <aside>
        <ul>
            <li><i class="fa-solid fa-hospital-user"></i> <a href="#">Patient Management</a></li>
            <li><i class="fa-solid fa-calendar-check"></i> <a href="#">Appointments</a></li>
            <li><i class="fa-solid fa-notes-medical"></i> <a href="#">SOAP Notes</a></li>
            <li><i class="fa-solid fa-gear"></i> <a href="#">Settings</a></li>
            <li><i class="fa-solid fa-right-from-bracket"></i> <a href="#">Logout</a></li>
        </ul>
    </aside>
</div>

<div class="main-content">
    <header>
        <h1>SOAP Notes History</h1>
    </header>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Diagnosis</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["patient_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["diagnosis"]) . "</td>";
                        echo "<td><a href='view_soap.php?id=" . $row["id"] . "' class='view-btn'>View</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No SOAP notes found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
