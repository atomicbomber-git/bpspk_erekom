<?php

include ("pemeriksaan/engine/render.php");

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>E-Rekomendasi</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css" media="screen" />
    <link rel="stylesheet" href="assets/stylesheets/index-home.css?s=23" media="screen" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,600,700' rel='stylesheet' type='text/css'>
    <!-- <link rel="stylesheet" href="http://www.jfxjournal.com/public/assets/plugin/font-awesome-4.5.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">


  </head>
  <body>
    <section>
      <div class="container">
        <img src="assets/images/logo-bpspl.png" alt="" class="logo"/>
        <h3 class="title">Si Jawara! <br/></h3> <h4 class="title"> (Sistem Aplikasi Surat Pemanfaatan Jenis Ikan antar Wilayah dan antar Negara) </h4>
        <ul class="menu">
          <li>
            <a href="<?= container("app_url") ?>/pengajuan/index.php?daftar">
              <div class="body-icon">
                <span class="fa fa-files-o fa-2x"></span>
                <span class="title">Pendaftaran</span>
              </div>
            </a>
          </li>
          <li>
            <a href="<?= container("app_url") ?>/pengajuan/">
              <div class="body-icon">
                <span class="fa fa-sign-in fa-2x"></span>
                <span class="title">Pengajuan</span>
              </div>
            </a>
          </li>
          <!-- <li>
            <a href="#">
              <div class="body-icon">
                <span class="fa fa-files-o fa-2x"></span>
                <span class="title">Cek Surat</span>
              </div>
            </a>
          </li> -->
          <li>
            <a href="<?= container("app_url") ?>/pemeriksaan/">
              <div class="body-icon">
                <span class="fa fa-search fa-2x"></span>
                <span class="title">Pemeriksaan</span>
              </div>
            </a>
          </li>
          <li>
            <a href="<?= container("app_url") ?>/admin/">
              <div class="body-icon">
                <span class="fa fa-user fa-2x"></span>
                <span class="title">Admin</span>
              </div>
            </a>
          </li>
          <!-- <li>
            <a 
                href="<?= container("app_url") ?>/guest/"
                >
              <div class="body-icon">
                <span class="fa fa-user fa-2x"></span>
                <span class="title">Tamu</span>
              </div>
            </a>
          </li> -->
        </ul>
        <ul class="menu">
          <li>
            <a href="#maklumat" data-toggle="modal" data-target="#maklumatpelayanan">
              <div class="body-icon">
                <span class="fa fa-file-text fa-2x"></span>
                <span class="title">Maklumat Pelayanan</span>
              </div>
            </a>
          </li>
          <li>
            <a href="#prosedur" data-toggle="modal" data-target="#prosedurpelayanan">
              <div class="body-icon">
                <span class="fa fa-check-square fa-2x"></span>
                <span class="title">Standar Pelayanan</span>
              </div>
            </a>
          </li>
          <li>
            <a href="#sop" data-toggle="modal" data-target="#soppelayanan">
              <div class="body-icon">
                <span class="fa fa-file-text fa-2x"></span>
                <span class="title">SOP Pelayanan</span>
              </div>
            </a>
          </li>
        </ul>
        <!-- <div class="download">
          <h5>download di</h5>
          <a href="#" class="button">
            <img src="images/google store icon.png" alt="" />
            <span>google play</span>
          </a>
          <a href="#" class="button">
            <i class="fa fa-apple"></i>
            <span>apps store</span>
          </a>
        </div> -->
        <!-- <div class="container">
        	<div class="row">
        		<div class="col-md-6">
        			<img src="assets/images/logo-bpspl.png" alt="" class="logo"/>
        			<h4 class="title">LPSPL SERANG MELAYANI <span class="text-danger">TANPA PUNGLI & GRATIFIKASI</span></h4>
        		</div>
        		<div class="col-md 6">
        			<img src="assets/images/logo-bpspl.png" alt="" class="logo"/>
        			<h4 class="title">SETIAP DATA YANG DIINPUTKAN <span class="text-danger">DIJAMIN</span> KERAHASIAANNYA</h4>
        		</div>
        	</div>
        </div> -->
        <h4 class="title">Loka Pengelolaan Sumberdaya Pesisir dan Laut Serang</h4>
        <!-- <h4 class="title">SETIAP DATA YANG DIINPUTKAN <span class="text-danger">DIJAMIN KERAHASIAANNYA </span></h4> -->
        <p style="color:#fff;text-align:center">Pengaduan dapat disampaikan melalui email : pelayananpsplserang@gmail.com</p>
      </div>
    </section>
    <div id="prosedurpelayanan" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
          <!-- <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modal Header</h4>
          </div> -->
          <div class="modal-body">
            <?php
            $sql->get_row('tb_maklumat',array('id'=>1),array('isi_maklumat'));
            if($sql->num_rows>0){
              $rr=$sql->result;
              echo $rr['isi_maklumat'];
            }
            ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
          </div>
        </div>

      </div>
    </div>
    <div id="maklumatpelayanan" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
         <!--  <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modal Header2</h4>
          </div> -->
          <div class="modal-body">
            <h3 style="text-align:center"><strong>MAKLUMAT PELAYANAN</strong></h3>

            <p style="text-align:center"><strong>&quot;DENGAN INI KAMI MENYATAKAN SANGGUP MENYELENGGARAKAN PELAYANAN LINGKUP LPSPL SERANG SESUAI STANDAR PELAYANAN,&nbsp;MEMBERIKAN PELAYANAN SESUAI DENGAN KEWAJIBAN DAN MELAKUKAN PERBAIKAN SECARA TERUS MENERUS, SERTA BERSEDIA MENERIMA SANKSI DAN/ATAU MEMBERIKAN KOMPENSASI APABILA PELAYANAN YANG DIBERIKAN TIDAK SESUAI STANDAR&quot;</strong></p><p></p>

            <p style="text-align:center">Serang, 27 Agustus 2019<br />
            KEPALA LPSPL SERANG</p>

            <p style="text-align:center"><strong>Sy. Iwan Taruna Alkadrie, S.T., M.Si.</strong><br />
            NIP. 19740513 200901 1 004</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <div id="soppelayanan" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
         <!--  <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modal Header2</h4>
          </div> -->
          <div class="modal-body">
            <h3 style="text-align:center"><strong>SOP PELAYANAN</strong></h3>
            <p>Download SOP Pelayanan : <a href="sop_pelayanan.zip">Download</a></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
  </body>

  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript">
  	$(document).ready(function(){
  		$('body').css('background-image','url(assets/images/background.jpg)');
  		$('body').css('background-size','cover');
  		$('body').css('background-position','center');

		var height = $(window).height();
		$("section").css("min-height", height);

		$(window).resize(function(){
		var newHeight = $(window).height();
		$("section").css("min-height", newHeight);
		});
	});
  </script>

</html>
