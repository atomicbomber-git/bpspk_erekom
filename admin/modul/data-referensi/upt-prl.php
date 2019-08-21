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
			$('nav li.df-prl').addClass('nav-active');
			$('#ref-dtbk').DataTable({
				'ordering':false,
			});
		});

	</script>
	<script src=\"custom.js\"></script>
";

?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Referensi PRL</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Referensi PRL</span></li>
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
					<h2 class="panel-title">Referensi PRL</h2>
				</header>
				
				<div class="panel-body">
					<table class="table table-hover table-striped mb-none" id="ref-dtuptprl">
						<thead>
							<tr>
								<th width="20px;">#</th>
								<th>Nama</th>
								<th>Email</th>
							</tr>
						</thead>
						<tbody id="isi_table">
						<?php
						$q=$sql->run("SELECT * FROM ref_upt_prl WHERE isDelete=0 ORDER BY nama ASC");
						$no=1;
						foreach ($q->fetchAll() as $row) {
						    echo "
						    <tr>
						        <td>".$no."</td>
						        <td><a href='#edit' class='text-bold'>".$row['nama']."</a>
						        	<p class=\"actions-hover actions-fade\"> 
						        	<a href='./edit-upt-prl.php?landing=".base64_encode($row['id_upt'])."'>Edit</a> 
						        	<a href='#' data-id='".base64_encode($row['id_upt'])."' class='row-delete'>Delete</a>
						        	</p>
						        </td>
						        <td>".$row['email']."</td>
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

		<div class="col-md-5">
			<form id="dtuptprl_add" class="form-horizontal" method="post">
				<input type="hidden" name="a" id="a" value="adduptprl" />
				<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="fa fa-caret-down"></a>
						</div>
						<h2 class="panel-title" id="cat-panel-title">Tambah Data Baru</h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="nama">Nama</label>
								<input name="nama" id="nama" placeholder="Nama" type="text" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="email">Email</label>
								<input name="email" id="email" placeholder="Email" type="email" class="form-control">
							</div>
						</div>				
					</div>
					<footer class="panel-footer">
						<button type="submit" id="btn-aksi" name="btn-aksi" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Tambah Data </button>
						<button type="reset" class="btn btn-default">Reset</button>
					</footer>
				</section>
			</form>
		</div>
	</div>

</section>
</div>
<?php
@include(AdminFooter);
?>