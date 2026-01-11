<?php
require 'auth_admin.php';
require '../config/database.php';

if (!isset($_GET['id_user']) || !is_numeric($_GET['id_user'])) {
    header("Location: kelola_pengguna.php");
    exit();
}

$id_user = (int) $_GET['id_user'];

if ($_SESSION['id_user'] == $id_user) {
    header("Location: kelola_pengguna.php?error=self_delete");
    exit();
}

mysqli_query($koneksi, "DELETE FROM users WHERE id_user = $id_user");

header("Location: kelola_pengguna.php?status=deleted");
exit();
