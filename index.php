<?php
session_start();

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? '',
];
$activateForm = $_SESSION['activate_form'] ?? 'login';
session_unset();

function showError($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}
function isActivateForm($forName, $activateForm) {
    return $forName === $activateForm ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGN IN ACCOUNT</title>
    <link rel="icon" href="image/favicon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login_and_register">
        <!-- LOGIN FORM -->
        <div class="form-box <?= isActivateForm('login', $activateForm); ?>" id="login-form">
            <form action="login.php" method="post">
                <img src="image/login-user-name-1.png" alt="logo" class="logo">
                <p>Sign in to your account</p>
                <?= showError($errors['login']); ?>

                <div class="input-box">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-box">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" name="login">Login</button>
                <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
                <p class="register-text">Don't have an account? 
                    <a href="#" onclick="showForm('register-form')">Register</a>
                </p>
            </form>
        </div>

        <!-- REGISTER FORM -->
        <div class="form-box <?= isActivateForm('register', $activateForm); ?>" id="register-form">
            <form action="register.php" method="post">
                <img src="image/admin.png" alt="logo" class="logo">
                <p>Register new Account</p>
                <?= showError($errors['register']); ?>

                <div class="input-box">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-box">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-box">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="input-box">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>

                <select name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="employee">User</option>
                </select>

                <button type="submit" name="register">Register</button>
                <p class="register-text">Already have an account? 
                    <a href="#" onclick="showForm('login-form')">Login</a>
                </p>
            </form>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>
