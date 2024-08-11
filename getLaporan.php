<?php
header('Content-Type: application/json');

include 'dbKoneksi.php';

$id = $_GET['id'];

$sql = "SELECT * FROM prediksi_laporan WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Data tidak ditemukan']);
}

$stmt->close();
$conn->close();