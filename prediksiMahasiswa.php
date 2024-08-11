<?php
$errorMessages = [];
$predicted = null;
$nextYear = null;
$absoluteError = null;
$squaredError = null;
$meanAbsoluteError = null;
$meanSquaredError = null;
$rootMeanSquaredError = null;
$meanAbsolutePercentageError = null;
$accuracy = null;
$historicalData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        include 'dbKoneksi.php';
        date_default_timezone_set('Asia/Jakarta');

        // Ambil parameter dari tabel parameter_model
        $sql = "SELECT inputSize, hiddenLayerSize, outputSize, learningRate, epochs, iterasiError FROM parameter_model ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);
        $param = $result->fetch_assoc();

        $inputSize = $param['inputSize'];
        $hiddenLayerSize = $param['hiddenLayerSize'];
        $outputSize = $param['outputSize'];
        $learningRate = $param['learningRate'];
        $epochs = $param['epochs'];
        $iterasiError = $param['iterasiError'];

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
        $numYears = count($data);

        if ($numYears === 0) {
            throw new Exception('Tidak ada data historis untuk tahun yang diberikan.');
        }

        // Menyimpan data historis
        $historicalData = $data;

        // Menyiapkan data input dan target
        $X = range(1, $numYears);  // Tahun ke-1, ke-2, dsb.
        $Y = $data;                // Jumlah mahasiswa

        // Normalisasi data
        $XMin = min($X);
        $XMax = max($X);
        $YMin = min($Y);
        $YMax = max($Y);

        if ($XMax === $XMin) {
            throw new Exception('Rentang nilai X adalah nol, normalisasi tidak mungkin.');
        }

        if ($YMax === $YMin) {
            throw new Exception('Rentang nilai Y adalah nol, normalisasi tidak mungkin.');
        }

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

        // Variabel untuk menyimpan error
        $errorMessages = [];

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

            // Tampilkan loss dalam alert
            if ($epoch % $iterasiError === 0) {
                $errorMessages[] = "Epoch $epoch, Loss: " . round($loss / $numYears, 4);
            }

            // Update error loss epoch terakhir
            $errorLossEpochTerakhir = $loss / $numYears;
        }

        // Prediksi jumlah mahasiswa di tahun berikutnya
        $nextYear = $numYears + 1;
        $nextYearNorm = ($nextYear - $XMin) / ($XMax - $XMin);

        if ($XMax === $XMin) {
            throw new Exception('Rentang nilai X adalah nol, normalisasi tidak mungkin.');
        }

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

        // Menghitung error dan akurasi
        $absoluteError = abs($predicted - $actualValue);
        $squaredError = pow($predicted - $actualValue, 2);
        $meanAbsoluteError = array_sum(array_map(fn($y) => abs($y - $predicted), $Y)) / $numYears;
        $meanSquaredError = array_sum(array_map(fn($y) => pow($y - $predicted, 2), $Y)) / $numYears;
        $rootMeanSquaredError = sqrt($meanSquaredError);
        $meanAbsolutePercentageError = array_sum(array_map(
            fn($y) => $y != 0 ? abs(($predicted - $y) / $y) : 0,
            $Y
        )) / $numYears;
        $accuracy = 100 - ($meanAbsolutePercentageError * 100);
    } catch (DivisionByZeroError $e) {
        $errorMessages[] = "Error: Terjadi kesalahan pembagian dengan nol - " . $e->getMessage();
    } catch (Exception $e) {
        $errorMessages[] = "Error: " . $e->getMessage();
    }

    // Fungsi untuk menyimpan laporan ke dalam database
    function simpanLaporan($conn, $year, $actualValue, $dataHistoris, $predicted, $errorLossEpochTerakhir, $absoluteError, $squaredError, $meanAbsoluteError, $meanSquaredError, $rootMeanSquaredError, $meanAbsolutePercentageError, $accuracy)
    {
        // Pastikan semua nilai yang dilewatkan ke bind_param adalah variabel
        $dataHistorisJson = json_encode($dataHistoris);
        $roundedPredicted = round($predicted, 4);
        $roundedErrorLossEpochTerakhir = round($errorLossEpochTerakhir, 4);
        $roundedAbsoluteError = round($absoluteError, 4);
        $roundedSquaredError = round($squaredError, 4);
        $roundedMeanAbsoluteError = round($meanAbsoluteError, 4);
        $roundedMeanSquaredError = round($meanSquaredError, 4);
        $roundedRootMeanSquaredError = round($rootMeanSquaredError, 4);
        // MAPE dan accuracy dalam persentase
        $roundedMeanAbsolutePercentageError = round($meanAbsolutePercentageError * 100, 2);
        $roundedAccuracy = round($accuracy, 2);

        // Menggunakan ROUND untuk memastikan format angka dengan 4 desimal
        $stmt = $conn->prepare("INSERT INTO prediksi_laporan (tahun, actual_value, data_historis, prediksi, error_loss_epoch_terakhir, error_absolut, error_kuadrat, mae, mse, rmse, mape, accuracy) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "iissdddddddd",
            $year,
            $actualValue,
            $dataHistorisJson,
            $roundedPredicted,
            $roundedErrorLossEpochTerakhir,
            $roundedAbsoluteError,
            $roundedSquaredError,
            $roundedMeanAbsoluteError,
            $roundedMeanSquaredError,
            $roundedRootMeanSquaredError,
            $roundedMeanAbsolutePercentageError,
            $roundedAccuracy
        );

        // Eksekusi statement
        $stmt->execute();

        // Tutup statement
        $stmt->close();
    }

    $dataHistoris = $data;
    simpanLaporan(
        $conn,
        $year,
        (int)$actualValue, // Convert to integer
        $dataHistoris,
        $predicted,
        $errorLossEpochTerakhir,
        $absoluteError,
        $squaredError,
        $meanAbsoluteError,
        $meanSquaredError,
        $rootMeanSquaredError,
        $meanAbsolutePercentageError,
        $accuracy
    );
}
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>JST Backpropagation</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="vendors/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <style>
        .btn .fa-spinner {
            display: none;
        }

        .btn.loading .fa-spinner {
            display: inline-block;
        }

        .btn.loading .fa-check {
            display: none;
        }

        .result-box {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin-top: 20px;
        }

        .custom-font {
            font-size: 18px;
        }
    </style>
</head>

<body>

    <!-- Left Panel -->

    <?php include 'sidebar.php'; ?>

    <!-- Left Panel -->

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">

        <!-- Header-->

        <?php include 'header.php'; ?>

        <!-- Header-->

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Prediksi Jumlah Mahasiswa</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Prediksi Jumlah Mahasiswa</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">

            <div class="row justify-content-center">

                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-center mb-3 mt-4">Formulir Prediksi Jumlah Mahasiswa</h3>
                            <form action="" method="post">
                                <div class="mb-3">
                                    <label for="year" class="form-label">Tahun:</label>
                                    <input type="number" id="year" name="year" class="form-control" placeholder="Masukkan tahun" required>
                                </div>

                                <div class="mb-4">
                                    <label for="actual_value" class="form-label">Jumlah Mahasiswa Aktual:</label>
                                    <input type="number" id="actual_value" name="actual_value" class="form-control" placeholder="Masukkan jumlah mahasiswa aktual" required>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-check"></i>
                                        <i class="fa fa-spinner fa-spin"></i>
                                        Submit
                                    </button>
                            </form>
                        </div>

                        </form>
                        <!-- Tampilkan hasil dan error di bawah formulir -->
                        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                            <div class="result-section mt-4">
                                <?php if (!empty($errorMessages)): ?>
                                    <?php foreach ($errorMessages as $message): ?>
                                        <div class="sufee-alert alert with-close alert-warning alert-dismissible fade show">
                                            <strong>Perhatian!</strong> <?php echo $message; ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <h2>Hasil Prediksi</h2>
                                <?php if (!empty($historicalData)): ?>
                                    <div class="result-box">
                                        <h3>Data Historis</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Tahun</th>
                                                    <th class="text-center">Jumlah Mahasiswa</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($historicalData as $index => $jumlah): ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo htmlspecialchars($index + 1); ?></td>
                                                        <td class="text-center"><?php echo htmlspecialchars($jumlah); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                                <h5 class="mt-1">Tahun: <?php echo htmlspecialchars($nextYear) . ' (' . $year . ')'; ?></h5>
                                <div class="custom-font">Jumlah mahasiswa sebenarnya: <?php echo htmlspecialchars($actualValue) . ' orang'; ?></div>

                                <h2 class="mt-4 mb-2">Error dan Akurasi</h2>
                                Absolute Error: <?php echo htmlspecialchars(round($absoluteError, 2)); ?><br>
                                Squared Error: <?php echo htmlspecialchars(round($squaredError, 2)); ?><br>
                                Mean Absolute Error: <?php echo htmlspecialchars(round($meanAbsoluteError, 2)); ?><br>
                                Mean Squared Error: <?php echo htmlspecialchars(round($meanSquaredError, 2)); ?><br>
                                Root Mean Squared Error: <?php echo htmlspecialchars(round($rootMeanSquaredError, 2)); ?><br>
                                Mean Absolute Percentage Error: <?php echo htmlspecialchars(round($meanAbsolutePercentageError * 100, 2)); ?>%<br>
                                Akurasi: <?php echo htmlspecialchars(round($accuracy, 2)); ?>%

                                <?php if ($predicted !== null): ?>
                                    <div class="alert alert-success mt-5" role="alert">
                                        <div class="text-center">
                                            <h5><strong>Prediksi jumlah mahasiswa untuk tahun <?php echo $year; ?> adalah</strong></h5>
                                            <h1><strong><?php echo round($predicted, 2); ?> orang</strong></h1>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

    </div> <!-- .content -->
    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <script src="vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="vendors/jszip/dist/jszip.min.js"></script>
    <script src="vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendors/pdfmake/build/vfs_fonts.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="assets/js/init-scripts/data-table/datatables-init.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            var submitButton = this.querySelector('button[type="submit"]');
            submitButton.classList.add('loading');
            submitButton.disabled = true;
        });
    </script>

</body>

</html>