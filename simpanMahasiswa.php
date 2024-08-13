<?php
include 'auth.php';
checkRole(['admin']);
include 'dbKoneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id = $_POST['id'];
$tahun = $_POST['tahun'];
$jumlah = $_POST['jumlah'];
$modified_at = date('Y-m-d H:i:s');

// Cek apakah tahun yang sama sudah ada
if (!$id) {
    // Hanya perlu memeriksa tahun jika ini adalah INSERT
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM mahasiswa WHERE tahun = ?");
    $checkStmt->bind_param("i", $tahun);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        // Jika tahun sudah ada
        echo json_encode(['status' => 'error', 'message' => 'Gagal: Tahun yang sama sudah ada.']);
        exit();
    }
}

if ($id) {
    // Update
    $stmt = $conn->prepare("UPDATE mahasiswa SET tahun = ?, jumlah = ?, modified_at = ? WHERE id = ?");
    $stmt->bind_param("iisi", $tahun, $jumlah, $modified_at, $id);
} else {
    // Insert
    $stmt = $conn->prepare("INSERT INTO mahasiswa (tahun, jumlah, modified_at) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $tahun, $jumlah, $modified_at);
}

$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);