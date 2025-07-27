<?php
include "../inc/koneksi.php";
include "../inc/rupiah.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran LDP-NET</title>
    <link rel="icon" href="LogoLDP.png">
    <style>
        body {
            font-family: "Courier New", monospace;
            font-size: 14px;
            color: #333;
            background: #f9f9f9;
        }

        .struk-container {
            width: 320px;
            margin: 10px auto;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            max-width: 70px;
            margin-bottom: 5px;
        }

        .struk-header {
            font-weight: bold;
            font-size: 16px;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        .struk-subheader {
            font-size: 12px;
            color: #555;
            margin-bottom: 10px;
        }

        hr {
            border: none;
            border-top: 1px dashed #999;
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
        }

        table th {
            width: 45%;
            color: #555;
        }

        table td {
            text-align: right;
            font-weight: bold;
            color: #000;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            color: #fff;
            font-size: 11px;
            display: inline-block;
        }

        .badge-danger {
            background: #e74c3c;
        }

        .badge-info {
            background: #3498db;
        }

        .footer {
            margin-top: 10px;
            font-size: 11px;
            color: #555;
            text-align: center;
            border-top: 1px dashed #999;
            padding-top: 5px;
        }

        .qrcode {
            margin: 8px auto 4px;
        }

        @media print {
            body {
                background: none;
                margin: 0;
                padding: 0;
            }

            .struk-container {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>

<body>

    <div class="struk-container">
        <!-- ✅ Logo Perusahaan -->
        <img src="LogoLDP.png" alt="Logo" class="logo">
        <div class="struk-header">** STRUK PEMBAYARAN **</div>
        <div class="struk-subheader">TAGIHAN LAYANAN INTERNET LDP-NET</div>
        <hr>

        <?php
        $id = $_GET['id_tagihan'];
        $sql_tampil = "SELECT p.id_pelanggan, p.nama, p.no_hp, t.id_tagihan, t.tagihan, 
                        t.status, t.tgl_bayar, t.bulan, t.tahun, k.id_paket, k.paket
                        FROM tb_pelanggan p 
                        INNER JOIN tb_tagihan t ON p.id_pelanggan=t.id_pelanggan
                        INNER JOIN tb_paket k ON k.id_paket=p.id_paket
                        WHERE t.status='LS' AND t.id_tagihan='$id'";
        $query_tampil = mysqli_query($koneksi, $sql_tampil);
        while ($data = mysqli_fetch_array($query_tampil, MYSQLI_BOTH)) {
        ?>
            <table>
                <tr>
                    <th>ID / Nama</th>
                    <td><?php echo $data['id_pelanggan'] . " / " . $data['nama']; ?></td>
                </tr>
                <tr>
                    <th>No HP</th>
                    <td><?php echo $data['no_hp']; ?></td>
                </tr>
                <tr>
                    <th>Paket</th>
                    <td><?php echo $data['paket']; ?></td>
                </tr>
                <tr>
                    <th>Bulan / Tahun</th>
                    <td><?php echo $data['bulan'] . " / " . $data['tahun']; ?></td>
                </tr>
                <tr>
                    <th>Tagihan</th>
                    <td><?php echo rupiah($data['tagihan']); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <?php if ($data['status'] == 'BL') { ?>
                            <span class="badge badge-danger">Belum Bayar</span>
                        <?php } elseif ($data['status'] == 'LS') { ?>
                            <span class="badge badge-info">Lunas</span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <th>Tgl Bayar</th>
                    <td><?php echo date("d-M-Y", strtotime($data['tgl_bayar'])); ?></td>
                </tr>
            </table>

            <hr>

            <!-- ✅ QR Code untuk validasi pembayaran -->
            <div class="qrcode">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?php echo urlencode($data['id_tagihan']); ?>" alt="QR Code">
            </div>

        <?php } ?>

        <div class="footer">
            Terima kasih telah melakukan pembayaran.<br>
            *** Simpan struk ini sebagai bukti pembayaran ***
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>
