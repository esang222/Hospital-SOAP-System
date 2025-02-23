<?php
include 'config.php';

// Retrieve registered patients before processing any form submission
$sqlPatients = "SELECT id, full_name FROM patients";
$resultPatients = $conn->query($sqlPatients);
if (!$resultPatients) {
    die("Error retrieving patients: " . $conn->error);
}

// Process form submission if it occurs
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientId = $_POST['patient_id'];
    $doctorName = $_POST['doctor']; // Get the selected doctor name
    $appointmentDate = $_POST['appointment_date'];
    $reason = $_POST['reason']; // Capture the reason for the appointment
    $status = 'Scheduled';

    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_name, appointment_date, reason, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $patientId, $doctorName, $appointmentDate, $reason, $status);

    if ($stmt->execute()) {
        header("Location: addAppointment.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>alert('Appointment added successfully!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <title>Add Appointment</title>

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
            border-radius: 15px;
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

        section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }

        .main {
            background-color: #C0D7E2FF;
            width: 100%;
            display: flex;
            justify-content: center;
            border: 1px solid white;
            border-radius: 15px;    
        }

        .container {
            background-color: #E8F3F8FF;
            padding: 30px 40px;
            border: 1px solid gray;
            border-radius: 15px;
            width: 100%;
            max-width: 500px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin: 30px;
        }

        .forms {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
        }

        .forms label {
            font-weight: 600;
        }

        .forms input, .forms select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            width: 100%;
        }

        .buttons {
            display: flex;
            justify-content: flex-end; 
            gap: 20px; 
        }

        .buttons button {
            margin-top: 20px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid gray;
            border-radius: 10px;
            box-shadow: 3px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .buttons #cancel {
            background-color: #C90F12;
            color: white;
        }

        .buttons #save {
            background-color: #0B9C2AFF;
            color: white;
        }

        .buttons #save:hover {
            background-color: #046E16FF;
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
                <li><i class="fa-solid fa-house"></i><a href="dashboard.php">Dashboard</a></li>
                <li><i class="fa-solid fa-hospital-user" style="color: #ffffff;"></i><a href="patients.php">Patient Management</a></li>
                <li><i class="fa-solid fa-calendar-check" style="color: #ffffff;"></i><a href="appointment.php">Appointments</a></li>
                <li><i class="fa-solid fa-notes-medical" style="color: #ffffff;"></i><a href="SOAP.php">SOAP Notes</a></li>
                <li><i class="fa-solid fa-laptop-medical"></i><a href="records.php">Records</a></li>
                <li><i class="fa-solid fa-gear" style="color: #ffffff;"></i><a href="#">Settings</a></li>
                <li>
                    <i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>
                    <a href="login.php?action=logout" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
                </li>
            </ul>
        </aside>
    </div>
    <div class="main-content">
        <header>
            <h1>Add Appointment</h1>
        </header>
        <section>
    <div class="main">
        <div class="container">
            <form method="POST" action="addAppointment.php" class="forms">
                <label for="patient_id">Patient ID:</label>
                <select id="patient_id" name="patient_id" required>
                    <option value="" selected disabled>Select a Patient</option>
                    <?php while ($patient = $resultPatients->fetch_assoc()): ?>
                        <option value="<?php echo $patient['id']; ?>">
                            <?php echo $patient['id'] . " - " . htmlspecialchars($patient['full_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="doctor">Doctor:</label>
                <select id="doctor" name="doctor" required>
                    <option value="" selected disabled>Select a Doctor</option>
                    <option value="Dr. John Smith">Dr. John Smith</option>
                    <option value="Dr. Alice Lee">Dr. Alice Lee</option>
                    <option value="Dr. Raj Patel">Dr. Raj Patel</option>
                    <option value="Dr. Michael Kim">Dr. Michael Kim</option>
                    <option value="Dr. Maria Garcia">Dr. Maria Garcia</option>
                    <option value="Dr. James Brown">Dr. James Brown</option>
                </select>

                <label for="appointment_date">Date of Appointment:</label>
                <input type="datetime-local" id="appointment_date" name="appointment_date" required>

                <label for="reason">Reason of Appointment:</label>
                <input type="text" id="reason" name="reason" required>

                <div class="buttons">
                    <a href="appointment.php"><button id="cancel" type="button">Cancel</button></a>
                    <button id="save" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</section>

    </div>
</body>
</html>