<?php
include ("../../engine/render.php");
$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, 
				pnotify.custom.css, jquery.appear.js, select2.css, datatables.css,
				theme.css, default.css, modernizr.js";

$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholder.js, 
				pnotify.custom.js, jquery.dataTables.js,datatables.js,select2.js,
				theme.js, theme.init.js,jquery.validate.js,jquery.validate.msg.id.js";

require_once(c_THEMES."auth.php");

$SCRIPT_FOOT = "
	<script>
		$(document).ready(function(){
			$('nav li.nav-dtref').addClass('nav-expanded nav-active');
			$('nav li.df-ikan').addClass('nav-active');
		});
	</script>
	<script src=\"custom.js\"></script>
";


$id_ciri = base64_decode($_GET['ciri']) ;
if(empty($id_ciri) OR !ctype_digit($id_ciri)){
	_redirect('./ciri-ikan.php');
}
$cr=$sql->query("SELECT rc.*,rdi.nama_ikan,rdi.nama_latin FROM ref_ciri_ikan rc JOIN ref_data_ikan rdi ON(rdi.id_ikan=rc.id_ikan) WHERE id_ciri='$id_ciri' LIMIT 1");
$found=$cr->rowCount();

if($found==0){
	echo '<div class="alert alert-warning">Data Not Found</div>';
}else{
	$ciri=$cr->fetch();
	$idikan=base64_encode($ciri['id_ikan']);
	$nama_ikan=$ciri['nama_ikan'];
	$nama_latin=$ciri['nama_latin'];
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Edit Ciri Ikan</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><a href="./ciri-ikan.php?ikan=<?php echo $idikan;?>">Referensi Ciri Ikan</a></li>
				<li><span>Edit Ciri Ikan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-8">
			<form id="crikan_update" class="form-horizontal" method="post">
				<input type="hidden" name="a" id="a" value="upcrikan" />
				<input type="hidden" name="idcr" id="idcr" value="<?php echo base64_encode($id_ciri);?>" />
				<input type="hidden" name="ikan" id="ikan" value="<?php echo $idikan;?>" />
				<section class="panel">
					<header class="panel-heading">
						<h2 class="panel-title" id="cat-panel-title">Edit Data Ikan : <?php echo $nama_latin."<br>( ".$nama_ikan." )" ?></h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="">Pilih Produk</label>
								<select class="form-control" name="produk" id="produk">
									<option value="">Pilih</option>
									<?php
									$sql->get_all('ref_jns_sampel');
									foreach($sql->result as $rkel){
										if($rkel['id_ref']==$ciri['id_produk']){
											echo '<option selected value="'.$rkel['id_ref'].'">'.$rkel['jenis_sampel'].'</option>';
										}else{
											echo '<option value="'.$rkel['id_ref'].'">'.$rkel['jenis_sampel'].'</option>';
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="">Ciri Ciri</label>
								<textarea class="form-control" rows="5" name="ciri_ciri"><?php echo $ciri['ciri_ciri'];?></textarea>
							</div>
						</div>			
					</div>
					<footer class="panel-footer">
						<a href="./ciri-ikan.php?ikan=<?php echo $idikan;?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali </a>
						<button type="submit" id="btn-aksi" name="btn-aksi" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Simpan Perubahan </button>
					</footer>
				</section>
			</form>
		</div>
	</div>

</section>
<?php
}
@include(AdminFooter);
?>