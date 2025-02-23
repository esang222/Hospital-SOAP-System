<?php
include 'config.php'; 

// Retrieve patients for the dropdown from the 'patients' table
$patientsResult = $conn->query("SELECT id, full_name FROM patients ORDER BY full_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientId       = $_POST['patient_id'];
    $chiefComplaint  = $_POST['chief_complaint'];
    $vitalSigns      = $_POST['vital_signs'];
    $diagnosis       = $_POST['diagnosis'];
    $treatmentPlan   = $_POST['treatment_plan'];

    // Combine inputs for objective
    $objective = "Vital Signs: " . $vitalSigns;

    // Insert into the soap_notes table using your correct schema
    $sql = "INSERT INTO soap_notes (patient_id, subjective, objective, assessment, plan) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $patientId, $chiefComplaint, $objective, $diagnosis, $treatmentPlan);

    if ($stmt->execute()) {
        echo "<script>alert('SOAP Note saved successfully!'); window.location.href='records.php';</script>";
    } else {
        echo "<script>alert('Error saving SOAP Note');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>SOAP Notes</title>

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
            justify-content: space-between; 
            align-items: center;
        }

        .top {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .patients select {
            font-size: 17px;
            padding: 8px;
            border-radius: 12px;
            width: 300px;
            margin-bottom: 20px;
        }

        .main {
            background-color: #C0D7E2FF;
            width: 100%;
            display: flex;
            justify-content: center;
            border: 1 px solid white;
            border-radius: 15px;    
        }

        .container {
            background-color: #E8F3F8FF;
            padding: 30px 40px;
            border: 1px solid gray;
            border-radius: 15px;
            width: 600px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container h2{
            color: #176B87;
            font-size: 30px;
        }

        .forms {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
        }

        .forms label {
            font-weight: 600;
            margin-top: 20px;
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
            margin-top: 20px;
            grid-column: span 2; 
        }

        .buttons button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid gray;
            border-radius: 10px;
            box-shadow: 3px 2px 5px rgba(0, 0, 0, 0.2);
            width: 150px;; 
        }

        .buttons #save {
            background-color: #0B9C2AFF;
            color: white;
        }

        .buttons #save:hover {
            background-color: #046E16FF;
        }

        .content-wrapper {
            display: grid;
            grid-template-columns: repeat(2, auto);
            grid-template-rows: repeat(2, auto);
            gap: 50px;
            width: 100%;
            padding: 60px 0px;
            justify-content: center;
            align-items: center;
            align-content: center;
        }

    </style>
</head>
<body>
<div class="sidebar">
    <div class="profile">
        <div class="profile-icon">
            <img src="<?php echo $profileImage; ?>" alt="Profile Image">
        </div>
        <div class="profile-name"><?php echo $adminName; ?></div>
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
        <h1>SOAP NOTES</h1>
    </header>

    <div class="top">
    <form method="POST" action="">
            <!-- Patient Selection -->
            <div class="patients">
                <select name="patient_id" id="patient_id" required>
                    <option value="" selected disabled>Select a patient</option>
                    <?php
                    if ($patientsResult->num_rows > 0) {
                        while ($patient = $patientsResult->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($patient['id']) . "'>" . htmlspecialchars($patient['full_name']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
    </div>

    <section>
        <div class="main">
            <div class="content-wrapper">
                <div class="container">
                    <h2>Subjective</h2>
                    <div class="forms">
                        <label for="chief_complaint">Chief Complaint:</label>
                        <input type="text" id="chief_complaint" name="chief_complaint" placeholder="e.g., Headache">
                        <label for="hpi">Allergies:</label>
                        <input type="text" id="hpi" name="hpi" placeholder="e.g., Penicillin">
                    </div>
                </div>

                <div class="container">
                    <h2>Objective</h2>
                    <div class="forms">
                        <label for="vital_signs">Vital Signs:</label>
                        <input type="text" id="vital_signs" name="vital_signs" placeholder="e.g., BP: 120/80">
                        <label for="physical_exam">Physical Examination:</label>
                        <input type="text" id="physical_exam" name="physical_exam" placeholder="e.g., Normal heart sounds">
                    </div>
                </div>

                <div class="container">
                    <h2>Assessment</h2>
                    <div class="forms">
                        <label for="diagnosis">Diagnosis:</label>
                        <input type="text" id="diagnosis" name="diagnosis" placeholder="e.g., Hypertension">
                    </div>
                </div>

                <div class="container">
                    <h2>Plan</h2>
                    <div class="forms">
                        <label for="treatment_plan">Treatment Plan:</label>
                        <input type="text" id="treatment_plan" name="treatment_plan" placeholder="e.g., Prescribe medication">
                    </div>
                </div>

                <div class="buttons">
                    <button id="save">Save</button>
                </div>
            </div>
            
        </div>
    </section>
</div>
</body>
</html>