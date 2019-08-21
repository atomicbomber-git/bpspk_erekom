<?php 
	$last=$sql->run("SELECT no_surat FROM tb_rekomendasi ORDER BY idrek DESC,date_create DESC, tgl_surat DESC LIMIT 1");
	$r=$last->fetch();
?>
<form method="post" class="form-horizontal" id="rek_add" action="">
	<input type="hidden" name="a" value="reksv" />
	<input type="hidden" name="token" value="<?php echo md5($idpengajuan.U_ID."rek");?>">
	<input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>">
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
					</div>
					<h2 class="panel-title">Draft Surat Rekomendasi</h2>
				</header>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label col-md-3">No Surat</label>
						<div class="col-md-5">
							<input type="text" class="form-control" name="no_surat">
							<p class="text-alert alert-info">No Surat Terakhir : <strong><?php echo $r['no_surat'];?></strong></p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Tanggal Surat</label>
						<div class="col-md-4">
							<input type="text" class="form-control" name="tgl_surat" data-plugin-datepicker data-date-orientation="top">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Perihal Surat</label>
						<div class="col-md-4">
							<input type="text" class="form-control" name="perihal" value="Rekomendasi">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Tujuan Kepada</label>
						<div class="col-md-6">
							<?php 
							$r=$sql->run("SELECT u.nama_lengkap,p.ref_iduser idu FROM tb_permohonan p JOIN tb_userpublic u ON(p.ref_iduser=u.iduser) WHERE p.idp='$idpengajuan' LIMIT 1");
							$tujuan=$r->fetch();
							?>
							<input type="hidden" readonly name="tujuan" value="<?php echo $tujuan['idu'];?>">
							<input type="text" readonly class="form-control" name="tujuan_nm" value="<?php echo $tujuan['nama_lengkap'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Tabel Hasil Permeriksaan</label>
						<div class="col-md-9">
							<table class="table table-bordered">
								<thead>
									<tr>
										<td>Jenis Produk</td>
										<td width="10%">Kemasan</td>
										<td width="12%">Satuan<br>Kemasan</td>
										<td width="12%">No.Segel</td>
										<td width="12%">Berat(Kg)</td>
										<td>Keterangan</td>
										<td width="5%">Aksi</td>
									</tr>
									<tbody>
										<?php
										$dt=$sql->run("SELECT thp.tot_berat as berat,thp.kuantitas as kemasan, rjs.id_ref,rjs.jenis_sampel,rdi.nama_ikan,rdi.nama_latin FROM tb_hsl_periksa thp
											JOIN ref_jns_sampel rjs ON(rjs.id_ref=thp.ref_jns_sampel)
											JOIN ref_data_ikan rdi ON(rdi.id_ikan=thp.ref_idikan)
											WHERE thp.ref_idp='$idpengajuan' 
											ORDER BY ref_jns_sampel ASC");
										if($dt->rowCount()>0){
											foreach($dt->fetchAll() as $row){
											?>
											<tr>
												<td>
													<select class="form-control" name="jenis_sampel[]">
														<option value="">-Pilih-</option>
														<?php
														$sql->get_all('ref_jns_sampel');
														echo $sql->sql;
														if($sql->num_rows>0){
															foreach($sql->result as $r){
																if($row['id_ref']==$r['id_ref']){
																	echo '<option selected value="'.$r['id_ref'].'">'.$r['jenis_sampel'].'</option>';
																}else{
																	echo '<option value="'.$r['id_ref'].'">'.$r['jenis_sampel'].'</option>';
																}
																
															}
														}
														?>
													</select>
												</td>
												<td><input type="text" name="kemasan[]" class="form-control" value="<?php echo $row['kemasan'];?>"></td>
												<td><input type="text" name="kemasan[]" class="form-control" value="<?php echo (($row['kemasan']=='0'?"":$row['kemasan']));?>"></td>
												<td><input type="text" name="nosegel[]" class="form-control"></td>
												<td><input type="text" name="berat[]" class="form-control" value="<?php echo $row['berat'];?>"></td>
												<td><input type="text" name="keterangan[]" class="form-control" value="<?php echo $row['nama_latin'];?>"></td>
												<td><a href="#" class="btn btn-sm btn-danger del_thisrow2" title="Hapus Baris Ini">X</a></td>
											</tr>
											<?php
											}
										 }
										?>
										<tr class="row_clone" style="display:none">
											<td>
												<select disabled class="form-control" name="jenis_sampel[]">
													<option value="">-Pilih-</option>
													<?php
													$sql->get_all('ref_jns_sampel');
													echo $sql->sql;
													if($sql->num_rows>0){
														foreach($sql->result as $r){
															echo '<option value="'.$r['id_ref'].'">'.$r['jenis_sampel'].'</option>';
														}
													}
													?>
												</select>
											</td>
											<td><input type="text" disabled name="kemasan[]" class="form-control"></td>
											<td><?php echo pilihan("satuan[]",$arr_satuan,"","class='form-control' id='satuan' disabled");?></td>
											<td><input type="text" disabled name="nosegel[]" class="form-control"></td>
											<td><input type="text" disabled name="berat[]" class="form-control"></td>
											<td><input type="text" disabled name="keterangan[]" class="form-control"></td>
											<td><a href="#" class="btn btn-sm btn-danger del_thisrow" title="Hapus Baris Ini">X</a></td>
										</tr>
									</tbody>
									<tfoot>
										<tr id="addrow">
											<td colspan="7">
												<p>catatan : <span class="text-alert alert-danger">Angka Desimal Menggunakan . <strong>(titik) cth: 90.2Kg</strong></span></p>
												<a href="#addrow" id="btn_add_data" class="btn btn-sm btn-default">Tambah Data (+)</a>
												</td>
										</tr>
									</tfoot>
								</thead>
							</table>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Redaksi<br>Surat Rekomendasi</label>
						<div class="col-md-8">
							<textarea name="redaksi_rek" rows="5" class="form-control editor">Bahwa sebagian sampel dari [sirip/kulit/daging/tulang hiu/pari] yang terindikasi [nama ikan] merupakan jenis ikan yang [dilindungi/tidak dilindung] , sehingga tidak direkomendasikan / dapat direkomendasikan peredarannya. Rekomendasi ini berlaku untuk satu kali pengiriman, berlaku sampai tanggal ...</textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Mengetahui Kepala/Plh</label>
						<div class="col-md-6">
							<select name="penandatgn" class="form-control">
								<option value="">-Pilih-</option>
								<?php 
								$a=$sql->run("SELECT u.nm_lengkap, p.nip FROM op_user u JOIN op_pegawai p ON(u.ref_idpeg=p.idp) WHERE u.lvl IN(91,90) AND u.status='2' ");
								if($a->rowCount()>0){
									foreach($a->fetchAll() as $b){
										echo '<option value="'.$b['nip'].'">'.$b['nm_lengkap'].' ('.$b['nip'].')</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Tembusan</label>
						<div class="col-md-6">
							<select name="tembusan_bk" class="form-control">
								<option value="">-Pilih-</option>
								<?php 
								$sql->get_all('ref_balai_karantina',array(),array('idbk','nama'));
								if($sql->num_rows>0){
									foreach($sql->result as $b){
										echo '<option value="'.$b['idbk'].'">'.$b['nama'].'</option>';
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
							<button class="btn btn-sm btn-primary" id="btn_simpan" type="submit">Simpan</button>
							<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
							
						</div>
					</div>
				</footer>
			</section>
		</div>
	</div>
</form>