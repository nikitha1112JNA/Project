<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: #f4f7fa;
            font-family: 'Poppins', sans-serif;
            color: #333;
            overflow-x: hidden;
        }

        /* Header Section */
        .header-section {
            background: linear-gradient(135deg, #d32f2f, #b71c1c);
            padding: 40px 0;
            text-align: center;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            animation: subtleWave 10s ease infinite;
        }
        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            animation: fadeInDown 1s ease;
        }
        .header-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            animation: fadeInUp 1s ease 0.3s forwards;
        }

        /* Main Content */
        .main-content {
            padding: 60px 0;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 25px rgba(211, 47, 47, 0.15);
        }
        .card i {
            font-size: 2.5rem;
            color: #d32f2f;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }
        .card:hover i {
            transform: scale(1.2);
        }
        .card h5 {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        .btn-custom {
            background: #d32f2f;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-custom:hover {
            background: #b71c1c;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(211, 47, 47, 0.3);
        }
        .btn-custom::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease;
        }
        .btn-custom:hover::after {
            width: 200px;
            height: 200px;
        }

        /* Footer */
        .footer {
            background: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes subtleWave {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 2rem;
            }
            .header-section p {
                font-size: 1rem;
            }
            .main-content {
                padding: 40px 0;
            }
            .card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <h1>Blood Donation System</h1>
            <p>Saving Lives, One Drop at a Time</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card">
                        <i class="fas fa-user-shield"></i>
                        <h5>Admin Login</h5>
                        <button class="btn btn-custom" onclick="location.href='admin_login.php'">Go to Admin</button>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card">
                        <i class="fas fa-user-plus"></i>
                        <h5>User Register</h5>
                        <button class="btn btn-custom" onclick="location.href='user_register.php'">Register Now</button>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card">
                        <i class="fas fa-sign-in-alt"></i>
                        <h5>User Login</h5>
                        <button class="btn btn-custom" onclick="location.href='user_login.php'">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2025 Blood Donation System. All Rights Reserved.</p>
    </div>

</body>
</html>