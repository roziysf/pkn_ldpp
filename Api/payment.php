<?php
header('Content-Type: application/json');
include "../inc/koneksi.php";

// Ambil parameter dari GET
$method        = $_GET['method'] ?? 'BRIVA';
$merchantRef   = $_GET['merchant_ref'] ?? 'TX001';
$amount        = $_GET['amount'] ?? 100000;
$customerName  = $_GET['customer_name'] ?? 'Nama Pelanggan';
$customerEmail = $_GET['customer_email'] ?? 'emailpelanggan@domain.com';
$customerPhone = $_GET['customer_phone'] ?? '081234567890';
$iduser        = $_GET['iduser'] ?? 'C001';

// Kunci Tripay
$apiKey       = 'DEV-vgWgbRdNekzH7TDliWXVoXrjt6tXxU9iV7ULIcct';
$privateKey   = 'qPnDg-fuSTK-yscXy-mCupz-pXMES';
$merchantCode = 'T42970';

// Produk (bisa dinamis)
$orderItems = [
    [
        'sku'         => 'FB-07',
        'name'        => 'Nama Produk 2',
        'price'       => $amount,
        'quantity'    => 1,
        'product_url' => 'https://tokokamu.com/product/nama-produk-2',
        'image_url'   => 'https://tokokamu.com/product/nama-produk-2.jpg',
    ]
];

// Data untuk request Tripay
$data = [
    'method'         => $method,
    'merchant_ref'   => $merchantRef,
    'amount'         => $amount,
    'customer_name'  => $customerName,
    'customer_email' => $customerEmail,
    'customer_phone' => $customerPhone,
    'order_items'    => $orderItems,
    'return_url'     => 'https://domainanda.com/redirect',
    'expired_time'   => (time() + (24 * 60 * 60)), // 24 jam
    'signature'      => hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey)
];

// Kirim request ke Tripay
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_FRESH_CONNECT  => true,
    CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/transaction/create',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER         => false,
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $apiKey],
    CURLOPT_FAILONERROR    => false,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query($data),
    CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
]);

$response = curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);

// Jika API error langsung keluar
if (!empty($error)) {
    echo json_encode(['success' => false, 'message' => $error]);
    exit;
}

// Decode response Tripay ke array
$tripay = json_decode($response, true);

if (!$tripay['success']) {
    echo json_encode(['success' => false, 'message' => $tripay['message']]);
    exit;
}

// Ambil reference Tripay
$reference = $tripay['data']['reference'];

// Ambil id_paket dari database sesuai tarif
$query = "SELECT * FROM tb_paket WHERE tarif = $amount LIMIT 1";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => mysqli_error($koneksi)]);
    exit;
}
$row = mysqli_fetch_assoc($result);
$id_paket = $row ? $row['id_paket'] : null;

// Jika tidak ada paket sesuai tarif
if (!$id_paket) {
    echo json_encode(['success' => false, 'message' => 'Paket dengan tarif tersebut tidak ditemukan']);
    exit;
}

// Ambil bulan & tahun sekarang (format 2 digit & 4 digit)
$bulanSekarang = date("m");
$tahunSekarang = date("Y");

// Data untuk simpan tagihan
$bulan       = $bulanSekarang;
$tahun       = $tahunSekarang;
$id_pelanggan = $iduser;
$tagihan     = $amount;
$status      = 'BL';
$tgl_bayar   = NULL;
$trx         = $merchantRef;
$invoice     = $reference;

// Query insert tagihan
$queryInsert = "INSERT INTO tb_tagihan 
    (id_tagihan, bulan, tahun, id_pelanggan, id_paket, tagihan, status, tgl_bayar, trx, invoice)
    VALUES 
    (NULL, '$bulan', '$tahun', '$id_pelanggan', '$id_paket', '$tagihan', '$status', NULL, '$trx', '$invoice')";

if (mysqli_query($koneksi, $queryInsert)) {
    echo json_encode([
        "success" => true,
        "message" => "Transaksi & Tagihan berhasil dibuat",
        "tripay"  => $tripay
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal menyimpan data tagihan: " . mysqli_error($koneksi),
        "tripay"  => $tripay
    ]);
}

mysqli_close($koneksi);
?>
