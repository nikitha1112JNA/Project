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

// Check if email is passed
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Delete donor from database
    $sql = "DELETE FROM donors WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    
    if ($stmt->execute()) {
        echo "<script>alert('Donor record deleted successfully!'); window.location.href='donate_blood.php';</script>";
    } else {
        echo "<script>alert('Error deleting record!');</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request!'); window.location.href='check_donor.php';</script>";
}

$conn->close();
?>
