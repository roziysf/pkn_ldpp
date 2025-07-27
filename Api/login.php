<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
include "../inc/koneksi.php";
include "isolir.php";

// Cek koneksi
if ($koneksi->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Koneksi gagal: ' . $koneksi->connect_error
    ]);
    exit();
}

// Ambil input dari POST request
$data = json_decode(file_get_contents("php://input"), true);
$id_pelanggan = isset($data['id_pelanggan']) ? $koneksi->real_escape_string($data['id_pelanggan']) : '';
$no_hp = isset($data['no_hp']) ? $koneksi->real_escape_string($data['no_hp']) : '';
isolir($id_pelanggan);

// Validasi input
if (empty($id_pelanggan) || empty($no_hp)) {
    echo json_encode([
        'status' => 'fail',
        'message' => 'id_pelanggan dan no_hp wajib diisi.'
    ]);
    exit();
}

// Query untuk cek login
$sql = "SELECT * FROM tb_pelanggan WHERE id_pelanggan = '$id_pelanggan' AND no_hp = '$no_hp'";
$result = $koneksi->query($sql);

// Cek hasil query

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
        'message' => 'Login success.',
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

// Tutup koneksi
$koneksi->close();
?>
