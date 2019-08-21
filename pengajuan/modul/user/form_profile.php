<?php
@include_once ("../engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, theme.css, default.css, pnotify.custom.css, theme-custom.css, modernizr.js";
$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, pnotify.custom.js, jquery.placeholder.js, theme.js, theme.custom.js, theme.init.js, jquery.validate.js, jquery.validate.msg.id.js";

@include(c_THEMES."meta.php");

$SCRIPT_FOOT = '
<script>
$(document).ready(function(){
	$("nav li.navakun").addClass("nav-active");
	$("#update_akun").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nama_lengkap:{
				required:true
			},
			email:{
				required:true,
				email:true
			},
			newpwd:{
				minlength: 4
			},
			newpwd_repeat:{
				equalTo:newpwd
			},
			oldpwd:{
				required:true
			}
		},
		messages:{
			nama_lengkap:{
				required:"Nama Lengkap Tidak Boleh Kosong."
			},
			email:{
				required:"Email Tidak Boleh Kosong."
			},
			oldpwd:{
				required:"Password Harus Diisi."
			}
		},
		errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        highlight: function (element, validClass) {
            $(element).parent().addClass("has-error");
        },
        unhighlight: function (element, validClass) {
            $(element).parent().removeClass("has-error");
        },
  		submitHandler: function(form) {
  			var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
  			$.ajax({
				url:"ajax.php",
				dataType:"json",
				type:"post",
				cache:false,
				data:$("#update_akun").serialize(),
				beforeSend:function(){
					$("#btn_update").prop("disabled", true);
					$("#actloading").show();
				},
				success:function(json){	
					if(json.stat){
						var notice = new PNotify({
							title: "Notification",
							text: json.msg,
							type: "success",
							addclass: "stack-bar-bottom",
							stack: stack_bar_bottom,
							width: "60%",
							delay:1000,
							after_close:function(){
								location.reload();
							}
						});
					}else{
						var notice = new PNotify({
							title: "Notification",
							text: json.msg,
							type: "warning",
							addclass: "stack-bar-bottom",
							stack: stack_bar_bottom,
							width: "60%",
							delay:2500
						});
					}
					$("#btn_update").prop("disabled", false);
					$("#actloading").hide();
				}
			});
  		}
	});

});
</script>
';
$sql->get_row('tb_userpublic',array('iduser'=>U_ID),'*');
$row=$sql->result;
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Pengaturan Akun</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_DOMAIN;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Pengaturan Akun</span></li>
			</ol>
			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<form id="update_akun" method="post">
		<input type="hidden" name="a" value="upak">
		<input type="hidden" name="olde" value="<?php echo base64_encode($row['email']);?>">
		<div class="row">
			<div class="col-md-12">
				<section class="panel">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
						</div>
						<h2 class="panel-title">Pengaturan Akun</h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-3 control-label" for="nama_lengkap">Nama Lengkap</label>
							<div class="col-md-6">
								<input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $row['nama_lengkap'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label" for="email">Email</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email'];?>">
								<p class="text-alert alert-danger">Anda Harus Verifikasi Ulang Akun Jika Mengganti Email</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label" for="newpwd">Password Baru</label>
							<div class="col-md-4">
								<input type="password" class="form-control" id="newpwd" name="newpwd">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label" for="newpwd_repeat">Ulangi Password Baru</label>
							<div class="col-md-4">
								<input type="password" class="form-control" id="newpwd_repeat" name="newpwd_repeat">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label" for="oldpwd">Password Lama</label>
							<div class="col-md-4">
								<input type="password" class="form-control" id="oldpwd" name="oldpwd">
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<div class="form-group">
							<div class="col-md-3"></div>
							<div class="col-md-9">
								<button class="btn btn-primary btn-sm btn_update" type="submit" >Simpan Perubahan</button>
								<p id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</p>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</form>
</section>
<?php
include(AdminFooter);
?>
