<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-download').addClass('nav-expanded nav-active');
	$('nav li.dw-dok').addClass('nav-active');
});
</script>
<script src=\"custom.js\"></script>
";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Download Dokumentasi Pemeriksaan</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Download</span></li>
				<li><span>Dokumentasi Pemeriksaan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12">
			<form action="download.php" target="_blank" name="download_bybulan" id="download_bybulan" class="form-horizontal" method="GET">
				<input type="hidden" name="t" value="month">
				<section class="panel">
					<header class="panel-heading">
						<h2 class="panel-title">Download Dokumentasi Pemeriksaan Per Bulan</h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-3 control-label">Filter Bulan</label>
							<div class="col-md-5">
								<select class="form-control" name="bln" id="bln">
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
								<select name="thn" id="thn" class='form-control'>
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
							</div>
						</div>
					</div>
				</section>
			</form>
		</div>
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Download Dokumentasi Pemeriksan Per Surat</h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal" id="stat_costumfilter" name="stat_costumfilter" type="post">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="toggle" data-plugin-toggle="">
                                    <section class="toggle active">
                                        <label>Pencarian Rinci</label>
                                        <div class="toggle-content panel-body">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">No Surat Rekomendasi</label>
                                                    <div class="col-md-7">
                                                    	<input type="text" class="form-control" id="filter_no_surat" name="filter_no_surat"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
													<label class="col-md-3 control-label">Filter Bulan</label>
													<div class="col-md-5">
														<select class="form-control" name="filter_bulan" id="filter_bulan">
															<option value="">Semua</option>
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
														<select name="filter_tahun" id="filter_tahun" class='form-control'>
															<option value="">Semua</option>
						                            		<?php
						                            		$q=$sql->run("SELECT DISTINCT(YEAR(tgl_surat)) as thn FROM tb_rekomendasi");
						                            		if($q->rowCount()>0){
						                            			foreach($q->fetchAll() as $pil){
						                            				// if(date('Y')==$pil['thn']){
						                            				// 	echo '<option selected value="'.$pil['thn'].'">'.$pil['thn'].'</option>';
						                            				// }else{
						                            					echo '<option value="'.$pil['thn'].'">'.$pil['thn'].'</option>';
						                            				// }
						                            			}
						                            		}
						                            		?>
						                            	</select>
													</div>
												</div>
                                                <div class="form-group">
                                                	<label class="col-md-3"></label>
                                                	<div class="col-md-7">
	                                                	<button type="reset" class="btn btn-default" id="filter_reset">Reset</button>
	                                                	<input type="submit" class="btn btn-primary" id="filter_cari" value="Cari">
	                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </form><hr/>
					<table class="table table-bordered table-striped mb-none" id="list-rekom">
						<thead>
							<tr>
								<th width="5%">No</th>
								<th width="20%">Nama Pemohon</th>
								<th width="20%">No Surat</th>
								<th width="20%">Tanggal Surat</th>
								<th width="15%" style="text-align: center">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
<?php
include(AdminFooter);
?>