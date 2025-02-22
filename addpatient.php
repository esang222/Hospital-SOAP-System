<?php 
include 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];

    $full_name = $first_name . ' ' . $last_name;

    $sql = "INSERT INTO patients (full_name, sex, dob, age, contact) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssis", $full_name, $sex, $dob, $age, $contact);

    if ($stmt->execute()) {
        header("Location: addpatient.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    if (isset($_GET['success']) && $_GET['success'] == 1) {
        echo "<script>alert('Patient added successfully!');</script>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <title>Add Patient</title>

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

        .buttons {
            display: flex;
            justify-content: flex-end; 
            gap: 20px; 
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
                <img src="<?php echo $profileImage; ?>" alt="Profile Image">
            </div>
            <div class="profile-name"><?php echo $adminName; ?></div>
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
            <h1>Add Patient</h1>
        </header>
        
        <section>
            <div class="main">
                <div class="container">
                    <form action="addpatient.php" method="POST" class="forms">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" required>

                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>

                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" required>

                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" required>

                        <label for="sex">Sex:</label>
                        <select id="sex" name="sex" required>
                            <option value="" selected disabled>Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>

                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" required>

                        <label for="contact">Contact Number:</label>
                        <input type="tel" id="contact" name="contact" required>

                        <div class="buttons">
                            <a href="patients.php"><button type="button" id="cancel">Cancel</button></a>
                            <button type="submit" id="save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</body>
</html>