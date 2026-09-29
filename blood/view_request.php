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

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid Request!'); window.location.href='all_requests.php';</script>";
    exit();
}

$request_id = intval($_GET['id']);

// Fetch request details
$sql = "SELECT * FROM blood_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();

if (!$request) {
    echo "<script>alert('Request not found!'); window.location.href='all_requests.php';</script>";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Blood Request - Blood Donation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .details-container { max-width: 600px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        .btn-back { background-color: #6c757d; color: white; }
        .btn-back:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center text-danger">Blood Request Details</h2>
        <div class="details-container mt-4">
            <table class="table table-bordered">
                <tr>
                    <th>Full Name</th>
                    <td><?php echo htmlspecialchars($request['name']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($request['email']); ?></td>
                </tr>
                <tr>
                    <th>Blood Group</th>
                    <td><?php echo htmlspecialchars($request['blood_group']); ?></td>
                </tr>
                <tr>
                    <th>Urgency</th>
                    <td><?php echo htmlspecialchars($request['urgency']); ?></td>
                </tr>
                <tr>
                    <th>Contact</th>
                    <td><?php echo htmlspecialchars($request['contact']); ?></td>
                </tr>
                <tr>
                    <th>Reason</th>
                    <td><?php echo htmlspecialchars($request['reason']); ?></td>
                </tr>
                <tr>
                    <th>Request Date</th>
                    <td><?php echo htmlspecialchars($request['request_date']); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <?php 
                            if ($request['status'] === 'Completed') {
                                echo '<span class="badge bg-success">Completed</span>';
                            } else {
                                echo '<span class="badge bg-warning">Pending</span>';
                            }
                        ?>
                    </td>
                </tr>
            </table>
            <div class="text-center">
                <a href="all_requests.php" class="btn btn-back">Back to Requests</a>
            </div>
        </div>
    </div>
</body>
</html>
