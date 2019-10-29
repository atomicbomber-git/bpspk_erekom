<?php

include("../../engine/render.php");

if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
	die();
}

if ($_POST) {
	include("../../engine/render.php");
	switch (trim(strip_tags($_POST['a']))) {
		case 'pr': //pengajuan rekomendasi
			if ($_POST['nm_penerima'] == "") {
				echo json_encode(array("stat" => false, "msg" => "Nama Penerima Harus Diisi."));
				exit();
			}

			if ($_POST['alamat_penerima'] == "") {
				echo json_encode(array("stat" => false, "msg" => "Alamat Penerima Harus Diisi."));
				exit();
			}

			if ($_POST['alat_angkut'] == "") {
				echo json_encode(array("stat" => false, "msg" => "Jenis Alat Angkut Harus Dipilih."));
				exit();
			}

			if ($_POST['alamat_gudang'] == "") {
				echo json_encode(array("stat" => false, "msg" => "Alamat Gudang Harus diisi."));
				exit();
			}

			if ($_POST['nm_brg'][0] == "") {
				echo json_encode(array("stat" => false, "msg" => "Minimal Menginputkan 1 Jenis Barang."));
				exit();
			}

			$tgl_pengajuan = date('Y-m-d H:i:s');
			$tgl_pelayanan = get_tgl_pelayanan($tgl_pengajuan);

			$no_antrian = $sql->get_count('tb_permohonan', array('tgl_pelayanan' => $tgl_pelayanan)) + 1;
			$format_noantrian = tanggalIndo($tgl_pelayanan, 'dm') . "-" . sprintf("%03d", $no_antrian);

			$arr_insert = array(
				'ref_iduser' => U_ID,
				'ref_satker' => 0,
				'tgl_pengajuan' => $tgl_pengajuan,
				'penerima' => $_POST['nm_penerima'],
				'alamat_gudang' => $_POST['alamat_gudang'],
				'ket_tambahan' => $_POST['ket'],
				'tujuan' => $_POST['alamat_penerima'],
				'jenis_angkutan' => $_POST['alat_angkut'],
				'jenis_tujuan' => $_POST['jenis_tujuan'],
				'status' => 1,
				'log_u' => '',
				'log_p' => '',
				'tgl_pelayanan' => $tgl_pelayanan,
				'no_antrian' => $no_antrian
			);

			$sql->insert('tb_permohonan', $arr_insert);
			if ($sql->error == null) {
				$idpermohonan = $sql->insert_id;
				for ($x = 0; $x < count($_POST['nm_brg']); $x++) {
					\App\Models\Barang::create([
						'ref_idphn' => $idpermohonan,
						'nm_barang' => $_POST['nm_brg'][$x],
						'kuantitas' => $_POST['kuantitas'][$x],
						'id_satuan_kuantitas' => $_POST['id_satuan_kuantitas'][$x],
						'jlh' => $_POST['jlh'][$x],
						'asal_komoditas' => $_POST['asal_komoditas'][$x],
						'date_input' => date('Y-m-d H:i:s')
					]);
				}
				echo json_encode(array("stat" => true, "msg" => "Data Berhasil Dikirim, Akan Segera Diproses Oleh Admin."));
				//---------------email notifikasi ke admin-------------------------
				require '../../../assets/phpmailer/PHPMailerAutoload.php';
				$sql->get_all('op_user', array('lvl' => 100), array('nm_lengkap', 'email'));
				if ($sql->num_rows > 0) {
					foreach ($sql->result as $ad) {
						$isi = "<p>" . $ad['nm_lengkap'] . ", Terdapat Pengajuan Permohonan Rekomendasi perlu diperiksa.</p>";
						$isi .= "<table border='0'>
							<tr>
								<td>Pemohon</td>
								<td>: " . U_NAME . "</td>
							</tr>
							<tr>
								<td>Ditujukan Kepada</td>
								<td>: " . $_POST['nm_penerima'] . "</td>
							</tr>
							<tr>
								<td>Alamat</td>
								<td>: " . $_POST['alamat_penerima'] . "</td>
							</tr>
							<tr>
								<td>No Antrian</td>
								<td>: " . $format_noantrian . "</td>
							</tr>
						</table>";
						$isi .= "<p>Demikian Pemberitahuan Ini kami sampaikan, atas perhatiannya kami ucapkan terima kasih.</p>";
						$arr = array(
							"send_to" => $ad['email'],
							"send_to_name" => $ad['nm_lengkap'],
							"subject_email" => "Permohonan Rekomendasi - LPSPL Serang",
							"isi_email" => $isi
						);
							/* sendMail($arr) */;
					}
				}
				//---------------------------------------------
				exit();
			} else {
				echo json_encode(array("stat" => false, "msg" => "Aksi Gagal."));
				exit();
			}
			break;

		case 'up': //update pengajuan rekomendasi
			$idp = base64_decode($_POST['idp']);
			$token = $_POST['token'];

			if (!ctype_digit($idp)) {
				echo json_encode(array("stat" => false, "msg" => "Invalid Request"));
				exit();
			}
			if ($token != md5(U_ID . $idp . 'upphn' . date('Y-m-d'))) {
				echo json_encode(array("stat" => false, "msg" => "Invalid Request"));
				exit();
			}

			if ($_POST['nm_penerima'] == "") {
				echo json_encode(array("stat" => false, "msg" => "Nama Penerima Harus Diisi."));
				exit();
			}

			if ($_POST['alamat_penerima'] == "") {
				echo json_encode(array("stat" => false, "msg" => "Alamat Penerima Harus Diisi."));
				exit();
			}

			if ($_POST['alat_angkut'] == "") {
				echo json_encode(array("stat" => false, "msg" => "Jenis Alat Angkut Harus Dipilih."));
				exit();
			}

			if ($_POST['alamat_gudang'] == "") {
				echo json_encode(array("stat" => false, "msg" => "Alamat Gudang Harus diisi."));
				exit();
			}

			if ($_POST['nm_brg'][0] == "") {
				echo json_encode(array("stat" => false, "msg" => "Minimal Menginputkan 1 Jenis Barang."));
				exit();
			}

			$arr_update = array(
				'penerima' => $_POST['nm_penerima'],
				'alamat_gudang' => $_POST['alamat_gudang'],
				'ket_tambahan' => $_POST['ket'],
				'tujuan' => $_POST['alamat_penerima'],
				'jenis_angkutan' => $_POST['alat_angkut'],
				'jenis_tujuan' => $_POST['jenis_tujuan'],
				'status' => 1
			);
			$sql->update('tb_permohonan', $arr_update, array('idp' => $idp));
			if ($sql->error == null) {
				$sql->delete('tb_barang', array('ref_idphn' => $idp));

				for ($x = 0; $x < count($_POST['nm_brg']); $x++) {

					\App\Models\Barang::create([
						'ref_idphn' => $idp,
						'nm_barang' => $_POST['nm_brg'][$x],
						'kuantitas' => $_POST['kuantitas'][$x],
						'id_satuan_kuantitas' => $_POST['id_satuan_kuantitas'][$x],
						'jlh' => $_POST['jlh'][$x],
						'asal_komoditas' => $_POST['asal_komoditas'][$x],
						'date_input' => date('Y-m-d H:i:s')
					]);
				}

				echo json_encode(array("stat" => true, "msg" => "Data Berhasil Dikirim, Akan Segera Diproses Oleh Admin."));
				exit();
			} else {
				echo json_encode(array("stat" => false, "msg" => "Aksi Gagal."));
				exit();
			}

			break;

		default:
			echo json_encode(array("stat" => false, "msg" => "Invalid Request"));
			break;
	}
}
