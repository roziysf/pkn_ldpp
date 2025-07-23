<?php
header('Content-Type: application/json');

// Ambil parameter dari GET (bisa dikirim via URL)
$method        = $_GET['method'] ?? 'BRIVA';
$merchantRef   = $_GET['merchant_ref'] ?? 'TX001';
$amount        = $_GET['amount'] ?? 1000000;
$customerName  = $_GET['customer_name'] ?? 'Nama Pelanggan';
$customerEmail = $_GET['customer_email'] ?? 'emailpelanggan@domain.com';
$customerPhone = $_GET['customer_phone'] ?? '081234567890';

// Kunci Tripay
$apiKey       = 'DEV-vgWgbRdNekzH7TDliWXVoXrjt6tXxU9iV7ULIcct';
$privateKey   = 'qPnDg-fuSTK-yscXy-mCupz-pXMES';
$merchantCode = 'T42970';

// Produk bisa juga dibuat dinamis, ini contoh tetap dulu
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

// Output API
echo empty($error) ? $response : json_encode(['error' => $error]);
?>
