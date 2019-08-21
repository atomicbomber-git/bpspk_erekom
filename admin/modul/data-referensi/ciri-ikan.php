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
			$('#ref-crikan').DataTable({
				'ordering':false,
			});
		});

	</script>
	<script src=\"custom.js\"></script>
";

$idikan=base64_decode($_GET['ikan']);
if(!ctype_digit($idikan)){
	@include(AdminFooter);
	exit();
}

$sql->get_row('ref_data_ikan',array('id_ikan'=>$idikan));
if($sql->num_rows>0){
	$ik=$sql->result;
	$nama_ikan=$ik['nama_ikan'];
	$nama_latin=$ik['nama_latin'];
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Referensi Ciri Ikan</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><a href="<?php echo c_MODULE;?>data-referensi/data-ikan.php">Data Ikan</a></li>
				<li><span>Referensi Ciri Ikan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-8">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<div class="panel-actions">
						<a href="#" class="fa fa-caret-down"></a>
					</div>
					<h2 class="panel-title">Referensi Ciri Ikan : <?php echo $nama_latin."<br>( ".$nama_ikan." )" ?></h2>
				</header>
				
				<div class="panel-body">
					<table class="table table-hover table-striped mb-none" id="ref-crikan">
						<thead>
							<tr>
								<th width="5px;">#</th>
								<th>Jenis Produk</th>
								<th>Ciri-Ciri</th>
							</tr>
						</thead>
						<tbody id="isi_table">
						<?php
						$q=$sql->run("SELECT cr.*,rjs.jenis_sampel FROM ref_ciri_ikan cr JOIN ref_jns_sampel rjs ON(rjs.id_ref=cr.id_produk) WHERE cr.id_ikan='$idikan'");
						$no=1;
						foreach ($q->fetchAll() as $row) {
						    echo "
						    <tr>
						        <td>".$no."</td>
						        <td>".$row['jenis_sampel']."
						        	<p class=\"actions-hover actions-fade\"> 
						        	<a href='./edit-ciri-ikan.php?ciri=".base64_encode($row['id_ciri'])."'>Edit</a> 
						        	<a href='#' data-id='".base64_encode($row['id_ciri'])."' class='row-delete'>Delete</a>
						        	</p>
						        </td>
						        <td>".nl2br($row['ciri_ciri'])."</td>
						    </tr>
						    ";
						    $no++;
						}
						?>
						</tbody>
					</table>

					<div id="DelModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
						<section class="panel">
							<header class="panel-heading">
								<h2 class="panel-title">Delete Data?</h2>
							</header>
							<div class="panel-body">
								<div class="modal-wrapper">
									<div class="modal-icon">
										<i class="fa fa-question-circle"></i>
									</div>
									<div class="modal-text">
										<p>Apakah anda yakin akan menghapus data ini?</p>
									</div>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 text-right">
										<button class="btn btn-primary modal-confirm">Confirm</button>
										<button class="btn btn-default modal-dismiss">Cancel</button>
									</div>
								</div>
							</footer>
						</section>
					</div>
				</div>
			</section>
		</div>

		<div class="col-md-4">
			<form id="crikan_add" class="form-horizontal" method="post">
				<input type="hidden" name="a" id="a" value="addcrikan" />
				<input type="hidden" name="ikan" id="ikan" value="<?php echo base64_encode($idikan);?>" />
				<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="fa fa-caret-down"></a>
						</div>
						<h2 class="panel-title" id="cat-panel-title">Tambah Data Ciri Baru</h2>
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
										echo '<option value="'.$rkel['id_ref'].'">'.$rkel['jenis_sampel'].'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="">Ciri Ciri</label>
								<textarea class="form-control" rows="5" name="ciri_ciri"></textarea>
							</div>
						</div>		
					</div>
					<footer class="panel-footer">
						<a href="./data-ikan.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali </a>
						<button type="submit" id="btn-aksi" name="btn-aksi" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Tambah Data </button>
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