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
			$('#ref-dtikan').DataTable({
				'ordering':false,
			});
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

?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Referensi Data Ikan</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Referensi Data Ikan</span></li>
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
					<h2 class="panel-title">Referensi Data Ikan</h2>
				</header>
				
				<div class="panel-body">
					<table class="table table-hover table-striped mb-none" id="ref-dtikan">
						<thead>
							<tr>
								<th width="5px;">#</th>
								<th width="20%">Nama Ikan</th>
								<th width="20%">Nama Latin</th>
								<th>Dilindungi/Izin Peredaran</th>
								<th>Kelompok</th>
								<th>Ciri-Ciri</th>
							</tr>
						</thead>
						<tbody id="isi_table">
						<?php
						$array_dilindungi = array(
							"1"=>"Dilindungi",
							"2"=>"Tidak Dilindungi");
						$array_peredaran = array(
							"1"=>"Dalam dan Luar Negeri",
							"2"=>"Hanya Dalam Negeri",
							"3"=>"Dilarang");
						$q=$sql->run("SELECT dt.*,kel.nama_kel FROM ref_data_ikan dt LEFT JOIN ref_kel_ikan kel ON(kel.id_ref=dt.ref_idkel) ORDER BY dt.nama_ikan ASC, dt.ref_idkel ASC ");
						$no=1;

						foreach ($q->fetchAll() as $row) {
							if($row['peredaran']=='2' OR $row['peredaran']=='3'){
								$dasar_huk="<br/><small>".$row['ket_dasarhukum']."</small>";
							}else{
								$dasar_huk="";
							}
						    echo "
						    <tr>
						        <td>".$no."</td>
						        <td><a href='#edit' class='text-bold'>".$row['nama_ikan']."</a>
						        	<p class=\"actions-hover actions-fade\"> 
						        	<a href='./edit-data-ikan.php?landing=".base64_encode($row['id_ikan'])."'>Edit</a> 
						        	<a href='#' data-id='".base64_encode($row['id_ikan'])."' class='row-delete'>Delete</a>
						        	</p>
						        </td>
						        <td>".$row['nama_latin']."</td>
						        <td>".$array_dilindungi[$row['dilindungi']]."<br/>".$array_peredaran[$row['peredaran']]."".$dasar_huk."</td>
						        <td>".$row['nama_kel']."</td>
						        <td><a href='./ciri-ikan.php?ikan=".base64_encode($row['id_ikan'])."'>Isi Ciri-ciri</a></td>
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
			<form id="dtikan_add" class="form-horizontal" method="post">
				<input type="hidden" name="a" id="a" value="adddtikan" />
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
								<label class="control-label" for="nm_ikan">Nama Ikan</label>
								<input name="nm_ikan" id="nm_ikan" placeholder="Nama Ikan" type="text" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="nm_latin">Nama Latin</label>
								<input name="nm_latin" id="nm_latin" placeholder="Nama Latin" type="text" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="dilindungi">Dilindungi</label>
								<select class="form-control" name="dilindungi">
									<option value=""> Pilih </option>
									<option value="1"> Dilindungi</option>
									<option value="2"> Tidak Dilindungi</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="peredaran">Izin Peredaran</label>
								<select class="form-control" name="peredaran" id="peredaran">
									<option value=""> Pilih </option>
									<option value="1"> Dalam dan Luar Negeri</option>
									<option value="2"> Hanya Dalam Negeri</option>
									<option value="3"> Dilarang</option>
								</select>
							</div>
						</div>
						<div class="form-group ket_dasarhukum" style="display:none">
							<div class="col-md-12">							
								<label class="control-label" for="peredaran">Ket Dasar Hukum</label>
								<textarea name="ket_dasarhukum" id="ket_dasarhukum" class="form-control"></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="kel">Kelompok</label>
								<select class="form-control" name="kel" id="kel">
									<?php
									$sql->get_all('ref_kel_ikan');
									foreach($sql->result as $rkel){
										echo '<option value="'.$rkel['id_ref'].'">'.$rkel['nama_kel'].'</option>';
									}
									?>
								</select>
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