<?php
// Include the configuration file for database connection
include 'config.php'; // Ensure this file properly initializes $conn

// Default profile image and admin name
$profileImage = 'img/hehe.jpg';
$adminName = 'Admin01';

// // Ensure database connection is established
// if (!isset($conn)) {
//     die("Database connection is not initialized.");
// }

// // Fetch SOAP notes with all relevant fields
// $sql = "SELECT 
//             s.id, 
//             p.full_name AS patient_name, 
//             s.subjective, 
//             s.objective, 
//             s.assessment, 
//             s.plan, 
//             s.created_at, 
//             s.updated_at 
//         FROM soap_notes s
//         INNER JOIN patients p ON s.patient_id = p.id 
//         ORDER BY s.created_at DESC"; // Sort by most recent notes

// // Execute the query
// $result = $conn->query($sql);

// // Check for query errors
// if (!$result) {
//     die("Error fetching SOAP notes: " . $conn->error);
// }
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
            text-wrap: nowrap;
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
            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image">
        </div>
        <div class="profile-name"><?php echo htmlspecialchars($adminName); ?></div>
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>