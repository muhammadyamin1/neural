<?php
include 'auth.php';
checkRole(['admin']);
include 'dbKoneksi.php';

// Query untuk mengambil data
$sql = "SELECT * FROM parameter_model WHERE id = 1"; // Ganti dengan ID yang sesuai jika ada lebih dari satu set data
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    $row = $result->fetch_assoc();
    $inputSize = $row['inputSize'];
    $hiddenLayerSize = $row['hiddenLayerSize'];
    $outputSize = $row['outputSize'];
    $learningRate = $row['learningRate'];
    $epochs = $row['epochs'];
    $iterasiError = $row['iterasiError'];
    $modified = $row['modified_at'];
} else {
    // Default values if no data found
    $inputSize = '';
    $hiddenLayerSize = '';
    $outputSize = '';
    $learningRate = '';
    $epochs = '';
    $iterasiError = '';
}

$conn->close();
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

    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <style>
        .form-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            /* Untuk memberikan jarak antar elemen form */
        }

        .form-group {
            margin-bottom: 0;
            /* Menghilangkan margin bawah default untuk menjaga jarak antar elemen form */
        }

        .form-display-error {
            display: flex;
            align-items: center;
        }

        .form-display-error .form-control.custom-width {
            width: 100px;
            margin-right: 10px;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            /* Menempatkan teks di kiri dan tombol di kanan */
            align-items: center;
            /* Menjaga elemen vertikal terhadap align-center */
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
                        <h1>Parameter Model</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Atur Parameter Model</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">

            <div class="col-lg-12">
                <?php if (isset($_SESSION['error'])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])) : ?>
                    <div class="alert alert-success" role="alert">
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-8">
                <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show" role="alert" style="text-align: justify;">
                    <i class="fa fa-exclamation-triangle"></i>
                    Perhatian!<br>Penggunaan 'Epochs' yang terlalu banyak dapat mengonsumsi sumber daya CPU secara berlebihan. Harap pertimbangkan untuk mengelola beban komputasi.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body card-block">
                        <form action="simpanParameterModel.php" method="post" class="form-container">
                            <div class="form-group">
                                <label for="inputSize">Input Size</label>
                                <input type="number" class="form-control" id="inputSize" name="inputSize" value="<?php echo isset($inputSize) ? $inputSize : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="hiddenLayerSize">Hidden Layer Size</label>
                                <input type="number" class="form-control" id="hiddenLayerSize" name="hiddenLayerSize" value="<?php echo isset($hiddenLayerSize) ? $hiddenLayerSize : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="outputSize">Output Size</label>
                                <input type="number" class="form-control" id="outputSize" name="outputSize" value="<?php echo isset($outputSize) ? $outputSize : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="learningRate">Learning Rate</label>
                                <input type="number" step="0.001" class="form-control" id="learningRate" name="learningRate" value="<?php echo isset($learningRate) ? $learningRate : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="epochs">Epochs</label>
                                <input type="number" class="form-control" id="epochs" name="epochs" value="<?php echo isset($epochs) ? $epochs : ''; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="iterasiError">Tampilkan Error Setiap</label>
                                <div class="form-display-error">
                                    <input type="number" class="form-control custom-width" id="iterasiError" name="iterasiError" value="<?php echo isset($iterasiError) ? $iterasiError : ''; ?>" required>
                                    <span>Epoch</span>
                                </div>
                            </div>
                            <div class="form-footer">
                                <span>Terakhir diubah: <?php echo isset($modified) ? $modified : '-'; ?></span>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Parameter</button>
                            </div>
                        </form>
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

</body>

</html>