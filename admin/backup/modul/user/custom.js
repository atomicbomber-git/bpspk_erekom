$(document).ready(function() {
	$("#tblistuser").DataTable({
		"ajax": {
        	"url":"ajax.php",
        	"method":"POST",
        	"data": function ( d ) {
                d.cari = $('#q').val();
                d.filterlvl = $('#filterlvl').val();
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

		var dtable=$("#tblistuser").DataTable();
		dtable.draw();
	});

	$("#hak_akses").change(function(event) {
		if($("#hak_akses").val()!=100){
			$('.admin-group').hide();
			$('.kep-group').show();
		}else{
			$('.admin-group').show();
			$('.kep-group').hide();
		}
	});

	$("#add_user").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			username:{
				required:true,
				remote: {
		            url: "ajax.php",
		            type: "post",
		            data:{
						username: function() {
				            return $( "#username" ).val();
				        },
				        a:"user_check"
					}
	       		},
	       		minlength:3
			},
			nama_lengkap:{
				required:function(){
					if($("#hak_akses").val()!=100){
						return false;
					}else{
						return true;
					}
				},
				minlength:3
			},
			email:{
				email:true
			},
			pwd:{
				required:true,
				minlength: 4
			},
			repeat_pwd:{
				equalTo:'#pwd'
			}
		},
		messages:{
			username:{
				required:"Username Harus Diisi.",
				remote:"Username Sudah Ada"
			},
			nama_lengkap:{
				required:"Nama Lengkap Tidak Boleh Kosong."
			},
			pwd:{
				required:"Password Harap Diisi."
			}
		},
		errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        highlight: function (element, validClass) {
            $(element).parent().addClass("has-error");
        },
        unhighlight: function (element, validClass) {
            $(element).parent().removeClass("has-error");
        },
  		submitHandler: function(form) {
  			var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
  			$.ajax({
				url:"ajax.php",
				dataType:"json",
				type:"post",
				cache:false,
				data:$("#add_user").serialize(),
				beforeSend:function(){
					$("#btn_add").prop("disabled", true);
					$("#actloading").show();
				},
				success:function(json){	
					if(json.stat){
						var notice = new PNotify({
							title: "Notification",
							text: json.msg,
							type: "success",
							addclass: "stack-bar-bottom",
							stack: stack_bar_bottom,
							width: "60%",
							delay:1000,
							after_close:function(){
								$("#add_user")[0].reset();
							}
						});
					}else{
						var notice = new PNotify({
							title: "Notification",
							text: json.msg,
							type: "warning",
							addclass: "stack-bar-bottom",
							stack: stack_bar_bottom,
							width: "60%",
							delay:2500
						});
					}
					$("#btn_add").prop("disabled", false);
					$("#actloading").hide();
				}
			});
  		}
	});

	$("#update_user").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			nama_lengkap:{
				required:true,
				minlength:3
			},
			email:{
				email:true
			},
			pwd:{
				minlength: 4
			},
			repeat_pwd:{
				equalTo:'#pwd'
			}
		},
		messages:{
			nama_lengkap:{
				required:"Nama Lengkap Tidak Boleh Kosong."
			}
		},
		errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        highlight: function (element, validClass) {
            $(element).parent().addClass("has-error");
        },
        unhighlight: function (element, validClass) {
            $(element).parent().removeClass("has-error");
        },
  		submitHandler: function(form) {
  			var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
  			$.ajax({
				url:"ajax.php",
				dataType:"json",
				type:"post",
				cache:false,
				data:$("#update_user").serialize(),
				beforeSend:function(){
					$("#btn_update").prop("disabled", true);
					$("#actloading").show();
				},
				success:function(json){	
					if(json.stat){
						var notice = new PNotify({
							title: "Notification",
							text: json.msg,
							type: "success",
							addclass: "stack-bar-bottom",
							stack: stack_bar_bottom,
							width: "60%",
							delay:1000
						});
					}else{
						var notice = new PNotify({
							title: "Notification",
							text: json.msg,
							type: "warning",
							addclass: "stack-bar-bottom",
							stack: stack_bar_bottom,
							width: "60%",
							delay:2500
						});
					}
					$("#btn_update").prop("disabled", false);
					$("#actloading").hide();
				}
			});
  		}
	});

	$("#tblistuser").on('click', '.delete-row', function(event) {
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
		var dtable=$("#tblistuser").DataTable();
		var PID = $("#del_pid").data('id');
		e.preventDefault();
		$.magnificPopup.close();

		var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
		$.ajax({
			url:'ajax.php',
			dataType:'json',
			type:'post',
			data:'a=delete&data='+PID,
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
					        nav_level_reload();
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

function filterlvl(lvl){
	switch (lvl){
		case 'all':
			$("#filterlvl").val('all');
			$(".ulvl").html('');
			$("#lvl_all").css('font-weight', 'bold');
			$("#lvl_admin,#lvl_plh,#lvl_kepala,#lvl_vr").css('font-weight', 'normal');
		break;

		case '100':
			$("#filterlvl").val('100');
			$(".ulvl").html('Admin');
			$("#lvl_admin").css('font-weight', 'bold');
			$("#lvl_plh,#lvl_kepala,#lvl_vr").css('font-weight', 'normal');
		break;

		case '90':
			$("#filterlvl").val('90');
			$(".ulvl").html('Kepala Balai');
			$("#lvl_kepala").css('font-weight', 'bold');
			$("#lvl_admin,#lvl_plh,#lvl_vr").css('font-weight', 'normal');
		break;

		case '91':
			$("#filterlvl").val('91');
			$(".ulvl").html('Plh. Kepala Balai');
			$("#lvl_plh").css('font-weight', 'bold');
			$("#lvl_admin,#lvl_kepala,#lvl_vr").css('font-weight', 'normal');
		break;

		case '95':
			$("#filterlvl").val('95');
			$(".ulvl").html('Verifikator');
			$("#lvl_vr").css('font-weight', 'bold');
			$("#lvl_admin,#lvl_kepala,#lvl_plh").css('font-weight', 'normal');
		break;

	}

	var dtable=$("#tblistuser").DataTable();
	dtable.draw();
}

function nav_level_reload() {
	$.ajax({
		url: 'ajax.php',
		type: 'POST',
		dataType: 'html',
		data: 'a=resetnav',
		success:function(html){	
			$(".nav_user_level").html(html);
		}
	});
}