<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$servername = "localhost"; // Change if necessary
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$database = "blood_donation_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all donors from the database
$query = "SELECT * FROM donors";
$result = $conn->query($query);

// Fetch total count of donors
$total_donors_query = "SELECT COUNT(*) AS total FROM donors";
$total_donors_result = $conn->query($total_donors_query);
$total_donors = $total_donors_result->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Donors</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96c93d);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            font-family: 'Poppins', sans-serif;
            color: #333;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(135deg, #dc3545, #b71c1c);
            color: white;
            padding-top: 20px;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2);
            transition: width 0.3s ease;
        }
        .sidebar:hover {
            width: 260px;
        }
        .sidebar h4 {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            animation: fadeInDown 1s ease;
        }
        .sidebar .btn {
            width: 90%;
            margin: 10px auto;
            background: white;
            color: #dc3545;
            border: none;
            padding: 12px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .sidebar .btn:hover {
            background: #f8f9fa;
            color: #b71c1c;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 40px;
        }
        h2.text-danger {
            font-weight: 700;
            text-shadow: 2px 2px 5px rgba(220, 53, 69, 0.3);
            animation: bounceIn 1s ease;
        }

        /* Total Donors Card */
        .total-card {
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 30px;
            animation: cardPop 1s ease;
        }
        .total-card h5 {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 10px;
        }
        .total-card .count {
            font-size: 2.5rem;
            font-weight: 700;
            color: #dc3545;
            animation: scaleUp 1.5s ease infinite alternate;
        }

        /* Table Container */
        .table-container {
            margin-top: 20px;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease;
        }
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead {
            background: linear-gradient(90deg, #dc3545, #b71c1c);
            color: white;
        }
        .table tbody tr {
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: #f1f1f1;
            transform: scale(1.01);
        }
        .table td, .table th {
            vertical-align: middle;
        }

        /* Animations */
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounceIn {
            0% { opacity: 0; transform: scale(0.3); }
            50% { opacity: 1; transform: scale(1.05); }
            70% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }
        @keyframes cardPop {
            0% { opacity: 0; transform: scale(0.8); }
            60% { opacity: 1; transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        @keyframes scaleUp {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 210px; padding: 20px; }
            .total-card .count { font-size: 2rem; }
            .table-container { padding: 10px; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4">Admin Panel</h4>
        <button class="btn" onclick="location.href='admin_dashboard.php'">Dashboard</button>
        <button class="btn" onclick="location.href='manage_donors.php'">Manage Donors</button>
        <button class="btn" onclick="location.href='manage_requests.php'">Manage Requests</button>
        <button class="btn" onclick="location.href='reports.php'">Reports</button>
        <button class="btn" onclick="location.href='index.php'">Logout</button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container mt-4">
            <h2 class="text-center text-danger">Manage Donors</h2>

            <!-- Total Donors Card -->
            <div class="total-card">
                <h5>Total Donors</h5>
                <div class="count"><?php echo $total_donors; ?></div>
            </div>

            <!-- Donors Table -->
            <div class="table-container">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Blood Group</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['blood_group']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No donors found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>