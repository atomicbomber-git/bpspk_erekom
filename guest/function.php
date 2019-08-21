<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since 5.0
* @category     themes_functions
*/

function ITEM_HEAD ($ITEM_HEAD) {
    $pecah = explode (",", $ITEM_HEAD);

    $base = array (
        "bootstrap.css"             => "\n<link rel=\"stylesheet\" href=\"".PLUGINS."bootstrap/css/bootstrap.css\" />", 
        "font-awesome.css"          => "\n<link rel=\"stylesheet\" href=\"".PLUGINS."font-awesome-4.7.0/css/font-awesome.min.css\" />",
        "bootstrap-datepicker.css"  => "\n<link rel=\"stylesheet\" href=\"".PLUGINS."datepicker/datepicker3.css\" />",
        "select2.css"               => "\n<link rel=\"stylesheet\" href=\"".PLUGINS."select2/select2.css\" />",
        "datatables.css"            => "\n<link rel=\"stylesheet\" href=\"".PLUGINS."datatables/dataTables.bootstrap.css\" />",
        "pnotify.css"               => "\n<link rel=\"stylesheet\" href=\"".PLUGINS."pnotify/pnotify.custom.min.css\" />",
        "theme.css"                 => "\n<link rel=\"stylesheet\" href=\"".CSS."AdminLTE.css\" />
                                        \n<link rel=\"stylesheet\" href=\"".CSS."skins/skin-blue.css\" />
                                        \n<link rel=\"stylesheet\" href=\"".CSS."custom.css\" />",
        "fileinput.min.css"         => "\n<link rel=\"stylesheet\" href=\"".PLUGINS."file-input/fileinput.min.css\" />",
        "magnificpopup.css"         => "\n<link rel=\"stylesheet\" href=\"".PLUGINS."MagnificPopup/magnific-popup.css\" />",
        "bm-datepicker.css"         => "\n<link rel=\"stylesheet\" href=\"".PLUGINS."bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css\" />"
    );
    foreach($pecah as $k){
        echo $base[trim($k)];
    }
}

function SCRIPT_HEAD($SCRIPT_HEAD=""){
    if (!empty($SCRIPT_HEAD)) {
        echo $SCRIPT_HEAD;
    }else return true;
}

function ITEM_FOOT ($ITEM_FOOT) {
    global $ModuleDir;
    $FOOT_EX = explode (",", $ITEM_FOOT);

    $FOOT_base = array (
        //PLUGINS
        "jquery.js"                 => "\n<script src=\"".PLUGINS."jquery/jquery-2.2.3.min.js\"></script>",
        "bootstrap.js"              => "\n<script src=\"".PLUGINS."bootstrap/js/bootstrap.js\"></script>",
        "bootstrap-datepicker.js"   => "\n<script src=\"".PLUGINS."datepicker/bootstrap-datepicker.js\"></script>",
        "bm-datepicker.js"          => "\n<script src=\"".PLUGINS."momentjs/moment.js\"></script>
                                        \n<script src=\"".PLUGINS."bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js\"></script>",
        "jquery-ui.min.js"          => "\n<script src=\"".PLUGINS."jqueryui/jquery-ui.min.js\"></script>",
        "pnotify.js"                => "\n<script src=\"".PLUGINS."pnotify/pnotify.custom.min.js\"></script>",
        "select2.js"                => "\n<script src=\"".PLUGINS."select2/select2.js\"></script>",
        "datatables.js"             => "\n<script src=\"".PLUGINS."datatables/jquery.dataTables.min.js\"></script>
                                        \n<script src=\"".PLUGINS."datatables/dataTables.bootstrap.min.js\"></script>",
        "bootstrap-timepicker.js"   => "\n<script src=\"".PLUGINS."timepicker/bootstrap-timepicker.js\"></script>",
        "jquery.validate.js"        => "\n<script src=\"".PLUGINS."jquery-validation/jquery.validate.js\"></script>",
        "jquery.validate.msg.id.js"             => "\n<script src=\"".PLUGINS."jquery-validation/messages_id.js\"></script>",
        "jquery.valdate.additional-method.js"   => "\n<script src=\"".PLUGINS."jquery-validation/additional-method.js\"></script>",

        "ckeditor.js"               => "\n<script src=\"".PLUGINS."ckeditor/ckeditor.js\"></script>\n<script src=\"".PLUGINS."ckeditor/adapters/jquery.js\"></script>",
        //base
        "theme.js"                  => "\n<script src=\"".JS."app.js\"></script>",
        "theme.custom.js"           => "\n<script src=\"".JS."theme.custom.js\"></script>",
        "theme.init.js"             => "\n<script src=\"".JS."theme.init.js\"></script>",
        "media.js"                  => "\n<script src=\"".JS."pages/examples.mediagallery.js\"></script>",
        "fileinput.min.js"          => "\n<script src=\"".PLUGINS."file-input/fileinput.min.js\"></script>",
        "magnificpopup.js"          => "\n<script src=\"".PLUGINS."MagnificPopup/MagnificPopup.js\"></script>",

                );
    foreach($FOOT_EX as $FOOT){
        echo $FOOT_base[trim($FOOT)];
    }
}

function SCRIPT_FOOT ($SCRIPT_FOOT = "") {
    if (!empty($SCRIPT_FOOT)) {
        echo $SCRIPT_FOOT;
    }else return true;
}

function is_available_id_taxonomy ( $id ) {
    $sql = new db;
    $sql -> db_Select("apotik_taxonomy", "taxonomy_id", "taxonomy_id='{$id}'");
    if ($sql->db_Rows()) {
        return TRUE;
    }
    else { return FALSE;}
}

function create_flag_text ( $text ) {
    $rewrite_text = preg_replace('/\%/',' percentage',$text); 
    $rewrite_text = preg_replace('/\@/',' at ',$rewrite_text); 
    $rewrite_text = preg_replace('/\&/',' and ',$rewrite_text); 
    $rewrite_text = preg_replace('/\s[\s]+/','-',$rewrite_text); // Strip off multiple spaces 
    $rewrite_text = preg_replace('/[\s\W]+/','-',$rewrite_text); // Strip off spaces and non-alpha-numeric 
    $rewrite_text = preg_replace('/^[\-]+/','',$rewrite_text); // Strip off the starting hyphens 
    $rewrite_text = preg_replace('/[\-]+$/','',$rewrite_text); // // Strip off the ending hyphens 
    $rewrite_text = strtolower($rewrite_text);
    return $rewrite_text;
}

?>