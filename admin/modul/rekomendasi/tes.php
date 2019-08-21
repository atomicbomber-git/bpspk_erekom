<?php

exit();include ("../../engine/render.php");

//$sql->limit="1,100";
$sql->get_all('tb_permohonan',array(),array('idp','tgl_pengajuan'));
$r=$sql->result;

foreach($r as $dt){

    $jam = date('H:i',strtotime($dt['tgl_pengajuan']));
    $tgl = date('Y-m-d',strtotime($dt['tgl_pengajuan']));
    echo "tgl:".$tgl." jam: ".$jam."<br/>";
    if($jam>'16:00' AND $jam<='24:00'){
        //echo "mode1";
        $ver_admin=date('Y-m-d H:i:s',strtotime($tgl." 07:3".rand(1,5).":".rand(00,60)." +1 days"));
    }else if($jam>'00:00' AND $jam<='07:30'){
        //echo "mode2";
        $ver_admin=date('Y-m-d H:i:s',strtotime($tgl." 07:3".rand(1,5).":".rand(00,60)));
    }else{
        //echo "mode3";
        $ver_admin=date('Y-m-d H:i:s',strtotime($dt['tgl_pengajuan']."+5 minutes"));
    }
    //echo "<br/>".$ver_admin;
    
    //$query="UPDATE tb_log_tahapan SET tanggal='".$ver_admin."' WHERE tahapan='1' AND ref_idp='".$dt['idp']."' ";
    //echo "<br/>";
    //$sql->run($query);
}


?>