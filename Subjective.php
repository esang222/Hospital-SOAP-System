<?php
$profileImage = 'img/hehe.jpg';
$adminName = 'Admin01';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>SOAP NOTES</title>

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

        section {
            display: flex;
            justify-content: space-between; 
            align-items: center;
            /* margin-top: 30px; */
        }

        .top{
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .soapnote h2{
            font-size: 40px;
            font-weight: 600;
            margin-left: 20px;
        }

        .patients select{
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
            justify-content: space-between; 
            margin-top: 20px; 
        }

        .buttons button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid gray;
            border-radius: 10px;
            box-shadow: 3px 2px 5px rgba(0, 0, 0, 0.2);
            width: 100%; 
        }

        .buttons #save {
            background-color: #0B9C2AFF;
            color: white;
        }

        .buttons #save:hover {
            background-color: #046E16FF;
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .soap-buttons {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            width: 100%;
            flex-direction: row;
            align-items: center;
            margin-bottom: 30px;
            gap: 25px;
        }

        .soap-buttons button {
            padding: 10px;
            font-size: 20px;
            border: 1px solid gray;
            border-radius: 10px;
            box-shadow: 3px 2px 5px rgba(0, 0, 0, 0.2);
            width: 100%; 
            background-color: #176B87;
            color: white;
        }

        .soap-buttons button:hover {
            opacity: 0.8; 
        }

        .soap-buttons button#subjective{
            background-color: #8EC3D3FF;
            color: black;
            border: none;

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
            <h1>SOAP NOTES</h1>
        </header>

        <div class="top">
             <div class="soapnote">
                <h2>SUBJECTIVE</h2>
            </div>

            <div class="patients">
                <select id="sex" name="sex" style="margin-left: auto; margin-right: 30px;">
                    <option value="" selected disabled>Select a patient</option>
                </select>
            </div>
        </div>
       
        <section>
            <div class="main">
                <div class="content-wrapper">
                    <div class="container">
                    <div class="forms">
                        <label for="chief_complaint">Chief Complaint:</label>
                        <input type="text" id="chief_complaint" name="chief_complaint" placeholder="e.g., Headache">

                        <label for="hpi">History of Present Illness (HPI):</label>
                        <input type="text" id="hpi" name="hpi" placeholder="e.g., Started 3 days ago, worsens at night">

                        <label for="past_medical_history">Past Medical History:</label>
                        <input type="text" id="past_medical_history" name="past_medical_history" placeholder="e.g., Hypertension, Diabetes">

                        <label for="current_medications">Current Medications:</label>
                        <input type="text" id="current_medications" name="current_medications" placeholder="e.g., Paracetamol, Metformin">

                        <label for="allergies">Allergies:</label>
                        <input type="text" id="allergies" name="allergies" placeholder="e.g., Penicillin">

                        <label for="family_social_history">Family & Social History:</label>
                        <input type="text" id="family_social_history" name="family_social_history" placeholder="e.g., Family history of hypertension">
                    </div>


                        <div class="buttons">
                            <button id="save">Save</button>
                        </div>
                    </div>

                    <div class="soap-buttons">
                        <a href="Subjective.php"><button id="subjective">Subjective</button></a>
                        <a href="Objective.php"><button id="objective">Objective</button></a>
                        <a href="Assessment.php"><button id="assessment">Assessment</button></a>
                        <a href="Plan.php"><button id="plan">Plan</button></a>
                    </div>
                </div>
            </div>
        </section>
</body>
</html>
