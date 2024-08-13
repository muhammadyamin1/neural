<?php
session_start(); // Mulai sesi
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
                        <h1>Hasil Laporan JST</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Hasil Laporan JST</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">

            <div class="row">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="my-4">Hasil Laporan Prediksi JST</h2>
                            <?php
                            if (isset($_SESSION['message'])) {
                                echo '<div class="sufee-alert alert with-close alert-info alert-dismissible fade show">';
                                echo $_SESSION['message'];
                                echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                                echo '<span aria-hidden="true">&times;</span>';
                                echo '</button>';
                                echo '</div>';
                                unset($_SESSION['message']); // Hapus pesan setelah ditampilkan
                            }
                            ?>
                            <div class="table-responsive">
                                <form action="hapusLaporan.php" method="post">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center align-middle">
                                                    <input type="checkbox" id="select-all">
                                                </th>
                                                <th class="text-center align-middle">No</th> <!-- Kolom untuk nomor urut -->
                                                <th class='text-center align-middle'>Tahun</th>
                                                <th class='text-center align-middle'>Actual Value</th>
                                                <th class='align-middle'>Prediksi</th>
                                                <th class='align-middle'>Loss Pada Epoch Terakhir</th>
                                                <th class='align-middle'>Absolute Error</th>
                                                <th class='align-middle'>Squared Error</th>
                                                <th class='align-middle'>MAE</th>
                                                <th class='align-middle'>MSE</th>
                                                <th class='align-middle'>RMSE</th>
                                                <th class='text-center align-middle'>MAPE (%)</th>
                                                <th class='text-center align-middle'>Accuracy (%)</th>
                                                <th class='text-center align-middle'>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            include 'dbKoneksi.php';

                                            // Ambil data laporan dari database
                                            $sql = "SELECT * FROM `prediksi_laporan` ORDER BY `prediksi_laporan`.`tahun` DESC";
                                            $result = $conn->query($sql);

                                            $no = 1; // Inisialisasi nomor urut

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td class='text-center'><input type='checkbox' name='delete_ids[]' value='" . $row['id'] . "'></td>"; // Checkbox untuk menghapus
                                                    echo "<td class='text-center'>" . $no++ . "</td>"; // Menampilkan nomor urut
                                                    echo "<td class='text-center'>" . $row['tahun'] . "</td>";
                                                    echo "<td class='text-center'>" . $row['actual_value'] . "</td>";
                                                    echo "<td>" . $row['prediksi'] . "</td>";
                                                    echo "<td>" . $row['error_loss_epoch_terakhir'] . "</td>";
                                                    echo "<td>" . $row['error_absolut'] . "</td>";
                                                    echo "<td>" . $row['error_kuadrat'] . "</td>";
                                                    echo "<td>" . $row['mae'] . "</td>";
                                                    echo "<td>" . $row['mse'] . "</td>";
                                                    echo "<td>" . $row['rmse'] . "</td>";
                                                    echo "<td class='text-center'>" . $row['mape'] . "%</td>";
                                                    echo "<td class='text-center'>" . $row['accuracy'] . "%</td>";
                                                    echo '<td class="text-center"><button type="button" class="btn btn-primary btn-sm" onclick="makePDF(' . $row['id'] . ')"><i class="fa fa-book"></i> Cetak PDF</button></td>';
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='14' class='text-center'>Tidak ada data</td></tr>";
                                            }

                                            $conn->close();
                                            ?>
                                        </tbody>
                                    </table>

                                    <!-- Form untuk menghapus data yang dipilih -->
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus Data Terpilih</button>
                                </form>

                                <!-- Form untuk aksi Cetak PDF -->
                                <form id="pdf-form" method="post" action="generatePDF.php" style="display:none;">
                                    <input type="hidden" name="pdf_id" id="pdf_id">
                                </form>
                            </div>
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
        function makePDF(id) {
            fetch('getLaporan.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    // Jika data_historis tidak ada, gunakan array kosong
                    const dataHistoris = data.data_historis ? JSON.parse(data.data_historis) : [];

                    // Membuat data tahun berdasarkan jumlah nilai dalam data_historis
                    const tahunHistoris = dataHistoris.map((_, index) => index + 1);

                    // Parsing data bobot dan bias dari format longtext JSON
                    const W1 = data.W1 ? JSON.parse(data.W1) : [];
                    const b1 = data.b1 ? JSON.parse(data.b1) : [];
                    const W2 = data.W2 ? JSON.parse(data.W2) : [];
                    const b2 = data.b2 ? JSON.parse(data.b2) : [];

                    // Konversi data ke tipe number jika perlu
                    const predicted = parseFloat(data.prediksi);
                    const errorLossEpochTerakhir = parseFloat(data.error_loss_epoch_terakhir);
                    const absoluteError = parseFloat(data.error_absolut);
                    const squaredError = parseFloat(data.error_kuadrat);
                    const meanAbsoluteError = parseFloat(data.mae);
                    const meanSquaredError = parseFloat(data.mse);
                    const rootMeanSquaredError = parseFloat(data.rmse);
                    const meanAbsolutePercentageError = parseFloat(data.mape) * 100;
                    const accuracy = parseFloat(data.accuracy);

                    // Definisi dokumen PDF
                    var docDefinition = {
                        content: [{
                                text: 'Laporan Prediksi JST',
                                style: 'header'
                            },
                            {
                                text: 'Pendahuluan',
                                style: 'subheader'
                            },
                            {
                                text: 'Jaringan Syaraf Tiruan (JST) ini digunakan untuk memprediksi jumlah mahasiswa di masa depan berdasarkan data jumlah mahasiswa dari tahun-tahun sebelumnya. JST merupakan model pembelajaran mesin yang terinspirasi oleh cara kerja otak manusia, dengan kemampuan untuk mempelajari pola dari data historis dan melakukan generalisasi untuk membuat prediksi.'
                            },
                            {
                                text: 'Metodologi',
                                style: 'subheader'
                            },
                            {
                                text: 'Metodologi Backpropagation digunakan untuk memprediksi jumlah mahasiswa berdasarkan data historis. Backpropagation adalah algoritma pembelajaran yang membantu JST dalam mengoptimalkan bobot neuron untuk menghasilkan prediksi yang lebih akurat.'
                            },
                            {
                                text: 'Hasil Prediksi',
                                style: 'subheader'
                            },
                            {
                                table: {
                                    body: [
                                        ['Tahun', data.tahun],
                                        ['Jumlah Mahasiswa Sebenarnya', data.actual_value],
                                        ['Prediksi', predicted.toFixed(4)],
                                        ['Loss Pada Epoch Terakhir', errorLossEpochTerakhir.toFixed(4)],
                                        ['Absolute Error', absoluteError.toFixed(4)],
                                        ['Squared Error', squaredError.toFixed(4)],
                                        ['MAE', meanAbsoluteError.toFixed(4)],
                                        ['MSE', meanSquaredError.toFixed(4)],
                                        ['RMSE', rootMeanSquaredError.toFixed(4)],
                                        ['MAPE (%)', meanAbsolutePercentageError.toFixed(2)],
                                        ['Accuracy (%)', accuracy.toFixed(2)]
                                    ]
                                }
                            },
                            {
                                text: 'Data Historis',
                                style: 'subheader',
                                alignment: 'center',
                                margin: [0, 20, 0, 0]
                            },
                            {
                                table: {
                                    headerRows: 1,
                                    widths: ['*', '*'],
                                    body: [
                                        [{
                                                text: 'Tahun',
                                                style: 'tableHeader',
                                                alignment: 'center'
                                            },
                                            {
                                                text: 'Nilai',
                                                style: 'tableHeader',
                                                alignment: 'center'
                                            }
                                        ],
                                        ...tahunHistoris.map((tahun, index) => [{
                                                text: tahun.toString(),
                                                alignment: 'center'
                                            },
                                            {
                                                text: dataHistoris[index].toString(),
                                                alignment: 'center'
                                            }
                                        ])
                                    ]
                                }
                            },
                            {
                                text: 'Bobot dan Bias',
                                style: 'subheader',
                                pageBreak: 'before' // Tambahkan pemisahan halaman sebelum bagian ini
                            },
                            {
                                text: 'Bobot pada Layer Tersembunyi (W1)',
                                style: 'subheader'
                            },
                            {
                                table: {
                                    headerRows: 1,
                                    widths: Array(W1[0].length).fill('*'),
                                    body: [
                                        [{
                                            text: 'Bobot',
                                            style: 'tableHeader',
                                            alignment: 'center'
                                        }],
                                        ...W1.map(row => row.map(weight => ({
                                            text: parseFloat(weight).toFixed(4).toString(),
                                            alignment: 'center'
                                        })))
                                    ]
                                }
                            },
                            {
                                text: 'Bias pada Layer Tersembunyi (b1)',
                                style: 'subheader'
                            },
                            {
                                table: {
                                    body: [
                                        [{
                                                text: 'Bias',
                                                style: 'tableHeader',
                                                alignment: 'center'
                                            },
                                            ...b1.map(b => ({
                                                text: parseFloat(b).toFixed(4).toString(),
                                                alignment: 'center'
                                            }))
                                        ]
                                    ]
                                }
                            },
                            {
                                text: 'Bobot pada Layer Output (W2)',
                                style: 'subheader'
                            },
                            {
                                table: {
                                    headerRows: 1,
                                    widths: Array(W2[0].length).fill('*'),
                                    body: [
                                        [{
                                            text: 'Bobot',
                                            style: 'tableHeader',
                                            alignment: 'center'
                                        }],
                                        ...W2.map(row => row.map(weight => ({
                                            text: parseFloat(weight).toFixed(4).toString(),
                                            alignment: 'center'
                                        })))
                                    ]
                                }
                            },
                            {
                                text: 'Bias pada Layer Output (b2)',
                                style: 'subheader'
                            },
                            {
                                table: {
                                    body: [
                                        [{
                                                text: 'Bias',
                                                style: 'tableHeader',
                                                alignment: 'center'
                                            },
                                            ...b2.map(b => ({
                                                text: parseFloat(b).toFixed(4).toString(),
                                                alignment: 'center'
                                            }))
                                        ]
                                    ]
                                }
                            },
                            {
                                text: 'Diskusi',
                                style: 'subheader',
                                margin: [0, 20.5, 0, 5]
                            },
                            {
                                text: 'Perbedaan antara prediksi dan nilai aktual dalam model prediksi, seperti Jaringan Syaraf Tiruan (JST), dapat disebabkan oleh beberapa faktor utama. Kualitas data berperan penting, di mana data yang tidak lengkap atau mengandung noise dapat mempengaruhi akurasi prediksi. Selain itu, arsitektur model yang tidak optimal, seperti overftting atau underftting, juga dapat menyebabkan perbedaan.'
                            },
                            {
                                text: 'Kesimpulan',
                                style: 'subheader',
                                margin: [0, 20, 0, 5]
                            },
                            {
                                text: 'Jaringan Syaraf Tiruan (JST) yang menggunakan metodologi backpropagation terbukti efektif untuk memprediksi jumlah mahasiswa berdasarkan data historis yang ada. Algoritma backpropagation dapat secara otomatis menyesuaikan bobot neuron untuk meminimalkan kesalahan prediksi melalui proses iteratif yang melibatkan perhitungan gradien dan pembaruan bobot. Proses ini memungkinkan model untuk mempelajari pola non-linear dari data historis dan menghasilkan prediksi yang lebih akurat.'
                            }
                        ],
                        styles: {
                            header: {
                                fontSize: 18,
                                bold: true,
                                margin: [0, 0, 0, 10]
                            },
                            subheader: {
                                fontSize: 15,
                                bold: true,
                                margin: [0, 10, 0, 5]
                            },
                            tableHeader: {
                                bold: true,
                                fontSize: 13,
                                color: 'black'
                            }
                        }
                    };
                    pdfMake.createPdf(docDefinition).open();
                });
        }

        document.getElementById('select-all').addEventListener('click', function(event) {
            const checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = event.target.checked;
            });
        });
    </script>

</body>

</html>