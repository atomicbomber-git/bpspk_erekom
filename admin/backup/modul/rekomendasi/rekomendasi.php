<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav2').addClass('nav-active');
	$('#btn_add_data').click(function(){
		var tr    = $('tr.row_clone:first');
	    var clone = tr.clone();
	    clone.find(':text').val('');
	    clone.find('input').prop('disabled', false);
	    clone.find('select').prop('disabled', false);
	    clone.find('textarea').prop('disabled', false);
	    clone.show();
	    $('tr.row_clone:last').after(clone);

	    clone.find('.del_thisrow').on('click', function(e) {
			e.preventDefault();
			$(this).closest('tr').remove();
		});
	});

	$('.del_thisrow2').click(function(e) {
		e.preventDefault();
		$(this).closest('tr').remove();
	});
});
</script>
<script src=\"js-rekomendasi.js\"></script>
";

$idpengajuan=base64_decode($_GET['data']);
if(ctype_digit($idpengajuan)){
	include "function.php";
	$arr_satuan=array(
		""=>"-Pilih-",
		"Colly"=>"Colly",
		"Container"=>"Container",
		"Truk"=>"Truk",
		"Ekor"=>"Ekor"
		);
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Draft Surat Rekomendasi</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><a href="./pemeriksaan-sample.php"><span>Pemeriksaan Sampel</span></a></li>
				<li><span>Draft Surat Rekomendasi</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<?php
	
	$found=$sql->get_count('tb_rekomendasi',array('ref_idp'=>$idpengajuan));
	if($found>0){
		include ("rek-edit.php");
	}else{
		include ("rek-add.php");
	}
	
	?>
</section>
<?php
}
include(AdminFooter);
?>
