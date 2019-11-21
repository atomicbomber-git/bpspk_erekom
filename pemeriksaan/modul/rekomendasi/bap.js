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

    $('.datepicker').bootstrapMaterialDatePicker({time:false,format:'MM/DD/YYYY'});

    $("#bap_add").validate({
        ignore: [],
        errorClass: "error",
        rules:{
            no_surat:{
                required:true,
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
                required:"No Surat Harus Diisi.",
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