<?php

use App\Models\Permohonan;
use App\Models\SatuanBarang;

$sql->get_row('tb_rekomendasi', array('ref_idp' => $idpengajuan));
$dtrek = $sql->result;

$permohonan = Permohonan::where("idp", $idpengajuan)
	->with("rekomendasi")
	->get();

$satuan_barangs = SatuanBarang::all()->pluck("nama", "id");

?>
<form method="post" class="form-horizontal" id="rek_update" action="">
	<input type="hidden" name="a" value="rekup" />
	<input type="hidden" name="token" value="<?php echo md5($dtrek['idrek'] . U_ID . "rek"); ?>">
	<input type="hidden" name="idrek" ID="rek" value="<?php echo base64_encode($dtrek['idrek']); ?>">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Draft Surat Rekomendasi</h3>
		</div>
		<div class="box-body">
			<div class="form-group">
				<label class="control-label col-md-2">No Surat</label>
				<div class="col-md-5">
					<input type="text" readonly class="form-control" name="no_surat" value="<?php echo $dtrek['no_surat']; ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Tanggal Surat</label>
				<div class="col-md-4">
					<input type="text" class="form-control datepicker" name="tgl_surat" value="<?php echo date("m/d/Y", strtotime($dtrek['tgl_surat'])); ?>">
					<small class="text-alert alert-danger">Format : Bulan/Hari/Tahun (mm/dd/yyyy)</small>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Perihal Surat</label>
				<div class="col-md-4">
					<input type="text" class="form-control" name="perihal" value="Rekomendasi">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Tujuan Kepada</label>
				<div class="col-md-6">
					<?php
					$r = $sql->run("SELECT u.nama_lengkap,p.ref_iduser idu FROM tb_permohonan p JOIN tb_userpublic u ON(p.ref_iduser=u.iduser) WHERE p.idp='$idpengajuan' LIMIT 1");
					$tujuan = $r->fetch();
					?>
					<input type="hidden" readonly name="tujuan" value="<?php echo $tujuan['idu']; ?>">
					<input type="text" readonly class="form-control" name="tujuan_nm" value="<?php echo strtoupper($tujuan['nama_lengkap']); ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Tabel Hasil Pemeriksaan</label>
				<div class="col-md-10 table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<td>Jenis Produk</td>
								<td width="7%">Kemasan</td>
								<td width="12%">Satuan<br>Kemasan</td>
								<td width="12%">No. Segel</td>
								<td width="10%">Berat(Kg)</td>
								<td>Keterangan</td>
							</tr>
						<tbody>
							<?php
								$dt = $sql->run("SELECT thp.* FROM tb_rek_hsl_periksa thp WHERE thp.ref_idrek=$dtrek[idrek]");
								
							if ($dt->rowCount() > 0) {
								$tombol = 1;
								foreach ($dt->fetchAll() as $row) {
									?>
									<tr class="dt">
										<td>
											<input
												type="hidden"
												name="jenis_ikan[]"
												value="<?= $row['ref_idikan']; ?>">
											
											<input 
												type="hidden"
												name="jenis_sampel[]"
												value="<?= $row['id_ref']; ?>">

											<input 
												type="hidden"
												name="produk[]"
												value="<?= $row['produk']; ?>">

											<input 
												type="hidden"
												name="kondisi_produk[]"
												value="<?= $row['kondisi_produk']; ?>">

											<input 
												type="hidden"
												name="jenis_produk[]"
												value="<?= $row['jenis_produk']; ?>">

											<p><?php echo $arr_produk[$row['ref_jns']]['nama']; ?><br />
												<?php echo $arr_ikan[$row['ref_idikan']]['nama'] . " <br><strong>" . $arr_ikan[$row['ref_idikan']]['latin'] . "</strong>"; ?></p>
										</td>
										<td>
											<input 
												type="hidden"
												name="kemasan[]"
												value="<?= $row['kemasan'] ?>">

											<?= $row['kemasan'] ?? '-' ?>
										<td>
											<input 
												type="hidden"
												name="id_satuan_barang[]"
												value="<?= $row['id_satuan_barang'] ?>">

											<?= $satuan_barangs[$row['id_satuan_barang']] ?? '-' ?>
										</td>
										<td>
											<input
												style="display: inline-block;"
												class="nosegel form-control"
												type="text"
												name="nosegel[]"
												class="form-control"
												value="<?= $row["no_segel"] ?? "" ?>"
												>

											<div
												style="
													display: inline-block;
													text-align: center;
												"
												>
												s/d
											</div>

											<input
												style="display: inline-block;"
												class="nosegel form-control"
												type="text"
												name="nosegel_akhir[]"
												class="form-control"`
												value="<?= $row["no_segel_akhir"] ?? "" ?>"
												>
										<td>
											<?php echo floatval($row['berat']); ?> Kg
											<input type="hidden" name="berat[]" value="<?php echo floatval($row['berat']); ?>">
										</td>
										<td><input type="text" name="keterangan[]" class="form-control" value="<?php echo $row['keterangan']; ?>"></td>
									</tr>
									<?php
										}
									} else {
										$tombol = 0;
										$dt = $sql->run("SELECT
											thp.tot_berat as berat,
											thp.kuantitas as kemasan, 
											thp.id_satuan_barang, 
											thp.ref_idikan,
											thp.produk,
											thp.kondisi_produk,
											thp.jenis_produk,
											rdi.nama_ikan,
											rdi.nama_latin
												FROM tb_hsl_periksa thp
											LEFT JOIN ref_data_ikan rdi ON(rdi.id_ikan=thp.ref_idikan)
												WHERE thp.ref_idp='$idpengajuan' 
												ORDER BY ref_jns_sampel ASC"
										
										);
										

										if ($dt->rowCount() > 0) {
											foreach ($dt->fetchAll() as $row) {
												?>
										<tr class="dt">
											<td>
												<input
													type="hidden"
													name="jenis_ikan[]"
													value="<?= $row['ref_idikan']; ?>">
												
												<input 
													type="hidden"
													name="jenis_sampel[]"
													value="<?= $row['id_ref']; ?>">

												<input 
													type="hidden"
													name="produk[]"
													value="<?= $row['produk']; ?>">

												<input 
													type="hidden"
													name="kondisi_produk[]"
													value="<?= $row['kondisi_produk']; ?>">

												<input 
													type="hidden"
													name="jenis_produk[]"
													value="<?= $row['jenis_produk']; ?>">

												<p><?php echo $arr_produk[$row['id_ref']]['nama']; ?><br />
													<?php echo $arr_ikan[$row['ref_idikan']]['nama'] . " <br><strong>" . $arr_ikan[$row['ref_idikan']]['latin'] . "</strong>"; ?></p>
											</td>
											<td>
												<input 
													type="hidden"
													name="kemasan[]"
													value="<?= $row['kemasan'] ?>">

												<?= $row['kemasan'] ?? '-' ?>
											</td>
											<td>
												<input 
													type="hidden"
													name="id_satuan_barang[]"
													value="<?= $row['id_satuan_barang'] ?>">

												<?= $satuan_barangs[$row['id_satuan_barang']] ?? '-' ?>
											</td>
											<td>
												<input
													style="display: inline-block;"
													class="nosegel form-control"
													type="text"
													name="nosegel[]"
													class="form-control">

												<div
													style="
														display: inline-block;
														text-align: center;
													"
													>
													s/d
												</div>

												<input
													style="display: inline-block;"
													class="nosegel form-control"
													type="text"
													name="nosegel_akhir[]"
													class="form-control">
											</td>
											<td>
												<?php echo floatval($row['berat']); ?> Kg
												<input type="hidden" name="berat[]" value="<?php echo floatval($row['berat']); ?>">
											</td>
											<td><input type="text" name="keterangan[]" class="form-control" value="<?php echo $arr_produk[$row['id_ref']]['nama']; ?>"></td>
										</tr>
							<?php
									}
								}
							}
							?>
						</tbody>
						<tfoot>
							<tr id="addrow">
								<td colspan="7">
									<p> Catatan: </p>
									
									<p>
										<span class="text-alert alert-danger"> Angka Desimal Menggunakan . <strong>(titik) cth: 90.2Kg</strong></span>	
									</p>

									<p>
										<span class="text-alert alert-danger">
											Nomor segel harus diisi 4 digit. Cth: 0001
										</span>
									</p>

									<?php if ($tombol == 1) { ?>
										<p>Penting : <span class="text-alert alert-danger">Jika terdapat <strong>ketidaksesuaian</strong> pada tabel hasil pemeriksaan dengan hasil pemeriksaan lapangan dapat menekan tombol "Reload Ulang" untuk mengambil ulang data dari hasil pemeriksaan lapangan.</p>
										<a href="#" id="btn_reload_data" data-idrek="<?php echo base64_encode($dtrek['idrek']); ?>" class="btn btn-sm btn-default btn-danger btn-flat">Reload Ulang</a>
									<?php } ?>
								</td>
							</tr>
						</tfoot>
						</thead>
					</table>
					
					<script>
					 	window.onload = function() {
							document.querySelectorAll('input.nosegel')
								.forEach(nosegel_input => {
									new Cleave(nosegel_input, {
										numeral: true,
										stripLeadingZeroes: false,
										numeralDecimalMark: '',
										delimiter: '',
										numeralIntegerScale: 4,
										numeralDecimalScale: 0
									});
								})
						}
					</script>


				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Redaksi<br>Surat Rekomendasi</label>
				<div class="col-md-8">
					<textarea name="redaksi_rek" rows="5" class="form-control editor"><?php echo $dtrek['redaksi']; ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Mengetahui Kepala/Plh</label>
				<div class="col-md-5">
					<select name="penandatgn" class="form-control sl2">
						<option value="">-Pilih-</option>
						<?php
						$a = $sql->run("SELECT u.nm_lengkap, p.nip FROM op_user u JOIN op_pegawai p ON(u.ref_idpeg=p.idp) WHERE u.lvl IN(91,90) AND u.status='2' ");
						if ($a->rowCount() > 0) {
							foreach ($a->fetchAll() as $b) {
								if ($b['nip'] == $dtrek['pnttd']) {
									echo '<option selected value="' . $b['nip'] . '">' . $b['nm_lengkap'] . ' (' . $b['nip'] . ')</option>';
								} else {
									echo '<option value="' . $b['nip'] . '">' . $b['nm_lengkap'] . ' (' . $b['nip'] . ')</option>';
								}
							}
						}
						?>
					</select>
				</div>
			</div>

			<?php
				$sql->get_all('ref_balai_karantina', array(), array('idbk', 'nama'));
			?>
			
			<div class="form-group">
				<label class="control-label col-md-2">Tembusan Balai Karantina I</label>
				<div class="col-md-4">
					<select name="tembusan_bk" class="form-control sl2">
						<option value="">-Pilih-</option>

						<?php foreach ($sql->result as $balai_karantina) : ?>
							<option 
								value="<?= $balai_karantina['idbk'] ?> "
								<?= $dtrek["ref_bk"] == $balai_karantina['idbk'] ? "selected" : "" ?>
								>
								<?= $balai_karantina['nama'] ?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-2">Tembusan Balai Karantina II</label>
				<div class="col-md-4">
					<select name="tembusan_bk_2" class="form-control sl2">
						<option value="">-Pilih-</option>
						<?php foreach ($sql->result as $balai_karantina) : ?>
							<option 
								<?= $dtrek["ref_bk_2"] == $balai_karantina['idbk'] ? "selected" : "" ?>
								value="<?= $balai_karantina['idbk'] ?> ">
								<?= $balai_karantina['nama'] ?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-2">Tembusan PSDKP</label>
				<div class="col-md-4">
					<select name="tembusan_psdkp" class="form-control sl2">
						<option value="">-Pilih-</option>
						<?php
						$sql->get_all('ref_psdkp', array('isDelete' => 0), array('id_psd', 'nama'));
						if ($sql->num_rows > 0) {
							foreach ($sql->result as $c) {
								if ($c['id_psd'] == $dtrek['ref_psdkp']) {
									echo '<option selected value="' . $c['id_psd'] . '">' . $c['nama'] . '</option>';
								} else {
									echo '<option value="' . $c['id_psd'] . '">' . $c['nama'] . '</option>';
								}
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">UPT PRL Penerima</label>
				<div class="col-md-4">
					<select name="upt_prl_penerima" class="form-control sl2">
						<option value="">-Pilih-</option>
						<?php
						$sql->get_all('ref_upt_prl', array('isDelete' => 0), array('id_upt', 'nama'));
						if ($sql->num_rows > 0) {
							foreach ($sql->result as $c) {
								if ($c['id_upt'] == $dtrek['ref_uptprl']) {
									echo '<option selected value="' . $c['id_upt'] . '">' . $c['nama'] . '</option>';
								} else {
									echo '<option value="' . $c['id_upt'] . '">' . $c['nama'] . '</option>';
								}
							}
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="form-group">
				<label class="control-label col-md-2"></label>
				<div class="col-md-5">
					<button class="btn btn-sm btn-primary btn-flat" id="btn_simpan" type="submit">Simpan Perubahan</button>
					<a class="btn btn-sm btn-info btn-flat" href="surat-rekomendasi.php?token=<?php echo md5($dtrek['idrek'] . U_ID . 'surat_rekomendasi'); ?>&rek=<?php echo base64_encode($dtrek['idrek']); ?>">Lihat Surat</a>
					<!-- <button class="btn btn-sm btn-warning" id="btn_submit" data-token="<?php echo md5($dtrek['idrek'] . U_ID . 'submit'); ?>" type="button">Kirim ke Kepala Balai/Plh Untuk Disahkan</button> -->
					<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan...</span>
					<!-- <span id="actsubmit" style="display:none"><i class="fa fa-spin fa-spinner"></i> Mengirim...</span> -->

				</div>
			</div>
		</div>
	</div>
</form>