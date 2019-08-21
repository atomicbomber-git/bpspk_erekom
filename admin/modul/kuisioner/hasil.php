<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-kuis').addClass('nav-expanded nav-active');
	$('nav li.kuis-hasil').addClass('nav-active');
});
</script>
<script src=\"custom.js?t=".time()."\"></script>
";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Hasil Kuisioner</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Hasil Kuisioner</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<div class="panel-actions">
						<a href="#" class="fa fa-caret-down"></a>
					</div>
					<h2 class="panel-title">Hasil Kuisioner</h2>
				</header>
				<div class="panel-body">
				<?php
					//arr_jns
					$arr_jenis=array();
					$sql->get_all('tb_kuis_jns');
					if($sql->num_rows>0){
						foreach ($sql->result as $j) {
							$arr_jenis[$j['id_jns']]=array("jenis"=>$j['jenis'],"fb"=>$j['feedback']);
						}
					}
					//arr_pertanyaan
					$arr_pertanyaan=array();
					$arr_hasil=array();
					$sql->order_by="id_jns ASC, id_kel ASC, id_q ASC ";
					$sql->get_all('tb_kuisioner_q',array('stat'=>1));
					$found_q=$sql->num_rows;
					if($found_q>0){
						foreach ($sql->result as $rp) {
							$arr_pertanyaan[]=array(
								"q"=>$rp['pertanyaan'],
								"id"=>$rp['id_q'],
								"jns"=>$rp['id_jns'],
								"optional"=>$rp['ket_optional']);
						}

						$sql->order_by=" ref_idq DESC";
						$sql->get_all('tb_kuisioner_a');
						
						$found_a=$sql->num_rows;
						if($found_a>0){
							$qnum=0;
							$sts=0;
							$ts=0;
							$ks=0;
							$cs=0;
							$s=0;
							$ss=0;
							$hk_sts=0;
							$hk_ts=0;
							$hk_ks=0;
							$hk_cs=0;
							$hk_s=0;
							$hk_ss=0;
							$jlhres=0;
							foreach ($sql->result as $ra) {
								if($qnum!=$ra['ref_idq']){
									$qnum=$ra['ref_idq'];
									$sts=0;$ts=0;$ks=0;$cs=0;$s=0;$ss=0;$jlhres=0;
									$hk_sts=0;$hk_ts=0;$hk_ks=0;$hk_cs=0;$hk_s=0;$hk_ss=0;
								}
								if($ra['jawaban']==1){
									$sts++;
								}
								if($ra['jawaban']==2){
									$ts++;
								}
								if($ra['jawaban']==3){
									$ks++;
								}
								if($ra['jawaban']==4){
									$cs++;
								}
								if($ra['jawaban']==5){
									$s++;
								}
								if($ra['jawaban']==6){
									$ss++;
								}

								if($ra['harapan']==7){
									$hk_sts++;
								}
								if($ra['harapan']==8){
									$hk_ts++;
								}
								if($ra['harapan']==9){
									$hk_ks++;
								}
								if($ra['harapan']==10){
									$hk_cs++;
								}
								if($ra['harapan']==11){
									$hk_s++;
								}
								if($ra['harapan']==12){
									$hk_ss++;
								}

								$jlhres++;
								$arr_hasil[$qnum]['sts']=$sts;
								$arr_hasil[$qnum]['ts']=$ts;
								$arr_hasil[$qnum]['ks']=$ks;
								$arr_hasil[$qnum]['cs']=$cs;
								$arr_hasil[$qnum]['s']=$s;
								$arr_hasil[$qnum]['ss']=$ss;
								$arr_hasil[$qnum]['hk_sts']=$hk_sts;
								$arr_hasil[$qnum]['hk_ts']=$hk_ts;
								$arr_hasil[$qnum]['hk_ks']=$hk_ks;
								$arr_hasil[$qnum]['hk_cs']=$hk_cs;
								$arr_hasil[$qnum]['hk_s']=$hk_s;
								$arr_hasil[$qnum]['hk_ss']=$hk_ss;
								$arr_hasil[$qnum]['jlh']=$jlhres;
							}
							/*echo '<pre>';
							print_r($arr_hasil);
							echo '</pre>';*/
						}
					}

				?>
				<table class="table table-bordered table-hover">
					<?php
					$no=1;
					$jenis=0;
					foreach ($arr_pertanyaan as $ap) {
						$jlh_responden=($arr_hasil[$ap['id']]['jlh']!="")?$arr_hasil[$ap['id']]['jlh']:0;
						$score_sts=$arr_hasil[$ap['id']]['sts'];
						$score_ts=$arr_hasil[$ap['id']]['ts'];
						$score_ks=$arr_hasil[$ap['id']]['ks'];
						$score_cs=$arr_hasil[$ap['id']]['cs'];
						$score_s=$arr_hasil[$ap['id']]['s'];
						$score_ss=$arr_hasil[$ap['id']]['ss'];
						$score_hk_sts=$arr_hasil[$ap['id']]['hk_sts'];
						$score_hk_ts=$arr_hasil[$ap['id']]['hk_ts'];
						$score_hk_ks=$arr_hasil[$ap['id']]['hk_ks'];
						$score_hk_cs=$arr_hasil[$ap['id']]['hk_cs'];
						$score_hk_s=$arr_hasil[$ap['id']]['hk_s'];
						$score_hk_ss=$arr_hasil[$ap['id']]['hk_ss'];

						if($ap['jns']!=$jenis){
							$jenis=$ap['jns'];
							$no=1;
							echo '<tr><td colspan="'.(($arr_jenis[$ap['jns']]['fb']==1)?'14':'8').'"><h4>'.$arr_jenis[$ap['jns']]['jenis'].'</h4></td></tr>';
						}
						echo '
						<tr>
							<td rowspan="3">'.$no.'. <strong>'.$ap['q'].'</strong>'.(($ap['optional']!="")?" <br/>*) ".$ap['optional']:"").'</td>
							<td colspan="6" style="text-align:center">Kualitas Layanan (%)</td>
							'.(($arr_jenis[$ap['jns']]['fb']==1)?'<td colspan="6" style="text-align:center">Harapan Responden (%)</td>':'').'
							<td rowspan="2"><small>Jlh Responden</small></td>
						</tr>
						<tr>
							<td style="width:5%;text-align:center"><small>Sangat Tidak Setuju</small></td>
							<td style="width:5%;text-align:center"><small>Tidak Setuju</small></td>
							<td style="width:5%;text-align:center"><small>Kurang Setuju</small></td>
							<td style="width:5%;text-align:center"><small>Cukup Setuju</small></td>
							<td style="width:5%;text-align:center"><small>Setuju</small></td>
							<td style="width:5%;text-align:center"><small>Sangat Setuju</small></td>
							'.(($arr_jenis[$ap['jns']]['fb']==1)?'<td style="width:5%;text-align:center"><small>Sangat Tidak Setuju</small></td>
							<td style="width:5%;text-align:center"><small>Tidak Setuju</small></td>
							<td style="width:5%;text-align:center"><small>Kurang Setuju</small></td>
							<td style="width:5%;text-align:center"><small>Cukup Setuju</small></td>
							<td style="width:5%;text-align:center"><small>Setuju</small></td>
							<td style="width:5%;text-align:center"><small>Sangat Setuju</small></td>':'').'
						</tr>
						<tr>
							<td style="width:5%;text-align:center">'.clean_num(number_format((($score_sts/$jlh_responden)*100),2)).' </td>
							<td style="width:5%;text-align:center">'.clean_num(number_format((($score_ts/$jlh_responden)*100),2)).' </td>
							<td style="width:5%;text-align:center">'.clean_num(number_format((($score_ks/$jlh_responden)*100),2)).' </td>
							<td style="width:5%;text-align:center">'.clean_num(number_format((($score_cs/$jlh_responden)*100),2)).' </td>
							<td style="width:5%;text-align:center">'.clean_num(number_format((($score_s/$jlh_responden)*100),2)).' </td>
							<td style="width:5%;text-align:center">'.clean_num(number_format((($score_ss/$jlh_responden)*100),2)).' </td>
							'.(($arr_jenis[$ap['jns']]['fb']==1)?'<td style="width:5%;text-align:center">'.clean_num(number_format((($score_hk_sts/$jlh_responden)*100),2)).' </td>
							<td style="width:5%;text-align:center">'.clean_num(number_format((($score_hk_ts/$jlh_responden)*100),2)).' </td>
							<td style="width:5%;text-align:center">'.clean_num(number_format((($score_hk_ks/$jlh_responden)*100),2)).' </td>
							<td style="width:5%;text-align:center">'.clean_num(number_format((($score_hk_cs/$jlh_responden)*100),2)).' </td>
							<td style="width:5%;text-align:center">'.clean_num(number_format((($score_hk_s/$jlh_responden)*100),2)).' </td>
							<td style="width:5%;text-align:center">'.clean_num(number_format((($score_hk_ss/$jlh_responden)*100),2)).' </td>':'').'
							<td>'.$jlh_responden.' orang</td>
						</tr>';
						$no++;
					}
					?>
					
				</table>
				</div>
			</section>
		</div>
	</div>
</section>
<?php
function clean_num($angka){
	if($angka=='0.00'){
		return "";
	}else{
		return $angka;
	}
}
@include(AdminFooter);
?>