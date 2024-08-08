<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">

        <div class="navbar-header">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="dashboard.php">JST Backpropagation</a>
            <a class="navbar-brand hidden" href="dashboard.php">JST</a>
        </div>

        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                    <a href="dashboard.php"> <i class="menu-icon fa fa-dashboard"></i>Dashboard</a>
                </li>
                <h3 class="menu-title">Input Data</h3><!-- /.menu-title -->
                <li class="<?php echo $current_page == 'tambahMahasiswa.php' ? 'active' : ''; ?>">
                    <a href="tambahMahasiswa.php"> <i class="menu-icon fa fa-plus-square"></i>Mahasiswa</a>
                </li>
                <h3 class="menu-title">Prediksi</h3><!-- /.menu-title -->
                <li class="<?php echo $current_page == 'prediksiMahasiswa.php' ? 'active' : ''; ?>">
                    <a href="prediksiMahasiswa.php"> <i class="menu-icon fa fa-users"></i>Jumlah Mahasiswa</a>
                </li>
                <li class="<?php echo $current_page == 'laporan.php' ? 'active' : ''; ?>">
                    <a href="laporan.php"> <i class="menu-icon fa fa-book"></i>Hasil</a>
                </li>
                <h3 class="menu-title">Pengaturan</h3><!-- /.menu-title -->
                <li class="<?php echo $current_page == 'parameterModel.php' ? 'active' : ''; ?>">
                    <a href="parameterModel.php"> <i class="menu-icon fa fa-bar-chart"></i>Parameter Model</a>
                </li>
                <li class="<?php echo $current_page == 'user.php' ? 'active' : ''; ?>">
                    <a href="user.php"> <i class="menu-icon fa fa-user"></i>User</a>
                </li>
                <li class="<?php echo $current_page == 'tentang.php' ? 'active' : ''; ?>">
                    <a href="tentang.php"> <i class="menu-icon fa fa-question-circle"></i>Tentang Aplikasi</a>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside><!-- /#left-panel -->