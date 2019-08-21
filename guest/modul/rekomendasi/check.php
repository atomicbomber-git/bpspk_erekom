<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('ul li.nav-check').addClass('active');

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

?>
<body class="hold-transition skin-blue sidebar-mini">

<div class="content-wrapper">
	<section class="content-header">
		<h1>
		Cek Surat
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo c_URL;?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Cek Surat</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-6">
				<div class="box">
					<form action="" method="post" class="form-horizontal" id="form_check">
						<div class="box-header with-border">
							<h3 class="box-title">Upload Foto</h3>
						</div>
						<div class="box-body">
							<label class="control-label">Silakan Input Kode Surat / No Surat Rekomendasi</label>
							<input class="form-control" type="text" name="check_kode" >
							<input type="hidden" name="a" value="check">
							<br/>
							<div class="alert alert-info" id="hasil_check" style="display:none"></div>
						</div>
						<div class="box-footer">
							<button type="submit" class="btn btn-sm btn-primary btn_check btn-flat">Cek Surat</button>
							<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Loading....</span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>


</div>
</body>
<?php
include(AdminFooter);
?>
