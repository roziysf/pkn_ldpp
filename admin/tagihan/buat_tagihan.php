<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border" class="btn btn-danger">
					<h3 class="box-title">BUAT TAGIHAN</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
						<button type="button" class="btn btn-box-tool" data-widget="remove">
							<i class="fa fa-remove"></i>
						</button>
					</div>
				</div>

				<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
					<div class="box-body">

						<div class="form-group">
							<label class="col-sm-2 control-label">Bulan</label>
							<div class="col-sm-4">
								<select name="bulan" id="bulan" class="form-control select2" style="width: 100%;" required>
									<option selected="selected">-- Pilih Bulan --</option>
									<?php
									// ambil data dari database
									$query = "SELECT * FROM tb_bulan";
									$hasil = mysqli_query($koneksi, $query);
									while ($row = mysqli_fetch_array($hasil)) {
									?>
										<option value="<?php echo $row['id_bulan'] ?>">
											<?php echo $row['id_bulan'] ?> - <?php echo $row['bulan'] ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Tahun</label>
							<div class="col-sm-4">
								<select name="tahun" id="tahun" class="form-control select2" style="width: 100%;" required>
									<option>-- Pilih Tahun --</option>
									<option>2024</option>
									<option>2025</option>
									<option>2026</option>
								</select>
							</div>
						</div>

						<!-- TABEL PELANGGAN -->
						<div class="box-footer">
							<button type="button" class="btn btn-success" onclick="pilihSemua()">Pilih Semua</button>
							<button type="button" class="btn btn-danger" onclick="batalPilihSemua()">Batal Pilih Semua</button>

							<div class="table-responsive" style="margin-top:10px;">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Pilih</th>
											<th>No</th>
											<th>ID pelanggan</th>
											<th>Nama</th>
											<th>Paket</th>
											<th>Tagihan (Rp.)</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$no = 1;
										$sql = $koneksi->query("SELECT p.id_pelanggan, p.nama, k.id_paket, k.paket, k.tarif 
																FROM tb_pelanggan p 
																INNER JOIN tb_paket k ON p.id_paket=k.id_paket 
																ORDER BY id_pelanggan");
										while ($data = $sql->fetch_assoc()) {
										?>
											<tr>
												<td>
													<input type="checkbox" class="checkbox-pilih" name="pilih[]" value="<?php echo $data['id_pelanggan']; ?>">
												</td>
												<td><?php echo $no++; ?></td>
												<td>
													<input type="text" class="form-control" value="<?php echo $data['id_pelanggan']; ?>" readonly>
												</td>
												<td>
													<input type="text" class="form-control" value="<?php echo $data['nama']; ?>" readonly>
												</td>
												<td>
													<input type="text" class="form-control" value="<?php echo $data['paket']; ?>" readonly>
												</td>
												<td>
													<input type="text" class="form-control" value="<?php echo $data['tarif']; ?>" readonly>
												</td>
												<!-- Hidden Input untuk Tarif dan ID Paket -->
												<input type="hidden" name="tarif[<?php echo $data['id_pelanggan']; ?>]" value="<?php echo $data['tarif']; ?>">
												<input type="hidden" name="id_paket[<?php echo $data['id_pelanggan']; ?>]" value="<?php echo $data['id_paket']; ?>">
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="box-footer">
							<a href="?page=buka-tagihan" class="btn btn-warning">Batal</a>
							<input type="submit" name="Simpan" value="Buat Tagihan" class="btn btn-primary">
						</div>
					</div>
				</form>

				<?php
				if (isset($_POST['Simpan'])) {

					$bulan = $_POST['bulan'];
					$tahun = $_POST['tahun'];
					$pilih = $_POST['pilih']; // hanya pelanggan yang dicentang

					if (!empty($pilih)) {
						foreach ($pilih as $id_pelanggan) {
							$tarif = $_POST['tarif'][$id_pelanggan];
							$id_paket = $_POST['id_paket'][$id_pelanggan];

							$sql_simpan = "INSERT INTO tb_tagihan (bulan, tahun, id_pelanggan, id_paket, tagihan, status) VALUES (
								'$bulan',
								'$tahun',
								'$id_pelanggan',
								'$id_paket',
								'$tarif',
								'BL')";
							$query_simpan = mysqli_query($koneksi, $sql_simpan);
						}
					}

					if ($query_simpan) {
						mysqli_close($koneksi);
						echo "<script>
							Swal.fire({title: 'Buat Tagihan Berhasil', text: '', icon: 'success', confirmButtonText: 'OK'
							}).then((result) => {
								if (result.value) {
									window.location = 'index.php?page=buka-tagihan';
								}
							})</script>";
					} else {
						echo "<script>
							Swal.fire({title: 'Buat Tagihan Gagal', text: '', icon: 'error', confirmButtonText: 'OK'
							}).then((result) => {
								if (result.value) {
									window.location = 'index.php?page=buat-tagihan';
								}
							})</script>";
					}
				}
				?>
			</div>
		</div>
	</div>
</section>

<script>
	function pilihSemua() {
		document.querySelectorAll('.checkbox-pilih').forEach(cb => cb.checked = true);
	}
	function batalPilihSemua() {
		document.querySelectorAll('.checkbox-pilih').forEach(cb => cb.checked = false);
	}
</script>
