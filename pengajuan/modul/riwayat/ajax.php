<?php
if ($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest') {
    die();
}

if ($_POST) {
    include("../../engine/render.php");
    switch (trim(strip_tags($_POST['a']))) {
        case 'lp': //list pengajuan
            $aColumns = array('p.penerima','p.tujuan');

            //table
            $Table = "tb_permohonan p ";
            $Table .= "JOIN tb_userpublic c ON(p.ref_iduser=c.iduser)";

            //limit
            $Limit = "";
            if (isset($_POST['start']) && $_POST['length'] != -1) {
                $Limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length']);
            }
            //order
            $Orders = " ORDER BY p.tgl_pengajuan DESC ";

            $Where=" WHERE p.ref_iduser='".U_ID."' ";
            //cari
            $sSearch = "";
            if (isset($_POST['cari']) && $_POST['cari']!= '') {
                $str = $_POST['cari'];

                $sSearch = "AND (";
                for ($i=0, $ien=count($aColumns) ; $i<$ien ; $i++) {
                    $sSearch .= "".$aColumns[$i]." LIKE '%".$str."%' OR ";
                }
                $sSearch = substr_replace($sSearch, "", -3);
                $sSearch .= ')';
            }

            $sCustomFilter="";

            $q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ");
            $rtot=$q->fetch();
            $total=$rtot['total'];

            $q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ".$Where.$sSearch.$sCustomFilter);
            $dbfiltot=$q->fetch();
            $filtertotal=$dbfiltot['total'];

            //data
            $q=$sql->run("SELECT c.nama_lengkap,p.tujuan,p.penerima,p.tgl_pengajuan,p.idp,p.status,p.tgl_pelayanan,p.no_antrian FROM $Table ".$Where.$sSearch.$sCustomFilter.$Orders.$Limit);
                
            $output = array(
                "draw" => intval($_POST['draw']),
                "recordsTotal" => intval($total),
                "recordsFiltered" => intval($filtertotal),
                "data" => array()
            );

            $no=1+$_POST['start'];
            $arr_status=array(
                1=>"Pemeriksaan Data.",
                2=>"Data Diterima, Pengajuan Sedang Diproses Oleh Admin.",
                3=>"Data Ditolak, Berkas/Data Tidak Lengkap.",
                4=>"Pemeriksaan Sampel Telah Dilakukan.",
                5=>"Surat Rekomendasi Sudah Diterbitkan."
            );

            foreach ($q->fetchAll() as $data) {
                $aksi="<a class='btn btn-sm btn-primary' href='./modul/riwayat/detail.php?permohonan=".base64_encode($data['idp'])."'>Detail Data</a>";
                
                $users=array(
                    $no,
                    $data['penerima']."<br/>".$data['tujuan'],
                    tanggalIndo($data['tgl_pengajuan'], 'j F Y H:i'),
                    format_noantrian($data['tgl_pelayanan'], $data['no_antrian']),
                    $arr_status[$data['status']],
                    $aksi
                );
                $output['data'][] = $users;
                $no++;
            }
            echo json_encode($output);
        break;

        default:
            echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
        break;
    }
}
