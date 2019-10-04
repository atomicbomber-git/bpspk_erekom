<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>E-Rekomendasi</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css" media="screen" />
    <link rel="stylesheet" href="assets/stylesheets/index-home.css" media="screen" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,600,700' rel='stylesheet' type='text/css'>
    <!-- <link rel="stylesheet" href="http://www.jfxjournal.com/public/assets/plugin/font-awesome-4.5.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.min.css">


  </head>
  <body>
    <section>
      <div class="container">
        <img src="assets/images/logo-bpspl.png" alt="" class="logo"/>
        <h3 class="title">Aplikasi E-Rekomendasi Pelayanan Lalu-lintas Hiu dan Pari <br /> LPSPL Serang</h3>
        <ul class="menu">
          <li>
            <a href="http://e-rekomendasi.bpsplpontianak.com/pengajuan/">
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
            <a href="http://e-rekomendasi.bpsplpontianak.com/pengajuan/index.php?daftar">
              <div class="body-icon">
                <span class="fa fa-files-o fa-2x"></span>
                <span class="title">Pendaftaran</span>
              </div>
            </a>
          </li>
          <li>
            <a href="http://e-rekomendasi.bpsplpontianak.com/pemeriksaan/">
              <div class="body-icon">
                <span class="fa fa-search fa-2x"></span>
                <span class="title">Pemeriksaan</span>
              </div>
            </a>
          </li>
          <li>
            <a href="http://e-rekomendasi.bpsplpontianak.com/admin/">
              <div class="body-icon">
                <span class="fa fa-user fa-2x"></span>
                <span class="title">Admin</span>
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
      </div>
    </section>
  </body>

  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript">
  	$(document).ready(function(){
		var height = $(window).height();
		$("section").css("min-height", height);

		$(window).resize(function(){
		var newHeight = $(window).height();
		$("section").css("min-height", newHeight);
		});
	});
  </script>

</html>
