<?php
include 'config.php';

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Handle edit patient
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $sex = $_POST['sex'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];

    $sql = "UPDATE patients SET full_name=?, sex=?, dob=?, contact=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $full_name, $sex, $dob, $contact, $id);

    if ($stmt->execute()) {
        header("Location: patients.php?message=Patient updated successfully");
        exit();
    } else {
        die("Error updating patient: " . $stmt->error);
    }
}

// Handle delete patient
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $sql = "DELETE FROM patients WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: patients.php?message=Patient deleted successfully");
        exit();
    } else {
        die("Error deleting patient: " . $stmt->error);
    }
}

// Fetch patients
$sql = "SELECT p.id, p.full_name, p.sex, p.dob, p.age, p.contact, COUNT(a.id) AS total_appointments
        FROM patients p
        LEFT JOIN appointments a ON p.id = a.patient_id
        WHERE p.full_name LIKE '%$search%'
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
    <title>Patient Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
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
        
        .search-bar {
            display: flex;
            align-items: center;
        }
        .search-bar i {
            margin-right: 10px;
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
            background-color: #09546D;
        }

        .patient-list table { 
            width: 100%; 
            border-collapse: collapse; 
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); 
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
        .action-link {
            text-decoration: none;
            color: #3674B5;
            padding: 8px 12px;
            border-radius: 18px;
            font-size: 17px;
            margin: 0 5px;
            display: inline-block;
            font-weight: 500;
        }
        .action-link.delete {
            color: red;
        }
        .action-link:hover {
            text-decoration: underline;
        }
        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0, 0, 0, 0.7);
            padding-top: 60px; 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
        .modal h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .modal label {
            display: block;
            margin: 10px 0 5px;
        }
        .modal input[type="text"],
        .modal input[type="date"],
        .modal select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .modal button {
            width: 100%;
            padding: 10px;
            background-color: #176B87;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .modal button:hover {
            background-color: #09546D;
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
                <li>
                    <i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>
                    <a href="login.php?action=logout" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
                </li>

            </ul>
        </aside>
    </div>
    <div class="main-content">
        <header>
            <h1>Patient Management</h1>
        </header>
        <nav>
            <div class="search-bar">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i>Search</button>
                </form>
                <a href="addpatient.php"><button type="button"><i class="fa-solid fa-user-plus"></i> Add Patient</button></a>
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
                                <!-- The edit link now includes the "edit" class to trigger the modal -->
                                <a href="#" class="action-link edit" 
                                   data-id="<?php echo htmlspecialchars($patient['id']); ?>" 
                                   data-name="<?php echo htmlspecialchars($patient['full_name']); ?>" 
                                   data-sex="<?php echo htmlspecialchars($patient['sex']); ?>" 
                                   data-dob="<?php echo htmlspecialchars($patient['dob']); ?>" 
                                   data-contact="<?php echo htmlspecialchars($patient['contact']); ?>">Edit</a>
                                <a href="#" class="action-link delete" onclick="confirmDelete(<?php echo htmlspecialchars($patient['id']); ?>)">Delete</a>
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

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Patient</h2>
            <form id="editForm" method="POST" action="">
                <input type="hidden" name="id" id="patientId">
                <label for="fullName">Full Name:</label>
                <input type="text" name="full_name" id="fullName" required>
                <label for="sex">Sex:</label>
                <select name="sex" id="sex" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob" required>
                <label for="contact">Contact:</label>
                <input type="text" name="contact" id="contact" required>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        // When any edit link is clicked, open the modal with prefilled data.
        document.querySelectorAll('.edit').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('patientId').value = this.getAttribute('data-id');
                document.getElementById('fullName').value = this.getAttribute('data-name');
                document.getElementById('sex').value = this.getAttribute('data-sex');
                document.getElementById('dob').value = this.getAttribute('data-dob');
                document.getElementById('contact').value = this.getAttribute('data-contact');
                document.getElementById('editModal').style.display = 'block';
            });
        });

        // Close the modal when clicking the close button
        document.querySelector('.close').onclick = function() {
            document.getElementById('editModal').style.display = 'none';
        };

        // Function to confirm deletion and redirect
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this patient?")) {
                window.location.href = 'patients.php?delete_id=' + id;
            }
        }
    </script>
</body>
</html>
