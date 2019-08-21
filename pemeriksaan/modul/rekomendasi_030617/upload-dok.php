<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('ul li.nav-foto').addClass('active');
});
</script>
<script src=\"foto-dok.js\"></script>
";

$idpengajuan=U_IDP;
if(ctype_digit($idpengajuan)){
?>
<body class="hold-transition skin-blue sidebar-mini">

<div class="content-wrapper">
	<section class="content-header">
		<h1>
		Dokumentasi Pemeriksaan
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo c_URL;?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dokumentasi Pemeriksaan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-4">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Upload Foto</h3>
					</div>
					<div class="box-body">
						<form class="form-horzontal" id="upload_foto" name="upload_foto" enctype="multipart/form-data">
						<input type="hidden" name="a" value="adft">
							<div class="form-group">
								<label class="control-label">Foto</label>
								<input type="file" class="form-control" name="file_foto" id="file_foto" accept="image/*" capture>
							</div>
							<div class="form-group">
								<label class="control-label">Keterangan Foto</label>
								<input type="text" class="form-control" name="ket_foto" id="ket_foto">
							</div>
							<button type="submit" name="btn_upload" id="btn_upload" class="btn btn-primary btn-flat">Upload</button>
							<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Foto Dokumentasi</h3>
					</div>
					<div class="box-body">
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
											$edit="<a href='#' data-id=\"".base64_encode($ft['id_dok'])."\" data-edit='".$ft['ket_foto']."' class='edit-row btn btn-xs btn-success'>Edit</a>";
											$del=" <a href='#' data-del=\"".base64_encode($ft['id_dok'])."\" class=\"delete-row btn btn-xs btn-danger\">Hapus</a>";
											$no++;
											echo '<tr>';
											echo '<td>'.$no.'</td>';
											echo '<td><a class="img-prev" href="'.ADM_FOTO.$ft['nm_file'].'"><img src="'.ADM_FOTO.'thumb_'.$ft['nm_file'].'"></a><br>'.$ft['ket_foto'].'<br/>'.$del.'</td>';
											echo '</tr>';
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>


</div>
</body>
<?php
}
include(AdminFooter);
?>
