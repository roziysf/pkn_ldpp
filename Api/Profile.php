<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include "../inc/koneksi.php";
include "isolir.php";
// ✅ Cek koneksi
if ($koneksi->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Koneksi gagal: ' . $koneksi->connect_error
    ]);
    exit();
}

// ✅ Ambil parameter dari URL
$id_pelanggan = isset($_GET['id_pelanggan']) ? $koneksi->real_escape_string($_GET['id_pelanggan']) : '';
$no_hp = isset($_GET['no_hp']) ? $koneksi->real_escape_string($_GET['no_hp']) : '';
isolir($id_pelanggan);
// ✅ Validasi input
if (empty($id_pelanggan) || empty($no_hp)) {
    echo json_encode([
        'status' => 'fail',
        'message' => 'Parameter id_pelanggan dan no_hp wajib diisi.'
    ]);
    exit();
}

// ✅ Query data pelanggan
$sql = "SELECT * FROM tb_pelanggan WHERE id_pelanggan = '$id_pelanggan' AND no_hp = '$no_hp'";
$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    $dataPelanggan = $result->fetch_assoc();

    // ✅ Ambil data paket berdasarkan id_paket
    $id_paket = $dataPelanggan['id_paket'];
    $dataPaket = null;
    if (!empty($id_paket)) {
        $sqlPaket = "SELECT * FROM tb_paket WHERE id_paket = '$id_paket'";
        $resultPaket = $koneksi->query($sqlPaket);

        if ($resultPaket->num_rows > 0) {
            $dataPaket = $resultPaket->fetch_assoc();
        }
    }

    // ✅ Ambil tagihan terbaru untuk hitung jatuh tempo
    $sqlTagihan = "SELECT * FROM tb_tagihan 
                   WHERE id_pelanggan = '$id_pelanggan' 
                   ORDER BY id_tagihan DESC 
                   LIMIT 1";
    $resultTagihan = $koneksi->query($sqlTagihan);

    $jatuhTempo = null;
    $sisaHari = null;
    if ($resultTagihan->num_rows > 0) {
        $tagihan = $resultTagihan->fetch_assoc();

        $tgl_bayar = $tagihan['tgl_bayar']; // format Y-m-d
        $jatuhTempo = date('Y-m-d', strtotime($tgl_bayar . ' +30 days'));

        // Hitung sisa hari dari sekarang
        $sekarang = new DateTime();
        $tempo = new DateTime($jatuhTempo);
        $selisih = $tempo->diff($sekarang);
        $sisaHari = $tempo > $sekarang ? $selisih->days : 0;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Data ditemukan.',
        'data' => [
            'pelanggan' => $dataPelanggan,
            'paket' => $dataPaket,
            'tagihan' => [
                'jatuh_tempo' => $jatuhTempo,
                'sisa_hari' => $sisaHari
            ]
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
