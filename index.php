<?php
session_start();

if (isset($_SESSION['register_success'])) {
    $successMsg = htmlspecialchars($_SESSION['register_success']);
    echo "<script>
        window.onload = function() {
            document.getElementById('successModal').style.display = 'flex';
            document.getElementById('successModalMsg').textContent = '$successMsg';
        }
    </script>";
    unset($_SESSION['register_success']);
}

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
    <style>
        /* Success Modal Styles */
    .modal {
        position: fixed;
        z-index: 9999;
        left: 0; top: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background: #fff;
        border-radius: 10px;
        padding: 30px 40px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        min-width: 300px;
    }
    .btn-ok, .btn-cancel {
        padding: 8px 24px;
        margin: 0 10px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }
    .btn-ok { background: #218838; color: #fff; }
    .btn-cancel { background: #c82333; color: #fff; }
    .btn-ok:hover { background: #176B87; }
    .btn-cancel:hover { background: #a71d2a; }
    </style>
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

                <button type="submit" id="register_success" name="register">Register</button>
                <p class="register-text">Already have an account? 
                    <a href="#" onclick="showForm('login-form')">Login</a>
                </p>
            </form>
        </div>
    </div>
    <div id="successModal" class="modal" style="display:none;">
    <div class="modal-content">
        <img src="https://cdn.pixabay.com/photo/2015/10/23/11/09/download-1002802_1280.jpg" alt="Success" style="width:60px;margin-bottom:10px;border-radius: 10px;">
        <p id="successModalMsg" style="margin-bottom:20px;"></p>
        <button onclick="closeSuccessModal(true)" class="btn-ok">OK</button>
        <button onclick="closeSuccessModal(false)" class="btn-cancel">Cancel</button>
    </div>
 </div>
 <script>
    function closeSuccessModal(isOk) {
        document.getElementById('successModal').style.display = 'none';
        if(isOk){
            // Optionally redirect or do something on OK
            // window.location.href = "index.php";
        }
        // If Cancel, just close the modal
    }
    </script>
    <script src="assets/js/script.js"></script>
</body>
</html>
