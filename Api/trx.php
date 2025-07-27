<?php
header('Content-Type: application/json');
include "../inc/koneksi.php"; // Pastikan file koneksi benar
include "../mikrotik/function.php";

$apiKey = 'DEV-vgWgbRdNekzH7TDliWXVoXrjt6tXxU9iV7ULIcct';

// Ambil reference dari parameter GET
$reference = $_GET['reference'] ?? null;

if (!$reference) {
    echo json_encode([
        "success" => false,
        "message" => "Reference tidak ditemukan"
    ]);
    exit;
}

$payload = [
    'reference' => $reference,
];

// === CURL Request ke Tripay ===
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_FRESH_CONNECT  => true,
    CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/transaction/detail?' . http_build_query($payload),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER         => false,
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
    CURLOPT_FAILONERROR    => false,
    CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
]);

$response = curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);

if (!empty($error)) {
    echo json_encode([
        "success" => false,
        "message" => "CURL Error: $error"
    ]);
    exit;
}

// Decode response Tripay
$data = json_decode($response, true);

if (!$data || !isset($data['data'])) {
    echo json_encode([
        "success" => false,
        "message" => "Response Tripay tidak valid",
        "raw" => $response
    ]);
    exit;
}

// Ambil status pembayaran dari Tripay
$status    = strtoupper($data['data']['status']); // PAID, UNPAID, EXPIRED
$reference = $data['data']['reference'];         // Reference yang sama

// === Update status di tb_tagihan jika sudah LUNAS ===
if ($status === 'PAID') {
    // Update status tagihan jadi Lunas & update tgl_bayar sekarang
    $query = "UPDATE tb_tagihan 
              SET status = 'LS', tgl_bayar = NOW() 
              WHERE invoice = '$reference'";
    mysqli_query($koneksi, $query);

    // Ambil data pelanggan dan paket dari tb_tagihan
    $getTagihan = mysqli_query($koneksi, "SELECT id_pelanggan, id_paket, tgl_bayar FROM tb_tagihan WHERE invoice = '$reference'");
    if ($getTagihan && mysqli_num_rows($getTagihan) > 0) {
        $tagihanData = mysqli_fetch_assoc($getTagihan);
        $id_pelanggan = $tagihanData['id_pelanggan'];
        $id_paket = $tagihanData['id_paket'];
        $tgl_bayar = $tagihanData['tgl_bayar'];

        // Ambil bulan tgl_bayar & bulan sekarang (format 01-12)
        $bulan_tgl_bayar = date('m', strtotime($tgl_bayar));
        $bulan_sekarang = date('m');

        if ($bulan_tgl_bayar === $bulan_sekarang) {
            // ✅ Update paket pelanggan hanya jika bulan ini
            $updatePelanggan = "UPDATE tb_pelanggan 
                                SET id_paket = '$id_paket' 
                                WHERE id_pelanggan = '$id_pelanggan'";
            $mt->updatePaket($id_pelanggan, $id_paket); 
            mysqli_query($koneksi, $updatePelanggan);
        }
    }
}

// === Ambil data tb_tagihan dari database ===
$sql = "SELECT t.id_pelanggan, t.id_paket, p.paket as nama_paket, t.tagihan, t.status, t.tgl_bayar 
        FROM tb_tagihan t
        LEFT JOIN tb_paket p ON p.id_paket = t.id_paket
        WHERE t.invoice = '$reference'";
$result = mysqli_query($koneksi, $sql);
$db_data = [];
if ($result && mysqli_num_rows($result) > 0) {
    $db_data = mysqli_fetch_assoc($result);
}

// === Response JSON ===
echo json_encode([
    "success"   => true,
    "message"   => $status === 'PAID'
        ? "Status berhasil diperbarui menjadi LUNAS (LS)"
        : "Transaksi belum lunas",
    "status"    => $status,
    "reference" => $reference,
    "tripay"    => $data['data'],
    "tagihan"   => $db_data
]);


mysqli_close($koneksi);
?>
