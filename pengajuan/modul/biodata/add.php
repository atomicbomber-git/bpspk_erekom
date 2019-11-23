<form id="form_biodata" method="post" enctype="multipart/form-data">
	<input type="hidden" name="a" value="adbio">
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
						<label class="control-label col-md-3">Nama Perusahaan / Perseorangan <small>*</small></label>
						<div class="col-md-6">
							<input type="text" name="nm_lengkap" readonly class="form-control" value="<?php echo U_NAME;?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Tempat,Tanggal Lahir <small>*</small></label>
						<div class="col-md-4">
							<input type="text" name="tmp_lahir" class="form-control" placeholder="Tempat Lahir">
						</div>
						<div class="col-md-3">
							<input type="text" name="tgl_lahir" data-plugin-datepicker class="form-control" >
							<p><small>cth : 12/01/1992 (bulan/hari/tahun)</small></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-3">No Identitas (KTP) <small>*</small></label>
						<div class="col-md-4">
							<input type="text" name="no_ktp" class="form-control" >
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Berkas KTP</label>
						<div class="col-md-5">
							<input type="file" name="file_ktp" accept="image/*" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan KTP Anda. (Hanya Gambar:png,jpg,jpeg, Size Maksimal 2Mb)</p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">No Telp <small>*</small></label>
						<div class="col-md-3">
							<input type="text" name="no_telp" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Alamat Rumah <small>*</small></label>
						<div class="col-md-5">
							<textarea class="form-control" row="3" name="alamat_rmh"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">NPWP</label>
						<div class="col-md-3">
							<input type="text" name="npwp" class="form-control" >
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Kartu NPWP</label>
						<div class="col-md-5">
							<input type="file" name="file_npwp" accept="image/*" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan Kartu NPWP Anda. (Hanya Gambar:png,jpg,jpeg, Size Maksimal 2Mb)</p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Nama Perusahaan</label>
						<div class="col-md-5">
							<input type="text" name="nm_perusahaan" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Nomor SIUP</label>
						<div class="col-md-4">
							<input type="text" name="siup" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Berkas SIUP</label>
						<div class="col-md-5">
							<input type="file" name="siup" accept="image/*" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan SIUP Anda. (Hanya Gambar:png,jpg,jpeg, Size Maksimal 2Mb)</p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Nomor NIB</label>
						<div class="col-md-4">
							<input type="text" name="nib" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Berkas NIB</label>
						<div class="col-md-5">
							<input type="file" name="nib" accept="image/*" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan NIB Anda. (Hanya Gambar:png,jpg,jpeg, Size Maksimal 2Mb)</p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Nomor SIPJI</label>
						<div class="col-md-4">
							<input type="text" name="sipji" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Berkas SIPJI</label>
						<div class="col-md-5">
							<input type="file" name="sipji" accept="image/*" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan SIPJI Anda. (Hanya Gambar:png,jpg,jpeg, Size Maksimal 2Mb)</p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Izin Usaha Lainnya</label>
						<div class="col-md-5">
							<textarea class="form-control" row="4" name="izin_lainnya"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Berkas Scan Tandatangan*</label>
						<div class="col-md-5">
							<input type="file" name="ttd" accept="image/*" class="form-control" value="">
							<p class="text-alert alert-info">Upload Hasil Scan Tandatangan Anda. (Hanya Gambar:png,jpg,jpeg, Size Maksimal 2Mb)</p>
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
						<button class="btn btn-primary btn-sm btn_simpan" type="submit" >Simpan Biodata</button>
						<p id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</p>
					</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</form>