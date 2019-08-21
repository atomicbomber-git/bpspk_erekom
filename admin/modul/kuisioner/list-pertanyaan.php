<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-kuis').addClass('nav-expanded nav-active');
	$('nav li.kuis-p').addClass('nav-active');
});
</script>
<script src=\"custom.js?t=".time()."\"></script>
";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Daftar Pertanyaan</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Daftar Pertanyaan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-7">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<div class="panel-actions">
						<a href="#" class="fa fa-caret-down"></a>
					</div>
					<h2 class="panel-title">Daftar Pertanyaan</h2>
				</header>
				
				<div class="panel-body">
					<table class="table table-hover table-striped mb-none" id="ref-pertanyaan">
						<thead>
							<tr>
								<th width="5px;">#</th>
								<th>Pertanyaan</th>
							</tr>
						</thead>
						<tbody id="isi_table">
						<?php
						$sql->get_all('tb_kuisioner_q');
						$no=1;
						foreach ($sql->result as $row) {
						    echo "
						    <tr>
						        <td>".$no."</td>
						        <td><a href='#edit' class='text-bold'>".$row['pertanyaan']."</a>
						        	<p class=\"actions-hover actions-fade\"> 
						        	<a href='./edit.php?p=".base64_encode($row['id_q'])."'>Edit</a> 
						        	<a href='#' data-id='".base64_encode($row['id_q'])."' class='row-delete'>Delete</a>
						        	</p>
						        </td>
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
										<input type="hidden" id="del_pid">
									</div>
								</div>
							</footer>
						</section>
					</div>
				</div>
			</section>
		</div>

		<div class="col-md-5">
			<form id="q_add" class="form-horizontal" method="post">
				<input type="hidden" name="a" id="a" value="add_q" />
				<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="fa fa-caret-down"></a>
						</div>
						<h2 class="panel-title" id="cat-panel-title">Tambah Pertanyaan Baru</h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="pertanyaan">Pertanyaan</label>
								<input name="pertanyaan" id="pertanyaan" placeholder="Pertanyaan" type="text" class="form-control">
							</div>
						</div>
			
					</div>
					<footer class="panel-footer">
						<button type="submit" id="btn-aksi" name="btn-aksi" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Tambah </button>
						<button type="reset" class="btn btn-default">Reset</button>
					</footer>
				</section>
			</form>
		</div>
	</div>

</section>
<?php
@include(AdminFooter);
?>