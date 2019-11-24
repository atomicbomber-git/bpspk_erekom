$(document).ready(function () {
    $('.editor').ckeditor({
        wordcount: {
            showParagraphs: false,
            showCharCount: true
        },
        qtClass: 'table table-hover table-bordered',
        qtBorder: '0',
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', '-'] }
        ]
    });


    //--- hasil pemeriksaan----
    $('.jns_ikan').select2();

    $('.ak_div').on('change', '.asal_komoditas', function (event) {
        event.preventDefault();
        var asal = $(this).val();
        var div = $(this).closest('div');
        if (asal == 'lainnya') {
            div.find('.custom_ak').show();
        } else {
            div.find('.custom_ak').hide();
        }
    });

    $("#update_pemeriksaan").validate({
        ignore: [],
        errorClass: "error",
        rules: {
            tgl_pemeriksaan: {
                required: true
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
        submitHandler: function (form) {
            $.ajax({
                url: 'ajax.php',
                dataType: 'json',
                type: 'post',
                cache: false,
                data: $("#update_pemeriksaan").serialize(),
                beforeSend: function () {
                    $('#btn_simpan').prop('disabled', true);
                    $('#actloading').show();
                },
                success: function (json) {
                    if (json.stat) {
                        var notice = new PNotify({
                            title: 'Notification',
                            text: json.msg,
                            type: 'success',
                            delay: 1000/*,
                            after_close:function(){
                                location.href="./pemeriksaan-sample.php";
                            }*/
                        });
                    } else {
                        var notice = new PNotify({
                            title: 'Notification',
                            text: json.msg,
                            type: 'warning',
                            delay: 2500
                        });
                    }
                    $('#btn_simpan').prop('disabled', false);
                    $('#actloading').hide();
                }
            });
            return false;
        }
    });

    $("#formhasilperiksa").validate({
        ignore: [],
        errorClass: "error",
        rules: {
            jenis_ikan: {
                required: true
            },
            pjg: {
                required: true
            },
            lbr: {
                required: true
            },
            berat: {
                required: true,
            },
            jenis_sampel: {
                required: true
            },

            product_type: { required: true },
            product_condition: { required: true },
            product_category: { required: true },
        },
        messages: {
            jenis_ikan: {
                required: "Jenis Ikan Harus Dipilih."
            },
            pjg: {
                required: "Panjang Sampel Harus Diisi."
            },
            lbr: {
                required: "Lebar Sampel Harus Diisi."
            },
            berat: {
                required: "Berat Sampel Harus Diisi.",
            },
            jenis_sampel: {
                required: "Jenis Produk Harus Dipilih."
            },

            product_type: { required: "Data harus diisi." },
            product_condition: { required: "Data harus diisi." },
            product_category: { required: "Data harus diisi." },
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
        submitHandler: function (form) {
            $.ajax({
                url: 'ajax.php',
                dataType: 'json',
                type: 'post',
                data: $("#formhasilperiksa").serialize(),
                beforeSend: function () {
                    $('#actloadingmd').show();
                    $('#btn_save').prop('disabled', true);
                },
                success: function (json) {
                    if (json.stat) {
                        var notice = new PNotify({
                            title: 'Notification',
                            text: json.msg,
                            type: 'success',
                            delay: 1000,
                            after_close: function () {
                                location.reload();
                            }
                        });
                    } else {
                        var notice = new PNotify({
                            title: 'Notification',
                            text: json.msg,
                            type: 'warning',
                            delay: 2500
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
        rules: {
            jenis_ikan: {
                required: true
            },
            pjg: {
                required: true
            },
            lbr: {
                required: true
            },
            berat: {
                required: true,
            },
            jenis_sampel: {
                required: true
            }
        },
        messages: {
            jenis_ikan: {
                required: "Jenis Ikan Harus Dipilih."
            },
            pjg: {
                required: "Panjang Sampel Harus Diisi."
            },
            lbr: {
                required: "Lebar Sampel Harus Diisi."
            },
            berat: {
                required: "Berat Sampel Harus Diisi.",
            },
            jenis_sampel: {
                required: "Jenis Produk Harus Dipilih."
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
        submitHandler: function (form) {
            $.ajax({
                url: 'ajax.php',
                dataType: 'json',
                type: 'post',
                data: $("#fupdatehslperiksa").serialize(),
                beforeSend: function () {
                    $('#actloadingmd').show();
                    $('#btn_save').prop('disabled', true);
                },
                success: function (json) {
                    if (json.stat) {
                        var notice = new PNotify({
                            title: 'Notification',
                            text: json.msg,
                            type: 'success',
                            delay: 1000
                        });
                    } else {
                        var notice = new PNotify({
                            title: 'Notification',
                            text: json.msg,
                            type: 'warning',
                            delay: 2500
                        });
                    }
                    $('#btn_save').prop('disabled', false);
                    $('#actloadingmd').hide();
                }
            });
            return false;
        }
    });

    $("#tabelhasilperiksa").on('click', '.btn_hps_hasilper', function (event) {
        event.preventDefault();
        var idhasil = $(this).data('delid');
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
        })).get().on('pnotify.confirm', function () {
            $.ajax({
                url: 'ajax.php',
                dataType: 'json',
                type: 'post',
                data: 'a=del-hsl-periksa&iddt=' + idhasil,
                success: function (json) {
                    if (json.stat) {
                        var notice = new PNotify({
                            title: 'Notification',
                            text: json.msg,
                            type: 'success',
                            delay: 1000,
                            after_close: function () {
                                location.reload();
                            }
                        });
                    } else {
                        var notice = new PNotify({
                            title: 'Notification',
                            text: json.msg,
                            type: 'warning',
                            delay: 2500
                        });
                    }
                }
            });
        })
    });

    //---hasil pemeriksaan
});