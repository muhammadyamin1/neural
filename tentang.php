<?php
include 'auth.php';
checkRole(['admin', 'user']);
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
        .section-title {
            margin-bottom: 30px;
        }

        .section-content {
            margin-bottom: 50px;
        }

        ul.specifications {
            list-style: none;
            padding: 0;
        }

        ul.specifications li {
            padding: 10px 15px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-size: 16px;
        }

        ul.specifications li:before {
            content: "\2713";
            /* Checkmark symbol */
            color: #28a745;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: 8px;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            margin-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .footer p {
            margin: 0;
            font-size: 0.9em;
            color: #6c757d;
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
                        <h1>Tentang Aplikasi</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Tentang Aplikasi</li>
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
                            <h1 class="text-center section-title">Tentang Aplikasi</h1>

                            <div class="section-content">
                                <h2>JST Backpropagation</h2>
                                <p>
                                    Jaringan Saraf Tiruan (JST) dengan algoritma Backpropagation adalah salah satu metode pembelajaran mesin yang paling populer dan efektif. JST ini bekerja dengan cara menyesuaikan bobot jaringan berdasarkan kesalahan yang diperoleh dari output yang dihasilkan dengan target yang diinginkan. Algoritma Backpropagation melakukan pembaruan bobot melalui proses propagasi balik kesalahan, sehingga jaringan dapat mempelajari pola dari data yang diberikan dan menghasilkan prediksi yang akurat.
                                </p>
                            </div>

                            <div class="section-content">
                                <h2>Ucapan Terima Kasih</h2>
                                <p>
                                    Saya, sebagai mahasiswi dari STMIK Pelita Nusantara, Eka Nissa, merancang dan mengembangkan aplikasi ini sebagai bagian dari tugas akhir saya. Riset dan pengembangan aplikasi ini dilakukan dengan menggunakan data dari STMIK Pelita Nusantara, yang memberikan kontribusi penting dalam menyempurnakan algoritma dan model prediksi yang digunakan. Saya berharap aplikasi ini dapat bermanfaat bagi pengguna dalam melakukan prediksi dan analisis data, serta sebagai referensi bagi mahasiswa lain yang ingin mempelajari lebih dalam tentang JST dan algoritma Backpropagation. Ucapan terima kasih tidak lupa saya ungkapkan kepada semua pihak yang telah mendukung dan membimbing saya selama proses pengembangan aplikasi ini.
                                </p>
                            </div>

                            <div class="section-content">
                                <h2>Spesifikasi Sistem yang Disarankan</h2>
                                <p>
                                    Untuk menjalankan aplikasi ini dengan baik, berikut adalah spesifikasi sistem yang disarankan:
                                </p>
                                <ul class="specifications">
                                    <li>Prosesor: Intel Core i5 atau setara</li>
                                    <li>RAM: Minimal 8 GB</li>
                                    <li>Ruang Penyimpanan: Minimal 100 GB</li>
                                    <li>Sistem Operasi: Windows 10 / macOS Catalina / Linux Ubuntu 20.04</li>
                                    <li>Perangkat Lunak: XAMPP atau LAMP (untuk server lokal), Browser terbaru (Google Chrome, Mozilla Firefox)</li>
                                </ul>
                            </div>

                            <div class="footer">
                                <p>&copy; 2024 Eka Nissa - STMIK Pelita Nusantara</p>
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

</body>

</html>