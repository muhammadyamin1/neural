<?php
include 'auth.php';
checkRole(['admin', 'user']);
require 'dbKoneksi.php';

$id = intval($_GET['id']); // Sanitasi input
$currentUser = $_SESSION['user_id'];
$currentUserRole = $_SESSION['role'];
$isSelf = ($currentUser == $id); // Cek apakah yang mengakses adalah diri sendiri
$isSuperAdmin = ($currentUser == 1); // Cek apakah yang mengakses adalah admin utama

// Jika admin utama (id 1) sedang diedit oleh orang lain, blok aksesnya
if ($id == 1 && !$isSelf && !$isSuperAdmin) {
    $_SESSION['error'] = "Anda tidak diizinkan mengubah data admin utama.";
    header('Location: user.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = htmlspecialchars($_POST['nama']); // Sanitasi input

    // Jika yang mengakses adalah diri sendiri atau super admin, ambil username dari input form
    if ($isSelf || $isSuperAdmin) {
        $username = htmlspecialchars($_POST['username']); // Sanitasi input
    } else {
        $username = $user['username']; // Gunakan username yang lama
    }

    // Cek apakah username baru sudah ada di database (selain user yang sedang diedit)
    if ($username !== $user['username']) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $username, $id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $_SESSION['error'] = "Username sudah digunakan oleh pengguna lain.";
            header('Location: editUser.php?id=' . $id);
            exit();
        }
    }

    // Jika role diubah oleh admin atau super admin, ambil dari input form
    if ($currentUserRole === 'admin' || $isSuperAdmin) {
        $role = htmlspecialchars($_POST['role']); // Sanitasi input
    } else {
        $role = $user['role']; // Gunakan role yang lama
    }

    // Persiapkan pernyataan SQL berdasarkan apakah password diubah atau tidak
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET username = ?, nama = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $username, $nama, $password, $role, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, nama = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $nama, $role, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "User berhasil diedit.";
    } else {
        $_SESSION['error'] = "Gagal mengedit user.";
    }

    $stmt->close();

    // Redirect berdasarkan siapa yang mengakses
    if ($isSelf) {
        header('Location: editUser.php?id=' . $id);
    } else {
        header('Location: user.php');
    }
    exit();
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
        .form-group .input-group-addon {
            cursor: pointer;
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
                        <h1>User</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">User</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">

            <div class="row">

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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="mb-4">Edit Pengguna</h2>
                            <form action="editUser.php?id=<?= $id ?>" method="POST">
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" id="nama" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" autocomplete="off" required>
                                </div>
                                <?php if ($isSelf || $isSuperAdmin): ?>
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" autocomplete="off" required>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="password">Password (kosongkan jika tidak ingin mengubah)</label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" placeholder="Password" class="form-control" autocomplete="new-password">
                                        <div class="input-group-addon" onclick="togglePasswordVisibility()">
                                            <i class="fa fa-eye" id="toggleIcon"></i>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($currentUserRole === 'admin' || $isSuperAdmin): ?>
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select name="role" id="role" class="form-control">
                                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                <?php if ($currentUserRole !== 'user'): ?>
                                    <a href="user.php" class="btn btn-secondary"><i class="fa fa-arrow-circle-o-left"></i> Kembali</a>
                                <?php endif; ?>
                            </form>
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
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>

</body>

</html>