<?php
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
	die();
}

include("../../engine/render.php");

if ($_POST) {
	switch (trim(strip_tags($_POST['a']))) {
		case 'adbio':
			$sql->get_row('tb_biodata', array('ref_iduser' => U_ID), 'idbio');
			if ($sql->num_rows > 0) {
				header('Content-Type: application/json');
				echo json_encode(array("stat" => false, "msg" => "Biodata Sudah Pernah Diinput, Silakan Refresh Halaman Ini."));
				exit();
			}

			if (!isset($_FILES['ttd']['name']) or !is_uploaded_file($_FILES['ttd']['tmp_name'])) {
				header('Content-Type: application/json');
				echo json_encode(array("stat" => false, "msg" => "Silakan Upload Tandatangan anda."));
				exit();
			}

			$tmp_lahir = $_POST['tmp_lahir'];
			$tgl_lahir = date("Y-m-d", strtotime($_POST['tgl_lahir']));;
			$no_ktp = $_POST['no_ktp'];
			$no_telp = $_POST['no_telp'];
			$alamat_rmh = $_POST['alamat_rmh'];
			$npwp = $_POST['npwp'];
			$nm_perusahaan = $_POST['nm_perusahaan'];
			$siup = $_POST['siup'];
			$izin_lainnya = $_POST['izin_lainnya'];
			$date_ = date('Y-m-d H:i:s');

			
			$arr_insert = array(
				"ref_iduser" => U_ID,
				"tmp_lahir" => $tmp_lahir,
				"tgl_lahir" => $tgl_lahir,
				"no_telp" => $no_telp,
				"no_ktp" => $no_ktp,
				"npwp" => $npwp,
				"nm_perusahaan" => $nm_perusahaan,
				"siup" => $siup,
				"izin_lain" => $izin_lainnya,
				"date_input" => date('Y-m-d H:i:s'),
				"gudang_1" => $_POST["gudang_1"],
				"gudang_2" => $_POST["gudang_2"] ?? null,
				"gudang_3" => $_POST["gudang_3"] ?? null,
			);

			$sql->insert('tb_biodata', $arr_insert);

			include("../../engine/resize-class.php");
			$location		= c_BASE . "berkas/";
			$npwp = $_FILES['file_npwp'];
			$ktp = $_FILES['file_ktp'];
			$siup = $_FILES['siup'];
			$ttd = $_FILES['ttd'];
			//npwp
			if (isset($npwp['name']) and is_uploaded_file($npwp['tmp_name'])) {
				list($CurWidth, $CurHeight) = getimagesize($npwp['tmp_name']);
				$npwpnm = get_file_extension($npwp['name']);
				$npwp_filename = U_ID . 'npwp_' . time();
				$npwp_ext = $npwpnm['file_ext'];
				$saved = $npwp_filename . '.' . $npwp_ext;

				$original = new resize($npwp['tmp_name']);
				$original->resizeImage($CurWidth, $CurHeight, 'exact');
				$original->saveImage($location . $saved, 75);

				$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 2), array('revisi'));
				$sql->order_by = "date_upload DESC, idb DESC";
				if ($sql->num_rows > 0) {
					$n = $sql->result;
					$rev = $n['revisi'];
					$revisi = $rev + 1;
				} else {
					$revisi = 0;
				}
				$arr_insert = array(
					"ref_iduser" => U_ID,
					"nama_file" => $saved,
					"type_file" => $npwp['type'],
					"jenis_berkas" => 2,
					"date_upload" => date('Y-m-d H:i:s'),
					"revisi" => $revisi
				);

				$sql->insert('tb_berkas', $arr_insert);
			}

			//siup
			if (isset($siup['name']) and is_uploaded_file($siup['tmp_name'])) {
				list($CurWidth, $CurHeight) = getimagesize($siup['tmp_name']);
				$siupnm = get_file_extension($siup['name']);
				$siup_filename = U_ID . 'siup_' . time();
				$siup_ext = $siupnm['file_ext'];
				$saved = $siup_filename . '.' . $siup_ext;

				$original = new resize($siup['tmp_name']);
				$original->resizeImage($CurWidth, $CurHeight, 'exact');
				$original->saveImage($location . $saved, 75);

				$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 3), array('revisi'));
				$sql->order_by = "date_upload DESC, idb DESC";
				if ($sql->num_rows > 0) {
					$n = $sql->result;
					$rev = $n['revisi'];
					$revisi = $rev + 1;
				} else {
					$revisi = 0;
				}
				$arr_insert = array(
					"ref_iduser" => U_ID,
					"nama_file" => $saved,
					"type_file" => $siup['type'],
					"jenis_berkas" => 3,
					"date_upload" => date('Y-m-d H:i:s'),
					"revisi" => $revisi
				);

				$sql->insert('tb_berkas', $arr_insert);
			}

			//nib
			if (isset($nib['name']) and is_uploaded_file($nib['tmp_name'])) {
				list($CurWidth, $CurHeight) = getimagesize($nib['tmp_name']);
				$nibnm = get_file_extension($nib['name']);
				$nib_filename = U_ID . 'nib_' . time();
				$nib_ext = $nibnm['file_ext'];
				$saved = $nib_filename . '.' . $nib_ext;

				$original = new resize($nib['tmp_name']);
				$original->resizeImage($CurWidth, $CurHeight, 'exact');
				$original->saveImage($location . $saved, 75);

				$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 5), array('revisi'));
				$sql->order_by = "date_upload DESC, idb DESC";
				if ($sql->num_rows > 0) {
					$n = $sql->result;
					$rev = $n['revisi'];
					$revisi = $rev + 1;
				} else {
					$revisi = 0;
				}
				$arr_insert = array(
					"ref_iduser" => U_ID,
					"nama_file" => $saved,
					"type_file" => $nib['type'],
					"jenis_berkas" => 5,
					"date_upload" => date('Y-m-d H:i:s'),
					"revisi" => $revisi
				);

				$sql->insert('tb_berkas', $arr_insert);
			}

			//sipji
			if (isset($sipji['name']) and is_uploaded_file($sipji['tmp_name'])) {
				list($CurWidth, $CurHeight) = getimagesize($sipji['tmp_name']);
				$sipjinm = get_file_extension($sipji['name']);
				$sipji_filename = U_ID . 'sipji_' . time();
				$sipji_ext = $sipjinm['file_ext'];
				$saved = $sipji_filename . '.' . $sipji_ext;

				$original = new resize($sipji['tmp_name']);
				$original->resizeImage($CurWidth, $CurHeight, 'exact');
				$original->saveImage($location . $saved, 75);

				$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 6), array('revisi'));
				$sql->order_by = "date_upload DESC, idb DESC";
				if ($sql->num_rows > 0) {
					$n = $sql->result;
					$rev = $n['revisi'];
					$revisi = $rev + 1;
				} else {
					$revisi = 0;
				}
				$arr_insert = array(
					"ref_iduser" => U_ID,
					"nama_file" => $saved,
					"type_file" => $sipji['type'],
					"jenis_berkas" => 6,
					"date_upload" => date('Y-m-d H:i:s'),
					"revisi" => $revisi
				);

				$sql->insert('tb_berkas', $arr_insert);
			}

			//ktp
			if (isset($ktp['name']) and is_uploaded_file($ktp['tmp_name'])) {
				list($CurWidth, $CurHeight) = getimagesize($ktp['tmp_name']);
				$ktpnm = get_file_extension($ktp['name']);
				$ktp_filename = U_ID . 'ktp_' . time();
				$ktp_ext = $ktpnm['file_ext'];
				$saved = $ktp_filename . '.' . $ktp_ext;

				$original = new resize($ktp['tmp_name']);
				$original->resizeImage($CurWidth, $CurHeight, 'exact');
				$original->saveImage($location . $saved, 75);

				$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 4), array('revisi'));
				$sql->order_by = "date_upload DESC, idb DESC";
				if ($sql->num_rows > 0) {
					$n = $sql->result;
					$rev = $n['revisi'];
					$revisi = $rev + 1;
				} else {
					$revisi = 0;
				}
				$arr_insert = array(
					"ref_iduser" => U_ID,
					"nama_file" => $saved,
					"type_file" => $ktp['type'],
					"jenis_berkas" => 4,
					"date_upload" => date('Y-m-d H:i:s'),
					"revisi" => $revisi
				);

				$sql->insert('tb_berkas', $arr_insert);
			}

			//ttd
			if (isset($ttd['name']) and is_uploaded_file($ttd['tmp_name'])) {
				list($CurWidth, $CurHeight) = getimagesize($ttd['tmp_name']);
				$ttdnm = get_file_extension($ttd['name']);
				$ttd_filename = U_ID . 'ttd_' . time();
				$ttd_ext = $ttdnm['file_ext'];
				$saved = $ttd_filename . '.' . $ttd_ext;

				$original = new resize($ttd['tmp_name']);
				$original->resizeImage(300, 200, 'auto');
				$original->saveImage($location . $saved, 75);

				$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 1), array('revisi'));
				$sql->order_by = "date_upload DESC, idb DESC";
				if ($sql->num_rows > 0) {
					$n = $sql->result;
					$rev = $n['revisi'];
					$revisi = $rev + 1;
				} else {
					$revisi = 0;
				}
				$arr_insert = array(
					"ref_iduser" => U_ID,
					"nama_file" => $saved,
					"type_file" => $ttd['type'],
					"jenis_berkas" => 1,
					"date_upload" => date('Y-m-d H:i:s'),
					"revisi" => $revisi
				);

				$sql->insert('tb_berkas', $arr_insert);
			}

			// var_dump($sql->error);

			if ($sql->error == null) {
				header('Content-Type: application/json');
				echo json_encode(array("stat" => true, "msg" => "Data Berhasil Disimpan."));
				exit();
			} else {
				header('Content-Type: application/json');
				echo json_encode(array("stat" => false, "msg" => "Aksi Gagal."));
				exit();
			}
			break;

		case 'edbio':
			$sql->get_row('tb_biodata', array('ref_iduser' => U_ID), 'idbio');
			if ($sql->num_rows > 0) {

				// $sql->get_row('tb_berkas',array('ref_iduser'=>U_ID,'jenis_berkas'=>1),'idb');
				// if($sql->num_rows<1){
				// 	if(!isset($_FILES['ttd']['name']) OR !is_uploaded_file($_FILES['ttd']['tmp_name'])){
				// 		header('Content-Type: application/json');
				// 		echo json_encode(array("stat"=>false,"msg"=>"Silakan Upload Tandatangan anda."));
				// 		exit();
				// 	}
				// }

				$row = $sql->result;
				$id = $row['idbio'];
				$tmp_lahir = $_POST['tmp_lahir'];
				$tgl_lahir = date("Y-m-d", strtotime($_POST['tgl_lahir']));;
				$no_ktp = $_POST['no_ktp'];
				$no_telp = $_POST['no_telp'];
				$alamat_rmh = $_POST['alamat_rmh'];
				$npwp = $_POST['npwp'];
				$nm_perusahaan = $_POST['nm_perusahaan'];
				$siup = $_POST['siup'];
				$nib = $_POST['nib'];
				$sipji = $_POST['sipji'];
				$izin_lainnya = $_POST['izin_lainnya'];
				$date_ = date('Y-m-d H:i:s');

				$arr_update = array(
					"tmp_lahir" => $tmp_lahir,
					"tgl_lahir" => $tgl_lahir,
					"no_telp" => $no_telp,
					"no_ktp" => $no_ktp,
					"alamat" => $alamat_rmh,
					"npwp" => $npwp,
					"nm_perusahaan" => $nm_perusahaan,
					"siup" => $siup,
					"nib" => $nib,
					"sipji" => $sipji,
					"izin_lain" => $izin_lainnya,
					"gudang_1" => $_POST["gudang_1"],
					"gudang_2" => $_POST["gudang_2"] ?? null,
					"gudang_3" => $_POST["gudang_3"] ?? null,
				);
				$sql->update('tb_biodata', $arr_update, array('idbio' => $id));

				include("../../engine/resize-class.php");
				$location		= c_BASE . "berkas/";
				$npwp = $_FILES['file_npwp'];
				$ktp = $_FILES['file_ktp'];
				$siup = $_FILES['siup'];
				$nib = $_FILES['nib'];
				$sipji = $_FILES['sipji'];
				$ttd = $_FILES['ttd'];

				//npwp
				if (isset($npwp['name']) and is_uploaded_file($npwp['tmp_name'])) {
					list($CurWidth, $CurHeight) = getimagesize($npwp['tmp_name']);
					$npwpnm = get_file_extension($npwp['name']);
					$npwp_filename = U_ID . 'npwp_' . time();
					$npwp_ext = $npwpnm['file_ext'];
					$saved = $npwp_filename . '.' . $npwp_ext;

					$original = new resize($npwp['tmp_name']);
					$original->resizeImage($CurWidth, $CurHeight, 'exact');
					$original->saveImage($location . $saved, 75);

					$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 2), array('revisi'));
					$sql->order_by = "date_upload DESC, idb DESC";
					if ($sql->num_rows > 0) {
						$n = $sql->result;
						$rev = $n['revisi'];
						$revisi = $rev + 1;
					} else {
						$revisi = 0;
					}
					$arr_insert = array(
						"ref_iduser" => U_ID,
						"nama_file" => $saved,
						"type_file" => $npwp['type'],
						"jenis_berkas" => 2,
						"date_upload" => date('Y-m-d H:i:s'),
						"revisi" => $revisi
					);

					$sql->insert('tb_berkas', $arr_insert);
				}

				//siup
				if (isset($siup['name']) and is_uploaded_file($siup['tmp_name'])) {
					list($CurWidth, $CurHeight) = getimagesize($siup['tmp_name']);
					$siupnm = get_file_extension($siup['name']);
					$siup_filename = U_ID . 'siup_' . time();
					$siup_ext = $siupnm['file_ext'];
					$saved = $siup_filename . '.' . $siup_ext;

					$original = new resize($siup['tmp_name']);
					$original->resizeImage($CurWidth, $CurHeight, 'exact');
					$original->saveImage($location . $saved, 75);

					$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 3), array('revisi'));
					$sql->order_by = "date_upload DESC, idb DESC";
					if ($sql->num_rows > 0) {
						$n = $sql->result;
						$rev = $n['revisi'];
						$revisi = $rev + 1;
					} else {
						$revisi = 0;
					}
					$arr_insert = array(
						"ref_iduser" => U_ID,
						"nama_file" => $saved,
						"type_file" => $siup['type'],
						"jenis_berkas" => 3,
						"date_upload" => date('Y-m-d H:i:s'),
						"revisi" => $revisi
					);

					$sql->insert('tb_berkas', $arr_insert);
				}

				//nib
				if (isset($nib['name']) and is_uploaded_file($nib['tmp_name'])) {
					list($CurWidth, $CurHeight) = getimagesize($nib['tmp_name']);
					$nibnm = get_file_extension($nib['name']);
					$nib_filename = U_ID . 'nib_' . time();
					$nib_ext = $nibnm['file_ext'];
					$saved = $nib_filename . '.' . $nib_ext;

					$original = new resize($nib['tmp_name']);
					$original->resizeImage($CurWidth, $CurHeight, 'exact');
					$original->saveImage($location . $saved, 75);

					$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 5), array('revisi'));
					$sql->order_by = "date_upload DESC, idb DESC";
					if ($sql->num_rows > 0) {
						$n = $sql->result;
						$rev = $n['revisi'];
						$revisi = $rev + 1;
					} else {
						$revisi = 0;
					}
					$arr_insert = array(
						"ref_iduser" => U_ID,
						"nama_file" => $saved,
						"type_file" => $nib['type'],
						"jenis_berkas" => 5,
						"date_upload" => date('Y-m-d H:i:s'),
						"revisi" => $revisi
					);

					$sql->insert('tb_berkas', $arr_insert);
				}

				//sipji
				if (isset($sipji['name']) and is_uploaded_file($sipji['tmp_name'])) {
					list($CurWidth, $CurHeight) = getimagesize($sipji['tmp_name']);
					$sipjinm = get_file_extension($sipji['name']);
					$sipji_filename = U_ID . 'sipji_' . time();
					$sipji_ext = $sipjinm['file_ext'];
					$saved = $sipji_filename . '.' . $sipji_ext;

					$original = new resize($sipji['tmp_name']);
					$original->resizeImage($CurWidth, $CurHeight, 'exact');
					$original->saveImage($location . $saved, 75);

					$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 6), array('revisi'));
					$sql->order_by = "date_upload DESC, idb DESC";
					if ($sql->num_rows > 0) {
						$n = $sql->result;
						$rev = $n['revisi'];
						$revisi = $rev + 1;
					} else {
						$revisi = 0;
					}
					$arr_insert = array(
						"ref_iduser" => U_ID,
						"nama_file" => $saved,
						"type_file" => $sipji['type'],
						"jenis_berkas" => 6,
						"date_upload" => date('Y-m-d H:i:s'),
						"revisi" => $revisi
					);

					$sql->insert('tb_berkas', $arr_insert);
				}

				//ktp
				if (isset($ktp['name']) and is_uploaded_file($ktp['tmp_name'])) {
					list($CurWidth, $CurHeight) = getimagesize($ktp['tmp_name']);
					$ktpnm = get_file_extension($ktp['name']);
					$ktp_filename = U_ID . 'ktp_' . time();
					$ktp_ext = $ktpnm['file_ext'];
					$saved = $ktp_filename . '.' . $ktp_ext;

					$original = new resize($ktp['tmp_name']);
					$original->resizeImage($CurWidth, $CurHeight, 'exact');
					$original->saveImage($location . $saved, 75);

					$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 4), array('revisi'));
					$sql->order_by = "date_upload DESC, idb DESC";
					if ($sql->num_rows > 0) {
						$n = $sql->result;
						$rev = $n['revisi'];
						$revisi = $rev + 1;
					} else {
						$revisi = 0;
					}
					$arr_insert = array(
						"ref_iduser" => U_ID,
						"nama_file" => $saved,
						"type_file" => $ktp['type'],
						"jenis_berkas" => 4,
						"date_upload" => date('Y-m-d H:i:s'),
						"revisi" => $revisi
					);

					$sql->insert('tb_berkas', $arr_insert);
				}

				//ttd
				if (isset($ttd['name']) and is_uploaded_file($ttd['tmp_name'])) {
					list($CurWidth, $CurHeight) = getimagesize($ttd['tmp_name']);
					$ttdnm = get_file_extension($ttd['name']);
					$ttd_filename = U_ID . 'ttd_' . time();
					$ttd_ext = $ttdnm['file_ext'];
					$saved = $ttd_filename . '.' . $ttd_ext;

					$original = new resize($ttd['tmp_name']);
					$original->resizeImage(300, 200, 'auto');
					$original->saveImage($location . $saved, 75);

					$sql->get_row('tb_berkas', array('ref_iduser' => U_ID, 'jenis_berkas' => 1), array('revisi'));
					$sql->order_by = "date_upload DESC, idb DESC";
					if ($sql->num_rows > 0) {
						$n = $sql->result;
						$rev = $n['revisi'];
						$revisi = $rev + 1;
					} else {
						$revisi = 0;
					}
					$arr_insert = array(
						"ref_iduser" => U_ID,
						"nama_file" => $saved,
						"type_file" => $ttd['type'],
						"jenis_berkas" => 1,
						"date_upload" => date('Y-m-d H:i:s'),
						"revisi" => $revisi
					);

					$sql->insert('tb_berkas', $arr_insert);
				}


				if ($sql->error == null) {
					header('Content-Type: application/json');
					echo json_encode(array("stat" => true, "msg" => "Perubahan Data Berhasil Disimpan."));
					exit();
				} else {
					header('Content-Type: application/json');
					echo json_encode(array("stat" => false, "msg" => "Aksi Gagal."));
					exit();
				}
			} else {
				header('Content-Type: application/json');
				echo json_encode(array("stat" => false, "msg" => "Invalid Action."));
				exit();
			}

			break;

		default:
			echo json_encode(array("stat" => false, "msg" => "Invalid Request"));
			break;
	}
}
