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

// Validasi input
if (empty($id_pelanggan)) {
    echo json_encode([
        'status' => 'fail',
        'message' => 'Parameter id_pelanggan wajib diisi.'
    ]);
    exit();
}

// SQL: JOIN tb_tagihan dengan tb_paket
$sql = "
  SELECT 
    t.id_tagihan,
    t.bulan,
    t.tahun,
    t.id_pelanggan,
    t.id_paket,
    t.tagihan,
    t.status,
    t.tgl_bayar,
    t.trx,
    t.invoice,
    p.paket AS nama_paket,
    p.tarif
  FROM tb_tagihan t
  LEFT JOIN tb_paket p ON t.id_paket = p.id_paket
  WHERE t.id_pelanggan = '$id_pelanggan'
  ORDER BY t.tahun DESC, t.bulan DESC
";

$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    $tagihanList = [];
    while ($row = $result->fetch_assoc()) {
        $tagihanList[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Data tagihan ditemukan.',
        'data' => $tagihanList
    ]);
} else {
    echo json_encode([
        'status' => 'fail',
        'message' => 'Data tagihan tidak ditemukan.'
    ]);
}

// Tutup koneksi
$koneksi->close();
?>
