<?php
include 'dbKoneksi.php';

$query = "SELECT tahun, jumlah, modified_at, id FROM mahasiswa ORDER BY tahun DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td class='text-center'>{$row['tahun']}</td>
                <td class='text-center'>{$row['jumlah']}</td>
                <td class='text-center'>{$row['modified_at']}</td>
                <td>
                    <button class='btn btn-warning btn-sm' onclick='editMahasiswa({$row['id']}, \"{$row['tahun']}\", {$row['jumlah']})'><i class='fa fa-edit'></i> Edit</button>
                    <button class='btn btn-danger btn-sm' onclick='hapusMahasiswa({$row['id']})'><i class='fa fa-trash'></i> Hapus</button>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='4' class='text-center'>Tidak ada data</td></tr>";
}

$conn->close();
