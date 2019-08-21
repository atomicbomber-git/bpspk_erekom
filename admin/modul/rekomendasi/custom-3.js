$(document).ready(function(){
	$( '.editor' ).ckeditor({
    	wordcount:{
    		showParagraphs: false,
    		showCharCount: true
    	},
    	qtClass: 'table table-hover table-bordered',
    	qtBorder: '0',
    	toolbar : [
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
            { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo','-' ] }
        ]
    });
	//----------------------------------------------------------------------------
	$("#list-persetujuankepala").DataTable({
		"ajax": {
        	"url":"ajax.php",
        	"method":"POST",
        	"data": function ( d ) {
                d.cari = $('#q').val();
                d.a="dtlist-persetujuan";
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

		var dtable=$("#list-persetujuankepala").DataTable();
		dtable.draw();
	});

	$('.img-prev').magnificPopup({type:'image'});


	$("#pengesahan").validate({
		ignore: [],
		errorClass: "error",
		errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        highlight: function (element, validClass) {
            $(element).parent().addClass('has-error');
        },
        unhighlight: function (element, validClass) {
            $(element).parent().removeClass('has-error');
        },
  		submitHandler: function(form) {
  			var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
  			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$("#pengesahan").serialize(),
				beforeSend:function(){
					$('#btn_pengesahan').prop('disabled', true);
					$('#actloading').show();
				},
				success:function(json){	
					if(json.stat){
						var notice = new PNotify({
							title: 'Notification',
							text: json.msg,
							type: 'success',
							addclass: 'stack-bar-bottom',
							stack: stack_bar_bottom,
							width: "60%",
							delay:1000,
							after_close:function(){
								location.href="./list-persetujuan.php";
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
					$('#btn_pengesahan').prop('disabled', false);
					$('#actloading').hide();
				}
			});
    		return false;
  		}
	});

	$("#btn_tolak").on('click', function(event) {
		event.preventDefault();
		$.magnificPopup.open({
		  	items: {
		    	src: '#TolakModal', // can be a HTML string, jQuery object, or CSS selector
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
	$("#TolakModal").on('click', '.modal-dismiss', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});
	/*Aksi konfirmasi penghaspusan*/
	$("#TolakModal").on('click', '.modal-confirm', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
		var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
		$.ajax({
			url:'ajax.php',
			dataType:'json',
			type:'post',
			data:'a=tolak-pengesahan&idp='+$("#idp").val()+'&token='+$("#token").val()+'&pesan='+$("#msg2verifiaktor").val(),
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
					        location.href('./list-persetujuan.php');
					        $("#msg2verifiaktor").val('');
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