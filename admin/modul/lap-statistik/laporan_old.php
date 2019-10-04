<?php
include ("../../engine/render.php");
define ("VENDOR", c_STATIC."assets/vendor/");

//data produk
$sql->get_all('ref_jns_sampel');
$arr_jns_produk=array();
if($sql->num_rows>0){
	foreach ($sql->result as $jns) {
		$arr_jns_produk[$jns['id_ref']]=$jns['jenis_sampel'];
	}
}
//data ikan dari rekomendasi
$arr_hasil=array();
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
}

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
$filter="WHERE 1 ";

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

if($_POST['filter_jsn_produk']!='all'){
	$filter.=" AND trh.ref_jns='".($_POST['filter_jsn_produk'])."' ";
	$filter_text.="<tr><td>Jenis Produk </td><td>: ".$arr_jns_produk[$_POST['filter_jsn_produk']]."</td></tr>";
}

$q=$sql->run("
	SELECT tr.idrek,tr.ref_idp,tr.no_surat,tr.tgl_surat,tu.nama_lengkap,trh.ref_idikan,trh.ref_jns FROM tb_rekomendasi tr 
	JOIN tb_userpublic tu ON (tu.iduser=tr.ref_iduser)
	JOIN tb_rek_hsl_periksa trh ON(trh.ref_idrek=tr.idrek) 
	$filter GROUP BY tr.idrek ORDER BY tr.tgl_surat DESC
	");

?>

<!DOCTYPE html>
<html>
<head>
	<title>STATISTIK REKOMENDASI HIU & PARI</title>
	<link rel="stylesheet" href="<?php echo VENDOR;?>bootstrap/css/bootstrap.css">
	<style type="text/css">
		body{
			padding:10px;
		}
	</style>
</head>
<body>
	<center class="title"><h3>STATISTIK REKOMENDASI HIU & PARI</h3></center>
	<center class="title"><h4>LPSPL SERANG</h4></center>
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
			<th width="2%" rowspan="2">No</th>
			<th rowspan="2">Nama</th>
			<th rowspan="2">No. Rekomendasi</th>
			<th rowspan="2">Tgl. Rekomendasi</th>
			<th width="15%" rowspan="2">Tujuan Pengiriman</th>
			<th colspan="3" class="text-center">Produk</th>
			<th rowspan="2">Total Berat</th>
		</tr>
		<tr>
			<th class="text-center">Jenis</th>
			<th class="text-center">Ikan</th>
			<th class="text-center">Berat (Kg)</th>
		</tr>
		<?php
			if($q->rowCount()>0){
				$no=1;
				$pr_jns="";
				$pr_ikan="";
				$pr_berat="";
				
				$jlh_berat=0;
				foreach ($q->fetchAll() as $dt) {

					$pr_jns="<ul>";
					$pr_ikan="<ul>";
					$pr_berat="<ul style='list-style-type: none;'>";
					$xx=0;
					$tot_berat=0;
					foreach ($arr_hasil[$dt['idrek']] as $key) {
						$dtikan=$arr_hasil[$dt['idrek']][$xx];

						if($_POST['filter_jsn_produk']!='all' AND $_POST['filter_jns_ikan']!='all'){
							if($dtikan['jns_produk']==$_POST['filter_jsn_produk'] AND $dtikan['id_ikan']==$_POST['filter_jns_ikan']){
								$pr_jns.='<li>'.$arr_jns_produk[$dtikan['jns_produk']].'</li>';
								$pr_ikan.='<li>'.$dtikan['nm_latin'].'</li>';
								$pr_berat.='<li>'.$dtikan['berat'].'</li>';
								$tot_berat=$tot_berat+$dtikan['berat'];
							}
						}else if($_POST['filter_jsn_produk']!='all'){
							if($dtikan['jns_produk']==$_POST['filter_jsn_produk']){
								$pr_jns.='<li>'.$arr_jns_produk[$dtikan['jns_produk']].'</li>';
								$pr_ikan.='<li>'.$dtikan['nm_latin'].'</li>';
								$pr_berat.='<li>'.$dtikan['berat'].'</li>';
								$tot_berat=$tot_berat+$dtikan['berat'];
							}
						}else if($_POST['filter_jns_ikan']!='all'){
							if($dtikan['id_ikan']==$_POST['filter_jns_ikan']){
								$pr_jns.='<li>'.$arr_jns_produk[$dtikan['jns_produk']].'</li>';
								$pr_ikan.='<li>'.$dtikan['nm_latin'].'</li>';
								$pr_berat.='<li>'.$dtikan['berat'].'</li>';
								$tot_berat=$tot_berat+$dtikan['berat'];
							}
						}else{
							$pr_jns.='<li>'.$arr_jns_produk[$dtikan['jns_produk']].'</li>';
							$pr_ikan.='<li>'.$dtikan['nm_latin'].'</li>';
							$pr_berat.='<li>'.$dtikan['berat'].'</li>';
							$tot_berat=$tot_berat+$dtikan['berat'];
						}
						$xx++;
						
					}
					$pr_jns.="</ul>";
					$pr_ikan.="</ul>";
					$pr_berat.="</ul>";

					echo '<tr>
						<td>'.$no.'</td>
						<td>'.$dt['nama_lengkap'].'</td>
						<td>'.$dt['no_surat'].'</td>
						<td>'.tanggalIndo($dt['tgl_surat'],'j F Y').'</td>
						<td>'.$arr_permohonan[$dt['ref_idp']]['tujuan'].'</td>
						<td>'.$pr_jns.'</td>
						<td>'.$pr_ikan.'</td>
						<td class="text-right">'.$pr_berat.'</td>
						<td>'.number_format($tot_berat,2).' Kg</td>
					</tr>';

					$jlh_berat=$jlh_berat+$tot_berat;
					$no++;
				}
				echo '<tr><td colspan="8" class="text-center"><strong>Jumlah Berat</strong></td><td class="text-right"><strong>'.number_format($jlh_berat,2).' </strong></td></tr>';
			}else{
				echo '<tr><td colspan="9" class="text-center">Data Tidak Ada.</td></tr>';
			}
		?>
		
	</table>
</body>
</html>