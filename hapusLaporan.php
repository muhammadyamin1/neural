<?php
session_start();
include 'dbKoneksi.php';

if (isset($_POST['delete_ids'])) {
    $ids = $_POST['delete_ids'];
    $ids_list = implode(',', array_map('intval', $ids));

    $sql = "DELETE FROM prediksi_laporan WHERE id IN ($ids_list)";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Data berhasil dihapus.";
    } else {
        $_SESSION['message'] = "Error: " . $conn->error;
    }
    $conn->close();
    header("Location: laporan.php"); // Redirect ke halaman utama setelah penghapusan
    exit();
} else {
    $_SESSION['message'] = "Tidak ada data yang dipilih untuk dihapus.";
    header("Location: laporan.php");
    exit();
}