<?php
function NAV_LEVEL($current=""){
    global $sql;
    $count_admin = $sql -> get_count('op_user', array("lvl"=>100));
    $count_kepala = $sql -> get_count('op_user', array("lvl"=>90));
    $count_plh = $sql -> get_count('op_user', array("lvl"=>91));
    $count_verifikator = $sql -> get_count('op_user', array("lvl"=>95));

    $count_all = $count_admin + $count_kepala + $count_plh + $count_verifikator;
    $nav_aktif = " class='text-bold'";
    $on = "
    <p><a href=\"#all\"".$nav_aktif." id=\"lvl_all\" onClick=\"filterlvl('all')\">Semua</a> (".$count_all.") | 
    <a href=\"#\" id=\"lvl_admin\" onClick=\"filterlvl('100')\">Admin</a> (".$count_admin.") | 
    <a href=\"#\" id=\"lvl_kepala\"onClick=\"filterlvl('90')\">Kepala Loka</a> (".$count_kepala.") |
    <a href=\"#\" id=\"lvl_plh\"onClick=\"filterlvl('91')\">Plh Kepala Loka</a> (".$count_plh.") | 
    <a href=\"#\" id=\"lvl_vr\"onClick=\"filterlvl('95')\">Verifikator</a> (".$count_verifikator.")";
    $on.="</p>";
    return $on;
}

/**
META USER Insert
*/
function REG_META_USER($UID){
    $sql=new dbPDO;
    $meta=array(
        "U_VERIFY"=>"1",
        "U_NOWLOG"=>"",
        "U_TOTAL_LOGIN"=>"0",
        "U_PWCHANGE"=>"",
        "U_REGISTERED"=>time(),
        "U_LASTLOG"=>"",
        "U_IP"=>"",
        "U_OPTIONS"=>""
    );

    $option_autoload=array("U_VERIFY","U_NOWLOG","U_LASTLOG","U_IP");
    foreach($meta as $key => $value){
        if(in_array($key,  $option_autoload)){
            $option="autoload";
        }else{
            $option="";
        }
         $data=array(
            "ref_id"=>$UID,
            "meta_key"=>$key,
            "meta_value"=>$value,
            "meta_option"=>$option,
            "meta_group"=>"2"
        );
        $sql->insert('web_meta',$data);
    }
}

function pilihan($name='',$opt=array(),$selected='',$extra=''){
    $form='<select name="'.$name.'" id="'.$name.'" '.$extra.' >';
    foreach ($opt as $key => $val) {
        if($selected!="" AND $selected==$key){
            $form.='<option selected value="'.$key.'">'.$val.'</option>';
        }else{
            $form.='<option value="'.$key.'">'.$val.'</option>';
        }
    }
    $form.='</select>';
    return $form;
}
