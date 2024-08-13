<?php
session_start();
include 'dbKoneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Menyimpan username untuk ditampilkan kembali jika terjadi error
    $_SESSION['input_username'] = $username;

    // Sanitizing inputs
    $username = mysqli_real_escape_string($conn, $username);

    // Query to fetch the user data
    $sql = "SELECT id, nama, password, role FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verify password using bcrypt
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['success'] = "Login sukses sebagai " . $_SESSION['role'] . "! Selamat datang " . $_SESSION['nama'] . ".";
            header('Location: dashboard.php'); // Redirect to dashboard or homepage
        } else {
            $_SESSION['error'] = "Username atau Password salah!";
            header('Location: index.php');
        }
    } else {
        $_SESSION['error'] = "Akun tidak ditemukan!";
        header('Location: index.php');
    }
}