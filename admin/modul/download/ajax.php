<?php
if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest'){
	die();
}
include ("../../engine/render.php");
if($_POST){
	switch (trim(strip_tags($_POST['a']))) {
		case 'list-rek':
			//$aColumns = array('p.penerima','p.tujuan','c.nama_lengkap');

			//table
			$Table = "tb_rekomendasi tr ";
			$Table .= "JOIN tb_userpublic c ON(tr.ref_iduser=c.iduser)";

			//limit
			$Limit = "";
			if ( isset($_POST['start']) && $_POST['length'] != -1 ) {
			    $Limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length']);
			}
			//order
			$Orders = " ORDER BY tr.tgl_surat DESC ";

			$Where=" WHERE 1 ";
			//cari
			$sSearch = "";
			/*if ( isset($_POST['cari']) && $_POST['cari']!= '' ) {
			    $str = $_POST['cari'];

			    $sSearch = "AND (";
			    for ( $i=0, $ien=count($aColumns) ; $i<$ien ; $i++ ) {
			        $sSearch .= "".$aColumns[$i]." LIKE '%".$str."%' OR ";
			    }
			    $sSearch = substr_replace( $sSearch, "", -3 );
			    $sSearch .= ')';
			}*/

			$sCustomFilter="";

			if($_POST['filter_no_surat']!=""){
				$sCustomFilter.=" AND tr.no_surat LIKE '".$_POST['filter_no_surat']."%'";
			}

			if($_POST['filter_bulan']!="" AND $_POST['filter_tahun']!=''){
				$sCustomFilter.=" AND DATE_FORMAT(tr.tgl_surat,'%Y-%m') ='".$_POST['filter_tahun']."-".$_POST['filter_bulan']."' ";
			}

			if($_POST['filter_tahun']!=""){
				$sCustomFilter.=" AND DATE_FORMAT(tr.tgl_surat,'%Y') ='".$_POST['filter_tahun']."' ";
			}			

			$q=$sql->run("SELECT COUNT(tr.idrek) as total FROM $Table ".$Where);
			$rtot=$q->fetch();
			$total=$rtot['total'];

			$q=$sql->run("SELECT COUNT(tr.idrek) as total FROM $Table ".$Where.$sSearch.$sCustomFilter);
			$dbfiltot=$q->fetch();
			$filtertotal=$dbfiltot['total'];

			//data
			$q=$sql->run("SELECT c.nama_lengkap,tr.no_surat,tr.tgl_surat FROM $Table ".$Where.$sSearch.$sCustomFilter.$Orders.$Limit);
			    
			$output = array(
			    "draw" => intval($_POST['draw']),
			    "recordsTotal" => intval($total),
			    "recordsFiltered" => intval($filtertotal),
			    "data" => array()
			);

			$no=1+$_POST['start'];
			foreach($q->fetchAll() as $data){ 
				$tombol="<a class='btn btn-sm btn-primary' href='download.php?t=norek&no=".base64_encode($data['no_surat'])."' target='_blank'>Download</a>";

				$datalist=array(
					$no,
					$data['nama_lengkap'].$stat,
					$data['no_surat'],
					tanggalIndo($data['tgl_surat'],'j F Y'),
					$tombol
				);
				$output['data'][] = $datalist;
				$no++;
			}
			echo json_encode($output);
		break;

		default:
			echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
			exit();
		break;
	}
}