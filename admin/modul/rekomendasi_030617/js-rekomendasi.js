$(document).ready(function() {
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
	$("#rek_add").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			no_surat:{required:true},
			tgl_surat:{required:true},
			penandatgn:{required:true}
		},
		messages:{
			no_surat:{required:"Nomor Surat Harus DiIsi."},
			tgl_surat:{required:"Tanggal Surat Harus Diisi."},
			penandatgn:{required:"Penandatangan Surat Harus Dipilih"}
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
				data:$("#rek_add").serialize(),
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

	$("#rek_update").validate({
		ignore: [],
		errorClass: "error",
		rules:{
			no_surat:{required:true},
			tgl_surat:{required:true},
			penandatgn:{required:true}
		},
		messages:{
			no_surat:{required:"Nomor Surat Harus DiIsi."},
			tgl_surat:{required:"Tanggal Surat Harus Diisi."},
			penandatgn:{required:"Penandatangan Surat Harus Dipilih"}
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
				data:$("#rek_update").serialize(),
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

	$("#btn_submit").click(function(event) {
		var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
		var token=$(this).data('token');
		$.ajax({
			url:'ajax.php',
			dataType:'json',
			type:'post',
			cache:false,
			data:"a=submit&rek="+$("#rek").val()+"&token="+token,
			beforeSend:function(){
				$('#btn_submit').prop('disabled', true);
				$('#actsubmit').show();
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
							location.href='./pemeriksaan-sample.php';
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
				$('#btn_submit').prop('disabled', false);
				$('#actsubmit').hide();
			}
		});
		return false;
	});

	$('#btn_reload_data').click(function(){
		var rek=$(this).data('idrek');

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

        $('#DelModal').on('click', '.modal-dismiss', function (e) {
	        e.preventDefault();
	        $.magnificPopup.close();
	    });

	    $('#DelModal').on('click', '.modal-confirm', function (e) {
	        e.preventDefault();
	        $.magnificPopup.close();

	        var stack_bar_bottom = {'dir1': 'up', 'dir2': 'right', 'spacing1': 0, 'spacing2': 0};
	        $.ajax({
	            url:'ajax.php',
	            dataType:'json',
	            type:'post',
	            data:'a=reset_tbl_rek&idrek='+rek,
	            success: function(json){
	                if(json.stat){
	                    var notice = new PNotify({
	                        title: 'Notification',
	                        text: json.msg,
	                        type: 'success',
	                        addclass: 'stack-bar-bottom',
	                        stack: stack_bar_bottom,
	                        width: '60%',
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
	                        width: '60%',
	                        delay:2500
	                    });
	                }
	            }
	        });
	    });
	});
});