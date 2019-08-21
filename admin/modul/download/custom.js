$(document).ready(function(){
	$("#list-rekom").DataTable({
		"ajax": {
        	"url":"ajax.php",
        	"method":"POST",
        	"data": function ( d ) {
                d.filter_no_surat = $('#filter_no_surat').val();
                d.filter_bulan = $('#filter_bulan').val();
                d.filter_tahun = $('#filter_tahun').val();
                d.a="list-rek";
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

	$("#stat_costumfilter").validate({
		ignore: [],
		rules:{
			filter_tahun:{
				required:function(){
					if($("#filter_bulan").val()!='all'){
						return true;
					}else{
						return false;
					}
				}
			}
		},
		messages:{
			filter_tahun:{
				required:"Tahun harus dipilih jika filter bulan digunakan."
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
  			var dtable=$("#list-rekom").DataTable();
			dtable.draw();
  		}
	});
});