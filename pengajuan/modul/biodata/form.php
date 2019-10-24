<?php
@include_once ("../engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, theme.css, default.css, pnotify.custom.css, theme-custom.css, modernizr.js";
$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, pnotify.custom.js, jquery.placeholder.js, theme.js, theme.custom.js, theme.init.js, jquery.validate.js, jquery.validate.msg.id.js";

@include(c_THEMES."meta.php");

$SCRIPT_FOOT="
<script>
$(document).ready(function(){
	$('nav li.nav2').addClass('nav-active');
	$('.img-prev').magnificPopup({type:'image'});
	$('#form_biodata').validate({
		ignore: [],
		errorClass: 'error',
		rules:{
			nm_lengkap:{required:true},
			tmp_lahir:{required:true},
			tgl_lahir:{required:true},
			no_ktp:{required:true,digits:true},
			nib:{required:true,digits:true},
			no_telp:{required:true},
			//ttd:{required:true},
			gudang_1:{required:true}
		},
		messages:{
			nm_lengkap:{required:'Nama Lengkap Harap Diisi.'},
			tmp_lahir:{required:'Tempat Lahir Harap Diisi.'},
			tgl_lahir:{required:'Tanggal Lahir Harap Diisi.'},
			no_ktp:{required:'No Identitas Harap Diisi.'},
			no_telp:{required:'No Telepon Harap Diisi'},
			//ttd:{required:'Silakan Uplaod Tandatangan Anda'},
			gudang_1:{required:'Alamat Gudang Harap Diisi'}
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
			var stack_bar_bottom = {'dir1': 'up', 'dir2': 'right', 'spacing1': 0, 'spacing2': 0};
			var formData = new FormData(document.getElementById('form_biodata'));
			$.ajax({
				url:'".c_STATIC."pengajuan/modul/biodata/ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:formData,
				mimeType:'multipart/form-data',
				contentType: false,
				processData:false,
				beforeSend:function(){
					$('#btn_simpan').prop('disabled', true);
					$('#actloading').show();	
				},
				success:function(json){	
					if(json.stat){
						var notice = new PNotify({
							title: 'Notification',
							text: json.msg,
							type: 'success',
							addclass: 'stack-bar-bottom',
							stack: stack_bar_bottom,
							width: '60%',
							delay:1000,
							after_close:function(){
								location.reload();
							}
						});
					}else{
						var notice = new PNotify({
							title: 'Notification',
							text: json.msg,
							type: 'warning',
							addclass: 'stack-bar-bottom',
							stack: stack_bar_bottom,
							width: '60%',
							delay:2500
						});
					}

					$('#btn_simpan').prop('disabled', false);
					$('#actloading').hide();
				}
			});
		return false;
		}
	});
});
</script>";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Biodata Pemohon</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_DOMAIN;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Biodata Pemohon</span></li>
			</ol>
			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<?php
	$found=$sql->get_count('tb_biodata',array('ref_iduser'=>U_ID));

	if($found>0){
		include("edit.php");
	}else{
		include("add.php");
	}
	?>
</section>
<?php
include(AdminFooter);
?>
