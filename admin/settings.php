<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../database.php";

$admin_id = $_SESSION['admin_id'] ?? null;
$username = $_SESSION['username'] ?? 'Admin';

// Fetch admin info
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

$success = $error = "";

// Handle profile update
if (isset($_POST['update_profile'])) {
    $new_name = trim($_POST['username']);
    $email = trim($_POST['email']);

    if ($new_name && $email) {
        $update = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $update->bind_param("ssi", $new_name, $email, $admin_id);
        if ($update->execute()) {
            $_SESSION['username'] = $new_name;
            $success = "Profile updated successfully!";
        } else {
            $error = "Failed to update profile!";
        }
        $update->close();
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass === $confirm_pass) {
        $check = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $check->bind_param("i", $admin_id);
        $check->execute();
        $check->bind_result($db_pass);
        $check->fetch();
        $check->close();

        if (password_verify($old_pass, $db_pass)) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->bind_param("si", $hashed, $admin_id);
            $update->execute();
            $update->close();
            $success = "Password changed successfully!";
        } else {
            $error = "Old password is incorrect!";
        }
    } else {
        $error = "New passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Settings</title>
<link rel="stylesheet" href="../assets/css/setting.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include "admin_page.php"; ?>
<div class="settings-container">
    <h2>Account Settings</h2>

    <?php if ($success): ?>
    <script>Swal.fire({icon:'success',title:'Success',text:'<?= $success ?>',confirmButtonColor:'#1a237e'});</script>
    <?php endif; ?>

    <?php if ($error): ?>
    <script>Swal.fire({icon:'error',title:'Error',text:'<?= $error ?>',confirmButtonColor:'#d32f2f'});</script>
    <?php endif; ?>

    <form method="POST" class="settings-form">
        <h3>Profile Information</h3>
        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($admin['username'] ?? '') ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required>

        <button type="submit" name="update_profile" class="btn-save">Update Profile</button>
    </form>

    <hr>

    <form method="POST" class="settings-form">
        <h3>Change Password</h3>
        <label>Old Password:</label>
        <input type="password" name="old_password" required>

        <label>New Password:</label>
        <input type="password" name="new_password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" name="change_password" class="btn-save">Change Password</button>
    </form>
</div>
</body>
</html>
