<?php
include ("../../engine/render.php");
define ("VENDOR", c_STATIC."assets/vendor/");
if($_POST['excel']=='yes'){
	header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
	header("Content-Disposition: inline; filename=\"rekap kuisioner-".date('d-m-Y').".xls\"");
	header("Pragma: no-cache");
	header("Expires: 0");
}

//data pertanyaan
$sql->order_by="id_jns ASC, id_kel ASC, id_q ASC ";
$sql->get_all('tb_kuisioner_q',array("stat"=>1),array('id_q','id_jns','id_kel','pertanyaan'));
$arr_pertanyaan=array();
if($sql->num_rows>0){
	foreach ($sql->result as $ptny) {
		$arr_pertanyaan[$ptny['id_q']]=array(
			"id_q"=>$ptny['id_q'],
			"id_kel"=>$ptny['id_kel'],
			"id_jns"=>$ptny['id_jns'],
			"pertanyaan"=>$ptny['pertanyaan']);
	}
}

$arr_kode=array(
	"1"=>"Sangat Tidak Setuju",
	"2"=>"Tidak Setuju",
	"3"=>"Kurang Setuju",
	"4"=>"Cukup Setuju",
	"5"=>"Setuju",
	"6"=>"Sangat Setuju",
	"7"=>"Sangat Tidak Setuju",
	"8"=>"Tidak Setuju",
	"9"=>"Kurang Setuju",
	"10"=>"Cukup Setuju",
	"11"=>"Setuju",
	"12"=>"Sangat Setuju"
	);

//data jawaban
$sql->get_all('tb_kuisioner_a');
$arr_jawaban=array();
if($sql->num_rows>0){
	foreach ($sql->result as $j) {
		$arr_jawaban[$j['ref_idq']][$j['ref_idpemohon']]['jawaban']=$arr_kode[$j['jawaban']];
		$arr_jawaban[$j['ref_idq']][$j['ref_idpemohon']]['harapan']=$arr_kode[$j['harapan']];
	}
}

//filter

$filter="WHERE 1 ";

if($_POST['filter_bulan']!="" AND $_POST['filter_bulan2']!=""){
	$filter.=" AND DATE_FORMAT(s.q_answered,'%Y-%m') BETWEEN '".$_POST['filter_bulan']."' AND '".$_POST['filter_bulan2']."' ";
	$filter_text.="<tr><td>Bulan </td><td>: ".tanggalIndo($_POST['filter_bulan'].'-01','F Y')." s.d ".tanggalIndo($_POST['filter_bulan2'].'-01','F Y')."</td></tr>";
}

if($_POST['filter_pemohon']!='all'){
	$filter.=" AND s.ref_idpemohon='".($_POST['filter_pemohon'])."' ";
	$sql->get_row('tb_userpublic',array('iduser'=>$_POST['filter_pemohon']),array('nama_lengkap'));
	$fp=$sql->result;
	$filter_text.="<tr><td>Pemohon </td><td>: ".$fp['nama_lengkap']."</td></tr>";
}

$q=$sql->run("
	SELECT s.tahun,s.q_answered,s.pke,c.nama_lengkap,s.ref_idpemohon FROM tb_kuisioner_s s
	LEFT JOIN tb_userpublic c ON(s.ref_idpemohon=c.iduser) 
	$filter ORDER BY s.q_answered DESC
	");
?>

<!DOCTYPE html>
<html>
<head>
	<title>REKAPITULASI KUISIONER PELAYANAN</title>
	<link rel="stylesheet" href="<?php echo VENDOR;?>bootstrap/css/bootstrap.css">
	<style type="text/css">
		body{
			padding:10px;
		}
		/*table #dtlap
		{	
			width:100%;
			font-family : arial;
			font-size: 12px;
			border-collapse: collapse;
		}*/
		th,td
		{
			padding:5px;
		}
	</style>
</head>
<body>
	<center class="title"><h3>REKAPITULASI KUISINER PELAYANAN</h3></center>
	<center class="title"><h4>BPSPL PONTIANAK</h4></center>
	<br/>
	<br/>
	<table>
		<tr>
			<td>Filter Aktif</td>
			<td>: </td>
		</tr>
		<?php echo $filter_text;?>
	</table>
	<table border='1' id="dtlap">
		<tr>
			<th width="2%" rowspan="3">No</th>
			<th rowspan="3">Nama Pemohon</th>
			<th rowspan="3">Tanggal Pengisian</th>
			<th rowspan="" colspan="46">Aspek pelayanan publik(Masyarakat) dan aspek pelaksanaan tugas</th>
			<th rowspan="" colspan="9">Pelayanan yang bebas dari korupsi</th>

			<?php
			/*foreach ($arr_pertanyaan as $p) {
				if($p['id_jns']=='1'){
					echo '<th colspan="2">'.$p['pertanyaan'].'</th>';
				}else{
					echo '<th>'.$p['pertanyaan'].'</th>';
				}
			}*/
			?>
		</tr>
		<tr>
			<?php
			foreach ($arr_pertanyaan as $p) {
				if($p['id_jns']=='1'){
					echo '<th colspan="2">'.$p['pertanyaan'].'</th>';
				}else{
					echo '<th>'.$p['pertanyaan'].'</th>';
				}
			}
			?>
		</tr>
		<tr>
			<?php
			foreach ($arr_pertanyaan as $p) {
				if($p['id_jns']=='1'){
					echo '<th>Kualitas Layanan</th>';
					echo '<th>Harapan Responden</th>';
				}else{
					echo '<th>Kualitas Layanan</th>';
				}
			}
			?>
		</tr>

		<?php
		if($q->rowCount()>0){
			$no=1;
			foreach ($q->fetchAll() as $dt) {
				echo '<tr>';
				echo '<td>'.$no.'</td>';
				echo '<td>'.$dt['nama_lengkap'].'</td>';
				echo '<td>'.tanggalIndo($dt['q_answered'],'j F Y H:i').'</td>';

				foreach ($arr_pertanyaan as $p) {
					if($p['id_jns']=='1'){
						echo '<td>'.$arr_jawaban[$p['id_q']][$dt['ref_idpemohon']]['jawaban'].'</td>';
						echo '<td>'.$arr_jawaban[$p['id_q']][$dt['ref_idpemohon']]['harapan'].'</td>';
					}else{
						echo '<td>'.$arr_jawaban[$p['id_q']][$dt['ref_idpemohon']]['jawaban'].'</td>';
					}
				}
				echo '</tr>';

				$no++;
			}
		}
		?>		
	</table>
</body>
</html>