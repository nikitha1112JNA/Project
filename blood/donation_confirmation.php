<?php
session_start();

// Check if donor data exists in the session
if (!isset($_SESSION['donor_data'])) {
    header("Location: donate_blood.php");
    exit();
}

$donor = $_SESSION['donor_data'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .confirmation-box { padding: 20px; background-color: #fff; border: 1px solid #28a745; border-radius: 5px; max-width: 600px; margin: 0 auto; }
        .btn-success { background-color: #28a745; border: none; }
        .btn-success:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center text-success">Donation Successful!</h2>
        <div class="confirmation-box mt-4">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($donor['name']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($donor['age']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($donor['phone']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($donor['email']); ?></p>
            <p><strong>Blood Group:</strong> <?php echo htmlspecialchars($donor['blood_group']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($donor['address']); ?></p>
            <p><strong>Availability:</strong> <?php echo htmlspecialchars($donor['availability']); ?></p>
            <div class="text-center mt-3">
                <a href="user_dashboard.php" class="btn btn-success">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
