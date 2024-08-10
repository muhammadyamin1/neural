<?php
include 'dbKoneksi.php';

// Ambil parameter model terbaru
$sql = "SELECT * FROM parameter_model ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $param = $result->fetch_assoc();
    $inputSize = $param['inputSize'];
    $hiddenLayerSize = $param['hiddenLayerSize'];
    $outputSize = $param['outputSize'];
    $learningRate = $param['learningRate'];
    $epochs = $param['epochs'];
} else {
    die("Tidak ada parameter model ditemukan.");
}

$result->close();

// Ambil data dari formulir
$year = $_POST['year'];
$actualValue = $_POST['actual_value'];

// Ambil data historis dari tabel mahasiswa
$sql = "SELECT jumlah FROM mahasiswa WHERE tahun < ? ORDER BY tahun ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $year);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row['jumlah'];
}
$stmt->close();
print_r($data);
$numYears = count($data);

// Menyiapkan data input dan target
$X = range(1, $numYears);  // Tahun ke-1, ke-2, dsb.
$Y = $data;                // Jumlah mahasiswa

// Normalisasi data
$XMin = min($X);
$XMax = max($X);
$YMin = min($Y);
$YMax = max($Y);

$XNorm = array_map(fn($x) => ($x - $XMin) / ($XMax - $XMin), $X);
$YNorm = array_map(fn($y) => ($y - $YMin) / ($YMax - $YMin), $Y);

// Inisialisasi bobot dan bias
$W1 = array_fill(0, $hiddenLayerSize, array_fill(0, $inputSize, rand() / getrandmax() * 0.1));
$b1 = array_fill(0, $hiddenLayerSize, rand() / getrandmax() * 0.1);

$W2 = array_fill(0, $outputSize, array_fill(0, $hiddenLayerSize, rand() / getrandmax() * 0.1));
$b2 = array_fill(0, $outputSize, rand() / getrandmax() * 0.1);

// Fungsi sigmoid
function sigmoid($z)
{
    return 1 / (1 + exp(-$z));
}

// Turunan fungsi sigmoid
function sigmoidDerivative($a)
{
    return $a * (1 - $a);
}

// Pelatihan
for ($epoch = 1; $epoch <= $epochs; $epoch++) {
    $loss = 0;

    // Forward Pass
    foreach ($XNorm as $i => $xNorm) {
        // Hidden layer
        $z1 = array_map(
            fn($w, $b) => array_sum(array_map(
                fn($weight) => $weight * $xNorm,
                $w
            )) + $b,
            $W1,
            $b1
        );
        $a1 = array_map('sigmoid', $z1);

        // Output layer
        $z2 = array_map(
            fn($w, $b) => array_sum(array_map(
                fn($weight, $a) => $weight * $a,
                $w,
                $a1
            )) + $b,
            $W2,
            $b2
        );
        $a2 = array_map('sigmoid', $z2);

        // Loss function (mean squared error)
        $loss += pow($YNorm[$i] - $a2[0], 2);

        // Backward Pass
        $delta2 = array_map(
            fn($a2, $yNorm) => ($a2 - $yNorm) * sigmoidDerivative($a2),
            $a2,
            [$YNorm[$i]]
        );

        // Gradient untuk W2 dan b2
        $dW2 = array_map(
            fn($w, $a1) => array_map(
                fn($a) => $delta2[0] * $a,
                $a1
            ),
            $W2,
            array_fill(0, $outputSize, $a1)
        );

        $db2 = $delta2;

        // Gradient untuk W1 dan b1
        $delta1 = array_map(
            fn($w) => array_sum(array_map(
                fn($weight, $delta) => $weight * $delta,
                $w,
                $delta2
            )),
            $W2
        );
        $a1Adjusted = array_map('sigmoidDerivative', $a1);
        $delta1 = array_map(fn($d, $a1Value) => $d * $a1Value, $delta1, $a1Adjusted);

        $dW1 = array_map(
            fn($d) => array_map(
                fn($x) => $d * $x,
                array_fill(0, $inputSize, $xNorm)
            ),
            $delta1
        );
        $db1 = $delta1;

        // Update bobot dan bias
        foreach ($W2 as $j => $w) {
            $W2[$j] = array_map(
                fn($w, $dw) => $w - $learningRate * $dw,
                $w,
                $dW2[$j]
            );
        }

        $b2 = array_map(
            fn($b, $db) => $b - $learningRate * $db,
            $b2,
            $db2
        );

        foreach ($W1 as $j => $w) {
            $W1[$j] = array_map(
                fn($w, $dw) => $w - $learningRate * $dw,
                $w,
                $dW1[$j]
            );
        }

        $b1 = array_map(
            fn($b, $db) => $b - $learningRate * $db,
            $b1,
            $db1
        );
    }

    // Tampilkan loss setiap 100 epoch
    if ($epoch % 100 === 0) {
        echo "Epoch $epoch, Loss: " . round($loss / $numYears, 4) . "\n";
    }
}

// Prediksi jumlah mahasiswa di tahun berikutnya
$nextYear = $numYears + 1;
$nextYearNorm = ($nextYear - $XMin) / ($XMax - $XMin);
$z1Next = array_map(
    fn($w, $b) => array_sum(array_map(
        fn($weight) => $weight * $nextYearNorm,
        $w
    )) + $b,
    $W1,
    $b1
);
$a1Next = array_map('sigmoid', $z1Next);

$z2Next = array_map(
    fn($w, $b) => array_sum(array_map(
        fn($weight, $a) => $weight * $a,
        $w,
        $a1Next
    )) + $b,
    $W2,
    $b2
);
$predictedNorm = sigmoid(array_sum($z2Next));

// Denormalisasi hasil prediksi
$predicted = $predictedNorm * ($YMax - $YMin) + $YMin;

// Menghitung error
$absoluteError = abs($predicted - $actualValue);
$squaredError = pow($predicted - $actualValue, 2);
$meanAbsoluteError = array_sum(array_map(fn($y) => abs($y - $predicted), $Y)) / $numYears;
$meanSquaredError = array_sum(array_map(fn($y) => pow($y - $predicted, 2), $Y)) / $numYears;
$rootMeanSquaredError = sqrt($meanSquaredError);
$meanAbsolutePercentageError = array_sum(array_map(fn($y) => abs(($y - $predicted) / $y) * 100, $Y)) / $numYears;

// Menghitung akurasi
$accuracy = 100 - $meanAbsolutePercentageError;

// Menampilkan hasil
echo "Prediksi jumlah mahasiswa untuk tahun ".$year." adalah " . round($predicted, 2) . "\n";
echo "Jumlah mahasiswa yang sebenarnya adalah $actualValue\n";
echo "Absolute Error: " . round($absoluteError, 2) . "\n";
echo "Squared Error: " . round($squaredError, 2) . "\n";
echo "Mean Absolute Error (MAE): " . round($meanAbsoluteError, 2) . "\n";
echo "Mean Squared Error (MSE): " . round($meanSquaredError, 2) . "\n";
echo "Root Mean Squared Error (RMSE): " . round($rootMeanSquaredError, 2) . "\n";
echo "Mean Absolute Percentage Error (MAPE): " . round($meanAbsolutePercentageError, 2) . "%\n";
echo "Akurasi: " . round($accuracy, 2) . "%\n";

$conn->close();

?>