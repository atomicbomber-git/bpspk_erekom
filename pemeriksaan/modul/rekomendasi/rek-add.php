<?php 
	//redaksi isi surat
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
	if(count($produk['ikan_dilindungi'][2] ?? [])>0){
		$list_dilindungi_dilarang_ekspor = array_unique($produk['ikan_dilindungi'][2]);
	}
	if(count($produk['ikan_dilindungi'][3] ?? [])>0){
		$list_dilindungi_dilarang_ekspor = array_unique($produk['ikan_dilindungi'][2]);
	}

	if(isset($list_tidakdilindungi) && count($list_tidakdilindungi) > 0){
		$text_hiupari = implode(', ', $list_tidakdilindungi);
		$text_tidakdilindungi = " ".$text_hiupari." tidak termasuk kedalam jenis hiu/pari yang dilindungi sehingga dapat direkomendasikan peredarannya" ;
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

	$c=$sql->run("SELECT DATE(date_insert) as tgl FROM tb_hsl_periksa WHERE ref_idp ='".$idpengajuan."' ORDER BY date_insert ASC LIMIT 1");
	if($c->rowCount()>0){
		$rc=$c->fetch();
		$tgl_berlaku=tanggalIndo(date('Y-m-d',strtotime($rc['tgl'] ."+7 days")),'j F Y');
	}else{
		$tgl_berlaku="...";
	}
	//---------------

	$last=$sql->run("SELECT no_surat_rek  as no_surat FROM tb_nosurat WHERE ref_idp='".$idpengajuan."' LIMIT 1");
	$r=$last->fetch();
?>
<form method="post" class="form-horizontal" id="rek_add" action="">
	<input type="hidden" name="a" value="reksv" />
	<input type="hidden" name="token" value="<?php echo md5($idpengajuan.U_ID."rek");?>">
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Draft Surat Rekomendasi</h3>
		</div>
		<div class="box-body">
			<div class="form-group">
				<label class="control-label col-md-2">No Surat</label>
				<div class="col-md-5">
					<input type="text" readonly class="form-control" id="no_surat" name="no_surat" value="<?php echo $r['no_surat'];?>">
					<p class="text-alert alert-info">Catatan : No Surat Sudah Dibuat Secara Otomatis Oleh Sistem.</p>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Tanggal Surat</label>
				<div class="col-md-4">
					<input type="text" class="form-control datepicker" name="tgl_surat" value="<?php echo date ('m/d/Y');?>">
					 <small class="text-alert alert-danger">Format : Bulan/Hari/Tahun (mm/dd/yyyy)</small>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Perihal Surat</label>
				<div class="col-md-4">
					<input type="text" class="form-control" name="perihal" value="Rekomendasi">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Tujuan Kepada</label>
				<div class="col-md-6">
					<?php 
					$r=$sql->run("SELECT u.nama_lengkap,p.ref_iduser idu FROM tb_permohonan p JOIN tb_userpublic u ON(p.ref_iduser=u.iduser) WHERE p.idp='$idpengajuan' LIMIT 1");
					$tujuan=$r->fetch();
					?>
					<input type="hidden" readonly name="tujuan" value="<?php echo ucwords($tujuan['idu']);?>">
					<input type="text" readonly class="form-control" name="tujuan_nm" value="<?php echo ucwords($tujuan['nama_lengkap']);?>">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Tabel Hasil Permeriksaan</label>
				<div class="col-md-10 table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<td>Produk</td>
								<td width="7%">Kemasan</td>
								<td width="12%">Satuan<br>Kemasan</td>
								<td width="12%">No.Segel</td>
								<td width="10%">Berat(Kg)</td>
								<td>Keterangan</td>
							</tr>
							<tbody>
								<?php
								$dt=$sql->run("SELECT thp.tot_berat as berat, thp.kuantitas as kemasan, thp.ref_idikan, rjs.id_ref,rjs.jenis_sampel,rdi.nama_ikan,rdi.nama_latin FROM tb_hsl_periksa thp
									JOIN ref_jns_sampel rjs ON(rjs.id_ref=thp.ref_jns_sampel)
									JOIN ref_data_ikan rdi ON(rdi.id_ikan=thp.ref_idikan)
									WHERE thp.ref_idp='$idpengajuan' 
									ORDER BY ref_jns_sampel ASC");
								if($dt->rowCount()>0){
									foreach($dt->fetchAll() as $row){
									?>
									<tr class="dt">
										<td>
											<input type="hidden" name="jenis_ikan[]" value="<?php echo $row['ref_idikan'];?>">
											<input type="hidden" name="jenis_sampel[]" value="<?php echo $row['id_ref'];?>">
											<p><?php echo $arr_produk[$row['id_ref']]['nama'];?><br/>
											<?php echo $arr_ikan[$row['ref_idikan']]['nama']." <br><strong>".$arr_ikan[$row['ref_idikan']]['latin']."</strong>";?></p>
										</td>
										<td><input type="text" name="kemasan[]" class="form-control" value="<?php echo (($row['kemasan']=='0'?"":$row['kemasan']));?>"></td>
										<td><?php echo pilihan("satuan[]",$arr_satuan,"","class='form-control' id='satuan'");?></td>
										<td><input type="text" name="nosegel[]" class="form-control"></td>
										<td>
											<?php echo floatval($row['berat']);?> Kg
											<input type="hidden" name="berat[]" value="<?php echo floatval($row['berat']);?>">
										</td>
										<td><input type="text" name="keterangan[]" class="form-control" value="<?php echo $arr_produk[$row['id_ref']]['nama'];?>"></td>
									</tr>
									<?php
									}
								 }
								?>
							</tbody>
							<tfoot>
								<tr id="addrow">
									<td colspan="7">
										<p>catatan : <span class="text-alert alert-danger">Angka Desimal Menggunakan . <strong>(titik) cth: 90.2Kg</strong></span></p>
										</td>
								</tr>
							</tfoot>
						</thead>
					</table>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Redaksi<br>Surat Rekomendasi</label>
				<div class="col-md-8">
					<textarea name="redaksi_rek" rows="5" class="form-control editor">Bahwa sebagian sampel <?php echo $nama_produk;?> yang terindikasi <?php echo $redaksi;?>. Rekomendasi ini berlaku untuk satu kali pengiriman, berlaku sampai tanggal <?php echo $tgl_berlaku;?>.</textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Mengetahui Kepala/Plh</label>
				<div class="col-md-5">
					<select name="penandatgn" class="form-control sl2">
						<option value="">-Pilih-</option>
						<?php 
						$a=$sql->run("SELECT u.nm_lengkap, p.nip FROM op_user u JOIN op_pegawai p ON(u.ref_idpeg=p.idp) WHERE u.lvl IN(91,90) AND u.status='2' ");
						if($a->rowCount()>0){
							foreach($a->fetchAll() as $b){
								echo '<option value="'.$b['nip'].'">'.$b['nm_lengkap'].' ('.$b['nip'].')</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Tembusan Balai Karantina</label>
				<div class="col-md-4">
					<select name="tembusan_bk" class="form-control sl2">
						<option value="">-Pilih-</option>
						<?php 
						$sql->get_all('ref_balai_karantina',array(),array('idbk','nama'));
						if($sql->num_rows>0){
							foreach($sql->result as $b){
								echo '<option value="'.$b['idbk'].'">'.$b['nama'].'</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">Tembusan PSDKP</label>
				<div class="col-md-4">
					<select name="tembusan_psdkp" class="form-control sl2">
						<option value="">-Pilih-</option>
						<?php 
						$sql->get_all('ref_psdkp',array('isDelete'=>0),array('id_psd','nama'));
						if($sql->num_rows>0){
							foreach($sql->result as $c){
								echo '<option value="'.$c['id_psd'].'">'.$c['nama'].'</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-2">UPT PRL Penerima</label>
				<div class="col-md-4">
					<select name="upt_prl_penerima" class="form-control sl2">
						<option value="">-Pilih-</option>
						<?php 
						$sql->get_all('ref_upt_prl',array('isDelete'=>0),array('id_upt','nama'));
						if($sql->num_rows>0){
							foreach($sql->result as $c){
								echo '<option value="'.$c['id_upt'].'">'.$c['nama'].'</option>';
							}
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="form-group">
				<label class="control-label col-md-2"></label>
				<div class="col-md-5">
					<button class="btn btn-sm btn-primary btn-flat" id="btn_simpan" type="submit">Simpan</button>
					<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
					
				</div>
			</div>
		</div>
	</div>
</form>