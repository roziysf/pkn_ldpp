<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
include "../inc/koneksi.php";
include "../mikrotik/function.php";



function isolir ($id_pelanggan){
global $koneksi;
global $mt;

// Ambil tagihan terbaru
$sqlTagihan = "SELECT * FROM tb_tagihan 
               WHERE id_pelanggan = '$id_pelanggan' 
               ORDER BY id_tagihan DESC 
               LIMIT 1";
$resultTagihan = $koneksi->query($sqlTagihan);

if ($resultTagihan->num_rows > 0) {
    $tagihan = $resultTagihan->fetch_assoc();

    // Hitung jatuh tempo (30 hari dari tgl_bayar)
    $tgl_bayar = $tagihan['tgl_bayar']; // format Y-m-d
    $jatuh_tempo = date('Y-m-d', strtotime($tgl_bayar . ' +30 days'));

    // Hitung sisa hari dari sekarang
    $sekarang = new DateTime();
    $tempo = new DateTime($jatuh_tempo);
    $selisih = $tempo->diff($sekarang);
    $sisa_hari = $tempo > $sekarang ? $selisih->days : 0; // kalau lewat jatuh tempo, 0

    // ✅ Update otomatis jika sudah jatuh tempo
    if ($tempo <= $sekarang) {
        // Jika lewat jatuh tempo → update paket jadi ISOLIR
       $update = $koneksi->query("UPDATE tb_pelanggan SET id_paket = NULL WHERE id_pelanggan = '$id_pelanggan'");

        if ($update) {
            $status_update = "Paket berhasil diupdate ke ISOLIR";
            $mt->updatePaket($id_pelanggan, "p-ISOLIR"); 
        } else {
            $status_update = "Gagal update paket: " . $koneksi->error;
        }
    } else {
        $status_update = "Belum jatuh tempo, paket tetap aktif";
    }

    // echo json_encode([
    //     "status" => "success",
    //     "message" => "Data ditemukan.",
    //     "data" => [
    //         "jatuh_tempo" => $jatuh_tempo,
    //         "sisa_hari" => $sisa_hari,
    //         "status_update" => $status_update
    //     ]
    // ]);
} else {
    // echo json_encode([
    //     "status" => "fail",
    //     "message" => "Tagihan tidak ditemukan."
    // ]);
}
}


?>
