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
	$("#list-pemeriksaan").DataTable({
		"ajax": {
        	"url":"ajax.php",
        	"method":"POST",
        	"data": function ( d ) {
                d.cari = $('#q').val();
                d.a="dtlist-periksa";
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

		var dtable=$("#list-pemeriksaan").DataTable();
		dtable.draw();
	});

	$(".jns_ikan").select2({
		width: '90%'
	});

	$("#formhasilperiksa").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			jenis_ikan:{
				required:true
			},
			pjg:{
				required:true
			},
			lbr:{
				required:true
			},
			berat:{
				required:true,
			},
			jenis_sampel:{
				required:true
			}
		},
		messages:{
			jenis_ikan:{
				required:"Jenis Ikan Harus Dipilih."
			},
			pjg:{
				required:"Panjang Sampel Harus Diisi."
			},
			lbr:{
				required:"Lebar Sampel Harus Diisi."
			},
			berat:{
				required:"Berat Sampel Harus Diisi.",
			},
			jenis_sampel:{
				required:"Jenis Produk Harus Dipilih."
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
			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				data:$("#formhasilperiksa").serialize(),
				beforeSend:function(){
					$('#actloadingmd').show();
					$('#btn_save').prop('disabled', true);
				},
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
					$('#btn_save').prop('disabled', false);
					$('#actloadingmd').hide();
				}
			});
    		return false;
  		}
	});

	$("#fupdatehslperiksa").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			jenis_ikan:{
				required:true
			},
			pjg:{
				required:true
			},
			lbr:{
				required:true
			},
			berat:{
				required:true,
			},
			jenis_sampel:{
				required:true
			}
		},
		messages:{
			jenis_ikan:{
				required:"Jenis Ikan Harus Dipilih."
			},
			pjg:{
				required:"Panjang Sampel Harus Diisi."
			},
			lbr:{
				required:"Lebar Sampel Harus Diisi."
			},
			berat:{
				required:"Berat Sampel Harus Diisi.",
			},
			jenis_sampel:{
				required:"Jenis Produk Harus Dipilih."
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
			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				data:$("#fupdatehslperiksa").serialize(),
				beforeSend:function(){
					$('#actloadingmd').show();
					$('#btn_save').prop('disabled', true);
				},
				success: function(json){
					if(json.stat){
						var notice = new PNotify({
							title: 'Notification',
							text: json.msg,
							type: 'success',
							addclass: 'stack-bar-bottom',
							stack: stack_bar_bottom,
							width: "60%",
							delay:1000
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
					$('#btn_save').prop('disabled', false);
					$('#actloadingmd').hide();
				}
			});
    		return false;
  		}
	});
		
	$('.ak_div').on('change', '.asal_komoditas', function(event) {
		event.preventDefault();
		var asal=$(this).val();
		var div=$(this).closest('div');
		if(asal=='lainnya'){
			div.find('.custom_ak').show();
		}else{
			div.find('.custom_ak').hide();
		}
	});

	$("#tabelhasilperiksa").on('click', '.btn_hps_hasilper', function(event) {
		event.preventDefault();
		var idhasil=$(this).data('delid');
		$("#btndelhasilpem").data('id',idhasil);
		$.magnificPopup.open({
		  	items: {
		    	src: '#DelHasilModal', // can be a HTML string, jQuery object, or CSS selector
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
	$("#DelHasilModal").on('click', '.modal-dismiss', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});
	/*Aksi konfirmasi penghaspusan*/
	$("#DelHasilModal").on('click', '.modal-confirm', function (e) {
		e.preventDefault();
		var idhasil=$(this).data('id');

		var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
		$.ajax({
			url:'ajax.php',
			dataType:'json',
			type:'post',
			data:'a=del-hsl-periksa&iddt='+idhasil,
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
				$.magnificPopup.close();
			}
		});
	});

	$("#update_pemeriksaan").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			tgl_pemeriksaan:{
				required:true
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
  			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$("#update_pemeriksaan").serialize(),
				beforeSend:function(){
					$('#btn_simpan').prop('disabled', true);
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
							delay:1000/*,
							after_close:function(){
								location.href="./pemeriksaan-sample.php";
							}*/
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
					$('#actloading').hide();
				}
			});
    		return false;
  		}
	});
	//----------------------------------------------------------------------------
	$('#tbl_gambar').DataTable({
		'ordering':false,
		"pageLength": 5,
		"lengthChange": false
	});

	$("#tbl_gambar").on('click', '.delete-row', function(event) {
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
	$("#DelModal").on('click', '.modal-dismiss', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});
	$("#DelModal").on('click', '.modal-confirm', function (e) {
		var dtable=$("#tbl_gambar").DataTable();
		var PID = $("#del_pid").data('id');
		e.preventDefault();
		$.magnificPopup.close();

		var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
		$.ajax({
			url:'ajax.php',
			dataType:'json',
			type:'post',
			data:'a=delft&idft='+PID,
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
					        //dtable.draw();
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

	$("#tbl_gambar").on('click', '.edit-row', function(event) {
		event.preventDefault();
		var PID = $(this).data('id');
		var ket = $(this).data('edit');
		$("#epid").data('id', PID);
		$("#ket_foto_edit").val(ket);

		$.magnificPopup.open({
		  	items: {
		    	src: '#EditModal', // can be a HTML string, jQuery object, or CSS selector
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
	$("#EditModal").on('click', '.modal-dismiss', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});
	$("#EditModal").on('click', '.modal-confirm', function (e) {
		var dtable=$("#tbl_gambar").DataTable();
		var PID = $("#epid").data('id');
		e.preventDefault();
		$.magnificPopup.close();

		var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
		$.ajax({
			url:'ajax.php',
			dataType:'json',
			type:'post',
			data:'a=upft&idft='+PID+'&ket='+$("#ket_foto_edit").val(),
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
					        //dtable.draw();
					        $("#ket_foto_edit").val('')
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

	$('.img-prev').magnificPopup({type:'image'});

	$("#upload_foto").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			file_foto:{
				required:true
			},
			ket_foto:{
				minlength:4
			}
		},
		messages:{
			file_foto:{
				required:"Foto Belum Dipilih"
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
  			var dtable=$("#tbl_gambar").DataTable();
  			var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
  			var formData = new FormData(document.getElementById("upload_foto"));
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
					$('#btn_upload').prop('disabled', true);
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
								//$("#upload_foto")[0].reset();
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
					$('#btn_upload').prop('disabled', false);
					$('#actloading').hide();
				}
			});
    		return false;
  		}
	});
	//----------------------------------------------------------------------------

	$("#bap_add").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			no_surat:{
				required:true
			},
			tgl_penetapan:{
				required:true
			},
			lokasi_penetapan:{
				required:true
			},
			ptg1:{
				required:true,
			},
			ptg2:{
				required:true
			}
		},
		messages:{
			no_surat:{
				required:"No Surat Harus Diisi."
			},
			tgl_penetapan:{
				required:"Tanggal Penetapan Berita Acara Harus Diisi."
			},
			lokasi_penetapan:{
				required:"Lokasi Penetapan Berita Acara Harus Diisi."
			},
			ptg1:{
				required:"Petugas Harus Dipilih",
			},
			ptg2:{
				required:"Petugas Harus Dipilih"
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
  			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$("#bap_add").serialize(),
				beforeSend:function(){
					$('#btn_simpan').prop('disabled', true);
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
								//location.href="./pemeriksaan-sample.php";
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
					$('#btn_simpan').prop('disabled', false);
					$('#actloading').hide();
				}
			});
    		return false;
  		}
	});

	$("#bap_update").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			no_surat:{
				required:true
			},
			tgl_penetapan:{
				required:true
			},
			lokasi_penetapan:{
				required:true
			},
			ptg1:{
				required:true,
			},
			ptg2:{
				required:true
			}
		},
		messages:{
			no_surat:{
				required:"No Surat Harus Diisi."
			},
			tgl_penetapan:{
				required:"Tanggal Penetapan Berita Acara Harus Diisi."
			},
			lokasi_penetapan:{
				required:"Lokasi Penetapan Berita Acara Harus Diisi."
			},
			ptg1:{
				required:"Petugas Harus Dipilih",
			},
			ptg2:{
				required:"Petugas Harus Dipilih"
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
  			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$("#bap_update").serialize(),
				beforeSend:function(){
					$('#btn_simpan').prop('disabled', true);
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
								//location.href="./pemeriksaan-sample.php";
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
					$('#btn_simpan').prop('disabled', false);
					$('#actloading').hide();
				}
			});
    		return false;
  		}
	});

});