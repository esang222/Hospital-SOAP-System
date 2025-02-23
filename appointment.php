<?php
include 'config.php';

// Process deletion if ?delete= is set in the URL
if (isset($_GET['delete'])) {
    $appointmentId = intval($_GET['delete']);
    $deleteSql = "DELETE FROM appointments WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $appointmentId);
    if ($deleteStmt->execute()) {
        header("Location: appointment.php?status=deleted");
        exit();
    } else {
        echo "Error deleting appointment: " . $deleteStmt->error;
    }
    $deleteStmt->close();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch appointments with reason column included
$sql = "SELECT a.id, p.full_name AS patient_name, p.contact, 
               a.doctor_name, a.appointment_date, a.reason, a.status
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        WHERE p.full_name LIKE ?
        ORDER BY a.appointment_date DESC";
$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();

// Update appointment if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];
    $appointment_date = $_POST['appointment_date'];
    $status = $_POST['status'];
    
    // Ensure "Complete" becomes "Completed"
    if ($status === 'Complete') {
        $status = 'Completed';
    }

    $updateSql = "UPDATE appointments SET appointment_date = ?, status = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssi", $appointment_date, $status, $appointment_id);
    
    if ($updateStmt->execute()) {
        header("Location: appointment.php?status=updated");
        exit();
    } else {
        echo "Error: " . $updateStmt->error;
    }
    $updateStmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Management</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4); 

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

        .action-link {
            text-decoration: none;
            color: #3674B5;
            padding: 8px 12px;
            /* border: 1px solid #ccc; */
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

        .text-success {
            color: #EB5B00;
        }

        .text-danger {
            color: red;
        }

        .text-warning {
            color: green;
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
        .modal input[type="datetime-local"],
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
            <h1>Appointments</h1>
        </header>
        <nav>
            <div class="search-bar">
                <form method="GET" action="appointment.php">
                    <input type="text" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i>Search</button>
                </form>
                <a href="addAppointment.php"><button><i class="fa-solid fa-calendar-check"></i>Add Appointment</button></a>
            </div>
        </nav>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient's Name</th>
                    <th>Contact Number</th>
                    <th>Doctor's Name</th>
                    <th>Appointment Date & Time</th>
                    <th>Reason of Appointment</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['contact']); ?></td>
                            <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td class="<?php echo !empty($row['status']) ? (($row['status'] == 'Scheduled') ? 'text-success' : 'text-danger') : 'text-warning'; ?>">
                                <?php echo !empty($row['status']) ? htmlspecialchars($row['status']) : 'Done'; ?>
                            </td>
                            <td>
                                <!-- Replace buttons with link-style actions -->
                                <a href="#" class="action-link" onclick="openModal(<?php echo htmlspecialchars($row['id']); ?>, '<?php echo htmlspecialchars($row['appointment_date']); ?>', '<?php echo htmlspecialchars($row['status']); ?>')">Edit</a>
                                <a href="#" class="action-link delete" onclick="confirmDelete(<?php echo htmlspecialchars($row['id']); ?>)">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No appointments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for editing appointment -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Appointment</h2>
            <form id="editForm" method="POST" action="">
                <input type="hidden" id="appointment_id" name="appointment_id">
                <label for="appointment_date">Date & Time:</label>
                <input type="datetime-local" id="appointment_date" name="appointment_date" required>
                
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="Scheduled">Scheduled</option>
                    <option value="Done">Done</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                
                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <script>
        // Open the edit modal and populate fields
        function openModal(id, date, status) {
            document.getElementById('appointment_id').value = id;
            document.getElementById('appointment_date').value = date;
            document.getElementById('status').value = status && status !== '' ? status : 'Scheduled';
            document.getElementById('editModal').style.display = "block";
        }

        // Close the edit modal
        function closeModal() {
            document.getElementById('editModal').style.display = "none";
        }

        // Confirm and delete an appointment
        function confirmDelete(appointmentId) {
            if (confirm("Are you sure you want to delete this appointment?")) {
                window.location.href = "appointment.php?delete=" + appointmentId;
            }
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
