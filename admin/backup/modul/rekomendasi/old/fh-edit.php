<?php
$dt=$sql->run("SELECT c.nama_lengkap, p.tujuan FROM tb_permohonan p JOIN tb_userpublic c ON(c.iduser=p.ref_iduser) WHERE p.idp='$idpengajuan' LIMIT 1");
$rdt=$dt->fetch();
$nama_lengkap=$rdt['nama_lengkap'];
$tujuan=$rdt['tujuan'];
?>
<form id="update_pemeriksaan" method="post">
	<input type="hidden" name="a" value="update-hsl-periksa">
	<input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>" >
	<input type="hidden" name="idpr" value="<?php echo base64_encode($idperiksa);?>" >
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
					</div>
					<h2 class="panel-title">Tabel Hasil Pemeriksaan</h2>
				</header>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label col-md-3">Nama Pemilik</label>
						<div class="col-md-4">
							<input type="text" name="nm_pemilik" class="form-control" value="<?php echo $nama_lengkap;?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Tujuan Pengiriman</label>
						<div class="col-md-4">
							<input type="text" name="tujuan" class="form-control" value="<?php echo $tujuan;?>">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Tanggal Pemeriksaan</label>
						<div class="col-md-3">
							<input type="text" name="tgl_pemeriksaan" data-plugin-datepicker data-date-orientation="top" class="form-control" value="<?php echo $tgl_periksa;?>">
						</div>
					</div>
					<hr/>
					<table class="table table-bordered" id="tblsampel">
						<thead>
							<tr>
								<th class="text-center">Jenis Ikan</th>
								<th class="text-center">Sampel</th>
								<th class="text-center" width="5%">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql->get_all('tb_hsl_periksa',array('ref_idp'=>$idpengajuan,'ref_idperiksa'=>$idperiksa),'*');
							foreach($sql->result as $row){
							?>
							<tr>
								<td width="30%">
									<p class="control-label">Jenis Ikan</p>
									<select class="form-control jns_ikan" name="jenis_ikan[]">
										<option value="">-Pilih-</option>
										<?php
										$sql->get_all('ref_data_ikan');
										echo $sql->sql;
										if($sql->num_rows>0){
											foreach($sql->result as $r){
												if($row['ref_idikan']==$r['id_ikan']){
													echo '<option selected value="'.$r['id_ikan'].'">'.$r['nama_ikan'].' ('.$r['nama_latin'].')</option>';
												}else{
													echo '<option value="'.$r['id_ikan'].'">'.$r['nama_ikan'].' ('.$r['nama_latin'].')</option>';
												}
											}
										}
										?>
									</select>
									<div class="row">
										<div class="col-md-6 ak_div">
											<p class="control-label">Asal Komoditas</p>
											<select name="asal_komoditas_opt[]" class="form-control asal_komoditas">
												<option value="">-Pilih-</option>
												<?php
												$ak=$sql->run("SELECT DISTINCT(asal_komoditas) ak FROM tb_hsl_periksa where ref_idp='$idpengajuan'");
												if($ak->rowCount()>0){
													foreach($ak->fetchAll() as $rak){
														if($rak['ak']==$row['asal_komoditas']){
															echo '<option selected value="'.$rak['ak'].'">'.$rak['ak'].'</option>';
														}else{
															echo '<option value="'.$rak['ak'].'">'.$rak['ak'].'</option>';
														}
													}
												}
												?>
												<option value="lainnya">Lainnya</option>
											</select>
											<input type="text" style="display:none" name="asal_komoditas[]" class="form-control custom_ak" value="<?php echo $row['asal_komoditas'];?>">
										</div>
										<div class="col-md-6">
											<p class="control-label">Kemasan (Colly)</p>
											<input type="text" name="kemasan[]" class="form-control" value="<?php echo $row['kuantitas'];?>">
										</div>
									</div>
								</td>
								<td>
									<div class="row">
										<div class="col-md-6">
											<p class="control-label">Jenis</p>
											<select class="form-control" name="jenis_sampel[]">
												<option value="">-Pilih-</option>
												<?php
												$sql->get_all('ref_jns_sampel');
												echo $sql->sql;
												if($sql->num_rows>0){
													foreach($sql->result as $r){
														if($row['ref_jns_sampel']==$r['id_ref']){
															echo '<option selected value="'.$r['id_ref'].'">'.$r['jenis_sampel'].'</option>';
														}else{
															echo '<option value="'.$r['id_ref'].'">'.$r['jenis_sampel'].'</option>';
														}
													}
												}
												?>
											</select>
										</div>
										<div class="col-md-3">
											<p class="control-label"><small>Panjang (Cm)</small></p>
											<input type="text" name="pjg[]" class="form-control" value="<?php echo $row['pjg'];?>">
										</div>
										<div class="col-md-3">
											<p class="control-label"><small>Lebar (Cm)</small></p>
											<input type="text" name="lbr[]" class="form-control" value="<?php echo $row['lbr'];?>">
										</div>
										
									</div>
									<div class="row">
										<div class="col-md-6">
											<p class="control-label">Keterangan</p>
											<textarea class="form-control" name="ket[]"><?php echo $row['ket'];?></textarea>
										</div>
										<div class="col-md-3">
											<p class="control-label"><small>Berat Sampel(Kg)</small></p>
											<input type="text" name="berat[]" class="form-control" value="<?php echo $row['berat'];?>">
										</div>
										<div class="col-md-3">
											<p class="control-label"><small>Berat Total(Kg)</small></p>
											<input type="text" name="berat_tot[]" class="form-control" value="<?php echo $row['tot_berat'];?>">
										</div>
									</div>
								</td>
								<td><a href="#" class="btn btn-sm btn-danger del_thisrow2" title="Hapus Baris Ini">X</a></td>
							</tr>
							<?php
							}
							?>
							<tr class="row_clone" style="display:none">
								<td width="30%">
									<p class="control-label">Jenis Ikan</p>
									<select disabled class="form-control jns_ikan2" name="jenis_ikan[]">
										<option value="">-Pilih-</option>
										<?php
										$sql->get_all('ref_data_ikan');
										echo $sql->sql;
										if($sql->num_rows>0){
											foreach($sql->result as $r){
												echo '<option value="'.$r['id_ikan'].'">'.$r['nama_ikan'].' ('.$r['nama_latin'].')</option>';
											}
										}
										?>
									</select>
									<div class="row">
										<div class="col-md-6 ak_div">
											<p class="control-label">Asal Komoditas</p>
											<select disabled name="asal_komoditas_opt[]" class="form-control asal_komoditas">
												<option value="">-Pilih-</option>
												<?php
												$ak=$sql->run("SELECT DISTINCT(asal_komoditas) ak FROM tb_hsl_periksa where ref_idp='$idpengajuan'");
												if($ak->rowCount()>0){
													foreach($ak->fetchAll() as $rak){
														echo '<option value="'.$rak['ak'].'">'.$rak['ak'].'</option>';
													}
												}
												?>
												<option value="lainnya">Lainnya</option>
											</select>
											<input disabled type="text" style="display:none" name="asal_komoditas[]" class="form-control custom_ak" value="">
										</div>
										<div class="col-md-6">
											<p class="control-label">Kemasan (Colly)</p>
											<input disabled type="text" name="kemasan[]" class="form-control">
										</div>
									</div>
								</td>
								<td>
									<div class="row">
										<div class="col-md-6">
											<p class="control-label">Jenis</p>
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
										</div>
										<div class="col-md-3">
											<p class="control-label"><small>Panjang (Cm)</small></p>
											<input disabled type="text" name="pjg[]" class="form-control">
										</div>
										<div class="col-md-3">
											<p class="control-label"><small>Lebar (Cm)</small></p>
											<input disabled type="text" name="lbr[]" class="form-control">
										</div>
										
									</div>
									<div class="row">
										<div class="col-md-6">
											<p class="control-label">Keterangan</p>
											<textarea disabled class="form-control" name="ket[]"></textarea>
										</div>
										<div class="col-md-3">
											<p class="control-label"><small>Berat Sampel(Kg)</small></p>
											<input disabled type="text" name="berat[]" class="form-control">
										</div>
										<div class="col-md-3">
											<p class="control-label"><small>Berat Total(Kg)</small></p>
											<input disabled type="text" name="berat_tot[]" class="form-control">
										</div>
									</div>
								</td>
								<td><a href="#" class="btn btn-sm btn-danger del_thisrow" title="Hapus Baris Ini">X</a></td>
							</tr>
						</tbody>
						<tfoot>
							<tr id="addrow">
								<td colspan="6">
									<p>catatan : <span class="text-alert alert-danger">Angka Desimal Menggunakan . <strong>(titik) cth: 90.2Kg</strong></span></p>
									<a href="#addrow" id="btn_add_hasil" class="btn btn-sm btn-default">Tambah (+)</a>
									</td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12">
							<a href="./pemeriksaan-sample.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
							<button type="submit" class="btn btn-primary btn_simpan">Simpan Perubahan</button>
							<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</form>