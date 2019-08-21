<?php
require_once("config.php");
$SCRIPT_FOOT = '
<script>
$(document).ready(function(){
	$("nav li.nav-profile").addClass("nav-active");
	$("#fupdateprofil").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nama_lengkap:{
				required:true
			},
			email:{
				email:true
			},
			newpwd:{
				minlength: 4
			},
			newpwd_repeat:{
				equalTo:newpwd
			}
		},
		messages:{
			nama_lengkap:{
				required:"Nama Lengkap Tidak Boleh Kosong."
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
				data:$("#fupdateprofil").serialize(),
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
							delay:1000
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

$sql->get_row('op_user',array("idu"=>U_ID));
$found=$sql->num_rows;
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>User Profile</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Users</span></li>
				<li><span>User Profile</span></li>
			</ol>
	
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<?php 
	if($found>0){
		$row=$sql->result;
		$profilpic="!logged-user.jpg";
		?>
		<div class="row">
			<div class="col-md-12">
				<section class="panel">
					<header class="panel-heading">
						<h2 class="panel-title">Profil Saya</h2>
					</header>
					<div class="panel-body">
						<form class="form-horizontal" action="" method="post" name="fupdateprofil" id="fupdateprofil" >
						<input type="hidden" name="a" value="up">
						<h4 class="mb-xlg">Informasi Pribadi</h4>
						<fieldset>
							<div class="form-group">
								<label class="col-md-3 control-label" for="username">Username</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username'];?>" readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label" for="nama_lengkap">Nama Lengkap</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $row['nm_lengkap'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label" for="email">Email</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label" for="">Jabatan</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="jabatan" name="jabatan" value="<?php echo $row['jabatan'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label" for="">No Telp</label>
								<div class="col-md-8">
									<input type="text" class="form-control" id="no_telp" name="no_telp" value="<?php echo $row['no_telp'];?>">
								</div>
							</div>
						</fieldset>
						<hr class="dotted">
						<h4 class="mb-xlg">Ganti Password</h4>
						<fieldset class="mb-xl">
							<div class="form-group">
								<label class="col-md-3 control-label" for="newpwd">Password Baru</label>
								<div class="col-md-8">
									<input type="password" class="form-control" id="newpwd" name="newpwd">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label" for="newpwd_repeat">Ulangi Password Baru</label>
								<div class="col-md-8">
									<input type="password" class="form-control" id="newpwd_repeat" name="newpwd_repeat">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label" for="oldpwd">Password Lama</label>
								<div class="col-md-8">
									<input type="password" class="form-control" id="oldpwd" name="oldpwd">
								</div>
							</div>
						</fieldset>
						</div>
						<div class="panel-footer">
							<div class="row">
								<div class="col-md-9 col-md-offset-3">
									<button type="submit" class="btn btn-primary" id="btn_update">Update Profile</button>
									<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
								</div>
							</div>
						</div>
					</form>
				</section>
			</div>
		</div>
	<?php
	}else{
		echo '<div class="alert alert-warning">Profil User tidak ditemukan.</div>';
	}
	?>
</section>
<?php
@include(AdminFooter);
?>