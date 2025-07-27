<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INTERNET TERISOLIR</title>
    <link rel="icon" href="https://img.icons8.com/fluency/48/000000/disconnected.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            max-width: 500px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 30px 20px;
        }
        .container img {
            max-width: 150px;
            margin-bottom: 15px;
        }
        h2 {
            color: #007FFF;
            margin-bottom: 15px;
        }
        p {
            color: #333;
            font-size: 14px;
            line-height: 1.6;
        }
        .highlight {
            background-color: #007FFF;
            color: white;
            padding: 6px 10px;
            border-radius: 4px;
            display: inline-block;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .footer {
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <img src="https://img.icons8.com/color/96/000000/laptop-error.png" alt="Isolir">
    <div class="highlight">ISOLIR</div>
    <h2>Pelanggan yang terhormat,</h2>
    <p>
        Kami informasikan bahwa layanan internet anda saat ini sedang di isolir 
        secara otomatis oleh sistem billing kami.
    </p>
    <p>
        Mohon maaf atas ketidaknyamanannya,<br>
        Agar dapat digunakan kembali, mohon melakukan pembayaran tagihan.
    </p>
    <p>
        Untuk menghindari terisolir berikutnya, dimohon agar melakukan pembayaran 
        sebelum tanggal jatuh tempo.<br>Terima kasih.
    </p>
    <div class="footer">© <?php echo date("Y"); ?> Billing System</div>
</div>

</body>
</html>
