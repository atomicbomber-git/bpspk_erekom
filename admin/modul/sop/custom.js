$(document).ready(function(){
	$( '.editor' ).ckeditor({
    	wordcount:{
    		showParagraphs: false,
    		showCharCount: true
    	},
    	qtClass: 'table table-hover table-bordered',
    	qtBorder: '0',
        height:'600'
    	/*toolbar : [
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
            { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo','-' ] }
        ]*/
    });

    $("#form_maklumat").validate({
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
                data:$("#form_maklumat").serialize(),
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
});