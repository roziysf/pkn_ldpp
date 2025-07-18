<?php
include "../inc/koneksi.php";
include "../inc/rupiah.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran BJ-NET</title>
    <link rel="icon" href="../dist/img/print.jpg">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .struk-container {
            width: 320px;
            margin: 0 auto;
            border: 1px dashed #333;
            padding: 15px;
            text-align: center;
        }

        .struk-header {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .struk-subheader {
            font-size: 14px;
            margin-bottom: 10px;
        }

        hr {
            border: none;
            border-top: 1px dashed #333;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        table th,
        table td {
            padding: 4px;
            border-bottom: 1px dashed #ccc;
        }

        table th {
            text-align: left;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            color: #fff;
            font-size: 11px;
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
            text-align: center;
            border-top: 1px dashed #333;
            padding-top: 5px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .struk-container {
                border: none;
            }
        }
    </style>
</head>

<body>

    <div class="struk-container">
        <div class="struk-header">** STRUK PEMBAYARAN **</div>
        <div class="struk-subheader">TAGIHAN LAYANAN BJ-NET</div>
        <hr>

        <?php
        $id = $_GET['id_tagihan'];
        $sql_tampil = "SELECT p.id_pelanggan, p.nama, p.no_hp, t.id_tagihan, t.tagihan, 
                        t.status, t.tgl_bayar, t.bulan, t.tahun, k.id_paket, k.paket
                        FROM tb_pelanggan p 
                        INNER JOIN tb_tagihan t ON p.id_pelanggan=t.id_pelanggan
                        INNER JOIN tb_paket k ON k.id_paket=p.id_paket
                        WHERE status='LS' AND id_tagihan='$id'";
        $query_tampil = mysqli_query($koneksi, $sql_tampil);
        while ($data = mysqli_fetch_array($query_tampil, MYSQLI_BOTH)) {
        ?>
            <table>
                <tr>
                    <th>Pelanggan</th>
                    <td><?php echo $data['id_pelanggan'] . " - " . $data['nama']; ?></td>
                </tr>
                <tr>
                    <th>Paket</th>
                    <td><?php echo $data['paket']; ?></td>
                </tr>
                <tr>
                    <th>Bulan/Tahun</th>
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
