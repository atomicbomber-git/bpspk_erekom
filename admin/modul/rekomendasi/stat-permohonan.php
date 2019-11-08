<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-st').addClass('nav-active');
	$('.pilpemohon').select2({
		width: '100%'
	});
});
</script>
<script src=\"custom-1.js\"></script>
";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Status Permohonan Rekomendasi</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Rekomendasi</span></li>
				<li><span>Status Permohonan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-primary">
				 <span class="pull-right">Hari ini : <?php echo tanggalIndo(date('Y-m-d H:i:s'),"l, j F Y H:i");?> </span><br/>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Status Permohonan</h2>
				</header>
				<div class="panel-body">
					<form class="form-horizontal" id="stat_costumfilter" name="stat_costumfilter" type="post">
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="toggle" data-plugin-toggle="">
                                    <section class="toggle">
                                        <label>Pencarian Rinci</label>
                                        <div class="toggle-content panel-body">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Pemohon</label>
                                                    <div class="col-md-7">
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
                                                    <label class="col-md-3 control-label">Status</label>
                                                    <div class="col-md-7">
                                                    	<select name="filter_stat" id="filter_stat" class="form-control">
                                                    		<option value="all">Semua</option>
                                                    		<option value="1">Menunggu Verifiaksi Admin</option>
                                                    		<option value="2">Proses Pemeriksaan Oleh Pemeriksa / Verifikator</option>
                                                    		<option value="3">Permohonan Ditolak, Berkas Tidak Lengkap</option>
                                                    		<option value="4">Menunggu Persetujuan Kepala Balai</option>
                                                    		<option value="5">Rekomendasi Sudah Diterbitkan</option>
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
					<table class="table table-bordered table-striped mb-none" id="stat-permohonan">
						<thead>
							<tr>
								<th width="5%">No</th>
								<th width="25%">Nama Perusahaan/Perseorangan</th>
								<th width="17%">Tanggal Pengajuan</th>
								<th>Tujuan</th>
								<th width="7%" style="text-align: center">Verifikasi Admin</th>
								<th width="7%" style="text-align: center">Pemeriksaan</th>
								<th width="7%" style="text-align: center">Kepala Sub Seksi PP</th>
								<th width="7%" style="text-align: center">Kepala Loka</th>
								<th width="7%" style="text-align: center">Selesai</th>
								<th width="15%" style="text-align: center">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
<div id="DelConfirm" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h2 class="panel-title">Hapus Permohonan?</h2>
		</header>
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-icon">
					<i class="fa fa-question-circle"></i>
				</div>
				<div class="modal-text">
					<p>Apakah anda yakin akan menghapus permohonan ini?<br/><strong>Peringatan</strong>: Data yang sudah dihapus tidak dapat dikembalikan.</p>
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
<?php
include(AdminFooter);
?>