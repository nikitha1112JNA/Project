<?php
session_start();
include 'db_connection.php'; // Include your database connection file

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $name = $_POST['name'];
    $email = $_POST['email'];
    $blood_group = $_POST['blood_group'];
    $urgency = $_POST['urgency'];
    $contact = $_POST['contact'];
    $reason = $_POST['reason'];

    // Insert into blood_requests table
    $sql = "INSERT INTO blood_requests (name, email, blood_group, urgency, contact, reason) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $blood_group, $urgency, $contact, $reason);

    if ($stmt->execute()) {
        echo "<script>
                alert('Request submitted successfully!');
                window.location.href = 'request_blood.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Error submitting request!');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Blood - Blood Donation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .form-container { padding: 20px; background-color: #fff; border: 1px solid #dc3545; border-radius: 5px; max-width: 600px; margin: 0 auto; }
        .btn-danger { background-color: #dc3545; border: none; }
        .btn-danger:hover { background-color: #c82333; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center text-danger">Request Blood</h2>
        <div class="form-container mt-4">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="bloodGroup" class="form-label">Blood Group</label>
                    <select class="form-control" id="bloodGroup" name="blood_group" required>
                        <option value="">Select</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="urgency" class="form-label">Urgency</label>
                    <select class="form-control" id="urgency" name="urgency" required>
                        <option value="">Select</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="contact" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact" name="contact" required>
                </div>
                <div class="mb-3">
                    <label for="reason" class="form-label">Reason for Request</label>
                    <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger w-100">Submit Request</button>
            </form>
        </div>
        <div class="text-center mt-4">
            <a href="user_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
