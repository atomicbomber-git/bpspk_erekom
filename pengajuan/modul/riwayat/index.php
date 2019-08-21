<?php
@include_once ("../engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, pnotify.custom.css, select2.css, codemirror.css, monokai.css, bootstrap-tagsinput.css, bootstrap-timepicker.css, theme.css, default.css, datatables.css, modernizr.js";

$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholde,jquery.dataTables.js,datatables.js, pnotify.custom.js, jquery.appear.js, select2.js, jquery.autosize.js, bootstrap-tagsinput.js, bootstrap-timepicker.js, theme.js, theme.init.js,jquery.validate.js,jquery.validate.msg.id.js ";

@include(c_THEMES."meta.php");

$SCRIPT_FOOT="
<script>
$(document).ready(function(){
	$('nav li.nav4').addClass('nav-active');
	$('#listriwayat').DataTable({
		'ajax': {
        	'url':'modul/riwayat/ajax.php',
        	'method':'POST',
        	'data': function ( d ) {
                d.cari = $('#q').val();
                d.a='lp';
            }
        },
        'pageLength': 10,
        'deferRender': true,
        'serverSide':true,
        'processing':true,
		'filter':false,
		'ordering':false,
		'lengthChange': false,
		'language': {
            'sProcessing':   'Sedang memproses...',
			'sLengthMenu':   'Tampilkan _MENU_ entri',
			'sZeroRecords':  'Tidak ditemukan data',
			'sInfo':         'Menampilkan _START_ sampai _END_ dari _TOTAL_ entri',
			'sInfoEmpty':    'Menampilkan 0 sampai 0 dari 0 entri',
			'sInfoFiltered': '(difilter dari _MAX_ entri keseluruhan)',
			'sInfoPostFix':  '',
			'sUrl':          '',
			'oPaginate': {
				'sFirst':    'Pertama',
				'sPrevious': 'Sebelumnya',
				'sNext':     'Selanjutnya',
				'sLast':     'Terakhir'
			}
        }
	});

	$('#form_cari').submit(function(e) {
		e.preventDefault();
		var dtable=$('#listriwayat').DataTable();
		dtable.draw();
	});
});
</script>";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Riwayat Permohonan Rekomendasi</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_DOMAIN;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Riwayat Permohonan Rekomendasi</span></li>
			</ol>
			<a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-9">
		</div>
		<div class="col-md-3">
			<form action="" method="POST" id="form_cari" name="form_cari" class="search">
				<div class="input-group input-search">
					<input type="text" class="form-control" name="q" id="q" placeholder="Cari...">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
					</span>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Riwayat Permohonan Rekomendasi</h2>
				</header>
				<div class="panel-body">
					<table class="table table-hover table-striped mb-none" id="listriwayat">
						<thead>
							<tr>
								<th width="5%">No</th>
								<th>Tujuan Pengiriman</th>
								<th width="20%">Tanggal Pengajuan</th>
								<th width="10%">No Antrian</th>
								<th width="25%">Status</th>
								<th width="17%">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
<?php
include(AdminFooter);
?>