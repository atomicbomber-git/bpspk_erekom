<?php
@include_once ("../engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, theme.css, default.css, theme-custom.css, modernizr.js";
$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, 
		jquery.placeholder.js, theme.js, theme.custom.js, theme.init.js, jquery.validate.js,jquery.validate.msg.id.js";
		
@include(c_THEMES."meta.php");

$SCRIPT_FOOT = "<script>
$(document).ready(function(){
	$(\"#lockconfirm\").validate({
		ignore: [],
		errorClass: \"error\",
		rules:{
			pwd:{
				required:true
			}
		},
		messages:{
			pwd:{
				required:\"Password Tidak Boleh Kosong.\"
			}
		},
		errorPlacement: function (error, element) {
	        //error.appendTo( element.parent(\"div\"));
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
				data:$(\"#lockconfirm\").serialize(),
				beforeSend:function(){	
				},
				success:function(json){	
					if(json.stat){
						location.reload();
					}else{
						$('.pesanerror').html(json.msg);
						$('.pesanerror').show();
					}
				}
			});
			return false;
			}
	});
	});
</script>";

?>
<section class="body-sign body-locked">
	<div class="center-sign">
		<div class="panel panel-sign">
			<div class="panel-body">
				<form action="" id="lockconfirm" method="POST">
					<input type="hidden" name="a" value="unlock">
					<div class="current-user text-center">
						<img src="<?php echo IMAGES;?>!logged-user.jpg" alt="" class="img-circle user-image" />
						<h2 class="user-name text-dark m-none"><?php echo U_NAME;?></h2>
						<!-- <p class="user-email m-none"></p> -->
					</div>
					<div class="form-group mb-lg">
						<div class="input-group input-group-icon">
							<input id="pwd" name="pwd" type="password" class="form-control input-lg" placeholder="Password" />
							<span class="input-group-addon">
								<span class="icon icon-lg">
									<i class="fa fa-lock"></i>
								</span>
							</span>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-8">
							<p class="mt-xs mb-none">
								<a href="?keluar">Bukan <?php echo U_NAME;?> ?</a>
							</p>
						</div>
						<div class="col-xs-4 text-right">
							<button type="submit" class="btn btn-primary">Unlock</button> 
							<!-- <a href="?unlock" class="btn btn-primary">Unlock</a> -->
						</div>
					</div>
				</form>
			</div>
			<br/>
			<p style="display: none" class="pesanerror text-center alert alert-danger"></p>
		</div>
	</div>
</section>
<?php
include(AdminFooter);
?>