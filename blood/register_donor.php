<?php
session_start();



$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "blood_donation_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Check if the user is already registered as a donor
$sql = "SELECT * FROM donors WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['message'] = "You are already registered as a donor.";
    header("Location: user_dashboard.php");
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $blood_group = $_POST['blood_group'];
    $address = $_POST['address'];
    $availability = $_POST['availability'];

    $sql = "INSERT INTO donors (user_id, name, age, phone, email, blood_group, address, availability) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isisssss", $user_id, $name, $age, $phone, $email, $blood_group, $address, $availability);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Donor registration successful!";
    } else {
        $_SESSION['message'] = "Error registering donor.";
    }
    header("Location: user_dashboard.php");
    exit();
}

$conn->close();
?>
