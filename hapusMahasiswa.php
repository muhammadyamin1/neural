<?php
include 'auth.php';
checkRole(['admin']);
include 'dbKoneksi.php';

$id = $_POST['id'];

$stmt = $conn->prepare("DELETE FROM mahasiswa WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();
