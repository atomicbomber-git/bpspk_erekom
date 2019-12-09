<?php
require_once("config.php");

$iduser=base64_decode($_GET['p']);
if(!ctype_digit($iduser)){
	exit();
}

$SCRIPT_FOOT="
<script>
$(document).ready(function(){
	$('nav li.nav2').addClass('nav-active');
	$('.img-prev').magnificPopup({type:'image'});
	$('#form_biodata').validate({
		ignore: [],
		errorClass: 'error',
		rules:{
			nm_lengkap:{required:true},
			tmp_lahir:{required:true},
			tgl_lahir:{required:true},
			no_ktp:{required:true,digits:true},
			no_telp:{required:true},
			//ttd:{required:true},
			alamat_rmh:{required:true}
		},
		messages:{
			nm_lengkap:{required:'Nama Lengkap Harap Diisi.'},
			tmp_lahir:{required:'Tempat Lahir Harap Diisi.'},
			tgl_lahir:{required:'Tanggal Lahir Harap Diisi.'},
			no_ktp:{required:'No Identitas Harap Diisi.'},
			no_telp:{required:'No Telepon Harap Diisi'},
			//ttd:{required:'Silakan Uplaod Tandatangan Anda'},
			alamat_rmh:{required:'Alamat Harap Diisi'}
		},
		errorPlacement: function (error, element) {
	        error.insertAfter(element);
	    },
	    highlight: function (element, validClass) {
	        $(element).parent().addClass('has-error');
	    },
	    unhighlight: function (element, validClass) {
	        $(element).parent().removeClass('has-error');
	    },
		submitHandler: function(form) {
			var stack_bar_bottom = {'dir1': 'up', 'dir2': 'right', 'spacing1': 0, 'spacing2': 0};
			var formData = new FormData(document.getElementById('form_biodata'));
			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:formData,
				mimeType:'multipart/form-data',
				contentType: false,
				processData:false,
				beforeSend:function(){
					$('#btn_simpan').prop('disabled', true);
					$('#actloading').show();	
				},
				success:function(json){	
					if(json.stat){
						var notice = new PNotify({
							title: 'Notification',
							text: json.msg,
							type: 'success',
							addclass: 'stack-bar-bottom',
							stack: stack_bar_bottom,
							width: '60%',
							delay:1000,
							after_close:function(){
								//location.reload();
							}
						});
					}else{
						var notice = new PNotify({
							title: 'Notification',
							text: json.msg,
							type: 'warning',
							addclass: 'stack-bar-bottom',
							stack: stack_bar_bottom,
							width: '60%',
							delay:2500
						});
					}

					$('#btn_simpan').prop('disabled', false);
					$('#actloading').hide();
				}
			});
		return false;
		}
	});
});
</script>";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Biodata Pemohon</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_DOMAIN;?>"><i class="fa fa-home"></i></a></li>
				<li><a href="./list.php"><span>Data Pemohon</span></a></li>
				<li><a href="./biodata.php?data=<?php echo base64_encode($iduser);?>"><span>Biodata</span></a></li>
				<li><span>Update Biodata</span></li>
			</ol>
			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<?php
	$found=$sql->get_count('tb_biodata',array('ref_iduser'=>$iduser));
	if($found>0){
		$b=$sql->run("SELECT u.nama_lengkap,u.email, b.* FROM tb_userpublic u JOIN tb_biodata b ON (b.ref_iduser=u.iduser) WHERE u.iduser='".$iduser."' LIMIT 1");

		$sql->get_row('tb_biodata',array('ref_iduser'=>$iduser),'*');
		if($b->rowCount()>0){
			$row=$b->fetch();
			$tmp_lahir=$row['tmp_lahir'];
			$tgl_lahir=date("m/d/Y", strtotime($row['tgl_lahir']));
			$no_ktp=$row['no_ktp'];
			$no_telp=$row['no_telp'];
			$nib = $row['nib'];
			$sipji = $row['sipji'];
			$npwp=$row['npwp'];
			$nm_perusahaan=$row['nm_perusahaan'];
			$siup=$row['siup'];
			$izin_lainnya=$row['izin_lain'];
			$nama_pemohon=$row['nama_lengkap'];
			$gudang_1 = $row['gudang_1'];
			$gudang_2 = $row['gudang_2'];
			$gudang_3 = $row['gudang_3'];
		}else{
			$nama_pemohon="";$tmp_lahir="";$tgl_lahir="";$no_ktp="";$no_telp="";$gudang_1="";$gudang_2="";$gudang_3="";$npwp="";$nm_perusahaan="";$siup="";$nib="";$sipji="";$izin_lainnya="";
		}
		?>
		<form id="form_biodata" method="post" enctype="multipart/form-data">
			<input type="hidden" name="a" value="updatebio">
			<input type="hidden" name="p" value="<?php echo base64_encode($iduser);?>">
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
								<label class="control-label col-md-3">Nama Lengkap <small>*</small></label>
								<div class="col-md-6">
									<input type="text" readonly name="nm_lengkap" class="form-control" value="<?php echo $nama_pemohon;?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Tempat,Tanggal Lahir <small>*</small></label>
								<div class="col-md-4">
									<input type="text" name="tmp_lahir" class="form-control" placeholder="Tempat Lahir" value="<?php echo $tmp_lahir;?>">
								</div>
								<div class="col-md-3">
									<input type="text" name="tgl_lahir" data-plugin-datepicker class="form-control" value="<?php echo $tgl_lahir;?>">
									<p><small>cth : 12/01/1992 (bulan/hari/tahun)</small></p>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-md-3">No Identitas (KTP) <small>*</small></label>
								<div class="col-md-4">
									<input type="text" name="no_ktp" class="form-control" value="<?php echo $no_ktp;?>">
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
									$n=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$iduser."' AND jenis_berkas='4' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
									if($n->rowCount()>0){
										$img_ktp=$n->fetch();
										echo '<img width="100%" href="'.BERKAS.$img_ktp['nama_file'].'" src="'.BERKAS.$img_ktp['nama_file'].'" class="img-prev">';
									}else{
										echo '<p class="text-alert alert-warning">KTP Belum diupload</p>';
									}
									
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">No Telp <small>*</small></label>
								<div class="col-md-3">
									<input type="text" name="no_telp" class="form-control" value="<?php echo $no_telp;?>">
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
									<input type="text" name="npwp" class="form-control" value="<?php echo $npwp;?>">
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
									$n=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$iduser."' AND jenis_berkas='2' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
									if($n->rowCount()>0){
										$img_npwp=$n->fetch();
										echo '<img width="100%" href="'.BERKAS.$img_npwp['nama_file'].'" src="'.BERKAS.$img_npwp['nama_file'].'" class="img-prev">';
									}else{
										echo '<p class="text-alert alert-warning">NPWP Belum diupload</p>';
									}
									
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Nama Perusahaan</label>
								<div class="col-md-5">
									<input type="text" name="nm_perusahaan" class="form-control" value="<?php echo $nm_perusahaan;?>">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3">Nomor SIUP</label>
								<div class="col-md-4">
									<input type="text" name="siup" class="form-control" value="<?php echo $siup;?>">
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
									$s=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$iduser."' AND jenis_berkas='3' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
									if($s->rowCount()>0){
										$img_siup=$s->fetch();
										echo '<img width="100%" href="'.BERKAS.$img_siup['nama_file'].'" src="'.BERKAS.$img_siup['nama_file'].'" class="img-prev">';
									}else{
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
									<input type="file" accept="image/*" name="file_nib" class="form-control" value="">
									<p class="text-alert alert-info">Upload Hasil Scan NIB Anda. (Hanya Gambar:png,jpg,jpeg)</p>
								</div>
								<div class="col-md-4">
									<p>NIB</p>
									<?php
										$s=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$iduser."' AND jenis_berkas='5' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
										if($s->rowCount()>0){
											$img_nib=$s->fetch();
											echo '<img width="100%" href="'.BERKAS.$img_nib['nama_file'].'" src="'.BERKAS.$img_nib['nama_file'].'" class="img-prev">';
										}else{
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
									$s=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='" .$iduser. "' AND jenis_berkas='6' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
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
									<textarea class="form-control" row="4" name="izin_lainnya"><?php echo $izin_lainnya;?></textarea>
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
									$t=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$iduser."' AND jenis_berkas='1' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
									if($t->rowCount()>0){
										$img_npwp=$t->fetch();
										echo '<img width="100%" href="'.BERKAS.$img_npwp['nama_file'].'" src="'.BERKAS.$img_npwp['nama_file'].'" class="img-prev">';
									}else{
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
								<button class="btn btn-primary btn-sm btn_simpan" type="submit" >Simpan Biodata</button>
								<a href="./biodata.php?data=<?php echo base64_encode($iduser);?>" class="btn btn-sm btn-danger">Kembali</a>
								<p id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</p>
							</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</form>
	<?php
	}else{

	}
	?>
</section>
<?php
include(AdminFooter);
?>
