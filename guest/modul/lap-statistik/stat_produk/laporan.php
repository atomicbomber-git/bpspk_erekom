<?php
include ("../../../engine/render.php");
define ("VENDOR", c_STATIC."plugins/");

//data produk
$sql->get_all('ref_jns_sampel');
$arr_jns_produk=array();
if($sql->num_rows>0){
	foreach ($sql->result as $jns) {
		$arr_jns_produk[$jns['id_ref']]=$jns['jenis_sampel'];
	}
}
$arr_produk=array();
$dp=$sql->run("SELECT DISTINCT(trh.ref_jns) idproduk FROM tb_rek_hsl_periksa trh ORDER BY trh.ref_jns");
if($dp->rowCount()>0){
	foreach ($dp->fetchAll() as $pr) {
		$arr_produk[]=array(
			"id_jns"=>$pr['idproduk'],
			"nm_produk"=>$arr_jns_produk[$pr['idproduk']]);
	}
}

//data ikan
$sql->get_all('ref_data_ikan');
$arr_ikan=array();
if($sql->num_rows>0){
	foreach ($sql->result as $dti) {
		$arr_ikan[$dti['id_ikan']]=array(
			"id_ikan"=>$dti['id_ikan'],
			"nm_ikan"=>$dti['nama_ikan'],
			"nm_latin"=>$dti['nama_latin']
			);
	}
}

//data ikan dari rekomendasi
/*$arr_hasil=array();
$h=$sql->run("SELECT trh.*,rdi.nama_ikan,rdi.nama_latin FROM tb_rek_hsl_periksa trh JOIN ref_data_ikan rdi ON(rdi.id_ikan=trh.ref_idikan) WHERE trh.ref_idikan<>0 ");
if($h->rowCount()>0){
	
	foreach ($h->fetchAll() as $hsl) {
		$idrek=$hsl['ref_idrek'];
		$idproduk=$hsl['ref_jns'];
		$arr_hasil[$idrek][]=array(
			"id_ikan"=>$hsl['ref_idikan'],
			"nm_ikan"=>$hsl['nama_ikan'],
			"nm_latin"=>$hsl['nama_latin'],
			"jns_produk"=>$hsl['ref_jns'],
			"berat"=>$hsl['berat']);
	}
}*/

//data permohonan
if($_POST['filter_pemohon']!='all'){
	$sql->get_all('tb_permohonan',array('ref_iduser'=>$_POST['filter_pemohon']),array('idp','penerima','tujuan'));
}else{
	$sql->get_all('tb_permohonan',array(),array('idp','penerima','tujuan'));
}

$arr_permohonan=array();
if($sql->num_rows>0){
	foreach ($sql->result as $rp) {
		$arr_permohonan[$rp['idp']]=array("penerima"=>$rp['penerima'],"tujuan"=>$rp['tujuan']);
	}
}

//filter
$filter="WHERE trh.ref_idikan <> 0 ";

switch ($_POST['filter_waktu']) {
	case 'tahun':
		$filter.=" AND YEAR(tr.tgl_surat) BETWEEN '".$_POST['filter_tahun']."' AND '".$_POST['filter_tahun2']."' ";
		$filter_text.="<tr><td>Tahun </td><td>: ".$_POST['filter_tahun']." s.d ".$_POST['filter_tahun2']."</td></tr>";
	break;

	case 'bulan':
		$filter.=" AND DATE_FORMAT(tr.tgl_surat,'%Y-%m') BETWEEN '".$_POST['filter_bulan']."' AND '".$_POST['filter_bulan2']."' ";
		$filter_text.="<tr><td>Bulan </td><td>: ".tanggalIndo($_POST['filter_bulan'].'-01','F Y')." s.d ".tanggalIndo($_POST['filter_bulan2'].'-01','F Y')."</td></tr>";
	break;

	case 'hari':
		$filter.=" AND tr.tgl_surat BETWEEN '".$_POST['filter_hari']."' AND '".$_POST['filter_hari2']."' ";
		$filter_text.="<tr><td>Tanggal </td><td>: ".tanggalIndo($_POST['filter_hari'],'j F Y')." s.d ".tanggalIndo($_POST['filter_hari2'],'j F Y')."</td></tr>";
	break;
	
	default:
		$filter.="";
	break;
}

if($_POST['filter_pemohon']!='all'){
	$filter.=" AND tr.ref_iduser='".($_POST['filter_pemohon'])."' ";
	$sql->get_row('tb_userpublic',array('iduser'=>$_POST['filter_pemohon']),array('nama_lengkap'));
	$fp=$sql->result;
	$filter_text.="<tr><td>Pemohon </td><td>: ".$fp['nama_lengkap']."</td></tr>";
}

if($_POST['filter_jns_ikan']!='all'){
	$filter.=" AND trh.ref_idikan='".($_POST['filter_jns_ikan'])."' ";
	$sql->get_row('ref_data_ikan',array('id_ikan'=>$_POST['filter_jns_ikan']));
	$fji=$sql->result;
	$filter_text.="<tr><td>Jenis Ikan </td><td>: ".$fji['nama_latin']." (".$fji['nama_ikan'].")</td></tr>";
}

/*if($_POST['filter_jsn_produk']!='all'){
	$filter.=" AND trh.ref_jns='".($_POST['filter_jsn_produk'])."' ";
	$filter_text.="<tr><td>Jenis Produk </td><td>: ".$arr_jns_produk[$_POST['filter_jsn_produk']]."</td></tr>";
}*/

$q=$sql->run("
	SELECT trh.idtb,trh.ref_idikan,trh.ref_jns,SUM(trh.berat) berat,tr.idrek,tr.ref_idp,tr.ref_iduser,tr.no_surat,tr.tgl_surat FROM tb_rek_hsl_periksa trh 
	JOIN tb_rekomendasi tr ON(trh.ref_idrek=tr.idrek)
	JOIN ref_data_ikan rdi ON(rdi.id_ikan=trh.ref_idikan)
	$filter GROUP BY trh.ref_idikan,trh.ref_jns ORDER BY trh.ref_idikan ASC, trh.ref_jns ASC
	");

$jlh_produk=count($arr_produk);
// echo $sql->sql;


$arr_data_tabel=array();
if($q->rowCount()>0){
	foreach ($q->fetchAll() as $dt) {
		$arr_data_tabel[$dt['ref_idikan']]['nm_latin']=$arr_ikan[$dt['ref_idikan']]['nm_latin'];
		$arr_data_tabel[$dt['ref_idikan']]['id_ikan']=$dt['ref_idikan'];
		
		foreach ($arr_produk as $p) {
			if($p['id_jns']==$dt['ref_jns']){
				$arr_data_tabel[$dt['ref_idikan']]['produk'][$dt['ref_jns']]=$dt['berat'];
			}else{
				//$arr_data_tabel[$dt['ref_idikan']]['produk'][$dt['ref_jns']]=0;
			}
		}
	}
}

// echo '<pre>';
// print_r($arr_data_tabel);
// exit();
?>


<!DOCTYPE html>
<html>
<head>
	<title>STATISTIK PRODUK HIU & PARI</title>
	<link rel="stylesheet" href="<?php echo VENDOR;?>bootstrap/css/bootstrap.css">
	<style type="text/css">
		body{
			padding:10px;
		}
	</style>
</head>
<body>
	<center class="title"><h3>STATISTIK PRODUK HIU & PARI</h3></center>
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
	<table class="table table-bordered table-hover">
		<tr>
			<th width="2%" rowspan="2" style="vertical-align: middle;">No</th>
			<th rowspan="2" style="vertical-align: middle;">Ikan</th>
			<th colspan="<?php echo $jlh_produk;?>" class="text-center">Produk (Kg)</th>
			<th rowspan="2" style="vertical-align: middle;">Total Berat (Kg)</th>
		</tr>
		<tr>
			<?php
			foreach ($arr_produk as $p) {
				echo '<th class="text-center" width="'.(60/$jlh_produk).'%">'.$p['nm_produk'].'</th>';
			}
			?>
		</tr>
		<?php
			if(count($arr_data_tabel)>0){
				$no=1;
				$jlh_berat=0;
				foreach ($arr_data_tabel as $row) {
					echo '<tr>
						<td>'.$no.'</td>
						<td>'.$row['nm_latin'].'</td> ';
						$berat=0;
						foreach ($arr_produk as $p) {
							echo '<td class="text-right">'.$row['produk'][$p['id_jns']].'</td>';
							$berat=$berat+$row['produk'][$p['id_jns']];
						}
					echo '
						<td class="text-right">'.number_format($berat,2).'</td>
					</tr>';

					$no++;
					$jlh_berat=$jlh_berat+$berat;
				}
				echo '<tr><td colspan="'.(2+$jlh_produk).'" class="text-center"><strong>Jumlah Berat</strong></td><td class="text-right"><strong>'.number_format($jlh_berat,2).'</strong></td></tr>';
			}else{
				echo '<tr><td colspan="'.(3+$jlh_produk).'" class="text-center">Data Tidak Ada.</td></tr>';
			}
		?>
		
	</table>
</body>
</html>