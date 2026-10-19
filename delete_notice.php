<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "DELETE FROM notices WHERE id = $id";
    mysqli_query($conn, $sql);

    header("Location: index.php");
exit();
}


?>