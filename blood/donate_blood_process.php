<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user_login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "blood_donation_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get request ID
$request_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch blood request details
$sql = "SELECT * FROM blood_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();
$stmt->close();

if (!$request) {
    die("Invalid request ID.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['donate'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $blood_group = $_POST['blood_group'];
    $donate_date = $_POST['donate_date'];

    // Insert donation record
    $insert_sql = "INSERT INTO donations (request_id, donor_name, age, email, phone, blood_group, donate_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("issssss", $request_id, $name, $age, $email, $phone, $blood_group, $donate_date);
    
    if ($stmt->execute()) {
        // Update request status to "Completed"
        $update_sql = "UPDATE blood_requests SET status = 'Completed' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $request_id);
        $update_stmt->execute();
        $update_stmt->close();
        
        echo "<script>alert('Thank you for your donation!'); window.location.href = 'all_requests.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error in processing donation.');</script>";
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
    <title>Donate Blood</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center text-danger">Donate Blood</h2>
        <p class="text-center"><strong>Blood Group Needed:</strong> <?php echo htmlspecialchars($request['blood_group']); ?></p>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Age</label>
                <input type="number" class="form-control" name="age" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Blood Group</label>
                <input type="text" class="form-control" name="blood_group" value="<?php echo htmlspecialchars($request['blood_group']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Donation Date</label>
                <input type="date" class="form-control" name="donate_date" required>
            </div>
            <button type="submit" name="donate" class="btn btn-danger w-100">Confirm Donation</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="all_requests.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
</body>
</html>
