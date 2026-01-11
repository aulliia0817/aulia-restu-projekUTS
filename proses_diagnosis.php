<?php
session_start();
require 'config/database.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit();
}
if (!isset($_GET['id_balita']) || !isset($_POST['jawaban'])) {
    header("Location: dashboard.php");
    exit();
}

$id_balita = (int) $_GET['id_balita'];
$id_user = $_SESSION['id_user'];
$jawaban_user = $_POST['jawaban'];

$query_cek = "SELECT tanggal_lahir FROM balita WHERE id_balita = $id_balita AND id_user = $id_user";
$result_cek = mysqli_query($koneksi, $query_cek);
if (mysqli_num_rows($result_cek) == 0) {
    header("Location: dashboard.php");
    exit();
}
$data_balita = mysqli_fetch_assoc($result_cek);



$aturan = [];
$query_aturan = "SELECT kondisi, kesimpulan FROM aturan";
$result_aturan = mysqli_query($koneksi, $query_aturan);
while ($row = mysqli_fetch_assoc($result_aturan)) {
    $aturan[] = $row;
}

$fakta = [];
foreach ($jawaban_user as $kode_gejala => $jawaban) {
    if ($jawaban == 'ya') {
        $fakta[] = $kode_gejala;
    }
}

$fakta_baru_ditemukan = true;
while ($fakta_baru_ditemukan) {
    $fakta_baru_ditemukan = false;
    foreach ($aturan as $rule) {
        if (!in_array($rule['kesimpulan'], $fakta)) {
            $kondisi_list = explode(' AND ', $rule['kondisi']);

            $semua_kondisi_terpenuhi = true;
            foreach ($kondisi_list as $kondisi) {
                if (!in_array($kondisi, $fakta)) {
                    $semua_kondisi_terpenuhi = false;
                    break;
                }
            }

            if ($semua_kondisi_terpenuhi) {
                $fakta[] = $rule['kesimpulan'];
                $fakta_baru_ditemukan = true;
            }
        }
    }
}



$kamus_hasil = [
    'K01' => 'Kwashiorkor',
    'K02' => 'Marasmus',
    'K03' => 'Anemia',
    'K04' => 'Stunting'
];

$kesimpulan_akhir = 'Normal / Tidak Terindikiasi Masalah';
foreach ($fakta as $f) {
    if (isset($kamus_hasil[$f])) {
        $kesimpulan_akhir = $kamus_hasil[$f];
    }
}

$tgl_lahir = new DateTime($data_balita['tanggal_lahir']);
$hari_ini = new DateTime('today');
$umur_bulan = $hari_ini->diff($tgl_lahir)->y * 12 + $hari_ini->diff($tgl_lahir)->m;

$jawaban_json = json_encode($jawaban_user);

$stmt = $koneksi->prepare("INSERT INTO hasil_diagnosis (id_balita, jawaban, kesimpulan_akhir, catatan_usia_saat_diagnosis) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issi", $id_balita, $jawaban_json, $kesimpulan_akhir, $umur_bulan);

if ($stmt->execute()) {
    $id_hasil_baru = $stmt->insert_id;
    header("Location: hasil.php?id_hasil=" . $id_hasil_baru);
    exit();
} else {
    echo "Terjadi kesalahan saat menyimpan hasil diagnosis.";
}

$stmt->close();
$koneksi->close();

?>