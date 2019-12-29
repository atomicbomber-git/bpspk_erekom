<?php

use Jenssegers\Date\Date;

?>

<table>
	<tbody>
		<tr>
			<td> Tanggal Pemeriksaan </td>
			<td> <?= Date::create($permohonan->pemeriksaan->tgl_periksa)->format("j F Y") ?> </td>
		</tr>

		<?php foreach($permohonan->petugas ?? [] as $index => $petugas): ?>
			<tr>
				<td width="35%"> Petugas Pemeriksa <?= $index + 1 ?> </td>
				<td>
					<?= $petugas->pegawai->nm_lengkap ?> (<?= $petugas->pegawai->nip ?? '-' ?>)
				</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>

<table style="width:100%" class="table table-bordered table-hasil">
	<caption>
		<h4>
			Tabel Hasil Pemeriksaan Sampel
		</h4>
	</caption>
	
	<thead>
		<tr>
			<td rowspan="2"> No </td>
			<td rowspan="2"> Jenis Produk </td>
			<td rowspan="2"> Jenis Ikan </td>
			<td colspan="3"> Sampel (Terkecil/Terbesar) </td>
			<td rowspan="2"> Berat Total<br>(Kg) </td>
			<td rowspan="2"> Jlh Kemasan  </td>
			<td rowspan="2"> Keterangan </td>
		</tr>
		<tr>
			<td>Panjang<br>(Cm)</td>
			<td>Lebar<br>(Cm)</td>
			<td>Berat<br>(Kg)</td>
		</tr>
	</thead>

	<tbody>
		<?php foreach($permohonan->hasil_periksa as $index => $hasil_periksa_line): ?>
			<tr>
				<td> <?= $index + 1 ?> </td>
				<td>
					<?= $hasil_periksa_line->produk ?> - 
					<?= $hasil_periksa_line->kondisi_produk ?> - 
					<?= $hasil_periksa_line->jenis_produk ?> 
				</td>
				<td> <?= $hasil_periksa_line->data_ikan->nama_ikan ?> </td>
				<td> <?= $hasil_periksa_line->pjg ?> </td>
				<td> <?= $hasil_periksa_line->lbr ?> </td>
				<td> <?= $hasil_periksa_line->berat ?> </td>
				<td> <?= $hasil_periksa_line->tot_berat ?> </td>
				<td>
					<?= $hasil_periksa_line->kuantitas ?>
					<?= $hasil_periksa_line->satuan_barang->nama ?>
				</td>
				<td> <?= $hasil_periksa_line->ket ?> </td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>

<pagebreak>