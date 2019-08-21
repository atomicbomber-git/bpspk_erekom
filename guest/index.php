<?php
include_once ("engine/render.php");
$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, theme.css,";
$ITEM_FOOT = "jquery.js, bootstrap.js, jquery.validate.js, jquery.validate.msg.id.js, theme.js";

require_once(c_THEMES."auth.php");

$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
  $('ul li.nav-dashboard').addClass('active');
});
</script>
";

?>
  <body class="hold-transition skin-blue sidebar-mini">
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-purple" style="cursor: pointer;" onclick="location.href='<?php echo c_URL.$ModuleDir;?>rekomendasi/check.php'">
            <div class="inner">
              <h4>Cek <br>Surat Rekomendasi</h4>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-aqua" style="cursor: pointer;" onclick="location.href='<?php echo c_URL.$ModuleDir;?>lap-statistik/'">
            <div class="inner">
              <h4>Statistik <br>Data</h4>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        
      </div>
    </section>
  </div>

</div>
</body>
<?php
@include(AdminFooter);
?>