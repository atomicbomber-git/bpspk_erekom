<?php
include ("pemeriksaan/engine/render.php");

$kdsurat=$_GET['nomor'];
if($kdsurat==""){
	exit();
}

if(!ctype_digit($kdsurat)){
	exit();
}

?>
<html>
	<head>
		<meta charset="utf-8">
		<title>E-Rekomendasi</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css" media="screen" />
		<link rel="stylesheet" href="assets/stylesheets/index-home.css?s=23" media="screen" />
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,600,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">
	</head>
	<body>
		<div class="row">
			<div class="col-md-12">
				<div class="hasil" style="max-width:500px;text-align:center;margin: 50px auto">
				<?php
				$q=$sql->query("SELECT tr.no_surat,tr.tgl_surat,tu.nama_lengkap,tp.tgl_pengajuan,tp.tujuan FROM tb_rekomendasi tr 
					JOIN tb_permohonan tp ON (tr.ref_idp=tp.idp) 
					JOIN tb_userpublic tu ON (tr.ref_iduser=tu.iduser)
					WHERE tr.kode_surat='$kdsurat' LIMIT 1
					");
				if($q->rowCount()>0){
					$dt=$q->fetch();
					echo '<center><h4><strong>Surat Rekomendasi Ditemukan.<strong></h4></center>';
					?>
					<table class="table" width="100%">
						<tr>
							<td>Nomor Surat</td>
							<td>: </td>
							<td><?php echo $dt['no_surat'];?></td>
						</tr>
						<tr>
							<td>Tanggal Surat</td>
							<td>: </td>
							<td><?php echo tanggalIndo($dt['tgl_surat'],'j F Y');?></td>
						</tr>
						<tr>
							<td>Pemohon</td>
							<td>: </td>
							<td><?php echo $dt['nama_lengkap'];?></td>
						</tr>
						<tr>
							<td>Tujuan</td>
							<td>: </td>
							<td><?php echo $dt['tujuan'];?></td>
						</tr>
						<tr>
							<td>Tanggal Pengajuan</td>
							<td>: </td>
							<td><?php echo tanggalIndo($dt['tgl_pengajuan'],'j F Y');?></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align:center;"><a href="download.php?surat=<?php echo $kdsurat;?>&token=<?php echo md5('download'.$kdsurat.'public');?>" class="btn btn-sm btn-danger">Download Surat</a></td>
						</tr>
					</table>
					<?php
				}else{
					echo '<center><h4>Maaf, Surat Tidak ditemukan.</h4></center>';
				}
				?>
				</div>
			</div>
		</div>
	</body>
	<script src="assets/vendor/jquery/jquery.min.js"></script>
	<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
		
	});
	</script>

</html>
