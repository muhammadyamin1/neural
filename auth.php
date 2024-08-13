<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu!";
    header('Location: index.php');
    exit();
}

// Function to check user role
function checkRole($roles = []) {
    if (!in_array($_SESSION['role'], $roles)) {
        $_SESSION['error'] = "Anda tidak memiliki akses ke halaman ini!";
        header('Location: index.php');
        exit();
    }
}
?>