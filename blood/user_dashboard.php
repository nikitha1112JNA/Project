<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Blood Donation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { 
            background: linear-gradient(45deg, #ffcccc, #f8f9fa, #cce5ff);
            background-size: 300% 300%;
            animation: gradientPulse 10s ease infinite;
            padding: 20px;
            font-family: 'Arial', sans-serif;
            overflow-x: hidden;
        }

        /* Container Styling */
        .container {
            max-width: 1200px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            box-shadow: 0 15px 40px rgba(220, 53, 69, 0.2);
            animation: containerGlow 2s ease infinite alternate;
            position: relative;
        }

        /* Heading and Text */
        h2.text-danger {
            font-weight: 900;
            text-shadow: 0 0 10px rgba(220, 53, 69, 0.6);
            animation: pulseText 1.5s ease infinite;
            letter-spacing: 2px;
        }
        p.text-center {
            color: #444;
            font-size: 1.2rem;
            animation: fadeInScale 1.8s ease;
            text-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        /* Logout Button */
        .btn-danger { 
            background: linear-gradient(135deg, #dc3545, #b31222);
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            transition: all 0.4s ease;
            box-shadow: 0 5px 20px rgba(220, 53, 69, 0.4);
            position: relative;
            overflow: hidden;
        }
        .btn-danger:hover { 
            background: linear-gradient(135deg, #c82333, #a01120);
            transform: scale(1.1) rotate(2deg);
            box-shadow: 0 10px 30px rgba(220, 53, 69, 0.6);
        }
        .btn-danger::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.5s ease, height 0.5s ease;
        }
        .btn-danger:hover::after {
            width: 200px;
            height: 200px;
        }

        /* Module Cards */
        .module-card { 
            transition: all 0.4s ease-in-out;
            cursor: pointer; 
            min-height: 150px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: linear-gradient(145deg, #ffffff, #ffe6e6);
            border: 3px solid #dc3545;
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            text-decoration: none;
            position: relative;
            overflow: hidden;
            animation: cardPop 0.8s ease forwards;
        }
        .module-card:hover { 
            transform: scale(1.1) translateY(-10px) rotate(3deg);
            box-shadow: 0 15px 35px rgba(220, 53, 69, 0.5);
            border-color: #b31222;
        }
        .module-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(220, 53, 69, 0.2) 0%, transparent 70%);
            animation: rotateShine 5s linear infinite;
        }
        .module-card::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(220, 53, 69, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s ease, height 0.6s ease;
        }
        .module-card:hover::after {
            width: 400px;
            height: 400px;
        }
        .module-card h5 { 
            color: #dc3545;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            z-index: 1;
            transition: all 0.4s ease;
            text-shadow: 0 0 8px rgba(220, 53, 69, 0.4);
            animation: textGlow 2s ease infinite alternate;
        }
        .module-card:hover h5 {
            color: #b31222;
            transform: scale(1.15);
        }

        /* Row Spacing */
        .row.g-4 {
            margin-top: 30px;
        }

        /* Animations */
        @keyframes gradientPulse {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }
        @keyframes containerGlow {
            from { box-shadow: 0 15px 40px rgba(220, 53, 69, 0.2); }
            to { box-shadow: 0 15px 40px rgba(220, 53, 69, 0.4); }
        }
        @keyframes pulseText {
            0% { transform: scale(1); text-shadow: 0 0 10px rgba(220, 53, 69, 0.6); }
            50% { transform: scale(1.05); text-shadow: 0 0 15px rgba(220, 53, 69, 0.8); }
            100% { transform: scale(1); text-shadow: 0 0 10px rgba(220, 53, 69, 0.6); }
        }
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes cardPop {
            0% { opacity: 0; transform: scale(0.7) rotate(-5deg); }
            60% { opacity: 1; transform: scale(1.05) rotate(2deg); }
            100% { transform: scale(1) rotate(0deg); }
        }
        @keyframes rotateShine {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes textGlow {
            from { text-shadow: 0 0 8px rgba(220, 53, 69, 0.4); }
            to { text-shadow: 0 0 15px rgba(220, 53, 69, 0.7); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            .module-card {
                min-height: 130px;
                margin-bottom: 20px;
            }
            h2.text-danger {
                font-size: 1.6rem;
            }
            .btn-danger {
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center text-danger">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
        <p class="text-center">Your contribution can save lives. Explore the options below!</p>
        <div class="text-center mb-4">
            <a href="index.php" class="btn btn-danger">Logout</a>
        </div>

        <!-- Modules in Row-Wise Layout -->
        <div class="row g-4">
            <div class="col-md-4">
                <a href="donate_blood.php" class="module-card p-3">
                    <h5>Register Donor</h5>
                </a>
            </div>
            <div class="col-md-4">
                <a href="available_blood.php" class="module-card p-3">
                    <h5>Available Donor</h5>
                </a>
            </div>
            <div class="col-md-4">
                <a href="request_blood.php" class="module-card p-3">
                    <h5>Request Blood</h5>
                </a>
            </div>
            <div class="col-md-4">
                <a href="donation_history.php" class="module-card p-3">
                    <h5>Donation History</h5>
                </a>
            </div>
            <div class="col-md-4">
                <a href="profile.php" class="module-card p-3">
                    <h5>Profile</h5>
                </a>
            </div>
            <div class="col-md-4">
                <a href="all_requests.php" class="module-card p-3">
                    <h5>All Requests</h5>
                </a>
            </div>
        </div>
    </div>
</body>
</html>