<?php 
	$l=$sql->run("SELECT thp.ref_idp, thp.ref_idikan, thp.ref_jns_sampel, rdi.nama_ikan,rdi.nama_latin,rdi.dilindungi,rdi.peredaran,rdi.ket_dasarhukum, rjs.jenis_sampel
		FROM tb_hsl_periksa thp
		LEFT JOIN ref_data_ikan rdi ON (rdi.id_ikan = thp.ref_idikan)
		LEFT JOIN ref_jns_sampel rjs ON (rjs.id_ref = thp.ref_jns_sampel)
		WHERE ref_idp ='$idpengajuan'
		ORDER BY rdi.dilindungi DESC, rdi.nama_ikan ASC  ");
	$produk=array();
	
	foreach($l->fetchAll() as $prd){
		$dasar_hukum="";
		if($prd['dilindungi']=='1'){
			if($prd['ket_dasarhukum']!=""){
				$dasar_hukum="sesuai dengan ".$prd['ket_dasarhukum'];
			}
			$produk['ikan_dilindungi'][$prd['peredaran']][]=$prd['nama_ikan']."(".$prd['nama_latin'].") ".$dasar_hukum;
		}else{
			$produk['ikan_takdilindungi'][$prd['peredaran']][]=$prd['nama_ikan']."(".$prd['nama_latin'].")";
		}
		
		$produk['jns_produk'][]=$prd['jenis_sampel'];
	}

	$sampel=array_unique($produk['jns_produk']);
	$nama_produk = implode(', ', $sampel);

	$list_tidakdilindungi = ($produk['ikan_takdilindungi'][1]);
	$list_dilindungi_dilarang_ekspor = ($produk['ikan_dilindungi'][2]);
	$list_dilindungi_penuh = ($produk['ikan_dilindungi'][3]);
	if(count($produk['ikan_takdilindungi'][1])>0){
		$list_tidakdilindungi = array_unique($produk['ikan_takdilindungi'][1]);
	}
	if(count($produk['ikan_dilindungi'][2])>0){
		$list_dilindungi_dilarang_ekspor = array_unique($produk['ikan_dilindungi'][2]);
	}
	if(count($produk['ikan_dilindungi'][3])>0){
		$list_dilindungi_dilarang_ekspor = array_unique($produk['ikan_dilindungi'][2]);
	}

	if(isset($list_tidakdilindungi) && count($list_tidakdilindungi > 0)){
		$text_hiupari = implode(', ', $list_tidakdilindungi);
		$text_tidakdilindungi = " ".$text_hiupari." tidak termasuk kedalam jenis hiu/pari yang dilindungi" ;
	}else{
		$text_tidakdilindungi = "";
	}

	if(isset($list_dilindungi_dilarang_ekspor) && count($list_dilindungi_dilarang_ekspor)> 0 ){
		$text_dilindungi_dilarangekspor ="";
		$text_hiupari2 = implode(', ', $list_dilindungi_dilarang_ekspor);
		if($text_tidakdilindungi!=""){
			$text_dilindungi_dilarangekspor .=". Sedangkan ";
		}
		$text_dilindungi_dilarangekspor .= " ".$text_hiupari2.", termasuk kedalam jenis yang perizinannya terbatas hanya untuk peredaran dalam negeri";
	}else{
		$text_dilindungi_dilarangekspor ="";
	}

	if(isset($list_dilindungi_penuh) && count($list_dilindungi_penuh)> 0 ){
		$text_dilindungi_penuh ="";
		$text_hiupari3 = implode(', ', $list_dilindungi_penuh);
		if($text_tidakdilindungi!="" OR $text_dilindungi_dilarangekspor!=""){
			$text_dilindungi_penuh .=". Sedangkan ";
		}
		$text_dilindungi_penuh = " ".$text_hiupari3.", termasuk kedalam jenis yang dilindungi penuh sehingga peredarannya dilarang";
	}else{
		$text_dilindungi_penuh ="";
	}

	$redaksi = $text_tidakdilindungi." ".$text_dilindungi_dilarangekspor." ".$text_dilindungi_penuh;

	/*$l=$sql->run("SELECT DISTINCT(rdi.nama_ikan) nama_ikan FROM tb_hsl_periksa thp JOIN ref_data_ikan rdi ON (rdi.id_ikan=thp.ref_idikan) WHERE thp.ref_idp='$idpengajuan'");
	$barang=array();
	foreach($l->fetchAll() as $brg){
		$barang[]=$brg['nama_ikan'];
	}
	$list_brg=implode(', ', $barang);*/
	$last=$sql->run("SELECT no_surat_bap as no_surat FROM tb_nosurat WHERE ref_idp='".$idpengajuan."' LIMIT 1");
	$r=$last->fetch();
?>
<form method="post" class="form-horizontal" id="bap_add" action="">
	<input type="hidden" name="a" value="bapsv" />
	<input type="hidden" name="token" value="<?php echo md5($idpengajuan.U_ID."bap");?>">
	<input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>">
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions">
						<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
					</div>
					<h2 class="panel-title">Berita Acara Pemeriksaan</h2>
				</header>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label col-md-3">No Surat</label>
						<div class="col-md-5">
							<input type="text" class="form-control" name="no_surat" id="no_surat" value="<?php echo $r['no_surat'];?>">
							<p class="text-alert alert-info">Catatan : No Surat Sudah Dibuat Secara Otomatis Oleh Sistem.</p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Tanggal Penetapan</label>
						<div class="col-md-4">
							<input type="text" class="form-control" name="tgl_penetapan" data-plugin-datepicker data-date-orientation="top">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Lokasi Penetapan</label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="lokasi_penetapan">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Redaksi Hasil Pemeriksaan</label>
						<div class="col-md-8">
							<textarea name="redaksi_bap" rows="5" class="editor form-control">Berdasarkan hasil pemeriksaan sampel <?php echo $nama_produk;?> yang dilakukan secara uji visual, menunjukkan bahwa sampel <?php echo $redaksi;?>.</textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Petugas 1</label>
						<div class="col-md-6">
							<select name="ptg1" class="form-control">
								<option value="">-Pilih-</option>
								<?php 
								$a1=$sql->run("SELECT pl.ref_idpeg,p.nip,p.nm_lengkap FROM tb_petugas_lap pl JOIN op_pegawai p ON(p.idp=pl.ref_idpeg) WHERE pl.ref_idp='".$idpengajuan."' ");
								if($a1->rowCount()>0){
									foreach($a1->fetchAll() as $b1){
										echo '<option value="'.$b1['ref_idpeg'].'">'.$b1['nm_lengkap'].' ('.$b1['nip'].')</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Petugas 2</label>
						<div class="col-md-6">
							<select name="ptg2" class="form-control">
								<option value="">-Pilih-</option>
								<?php 
								$a2=$sql->run("SELECT pl.ref_idpeg,p.nip,p.nm_lengkap FROM tb_petugas_lap pl JOIN op_pegawai p ON(p.idp=pl.ref_idpeg) WHERE pl.ref_idp='".$idpengajuan."' ");
								if($a2->rowCount()>0){
									foreach($a2->fetchAll() as $b2){
										echo '<option value="'.$b2['ref_idpeg'].'">'.$b2['nm_lengkap'].' ('.$b2['nip'].')</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="form-group">
						<label class="control-label col-md-3"></label>
						<div class="com-md-5">
							<button class="btn btn-sm btn-primary" type="submit">Simpan</button>
							<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
							
						</div>
					</div>
				</footer>
			</section>
		</div>
	</div>
</form>