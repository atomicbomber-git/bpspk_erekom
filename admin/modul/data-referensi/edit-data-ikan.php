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

		$('#peredaran').change(function(){
			var idval = $(this).val();
			if(idval==2 || idval==3){
				$('.ket_dasarhukum').show();
			}else{
				$('.ket_dasarhukum').hide();
				$('#ket_dasarhukum').val('');
			}
		});
	</script>
	<script src=\"custom.js\"></script>
";


$id_ikan = base64_decode($_GET['landing']) ;
if(empty($id_ikan) OR !ctype_digit($id_ikan)){
	_redirect('./data-ikan.php');
}
$sql->get_row('ref_data_ikan',array('id_ikan'=>$id_ikan),array('id_ikan','nama_ikan','nama_latin','ref_idkel','dilindungi','peredaran','ket_dasarhukum'));
$ikan=$sql->result;
$found=$sql->num_rows;

?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Edit Data Ikan</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Edit Data Ikan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-8">
			<?php
			if($found==0){
				echo '<div class="alert alert-warning">Data Not Found</div>';
			}else{
			?>
			<form id="dtikan_update" class="form-horizontal" method="post">
				<input type="hidden" name="a" id="a" value="updtikan" />
				<input type="hidden" name="idik" id="idik" value="<?php echo base64_encode($id_ikan);?>" />
				<section class="panel">
					<header class="panel-heading">
						<h2 class="panel-title" id="cat-panel-title">Edit Data Ikan</h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="nm_ikan">Nama Ikan</label>
								<input name="nm_ikan" id="nm_ikan" placeholder="Nama Ikan" type="text" class="form-control" value="<?php echo $ikan['nama_ikan'];?>">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="nm_latin">Nama Latin</label>
								<input name="nm_latin" id="nm_latin" placeholder="Nama Latin" type="text" class="form-control" value="<?php echo $ikan['nama_latin'];?>">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="dilindungi">Dilindungi</label>
								<select class="form-control" name="dilindungi">
									<option <?php echo (($ikan['dilindungi']=='0')?"selected":""); ?>value=""> Pilih </option>
									<option <?php echo (($ikan['dilindungi']=='1')?"selected":""); ?> value="1"> Dilindungi</option>
									<option <?php echo (($ikan['dilindungi']=='2')?"selected":""); ?> value="2"> Tidak Dilindungi</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="peredaran">Izin Peredaran</label>
								<select class="form-control" name="peredaran" id="peredaran">
									<option <?php echo (($ikan['peredaran']=='0')?"selected":""); ?> value=""> Pilih </option>
									<option <?php echo (($ikan['peredaran']=='1')?"selected":""); ?> value="1"> Dalam dan Luar Negeri</option>
									<option <?php echo (($ikan['peredaran']=='2')?"selected":""); ?> value="2"> Hanya Dalam Negeri</option>
									<option <?php echo (($ikan['peredaran']=='3')?"selected":""); ?> value="3"> Dilarang</option>
								</select>
							</div>
						</div>
						<div class="form-group ket_dasarhukum" <?php echo (($ikan['peredaran']=='2' OR $ikan['peredaran']=='3')?'':'style="display:none"');?> >
							<div class="col-md-12">							
								<label class="control-label" for="peredaran">Ket Dasar Hukum</label>
								<textarea name="ket_dasarhukum" id="ket_dasarhukum" class="form-control"><?php echo $ikan['ket_dasarhukum'];?></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6">							
								<label class="control-label" for="kel">Kelompok</label>
								<select name="kel" id="kel" class="form-control" >
									<?php
									$sql->get_all('ref_kel_ikan');
									foreach($sql->result as $rkel){
										if($rkel['id_ref']==$ikan['ref_idkel']){
											echo '<option selected value="'.$rkel['id_ref'].'">'.$rkel['nama_kel'].'</option>';
																					}else{
											echo '<option value="'.$rkel['id_ref'].'">'.$rkel['nama_kel'].'</option>';
										}
									}
									?>
								</select>
							</div>
						</div>			
					</div>
					<footer class="panel-footer">
						<a href="./data-ikan.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali </a>
						<button type="submit" id="btn-aksi" name="btn-aksi" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Simpan Perubahan </button>
					</footer>
				</section>
			</form>
			<?php 
			}
			?>
		</div>
	</div>

</section>
</div>
<?php
@include(AdminFooter);
?>