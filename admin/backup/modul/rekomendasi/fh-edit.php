<?php
include ("config.php");
$idpengajuan=base64_decode($_GET['data']);
$idhslperiksa=base64_decode($_GET['hsl']);
if(!ctype_digit($idpengajuan)){
	exit();
}
if(!ctype_digit($idhslperiksa)){
	exit();
}
$SCRIPT_FOOT = "
	<script>
	$(document).ready(function(){
		$('nav li.nav2').addClass('nav-active');
	});
	</script>
	<script src=\"custom-2.js\"></script>
";

$sql->get_row('tb_hsl_periksa',array('id_per'=>$idhslperiksa,'ref_idp'=>$idpengajuan));
if($sql->num_rows<1){
	exit();
}
$dtrow=$sql->result;
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Edit Hasil Pemeriksaan</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><a href="./form-hasil.php?data=<?php echo $_GET['data'];?>"><span>Pemeriksaan Sampel</span></a></li>
				<li><span>Edit Hasil Pemeriksaan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
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
					<form class="form-horizontal" action="" method="POST" name="fupdatehslperiksa" id="fupdatehslperiksa">
                        <input type="hidden" name="a" value="update-hsl-periksa">
						<input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>" >
						<input type="hidden" name="idhsl" value="<?php echo base64_encode($idhslperiksa);?>" >
						<div class="form-group">
							<label class="col-sm-3 control-label">Jenis Ikan</label>
							<div class="col-sm-9">
								<select class="form-control jns_ikan" name="jenis_ikan">
									<option value="">-Pilih-</option>
									<?php
									$sql->get_all('ref_data_ikan');
									if($sql->num_rows>0){
										foreach($sql->result as $r){
											if($dtrow['ref_idikan']==$r['id_ikan']){
												echo '<option selected value="'.$r['id_ikan'].'">'.$r['nama_ikan'].' ('.$r['nama_latin'].')</option>';
											}else{
												echo '<option value="'.$r['id_ikan'].'">'.$r['nama_ikan'].' ('.$r['nama_latin'].')</option>';
											}
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
											if($rak['ak']==$dtrow['asal_komoditas']){
												echo '<option selected value="'.$rak['ak'].'">'.$rak['ak'].'</option>';
											}else{
												echo '<option value="'.$rak['ak'].'">'.$rak['ak'].'</option>';
											}
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
								<input type="text" name="kemasan" class="form-control" value="<?php echo $dtrow['kuantitas'];?>">
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
											if($dtrow['ref_jns_sampel']==$r['id_ref']){
												echo '<option selected value="'.$r['id_ref'].'">'.$r['jenis_sampel'].'</option>';
											}else{
												echo '<option value="'.$r['id_ref'].'">'.$r['jenis_sampel'].'</option>';
											}
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Panjang (Cm)</label>
							<div class="col-md-3">
								<input type="text" name="pjg" class="form-control" value="<?php echo $dtrow['pjg'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Lebar (Cm)</label>
							<div class="col-md-3">
								<input type="text" name="lbr" class="form-control" value="<?php echo $dtrow['lbr'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Berat Sampel(Kg)</label>
							<div class="col-md-3">
								<input type="text" name="berat" class="form-control" value="<?php echo $dtrow['berat'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Berat Total (Kg)</label>
							<div class="col-md-4">
								<input type="text" name="berat_tot" class="form-control" value="<?php echo $dtrow['tot_berat'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Keterangan</label>
							<div class="col-md-5">
								<textarea class="form-control" name="ket"><?php echo $dtrow['ket'];?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-md-5">
								<button type="submit" class="btn btn-sm btn-primary" id="btn_save">Tambah Hasil</button>
								<span id="actloadingmd" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
							</div>
						</div>
					</form>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12">
							<a href="./form-hasil.php?data=<?php echo $_GET['data'];?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</section>
<?php
include(AdminFooter);
?>