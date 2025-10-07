<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$admin_name = $_SESSION['username'] ?? "Admin";
?>

<!-- Sidebar -->

<style>
/* Optional inline styles (you can move this to admin.css) */
.admin-header {
  background-color: #1e293b;
  color: #fff;
  padding: 10px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.admin-header .brand {
  display: flex;
  align-items: center;
}

.admin-header .brand img {
  height: 40px;
  border-radius: 8px;
  margin-right: 10px;
}

.admin-header .admin-user {
  display: flex;
  align-items: center;
  gap: 8px;
}

.admin-header .user {
  height: 35px;
  width: 35px;
  border-radius: 50%;
}

.Sidebar {
  position: fixed;
  top: 60px;
  left: 0;
  width: 230px;
  background-color: #f8fafc;
  height: 100%;
  border-right: 1px solid #e2e8f0;
  padding-top: 20px;
}

.Sidebar nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.Sidebar nav ul li {
  margin: 10px 0;
}

.Sidebar nav ul li a {
  display: flex;
  align-items: center;
  padding: 10px 20px;
  text-decoration: none;
  color: #1e293b;
  font-weight: 500;
  transition: background 0.3s, color 0.3s;
}

.Sidebar nav ul li a:hover,
.Sidebar nav ul li a.active {
  background-color: #3b82f6;
  color: #fff;
  border-radius: 8px;
}

.Sidebar img {
  height: 22px;
  margin-right: 10px;
}
</style>
