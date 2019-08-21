$(document).ready(function(){
    $("#q_add").validate({
        ignore: [],
        rules:{
            pertanyaan:{
                required:true
            }
        },
        messages:{
            pertanyaan:{
                required:"Pertanyaan tidak boleh kosong"
            }
        },
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
                data:$("#q_add").serialize(),
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

    $("#ref-pertanyaan").on('click', '.row-delete', function(event) {
        event.preventDefault();
        var PID = $(this).data('id');
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
        var PID = $("#del_pid").data('id');
        e.preventDefault();
        $.magnificPopup.close();

        var stack_bar_bottom = {"dir1": "up", "dir2": "right", "spacing1": 0, "spacing2": 0};
        $.ajax({
            url:'ajax.php',
            dataType:'json',
            type:'post',
            data:'a=del_q&idq='+PID,
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