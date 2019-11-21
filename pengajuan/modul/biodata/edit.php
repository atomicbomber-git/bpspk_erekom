<?php

use App\Models\Biodata;

$sql->get_row('tb_biodata', array('ref_iduser' => U_ID), '*');
if ($sql->num_rows > 0) {
	$row = $sql->result;
	$tmp_lahir = $row['tmp_lahir'];
	$tgl_lahir = date("m/d/Y", strtotime($row['tgl_lahir']));
	$no_ktp = $row['no_ktp'];
	$no_telp = $row['no_telp'];
	$npwp = $row['npwp'];
	$nm_perusahaan = $row['nm_perusahaan'];
	$siup = $row['siup'];
	$izin_lainnya = $row['izin_lain'];
	$nib = $row['nib'];
	$sipji = $row['sipji'];

	$gudang_1 = $row['gudang_1'];
	$gudang_2 = $row['gudang_2'];
	$gudang_3 = $row['gudang_3'];
} else {
	$tmp_lahir = "";
	$tgl_lahir = "";
	$no_ktp = "";
	$no_telp = "";
	$npwp = "";
	$nm_perusahaan = "";
	$siup = "";
	$izin_lainnya = "";
	$nib = "";
	$sipji = "";
	
	$gudang_1 = "";
	$gudang_2 = "";
	$gudang_3 = "";
}
?>
<form id="form_biodata" method="post" enctype="multipart/form-data">
	<input type="hidden" name="a" value="edbio">
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
					</div>
					<h2 class="panel-title">Biodata Pemohon</h2>
					<p class="panel-subtitle">Silakan Lengkapi Biodata Anda</p>
				</header>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label col-md-3">Nama Perusahaan / Perseorangan<small>*</small></label>
						<div class="col-md-6">
							<input type="text" readonly name="nm_lengkap" class="form-control" value="<?php echo U_NAME; ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Tempat,Tanggal Lahir <small>*</small></label>
						<div class="col-md-4">
							<input type="text" name="tmp_lahir" class="form-control" placeholder="Tempat Lahir" value="<?php echo $tmp_lahir; ?>">
						</div>
						<div class="col-md-3">
							<input type="text" name="tgl_lahir" data-plugin-datepicker class="form-control" value="<?php echo $tgl_lahir; ?>">
							<p><small>cth : 12/01/1992 (bulan/hari/tahun)</small></p>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3">No Identitas (KTP) <small>*</small></label>
						<div class="col-md-4">
							<input type="text" name="no_ktp" class="form-control" value="<?php echo $no_ktp; ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Kartu KTP</label>
						<div class="col-md-5">
							<input type="file" accept="image/*" name="file_ktp" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan Kartu KTP Anda. (Hanya Gambar:png,jpg,jpeg)</p>
						</div>
						<div class="col-md-4">
							<p>KTP</p>
							<?php
							$n = $sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='" . U_ID . "' AND jenis_berkas='4' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if ($n->rowCount() > 0) {
								$img_ktp = $n->fetch();
								echo '<img width="100%" href="' . BERKAS . $img_ktp['nama_file'] . '" src="' . BERKAS . $img_ktp['nama_file'] . '" class="img-prev">';
							} else {
								echo '<p class="text-alert alert-warning">KTP Belum diupload</p>';
							}

							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">No Telp <small>*</small></label>
						<div class="col-md-3">
							<input type="text" name="no_telp" class="form-control" value="<?php echo $no_telp; ?>">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3">
							Gudang 1 <small>*</small>
						</label>
						<div class="col-md-5">
							<textarea class="form-control" row="3" name="gudang_1"><?= $gudang_1 ?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3">
							Gudang 2
						</label>
						<div class="col-md-5">
							<textarea class="form-control" row="3" name="gudang_2"><?= $gudang_2 ?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3">
							Gudang 3
						</label>
						<div class="col-md-5">
							<textarea class="form-control" row="3" name="gudang_3"><?= $gudang_3 ?></textarea>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3">NPWP</label>
						<div class="col-md-3">
							<input type="text" name="npwp" class="form-control" value="<?php echo $npwp; ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Kartu NPWP</label>
						<div class="col-md-5">
							<input type="file" accept="image/*" name="file_npwp" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan Kartu NPWP Anda. (Hanya Gambar:png,jpg,jpeg)</p>
						</div>
						<div class="col-md-4">
							<p>NPWP</p>
							<?php
							$n = $sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='" . U_ID . "' AND jenis_berkas='2' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if ($n->rowCount() > 0) {
								$img_npwp = $n->fetch();
								echo '<img width="100%" href="' . BERKAS . $img_npwp['nama_file'] . '" src="' . BERKAS . $img_npwp['nama_file'] . '" class="img-prev">';
							} else {
								echo '<p class="text-alert alert-warning">NPWP Belum diupload</p>';
							}

							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Nama Perusahaan</label>
						<div class="col-md-5">
							<input type="text" name="nm_perusahaan" class="form-control" value="<?php echo $nm_perusahaan; ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Nomor SIUP</label>
						<div class="col-md-4">
							<input type="text" name="siup" class="form-control" value="<?php echo $siup; ?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Berkas SIUP</label>
						<div class="col-md-5">
							<input type="file" accept="image/*" name="siup" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan SIUP Anda. (Hanya Gambar:png,jpg,jpeg)</p>
						</div>
						<div class="col-md-4">
							<p>SIUP</p>
							<?php
							$s = $sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='" . U_ID . "' AND jenis_berkas='3' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if ($s->rowCount() > 0) {
								$img_siup = $s->fetch();
								echo '<img width="100%" href="' . BERKAS . $img_siup['nama_file'] . '" src="' . BERKAS . $img_siup['nama_file'] . '" class="img-prev">';
							} else {
								echo '<p class="text-alert alert-warning">SIUP Belum diupload</p>';
							}
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3">Nomor NIB*</label>
						<div class="col-md-4">
							<input type="text" name="nib" class="form-control" value="<?php echo $nib; ?>">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3">Berkas NIB*</label>
						<div class="col-md-5">
							<input type="file" accept="image/*" name="nib" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan NIB Anda. (Hanya Gambar:png,jpg,jpeg)</p>
						</div>
						<div class="col-md-4">
							<p>NIB</p>
							<?php
							$s = $sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='" . U_ID . "' AND jenis_berkas='5' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if ($s->rowCount() > 0) {
								$img_nib = $s->fetch();
								echo '<img width="100%" href="' . BERKAS . $img_nib['nama_file'] . '" src="' . BERKAS . $img_nib['nama_file'] . '" class="img-prev">';
							} else {
								echo '<p class="text-alert alert-warning">NIB Belum diupload</p>';
							}
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3">Nomor SIPJI</label>
						<div class="col-md-4">
							<input type="text" name="sipji" class="form-control" value="<?php echo $sipji; ?>">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3">Berkas SIPJI</label>
						<div class="col-md-5">
							<input type="file" accept="image/*" name="sipji" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan SIPJI Anda. (Hanya Gambar:png,jpg,jpeg)</p>
						</div>
						<div class="col-md-4">
							<p>SIPJI</p>
							<?php
							$s = $sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='" . U_ID . "' AND jenis_berkas='6' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if ($s->rowCount() > 0) {
								$img_sipji = $s->fetch();
								echo '<img width="100%" href="' . BERKAS . $img_sipji['nama_file'] . '" src="' . BERKAS . $img_sipji['nama_file'] . '" class="img-prev">';
							} else {
								echo '<p class="text-alert alert-warning">SIPJI Belum diupload</p>';
							}
							?>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3">Izin Usaha Lainnya</label>
						<div class="col-md-5">
							<textarea class="form-control" row="4" name="izin_lainnya"><?php echo $izin_lainnya; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Berkas Scan Tandatangan*</label>
						<div class="col-md-5">
							<input type="file" accept="image/*" name="ttd" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan Tandatangan Anda. (Hanya Gambar:png,jpg,jpeg)</p>
						</div>
						<div class="col-md-4">
							<p>Tandatangan</p>
							<?php
							$t = $sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='" . U_ID . "' AND jenis_berkas='1' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if ($t->rowCount() > 0) {
								$img_npwp = $t->fetch();
								echo '<img width="100%" href="' . BERKAS . $img_npwp['nama_file'] . '" src="' . BERKAS . $img_npwp['nama_file'] . '" class="img-prev">';
							} else {
								echo '<p class="text-alert alert-warning">Tandatangan Belum diupload</p>';
							}
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3"></label>
						<div class="col-md-5">
							<p>*) Harus Diisi.</p>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-9">
							<button class="btn btn-primary btn-sm btn_simpan" type="submit">Simpan Biodata</button>
							<p id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</p>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</form>