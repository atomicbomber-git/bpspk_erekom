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
			$('nav li.df-bw').addClass('nav-active');
			
		});

	</script>
	<script src=\"custom.js\"></script>
";

?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Rekap </h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Rekap </span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12">
			<form class="form-horizontal" name="rekap" id="rekap" action="web-list.php" method="POST"  target="_blank">
				<input type="hidden" id="excel" name="excel" value="">
				<section class="panel panel-featured-primary">
					<header class="panel-heading">
						<h2 class="panel-title">Rekap Surat Rekomendasi</h2>
					</header>
					
					<div class="panel-body">
						
						<div class="form-group">
							<label class="col-md-3 control-label">Filter Bulan</label>
							<div class="col-md-5">
								<select class="form-control" name="filter_bulan" id="filter_bulan">
									<option value="01">Januari</option>
									<option value="02">Februari</option>
									<option value="03">Maret</option>
									<option value="04">April</option>
									<option value="05">Mei</option>
									<option value="06">Juni</option>
									<option value="07">Juli</option>
									<option value="08">Agustus</option>
									<option value="09">September</option>
									<option value="10">Oktober</option>
									<option value="11">November</option>
									<option value="12">Desember</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3 control-label">Filter Tahun</label>
							<div class="col-md-5">
								<select name="filter_tahun" id="filter_tahun" class='form-control pilpemohon'>
                            		<?php
                            		$q=$sql->run("SELECT DISTINCT(YEAR(tgl_surat)) as thn FROM tb_rekomendasi");
                            		if($q->rowCount()>0){
                            			foreach($q->fetchAll() as $pil){
                            				if(date('Y')==$pil['thn']){
                            					echo '<option selected value="'.$pil['thn'].'">'.$pil['thn'].'</option>';
                            				}else{
                            					echo '<option value="'.$pil['thn'].'">'.$pil['thn'].'</option>';
                            				}
                            			}
                            		}
                            		?>
                            	</select>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label class="col-md-3"></label>
							<div class="col-md-5">
								<button type="submit" class="btn btn-sm btn-primary btn-viewlap">Proses</button>
								<!-- <button type="button" class="btn btn-sm btn-warning btn-dwlap">Download Excel</button> -->
							</div>
						</div>
					</div>
				</section>
			</form>
		</div>
	</div>

</section>
</div>
<?php
@include(AdminFooter);
?>