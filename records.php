<?php
include 'config.php'; 

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchQuery = "";
$searchParams = [];

if (!empty($search)) {
    $searchQuery = "WHERE p.full_name LIKE ? OR s.subjective LIKE ? OR s.objective LIKE ? OR s.assessment LIKE ? OR s.plan LIKE ?";
    $searchTerm = "%$search%";
    $searchParams = [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm];
}

// Fetch SOAP notes with search functionality
$sql = "SELECT 
            s.id, 
            p.full_name, 
            s.subjective, 
            s.objective, 
            s.assessment, 
            s.plan, 
            s.created_at 
        FROM soap_notes s
        INNER JOIN patients p ON s.patient_id = p.id
        $searchQuery
        ORDER BY s.created_at DESC";

$stmt = $conn->prepare($sql);

if (!empty($search)) {
    $stmt->bind_param("sssss", ...$searchParams);
}

$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die("Error fetching SOAP notes: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Records</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Lexend", serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #f9f9f9;
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

        .search-bar {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-top: 30px;
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

        .table-container {
            border-radius: 10px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.4); 
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid gray;
            text-align: center;
        }

        th, td {
            padding: 12px;
        }

        th {
            background-color: #B2DBED;
            color: black;
        }

        .view-btn {
            background-color: #2d98da;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .view-btn:hover {
            background-color: #1e66a7;
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
            <li><i class="fa-solid fa-house"></i> <a href="dashboard.php">Dashboard</a></li>
            <li><i class="fa-solid fa-hospital-user"></i> <a href="patients.php">Patient Management</a></li>
            <li><i class="fa-solid fa-calendar-check"></i> <a href="appointment.php">Appointments</a></li>
            <li><i class="fa-solid fa-notes-medical"></i> <a href="SOAP.php">SOAP Notes</a></li>
            <li><i class="fa-solid fa-laptop-medical"></i> <a href="records.php">Records</a></li>
            <li><i class="fa-solid fa-gear"></i> <a href="#">Settings</a></li>
            <li>
                <i class="fa-solid fa-right-from-bracket" style="color: #ffffff;"></i>
                <a href="login.php?action=logout" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
            </li>
        </ul>
    </aside>
</div>

<div class="main-content">
    <header>
        <h1>SOAP Notes Records</h1>
    </header>
    <div class="search-bar">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i>Search</button>
        </form>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Date</th>
                    <th>Subjective</th>
                    <th>Objective</th>
                    <th>Assessment</th>
                    <th>Plan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["full_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["subjective"]) . "</td>";
                        echo "<td>" . nl2br(htmlspecialchars($row["objective"])) . "</td>";
                        echo "<td>" . htmlspecialchars($row["assessment"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["plan"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No SOAP notes found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
