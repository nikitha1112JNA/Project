<?php
session_start();
include 'db_connection.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: user_login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

// Fetch user data from the register table
$query = "SELECT name, email, phone, address FROM register WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit();
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {
    $new_name = htmlspecialchars($_POST['name']);
    $new_email = htmlspecialchars($_POST['email']);
    $new_phone = htmlspecialchars($_POST['phone']);
    $new_address = htmlspecialchars($_POST['address']);

    // Update query
    $update_query = "UPDATE register SET name = ?, email = ?, phone = ?, address = ? WHERE email = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssss", $new_name, $new_email, $new_phone, $new_address, $user_email);

    if ($stmt->execute()) {
        $success_message = "Profile updated successfully!";
        $_SESSION['user_email'] = $new_email; // Update session email if changed
        $user = ['name' => $new_name, 'email' => $new_email, 'phone' => $new_phone, 'address' => $new_address];
    } else {
        $error_message = "Error updating profile.";
    }
}

// Handle account deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete'])) {
    $delete_query = "DELETE FROM register WHERE email = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("s", $user_email);

    if ($stmt->execute()) {
        session_destroy();
        header("Location: user_register.php");
        exit();
    } else {
        $error_message = "Error deleting account.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center text-danger">User Profile</h2>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <div class="card p-4 shadow-sm">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" name="address" rows="3" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" name="update" class="btn btn-success">Update Profile</button>
                    <button type="submit" name="delete" class="btn btn-danger ms-3" onclick="return confirm('Are you sure you want to delete your account?');">Delete Account</button>
                    <a href="user_dashboard.php" class="btn btn-secondary ms-3">Back</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
