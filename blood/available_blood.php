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

// Fetch available donors
$sql = "SELECT name, email, blood_group, phone, availability FROM donors";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Donors - Blood Donation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .table-container { max-height: 400px; overflow-y: auto; background-color: #fff; border: 1px solid #dc3545; border-radius: 5px; padding: 10px; max-width: 800px; margin: 0 auto; }
        .search-container { max-width: 400px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center text-danger">Available Donors</h2>
        
        <!-- Search Bar -->
        <div class="search-container text-center mt-3">
            <input type="text" id="searchBloodGroup" class="form-control" placeholder="Search by Blood Group (e.g., A+, B-)">
        </div>

        <div class="table-container mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Blood Group</th>
                        <th>Phone Number</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody id="donorTable">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="blood-group"><?php echo htmlspecialchars($row['blood_group']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['availability']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No donors available</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-4">
            <a href="user_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <script>
        // Search function to filter table by blood group
        document.getElementById('searchBloodGroup').addEventListener('keyup', function() {
            let searchValue = this.value.trim().toUpperCase(); // Get input and convert to uppercase
            let rows = document.querySelectorAll('#donorTable tr');

            rows.forEach(row => {
                let bloodGroup = row.querySelector('.blood-group').textContent.toUpperCase();
                row.style.display = bloodGroup.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
