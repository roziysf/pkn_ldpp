<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include "../inc/koneksi.php";
// Cek koneksi
if ($koneksi->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Koneksi gagal: ' . $koneksi->connect_error
    ]);
    exit();
}

// Ambil parameter dari URL
$id_pelanggan = isset($_GET['id_pelanggan']) ? $koneksi->real_escape_string($_GET['id_pelanggan']) : '';
$no_hp = isset($_GET['no_hp']) ? $koneksi->real_escape_string($_GET['no_hp']) : '';

// Validasi input
if (empty($id_pelanggan) || empty($no_hp)) {
    echo json_encode([
        'status' => 'fail',
        'message' => 'Parameter id_pelanggan dan no_hp wajib diisi.'
    ]);
    exit();
}

// Query data pelanggan
$sql = "SELECT * FROM tb_pelanggan WHERE id_pelanggan = '$id_pelanggan' AND no_hp = '$no_hp'";
$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    $dataPelanggan = $result->fetch_assoc();

    // Ambil data paket berdasarkan id_paket dari data pelanggan
    $id_paket = $dataPelanggan['id_paket'];
    $sqlPaket = "SELECT * FROM tb_paket WHERE id_paket = '$id_paket'";
    $resultPaket = $koneksi->query($sqlPaket);

    $dataPaket = null;
    if ($resultPaket->num_rows > 0) {
        $dataPaket = $resultPaket->fetch_assoc();
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Data ditemukan.',
        'data' => [
            'pelanggan' => $dataPelanggan,
            'paket' => $dataPaket
        ]
    ]);
} else {
    echo json_encode([
        'status' => 'fail',
        'message' => 'Data tidak ditemukan.'
    ]);
}

$koneksi->close();
?>
