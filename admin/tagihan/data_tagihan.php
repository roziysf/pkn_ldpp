<?php
	$bulan = $_POST["bulan"];
	$tahun = $_POST["tahun"];
?>
<?php
	$sql = $koneksi->query("SELECT * from tb_bulan where id_bulan='$bulan'");
	while ($data= $sql->fetch_assoc()) {
	
		$bl=$data['bulan'];
	}
?>


<section class="content">

	<div class="alert alert-warning alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<h4>
			<i class="icon fa fa-info"></i> Data Tagihan</h4>
		<h4>Bulan :
			<?php echo $bl; ?>- Tahun :
			<?php echo $tahun; ?>
		</h4>
	</div>

	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">DATA TAGIHAN</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse">
					<i class="fa fa-minus"></i>
				</button>
				<button type="button" class="btn btn-box-tool" data-widget="remove">
					<i class="fa fa-remove"></i>
				</button>

			</div>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="table-responsive">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>ID PELANGGAN</th>
							<th>Nama</th>
							<th>Tagihan</th>
							<th>Status</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>

						<?php
$no = 1;
$sql = $koneksi->query("SELECT p.id_pelanggan, p.nama, p.no_hp, t.id_tagihan, t.tagihan, t.status, t.tgl_bayar 
    FROM tb_pelanggan p 
    INNER JOIN tb_tagihan t ON p.id_pelanggan=t.id_pelanggan 
    WHERE t.bulan='$bulan' AND t.tahun='$tahun' AND t.status='BL'
    ORDER BY t.tahun DESC, t.bulan DESC, t.tgl_bayar DESC, t.id_tagihan DESC");

while ($data = $sql->fetch_assoc()) {
?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= $data['id_pelanggan']; ?></td>
    <td><?= $data['nama']; ?></td>
    <td><?= rupiah($data['tagihan']); ?></td>
    <td>
        <?php if ($data['status'] == 'BL') { ?>
            <span class="label label-danger">Belum Bayar</span>
        <?php } elseif ($data['status'] == 'LS') { ?>
            <span class="label label-primary">Lunas</span> (<?= $data['tgl_bayar']; ?>)
        <?php } ?>
    </td>
    <td>
        <a href="?page=bayar-tagihan&kode=<?= $data['id_tagihan']; ?>" class="btn btn-info">
            <i class="glyphicon glyphicon-ok"></i> BAYAR
        </a>
        <a href="https://api.whatsapp.com/send?phone=<?= $data['no_hp']; ?>&text=Salam,%20Bpk/Ibu/Sdr/i%20<?= $data['nama']; ?>,
        %0AMohon%20untuk%20melakukan%20pembayaran%20Tagihan%20Internet%20untuk%20Bulan%20<?= $bulan; ?>%20Tahun%20<?= $tahun; ?>.%20
        Pembayaran%20bisa%20dengan%20transfer%20ke:%0ARek. Mandiri%0ANo:%200383888888%0Aa.n:%20AdminKtmNet
        %0A%0AKirim%20Bukti%20Pembayaran%20ke%20WA%20ini.%20Terima%20kasih%0A%0A*Admin KTM Cell*" 
        target="_blank" class="btn btn-gray">
            <img src="dist/img/wa2.png">
        </a>
    </td>
</tr>
<?php } ?>

					</tbody>

				</table>
				<!-- /.box-body -->
				<div class="box-footer">
					<a href="?page=buka-tagihan" class="btn btn-primary">Kembali</a>
				</div>
			</div>
		</div>
	</div>
</section>