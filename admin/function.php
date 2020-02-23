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
        
        "isotope.css"               => "\n<link rel=\"stylesheet\" href=\"".VENDOR."isotope/jquery.isotope.css\">",

        "bootstrap.css"             => "\n<link rel=\"stylesheet\" href=\"".VENDOR."bootstrap/css/bootstrap.css\" />", 
        "font-awesome.css"          => "\n<link rel=\"stylesheet\" href=\"".VENDOR."font-awesome/css/font-awesome.css\" />",
        "magnific-popup.css"        => "\n<link rel=\"stylesheet\" href=\"".VENDOR."magnific-popup/magnific-popup.css\" />",
        "datepicker3.css"           => "\n<link rel=\"stylesheet\" href=\"".VENDOR."bootstrap-datepicker/css/datepicker3.css\" />",

        "jquery-ui.min.css"         => "\n<link rel=\"stylesheet\" href=\"".VENDOR."jquery-ui/css/ui-lightness/jquery-ui.min.css\" />",
        "pnotify.custom.css"        => "\n<link rel=\"stylesheet\" href=\"".VENDOR."pnotify/pnotify.custom.css\" />",
        "morris.css"                => "\n<link rel=\"stylesheet\" href=\"".VENDOR."morris/morris.css\" />",
        "select2.css"               => "\n<link rel=\"stylesheet\" href=\"".VENDOR."select2/select2.css\" />",
        "datatables.css"            => "\n<link rel=\"stylesheet\" href=\"".VENDOR."jquery-datatables-bs3/assets/css/datatables.css\" />",
        "bootstrap-multiselect.css" => "\n<link rel=\"stylesheet\" href=\"".VENDOR."bootstrap-multiselect/bootstrap-multiselect.css\" />",
        //"bootstrap-timepicker.css"=> "\n<link rel=\"stylesheet\" href=\"".VENDOR."bootstrap-timepicker/css/bootstrap-timepicker.css\" />",
        "summernote.css"            => "\n<link rel=\"stylesheet\" href=\"".VENDOR."summernote/summernote.css\" />",
        "summernote-bs3.css"        => "\n<link rel=\"stylesheet\" href=\"".VENDOR."summernote/summernote-bs3.css\" />",
        "codemirror.css"            => "\n<link rel=\"stylesheet\" href=\"".VENDOR."codemirror/lib/codemirror.css\" />",
        "monokai.css"               => "\n<link rel=\"stylesheet\" href=\"".VENDOR."codemirror/theme/monokai.css\" />",
        "bootstrap-tagsinput.css"   => "\n<link rel=\"stylesheet\" href=\"".VENDOR."bootstrap-tagsinput/bootstrap-tagsinput.css\" />",
        "bootstrap-timepicker.css"  => "\n<link rel=\"stylesheet\" href=\"".VENDOR."bootstrap-timepicker/css/bootstrap-timepicker.css\" />",
        //"dropzone_basic.css"      => "\n<link rel=\"stylesheet\" href=\"".VENDOR."dropzone/css/basic.css\" />",
        //"dropzone.css"            => "\n<link rel=\"stylesheet\" href=\"".VENDOR."dropzone/css/dropzone.css\" />",

        "cropper.css"                 => "\n<link rel=\"stylesheet\" href=\"".VENDOR."cropperjs/cropper.css\" />",
        "theme.css"                 => "\n<link rel=\"stylesheet\" href=\"".CSS."theme.css\" />",
        "default.css"               => "\n<link rel=\"stylesheet\" href=\"".CSS."skins/default.css\" />",
        "theme-custom.css"          => "\n<link rel=\"stylesheet\" href=\"".CSS."theme-custom.css\" />",                    
        //JS
        "modernizr.js"              => "\n<script src=\"".VENDOR."modernizr/modernizr.js\"></script>",
        

        "fileinput.min.css"         => "\n<link rel=\"stylesheet\" href=\"".VENDOR."file-input/fileinput.min.css\" />"

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
        //VENDOR
        "vue.js"                    => "\n<script src=\"".VENDOR."vue.js\"></script>",
        "cleave.js"                 => "\n<script src=\"".VENDOR."cleave.min.js\"></script>",
        "isotope.js"                => "\n<script src=\"".VENDOR."isotope/jquery.isotope.js\"></script>",

        "jquery.js"                 => "\n<script src=\"".VENDOR."jquery/jquery.js\"></script>",
        "jquery.browser.mobile.js"  => "\n<script src=\"".VENDOR."jquery-browser-mobile/jquery.browser.mobile.js\"></script>",
        "bootstrap.js"              => "\n<script src=\"".VENDOR."bootstrap/js/bootstrap.js\"></script>",
        "nanoscroller.js"           => "\n<script src=\"".VENDOR."nanoscroller/nanoscroller.js\"></script>",
        "bootstrap-datepicker.js"   => "\n<script src=\"".VENDOR."bootstrap-datepicker/js/bootstrap-datepicker.js\"></script>",
        "magnific-popup.js"         => "\n<script src=\"".VENDOR."magnific-popup/magnific-popup.js\"></script>",
        "jquery.placeholder.js"     => "\n<script src=\"".VENDOR."jquery-placeholder/jquery.placeholder.js\"></script>",

        //Tambahan
        
        "jquery-ui.min.js"          => "\n<script src=\"".VENDOR."jquery-ui/js/jquery-ui.min.js\"></script>",
        "jquery.easypiechart.js"    => "\n<script src=\"".VENDOR."jquery-easypiechart/jquery.easypiechart.js\"></script>",
        "pnotify.custom.js"         => "\n<script src=\"".VENDOR."pnotify/pnotify.custom.js\"></script>",
        "jquery.maskedinput.js"     => "\n<script src=\"".VENDOR."jquery-maskedinput/jquery.maskedinput.js\"></script>",
        "select2.js"                => "\n<script src=\"".VENDOR."select2/select2.js\"></script>",
        "jquery.autosize.js"        => "\n<script src=\"".VENDOR."jquery-autosize/jquery.autosize.js\"></script>",
        "bootstrap-multiselect.js"  => "\n<script src=\"".VENDOR."bootstrap-multiselect/bootstrap-multiselect.js\"></script>",
        "jquery.flot.js"            => "\n<script src=\"".VENDOR."flot/jquery.flot.js\"></script>",
        "jquery.flot.tooltip.js"    => "\n<script src=\"".VENDOR."flot-tooltip/jquery.flot.tooltip.js\"></script>",
        "jquery.flot.categories.js" => "\n<script src=\"".VENDOR."flot/jquery.flot.categories.js\"></script>",
        "snap.svg.js"               => "\n<script src=\"".VENDOR."snap-svg/snap.svg.js\"></script>",
        "liquid.meter.js"           => "\n<script src=\"".VENDOR."liquid-meter/liquid.meter.js\"></script>",
        "landing.js"                => "\n<script src=\"".c_MODULE."landing.js\"></script>",
        "customer-custom.js"        => "\n<script src=\"".c_URL.$ModuleDir."customer/customer-custom.js\"></script>",
        "jquery.dataTables.js"      => "\n<script src=\"".VENDOR."jquery-datatables/media/js/jquery.dataTables.js\"></script>",
        "dataTables.tableTools.min.js"=> "\n<script src=\"".VENDOR."jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js\"></script>",
        "datatables.js"             => "\n<script src=\"".VENDOR."jquery-datatables-bs3/assets/js/datatables.js\"></script>",
        "codemirror.js"             => "\n<script src=\"".VENDOR."codemirror/lib/codemirror.js\"></script>",
        "active-line.js"            => "\n<script src=\"".VENDOR."codemirror/addon/selection/active-line.js\"></script>",
        "matchbrackets.js"          => "\n<script src=\"".VENDOR."codemirror/addon/edit/matchbrackets.js\"></script>",
        "javascript.js"             => "\n<script src=\"".VENDOR."codemirror/mode/javascript/javascript.js\"></script>",
        "xml.js"                    => "\n<script src=\"".VENDOR."codemirror/mode/xml/xml.js\"></script>",
        "htmlmixed.js"              => "\n<script src=\"".VENDOR."codemirror/mode/htmlmixed/htmlmixed.js\"></script>",
        "css.js"                    => "\n<script src=\"".VENDOR."codemirror/mode/css/css.js\"></script>",
        "summernote.js"             => "\n<script src=\"".VENDOR."summernote/summernote.js\"></script><script src=\"".VENDOR."summernote/plugins/image-manager.js\"></script><script src=\"".VENDOR."summernote/plugins/video.js\"></script>",
        "bootstrap-tagsinput.js"    => "\n<script src=\"".VENDOR."bootstrap-tagsinput/bootstrap-tagsinput.js\"></script>",
        "bootstrap-timepicker.js"   => "\n<script src=\"".VENDOR."bootstrap-timepicker/js/bootstrap-timepicker.js\"></script>",
        "jquery.validate.js"        => "\n<script src=\"".VENDOR."jquery-validation/jquery.validate.js\"></script>",
        "jquery.validate.msg.id.js"             => "\n<script src=\"".VENDOR."jquery-validation/messages_id.js\"></script>",
        "jquery.valdate.additional-method.js"   => "\n<script src=\"".VENDOR."jquery-validation/additional-method.js\"></script>",
        "cropper.js"               => "\n<script src=\"".VENDOR."cropperjs/cropper.js\"></script>",

        "ckeditor.js"               => "\n<script src=\"".VENDOR."ckeditor/ckeditor.js\"></script>\n<script src=\"".VENDOR."ckeditor/adapters/jquery.js\"></script>",
        //"bootstrap-timepicker.js"   => "\n<script src=\"".VENDOR."bootstrap-timepicker/js/bootstrap-timepicker.js\"></script>",


        //"dropzone.js"               => "\n<script src=\"".VENDOR."dropzone/dropzone.js\"></script>",
        "jquery.appear.js"          => "\n<script src=\"".VENDOR."jquery-appear/jquery.appear.js\"></script>",
        "jquery.flot.pie.js"        => "\n<script src=\"".VENDOR."flot/jquery.flot.pie.js\"></script>",                    
        //"jquery.flot.resize.js"     => "\n<script src=\"".VENDOR."flot/jquery.flot.resize.js\"></script>",
        //"jquery.sparkline.js"       => "\n<script src=\"".VENDOR."jquery-sparkline/jquery.sparkline.js\"></script>",
        "raphael.js"                => "\n<script src=\"".VENDOR."raphael/raphael.js\"></script>",
        "morris.js"                 => "\n<script src=\"".VENDOR."morris/morris.js\"></script>",
        "gauge.js"                  => "\n<script src=\"".VENDOR."gauge/gauge.js\"></script>",
        
        
        //base
        "theme.js"                  => "\n<script src=\"".JS."theme.js\"></script>",
        "theme.custom.js"           => "\n<script src=\"".JS."theme.custom.js\"></script>",
        "theme.init.js"             => "\n<script src=\"".JS."theme.init.js\"></script>",
        "media.js"                  => "\n<script src=\"".JS."pages/examples.mediagallery.js\"></script>",

        //custom
        //"examples.dashboard.js"     => "\n<script src=\"".JS."dashboard/examples.dashboard.js\"></script>",
        "examples.charts.js"        => "\n<script src=\"".JS."ui-elements/examples.charts.js\"></script>",

        "fileinput.min.js"          => "\n<script src=\"".VENDOR."file-input/fileinput.min.js\"></script>",
        "jquery.nestable.js"          => "\n<script src=\"".VENDOR."jquery-nestable/jquery.nestable.js\"></script>"

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

function VIEW_CHILD_CATEGORY($parent="0", $level="0") {
  $sqld = new db;
  $sqld -> db_Select("category", "cat_id, cat_name, cat_count", "WHERE `cat_type`='cat_item' AND `parent_id`='{$parent}' GROUP BY cat_id");

  while ($row = $sqld-> db_Fetch()) {
    echo "
    <tr>
        <td>".str_repeat('—',$level)." ".$row['cat_name']."</td>
        <td>".$row['cat_name']."</td>
        <td>".$row['cat_count']."</td>
        <td class=\"actions-hover actions-fade\">
            <a href=\"".c_SELF."?action=edit&id=".$row['cat_id']."\"><i class=\"fa fa-pencil\"></i></a>
            <a href=\"".c_SELF."?action=delete&id=".$row['cat_id']."\" class=\"delete-row\"><i class=\"fa fa-trash-o\"></i></a>
        </td>
    </tr>
    ";
    VIEW_CHILD_CATEGORY($row['cat_id'], $level+1);
  }
}

function SELECT_CHILD_CATEGORY($parent="0", $level="0") {
  $sqld = new db;
  $sqld -> db_Select("category", "cat_id, cat_name", "WHERE `cat_type`='cat_item' AND `parent_id`='{$parent}' GROUP BY cat_id");
  while ($row = $sqld-> db_Fetch()) {
    echo "<option value=\"{$row['cat_id']}\">".str_repeat('├',$level)." {$row['cat_name']}</option>";
    SELECT_CHILD_CATEGORY($row['cat_id'], $level+1);
  }
}

function CEK_PASSWORD_EXPIRED() {
	if(((OPPWCHANGE+2592000) < time()) && (c_PAGE != "ubah_passwords.php")) {
		echo "
		<!-- Password Expired -->
        <div class=\"nNote nWarning hideit\">
            <p><strong>WARNING: </strong>Untuk keamanan, Silakan ganti password anda secara berkala.</span>
	Password anda telah 30 hari Tidak diGanti. <a href='". c_MODULE ."ubah_passwords.php'>Ubah Password disini</a></p>
        </div>";
    }
}


function GET_AVATAR_IMAGES ( $email ) {
	$email = trim ($email);
	$email = strtolower ($email);
	$email = md5 ( $email );
	return "http://www.gravatar.com/avatar/".$email;
}

/*
 * Statistik bar Admin page
 */
function STATISTIK_BAR(){
}

/*
 * cek available id slug tag, cat
 */
function is_available_id_taxonomy ( $id ) {
    $sql = new db;
    $sql -> db_Select("apotik_taxonomy", "taxonomy_id", "taxonomy_id='{$id}'");
    if ($sql->db_Rows()) {
        return TRUE;
    }
    else { return FALSE;}
}

/*
 * SEO url friendly
 */
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

/*
 * Duit Format
 */
function duit($xx) {
    if (empty($xx)){ return $xx;}else {
    $x = trim($xx);
    $b = number_format($x, 0, ",", ".");
    return $b;
    }
}

/*
 * tanggal Format
 */
function tanggal($xx) {
    $r = explode("-", $xx);
    return $r[2]."-".$r[1]."-".$r[0];
}
/*
* Image Manager Modal Untuk Banner/Featured Image Post
*/
function ImageManagerModal($setting=array()){
    $btn_name=$setting['btn_select_img_name'];
    if($btn_name!=""){
        $btn_select=$setting['btn_select_img_name'];
    }else{
        $btn_select="Jadikan Banner Image";
    }
?>
<div id="Gallery_Select_Img" class="zoom-anim-dialog modal-block modal-block-full modal-block-primary mfp-hide">
    <section class="panel">
        <header class="panel-heading">
            <h2 class="panel-title">Image Manager</h2>
        </header>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="tabs">
                        <ul class="nav nav-tabs tabs-primary">
                            <li class="active">
                                <a href="#upload" data-toggle="tab">Upload</a>
                            </li>
                            <li>
                                <a href="#pickfrommedia" data-toggle="tab">Ambil Dari Media Images</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="upload" class="tab-pane active">
                                <form enctype="multipart/form-data" id="newUploadImg" class="form-horizontal">
                                    <input type="hidden" name="ac" value="addImage">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="file" class="file-loading" id="file" accept="image/*" name="file">
                                        </div>
                                    </div>
                                    <br>
                                    <span id="formbefore">
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Nama Gambar</label>
                                            <div class="col-md-9">
                                                <input type="text" name="nm_img" id="nm_img" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Sumber Gambar</label>
                                            <div class="col-md-9">
                                                <input type="text" name="sumber_img" id="sumber_img" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Link Sumber Gambar</label>
                                            <div class="col-md-9">
                                                <input type="text" name="sumber_link_img" id="sumber_link_img" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Deskripsi Gambar</label>
                                            <div class="col-md-9">
                                                <input type="text" name="desc_img" id="desc_img" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-3">
                                                 <button class="btn btn-primary" id="btnUploadImg">Upload</button><span id="actloadingimgupload" style="display:none"><i class="fa fa-spin fa-spinner"></i> Mengupload Gambar....</span><span id="statusupload"></span>
                                            </div>
                                        </div>
                                    <span>
                                </form>
                            </div>
                            <div id="pickfrommedia" class="tab-pane">
                                <table class="table" id="listmediaimage">
                                    <thead>
                                        <tr>
                                            <th width="35px">Gambar</th>
                                            <th>Nama</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    Gambar yang dipilih: <br/><span id="selectedimg">Tidak Ada</span>
                </div>
            </div>
        </div>
        <footer class="panel-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button id="fimg_pilih" class="btn btn-primary modal-selectimg"><?php echo $btn_select;?></button>
                    <!-- <button id="fimg_insert" class="btn btn-primary modal-insertimg">Masukkan Gambar</button> -->
                    <button class="btn btn-default modal-dismiss">Cancel</button>
                </div>
            </div>
        </footer>
    </section>
</div>
<?php
}

?>