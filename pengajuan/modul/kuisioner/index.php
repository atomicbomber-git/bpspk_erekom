<?php
$sql->order_by=" q_answered DESC ";
$sql->limit=" 1";
$sql->get_row('tb_kuisioner_s',array('ref_idpemohon'=>U_ID));
if($sql->num_rows>0){
	$r=$sql->result;
	$lastkuisionerdate=$r['q_answered'];

	$q=$sql->run("SELECT COUNT(idp) jlh FROM tb_permohonan WHERE ref_iduser='".U_ID."' AND tgl_pengajuan> '".$lastkuisionerdate."'");
	$r=$q->fetch();
	if(($r['jlh']+1)<5){
		header('location:?pengajuan');
	}
}

$arr_soal_rule=array();
$arr_soal_msg=array();
$sql->order_by="id_q ASC";
$sql->get_all('tb_kuisioner_q',array('stat'=>1));
if($sql->num_rows>0){
	$n=1;
	$sjns=0;
	foreach ($sql->result as $s) {
		if($s['optional']==0){
			if($sjns!=$s['id_jns']){
				$sjns=$s['id_jns'];
				$n=1;
			}

			if($sjns==1){
				$arr_soal_rule[]="soalkl_".$s['id_q']."_".$sjns."_".$n.":{required:true} ";
				$arr_soal_msg[]="soalkl_".$s['id_q']."_".$sjns."_".$n.": \"&nbsp;- Pernyataan No. ".$n." (Kualitas Layanan) Belum Diisi\"";
				$arr_soal_rule[]="soalhk_".$s['id_q']."_".$sjns."_".$n.":{required:true} ";
				$arr_soal_msg[]="soalhk_".$s['id_q']."_".$sjns."_".$n.": \"&nbsp;- Pernyataan No. ".$n." (Harapan Konsumen) Belum Diisi\"";
			}else{
				$arr_soal_rule[]="soal_".$s['id_q']."_".$sjns."_".$n.":{required:true} ";
				$arr_soal_msg[]="soal_".$s['id_q']."_".$sjns."_".$n.": \"- Pernyataan No. ".$n." Belum Diisi\"";
			}
		}
	$n++;
	}
}
$soal_rule=implode(', ', $arr_soal_rule);
$soal_msg=implode(', ', $arr_soal_msg);
$SCRIPT_FOOT="
<script>
$(document).ready(function(){
	$('nav li.nav1').addClass('nav-active');
	
	$('#form_kuisioner').validate({
		ignore: [],
		errorClass: 'error',
		rules:{
			".$soal_rule."
		},
		messages:{
			".$soal_msg."
		},
		errorPlacement: function (error, element) {
	        error.insertBefore($(element).closest('table').find('.error_text'));
	        //$(element).closest('table').find('.error_text').html(error);
	    },
	    highlight: function (element, validClass) {
	        //$(element).closest('tr').addClass('warning');
	        //$(element).closest('table').find('tr.soal').addClass('warning');
	    },
	    unhighlight: function (element, validClass) {
	        //$(element).closest('tr').removeClass('warning');
	        //$(element).closest('table').find('tr.soal').removeClass('warning');
	    },
		submitHandler: function(form) {
			var stack_bar_bottom = {'dir1': 'up', 'dir2': 'right', 'spacing1': 0, 'spacing2': 0};
			
			$.ajax({
				url:'".c_STATIC."pengajuan/modul/kuisioner/ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$('#form_kuisioner').serialize(),
				beforeSend:function(){
					$('#btn_submit').prop('disabled', true);
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
								//location.reload();
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
					$('#btn_submit').prop('disabled', false);
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
		<h2>Pengajuan Rekomendasi</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_DOMAIN;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Pengajuan Rekomendasi</span></li>
			</ol>
			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<form id="form_kuisioner" method="post">
		<input type="hidden" name="a" value="kuisioner">
		<div class="row">
			<?php
			if(U_VERIFY==1){
				$sql->get_row('tb_biodata',array('ref_iduser'=>U_ID),'idbio');
				if($sql->num_rows>0){
					$sql->get_row('tb_berkas',array('ref_iduser'=>U_ID,'jenis_berkas'=>1),'idb');
					if($sql->num_rows>0){
						?>
						<div class="col-md-12">
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
									</div>
									<h2 class="panel-title">Kuisioner</h2>
								</header>
								<div class="panel-body">
									<p class="text-center">Untuk Meningkatkan Kualitas Pelayanan, 
									<br/>Kami Memerlukan <em>Feedback</em>/Masukan dari Bapak/Ibu terhadap aplikasi E-Rekomendasi melalui kuisioner ini.
									<br/>Silakan Isi kuisioner ini dengan memilih enam (6) parameter yaitu Sangat Tidak Setuju, Tidak Setuju, Kurang Setuju, Cukup Setuju, Setuju dan Sangat Setuju</p>
									<br/>
									<?php
									$sql->get_all('tb_kuis_jns');
									if($sql->num_rows>0){
										foreach ($sql->result as $jns) {
											$arr_jenis[$jns['id_jns']]=array(
												"id_jns"=>$jns['id_jns'],
												"jenis"=>$jns['jenis'],
												"hk"=>$jns['feedback']);
										}
									}

									echo '<h3>'.$arr_jenis[1]['jenis'].'</h3>';

									
									$soal1=$sql->run("SELECT s.* FROM tb_kuisioner_q s WHERE s.stat ='1' AND s.id_jns='".$arr_jenis[1]['id_jns']."' ORDER BY s.id_q ASC");
									if($soal1->rowCount()>0){
										$no1=1;
										foreach ($soal1->fetchAll() as $s1) {
											$optional=(($s1['optional']==1)?" *)<br/><small class='text text-danger'>".$s1['ket_optional']."</small>":"");
											$s1name_a='soalkl_'.$s1['id_q'].'_'.$s1['id_jns'].'_'.$no1;
											$s1name_b='soalhk_'.$s1['id_q'].'_'.$s1['id_jns'].'_'.$no1;

											echo '<div class="table-responsive" style="max-width=100%">';
											echo '<table class="table table-bordered">';
											echo '<tr class="soal">
												<th colspan="15">'.$no1.'. '.$s1['pertanyaan'].''.$optional.' <br/><span class="error_text"></span></th>
											</tr>';
											echo '<tr>
												<td colspan="6" class="text-center">Kualitas Layanan</td>
												<td rowspan="2" width="1%"></td>
												<td colspan="6" class="text-center">Harapan Konsumen</td>
											</tr>
											<tr>
												<td width="'.(80/12).'%" class="text-center"><small>Sangat Tidak Setuju </small></td>
												<td width="'.(80/12).'%" class="text-center"><small>Tidak Setuju</small></td>
												<td width="'.(80/12).'%" class="text-center"><small>Kurang Setuju</small></td>
												<td width="'.(80/12).'%" class="text-center"><small>Cukup Setuju</small></td>
												<td width="'.(80/12).'%" class="text-center"><small>Setuju </small></td>
												<td width="'.(80/12).'%" class="text-center"><small>Sangat Setuju</small></td>
												<td width="'.(80/12).'%" class="text-center"><small>Sangat Tidak Setuju </small></td>
												<td width="'.(80/12).'%" class="text-center"><small>Tidak Setuju</small></td>
												<td width="'.(80/12).'%" class="text-center"><small>Kurang Setuju</small></td>
												<td width="'.(80/12).'%" class="text-center"><small>Cukup Setuju</small></td>
												<td width="'.(80/12).'%" class="text-center"><small>Setuju </small></td>
												<td width="'.(80/12).'%" class="text-center"><small>Sangat Setuju</small></td>
											</tr>
											';
											echo '<tr>
												<td class="text-center"><label><input type="radio" name="'.$s1name_a.'" id="'.$s1name_a.'" value="1"></label></td>
												<td class="text-center"><label><input type="radio" name="'.$s1name_a.'" id="'.$s1name_a.'" value="2"></label></td>
												<td class="text-center"><label><input type="radio" name="'.$s1name_a.'" id="'.$s1name_a.'" value="3"></label></td>
												<td class="text-center"><label><input type="radio" name="'.$s1name_a.'" id="'.$s1name_a.'" value="4"></label></td>
												<td class="text-center"><label><input type="radio" name="'.$s1name_a.'" id="'.$s1name_a.'" value="5"></label></td>
												<td class="text-center"><label><input type="radio" name="'.$s1name_a.'" id="'.$s1name_a.'" value="6"></label></td>
												<td></td>
												<td class="text-center"><label><input type="radio" name="'.$s1name_b.'" id="'.$s1name_b.'" value="7"></label></td>
												<td class="text-center"><label><input type="radio" name="'.$s1name_b.'" id="'.$s1name_b.'" value="8"></label></td>
												<td class="text-center"><label><input type="radio" name="'.$s1name_b.'" id="'.$s1name_b.'" value="9"></label></td>
												<td class="text-center"><label><input type="radio" name="'.$s1name_b.'" id="'.$s1name_b.'" value="10"></label></td>
												<td class="text-center"><label><input type="radio" name="'.$s1name_b.'" id="'.$s1name_b.'" value="11"></label></td>
												<td class="text-center"><label><input type="radio" name="'.$s1name_b.'" id="'.$s1name_b.'" value="12"></label></td>
											</tr>';
											$no1++;
											echo '</table>';
											echo '</div>';
										}
									}
									

										
									echo '<h3>'.$arr_jenis[2]['jenis'].'</h3>';

									$soal2=$sql->run("SELECT s.* FROM tb_kuisioner_q s WHERE s.stat ='1' AND s.id_jns='".$arr_jenis[2]['id_jns']."' ORDER BY s.id_q ASC");
									if($soal2->rowCount()>0){
										$no2=1;
										foreach ($soal2->fetchAll() as $s2) {
											$optional=(($s2['optional']==1)?" *)<br/><small class='text text-danger'>".$s2['ket_optional']."</small>":"");
											$s2name='soal_'.$s2['id_q'].'_'.$s2['id_jns'].'_'.$no2;

											echo '<table class="table table-bordered" width="100%">';
											echo '<tr class="soal">
												<th colspan="15">'.$no2.'. '.$s2['pertanyaan'].''.$optional.' <br/><span class="error_text"></span></th>
											</tr>';
											echo '<tr>
												<td width="'.(100/6).'%" class="text-center"><label><input type="radio" name="'.$s2name.'" id="'.$s2name.'" value="1"> <small>Sangat Tidak Setuju </small></label></td>
												<td width="'.(100/6).'%" class="text-center"><label><input type="radio" name="'.$s2name.'" id="'.$s2name.'" value="2"> <small>Tidak Setuju</small></label></td>
												<td width="'.(100/6).'%" class="text-center"><label><input type="radio" name="'.$s2name.'" id="'.$s2name.'" value="3"> <small>Kurang Setuju</small></label></td>
												<td width="'.(100/6).'%" class="text-center"><label><input type="radio" name="'.$s2name.'" id="'.$s2name.'" value="4"> <small>Cukup Setuju</small></label></td>
												<td width="'.(100/6).'%" class="text-center"><label><input type="radio" name="'.$s2name.'" id="'.$s2name.'" value="5"> <small>Setuju </small></label></td>
												<td width="'.(100/6).'%" class="text-center"><label><input type="radio" name="'.$s2name.'" id="'.$s2name.'" value="6"> <small>Sangat Setuju</small></label></td>
											</tr>';
											$no2++;
											echo '</table>';
										}
									}
									?>
								</div>
								<div class="panel-footer">
									<div class="row">
										<div class="col-md-12"><button type="submit" id="btn_submit" class="btn btn-primary">Kirim</button></div>
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
								<strong>Maaf,</strong> Silakan Upload Tandatangan Anda Sebelum Mengajukan Permohonan Rekomendasi. Klik <a href="?biodata"><strong>Di Sini</strong></a> Untuk Melengkapi Biodata Anda.
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