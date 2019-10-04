<?php
@include_once ("../engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, theme.css, default.css, theme-custom.css, modernizr.js";
$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholder.js, theme.js, theme.custom.js, theme.init.js, jquery.validate.js, jquery.validate.msg.id.js";

@include(c_THEMES."meta.php");

$SCRIPT_FOOT="
<script>
$(document).ready(function(){
	$('nav li.landing').addClass('nav-active');

	$('#form_verify').validate({
		ignore: [],
		errorClass: 'error',
		rules:{
			ver_kode:{
				required:true
			}
		},
		messages:{
			ver_kode:{
				required:'Silakan Masukkan Kode Verifikasi.'
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
			url:'".c_STATIC."pengajuan/ajax.php',
			dataType:'json',
			type:'post',
			cache:false,
			data:$('#form_verify').serialize(),
			beforeSend:function(){
				$('.pesansukses').hide();
				$('.pesanerror').hide();	
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

	$('#kirim_ulang').on('click',function(){
		$.ajax({
			url:'".c_STATIC."pengajuan/ajax.php',
			dataType:'json',
			type:'post',
			cache:false,
			data:'a=svk',
			beforeSend:function(){
				$('#kirim_ulang').hide();
				$('#actloading').show();
				$('.pesanerror').hide();
				$('.pesansukses').hide();	
			},
			success:function(json){	
				if(json.stat){
					$('.pesansukses').html(json.msg);
					$('.pesansukses').show();
				}else{
					$('.pesanerror').html(json.msg);
					$('.pesanerror').show();
				}
				setTimeout(function(){ 
					$('#kirim_ulang').show();
				}, 5000);
				$('#actloading').hide();
				
			}
		});
	});
});
</script>";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Verifikasi Akun</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_DOMAIN;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Verifikasi Akun</span></li>
			</ol>
			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<form id="form_verify" method="post">
		<input type="hidden" name="a" value="vk">
		<div class="row">
			<div class="col-md-8">
				<section class="panel">
					<div class="panel-body">
						<?php
						if(!container(App\Services\Auth::class)->isVerified()){
						?>
						<div class="row">
							<div class="col-md-12">
							<p>Silakan Masukkan Kode Verifikasi Yang Telah Dikirim Ke Email Anda.<br>
							Belum Menerima Kode Verifikasi ? <a href="#" id="kirim_ulang"><strong>Kirim Ulang</strong></a></p>
							<p style="display: none" class="pesanerror text-center alert alert-danger"></p>
							<p style="display: none" class="pesansukses text-center alert alert-success"></p>
							<p id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Mengirim Kode Verifikasi...</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<input type="text" name="ver_kode" class="form-control input-lg" maxlength="5" style="text-transform:uppercase">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group"><br>
									<button class="btn btn-primary btn_verifikasi">Verifikasi</button>
								</div>
							</div>
						</div>
						<?php
						}else{
							?>
							<div class="row">
								<div class="col-md-12">
								<p>Akun Anda Sudah Diverifikasi, dan Dapat Mengajukan Permohonan Rekomendasi</p>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</section>
			</div>
		</div>
	</form>
</section>
<?php
include(AdminFooter);
?>
