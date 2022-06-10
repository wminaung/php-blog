<?php
session_start();
require '../config/config.php';
require "../pre.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in']) && empty($_SESSION['role'])) {
    header("location: login.php");
    exit();
}
if ($_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM users WHERE id=:id");
$stmt->bindValue(':id', $id);
$stmt->execute();
header("Location: users_list.php");
