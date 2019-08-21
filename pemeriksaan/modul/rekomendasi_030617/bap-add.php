<?php 
	$l=$sql->run("SELECT DISTINCT(rdi.nama_ikan) nama_ikan FROM tb_hsl_periksa thp JOIN ref_data_ikan rdi ON (rdi.id_ikan=thp.ref_idikan) WHERE thp.ref_idp='$idpengajuan'");
	$barang=array();
	foreach($l->fetchAll() as $brg){
		$barang[]=$brg['nama_ikan'];
	}
	$list_brg=implode(', ', $barang);

	$last=$sql->run("SELECT no_surat FROM tb_bap ORDER BY id_bap DESC, tgl_surat DESC LIMIT 1");
	$r=$last->fetch();
?>
<form method="post" class="form-horizontal" id="bap_add" action="">
	<input type="hidden" name="a" value="bapsv" />
	<input type="hidden" name="token" value="<?php echo md5($idpengajuan.U_ID."bap");?>">
	
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Berita Acara Pemeriksaan</h3>
		</div>
		<div class="box-body">
			<div class="form-group">
				<label class="control-label col-md-3">No Surat</label>
				<div class="col-md-5">
					<input type="text" class="form-control" name="no_surat" id="no_surat">
					<p class="text-alert alert-info">No Surat Terakhir : <strong><?php echo $r['no_surat'];?></strong></p>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Tanggal Penetapan</label>
				<div class="col-md-4">
					<input type="text" class="form-control datepicker" name="tgl_penetapan" value="<?php echo date ('m/d/Y');?>">
					 <small class="text-alert alert-danger">Format : Bulan/Hari/Tahun (mm/dd/yyyy)</small>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Lokasi Penetapan</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="lokasi_penetapan">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Redaksi Hasil Pemeriksaan</label>
				<div class="col-md-8">
					<textarea name="redaksi_bap" rows="5" class="editor form-control">Berdasarkan hasil pemeriksaan sampel yang dilakukan secara uji visual, menunjukkan bahwa sampel <?php echo $list_brg;?> pada lampiran tidak termasuk / termasuk jenis Ikan Hiu dan Ikan Pari yang dilarang.</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Petugas 1</label>
				<div class="col-md-6">
					<select name="ptg1" class="form-control">
						<option value="">-Pilih-</option>
						<?php 
						$a1=$sql->run("SELECT pl.ref_idpeg,p.nip,p.nm_lengkap FROM tb_petugas_lap pl JOIN op_pegawai p ON(p.idp=pl.ref_idpeg) WHERE pl.ref_idp='".$idpengajuan."' ");
						if($a1->rowCount()>0){
							foreach($a1->fetchAll() as $b1){
								echo '<option value="'.$b1['ref_idpeg'].'">'.$b1['nm_lengkap'].' ('.$b1['nip'].')</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Petugas 2</label>
				<div class="col-md-6">
					<select name="ptg2" class="form-control">
						<option value="">-Pilih-</option>
						<?php 
						$a2=$sql->run("SELECT pl.ref_idpeg,p.nip,p.nm_lengkap FROM tb_petugas_lap pl JOIN op_pegawai p ON(p.idp=pl.ref_idpeg) WHERE pl.ref_idp='".$idpengajuan."' ");
						if($a2->rowCount()>0){
							foreach($a2->fetchAll() as $b2){
								echo '<option value="'.$b2['ref_idpeg'].'">'.$b2['nm_lengkap'].' ('.$b2['nip'].')</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="form-group">
				<label class="control-label col-md-3"></label>
				<div class="col-md-5">
					<button class="btn btn-sm btn-primary btn-flat" type="submit">Simpan</button>
					<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
					
				</div>
			</div>
		</div>
	</div>
</form>