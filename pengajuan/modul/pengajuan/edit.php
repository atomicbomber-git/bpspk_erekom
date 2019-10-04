<?php
include ("../../engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, pnotify.custom.css, select2.css, codemirror.css, monokai.css, bootstrap-tagsinput.css, bootstrap-timepicker.css, theme.css, default.css, datatables.css, modernizr.js";

$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholde,jquery.dataTables.js,datatables.js, pnotify.custom.js, jquery.appear.js, select2.js, jquery.autosize.js, bootstrap-tagsinput.js, bootstrap-timepicker.js, theme.js, theme.init.js,jquery.validate.js,jquery.validate.msg.id.js ";

require_once(c_THEMES."auth.php");
$idp=base64_decode($_GET['data']);
$token=$_GET['token'];

if(!ctype_digit($idp)){
	exit();
}
if($token!=md5($idp.U_ID."editp")){
	exit();
}

$SCRIPT_FOOT="
<script>
$(document).ready(function(){
	$('nav li.nav1').addClass('nav-active');
	$('#btn_add_brg').click(function(){
		var tr    = $('tr.row_clone:first');
	    var clone = tr.clone();
	    clone.find(':text').val('');
	    clone.find('input').prop('disabled', false);
	    clone.show();
	    tr.after(clone);

	    clone.find('.del_thisrow').on('click', function(e) {
			e.preventDefault();
			$(this).closest('tr').remove();
		});
	});
	$('.del_thisrow2').click(function(e) {
		e.preventDefault();
		$(this).closest('tr').remove();
	});
	
	$('#update_pengajuan').validate({
		ignore: [],
		errorClass: 'error',
		rules:{
			nm_penerima:{required:true},
			alamat_penerima:{required:true},
			alamat_gudang:{required:true},
			alat_angkut:{required:true}
		},
		messages:{
			nm_penerima:{required:'Nama Penerima Harap Diisi.'},
			alamat_penerima:{required:'Alamat Penerima Harap Diisi.'},
			alamat_gudang:{required:'Alamat Gudang Harap Diisi.'},
			alat_angkut:{required:'Silakan Pilih Jenis Alat Angkut.'}
		},
		errorPlacement: function (error, element) {
	        error.insertAfter(element);
	    },
	    highlight: function (element, validClass) {
	        $(element).parent().addClass('has-error');
	    },
	    unhighlight: function (element, validClass) {
	        $(element).parent().removeClass('has-error');
	    },
		submitHandler: function(form) {
			var stack_bar_bottom = {'dir1': 'up', 'dir2': 'right', 'spacing1': 0, 'spacing2': 0};
			$.ajax({
				url:'".c_STATIC."pengajuan/modul/pengajuan/ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$('#update_pengajuan').serialize(),
				beforeSend:function(){
					$('#btn_update').prop('disabled', true);
					$('#actloading').show();	
				},
				success:function(json){	
					if(json.stat){
						var notice = new PNotify({
							title: 'Notification',
							text: json.msg,
							type: 'success',
							addclass: 'stack-bar-bottom',
							stack: stack_bar_bottom,
							width: '60%',
							delay:1000,
							after_close:function(){
								location.href='".c_DOMAIN."';
							}
						});
					}else{
						var notice = new PNotify({
							title: 'Notification',
							text: json.msg,
							type: 'warning',
							addclass: 'stack-bar-bottom',
							stack: stack_bar_bottom,
							width: '60%',
							delay:2500
						});
					}
					$('#btn_update').prop('disabled', false);
					$('#actloading').hide();
				}
			});
		return false;
		}
	});
});
</script>";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Edit Data Pengajuan</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_DOMAIN;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Edit Data Pengajuan</span></li>
			</ol>
			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<form id="update_pengajuan" method="post">
		<input type="hidden" name="a" value="up">
		<input type="hidden" name="token" value="<?php echo md5(U_ID.$idp.'upphn'.date('Y-m-d'));?>">
		<input type="hidden" name="idp" value="<?php echo base64_encode($idp);?>">
		<div class="row">
			<?php
			if(container(App\Services\Auth::class)->isVerified()){
				$sql->get_row('tb_biodata',array('ref_iduser'=>U_ID),array('idbio','alamat'));
				if($sql->num_rows>0){
					$bio=$sql->result;
					$sql->get_row('tb_permohonan',array('ref_iduser'=>U_ID,'idp'=>$idp,'status'=>3),'*');
					if($sql->num_rows>0){
						$phn=$sql->result;
						?>
						<div class="col-md-12">
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
									</div>
									<h2 class="panel-title">Edit Data Pengajuan</h2>
								</header>
								<div class="panel-body">
									<h4>Tujuan Pengiriman</h4>
									<div class="form-group">
										<label class="control-label col-md-3">Nama Penerima</label>
										<div class="col-md-4">
											<input type="text" name="nm_penerima" class="form-control" value="<?php echo $phn['penerima'];?>">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Alamat Penerima</label>
										<div class="col-md-5">
											<textarea class="form-control" name="alamat_penerima"><?php echo $phn['tujuan'];?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Untuk</label>
										<div class="col-md-3">
											<select class="form-control" name="jenis_tujuan">
												<option value=''>-Pilih-</option>
												<option value='perdagangan' <?php echo (($phn['jenis_tujuan']=='perdagangan')?"selected":"");?>>Perdagangan</option>
												<option value='souvenir' <?php echo (($phn['jenis_tujuan']=='souvenir')?"selected":"");?>>Souvenir</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Alat Angkut</label>
										<div class="col-md-3">
											<select class="form-control" name="alat_angkut">
												<option value=''>-Pilih-</option>
												<option value='udara' <?php echo (($phn['jenis_angkutan']=='udara')?"selected":"");?>>Pesawat Udara</option>
												<option value='laut' <?php echo (($phn['jenis_angkutan']=='laut')?"selected":"");?>>Kapal Laut</option>
												<option value='darat' <?php echo (($phn['jenis_angkutan']=='darat')?"selected":"");?>>Kendaraan Darat</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Lokasi Pemeriksaan Sampel</label>
										<div class="col-md-5">
											<select class="form-control" name="alamat_gudang">
												<option value="">-- Pilih Lokasi Pemeriksan --</option>
												<option <?php echo (($phn['alamat_gudang']==$bio['alamat'])?"selected":"");?> value="<?php echo $bio['alamat'];?>"><?php echo $bio['alamat'];?></option>
												<option <?php echo (($phn['alamat_gudang']=='Kantor LPSPL Serang')?"selected":"");?> value="Kantor LPSPL Serang">Kantor LPSPL Serang</option>
												<option <?php echo (($phn['alamat_gudang']=='Kantor Satker Balikpapan, LPSPL Serang')?"selected":"");?> value="Kantor LPSPL Serang">Kantor Satker Balikpapan, LPSPL Serang</option>
												<option <?php echo (($phn['alamat_gudang']=='Kantor Satker Banjarmasin, LPSPL Serang')?"selected":"");?> value="Kantor LPSPL Serang">Kantor Satker Banjarmasin, LPSPL Serang</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-md-3">Keterangan</label>
										<div class="col-md-5">
											<textarea class="form-control" name="ket"><?php echo $phn['ket_tambahan'];?></textarea>
										</div>
									</div>
									<hr>
									<h4>Data Barang</h4>
									<table class="table table-bordered" id="tblbarang">
										<thead>
											<tr>
												<th class="text-center" >Nama Barang</th>
												<th class="text-center" width="15%">Kemasan<br>(Colly)</th>
												<th class="text-center" width="15%">Jumlah/<br>Berat (Kg)</th>
												<th class="text-center" width="20%">Asal Komoditas</th>
												<th class="text-center" width="5%">Aksi</th>
											</tr>
										</thead>
										<tbody>
											
											<?php
											$sql->get_all('tb_barang',array('ref_idphn'=>$idp),'*');
											if($sql->num_rows>0){
												foreach($sql->result as $tbr){
													echo '<tr>
														<td><input type="text" name="nm_brg[]" class="form-control" value="'.$tbr['nm_barang'].'"></td>
														<td><input type="text" name="kuantitas[]" class="form-control" value="'.$tbr['kuantitas'].'"></td>
														<td><input type="text" name="jlh[]" class="form-control" value="'.$tbr['jlh'].'"></td>
														<td><input type="text" name="asal_komoditas[]" class="form-control" value="'.$tbr['asal_komoditas'].'"></td>
														<td><a href="#" class="btn btn-sm btn-danger del_thisrow2" title="Hapus Baris Ini">X</a></td>
													</tr>';
												}
											}
											?>
											<tr class="row_clone" style="display:none">
												<td><input type="text" disabled name="nm_brg[]" class="form-control"></td>
												<td><input type="text" disabled name="kuantitas[]" class="form-control"></td>
												<td><input type="text" disabled name="jlh[]" class="form-control"></td>
												<td><input type="text" disabled name="asal_komoditas[]" class="form-control"></td>
												<td><a href="#" class="btn btn-sm btn-danger del_thisrow" title="Hapus Baris Ini">X</a></td>
											</tr>
										</tbody>
										<tfoot>
											<tr id="addrow">
												<td colspan="5">
													<p>catatan : <span class="text-alert alert-danger">Angka Desimal Menggunakan . <strong>(titik) cth: 90.2Kg</strong></span></p>
													<a href="#addrow" id="btn_add_brg" class="btn btn-sm btn-default">Tambah Barang (+)</a>
													</td>
											</tr>
										</tfoot>
									</table>
								</div>
								<div class="panel-footer">
									<div class="row">
										<div class="col-md-12"><button type="submit" id="btn_update" class="btn btn-primary">Simpan Perubahan</button></div>
										<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Loading...</span>
									</div>
								</div>
							</section>
						</div>
					<?php
					}else{
						?>
						<div class="col-md-12">
							<div class="alert alert-danger">
								<strong>Maaf, Data tidak dapat diakses karena masih dalam pemeriksaan.
							</div>
						</div>
						<?php
					}
				}else{
					?>
					<div class="col-md-12">
						<div class="alert alert-danger">
							<strong>Maaf,</strong> Anda Harus Melakukan Melengkapi Biodata Anda untuk menggunakan fasilitas ini. Klik <a href="?biodata"><strong>Di Sini</strong></a> Untuk Melengkapi Biodata Anda.
						</div>
					</div>
					<?php
				}
			}else{
				?>
				<div class="col-md-12">
					<div class="alert alert-danger">
						<strong>Maaf,</strong> Anda Harus Melakukan Verifikasi Akun Terlebih Dahulu untuk menggunakan fasilitas ini. Klik <a href="?verifikasi"><strong>Di Sini</strong></a> Untuk Melakukan Verifikasi Akun.
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</form>
</section>
<?php
include(AdminFooter);
?>
