<?php
$db_host = 'localhost';     
$db_user = 'root';          
$db_pass = '';              
$db_name = 'db_stunting';   

$koneksi = mysqli_connect(hostname: $db_host, username: $db_user, password: $db_pass, database: $db_name);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

?>