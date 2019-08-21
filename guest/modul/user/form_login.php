<?php
@include_once ("../engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, theme.css,";
$ITEM_FOOT = "jquery.js, bootstrap.js, jquery.validate.js, jquery.validate.msg.id.js";

@include(c_THEMES."meta.php");

$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$(\"#formlogin\").validate({
		ignore: [],
		errorClass: \"error\",
		rules:{
			p_uname:{
				required:true
			},
			p_pwd:{
				required:true
			}
		},
		messages:{
			p_uname:{
				required:\"Username Harus Diisi\"
			},
			p_pwd:{
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
				url:'".c_STATIC."ajax.php',
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
						//grecaptcha.reset();
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
<body class="hold-transition login-page">
<div class="login-box">
  	<div class="login-logo">
    	<img src="<?php echo IMAGES;?>logo.png" width="150px">
  	</div>
  	<div class="login-box-body">
    	<p class="login-box-msg">AKSES TAMU <br/>Silakan Login</p>

		<form action="" method="post" id="formlogin">
			<input type="hidden" name="a" value="l">
			<div class="form-group has-feedback">
				<span class="fa fa-user-circle form-control-feedback"></span>
				<input type="text" name="p_uname" class="form-control" placeholder="Username">
			</div>
			<div class="form-group has-feedback">
				<span class="fa fa-lock form-control-feedback"></span>
				<input type="password" name="p_pwd" class="form-control" placeholder="Password">
			</div>
			<div class="row">
				<div class="col-xs-8">
					<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Loading...</span>
				</div>
				<div class="col-xs-4">
				<button type="submit" id="btn_login" class="btn btn-primary btn-block btn-flat">Login</button>
				</div>
			</div>
		</form>

		<div class="social-auth-links text-center">
			<p style="display: none" class="pesanerror text-center alert alert-danger"></p>
		</div>
  </div>
</div>
</body>
<?php
include(AdminFooter);
?>