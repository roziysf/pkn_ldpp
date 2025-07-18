<?php
include "inc/koneksi.php";
?>

<section class="content">
    <div class="row">
        <div class="col-md-12">

      

            <!-- TABEL -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">DATA TAGIHAN</h3>
                </div>

                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Pelanggan</th>
                                <th>Nama</th>
                                <th>Tagihan</th>
								<th>Paket</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Status</th>
                                <th>Tanggal Bayar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($bulanDipilih) && !empty($tahunDipilih)) {
                                $sql = $koneksi->query("SELECT t.*, p.nama, p.no_hp
                                    FROM tb_tagihan t 
                                    INNER JOIN tb_pelanggan p ON t.id_pelanggan = p.id_pelanggan
                                    WHERE t.bulan = '$bulanDipilih' AND t.tahun = '$tahunDipilih'
                                    ORDER BY t.tahun DESC, t.bulan DESC, t.tgl_bayar DESC, t.id_tagihan DESC");
                            } else {
                                $sql = $koneksi->query("SELECT t.*, p.nama, p.no_hp
                                    FROM tb_tagihan t 
                                    INNER JOIN tb_pelanggan p ON t.id_pelanggan = p.id_pelanggan
                                    ORDER BY t.tahun DESC, t.bulan DESC, t.tgl_bayar DESC, t.id_tagihan DESC");
                            }

                            $no = 1;
                            while ($data = $sql->fetch_assoc()) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $data['id_pelanggan']; ?></td>
                                    <td><?= $data['nama']; ?></td>
                                    <td><?= rupiah($data['tagihan']); ?></td>
                                    <td><?= $data['id_paket']; ?></td>
                                    <td><?= $data['bulan']; ?></td>
                                    <td><?= $data['tahun']; ?></td>
                                    <td>
                                        <?php if ($data['status'] == 'BL') { ?>
                                            <span class="label label-danger">Belum Bayar</span>
                                        <?php } elseif ($data['status'] == 'LS') { ?>
                                            <span class="label label-primary">Lunas</span>
                                        <?php } ?>
                                    </td>
                                    <td><?= $data['tgl_bayar'] ?? '-'; ?></td>
                                    
									<td>
										<?php
										if ($data['status']== "LS") {
											?>

		
													<a href="./report/cetak_struk.php?id_tagihan=<?php echo $data['id_tagihan']; ?>"
													target=" _blank" title="Cetak Struk" class="btn btn-primary">
														<i class="glyphicon glyphicon-print"></i> Struk</a>
											<?php

										}else{
											?>
										


                                        <a href="?page=bayar-tagihan&kode=<?= $data['id_tagihan']; ?>" class="btn btn-info">
                                            <i class="glyphicon glyphicon-ok"></i> BAYAR
                                        </a>
                                        <a href="https://api.whatsapp.com/send?phone=<?= $data['no_hp']; ?>&text=Salam,%20Bpk/Ibu/Sdr/i%20<?= $data['nama']; ?>,%0A
                                            Mohon%20melakukan%20pembayaran%20tagihan%20internet%20bulan%20<?= $data['bulan']; ?>%20tahun%20<?= $data['tahun']; ?>.%0A
                                            Tagihan: <?= rupiah($data['tagihan']); ?>%0A
                                            Transfer ke:%0ARek. Mandiri%0ANo: 0383888888%0Aa.n: AdminKtmNet%0A
                                            Mohon kirim bukti ke WA ini.%0A*Admin KTM Cell*"
                                            target="_blank" class="btn btn-success">
                                            <img src="dist/img/wa2.png" style="height:20px;">
                                        </a>
											<?php
										}
										?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

               
            </div>

        </div>
    </div>
</section>
