<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Change if needed
$password = "";
$dbname = "blood_donation_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$user_email = "";
$donor = null;

// Check if email is entered and process the form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check_email'])) {
    $user_email = $_POST['email'];
    
    $sql = "SELECT * FROM donors WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $donor = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Donor Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .container { max-width: 600px; margin: auto; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center text-danger">Check Donor Registration</h2>
        <div class="card p-3">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Enter Your Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" name="check_email" class="btn btn-primary w-100">Check Registration</button>
            </form>
        </div>
        
        <?php if (!empty($user_email)): ?>
            <?php if ($donor): ?>
                <div class="alert alert-success text-center mt-3">
                    <strong>You are already registered as a donor.</strong>
                </div>
                <div class="card p-3">
                    <h5 class="text-center">Your Donor Details</h5>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($donor['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($donor['email']); ?></p>
                    <p><strong>Age:</strong> <?php echo htmlspecialchars($donor['age']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($donor['phone']); ?></p>
                    <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($donor['blood_group']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($donor['address']); ?></p>
                    <p><strong>Availability:</strong> <?php echo htmlspecialchars($donor['availability']); ?></p>
                    <div class="text-center">
                        <a href="update_donor.php?email=<?php echo urlencode($user_email); ?>" class="btn btn-warning">Update Details</a>
                        <a href="delete_donor.php?email=<?php echo urlencode($user_email); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your registration?')">Delete Registration</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger text-center mt-3">
                    <strong>No registration found for this email.</strong>
                    <br> Please <a href="donor_register.php" class="alert-link">register here</a>.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="user_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
