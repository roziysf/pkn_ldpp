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

// Query ambil semua paket
$sql = "SELECT * FROM tb_paket";
$result = $koneksi->query($sql);

// Siapkan data
if ($result->num_rows > 0) {
    $paketList = [];
    while ($row = $result->fetch_assoc()) {
        $paketList[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Data paket ditemukan.',
        'data' => $paketList
    ]);
} else {
    echo json_encode([
        'status' => 'fail',
        'message' => 'Tidak ada data paket.'
    ]);
}

// Tutup koneksi
$koneksi->close();
?>
