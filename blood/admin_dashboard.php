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

// Fetch blood group data for pie chart
$blood_groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
$blood_group_data = array_fill(0, count($blood_groups), 0); // Initialize all to 0

$query = "SELECT blood_group, COUNT(*) as count FROM donors GROUP BY blood_group";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $index = array_search($row['blood_group'], $blood_groups);
        if ($index !== false) {
            $blood_group_data[$index] = (int)$row['count'];
        }
    }
}

// Convert blood group data to JSON for JavaScript
$blood_group_json = json_encode($blood_group_data);

// Fetch donation trend data for line chart (last 6 months)
$months = [];
$donation_data = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('M', strtotime("-$i months"));
    $months[] = $month;
    
    $start_date = date('Y-m-01 00:00:00', strtotime("-$i months"));
    $end_date = date('Y-m-t 23:59:59', strtotime("-$i months"));
    
    $query = "SELECT COUNT(*) as total FROM blood_requests WHERE status='Completed' AND request_date BETWEEN '$start_date' AND '$end_date'";
    $result = $conn->query($query);
    $donation_data[] = (int)$result->fetch_assoc()['total'];
}

// Convert donation data to JSON for JavaScript
$months_json = json_encode($months);
$donation_data_json = json_encode($donation_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { 
            height: 100vh; width: 250px; 
            position: fixed; top: 0; left: 0; 
            background-color: #dc3545; 
            color: white; padding-top: 20px;
        }
        .sidebar .btn { width: 90%; margin: 10px auto; background: white; color: #dc3545; }
        .sidebar .btn:hover { background: #f8f9fa; }
        .main-content { margin-left: 260px; padding: 20px; }
        .dashboard-card { transition: transform 0.3s; }
        .dashboard-card:hover { transform: scale(1.05); }
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
            <h2 class="text-center text-danger">Admin Dashboard</h2>

            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card dashboard-card p-3 bg-light">
                        <h5>Total Donors</h5>
                        <h3 class="text-primary">
                            <?php 
                            $result = $conn->query("SELECT COUNT(*) AS total FROM donors");
                            echo $result->fetch_assoc()['total'];
                            ?>
                        </h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card dashboard-card p-3 bg-light">
                        <h5>Blood Requests</h5>
                        <h3 class="text-warning">
                            <?php 
                            $result = $conn->query("SELECT COUNT(*) AS total FROM blood_requests WHERE status='Pending'");
                            echo $result->fetch_assoc()['total'];
                            ?>
                        </h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card dashboard-card p-3 bg-light">
                        <h5>Successful Donations</h5>
                        <h3 class="text-success">
                            <?php 
                            $result = $conn->query("SELECT COUNT(*) AS total FROM blood_requests WHERE status='Completed'");
                            echo $result->fetch_assoc()['total'];
                            ?>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <canvas id="bloodGroupChart" class="chart-container"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="donationTrendChart" class="chart-container"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Pie chart for blood groups
        const bloodGroupCtx = document.getElementById('bloodGroupChart').getContext('2d');
        const bloodGroupData = <?php echo $blood_group_json; ?>;
        new Chart(bloodGroupCtx, {
            type: 'pie',
            data: {
                labels: ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
                datasets: [{
                    data: bloodGroupData,
                    backgroundColor: ['red', 'pink', 'blue', 'purple', 'orange', 'green', 'cyan', 'gray']
                }]
            }
        });

        // Line chart for donation trend
        const donationTrendCtx = document.getElementById('donationTrendChart').getContext('2d');
        const donationLabels = <?php echo $months_json; ?>;
        const donationData = <?php echo $donation_data_json; ?>;
        new Chart(donationTrendCtx, {
            type: 'line',
            data: {
                labels: donationLabels,
                datasets: [{
                    label: 'Donations Over Time',
                    data: donationData,
                    borderColor: 'red',
                    fill: false
                }]
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>