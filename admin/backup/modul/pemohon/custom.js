$(document).ready(function(){
	$("#listpemohon").DataTable({
		"ajax": {
        	"url":"ajax.php",
        	"method":"POST",
        	"data": function ( d ) {
                d.cari = $('#q').val();
                d.a="list-pemohon";
            }
        },
        "pageLength": 10,
        "deferRender": true,
        "serverSide":true,
        "processing":true,
		"filter":false,
		"ordering":false,
		/*"lengthChange": false,*/
		"language": {
            "sProcessing":   "Sedang memproses...",
			"sLengthMenu":   "Tampilkan _MENU_ entri",
			"sZeroRecords":  "Tidak ditemukan data",
			"sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
			"sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
			"sInfoFiltered": "(difilter dari _MAX_ entri keseluruhan)",
			"sInfoPostFix":  "",
			"sUrl":          "",
			"oPaginate": {
				"sFirst":    "Pertama",
				"sPrevious": "Sebelumnya",
				"sNext":     "Selanjutnya",
				"sLast":     "Terakhir"
			}
        }
	});

	$("#form_cari").submit(function(e) {
		e.preventDefault();

		var dtable=$("#listpemohon").DataTable();
		dtable.draw();
	});

	$("#listriwayat").DataTable({
		"ajax": {
        	"url":"ajax.php",
        	"method":"POST",
        	"data": function ( d ) {
                //d.cari = $('#q').val();
                d.u=$('#u').val();
                d.a="list-riwayat";
            }
        },
        "pageLength": 10,
        "deferRender": true,
        "serverSide":true,
        "processing":true,
		"filter":false,
		"ordering":false,
		/*"lengthChange": false,*/
		"language": {
            "sProcessing":   "Sedang memproses...",
			"sLengthMenu":   "Tampilkan _MENU_ entri",
			"sZeroRecords":  "Tidak ditemukan data",
			"sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
			"sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
			"sInfoFiltered": "(difilter dari _MAX_ entri keseluruhan)",
			"sInfoPostFix":  "",
			"sUrl":          "",
			"oPaginate": {
				"sFirst":    "Pertama",
				"sPrevious": "Sebelumnya",
				"sNext":     "Selanjutnya",
				"sLast":     "Terakhir"
			}
        }
	});

	$("#listpetugas").on('click', '.ganti_ptgs', function(event) {
		event.preventDefault();
		var idpl = $(this).data('id');
		var nama = $(this).data('nama');
		$("#idtb").val(idpl);
		$(".textpesan").html("Ganti "+nama);

		$.magnificPopup.open({
		  	items: {
		    	src: '#GantiPeg', // can be a HTML string, jQuery object, or CSS selector
		    	type: 'inline',
				fixedContentPos: false,
				fixedBgPos: true,
				overflowY: 'auto',
				closeBtnInside: true,
				preloader: false,
				midClick: true,
				removalDelay: 300,
				mainClass: 'my-mfp-slide-bottom',
				modal: true
		  	}
		});
	});
	/*Aksi menutup pesan konfirmasi penghapusan*/
	$("#GantiPeg").on('click', '.modal-dismiss', function (e) {
		$("#idtb").val("");
		$(".textpesan").html("");
		$("#formgantipeg").off();
		e.preventDefault();
		$.magnificPopup.close();
	});
	/*Aksi konfirmasi penghaspusan*/
	$("#GantiPeg").on('click', '.modal-confirm', function (e) {
		e.preventDefault();
		$.magnificPopup.close();

		var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
		$.ajax({
			url:'ajax.php',
			dataType:'json',
			type:'post',
			data:$("#formgantipeg").serialize(),
			success: function(json){
				if(json.stat){
					var notice = new PNotify({
						title: 'Notification',
						text: json.msg,
						type: 'success',
						addclass: 'stack-bar-bottom',
						stack: stack_bar_bottom,
						width: "60%",
						delay:1000,
						after_close: function() {
					        location.reload();
					    }
					});
				}else{
					var notice = new PNotify({
						title: 'Notification',
						text: json.msg,
						type: 'warning',
						addclass: 'stack-bar-bottom',
						stack: stack_bar_bottom,
						width: "60%",
						delay:2500
					});
				}
			}
		});
	});
});