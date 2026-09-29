<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user_login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$dbname = "blood_donation_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch all blood requests
$sql = "SELECT * FROM blood_requests ORDER BY request_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Blood Requests - Blood Donation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .table-container { max-height: 500px; overflow-y: auto; background-color: #fff; border: 1px solid #dc3545; border-radius: 5px; padding: 10px; max-width: 1000px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center text-danger">All Blood Requests</h2>
        <div class="table-container mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Blood Group</th>
                        <th>Urgency</th>    
                        <th>Contact</th>
                        <th>Reason</th>
                        <th>Request Date</th>
                        <th>Action</th>
                        <th>Process</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['blood_group']); ?></td>
                                <td><?php echo htmlspecialchars($row['urgency']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                <td><?php echo htmlspecialchars($row['reason']); ?></td>
                                <td><?php echo htmlspecialchars($row['request_date']); ?></td>
                                <td>
                                    <a href="view_request.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View</a>
                                </td>
                                <td>
    <?php if ($row['status'] === 'Completed'): ?>
        <button class="btn btn-success btn-sm" disabled>Donated</button>
    <?php else: ?>
        <a href="donate_blood_process.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Donate</a>
    <?php endif; ?>
</td>

                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">No blood requests found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-4">
            <a href="user_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
