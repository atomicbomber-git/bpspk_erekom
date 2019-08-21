<?php

require_once("../bootstrap.php");
include_once ("engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, bootstrap-multiselect.css,pnotify.custom.css, datatables.css, fileinput.min.css, theme.css, default.css, theme-custom.css,  modernizr.js";
$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholder.js, bootstrap-multiselect.js, jquery.dataTables.js,ckeditor.js, datatables.js, fileinput.min.js, jquery.flot.js, jquery.flot.tooltip.js, jquery.flot.categories.js,pnotify.custom.js,jquery.validate.js,jquery.validate.msg.id.js snap.svg.js, liquid.meter.js, theme.js, theme.init.js";


require_once(c_THEMES."auth.php");

$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.landing').addClass('nav-active');

	$('#form_check').validate({
		ignore: [],
		errorClass: 'error',
		rules:{
			check_kode:{
				minlength:5,
				required: true
			}
		},
		messages:{
			check_kode:{
				required:'Silakan Input Nomor Surat atau Kode Surat'
			}
		},
		errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        highlight: function (element, validClass) {
            $(element).parent().addClass('has-error');
        },
        unhighlight: function (element, validClass) {
            $(element).parent().removeClass('has-error');
        },
  		submitHandler: function(form) {
  			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$('#form_check').serialize(),
				beforeSend:function(){
					$('.btn_check').prop('disabled', true);
					$('#actloading').show();
					$('#hasil_check').hide();
				},
				success:function(json){	
					if(json.stat){
						$('#hasil_check').html(json.msg);
						$('#hasil_check').show();
					}else{
						$('#hasil_check').html(json.msg);
						$('#hasil_check').show();
					}
					$('.btn_check').prop('disabled', false);
					$('#actloading').hide();
				}
			});
    		return false;
  		}
	});
});
</script>
";

$profilpic="!logged-user.jpg";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Dashboard</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Dashboard</span></li>
			</ol>
	
			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-primary">
				<strong>Hi <?php echo U_NAME ;?>,Selamat Datang.</strong> <span class="pull-right">Hari ini : <?php echo tanggalIndo(date('Y-m-d H:i:s'),"l, j F Y H:i");?> </span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<form action="" method="post" class="form-horizontal" id="form_check">
				<section class="panel">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
						</div>
						<h2 class="panel-title">Cek Surat Rekomendasi</h2>
					</header>
					<div class="panel-body">
						<label class="control-label">Silakan Input Kode Surat / No Surat Rekomendasi</label>
						<input class="form-control" type="text" name="check_kode" >
						<input type="hidden" name="a" value="check">
						<br/>
						<div class="alert alert-info" id="hasil_check" style="display:none"></div>
					</div>
					<footer class="panel-footer">
						<button type="submit" class="btn btn-sm btn-primary btn_check">Cek Surat</button>
						<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Loading....</span>
					</footer>
				</section>
			</form>
		</div>
	</div>
	<div class="row">
		
	</div>
</section>
<?php
@include(AdminFooter);
?>