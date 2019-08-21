<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav2').addClass('nav-active');
});
</script>
<script src=\"custom-2.js\"></script>
";
$idpengajuan=base64_decode($_GET['data']);
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Upload Dokumentasi Pemeriksaan</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><a href="./pemeriksaan-sample.php"><span>Pemeriksaan Sampel</span></a></li>
				<li><span>Foto Dokumentasi</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-7">
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
					</div>
					<h2 class="panel-title">Daftar Foto Dokumentasi</h2>
				</header>
				<div class="panel-body">
					<table class="table table-hover table-bordered" id="tbl_gambar">
						<thead>
							<tr>
								<td width="5%">No</td>
								<td>Keterangan</td>
							</tr>
						</thead>
						<tbody>
							<?php
								$sql->get_all('tb_dokumentasi',array('ref_idp'=>$idpengajuan),array('nm_file','ket_foto','id_dok'));
								if($sql->num_rows>0){
									$no=0;
									foreach($sql->result as $ft){
										$edit="<a href='#' data-id=\"".base64_encode($ft['id_dok'])."\" data-edit='".$ft['ket_foto']."' class='edit-row'>Edit</a>";
										$del="<a href='#' data-del=\"".base64_encode($ft['id_dok'])."\" class=\"delete-row modal-with-move-anim\">Hapus</a>";
										$no++;
										echo '<tr>';
										echo '<td>'.$no.'</td>';
										echo '<td><a class="img-prev" href="'.ADM_FOTO.$ft['nm_file'].'"><img src="'.ADM_FOTO.'thumb_'.$ft['nm_file'].'"></a><br>'.$ft['ket_foto'].'<div class="actions-hover actions-fade">'.$edit.$del.'</div></td>';
										echo '</tr>';
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</section>
		</div>
		<div class="col-md-5">
			<form class="form-horzontal" id="upload_foto" name="upload_foto" enctype="multipart/form-data">
				<input type="hidden" name="a" value="adft">
				<input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>" >
				<section class="panel">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
						</div>
						<h2 class="panel-title">Upload Foto</h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<label class="control-label">Foto</label>
							<input type="file" class="form-control" name="file_foto" id="file_foto">
							<p><span class="text-alert alert-info">* Ukuran Maksimal File 2 Mb</span></p>
						</div>
						<div class="form-group">
							<label class="control-label">Keterangan Foto</label>
							<input type="text" class="form-control" name="ket_foto" id="ket_foto">
						</div>
					</div>
					<footer class="panel-footer">
						<button type="submit" name="btn_upload" id="btn_upload" class="btn btn-primary">Upload</button>
						<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
					</footer>
				</section>
			</form>
		</div>
	</div>
	<div class="row">
		
	</div>
</section>
<div id="DelModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
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
					<p>Apakah anda yakin akan menghapus Foto Ini ini?</p>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button id="del_pid" data-id="" class="btn btn-primary modal-confirm">Confirm</button>
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</section>
</div>
<div id="EditModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h2 class="panel-title">Ubah Keterangan Gambar/Foto</h2>
		</header>
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="from-group">
					<label class="control-label">Keterangan Gambar/Foto</label>
					<input type="text" class="form-control" name="ket_foto" id="ket_foto_edit">
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button id="epid" data-id="" class="btn btn-primary modal-confirm">Confirm</button>
					<button class="btn btn-default modal-dismiss">Cancel</button>
				</div>
			</div>
		</footer>
	</section>
</div>
<?php
include(AdminFooter);
?>