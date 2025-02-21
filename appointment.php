<?php
$profileImage = 'img/hehe.jpg.';
$adminName = 'Admin01';
include 'config.php';


// Establish database connection if not already set
if (!isset($conn)) {
    $conn = new mysqli("127.0.0.1", "root", "", "hospital_soap_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

$sql = "SELECT a.id, p.full_name AS patient_name, p.contact, 
               u.username AS doctor_name, a.appointment_date, a.status, a.notes
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        JOIN users u ON a.doctor_id = u.id
        WHERE u.role = 'Doctor'
        ORDER BY a.appointment_date DESC";

$result = $conn->query($sql);
?>
      
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Patient Management</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap');

        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: "Lexend", serif; 
        }
        html, body { 
            height: 100%; 
        }
        body { 
            display: flex; 
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

        nav {
            display: flex;
            justify-content: flex-end;
            padding: 20px;
        }

        .search-bar {
            display: flex;
            align-items: center;
        }

        .search-bar i {
            padding-right: 10px;
        }

        .search-bar input {
            padding: 8px;
            margin-right: 10px;
            border-radius: 10px;
            width: 15rem;
            font-size: 14px;
            border: 2px solid gray;
        }

        .search-bar button {
            padding: 10px 15px;
            margin-right: 10px;
            background-color: #176B87;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 10px;
            font-size: 16px;
            box-shadow: 3px 2px 5px rgba(0, 0, 0, 0.4);
        }

        .search-bar button:hover {
            background-color: #09546DFF;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid gray;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #B2DBED;
            color: black;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-primary { background-color: #1f677a; }
        .btn-success { background-color: #28a745; }
        .btn-warning { background-color: #ffc107; color: black; }
        .btn-danger { background-color: #dc3545; }


        .text-success { color: green; }
        .text-danger { color: red; }

    </style>
</head>
    <div class="sidebar">
        <div class="profile">
            <div class="profile-icon">
                <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image">
            </div>
            <div class="profile-name"><?php echo htmlspecialchars($adminName); ?></div>
        </div>
        <aside>
            <ul>
                <li><i class="fa-solid fa-house"></i></i>
                <a href="dashboard.php">Dashboard</a></li>
                <li><i class="fa-solid fa-hospital-user" style="color: #ffffff;"></i>
                <a href="patients.php">Patient Management</a></li>
                <li><i class="fa-solid fa-calendar-check" style="color: #ffffff;"></i>
                <a href="appointment.php">Appointments</a></li>
                <li><i class="fa-solid fa-notes-medical" style="color: #ffffff;"></i>
                <a href="Subjective.php">SOAP Notes</a></li>
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
            <h1>Appointments</h1>
        </header>
        <nav>
            <div class="search-bar">
                <input type="text" placeholder="Search">
                <button><i class="fa-solid fa-magnifying-glass"></i>Search</button>
                <a href="addAppointment.php"><button> <i class="fa-solid fa-calendar-check"></i>Add Appointment</button></a>
            </div>
        </nav>

        <h2>Upcoming Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient's Name</th>
                    <th>Contact Number</th>
                    <th>Doctor's Name</th>
                    <th>Specialty</th>
                    <th>Appointment Date & Time</th>
                    <th>Reason of Appointment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
                    <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                <td><?php echo htmlspecialchars($row['contact']); ?></td>
                <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                <td><?php echo htmlspecialchars($row['notes']); ?></td>
                <td class="<?php echo ($row['status'] == 'Scheduled') ? 'text-success' : 'text-danger'; ?>">
                    <?php echo htmlspecialchars($row['status']); ?>
                </td>
                <td>
                    <a href="edit_appointment.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-warning">Edit</a>
                    <a href="delete_appointment.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>

        </table>
    </div>
</body>
</html>

<?php

$conn->close();
?>
