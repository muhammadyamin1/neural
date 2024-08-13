<?php
session_start();
require 'dbKoneksi.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "User berhasil dihapus.";
} else {
    $_SESSION['error'] = "Gagal menghapus user.";
}

$stmt->close();

header('Location: user.php');
exit();
?>