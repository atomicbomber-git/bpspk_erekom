<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-lap').addClass('nav-expanded nav-active');
	$('nav li.stat-rek').addClass('nav-active');
	$('#filter_jns_ikan,#filter_jsn_produk,#filter_satker,.pilpemohon').select2();

	$('.btn-dwlap').click(function(){
		$('#excel').val('yes');
		$('#statistik').submit();
	});

	$('.btn-viewlap').click(function(){
		$('#excel').val('');
		$('#statistik').submit();
	});
});
</script>
";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Statistik Rekomendasi Lalu Lintas Hiu & Pari</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Statistik Rekomendasi Lalu Lintas Hiu & Pari</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12">
			<form class="form-horizontal" name="statistik" id="statistik" action="laporan_new.php" method="POST"  target="_blank">
				<input type="hidden" id="excel" name="excel" value="">
				<section class="panel panel-featured-primary">
					<header class="panel-heading">
						<h2 class="panel-title">Statistik Rekomendasi Lalu Lintas Hiu & Pari</h2>
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
									<!-- <option value="all_hiu">Semua Jenis Hiu</option>
									<option value="all_pari">Semua Jenis Pari</option> -->
									<?php
									$sql->get_all('ref_data_ikan');
									echo $sql->sql;
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
							<label class="col-md-3 control-label">Kelompok Ikan</label>
							<div class="col-md-5">
								<select class="form-control" name="filter_kel_ikan" id="filter_kel_ikan">
									<option value="all">Semua</option>
									<option value="1">Ikan Pari</option>
									<option value="2">Ikan Hiu</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label">Jenis Produk</label>
							<div class="col-md-5">
								<select class="form-control" name="filter_jsn_produk" id="filter_jsn_produk">
									<option value="all">Semua</option>
									<?php
									$sql->get_all('ref_jns_sampel');
									if($sql->num_rows>0){
										foreach($sql->result as $r){
											echo '<option value="'.$r['id_ref'].'">'.$r['jenis_sampel'].'</option>';
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
						<div class="form-group">
							<label class="col-md-3 control-label">Satuan Kerja</label>
							<div class="col-md-5">
								<select class="form-control" name="filter_satker" id="filter_satker">
									<option value="all">Semua</option>
									<?php
									$sql->get_all('ref_satuan_kerja');
									if($sql->num_rows>0){
										foreach($sql->result as $r){
											echo '<option value="'.$r['id_satker'].'">'.$r['nm_satker'].'</option>';
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
								<button type="button" class="btn btn-sm btn-primary btn-viewlap">Proses</button>
								<button type="button" class="btn btn-sm btn-warning btn-dwlap">Download Excel</button>
							</div>
						</div>
					</div>
				</section>
			</form>
		</div>
	</div>

</section>
<?php
@include(AdminFooter);
?>