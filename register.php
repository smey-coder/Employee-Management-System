<?php
session_start();
require_once "database.php";

try {
    if (isset($_POST['register'])) {
        $name  = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password_raw = trim($_POST['password']);
        $role  = $_POST['role'];

        // Validate inputs
        if (empty($name) || empty($email) || empty($password_raw) || empty($role)) {
            $_SESSION['register_error'] = 'All fields are required!';
            $_SESSION['activate_form'] = 'register';
            header("Location: index.php");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['register_error'] = 'Invalid email format!';
            $_SESSION['activate_form'] = 'register';
            header("Location: index.php");
            exit();
        }

        // Optional: Password length check
        if (strlen($password_raw) < 6) {
            $_SESSION['register_error'] = 'Password must be at least 6 characters!';
            $_SESSION['activate_form'] = 'register';
            header("Location: index.php");
            exit();
        }

        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['register_error'] = 'Username or Email is already taken!';
            $_SESSION['activate_form'] = 'register';
            $stmt->close();
            header("Location: index.php");
            exit();
        } else {
            // Hash password and insert
            $password = password_hash($password_raw, PASSWORD_DEFAULT);
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssss", $name, $email, $password, $role);

            if ($stmt->execute()) {
                unset($_SESSION['register_error']);
                $_SESSION['activate_form'] = 'login';
                $_SESSION['register_success'] = 'User added successfully!';
            } else {
                $_SESSION['register_error'] = 'Database error occurred while registering!';
                $_SESSION['activate_form'] = 'register';
            }
            $stmt->close();
            header("Location: index.php");
            exit();
        }
    }
} catch (mysqli_sql_exception $e) {
    $_SESSION['register_error'] = 'Error: ' . $e->getMessage();
    $_SESSION['activate_form'] = 'register';
    header("Location: index.php");
    exit();
} catch (Exception $e) {
    $_SESSION['register_error'] = 'Unexpected error: ' . $e->getMessage();
    $_SESSION['activate_form'] = 'register';
    header("Location: index.php");
    exit();
}
?>