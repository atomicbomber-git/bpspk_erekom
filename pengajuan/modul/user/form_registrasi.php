<?php
@include_once ("../engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, theme.css, default.css, theme-custom.css, modernizr.js";
$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholder.js, theme.js, theme.custom.js, theme.init.js, jquery.validate.js, jquery.validate.msg.id.js";

@include(c_THEMES."meta.php");

$SCRIPT_FOOT = '
<script>
$(document).ready(function(){
	$("#formregistrasi").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nama_lengkap:{
				required:true
			},
			email:{
				required:true,
				email:true,
				remote:{
					url:"'.c_STATIC.'pengajuan/ajax.php",
					type:"post",
					data: {
						a : "ec",
						email : function() {
				            return $( "#email" ).val();
				        }
					}
				}
			},
			pwd:{
				required:true
			},
			pwd_confirm:{
				required:true,
				equalTo:"#pwd"
			}
		},
		messages:{
			nama_lengkap:{
				required:"Nama Lengkap Harus Diisi"
			},
			email:{
				required:"Email Harus Diisi",
				remote:"Email sudah digunakan"
			},
			pwd:{
				required:"Password Harus Diisi"
			},
			pwd_confirm:{
				equalTo:"Harap Masukkan Kembali Password yang sudah diisi."
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
				$.ajax({
				url:"'.c_STATIC.'pengajuan/ajax.php",
				dataType:"json",
				type:"post",
				cache:false,
				data:$("#formregistrasi").serialize(),
				beforeSend:function(){
					$(".btn-akun").prop("disabled", true);
					$("#actloading").show();	
				},
				success:function(json){	
					if(json.stat){
						$(".pesansukses").html(json.msg);
						$(".pesansukses").show();
						$("#formregistrasi")[0].reset();
					}else{
						$(".pesanerror").html(json.msg);
						$(".pesanerror").show();
						grecaptcha.reset();
					}
					window.location.hash = "#sign-up";
					$(".btn-akun").prop("disabled", false);
					$("#actloading").hide();
				}
			});
			return false;
			}
	});
});
</script>';
?>
<section class="body-sign">
	<div class="center-sign" id="sign-up">
		<!-- <a href="<?php echo c_MODULE ;?>" class="logo pull-left" style="padding-top:10px;">
			<img src="<?php echo IMAGES ;?>logo.png" style="max-width:259px;" alt="<?php echo c_APP ;?>, Registrasi Pemohon" />
		</a> -->
		<p style="display: none" class="pesanerror text-center alert alert-danger"></p>
		<p style="display: none" class="pesansukses text-center alert alert-success"></p>
		<div class="panel panel-sign" >
			<div class="panel-title-sign mt-xl text-right">
				<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Pendaftaran Akun</h2>
			</div>
			<div class="panel-body">
				<form id="formregistrasi" method="post">
					<input type="hidden" name="a" value="r" >
					<div class="form-group mb-lg">
						<label>Nama Lengkap</label>
						<input name="nama_lengkap" id="nama_lengkap" type="text" class="form-control input-lg" />
					</div>

					<div class="form-group mb-lg">
						<label>Alamat Email</label>
						<input name="email" id="email" type="email" class="form-control input-lg" />
					</div>

					<div class="form-group mb-none">
						<div class="row">
							<div class="col-sm-6 mb-lg">
								<label>Password</label>
								<input name="pwd" id="pwd" type="password" class="form-control input-lg" />
							</div>
							<div class="col-sm-6 mb-lg">
								<label>Konfirmasi Password</label>
								<input name="pwd_confirm" type="password" class="form-control input-lg" />
							</div>
						</div>
					</div>

					<div class="form-group mb-lg">
						<script src="https://www.google.com/recaptcha/api.js?hl=id" async defer></script>
						<div class="g-recaptcha" data-sitekey="6LdTkiYTAAAAAPAX_45mraZiT4tbnK5mv7V6RMz1"></div>
					</div>

					<div class="row">
						<div class="col-sm-8">
							<button type="submit" class="btn btn-primary hidden-xs btn-akun">Buat Akun</button>
							<button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Buat Akun</button>
							<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Loading...</span>
						</div>
						<div class="col-sm-4 text-right"></div>
					</div>
					<span class="mt-lg mb-lg line-thru text-center text-uppercase">
						<span>or</span>
					</span>

					<p class="text-center">Sudah Memiliki Akun ? <a href="?login">Login</a>

				</form>
			</div>
		</div>
		<p class="text-center text-muted mt-md mb-md">
			&copy;<?php echo date("Y") ;?> <a href="http://<?php echo $SITE_CONF_AUTOLOAD['WEBSITE_CLIENT'] ;?>/"><?php echo c_CLIENT ;?></a>.<br/>
   		</p>
	</div>
</section>
<?php
include(AdminFooter);
?>