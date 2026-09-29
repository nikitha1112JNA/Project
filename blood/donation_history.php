<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["email"])) {
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

    // Sanitize email input
    $email = trim($_POST["email"]);

    // Fetch all donor details from the `donations` table based on email
    $sql = "SELECT id, request_id, donor_name, age, email, phone, blood_group, donate_date FROM donations WHERE email = ? ORDER BY donate_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $donationHistory = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $donationHistory .= "<tr>
                <td>" . htmlspecialchars($row['id']) . "</td>
                <td>" . htmlspecialchars($row['request_id']) . "</td>
                <td>" . htmlspecialchars($row['donor_name']) . "</td>
                <td>" . htmlspecialchars($row['age']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>" . htmlspecialchars($row['blood_group']) . "</td>
                <td>" . htmlspecialchars($row['donate_date']) . "</td>
            </tr>";
        }
    } else {
        $donationHistory = "<tr><td colspan='8' class='text-center'>No donations found</td></tr>";
    }

    echo $donationHistory;
    $stmt->close();
    $conn->close();
    exit(); // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation History - Blood Donation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .table-container { 
            max-height: 400px; overflow-y: auto; background-color: #fff; 
            border: 1px solid #dc3545; border-radius: 5px; padding: 10px; 
            max-width: 800px; margin: 0 auto; 
        }
        .search-box { max-width: 400px; margin: 0 auto; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center text-danger">Check Donation History</h2>

        <!-- Email Input Form -->
        <div class="search-box mt-4 text-center">
            <input type="email" id="donorEmail" class="form-control" placeholder="Enter Donor Email">
            <button class="btn btn-primary mt-2" id="checkHistory">Check</button>
        </div>

        <!-- Donation History Table -->
        <div class="table-container mt-4" id="donationTable" style="display: none;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Request ID</th>
                        <th>Donor Name</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Blood Group</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody id="donationData">
                    <!-- Donation records will be displayed here -->
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="user_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#checkHistory").click(function() {
                var email = $("#donorEmail").val().trim();
                if (email === "") {
                    alert("Please enter an email!");
                    return;
                }

                $.ajax({
                    url: "", // Same file
                    type: "POST",
                    data: { email: email },
                    success: function(response) {
                        if (response.trim() !== "") {
                            $("#donationData").html(response);
                            $("#donationTable").show();
                        } else {
                            alert("No data found.");
                        }
                    },
                    error: function() {
                        alert("Error retrieving data. Please try again.");
                    }
                });
            });
        });
    </script>
</body>
</html>
