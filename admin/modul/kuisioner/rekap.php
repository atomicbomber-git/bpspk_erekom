<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-kuis').addClass('nav-expanded nav-active');
	$('nav li.kuis-rekap').addClass('nav-active');

	$('.pilpemohon').select2({
		width: '100%'
	});
});
</script>
";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Rekap Kuisioner</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Rekap Kuisioner</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Rekap Kuisioner</h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal" action="rekap_kuisioner.php" method="POST">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Pemohon</label>
                                <div class="col-md-7">
                                	<select name="filter_pemohon" id="filter_pemohon" class='form-control pilpemohon'>
                                		<option value="all">Semua</option>
                                		<?php
                                		$q=$sql->run("SELECT * FROM tb_userpublic tu ORDER BY tu.nama_lengkap ASC");
                                		if($q->rowCount()>0){
                                			foreach($q->fetchAll() as $pil){
                                				echo '<option value="'.$pil['iduser'].'">'.$pil['nama_lengkap'].'</option>';
                                			}
                                		}
                                		?>
                                	</select>
                                </div>
                            </div>
                            <div class="form-group">
								<label class="col-md-3 control-label">Filter Perbulan</label>
								<div class="col-md-5">
									<label>
										<div class="input-group">
											<input type="text" class="form-control" id="filter_bulan" name="filter_bulan" data-plugin-datepicker="" data-date-min-view-mode="months" data-date-format="yyyy-mm" data-date-autoclose="true" data-date-orientation="top">
											<span class="input-group-addon">s/d</span>
											<input type="text" class="form-control" id="filter_bulan2" name="filter_bulan2" data-plugin-datepicker="" data-date-min-view-mode="months" data-date-format="yyyy-mm" data-date-autoclose="true" data-date-orientation="top">
										</div>
									</label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">Download Excel</label>
								<div class="col-md-5">
									<input type="checkbox" name="excel" value="yes">
								</div>
							</div>
                            <div class="form-group">
                            	<label class="col-md-3"></label>
                            	<div class="col-md-7">
                                	<button type="reset" class="btn btn-default" id="filter_reset">Reset</button>
                                	<input type="submit" class="btn btn-primary" value="Tampilakan Rekap">
                                </div>
                            </div>
                        </div>
                    </form>
				</div>
			</section>
		</div>
	</div>
</section>
<?php
@include(AdminFooter);
?>