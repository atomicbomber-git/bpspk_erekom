<?php
use App\Services\Letter;

require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('ul li.nav-rek').addClass('active');
});
</script>
<script src=\"custom-2.js\"></script>
";

$idrek=base64_decode($_GET['rek']);
if(!ctype_digit($idrek)){
	exit();
}

if($_GET['token']!=md5($idrek.U_ID.'surat_rekomendasi')){
	exit();
}

//load data surat rekomendasi
$rek=$sql->run("SELECT tr.*, tp.tgl_pengajuan, tp.tujuan, tp.jenis_angkutan, tu.nama_lengkap, tb.no_surat nobap, tb.tgl_surat tglbap, op.nm_lengkap penandatgn, op.jabatan, op.ttd,ou.lvl 
FROM tb_rekomendasi tr
JOIN tb_permohonan tp ON (tr.ref_idp=tp.idp)
JOIN tb_userpublic tu ON (tu.iduser=tr.ref_iduser)
JOIN tb_bap tb ON (tp.idp=tb.ref_idp)
JOIN op_pegawai op ON(tr.pnttd=op.nip)
JOIN op_user ou ON(ou.ref_idpeg=op.idp)
WHERE tr.idrek='".$idrek."' LIMIT 1");

$row=$rek->fetch();
?>
<body class="hold-transition skin-blue sidebar-mini">

<div class="content-wrapper">
	<section class="content-header">
		<h1>
		Surat Rekomendasi
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo c_URL;?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="./input-rekomendasi.php">Draft Surat Rekomendasi</a></li>
			<li class="active">Preview Surat Rekomendasi</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<section class="panel">
					<div class="panel-body">
					<?= container(Letter::class)->getHeaderContentHTML(ADM_IMAGES) ?>
						<br/>
						<table style="width:100%">
							<tr>
								<td>Nomor</td>
								<td>: <?php echo $row['no_surat'];?></td>
								<td style="text-align:right"><?php echo tanggalIndo($row['tgl_surat'],'j F Y');?></td>
							</tr>
							<tr>
								<td>Perihal</td>
								<td>: <?php echo $row['perihal'];?></td>
								<td></td>
							</tr>
						</table>
						<table style="width:100%">
							<tr>
								<td>
								<br>Kepada
								<br>Yth. <?php echo $row['nama_lengkap'];?>
								<br>di -
								<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tempat</td>
								<td style="text-align:right"></td>
							</tr>
						</table>
						<table style="width:100%">
							<tr>
								<td><br>
								<p>Menindaklanjuti Surat Saudara tanggal <?php echo tanggalIndo($row['tgl_pengajuan'],'j F Y');?> perihal permohonan rekomendasi untuk lalu lintas Hiu/Pari ke <?php echo ucwords($row['tujuan']);?> melalui jalur <?php echo ucwords($row['jenis_angkutan']);?>, dengan ini disampaikan bahwa Petugas Balai Pengelolaan Sumberdaya Pesisir dan Laut Pontianak telah melakukan identifikasi yang tertuang dalam Berita Acara Nomor : <?php echo $row['nobap'];?> tanggal <?php echo tanggalIndo($row['tglbap'],'j F Y');?> dengan hasil:</p>
								</td>
							</tr>
						</table>
						<table style="width:100%" class="table table-bordered" >
							<tr>
								<td width="5%">No</td>
								<td>Jenis Ikan</td>
								<td width="12%">Kemasan</td>
								<td width="12%">No.Segel</td>
								<td width="12%">Berat Ikan(Kg)</td>
								<td>Keterangan</td>
							</tr>
                            
							<?php
                                $dt
                                    =
                                        $sql
                                            ->run("SELECT thp.*, rjs.jenis_sampel, rdi.nama_latin FROM tb_rek_hsl_periksa thp LEFT JOIN ref_jns_sampel rjs ON (rjs.id_ref=thp.ref_jns) LEFT JOIN ref_data_ikan rdi ON(rdi.id_ikan=thp.ref_idikan) WHERE thp.ref_idrek='".$idrek."' ORDER BY thp.ref_jns ASC"
                        
                        
                            );
							if($dt->rowCount()>0){
								$no=0;
								foreach($dt->fetchAll() as $dtrow){
									$no++;
									?>
									<tr>
										<td width="5%"><?php echo $no;?></td>
										<td><em><?php echo $dtrow['nama_latin'];?></em></td>
										<td><?php echo $dtrow['kemasan']." ".$dtrow['satuan'];?></td>
										<td><?php echo $dtrow['no_segel'];?></td>
										<td><?php echo (($dtrow['berat']=='0.00')?"":$dtrow['berat']);?></td>
										<td><?php echo $dtrow['keterangan'];?></td>
									</tr>
									<?php
								}
							}
							?>
						</table>
						<table style="width:100%">
							<tr>
								<td><br><p><?php echo $row['redaksi'];?></p></td>
							</tr>
							<tr>
								<td><p>Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.</p></td>
							</tr>
						</table>
						<?php
						$tmb=$sql->run("SELECT rbk.nama FROM tb_rekomendasi tr JOIN ref_balai_karantina rbk ON(rbk.idbk=tr.ref_bk) WHERE tr.ref_idp='".$row['ref_idp']."' LIMIT 1");
						$karantina=$tmb->fetch();
						?>
						<table style="width:100%">
							<tr>
								<td width="60%"></td>
								<td width="60%" style="text-align:center">
									<?php echo (($row['lvl']==90)?"Kepala Balai":"Plh. Kepala Balai");?>
									<p><a href="#"><img height="100px" src="<?php echo ADM_IMAGES.$row['ttd'];?>"></a></p>
									<?php echo $row['penandatgn'];?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
								Tembusan:
								<ol>
									<li>Direktur Jenderal PRL</li>
									<li>Direktur Konservasi Keanekaragaman dan Hayati Laut</li>
									<li>Kepala <?php echo $karantina['nama'];?></li>
								</ol>
								</td>
							</tr>
						</table>
					</div>
					<footer class="panel-footer">
						<div class="form-group">
							<a target="_blank" href="download-rekomendasi.php?rek=<?php echo $row['kode_surat'];?>&token=<?php echo md5($row['kode_surat'].U_ID.'dwsurat_rekomendasi');?>" class="btn btn-sm btn-primary">Download PDF </a>
						</div>
					</footer>
				</section>
			</div>
		</div>
	</section>

</div>
</body>
<?php
include(AdminFooter);
?>
