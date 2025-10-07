<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../index.php');
    exit();
 }

 require_once "../database.php";

 $admin_name = $_SESSION['username'] ?? "Admin";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar+Sidebar</title>
    <link rel="stylesheet" href="../assets/css/admin.css"> <!-- updated path -->
</head>
<body>
    <header>
        <div class="left">
            <div class="menu-container">
                <div class="menu" id="Menu">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            <div class="brand">
                <img src="icon/campay.jpg" alt="icon-udemy" class="logo">
                <span class="name">SRK</span>

            </div>
            </div>
        </div>
        <div class="right">
            <a href="#" class="icons-header">
                <img src="icon/chat.svg" alt="chat">

            </a>
            <a href="#" class="icons-header">
                <img src="icon/question.svg" alt="question">
                
            </a>
            <a href="#" class="icons-header">
                <img src="icon/notification.svg" alt="notification">
            </a>
            <img src="icons8-user-96.png" alt="img-user" class="user">

        </div>
    </header>
    <div class="Sidebar" id="Sidebar">
        <nav>
            <ul>
                <li>
                    <a href="#" class="search">
                        <img src="icon/search.svg"  alt="">
                        <span>Search</span>
                    </a>
                </li>
                <li>
                    <a href="dashboard.php" class="selected">
                        <img src="icon/home.svg"  alt="">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="employees.php">
                        <img src="icon/employee-group-svgrepo-com.svg"  alt="">
                        <span>Employees</span>
                    </a>
                </li>
                <li>
                    <a href="departments.php">
                        <img src="icon/store-svgrepo-com (1).svg"  alt="">
                        <span>Departments</span>
                    </a>
                </li>
                <li>
                    <a href="attendances.php">
                        <img src="icon/classlist-svgrepo-com (1).svg"  alt="">
                        <span>Attendances</span>
                    </a>
                </li>
                <li>
                    <a href="settings.php">
                        <img src="icon/setting-svgrepo-com (1).svg"  alt="">
                        <span>Settings</span>
                    </a>
                </li>
                <li>
                    <a href="abouts.php">
                        <img src="icon/about-description-help-svgrepo-com.svg"  alt="">
                        <span>Abouts</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php">
                        <img src="icon/logout-svgrepo-com (2).svg"  alt="">
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <main class="main-header" id="main">
        <div class="header">
            <div class="header-left">
                <h2 class="welcome-message">
                    Welcome, <span class="name" id="manager-name"><?= htmlspecialchars($admin_name); ?></span>
                </h2>
            </div>
            <div class="header-right">
                <span id="current-day"></span>,
                <span id="current-time"></span>
            </div>
        </div>

        <style>
        /* Header layout */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            border-radius: 12px;
            background-color: #f5f7fa;
            border-bottom: 2px solid #e0e0e0;
            font-family: 'Poppins', sans-serif;
        }

        /* Welcome message */
        .welcome-message {
            font-size: 28px;
            margin: 0;
            background: linear-gradient(90deg, #4facfe, #00f2fe);
            color: white;
            padding: 10px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            letter-spacing: 1px;
        }

        /* Admin name highlight */
        .welcome-message .name {
            color: #ffeb3b; /* more visible contrast */
            font-weight: bold;
            text-transform: capitalize;
        }

        /* Right side: date and time */
        .header-right {
            font-size: 16px;
            color: #555;
            text-align: right;
        }

        /* Responsive for smaller screens */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-right {
                margin-top: 10px;
                text-align: left;
            }
        }
        </style>
    </main>
    <?php
    include "footer.php";
    ?>
    <script src="../assets/js/admin.js"></script>
    <script>
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long' };
        const dayName = now.toLocaleDateString('en-US', options);
        const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

        document.getElementById("current-day").textContent = dayName;
        document.getElementById("current-time").textContent = time;
    }

    // Initial load and update every second
    updateDateTime();
    setInterval(updateDateTime, 1000);
    </script>
</body>
</html>