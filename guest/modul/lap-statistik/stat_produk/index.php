<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('ul li.nav-lap').addClass('active');
	$('ul li.stat-pr').addClass('active');
	$('#filter_jns_ikan,#filter_jsn_produk,.pilpemohon').select2();

	$('#filter_tahun,#filter_tahun2').datepicker({
		dateFormat: 'yyyy',
		autoclose :true,
		orientation:'top',
		minViewMode:'years'
	});

	$('#filter_bulan,#filter_bulan2').datepicker({
		dateFormat: 'yyyy-mm',
		autoclose :true,
		orientation:'top',
		minViewMode:'months'
	});

	$('#filter_hari,#filter_hari2').datepicker({
		dateFormat: 'yyyy-mm-dd',
		autoclose :true,
		orientation:'top'
	});
});
</script>
";
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="content-wrapper">
	<section class="content-header">
		<h1>
		Statistik Produk Hiu & Pari
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo c_URL;?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Statistik Produk Hiu & Pari</li>
		</ol>
	</section>
	<section class="content">
	<div class="row">
		<div class="col-md-12">
			<form class="form-horizontal" name="statistik" action="laporan.php" method="POST"  target="_blank">
				<section class="panel panel-featured-primary">
					<header class="panel-heading">
						<h2 class="panel-title">Statistik Produk Hiu & Pari</h2>
					</header>
					
					<div class="panel-body">
						<div class="form-group" style="margin-bottom: 0px">
							<label class="col-md-3 control-label">Filter Waktu</label>
							<div class="radio col-md-4">
								<label>
									<input type="radio" name="filter_waktu" id="tahun" checked value="tahun">
									Filter Pertahun
									<div class="input-group">
										<input type="text" class="form-control" id="filter_tahun" name="filter_tahun" data-plugin-datepicker="" data-date-min-view-mode="years" data-date-format="yyyy" data-date-autoclose="true" data-date-orientation="top">
										<span class="input-group-addon">s/d</span>
										<input type="text" class="form-control" id="filter_tahun2" name="filter_tahun2" data-plugin-datepicker="" data-date-min-view-mode="years" data-date-format="yyyy" data-date-autoclose="true" data-date-orientation="top">
									</div>
								</label>
							</div>
						</div>
						<div class="form-group" style="margin-bottom: 0px">
							<label class="col-md-3 control-label"></label>
							<div class="radio col-md-5">
								<label>
									<input type="radio" name="filter_waktu" id="bulan" value="bulan">
									Filter Perbulan
									<div class="input-group">
										<input type="text" class="form-control" id="filter_bulan" name="filter_bulan" data-plugin-datepicker="" data-date-min-view-mode="months" data-date-format="yyyy-mm" data-date-autoclose="true" data-date-orientation="top">
										<span class="input-group-addon">s/d</span>
										<input type="text" class="form-control" id="filter_bulan2" name="filter_bulan2" data-plugin-datepicker="" data-date-min-view-mode="months" data-date-format="yyyy-mm" data-date-autoclose="true" data-date-orientation="top">
									</div>
								</label>
							</div>
						</div>
						<div class="form-group" style="margin-bottom: 0px">
							<label class="col-md-3 control-label"></label>
							<div class="radio col-md-5">
								<label>
									<input type="radio" name="filter_waktu" id="hari" value="hari">
									Filter Perhari
									<div class="input-group ">
										<input type="text" class="form-control" id="filter_hari" name="filter_hari" data-plugin-datepicker="" data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-date-orientation="top">
										<span class="input-group-addon">s/d</span>
										<input type="text" class="form-control" id="filter_hari2" name="filter_hari2" data-plugin-datepicker="" data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-date-orientation="top">
									</div>
								</label>
							</div>
						</div><br/>
						<div class="form-group">
							<label class="col-md-3 control-label">Jenis Ikan</label>
							<div class="col-md-5">
								<select class="form-control" name="filter_jns_ikan" id="filter_jns_ikan">
									<option value="all">Semua</option>
									<?php
									$sql->get_all('ref_data_ikan');
									if($sql->num_rows>0){
										foreach($sql->result as $r){
											echo '<option value="'.$r['id_ikan'].'">'.$r['nama_ikan'].' ('.$r['nama_latin'].')</option>';
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Pemohon</label>
							<div class="col-md-5">
								<select name="filter_pemohon" id="filter_pemohon" class='form-control pilpemohon'>
                            		<option value="all">Semua</option>
                            		<?php
                            		$q=$sql->run("SELECT DISTINCT(ref_iduser) iduser,tu.nama_lengkap FROM tb_permohonan JOIN tb_userpublic tu ON (tu.iduser=tb_permohonan.ref_iduser) ORDER BY tu.nama_lengkap ASC");
                            		if($q->rowCount()>0){
                            			foreach($q->fetchAll() as $pil){
                            				echo '<option value="'.$pil['iduser'].'">'.$pil['nama_lengkap'].'</option>';
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
								<button type="submit" class="btn btn-sm btn-primary">Proses</button>
							</div>
						</div>
					</div>
				</section>
			</form>
		</div>
	</div>

	</section>
</div>

</div>
</body>
<?php
@include(AdminFooter);
?>