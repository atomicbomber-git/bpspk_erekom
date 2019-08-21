<?php 
	$sql->get_row('tb_bap',array('ref_idp'=>$idpengajuan),array('id_bap','no_surat','tgl_surat','lokasi','redaksi','ptgs1','ptgs2'));
	if($sql->num_rows<1){
		exit();
	}else{
		$row=$sql->result;
	}

?>
<form method="post" class="form-horizontal" id="bap_update" action="">
	<input type="hidden" name="a" value="bapup" />
	<input type="hidden" name="token" value="<?php echo md5($row['id_bap'].U_ID."bap");?>">
	<input type="hidden" name="idbap" value="<?php echo base64_encode($row['id_bap']);?>">
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
					</div>
					<h2 class="panel-title">Berita Acara Pemeriksaan</h2>
				</header>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label col-md-3">No Surat</label>
						<div class="col-md-5">
							<input type="text" class="form-control" name="no_surat" value="<?php echo $row['no_surat'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Tanggal Penetapan</label>
						<div class="col-md-4">
							<input type="text" class="form-control" name="tgl_penetapan" data-plugin-datepicker data-date-orientation="top" value="<?php echo date("m/d/Y", strtotime($row['tgl_surat']));?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Lokasi Penetapan</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="lokasi_penetapan" value="<?php echo $row['lokasi'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Redaksi Hasil Pemeriksaan</label>
						<div class="col-md-8">
							<textarea name="redaksi_bap" rows="5" class="form-control editor"><?php echo $row['redaksi'];?></textarea>
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
										if($row['ptgs1']==$b1['ref_idpeg']){
											echo '<option selected value="'.$b1['ref_idpeg'].'">'.$b1['nm_lengkap'].' ('.$b1['nip'].')</option>';
										}else{
											echo '<option value="'.$b1['ref_idpeg'].'">'.$b1['nm_lengkap'].' ('.$b1['nip'].')</option>';
										}
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
										if($row['ptgs2']==$b2['ref_idpeg']){
											echo '<option selected value="'.$b2['ref_idpeg'].'">'.$b2['nm_lengkap'].' ('.$b2['nip'].')</option>';
										}else{
											echo '<option value="'.$b2['ref_idpeg'].'">'.$b2['nm_lengkap'].' ('.$b2['nip'].')</option>';
										}
									}
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="form-group">
						<label class="control-label col-md-3"></label>
						<div class="com-md-5">
							<button class="btn btn-sm btn-primary" type="submit">Simpan Perubahan</button>
							<a class="btn btn-sm btn-info" href="surat-bap.php?token=<?php echo md5($row['id_bap'].U_ID.'surat_bap');?>&bap=<?php echo base64_encode($row['id_bap']);?>">Lihat Surat</a>
							<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
							
						</div>
					</div>
				</footer>
			</section>
		</div>
	</div>
</form>