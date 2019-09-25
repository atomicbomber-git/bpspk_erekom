<?php

use App\Models\Pegawai;
use App\Models\PetugasLapangan;

if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest'){
	die();
}

include ("../../engine/render.php");

if($_POST){
	switch (trim(strip_tags($_POST['a']))) {
		case 'update-dt-periksa':
			$idpengajuan=U_IDP;

			$idperiksa=base64_decode($_POST['idpr']);
			if(!ctype_digit($idperiksa)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			
			$arr_update=array(
				'tgl_periksa'=>date('Y-m-d',strtotime($_POST['tgl_pemeriksaan']))
				);
			$sql->update('tb_pemeriksaan',$arr_update,array('id_periksa'=>$idperiksa));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal."));
			}
		break;

		case 'add-hsl-periksa':
			$idpengajuan=U_IDP;
			$idperiksa=base64_decode($_POST['idpr']);
			if(!ctype_digit($idperiksa)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			if($_POST['jenis_ikan']=='0' OR $_POST['jenis_ikan']==''){
				echo json_encode(array("stat"=>false,"msg"=>"Silakan Pilih Jenis Ikan"));
				exit();
			}

			if($_POST['pjg']=='0' OR $_POST['pjg']==''){
				echo json_encode(array("stat"=>false,"msg"=>"Panjang Sampel Belum Diisi"));
				exit();
			}

			if($_POST['lbr']=='0' OR $_POST['lbr']==''){
				echo json_encode(array("stat"=>false,"msg"=>"Lebar Sampel Belum Diisi"));
				exit();
			}

			if($_POST['berat']=='0' OR $_POST['berat']==''){
				echo json_encode(array("stat"=>false,"msg"=>"Berat Sampel Belum Diisi"));
				exit();
			}

			$tgl_=date('Y-m-d H:i:s');
			$asal_komoditas=(($_POST['asal_komoditas_opt']=='lainnya')?$_POST['asal_komoditas']:$_POST['asal_komoditas_opt']);
			$arr_insert2=array(
				'ref_idikan'=>$_POST['jenis_ikan'],
				'ref_idp'=>$idpengajuan,
				'ref_idperiksa'=>$idperiksa,
				'ref_jns_sampel'=>$_POST['jenis_sampel'],
				'pjg'=>$_POST['pjg'],
				'lbr'=>$_POST['lbr'],
				'berat'=>$_POST['berat'],
				'pjg2'=>$_POST['pjg2'],
				'lbr2'=>$_POST['lbr2'],
				'berat2'=>$_POST['berat2'],
				'tot_berat'=>$_POST['berat_tot'],
				'ket'=>$_POST['ket'],
				'date_insert'=>$tgl_,
				'asal_komoditas'=>$asal_komoditas,
				'kuantitas'=>$_POST['kemasan']
				);
			$sql->insert('tb_hsl_periksa',$arr_insert2);
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
				//insert ke log tahapan
				$sql->get_row('tb_log_tahapan',array('ref_idp'=>$idpengajuan,'tahapan'=>4),'id');
				if($sql->num_rows==0){
					$sql->insert('tb_log_tahapan',array('ref_idp'=>$idpengajuan,'tahapan'=>'4','tanggal'=>$tgl_));	
				}

				//input no surat
				$sql->get_row('tb_nosurat',array('ref_idp'=>$idpengajuan),'id');
				if($sql->num_rows==0){
					//get kode surat
					$ck=$sql->run("SELECT id_satker,kode FROM ref_satuan_kerja rsk JOIN tb_permohonan p ON (p.ref_satker=rsk.id_satker) WHERE p.idp ='".$idpengajuan."' ");
					if($ck->rowCount()>0){
						$kr=$ck->fetch();
						$kodesurat = $kr['kode'];
						$id_satker = $kr['id_satker'];
						
						$ln=$sql->run("SELECT MAX(no_urut)+1 as next_no from tb_nosurat");
						if($ln->rowCount()>0){
							$r_ln=$ln->fetch();
							$no_surat_selanjutnya = $r_ln['next_no'];
							$kodesurat_satker = $kodesurat;
							$tgl=date('Y-m-d H:i:s');

							$arr_no_surat=array(
								"ref_satker"=>$id_satker,
								"ref_idp"=>$idpengajuan,
								"no_urut"=>$no_surat_selanjutnya,
								"kode_satker"=>$kodesurat_satker,
								"no_surat_st"=>generate_nosurat('st',$no_surat_selanjutnya,$kodesurat_satker,$tgl),
								"no_surat_bap"=>generate_nosurat('bap',$no_surat_selanjutnya,$kodesurat_satker,$tgl),
								"no_surat_rek"=>generate_nosurat('rek',$no_surat_selanjutnya,$kodesurat_satker,$tgl),
								"tgl"=>$tgl
							);

							$sql->insert('tb_nosurat',$arr_no_surat);
						}
					}
				}
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal."));
			}
		break;

		case 'update-hsl-periksa':
			$idpengajuan=U_IDP;

			$idhsl=base64_decode($_POST['idhsl']);
			if(!ctype_digit($idhsl)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$asal_komoditas=(($_POST['asal_komoditas_opt']=='lainnya')?$_POST['asal_komoditas']:$_POST['asal_komoditas_opt']);	
			$arr_update=array(
				'ref_idikan'=>$_POST['jenis_ikan'],
				'ref_jns_sampel'=>$_POST['jenis_sampel'],
				'pjg'=>$_POST['pjg'],
				'lbr'=>$_POST['lbr'],
				'berat'=>$_POST['berat'],
				'pjg2'=>$_POST['pjg2'],
				'lbr2'=>$_POST['lbr2'],
				'berat2'=>$_POST['berat2'],
				'tot_berat'=>$_POST['berat_tot'],
				'ket'=>$_POST['ket'],
				'asal_komoditas'=>$asal_komoditas,
				'kuantitas'=>$_POST['kemasan']
				);
			$sql->update('tb_hsl_periksa',$arr_update,array('id_per'=>$idhsl));

			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal."));
			}
		break;

		case 'del-hsl-periksa':
			$idhasil=base64_decode($_POST['iddt']);
			if(!ctype_digit($idhasil)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request."));
				exit();
			}

			$sql->delete('tb_hsl_periksa',array('id_per'=>$idhasil));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Dihapus."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'adft': // upload foto
			$idpengajuan=U_IDP;

			include ("../../engine/resize-class.php");
			$filefoto		=$_FILES['file_foto'];
			$ImageName 		= $filefoto['name'];
			$ImageSize 		= $filefoto['size'];
			$TempSrc	 	= $filefoto['tmp_name'];
			$ImageType	 	= $filefoto['type'];
			$location		= c_BASE_UTAMA."admin/berkas/dok_sample/";

			$img_file=get_file_extension($ImageName);
			$ImageFileName=time();
			$ImageExt=$img_file['file_ext'];
			$saved_img_file=$ImageFileName.".".$ImageExt;

			if($ImageSize>10000000){
				echo json_encode(array("stat"=>false,"msg"=>"Ukuran Images Maksimal 10MB."));
				exit();
			}

			if(!isset($ImageName) || !is_uploaded_file($TempSrc)){
				echo json_encode(array("stat"=>false,"msg"=>"Pastikan gambar sudah dipilih"));
				exit();
			}else{
				$img_ttd = new resize($TempSrc);
				$img_ttd -> resizeImage(500, 400, 'auto');
				$img_ttd -> saveImage($location.$saved_img_file, 80);

				$img_ttd = new resize($TempSrc);
				$img_ttd -> resizeImage(100, 100, 'crop');
				$img_ttd -> saveImage($location."thumb_".$saved_img_file, 70);

				$arr_insert=array(
					"ref_idp"=>$idpengajuan,
					"nm_file"=>$saved_img_file,
					"ket_foto"=>$_POST['ket_foto'],
					"file_type"=>$ImageType,
					"date_insert"=>date('Y-m-d H:i:s')
					);

				$sql->insert('tb_dokumentasi',$arr_insert);
				if($sql->error==null){
					echo json_encode(array("stat"=>true,"msg"=>"Foto Berhasil DiSimpan."));
				}else{
					echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
				}
			}
		break;

		case 'upft': //update ket foto
			$idft=base64_decode($_POST['idft']);
			if(!ctype_digit($idft)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->update('tb_dokumentasi',array('ket_foto'=>$_POST['ket']),array('id_dok'=>$idft));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'delft': // hapus foto
			$idft=base64_decode($_POST['idft']);
			if(!ctype_digit($idft)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$sql->get_row('tb_dokumentasi',array('id_dok'=>$idft),array('nm_file'));
			$r=$sql->result;

			$sql->delete('tb_dokumentasi',array('id_dok'=>$idft));
			if($sql->error==null){
				$location		= c_BASE."berkas/dok_sample/";
				@unlink($location.$r['nm_file']);
				@unlink($location."thumb_".$r['nm_file']);
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Dihapus."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'bapsv': //add BAP
			$idpengajuan=U_IDP;

			if($_POST['token']!=md5($idpengajuan.U_ID.'bap')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$pegawais = Pegawai::query()
				->select("idp", "nip")
				->whereIn("idp", [$_POST["ptg1"], $_POST["ptg2"]])
				->get()
				->pluck("nip", "idp");

			$arr_insert = array (
				'ref_idp'=>$idpengajuan,
				'no_surat'=>$_POST['no_surat'],
				'tgl_surat'=>date("Y-m-d", strtotime($_POST['tgl_penetapan'])),
				'lokasi'=>$_POST['lokasi_penetapan'],
				'redaksi'=>$_POST['redaksi_bap'],
				'ptgs1'=>$_POST['ptg1'],
				'ptgs2'=>$_POST['ptg2'],
				'nipptgs1'=> $pegawais[$_POST['ptg1']] ?? "-",
				'nipptgs2'=> $pegawais[$_POST['ptg2']] ?? "-",
				'date_insert'=>date('Y-m-d H:i:s')
			);

			$sql->insert('tb_bap',$arr_insert);

			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'bapup': //update BAP
			$idbap=base64_decode($_POST['idbap']);
			if(!ctype_digit($idbap)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			if($_POST['token']!=md5($idbap.U_ID.'bap')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				'no_surat'=>$_POST['no_surat'],
				'tgl_surat'=>date("Y-m-d", strtotime($_POST['tgl_penetapan'])),
				'lokasi'=>$_POST['lokasi_penetapan'],
				'redaksi'=>$_POST['redaksi_bap'],
				'ptgs1'=>$_POST['ptg1'],
				'ptgs2'=>$_POST['ptg2']
			);

			$sql->update('tb_bap',$arr_update,array('id_bap'=>$idbap));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Perubahan Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'reksv': //add draft rekomendasi
			$idpengajuan=U_IDP;

			if($_POST['token']!=md5($idpengajuan.U_ID.'rek')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->get_row('tb_permohonan',array('idp'=>$idpengajuan),array('ref_satker'));
			if($sql->num_rows>0){
				$sat=$sql->result;
				$ref_satker=$sat['ref_satker'];
			}else{
				$ref_satker=0;
			}

			$arr_insert=array(
				"ref_idp"=>$idpengajuan,
				"ref_iduser"=>$_POST['tujuan'],
				"ref_bk"=>$_POST['tembusan_bk'],
				"ref_psdkp"=>$_POST['tembusan_psdkp'],
				"ref_uptprl"=>$_POST['upt_prl_penerima'],
				"ref_satker"=>$ref_satker,
				"no_surat"=>$_POST['no_surat'],
				"kode_surat"=>time().$idpengajuan,
				"perihal"=>$_POST['perihal'],
				"tgl_surat"=>date("Y-m-d", strtotime($_POST['tgl_surat'])),
				"tujuan"=>$_POST['tujuan_nm'],
				"redaksi"=>$_POST['redaksi_rek'],
				"pnttd"=>$_POST['penandatgn'],
				"date_create"=>date('Y-m-d H:i:s')
			);
			$sql->insert('tb_rekomendasi',$arr_insert);
			if($sql->error==null){
				$idrek=$sql->insert_id;
				for($x=0;$x<count($_POST['jenis_sampel']);$x++){
					$arr_insert2=array(
						"ref_idrek"=>$idrek,
						"ref_jns"=>$_POST['jenis_sampel'][$x],
						"ref_idikan"=>$_POST['jenis_ikan'][$x],
						"kemasan"=>$_POST['kemasan'][$x],
						"satuan"=>$_POST['satuan'][$x],
						"no_segel"=>$_POST['nosegel'][$x],
						"berat"=>$_POST['berat'][$x],
						"keterangan"=>$_POST['keterangan'][$x],
						"date_create"=>date('Y-m-d H:i:s')
						);

					$sql->insert('tb_rek_hsl_periksa',$arr_insert2);
				}
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'rekup': //update draft rekomendasi
			$idrek=base64_decode($_POST['idrek']);
			if(!ctype_digit($idrek)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			if($_POST['token']!=md5($idrek.U_ID.'rek')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				"no_surat"=>$_POST['no_surat'],
				"perihal"=>$_POST['perihal'],
				"ref_bk"=>$_POST['tembusan_bk'],
				"ref_psdkp"=>$_POST['tembusan_psdkp'],
				"ref_uptprl"=>$_POST['upt_prl_penerima'],
				"tgl_surat"=>date("Y-m-d", strtotime($_POST['tgl_surat'])),
				"redaksi"=>$_POST['redaksi_rek'],
				"pnttd"=>$_POST['penandatgn']
			);
			$sql->update('tb_rekomendasi',$arr_update,array('idrek'=>$idrek));
			if($sql->error==null){
				$sql->delete('tb_rek_hsl_periksa',array('ref_idrek'=>$idrek));
				for($x=0;$x<count($_POST['jenis_sampel']);$x++){
					$arr_insert2=array(
						"ref_idrek"=>$idrek,
						"ref_jns"=>$_POST['jenis_sampel'][$x],
						"ref_idikan"=>$_POST['jenis_ikan'][$x],
						"kemasan"=>$_POST['kemasan'][$x],
						"satuan"=>$_POST['satuan'][$x],
						"no_segel"=>$_POST['nosegel'][$x],
						"berat"=>$_POST['berat'][$x],
						"keterangan"=>$_POST['keterangan'][$x],
						"date_create"=>date('Y-m-d H:i:s')
						);

					$sql->insert('tb_rek_hsl_periksa',$arr_insert2);
				}
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'reset_tbl_rek':
			//hapus hasil pemeriksaan di rekomendasi
			//dilakukan ketika ada perubahan pada hasil pemeriksaan lapangan
			//sehingga perlu resync dari tabel hasil pemeriksaan
			$idrek=base64_decode($_POST['idrek']);
			if(!ctype_digit($idrek)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->delete('tb_rek_hsl_periksa',array('ref_idrek'=>$idrek));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Reload Ulang Berhasil."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'getciri':
			$idikan=$_POST['ik'];
			$idproduk=$_POST['pr'];
			if(!ctype_digit($idikan)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			if(!ctype_digit($idproduk)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->get_row('ref_ciri_ikan',array('id_ikan'=>$idikan,'id_produk'=>$idproduk));
			if($sql->num_rows>0){
				$r=$sql->result;
				echo $r['ciri_ciri'];
			}else{
				echo "";
			}
		break;

		case 'check_nobap':
			$no_surat=$_POST['no_surat'];
			$sql->get_row('tb_bap',array("no_surat"=>$no_surat),array('id_bap'));
			$r=$sql->num_rows;
			if($r>0){
				echo 'false';
			}else{
				echo 'true';
			}
		break;

		case 'check_norek':
			$no_surat=$_POST['no_surat'];
			$sql->get_row('tb_rekomendasi',array("no_surat"=>$no_surat),array('idrek'));
			$r=$sql->num_rows;
			if($r>0){
				echo 'false';
			}else{
				echo 'true';
			}
		break;

		/*case 'submit':
			$idrek=base64_decode($_POST['rek']);
			if(!ctype_digit($idrek)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			if($_POST['token']!=md5($idrek.U_ID.'submit')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$getdb=$sql->run("SELECT tr.ref_idp,op.nm_lengkap, op.email, op.idp FROM tb_rekomendasi tr JOIN op_pegawai op ON(op.nip=tr.pnttd) WHERE tr.idrek='$idrek' LIMIT 1");
			$r=$getdb->fetch();

			$sql->update('tb_permohonan',array('status'=>4),array('idp'=>$r['ref_idp']));
			if($sql->error==null){
				//---------------email-------------------------
				require '../../../assets/phpmailer/PHPMailerAutoload.php';
				$isi="<p>".$r['nm_lengkap'].", Terdapat Permohonan Rekomendasi perlu diperiksa dan disahkan.</p>";
				$isi.="<p>Silakan Buka Tautan <a target='_blank' href='".c_DOMAIN."persetujuan.php?token=".md5($r['ref_idp'].$r['idp']."confirm")."&data=".base64_encode($r['ref_idp'])."'>Berikut Ini</a></p>";
				$isi.="<p>Demikian Pemberitahuan Ini kami sampaikan, atas perhatiannya kami ucapkan terima kasih.</p>";
				$arr=array(
					"send_to"=>$email,
					"send_to_name"=>$nama_pemohon,
					"subject_email"=>"Pengesahan Permohonan Rekomendasi - BPSPL Pontianak",
					"isi_email"=>$isi);
				sendMail($arr);
				//---------------------------------------------
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			} 
		break;*/

		default:
			echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
			exit();
		break;
	}
}
