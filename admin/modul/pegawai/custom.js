$(document).ready(function(){
	$("#tblistpegawai").DataTable({
		"ajax": {
        	"url":"ajax.php",
        	"method":"POST",
        	"data": function ( d ) {
                d.cari = $('#q').val();
                d.filter_satker = $('#satker').val();
                d.a="dtlist";
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

		var dtable=$("#tblistpegawai").DataTable();
		dtable.draw();
	});

	$("#form_filter").submit(function(e) {
		e.preventDefault();

		var dtable=$("#tblistpegawai").DataTable();
		dtable.draw();
	});

	$("#pegawai_add").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nm_lengkap:{
				required:true
			},
			nip:{
				required:true
			},
			email:{
				required:true,
				email:true
			},
			satker:{
				required:true
			},
			file_ttd:{
				required:true
			}
		},
		messages:{
			nm_lengkap:{
				required:"Nama Lengkap Tidak Boleh Kosong"
			},
			nip:{
				required:"NIP Pegawai Tidak Boleh Kosong"
			},
			email:{
				required:"Email Harus Diisi"
			},
			satker:{
				required:"Satuan Kerja Harus Dipilih"
			},
			file_ttd:{
				required:"Pastikan File Sudah Dipilih"
			}
		},
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
  			var formData = new FormData(document.getElementById("pegawai_add"));
  			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:formData,
				mimeType:'multipart/form-data',
				contentType: false,
				processData:false,
				beforeSend:function(){
					$('#btn_simpan').prop('disabled', true);
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
								$("#pegawai_add")[0].reset();
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
					$('#btn_simpan').prop('disabled', false);
				}
			});
    		return false;
  		}
	});

	$("#pegawai_update").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nm_lengkap:{
				required:true
			},
			nip:{
				required:true
			},
			email:{
				required:true,
				email:true
			},
			satker:{
				required:true
			},
			file_ttd:{
				required:function (element) {
					if($("#gantittd").is(':checked')){
						return true                           
					}else{
						return false;
					}  
				}
			}
		},
		messages:{
			nm_lengkap:{
				required:"Nama Lengkap Tidak Boleh Kosong"
			},
			nip:{
				required:"NIP Pegawai Tidak Boleh Kosong"
			},
			email:{
				required:"Email Harus Diisi"
			},
			satker:{
				required:"Satuan Kerja Harus Dipilih"
			},
			file_ttd:{
				required:"Pastikan File Sudah Dipilih"
			}
		},
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
  			var formData = new FormData(document.getElementById("pegawai_update"));
  			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:formData,
				mimeType:'multipart/form-data',
				contentType: false,
				processData:false,
				beforeSend:function(){
					$('#btn_simpan').prop('disabled', true);
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
								$("#pegawai_add")[0].reset();
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
					$('#btn_simpan').prop('disabled', false);
				}
			});
    		return false;
  		}
	});

	$("#tblistpegawai").on('click', '.delete-row', function(event) {
		event.preventDefault();
		var PID = $(this).data('del');
		$("#del_pid").data('id', PID);

		$.magnificPopup.open({
		  	items: {
		    	src: '#DelModal', // can be a HTML string, jQuery object, or CSS selector
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
	$("#DelModal").on('click', '.modal-dismiss', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});
	/*Aksi konfirmasi penghaspusan*/
	$("#DelModal").on('click', '.modal-confirm', function (e) {
		var dtable=$("#tblistpegawai").DataTable();
		var PID = $("#del_pid").data('id');
		e.preventDefault();
		$.magnificPopup.close();

		var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
		$.ajax({
			url:'ajax.php',
			dataType:'json',
			type:'post',
			data:'a=delete&iddt='+PID,
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
					        dtable.draw();
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