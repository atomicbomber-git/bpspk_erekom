$(document).ready(function(){
	$("#dtikan_add").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nm_ikan:{
				required:true
			},
			nm_latin:{
				required:true
			}
		},
		messages:{
			nm_ikan:{
				required:"Nama Ikan Tidak Boleh Kosong"
			},
			nm_latin:{
				required:"Nama Latin Ikan Tidak Boleh Kosong"
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
  			// var dtable=$("#ref-dtikan").DataTable();
  			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$("#dtikan_add").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
								location.reload();
								// dtable.draw();
								// $("#dtikan_add")[0].reset();
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});

	$("#dtikan_update").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nm_ikan:{
				required:true
			},
			nm_latin:{
				required:true
			}
		},
		messages:{
			nm_ikan:{
				required:"Nama Ikan Tidak Boleh Kosong"
			},
			nm_latin:{
				required:"Nama Latin Ikan Tidak Boleh Kosong"
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
				data:$("#dtikan_update").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});
	
	$("#ref-dtikan").on('click', '.row-delete', function(event) {
		event.preventDefault();
		var id=$(this).data('id');
		delete_data(id,'deldtikan');
	});

	$("#dtsatker_add").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			satker:{
				required:true
			},
			kd_nosurat:{
				required:true
			}
		},
		messages:{
			satker:{
				required:"Nama Satuan Kerja Tidak Boleh Kosong"
			},
			kd_nosurat:{
				required:"Kode Nomor Surat Tidak Boleh Kosong"
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
  			// var dtable=$("#ref-dtikan").DataTable();
  			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$("#dtsatker_add").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
								location.reload();
								// dtable.draw();
								// $("#dtsatker_add")[0].reset();
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});

	$("#dtsatker_update").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			satker:{
				required:true
			},
			kd_nosurat:{
				required:true
			}
		},
		messages:{
			satker:{
				required:"Nama Satuan Kerja Tidak Boleh Kosong"
			},
			kd_nosurat:{
				required:"Kode Nomor Surat Tidak Boleh Kosong"
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
				data:$("#dtsatker_update").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});
	
	$("#ref-satker").on('click', '.row-delete', function(event) {
		event.preventDefault();
		var id=$(this).data('id');
		delete_data(id,'deldtsatker');
	});

	$("#dtbk_add").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nama:{
				required:true
			},
			email:{
				required:true,
				email:true
			}
		},
		messages:{
			nama:{
				required:"Nama Balai Tidak Boleh Kosong"
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
  			// var dtable=$("#ref-dtbk").DataTable();
  			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$("#dtbk_add").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
								location.reload();
								// dtable.draw();
								// $("#dtbk_add")[0].reset();
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});

	$("#dtbk_update").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nama:{
				required:true
			},
			email:{
				required:true,
				email:true
			}
		},
		messages:{
			nama:{
				required:"Nama Balai Tidak Boleh Kosong"
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
				data:$("#dtbk_update").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});
	
	$("#ref-dtbk").on('click', '.row-delete', function(event) {
		event.preventDefault();
		var id=$(this).data('id');
		delete_data(id,'deldtbk');
	});

	$("#dtjproduk_add").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nm_jenis:{
				required:true
			}
		},
		messages:{
			nm_jenis:{
				required:"Nama Jenis Produk Tidak Boleh Kosong"
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
  			// var dtable=$("#ref-dtjproduk").DataTable();
  			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				cache:false,
				data:$("#dtjproduk_add").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
								location.reload();
								// dtable.draw();
								// $("#dtjproduk_add")[0].reset();
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});

	$("#dtjproduk_update").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nm_jenis:{
				required:true
			}
		},
		messages:{
			nm_jenis:{
				required:"Nama Jenis Produk Tidak Boleh Kosong"
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
				data:$("#dtjproduk_update").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});
	
	$("#ref-dtjproduk").on('click', '.row-delete', function(event) {
		event.preventDefault();
		var id=$(this).data('id');
		delete_data(id,'deldtjproduk');
	});

	//curd ciri-ciri ikan
	$("#crikan_add").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			produk:{
				required:true
			},
			ciri_ciri:{
				required:true
			}
		},
		messages:{
			produk:{
				required:"Jenis Produk Harus Dipilih"
			},
			ciri_ciri:{
				required:"Ciri Ciri Tidak boleh kosong"
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
				data:$("#crikan_add").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});

	$("#crikan_update").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			produk:{
				required:true
			},
			ciri_ciri:{
				required:true
			}
		},
		messages:{
			produk:{
				required:"Jenis Produk Harus Dipilih"
			},
			ciri_ciri:{
				required:"Ciri Ciri Tidak boleh kosong"
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
				data:$("#crikan_update").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});
	
	$("#ref-crikan").on('click', '.row-delete', function(event) {
		event.preventDefault();
		var id=$(this).data('id');
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

		$("#DelModal").on('click', '.modal-dismiss', function (e) {
			e.preventDefault();
			$.magnificPopup.close();
		});

		$("#DelModal").on('click', '.modal-confirm', function (e) {
			e.preventDefault();
			$.magnificPopup.close();
			$("#DelModal").off();

			var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
			$.ajax({
				url:'ajax.php',
				dataType:'json',
				type:'post',
				data:'a=delcrikan&idcr='+id,
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
							after_close:function(){
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

	//curd upt prl
	$("#dtuptprl_add").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nama:{
				required:true
			},
			email:{
				required:true,
				email:true
			}
		},
		messages:{
			nama:{
				required:"Nama UPT PRL Tidak Boleh Kosong"
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
				data:$("#dtuptprl_add").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});

	$("#dtuptprl_update").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nama:{
				required:true
			},
			email:{
				required:true,
				email:true
			}
		},
		messages:{
			nama:{
				required:"Nama UPT PRL Tidak Boleh Kosong"
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
				data:$("#dtuptprl_update").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});
	
	$("#ref-dtuptprl").on('click', '.row-delete', function(event) {
		event.preventDefault();
		var id=$(this).data('id');
		delete_data(id,'deluptprl');
	});

	//curd upt psdkp
	$("#dtpsdkp_add").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nama:{
				required:true
			},
			email:{
				required:true,
				email:true
			}
		},
		messages:{
			nama:{
				required:"Nama UPT PRL Tidak Boleh Kosong"
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
				data:$("#dtpsdkp_add").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});

	$("#dtpsdkp_update").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nama:{
				required:true
			},
			email:{
				required:true,
				email:true
			}
		},
		messages:{
			nama:{
				required:"Nama UPT PRL Tidak Boleh Kosong"
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
				data:$("#dtpsdkp_update").serialize(),
				beforeSend:function(){
					$('#btn-aksi').prop('disabled', true);
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
					$('#btn-aksi').prop('disabled', false);
				}
			});
    		return false;
  		}
	});
	
	$("#ref-dtpsdkp").on('click', '.row-delete', function(event) {
		event.preventDefault();
		var id=$(this).data('id');
		delete_data(id,'delpsdkp');
	});
});

function delete_data(id,ac){
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

	$("#DelModal").on('click', '.modal-dismiss', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});

	$("#DelModal").on('click', '.modal-confirm', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
		$("#DelModal").off();

		var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
		$.ajax({
			url:'ajax.php',
			dataType:'json',
			type:'post',
			data:'a='+ac+'&iddt='+id,
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
						after_close:function(){
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
}
