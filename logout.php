<?php
session_start();

// Hapus semua data sesi
session_unset();

// Hapus sesi
session_destroy();

// Mulai sesi baru untuk menyimpan pesan sukses
session_start();
$_SESSION['success'] = "Anda telah berhasil logout.";

// Alihkan pengguna ke halaman login
header("Location: index.php");
exit();