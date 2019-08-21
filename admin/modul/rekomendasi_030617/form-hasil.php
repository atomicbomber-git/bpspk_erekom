<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav2').addClass('nav-active');
	
	var ik;
	var pr;

	$('.jns_ikan').change(function(){
		ik=$('.jns_ikan').val();
		if(pr!='' &&ik!='' && pr!=undefined){
			get_ket(ik,pr);
		}
	});

	$('.jns_produk').change(function(){
		pr=$('.jns_produk').val();
		if(ik!='' && pr!='' && ik!=undefined){
			get_ket(ik,pr);
		}
	});
});

function get_ket(ik,pr){
	$.ajax({
		url:'ajax.php',
		dataType:'html',
		type:'post',
		data:'a=getciri&ik='+ik+'&pr='+pr,
		beforeSend:function(){
		},
		success: function(html){
			$('.ket').html(html);
		}
	});
}
</script>
<script src=\"custom-2.js\"></script>
";

$idpengajuan=base64_decode($_GET['data']);
if(ctype_digit($idpengajuan)){
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Input Hasil Pemeriksaan</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><a href="./pemeriksaan-sample.php"><span>Pemeriksaan Sampel</span></a></li>
				<li><span>Input Hasil Pemeriksaan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<?php
	
	$sql->get_row('tb_pemeriksaan',array('ref_idp'=>$idpengajuan),array('tgl_periksa','id_periksa'));
	$found=$sql->num_rows;
	if($found>0){
		$r=$sql->result;
		$tgl_periksa=date('m/d/Y',strtotime($r['tgl_periksa']));
		$idperiksa=$r['id_periksa'];
	}else{
		$arr_insert=array(
			'tgl_periksa'=>date('m/d/Y'),
			'ref_idp'=>$idpengajuan,
			'date_insert'=>date('Y-m-d H:i:s'));
		$sql->insert('tb_pemeriksaan',$arr_insert);
		$idperiksa=$sql->insert_id;
	}	

	$dt=$sql->run("SELECT c.nama_lengkap, p.tujuan FROM tb_permohonan p JOIN tb_userpublic c ON(c.iduser=p.ref_iduser) WHERE p.idp='$idpengajuan' LIMIT 1");
	$rdt=$dt->fetch();
	$nama_lengkap=$rdt['nama_lengkap'];
	$tujuan=$rdt['tujuan'];
	?>
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
					</div>
					<h2 class="panel-title">Hasil Pemeriksaan</h2>
				</header>
				<div class="panel-body">
					<form id="update_pemeriksaan" method="post">
						<input type="hidden" name="a" value="update-dt-periksa">
						<input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>" >
						<input type="hidden" name="idpr" value="<?php echo base64_encode($idperiksa);?>" >
						<div class="form-group">
							<label class="control-label col-md-3">Nama Pemilik</label>
							<div class="col-md-4">
								<input type="text" readonly name="nm_pemilik" class="form-control" value="<?php echo $nama_lengkap;?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Tujuan Pengiriman</label>
							<div class="col-md-4">
								<input type="text" readonly name="tujuan" class="form-control" value="<?php echo $tujuan;?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Tanggal Pemeriksaan</label>
							<div class="col-md-3">
								<input type="text" name="tgl_pemeriksaan" data-plugin-datepicker data-date-orientation="top" class="form-control" value="<?php echo $tgl_periksa;?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3"></label>
							<div class="col-md-9">
								<button type="submit" class="btn btn-sm btn-primary btn_simpan">Simpan Perubahan</button>
								<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
							</div>
						</div>
					</form>
					<hr/>	
	                <div class="form-group">
	                    <div class="col-md-12">
	                        <div class="toggle" data-plugin-toggle="" id="formaddhasil">
	                            <section class="toggle">
	                                <label>Tambah Hasil Pemeriksaan</label>
	                                <div class="toggle-content panel-body">
	                                	<form class="form-horizontal" action="" method="POST" name="formhasilperiksa" id="formhasilperiksa">
	                                        <input type="hidden" name="a" value="add-hsl-periksa">
											<input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>" >
											<input type="hidden" name="idpr" value="<?php echo base64_encode($idperiksa);?>" >
											<div class="form-group">
												<label class="col-sm-3 control-label">Jenis Ikan</label>
												<div class="col-sm-9">
													<select class="form-control jns_ikan" name="jenis_ikan">
														<option value="">-Pilih-</option>
														<?php
														$sql->get_all('ref_data_ikan');
														if($sql->num_rows>0){
															foreach($sql->result as $r){
															echo '<option value="'.$r['id_ikan'].'">'.$r['nama_ikan'].' ('.$r['nama_latin'].')</option>';
															}
														}
														?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Asal Komoditas</label>
												<div class="col-md-4 ak_div">
													<select name="asal_komoditas_opt" class="form-control asal_komoditas">
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
													<input type="text" style="display:none" name="asal_komoditas" class="form-control custom_ak">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Kemasan (Colly)</label>
												<div class="col-md-2">
													<input type="text" name="kemasan" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Jenis Produk</label>
												<div class="col-md-6">
													<select class="form-control jns_produk" name="jenis_sampel">
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
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Panjang Sampel (Cm)</label>
												<div class="col-md-3">
													<input type="text" name="pjg" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Lebar Sampel (Cm)</label>
												<div class="col-md-3">
													<input type="text" name="lbr" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Berat Sampel(Kg)</label>
												<div class="col-md-3">
													<input type="text" name="berat" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Berat Total (Kg)</label>
												<div class="col-md-4">
													<input type="text" name="berat_tot" class="form-control">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Keterangan</label>
												<div class="col-md-5">
													<textarea class="form-control ket" rows="5" name="ket"></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"></label>
												<div class="col-md-5">
													<button type="submit" class="btn btn-sm btn-primary" id="btn_save">Tambah Hasil</button>
													<span id="actloadingmd" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-3 control-label"></label>
												<div class="col-md-6">
												<p>catatan : <span class="text-alert alert-danger">Angka Desimal Menggunakan . <strong>(titik) cth: 90.2Kg</strong></span></p>
												</div>
											</div>
										</form>
	                                </div>
	                            </section>
	                        </div>
	                    </div>
	                </div>
					<hr/>
					<center><h5>Tabel Hasil Pemeriksaan</h5></center>
					<div class="table-responsive">
					<table class="table table-bordered" id="tabelhasilperiksa">
						<thead><tr>
							<th rowspan='2' style="vertical-align: middle;" class="text-center">No</th>
							<th colspan='3' class="text-center">Ikan</th>
							<th colspan='4' class="text-center">Sampel</th>
							<th rowspan='2' style="vertical-align: middle;" class="text-center">Berat Total(Kg)</th>
							<th rowspan='2' style="vertical-align: middle;" class="text-center">Keterangan</th>
							<th rowspan='2' style="vertical-align: middle;" class="text-center">Aksi</th>
						</tr>
						<tr>
							<td>Jenis</td>
							<td>Asal Komoditas</td>
							<td>Kemasan</td>
							<td>Jenis Produk</td>
							<td>Panjang (Cm)</td>
							<td>Lebar (Cm)</td>
							<td>Berat (Kg)</td>
						</tr>
						</thead>
						<tbody>
						<?php
						$tb=$sql->query("SELECT th.*,rdi.nama_ikan,rjs.jenis_sampel as jns_produk FROM tb_hsl_periksa th 
									LEFT JOIN ref_data_ikan rdi ON (rdi.id_ikan=th.ref_idikan) 
									LEFT JOIN ref_jns_sampel rjs ON (rjs.id_ref=th.ref_jns_sampel)
									WHERE th.ref_idp='$idpengajuan' AND th.ref_idperiksa='$idperiksa'
									");
						if($tb->rowCount()>0){
							$no=1;
							foreach ($tb->fetchAll() as $dtrow) {
								$btnaksi='
								<a href="fh-edit.php?data='.base64_encode($idpengajuan).'&hsl='.base64_encode($dtrow['id_per']).'" class="btn btn-xs btn-success">Edit</a>  <a href="#" data-delid="'.base64_encode($dtrow['id_per']).'" class="btn btn-xs btn-danger btn_hps_hasilper">Hapus</a>';
								echo '
								<tr>
									<td>'.$no.'</td>
									<td>'.$dtrow['nama_ikan'].'</td>
									<td>'.$dtrow['asal_komoditas'].'</td>
									<td>'.$dtrow['kuantitas'].'</td>
									<td>'.$dtrow['jns_produk'].'</td>
									<td>'.$dtrow['pjg'].'</td>
									<td>'.$dtrow['lbr'].'</td>
									<td>'.$dtrow['berat'].'</td>
									<td>'.$dtrow['tot_berat'].'</td>
									<td>'.$dtrow['ket'].'</td>
									<td>'.$btnaksi.'</td>
								</tr>';
								$no++;
							}
						}else{
							echo '<tr><td colspan="11" class="text-center">Data Belum Diisi.</td></tr>';
						}
						?>
						</tbody>
					</table>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12">
							<a href="./pemeriksaan-sample.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<div id="DelHasilModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title">Hapus Data?</h2>
			</header>
			<div class="panel-body">
				<div class="modal-wrapper">
					<div class="modal-icon">
						<i class="fa fa-question-circle"></i>
					</div>
					<div class="modal-text">
						<p>Apakah anda yakin akan menghapus Data ini?</p>
					</div>
				</div>
			</div>
			<footer class="panel-footer">
				<div class="row">
					<div class="col-md-12 text-right">
						<button id="btndelhasilpem" class="btn btn-primary modal-confirm">Confirm</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
		</section>
	</div>
</section>
<?php
}
include(AdminFooter);
?>
