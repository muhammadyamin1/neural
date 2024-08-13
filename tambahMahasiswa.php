<?php
include 'auth.php';
checkRole(['admin']);
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
                        <h1>Input Data Mahasiswa</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Input Data Mahasiswa</li>
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
                            <h2>Input Data Mahasiswa</h2>
                            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalMahasiswa" onclick="resetModal()">Tambah Mahasiswa</button>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Tahun</th>
                                            <th class="text-center">Jumlah Mahasiswa</th>
                                            <th class="text-center">Terakhir Diubah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dataMahasiswa">
                                        <!-- Data akan dimuat secara dinamis -->
                                        <tr id="loadingRow" style="display: none;">
                                            <td colspan="4" class="text-center">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Tambah/Edit Mahasiswa -->
                <div class="modal fade" id="modalMahasiswa" tabindex="-1" role="dialog" aria-labelledby="modalMahasiswaLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="formMahasiswa" action="simpanMahasiswa.php" method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalMahasiswaLabel">Tambah Mahasiswa</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="tahun">Tahun</label>
                                        <input type="number" class="form-control" id="tahun" name="tahun" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="jumlah">Jumlah Mahasiswa</label>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                                    </div>
                                    <input type="hidden" id="idMahasiswa" name="id">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Konfirmasi Hapus -->
                <div class="modal fade" id="modalHapus" tabindex="-1" role="dialog" aria-labelledby="modalHapusLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalHapusLabel">Konfirmasi Hapus</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Apakah Anda yakin ingin menghapus data ini?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-danger" id="btnHapus">Hapus</button>
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
        jQuery(document).ready(function() {
            loadMahasiswa();

            jQuery('#formMahasiswa').on('submit', function(e) {
                e.preventDefault();
                var formData = jQuery(this).serialize();
                jQuery.post('simpanMahasiswa.php', formData, function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 'error') {
                        alert(result.message);
                    } else {
                        jQuery('#modalMahasiswa').modal('hide');
                        alert(result.message);
                        loadMahasiswa();
                    }
                });
            });

            jQuery('#btnHapus').on('click', function() {
                var id = jQuery(this).data('id');
                jQuery.post('hapusMahasiswa.php', {
                    id: id
                }, function(data) {
                    jQuery('#modalHapus').modal('hide');
                    alert('Data berhasil dihapus.');
                    loadMahasiswa();
                });
            });
        });

        function loadMahasiswa() {
            // Menampilkan teks loading
            jQuery('#loadingRow').show();

            // Menyembunyikan data yang lama (jika ada)
            jQuery('#dataMahasiswa').children().not('#loadingRow').hide();

            // Melakukan panggilan AJAX
            jQuery.get('getMahasiswa.php', function(data) {
                // Sembunyikan teks loading dan tampilkan data dalam tabel
                jQuery('#loadingRow').hide();
                jQuery('#dataMahasiswa').html(data);
            });
        }

        function editMahasiswa(id, tahun, jumlah) {
            jQuery('#idMahasiswa').val(id);
            jQuery('#tahun').val(tahun).prop('readonly', true); // Set tahun readonly
            jQuery('#jumlah').val(jumlah);
            jQuery('#modalMahasiswaLabel').text('Edit Mahasiswa');
            jQuery('#modalMahasiswa').modal('show');
        }

        function hapusMahasiswa(id) {
            jQuery('#btnHapus').data('id', id);
            jQuery('#modalHapus').modal('show');
        }

        function resetModal() {
            jQuery('#idMahasiswa').val('');
            jQuery('#tahun').val('').prop('readonly', false); // Remove readonly untuk input baru
            jQuery('#jumlah').val('');
            jQuery('#modalMahasiswaLabel').text('Tambah Mahasiswa');
        }
    </script>

</body>

</html>