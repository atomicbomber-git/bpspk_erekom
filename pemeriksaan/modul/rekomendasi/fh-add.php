<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('ul li.nav-hasil').addClass('active');
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
<script src=\"hasil-pemeriksaan.js\"></script>
";

$idpengajuan=U_IDP;
$idperiksa=base64_decode($_GET['p']);
if($idperiksa==""){
	header('location:./input-hasil.php');
}
$sql->get_row('tb_pemeriksaan',array('id_periksa'=>$idperiksa));
if($sql->num_rows>0){
if(ctype_digit($idpengajuan)){
?>
<body class="hold-transition skin-blue sidebar-mini">

<div class="content-wrapper">
	<section class="content-header">
		<h1>
		Hasil Pemeriksaan
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo c_URL;?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="./input-hasil.php">Hasil Pemeriksaan</a></li>
			<li class="active">Input Hasil Pemeriksaan</li>
		</ol>
	</section>
	<section class="content">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">Input Hasil Pemeriksaan</h3>
			</div>
			<div class="box-body">
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
						<label class="control-label col-md-4">-- Sampel Terkecil --</label>
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
						<label class="control-label col-md-4">-- Sampel Terbesar --</label>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Panjang Sampel (Cm)</label>
						<div class="col-md-3">
							<input type="text" name="pjg2" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Lebar Sampel (Cm)</label>
						<div class="col-md-3">
							<input type="text" name="lbr2" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Berat Sampel(Kg)</label>
						<div class="col-md-3">
							<input type="text" name="berat2" class="form-control">
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
							<button type="submit" class="btn btn-sm btn-primary btn-flat" id="btn_save">Tambah Hasil</button>
							<a href="./input-hasil.php" class="btn btn-sm btn-danger btn-flat">Kembali</a>
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
		</div>
	</section>
</div>

</div>
</body>
<?php
}
}
include(AdminFooter);
?>
