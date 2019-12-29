<?php

use App\Models\OperatorUser;
use App\Services\Contracts\Template;

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

			if (!isset($_FILES['invoice']['name']) or !is_uploaded_file($_FILES['invoice']['tmp_name'])) {
				header('Content-Type: application/json');
				echo json_encode(array("stat" => false, "msg" => "Silakan Upload Berkas Invoice Anda."));
				exit();
			}

			if (!isset($_FILES['packing_list']['name']) or !is_uploaded_file($_FILES['packing_list']['tmp_name'])) {
				header('Content-Type: application/json');
				echo json_encode(array("stat" => false, "msg" => "Silakan Upload Berkas Packing List Anda."));
				exit();
			}

			/* Handle file uploads */
			$uploadPath = app_path() . "/pengajuan/berkas";
			$allowedFileExtensions = ['jpeg','jpg','png'];
			$errors = [];

			$uploadedFileNames = [];

			foreach (["invoice", "packing_list", "pra_bap"] as $field) {
				
				/* Allow pra_bap field to be empty */
				if (($field === "pra_bap") && !isset($_FILES[$field]['name'])) {
					continue;
				}

				$fileName = md5(time() .  $_FILES[$field]['name']);
				$fileSize = $_FILES[$field]['size'];
				$fileTmpName  = $_FILES[$field]['tmp_name'];
				$fileType = $_FILES[$field]['type'];
				$fileExtension = strtolower(end(explode('.', $_FILES[$field]["name"])));

				if (! in_array($fileExtension, $allowedFileExtensions)) {
					$errors[] = "{$field}: This file extension is not allowed.";
				}

				$finalFileName = $fileName . "." . $fileExtension;
				$finalUploadPath = $uploadPath . "/" . $finalFileName;

				if (move_uploaded_file($fileTmpName, $finalUploadPath)) {
					$errors[] = "{$field}: Failed to upload file.";
					$uploadedFileNames[$field] = $finalFileName;
				}
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
				'file_invoice' => $uploadedFileNames['invoice'] ?? null,
				'file_packing_list' => $uploadedFileNames['packing_list'] ?? null,
                'file_pra_bap' => $uploadedFileNames['pra_bap'] ?? null,
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

				$administratorEmails = OperatorUser::query()
					->select("email", "nm_lengkap")
					->admin()
					->active()
					->pluck("nm_lengkap", "email");

				$successful = true;
				try {
					foreach ($administratorEmails as $administratorEmailAddress => $administratorName) {
						container(Swift_Mailer::class)->send(
							(new Swift_Message("Permohonan Rekomendasi - " . container("app_short_name")))
								->setFrom([ container("admin_email_address") => "Administrator" ])
								->setTo([ $administratorEmailAddress => $administratorName ])
								->setBody(
									container(Template::class)->render("email/pengajuan_rekomendasi", [
										"nama_lengkap_admin" => $administratorName,
										"nama_pemohon" => U_NAME,
										"nama_penerima" => $_POST["nm_penerima"] ?? "",
										"alamat_penerima" => $_POST["alamat_penerima"] ?? "",
										"format_noantrian" => $format_noantrian,
									]),
									"text/html",
								)
						);
					}
				}
				catch (\Exception $e) {
					dump($e->getMessage());
					$successful = false;
				}

				echo $successful ?
					json_encode(["stat" => $successful, "msg" => "Aksi Berhasil."]) :
					json_encode(["stat" => $successful, "msg" => "Aksi Gagal."]);

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
