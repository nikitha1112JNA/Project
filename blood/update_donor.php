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

$donor = null;
$email = isset($_GET['email']) ? $_GET['email'] : '';

if (!empty($email)) {
    // Fetch donor details
    $sql = "SELECT * FROM donors WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $donor = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_donor'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $blood_group = $_POST['blood_group'];
    $address = $_POST['address'];
    $availability = $_POST['availability'];

    // Update query
    $sql = "UPDATE donors SET name=?, age=?, phone=?, blood_group=?, address=?, availability=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssss", $name, $age, $phone, $blood_group, $address, $availability, $email);
    
    if ($stmt->execute()) {
        echo "<script>alert('Donor details updated successfully!'); window.location.href='donate_blood.php';</script>";
    } else {
        echo "<script>alert('Error updating details!');</script>";
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
    <title>Update Donor Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .container { max-width: 600px; margin: auto; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center text-warning">Update Donor Details</h2>
        <div class="card p-3">
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($donor['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Age</label>
                    <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($donor['age']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($donor['phone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Blood Group</label>
                    <input type="text" class="form-control" name="blood_group" value="<?php echo htmlspecialchars($donor['blood_group']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" required><?php echo htmlspecialchars($donor['address']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Availability (Yes/No)</label>
                    <select class="form-control" name="availability" required>
                        <option value="Yes" <?php echo ($donor['availability'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No" <?php echo ($donor['availability'] == 'No') ? 'selected' : ''; ?>>No</option>
                    </select>
                </div>
                <button type="submit" name="update_donor" class="btn btn-success w-100">Update Details</button>
            </form>
        </div>
        <div class="text-center mt-3">
            <a href="donate_blood.php" class="btn btn-secondary">Back</a>
        </div>
    </div>
</body>
</html>
