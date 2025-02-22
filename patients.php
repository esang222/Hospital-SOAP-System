<?php
include 'config.php';

// Define or fetch admin profile information
$profileImage = 'img/hehe.jpg'; 
$adminName = 'Admin Name'; 

$sql = "SELECT p.id, p.full_name, p.sex, p.dob, p.age, p.contact, COUNT(a.id) AS total_appointments
        FROM patients p
        LEFT JOIN appointments a ON p.id = a.patient_id
        GROUP BY p.id";

$result = $conn->query($sql);

$patients = [];
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $patients[] = $row;
        }
    }
} else {
    die("Error fetching patients: " . $conn->error);
}

$conn->close();
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
            font-size: 14px;
            box-shadow: 3px 2px 5px rgba(0, 0, 0, 0.4);
        }

        .search-bar button:hover {
            background-color: #09546DFF;
        }

        .patient-list table { 
            width: 100%; 
            border-collapse: collapse; 
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4); 
        }
        .patient-list th, .patient-list td { 
            border: 1px solid gray; 
            padding: 10px; 
            text-align: center; 
        }
        .patient-list th { 
            background-color: #B2DBED; 
            color: black; 
            font-weight: 600; 
        }
        .patient-list td .edit, .patient-list td .delete, .patient-list td .add-appointment {
            padding: 10px; 
            color: white; 
            border: 1px solid gray; 
            cursor: pointer; 
            margin-right: 10px; 
            border-radius: 10px;
        }
        .patient-list td .edit {
             background-color: #B9B048FF; 
        }
        .patient-list td .edit:hover { 
            background-color: #A79F30FF;
         }
        .patient-list td .delete { 
            background-color: #BB5557FF;
         }
        .patient-list td .delete:hover { 
            background-color: #852426FF;
         }
        .patient-list td .add-appointment { 
            background-color: #4CAC4FFF;
         }
        .patient-list td .add-appointment:hover { 
            background-color: #126D15FF;
         }
        .pagination { 
            margin-top: 30px; 
            text-align: center;
         }
        .pagination i { 
            padding: 0px 20px;
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
            <h1>Patient Management</h1>
        </header>
        <nav>
            <div class="search-bar">
                <input type="text" placeholder="Search">
                <button><i class="fa-solid fa-magnifying-glass"></i>Search</button>
                <a href="addpatient.php"><button> <i class="fa-solid fa-user-plus"></i>Add Patient</button></a>
            </div>
        </nav>

        <section class="patient-list">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Sex</th>
                        <th>Date of Birth</th>
                        <th>Age</th>
                        <th>Contact Information</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($patients)): ?>
                        <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($patient['id']); ?></td>
                            <td><?php echo htmlspecialchars($patient['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($patient['sex']); ?></td>
                            <td><?php echo htmlspecialchars($patient['dob']); ?></td>
                            <td><?php echo htmlspecialchars($patient['age']); ?></td>
                            <td><?php echo htmlspecialchars($patient['contact']); ?></td>
                            <td>
                                <button class="edit">Edit</button>
                                <button class="delete">Delete</button>
                                <a href="appointment.php"><button class="add-appointment">Add Appointment</button></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No patients found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>