<?php
include ("../../engine/render.php");
define ("VENDOR", c_STATIC."assets/vendor/");

if($_POST['excel']=='yes'){
	header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
	header("Content-Disposition: inline; filename=\"lap-statistik-".date('d-m-Y').".xls\"");
	header("Pragma: no-cache");
	header("Expires: 0");
}

//data produk
$sql->get_all('ref_jns_sampel');
$arr_jns_produk=array();
if($sql->num_rows>0){
	foreach ($sql->result as $jns) {
		$arr_jns_produk[$jns['id_ref']]=$jns['jenis_sampel'];
	}
}

//data satker
$sql->get_all('ref_satuan_kerja');
$arr_satker=array();
if($sql->num_rows>0){
	foreach ($sql->result as $st) {
		$arr_satker[$st['id_satker']]=$st['nm_satker'];
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
	if($_POST['filter_jns_ikan']=='all_hiu'){
		$filter.=" AND trh.ref_idikan IN (SELECT id_ikan FROM ref_data_ikan WHERE ref_idkel='2') ";
		$filter2.=" AND rdi.ref_idkel='2' ";
		$filter_text.="<tr><td>Jenis Ikan </td><td>: Semua Jenis Hiu</td></tr>";
	}else if($_POST['filter_jns_ikan']=='all_pari'){
		$filter.=" AND trh.ref_idikan IN (SELECT id_ikan FROM ref_data_ikan WHERE ref_idkel='1') ";
		$filter2.=" AND rdi.ref_idkel='1' ";
		$filter_text.="<tr><td>Jenis Ikan </td><td>: Semua Jenis Pari</td></tr>";
	}else{
		$filter.=" AND trh.ref_idikan='".($_POST['filter_jns_ikan'])."' ";
		$filter2.=" AND trh.ref_idikan='".($_POST['filter_jns_ikan'])."' ";
		$sql->get_row('ref_data_ikan',array('id_ikan'=>$_POST['filter_jns_ikan']));
		$fji=$sql->result;
		$filter_text.="<tr><td>Jenis Ikan </td><td>: ".$fji['nama_latin']." (".$fji['nama_ikan'].")</td></tr>";
	}
}

if($_POST['filter_jsn_produk']!='all'){
	$filter.=" AND trh.ref_jns='".($_POST['filter_jsn_produk'])."' ";
	$filter2.=" AND trh.ref_jns='".($_POST['filter_jsn_produk'])."' ";
	$filter_text.="<tr><td>Jenis Produk </td><td>: ".$arr_jns_produk[$_POST['filter_jsn_produk']]."</td></tr>";
}

if($_POST['filter_satker']!='all'){
	$filter.=" AND tr.ref_satker='".($_POST['filter_satker'])."' ";
	$filter_text.="<tr><td>Satuan kerja </td><td>: ".$arr_satker[$_POST['filter_satker']]."</td></tr>";
}

if($_POST['filter_kel_ikan']!='all'){
	$filter2 .="AND rdi.ref_idkel='".$_POST['filter_kel_ikan']."' ";
	$array_kel=array(
		"1"=>"Ikan Pari",
		"2"=>"Ikan Hiu");
	$filter_text.="<tr><td>Kelompok Jenis Ikan</td><td>: ".$array_kel[$_POST['filter_kel_ikan']]."</td></tr>";
}

//data ikan dari rekomendasi
$arr_hasil=array();
$h=$sql->run("SELECT trh.*,rdi.nama_ikan,rdi.nama_latin FROM tb_rek_hsl_periksa trh JOIN ref_data_ikan rdi ON(rdi.id_ikan=trh.ref_idikan) WHERE trh.ref_idikan<>0 $filter2");
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

$q=$sql->run("
	SELECT tr.idrek,tr.ref_idp,tr.no_surat,tr.tgl_surat,tu.nama_lengkap,trh.ref_idikan,trh.ref_jns FROM tb_rekomendasi tr 
	JOIN tb_userpublic tu ON (tu.iduser=tr.ref_iduser)
	JOIN tb_rek_hsl_periksa trh ON(trh.ref_idrek=tr.idrek) 
	$filter GROUP BY tr.idrek ORDER BY tr.tgl_surat DESC, TRIM(tr.no_surat) DESC
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
		table #dtlap
		{	
			width:100%;
			font-family : arial;
			font-size: 12px;
			border-collapse: collapse;
		}
		th,td
		{
			padding:5px;
		}
	</style>
</head>
<body>
	<center class="title"><h3>STATISTIK REKOMENDASI HIU & PARI</h3></center>
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
			<th width="2%" rowspan="2">No</th>
			<th rowspan="2">Nama</th>
			<th rowspan="2">No. Rekomendasi</th>
			<th rowspan="2">Tgl. Rekomendasi</th>
			<th width="15%" rowspan="2">Tujuan Pengiriman</th>
			<th colspan="3" class="text-center">Produk</th>
			<th rowspan="2">Total <br>Berat (Kg)</th>
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
					$jlhrowspan=count($arr_hasil[$dt['idrek']]);
					
					$pr_jns="";
					$pr_ikan="";
					$pr_berat="";
					$otherrow="";
					$xx=0;
					$tot_berat=0;
					foreach ($arr_hasil[$dt['idrek']] as $key) {
						$dtikan=$arr_hasil[$dt['idrek']][$xx];
						if($xx==0){
							if($_POST['filter_jsn_produk']!='all' AND $_POST['filter_jns_ikan']!='all'){
								if($dtikan['jns_produk']==$_POST['filter_jsn_produk'] AND $dtikan['id_ikan']==$_POST['filter_jns_ikan']){
									$pr_jns.='<td>'.$arr_jns_produk[$dtikan['jns_produk']].'</td>';
									$pr_ikan.='<td><em>'.$dtikan['nm_latin'].'</em></td>';
									$pr_berat.='<td style="text-align:right">'.$dtikan['berat'].'</td>';
									$tot_berat=$tot_berat+$dtikan['berat'];
								}
							}else if($_POST['filter_jsn_produk']!='all'){
								if($dtikan['jns_produk']==$_POST['filter_jsn_produk']){
									$pr_jns.='<td>'.$arr_jns_produk[$dtikan['jns_produk']].'</td>';
									$pr_ikan.='<td><em>'.$dtikan['nm_latin'].'</em></td>';
									$pr_berat.='<td style="text-align:right">'.$dtikan['berat'].'</td>';
									$tot_berat=$tot_berat+$dtikan['berat'];
								}
							}else if($_POST['filter_jns_ikan']!='all'){
								if($dtikan['id_ikan']==$_POST['filter_jns_ikan']){
									$pr_jns.='<td>'.$arr_jns_produk[$dtikan['jns_produk']].'</td>';
									$pr_ikan.='<td><em>'.$dtikan['nm_latin'].'</em></td>';
									$pr_berat.='<td style="text-align:right">'.$dtikan['berat'].'</td>';
									$tot_berat=$tot_berat+$dtikan['berat'];
								}
							}else{
								$pr_jns.='<td>'.$arr_jns_produk[$dtikan['jns_produk']].'</td>';
								$pr_ikan.='<td><em>'.$dtikan['nm_latin'].'</em></td>';
								$pr_berat.='<td style="text-align:right">'.$dtikan['berat'].'</td>';
								$tot_berat=$tot_berat+$dtikan['berat'];
							}
						}else{
							$otherrow.="<tr>";
							if($_POST['filter_jsn_produk']!='all' AND $_POST['filter_jns_ikan']!='all'){
								if($dtikan['jns_produk']==$_POST['filter_jsn_produk'] AND $dtikan['id_ikan']==$_POST['filter_jns_ikan']){
									$otherrow.='<td>'.$arr_jns_produk[$dtikan['jns_produk']].'</td>';
									$otherrow.='<td><em>'.$dtikan['nm_latin'].'</em></td>';
									$otherrow.='<td style="text-align:right">'.$dtikan['berat'].'</td>';
									$tot_berat=$tot_berat+$dtikan['berat'];
								}
							}else if($_POST['filter_jsn_produk']!='all'){
								if($dtikan['jns_produk']==$_POST['filter_jsn_produk']){
									$otherrow.='<td>'.$arr_jns_produk[$dtikan['jns_produk']].'</td>';
									$otherrow.='<td><em>'.$dtikan['nm_latin'].'</em></td>';
									$otherrow.='<td style="text-align:right">'.$dtikan['berat'].'</td>';
									$tot_berat=$tot_berat+$dtikan['berat'];
								}
							}else if($_POST['filter_jns_ikan']!='all'){
								if($dtikan['id_ikan']==$_POST['filter_jns_ikan']){
									$otherrow.='<td>'.$arr_jns_produk[$dtikan['jns_produk']].'</td>';
									$otherrow.='<td><em>'.$dtikan['nm_latin'].'</em></td>';
									$otherrow.='<td style="text-align:right">'.$dtikan['berat'].'</td>';
									$tot_berat=$tot_berat+$dtikan['berat'];
								}
							}else{
								$otherrow.='<td>'.$arr_jns_produk[$dtikan['jns_produk']].'</td>';
								$otherrow.='<td><em>'.$dtikan['nm_latin'].'</em></td>';
								$otherrow.='<td style="text-align:right">'.$dtikan['berat'].'</td>';
								$tot_berat=$tot_berat+$dtikan['berat'];
							}
							$otherrow.="</tr>";
						}
						$xx++;
					}
					$rowspan=(($jlhrowspan>1)?'rowspan="'.$jlhrowspan.'" ':'');
					if($tot_berat!=0){
						echo '<tr>
							<td '.$rowspan.'>'.$no.'</td>
							<td '.$rowspan.'>'.ucwords($dt['nama_lengkap']).'</td>
							<td '.$rowspan.'>'.$dt['no_surat'].'</td>
							<td '.$rowspan.'>'.tanggalIndo($dt['tgl_surat'],'j F Y').'</td>
							<td '.$rowspan.'>'.ucwords($arr_permohonan[$dt['ref_idp']]['tujuan']).'</td>
							'.$pr_jns.'
							'.$pr_ikan.'
							'.$pr_berat.'
							<td '.$rowspan.' style="text-align:right">'.number_format($tot_berat,2).'</td>
						</tr>';
						echo $otherrow;

						$jlh_berat=$jlh_berat+$tot_berat;
						$no++;
					}
				}
				echo '<tr><td colspan="8" class="text-center"><strong>Jumlah Berat</strong></td><td class="text-right"><strong>'.number_format($jlh_berat,2).' </strong></td></tr>';
			}else{
				echo '<tr><td colspan="9" class="text-center">Data Tidak Ada.</td></tr>';
			}
		?>
		
	</table>
</body>
</html>