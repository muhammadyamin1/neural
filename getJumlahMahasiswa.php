<?php
include 'auth.php';
checkRole(['admin', 'user']);
header('Content-Type: application/json');

include 'dbKoneksi.php';

$sql = "SELECT tahun, jumlah FROM mahasiswa ORDER BY tahun ASC";
$result = $conn->query($sql);

$years = [];
$studentCounts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $years[] = $row['tahun'];
        $studentCounts[] = $row['jumlah'];
    }
}

$conn->close();

echo json_encode(['years' => $years, 'studentCounts' => $studentCounts]);
?>
