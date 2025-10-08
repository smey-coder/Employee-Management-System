<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../database.php";

$admin_name = $_SESSION['username'] ?? "Admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>About</title>
<link rel="stylesheet" href="../assets/css/about.css">
</head>
<body>
    <?php include "admin_page.php"; ?>
<div class="about-container">
    <h2>About This System</h2>

    <div class="about-card">
        <h3>System Information</h3>
        <p><strong>Project:</strong> Employee Management System</p>
        <p><strong>Version:</strong> 1.0.0</p>
        <p><strong>Developed by:</strong> SRK Team</p>
        <p><strong>Admin:</strong> <?= htmlspecialchars($admin_name) ?></p>
        <p><strong>Language:</strong> PHP, MySQL, HTML, CSS, JS</p>
    </div>

    <div class="about-card">
        <h3>Developer Info</h3>
        <p><strong>Name:</strong> Reaksmey</p>
        <p><strong>Role:</strong> Software Developer</p>
        <p><strong>Email:</strong> reaksmey@example.com</p>
        <p><strong>University:</strong> Norton University, Cambodia</p>
        <p><strong>Field:</strong> Software Development</p>
    </div>

    <div class="about-footer">
        <p>&copy; <?= date('Y') ?> SRK Employee Management System. All rights reserved.</p>
    </div>
</div>
</body>
</html>
