<?php
//File Database for Employee Management System
$db_server = "localhost";
$db_username = "SmeyKh";
$db_password = "hello123(*)";
$db_name = "employee_db";
try{
    $conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);
    // if($conn){
    //     echo "<h1>Connected successfully!<h1>";
    // }
}catch(mysqli_connection_error $e){
    echo "Connection failed: " . $e->getMessage();
}
?>