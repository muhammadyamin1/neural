<?php
include 'auth.php';
checkRole(['admin']);
include 'dbKoneksi.php';
date_default_timezone_set('Asia/Jakarta');

// Ambil data dari form
$inputSize = $_POST['inputSize'];
$hiddenLayerSize = $_POST['hiddenLayerSize'];
$outputSize = $_POST['outputSize'];
$learningRate = $_POST['learningRate'];
$epochs = $_POST['epochs'];
$iterasiError = $_POST['iterasiError'];

// Cek apakah data sudah ada di database
$sql = "SELECT * FROM parameter_model WHERE id = 1"; // Ganti dengan ID yang sesuai jika ada lebih dari satu set data
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Jika data ada, update data yang sudah ada
    $sql = "UPDATE parameter_model SET 
            inputSize = '$inputSize', 
            hiddenLayerSize = '$hiddenLayerSize', 
            outputSize = '$outputSize', 
            learningRate = '$learningRate', 
            epochs = '$epochs',
            iterasiError = '$iterasiError',
            modified_at = NOW() 
            WHERE id = 1"; // Ganti dengan ID yang sesuai jika ada lebih dari satu set data
} else {
    // Jika data belum ada, simpan data baru
    $sql = "INSERT INTO parameter_model (id, inputSize, hiddenLayerSize, outputSize, learningRate, epochs, iterasiError) 
            VALUES (1, '$inputSize', '$hiddenLayerSize', '$outputSize', '$learningRate', '$epochs', '$iterasiError')"; // Ganti ID jika perlu
}

if ($conn->query($sql) === TRUE) {
    $_SESSION['success'] = "Parameter berhasil disimpan";
    header("Location: parameterModel.php");
} else {
    $_SESSION['error'] = "Gagal menyimpan parameter: " . $conn->error;
    header("Location: parameterModel.php");
    exit();
}

$conn->close();