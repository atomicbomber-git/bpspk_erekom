$(document).ready(function(){
	$('#tbl_gambar').DataTable({
		'ordering':false,
		"pageLength": 5,
		"lengthChange": false
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

	$("#tbl_gambar").on('click', '.delete-row', function(event) {
		event.preventDefault();
		var PID = $(this).data('del');
		(new PNotify({
            title: 'Konfirmasi Penghapusan',
            text: 'Anda yakin akan menghapus hasil pemeriksaan ini?',
            icon: 'glyphicon glyphicon-question-sign',
            hide: false,
            confirm: {
                confirm: true
            },
            buttons: {
                closer: false,
                sticker: false
            },
            history: {
                history: false
            }
        })).get().on('pnotify.confirm', function() {
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
						delay:2500
					});
				}
			}
		});
        })
	});
});