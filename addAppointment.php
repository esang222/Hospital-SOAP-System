<?php
$profileImage = "img/hehe.jpg"; 
$adminName = 'Admin01';

// Database connection
$servername = "127.0.0.1";
$username = "root";
$password = "";
$database = "hospital_soap_system";
$port = 3306; // Ensure the port is set correctly

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientId = $_POST['patient_id'];
    $doctorName = $_POST['doctor_name'];
    $appointmentDate = $_POST['appointment_date'];
    $status = 'Scheduled';
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_name, appointment_date, status, reason) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $patientId, $doctorName, $appointmentDate, $status, $reason);

    if ($stmt->execute()) {
        echo "New appointment created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        }

        .profile {
            display: flex;
            margin-bottom: 20px;
            align-items: center;
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
        
        .title h1{
            margin-top: 30px;
            font-size: 35px;
            font-weight: 600;
            margin-left: 30px;
            color: #176B87;
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

        section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }

        .main{
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

        .buttons button{
            margin-top: 20px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid gray;
            border-radius: 10px;
            box-shadow: 3px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .buttons #cancel{
            background-color: #C90F12;
            color: white;
        }

        .buttons #save{
            background-color: #0B9C2AFF;
            color: white;
            padding: 10px 20px;
        }

        .buttons #save:hover{
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
            <h1>Add Appointment</h1>
        </header>
        
        <section>
            <div class="main">
                <div class="container">
                    <form method="POST" action="addAppointment.php" class="forms">
                        <label for="patient_id">Patient ID:</label>
                        <input type="text" id="patient_id" name="patient_id">

                        <label for="doctor_name">Doctor Name:</label>
                        <input type="text" id="doctor_name" name="doctor_name">

                        <label for="appointment_date">Date of Appointment:</label>
                        <input type="datetime-local" id="appointment_date" name="appointment_date">

                        <label for="reason">Reason:</label>
                        <input type="text" id="reason" name="reason">

                        <!-- Add other necessary fields as needed -->
                        
                        <div class="buttons">
                            <a href="appointment.php"><button type="button" id="cancel">Cancel</button></a>
                            <button type="submit" id="save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
