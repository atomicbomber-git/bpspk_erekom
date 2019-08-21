<?php
@include_once ("../engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, theme.css, default.css, theme-custom.css, modernizr.js";
$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholder.js, theme.js, theme.custom.js, theme.init.js, jquery.validate.js, jquery.validate.msg.id.js";

@include(c_THEMES."meta.php");

$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$(\"#formlogin\").validate({
		ignore: [],
		errorClass: \"error\",
		rules:{
			authuser:{
				required:true
			},
			authsandi:{
				required:true
			}
		},
		messages:{
			authuser:{
				required:\"Username Harus Diisi\"
			},
			authsandi:{
				required:\"Password Harus Diisi\"
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
				url:'".c_STATIC."admin/ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$(\"#formlogin\").serialize(),
				beforeSend:function(){
					$('#btn_login').prop('disabled', true);
					$('#actloading').show();	
				},
				success:function(json){	
					if(json.stat){
						location.reload();
					}else{
						$('.pesanerror').html(json.msg);
						$('.pesanerror').show();
						grecaptcha.reset();
					}
					$('#actloading').hide();
					$('#btn_login').prop('disabled', false);
				}
			});
			return false;
			}
	});
});
</script>";

?>
<section class="body-sign">
	<div class="center-sign">
		<!-- <a href="<?php echo c_MODULE ;?>" class="logo pull-left" style="padding-top:10px;">
			<img src="<?php echo IMAGES ;?>logo.png" style="max-width:259px;" alt="<?php echo c_APP ;?>, Login Pemohon" />
		</a> -->

		<div class="panel panel-sign">
			<div class="panel-title-sign mt-xl text-right">
				<h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Login</h2>
			</div>
			<div class="panel-body">
				<form action=" method="post" id="formlogin">
					<input type="hidden" name="a" value="l">
					<div class="form-group mb-lg">
						<label>Username</label>
						<div class="input-group input-group-icon">
							<input name="authuser" type="text" class="form-control input-lg" />
							<span class="input-group-addon">
								<span class="icon icon-lg">
									<i class="fa fa-user"></i>
								</span>
							</span>
						</div>
					</div>

					<div class="form-group mb-lg">
						<div class="clearfix">
							<label class="pull-left">Password</label>
							<!-- <a href="?recover" class="pull-right">Lupa Password?</a> -->
						</div>
						<div class="input-group input-group-icon">
							<input name="authsandi" type="password" class="form-control input-lg" />
							<span class="input-group-addon">
								<span class="icon icon-lg">
									<i class="fa fa-lock"></i>
								</span>
							</span>
						</div>
					</div>
					<div class="form-group mb-lg">
						<script src="https://www.google.com/recaptcha/api.js?hl=id" async defer></script>
						<div class="g-recaptcha" data-sitekey="6LdTkiYTAAAAAPAX_45mraZiT4tbnK5mv7V6RMz1"></div>
					</div>

					<div class="row">
						<div class="col-sm-8">
							<button type="submit" name="LoginSubmit" id="btn_login" value="Sign In" class="btn btn-primary hidden-xs">Masuk</button>
							<button type="submit" name="LoginSubmit" id="btn_login" value="Sign In" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Masuk</button>
							<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Loading...</span>
							<!-- <div class="checkbox-custom checkbox-default">
								<input id="RememberMe" name="autologin" type="checkbox"/>
								<label for="RememberMe">AutoLogin Selama 7 Hari</label>
							</div> -->
						</div>
						<div class="col-sm-4">
							<!-- <button type="submit" name="LoginSubmit" value="Sign In" class="btn btn-primary hidden-xs">Masuk</button>
							<button type="submit" name="LoginSubmit" value="Sign In" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Masuk</button> -->
						</div>
					</div>
				</form>
			</div>
		</div>
		<p style="display: none" class="pesanerror text-center alert alert-danger"></p>

		<p class="text-center text-muted mt-md mb-md">
			&copy;<?php echo date("Y") ;?> <a href="http://<?php echo $SITE_CONF_AUTOLOAD['WEBSITE_CLIENT'] ;?>/"><?php echo c_CLIENT ;?></a>.<br/>
   		</p>
	</div>
</section>
<?php
include(AdminFooter);
?>