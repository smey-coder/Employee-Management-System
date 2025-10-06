<?php
 session_start();
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
                <img src="icon/udemy.svg" alt="icon-udemy" class="logo">
                <span class="name">apex</span>

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
                        <span>Buscar</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="selected">
                        <img src="icon/home.svg"  alt="">
                        <span>Pagin de Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="icon/file.svg"  alt="">
                        <span>Products</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="icon/megaphone.svg"  alt="">
                        <span>Marketing</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="icon/money.svg"  alt="">
                        <span>Ventas</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="icon/wallet.svg"  alt="">
                        <span>Cartera</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="icon/chart.svg"  alt="">
                        <span>Informes</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <img src="icon/tools.svg"  alt="">
                        <span>Heramient</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <main id="main">
        <p>This is main menu</p>
    </main>
    <script src="../assets/js/admin.js"></script>
</body>
</html>