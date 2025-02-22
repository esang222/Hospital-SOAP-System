<?php
$profileImage = 'img/hehe.jpg'; 
$adminName = 'Admin Name'; 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospital_soap_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = '';
$editPatient = null;
$search = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM patients WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $successMessage = 'Patient successfully deleted.';
    } else {
        $successMessage = "Error deleting record: " . $conn->error;
    }
    $stmt->close();
}

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM patients WHERE id=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editPatient = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $sex = $_POST['sex'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $age = $_POST['age'];

    $sql = "UPDATE patients SET full_name=?, sex=?, dob=?, contact=?, age=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $full_name, $sex, $dob, $contact, $age, $id);

    if ($stmt->execute()) {
        $successMessage = 'Patient details updated successfully.';
    } else {
        $successMessage = "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

$sql = "SELECT id, full_name, sex, dob, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age, contact FROM patients";
if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $sql .= " WHERE full_name LIKE ?";
}
$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $search_param = '%' . $search . '%';
    $stmt->bind_param("s", $search_param);
}
$stmt->execute();
$result = $stmt->get_result();
$patients = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <form action="patients.php" method="GET" style="display:inline;">
                    <input type="text" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i>Search</button>
                </form>
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
                <button class="edit" data-id="<?php echo $patient['id']; ?>" data-full_name="<?php echo $patient['full_name']; ?>" data-sex="<?php echo $patient['sex']; ?>" data-dob="<?php echo $patient['dob']; ?>" data-age="<?php echo $patient['age']; ?>" data-contact="<?php echo $patient['contact']; ?>">Edit</button>
                <form action="patients.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $patient['id']; ?>">
                    <button type="submit" name="delete" class="delete">Delete</button>
                </form>
                <form action="addappointment.php" method="GET" style="display:inline;">
                    <input type="hidden" name="patient_id" value="<?php echo $patient['id']; ?>">
                    <input type="hidden" name="full_name" value="<?php echo $patient['full_name']; ?>">
                    <input type="hidden" name="contact" value="<?php echo $patient['contact']; ?>">
                    <button type="submit" class="add-appointment">Add Appointment</button>
                </form>
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
    <div class="notification" id="notification"><?php echo htmlspecialchars($successMessage); ?></div>

    <div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Patient</h2>
        <form method="POST" action="patients.php">
            <input type="hidden" name="id" id="edit-id">
            <label>Full Name:</label>
            <input type="text" name="full_name" id="edit-full_name" required>
            <label>Sex:</label>
            <select name="sex" id="edit-sex">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <label>Date of Birth:</label>
            <input type="date" name="dob" id="edit-dob" required>
            <label>Age:</label>
            <input type="number" name="age" id="edit-age" required>
            <label>Contact:</label>
            <input type="text" name="contact" id="edit-contact" required>
            <button type="submit" name="update">Update</button>
        </form>
    </div>
</div>

<script>
    var modal = document.getElementById("editModal");

    var btns = document.getElementsByClassName("edit");

    var span = document.getElementsByClassName("close")[0];

    for (let btn of btns) {
        btn.onclick = function() {
            modal.style.display = "block";
            document.getElementById('edit-id').value = this.getAttribute('data-id');
            document.getElementById('edit-full_name').value = this.getAttribute('data-full_name');
            document.getElementById('edit-sex').value = this.getAttribute('data-sex');
            document.getElementById('edit-dob').value = this.getAttribute('data-dob');
            document.getElementById('edit-age').value = this.getAttribute('data-age');
            document.getElementById('edit-contact').value = this.getAttribute('data-contact');
        }
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function showNotification(message) {
        var notification = document.getElementById("notification");
        notification.innerText = message;
        notification.style.display = "block";
        setTimeout(function() {
            notification.style.display = "none";
        }, 2000); 
    }


    <?php if (!empty($successMessage)): ?>
        showNotification("<?php echo $successMessage; ?>");
    <?php endif; ?>
</script>
</body>
</html>