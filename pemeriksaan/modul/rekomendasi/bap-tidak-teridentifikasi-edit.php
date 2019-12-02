<?php

/* Controller code */

use App\Models\Permohonan;
use Jenssegers\Date\Date;

$permohonan = Permohonan::find($idpengajuan);
$permohonan->load("petugas.pegawai");
$permohonan->load("berita_acara_pemeriksaan_tidak_teridentifikasi");

$l = $sql->run("SELECT thp.ref_idp, thp.ref_idikan, thp.ref_jns_sampel, rdi.nama_ikan,rdi.nama_latin,rdi.dilindungi,rdi.peredaran,rdi.ket_dasarhukum, rjs.jenis_sampel
		FROM tb_hsl_periksa thp
		LEFT JOIN ref_data_ikan rdi ON (rdi.id_ikan = thp.ref_idikan)
		LEFT JOIN ref_jns_sampel rjs ON (rjs.id_ref = thp.ref_jns_sampel)
		WHERE ref_idp ='$idpengajuan'
		ORDER BY rdi.dilindungi DESC, rdi.nama_ikan ASC  ");
$produk = array();

foreach ($l->fetchAll() as $prd) {
	$dasar_hukum = "";
	if ($prd['dilindungi'] == '1') {
		if ($prd['ket_dasarhukum'] != "") {
			$dasar_hukum = "sesuai dengan " . $prd['ket_dasarhukum'];
		}
		$produk['ikan_dilindungi'][$prd['peredaran']][] = $prd['nama_ikan'] . "(" . $prd['nama_latin'] . ") " . $dasar_hukum;
	} else {
		$produk['ikan_takdilindungi'][$prd['peredaran']][] = $prd['nama_ikan'] . "(" . $prd['nama_latin'] . ")";
	}

	$produk['jns_produk'][] = $prd['jenis_sampel'];
}

$sampel = array_unique($produk['jns_produk'] ?? []);
$nama_produk = implode(', ', $sampel);

$list_tidakdilindungi = ($produk['ikan_takdilindungi'][1]);
$list_dilindungi_dilarang_ekspor = ($produk['ikan_dilindungi'][2]);
$list_dilindungi_penuh = ($produk['ikan_dilindungi'][3]);
if (count($produk['ikan_takdilindungi'][1] ?? []) > 0) {
	$list_tidakdilindungi = array_unique($produk['ikan_takdilindungi'][1] ?? []);
}
if (count($produk['ikan_dilindungi'][2] ?? []) > 0) {
	$list_dilindungi_dilarang_ekspor = array_unique($produk['ikan_dilindungi'][2] ?? []);
}
if (count($produk['ikan_dilindungi'][3] ?? []) > 0) {
	$list_dilindungi_dilarang_ekspor = array_unique($produk['ikan_dilindungi'][2] ?? []);
}


if (isset($list_tidakdilindungi) && count($list_tidakdilindungi ?? []) > 0) {
	$text_hiupari = implode(', ', $list_tidakdilindungi);
	$text_tidakdilindungi = " " . $text_hiupari . " tidak termasuk kedalam jenis hiu/pari yang dilindungi";
} else {
	$text_tidakdilindungi = "";
}

if (isset($list_dilindungi_dilarang_ekspor) && count($list_dilindungi_dilarang_ekspor) > 0) {
	$text_dilindungi_dilarangekspor = "";
	$text_hiupari2 = implode(', ', $list_dilindungi_dilarang_ekspor);
	if ($text_tidakdilindungi != "") {
		$text_dilindungi_dilarangekspor .= ". Sedangkan ";
	}
	$text_dilindungi_dilarangekspor .= " " . $text_hiupari2 . ", termasuk kedalam jenis yang perizinannya terbatas hanya untuk peredaran dalam negeri";
} else {
	$text_dilindungi_dilarangekspor = "";
}

if (isset($list_dilindungi_penuh) && count($list_dilindungi_penuh) > 0) {
	$text_dilindungi_penuh = "";
	$text_hiupari3 = implode(', ', $list_dilindungi_penuh);
	if ($text_tidakdilindungi != "" or $text_dilindungi_dilarangekspor != "") {
		$text_dilindungi_penuh .= ". Sedangkan ";
	}
	$text_dilindungi_penuh = " " . $text_hiupari3 . ", termasuk kedalam jenis yang dilindungi penuh sehingga peredarannya dilarang";
} else {
	$text_dilindungi_penuh = "";
}

$redaksi = $text_tidakdilindungi . " " . $text_dilindungi_dilarangekspor . " " . $text_dilindungi_penuh;

$last = $sql->run("SELECT no_surat_bap as no_surat FROM tb_nosurat WHERE ref_idp='" . $idpengajuan . "' LIMIT 1");
$r = $last->fetch();
?>
<form method="post" class="form-horizontal" id="bap_add" action="">
	<input type="hidden" name="a" value="bapttup" />
	<input type="hidden" name="token" value="<?php echo md5($idpengajuan . U_ID . "bap"); ?>">

	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Berita Acara Pemeriksaan</h3>
		</div>
		<div class="box-body">
			<div class="form-group">
				<label class="control-label col-md-3">No Surat</label>
				<div class="col-md-5">
					<input type="text" readonly class="form-control" name="no_surat" id="no_surat" value="<?= $permohonan->berita_acara_pemeriksaan_tidak_teridentifikasi->no_surat ?>">
					<p class="text-alert alert-info">Catatan : No Surat Sudah Dibuat Secara Otomatis Oleh Sistem.</p>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Tanggal Penetapan</label>
				<div class="col-md-4">
					<input 
                        type="text"
                        class="form-control datepicker"
                        name="tgl_penetapan"
                        value="<?= Date::parse($permohonan->berita_acara_pemeriksaan_tidak_teridentifikasi->tgl_surat)->format("d/m/Y") ?>">
					<small class="text-alert alert-danger">Format : Bulan/Hari/Tahun (mm/dd/yyyy)</small>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Lokasi Penetapan</label>
				<div class="col-md-6">
					<input type="text" 
                        value="<?= $permohonan->berita_acara_pemeriksaan_tidak_teridentifikasi->lokasi ?>"
                        class="form-control" name="lokasi_penetapan">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Redaksi Hasil Pemeriksaan</label>
				<div class="col-md-8">
                    <textarea name="redaksi_bap" rows="5" class="editor form-control">
                        <?= $permohonan->berita_acara_pemeriksaan_tidak_teridentifikasi->redaksi ?>
                    </textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Petugas 1</label>
				<div class="col-md-6">
					<select name="ptg1" class="form-control">
						<option value="">-Pilih-</option>

						<?php foreach($permohonan->petugas as $petugas): ?>
							<option 
                                value="<?= $petugas->ref_idpeg ?>"
                                <?= ($petugas->ref_idpeg == $permohonan->berita_acara_pemeriksaan_tidak_teridentifikasi->ptgs1) ? 
                                    "selected" : 
                                    ""
                                ?>
                                >
								<?= $petugas->pegawai->nm_lengkap ?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-md-3">Petugas 2</label>
				<div class="col-md-6">
					<select name="ptg2" class="form-control">
						<option value="">-Pilih-</option>

						<?php foreach($permohonan->petugas as $petugas): ?>
							<option 
                                value="<?= $petugas->ref_idpeg ?>"
                                <?= ($petugas->ref_idpeg == $permohonan->berita_acara_pemeriksaan_tidak_teridentifikasi->ptgs2) ? 
                                    "selected" : 
                                    ""
                                ?>
                                >
								<?= $petugas->pegawai->nm_lengkap ?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Petugas 3</label>
				<div class="col-md-6">
					<select name="ptg3" class="form-control">
						<option value="">-Pilih-</option>

						<?php foreach($permohonan->petugas as $petugas): ?>
							<option 
                                <?= ($petugas->ref_idpeg == $permohonan->berita_acara_pemeriksaan_tidak_teridentifikasi->ptgs3) ? 
                                    "selected" : 
                                    ""
                                ?>
                                value="<?= $petugas->ref_idpeg ?>">
								<?= $petugas->pegawai->nm_lengkap ?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-md-3">Petugas 4</label>
				<div class="col-md-6">
					<select name="ptg4" class="form-control">
						<option value="">-Pilih-</option>

						<?php foreach($permohonan->petugas as $petugas): ?>
							<option 
                                value="<?= $petugas->ref_idpeg ?>"
                                <?= ($petugas->ref_idpeg == $permohonan->berita_acara_pemeriksaan_tidak_teridentifikasi->ptgs4) ? 
                                    "selected" : 
                                    ""
                                ?>
                                >
								<?= $petugas->pegawai->nm_lengkap ?>
							</option>
						<?php endforeach ?>
					</select>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="form-group">
				<label class="control-label col-md-3"></label>
				<div class="col-md-5">
					<button class="btn btn-sm btn-primary btn-flat" type="submit">Simpan</button>
					<a class="btn btn-sm btn-info btn-flat" href="surat-bap.php?token=<?php echo md5($row['id_bap'].U_ID.'surat_bap');?>&bap=<?php echo base64_encode($row['id_bap']);?>">Lihat Surat</a>
					<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>

				</div>
			</div>
		</div>
	</div>
</form>