<?php
// Include database connection
include "config.php";

// Fetch total patients
$sqlPatients = "SELECT COUNT(*) AS total FROM patients";
$resultPatients = $conn->query($sqlPatients);
$totalPatients = ($resultPatients->num_rows > 0) ? $resultPatients->fetch_assoc()['total'] : 0;

// Fetch today's appointments
$today = date('Y-m-d');
$sqlAppointments = "SELECT COUNT(*) AS total FROM appointments WHERE DATE(appointment_date) = '$today'";
$resultAppointments = $conn->query($sqlAppointments);
$todaysAppointments = ($resultAppointments->num_rows > 0) ? $resultAppointments->fetch_assoc()['total'] : 0;

// Fetch upcoming appointments (appointments from today onward)
$sql = "SELECT a.id, a.appointment_date AS date, 
               p.full_name AS patientName, 
               a.doctor_name AS doctor, 
               a.status
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        WHERE a.appointment_date >= CURDATE()
        ORDER BY a.appointment_date ASC";

$result = $conn->query($sql);

$upcomingAppointments = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $upcomingAppointments[] = $row;
    }
}
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
            box-sizing: border-box;
            text-wrap: nowrap;
            display: flex;
            flex-direction: column; 
            box-shadow: 3px 3px 10px gray;        
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
            /* margin: 15px 0; */
            padding: 25px 10px;
        }

        aside ul li:hover {
            border: 1px;
            border-radius: 10px;
            background-color: #668C9CFF;
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
            border-radius: 15px;
            margin-bottom: 50px;
        }

        header h1 {
            margin: 0;
            font-weight: 600;
        }

        section{
            background-color: #C0D7E2FF; 
            padding: 40px 30px;
            height: 80%;
            border-radius: 15px;
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
            background-color: white;
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
            background-color: #93B2C0FF;
            font-weight: bold;
        }

        .appointments-table tbody tr {
            background-color: #FFFFFFFF;
        }

        .view-link {
            color: #DB2222FF;
            text-decoration: none;
            font-weight: bold;
        }

        .view-link:hover {
            text-decoration: underline;
        }

        .btn-container{
            background-color: white;
            padding: 20px;
            border-radius: 10px;
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
            justify-content: center;
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
            width: 80%;
            justify-content: center;
            text-decoration: none;    
        }

        .action-buttons a {
            text-decoration: none;        
        }

        .action-buttons button:hover {
            background-color: #244958FF;
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
                <li><i class="fa-solid fa-house"></i>
                <a href="dashboard.php">Dashboard</a></li>
                <li><i class="fa-solid fa-hospital-user" style="color: #ffffff;"></i>
                <a href="patients.php">Patient Management</a></li>
                <li><i class="fa-solid fa-calendar-check" style="color: #ffffff;"></i>
                <a href="appointment.php">Appointments</a></li>
                <li><i class="fa-solid fa-notes-medical" style="color: #ffffff;"></i>
                <a href="SOAP.php">SOAP Notes</a></li>
                <li><i class="fa-solid fa-laptop-medical"></i>
                <a href="records.php">Records</a></li>
                <li><i class="fa-solid fa-gear" style="color: #ffffff;"></i>
                <a href="#">Settings</a></li>
                <li><i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>
                <a href="login.php">Logout</a></li>
            </ul>
        </aside>
    </div>

<div class="main-content">
    <header>
        <h1>Dashboard</h1>
    </header>

    <section>
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
            <?php if (!empty($upcomingAppointments)): ?>
                <?php foreach ($upcomingAppointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['patientName']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['doctor']); ?></td>
                        <td class="<?php echo ($appointment['status'] == 'Scheduled') ? 'text-success' : (($appointment['status'] == 'Cancelled') ? 'text-danger' : 'text-warning'); ?>">
                            <?php echo htmlspecialchars($appointment['status']); ?>
                        </td>
                        <td>
                            <a href="appointment.php?id=<?php echo htmlspecialchars($appointment['id']); ?>" class="view-link">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No appointments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

    <div class="quick-actions">
        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
        <div class="action-buttons">
            <a href="addpatient.php"><button><i class="fa-solid fa-user-plus"></i> Add New Patient</button></a>
            <a href="addAppointment.php"><button><i class="fa-solid fa-calendar-check" style="color: #ffffff;"></i> Schedule Appointment</button></a>
            <a href="SOAP.php"><button><i class="fa-solid fa-notes-medical" style="color: #ffffff;"></i> Create SOAP Note</button></a>
        </div>
    </div>
    </section>
    
</div>



</body>
</html>
