<?php
$profileImage = ""; 
$adminName = "Admin01";
$totalPatients = "";
$todaysAppointments = "";
$upcomingAppointments = [
    [
        'date' => 'Feb. 20, 2025',
        'patientName' => 'Albert Alforja',
        'doctor' => 'Shrek',
        'status' => 'Scheduled'
    ],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            text-wrap: nowrap;
        }

        .profile {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .profile-icon {
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
            object-fit: cover;
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
            /* margin: 15px 0; */
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
            margin-bottom: 50px;
        }

        header h1 {
            margin: 0;
            font-weight: 600;
        }

        .quick-stats {
            margin: 20px 0;
            font-size: 25px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quick-stats i {
            font-size: 1.5em;
            color: #176B87;
        }

        .stats-container {
            background-color: #DBECF7;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 100px;
        }

        .stats-container p {
            margin: 8px 0;
        }

        .appointments-section {
            margin-bottom: 100px;
        }

        .appointments-section h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 25px;
            font-weight: bold;
        }

        .appointments-section i {
            font-size: 1.5em;
            color: #176B87;
        }

        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .appointments-table th,
        .appointments-table td {
            border: 1px solid gray;
            padding: 10px;
            text-align: center;

        }

        .appointments-table th {
            background-color: #DBECF7;
            font-weight: bold;
        }

        .appointments-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .view-link {
            color: #DB2222FF;
            text-decoration: none;
            font-weight: bold;
        }

        .view-link:hover {
            text-decoration: underline;
        }

        .quick-actions {
            margin-top: 20px;
        }

        .quick-actions h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 25px;
            font-weight: bold;
        }

        .quick-actions i {
            font-size: 1.5em;
            color: #176B87;
        }

        .action-buttons button i {
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 50px;
            margin-top: 10px;
            align-items: center;
        }

        .action-buttons button {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #176B87;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            margin-left: 100px;
            width: 18%;
            justify-content: center;
        }

        .action-buttons button:hover {
            background-color: #268ab2;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="profile">
        <img src="<?php echo $profileImage; ?>" alt="Profile" class="profile-icon">
        <div class="profile-name"><?php echo $adminName; ?></div>
    </div>
    <aside>
            <ul>
                <li><i class="fa-solid fa-hospital-user" style="color: #ffffff;"></i>
                <a href="#">Patient Management</a></li>
                <li><i class="fa-solid fa-calendar-check" style="color: #ffffff;"></i>
                <a href="#">Appointments</a></li>
                <li><i class="fa-solid fa-notes-medical" style="color: #ffffff;"></i>
                <a href="#">SOAP Notes</a></li>
                <li><i class="fa-solid fa-gear" style="color: #ffffff;"></i>
                <a href="#">Settings</a></li>
                <li><i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>
                <a href="#">Logout</a></li>
            </ul>
        </aside>
</div>

<div class="main-content">
    <header>
        <h1>Dashboard</h1>
    </header>

    <div class="quick-stats">
        <i class="fas fa-chart-bar"></i> Quick Stats
    </div>
    <div class="stats-container">
        <p><strong>Total Patients:</strong> <?php echo $totalPatients; ?></p>
        <p><strong>Today's Appointments:</strong> <?php echo $todaysAppointments; ?></p>
    </div>

    <div class="appointments-section">
        <h3><i class="fa-solid fa-calendar-check"></i> Upcoming Appointments</h3>
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Patient Name</th>
                    <th>Doctor</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($upcomingAppointments as $appointment): ?>
                <tr>
                    <td><?php echo $appointment['date']; ?></td>
                    <td><?php echo $appointment['patientName']; ?></td>
                    <td><?php echo $appointment['doctor']; ?></td>
                    <td><?php echo $appointment['status']; ?></td>
                    <td><a href="#" class="view-link">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="quick-actions">
        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
        <div class="action-buttons">
            <button><i class="fa-solid fa-user-plus"></i> Add New Patient</button>
            <button><i class="fa-solid fa-calendar-check" style="color: #ffffff;"></i> Schedule Appointment</button>
            <button><i class="fa-solid fa-notes-medical" style="color: #ffffff;"></i> New SOAP Note</button>
        </div>
    </div>
</div>

</body>
</html>
