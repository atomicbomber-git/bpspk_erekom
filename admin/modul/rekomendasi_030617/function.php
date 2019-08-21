<?php
function pilihan($name='',$opt=array(),$selected='',$extra=''){
    $form='<select name="'.$name.'" '.$extra.' >';
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

?>