<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('ul li.nav-hasil').addClass('active');
  $('.datepicker').bootstrapMaterialDatePicker({time:false,format:'MM/DD/YYYY'});

});
</script>
<script src=\"hasil-pemeriksaan.js\"></script>
";

$idpengajuan=U_IDP;
if(ctype_digit($idpengajuan)){
?>
<body class="hold-transition skin-blue sidebar-mini">

<div class="content-wrapper">
    <section class="content-header">
      <h1>
        Hasil Pemeriksaan
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo c_URL;?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Hasil Pemeriksaan</li>
      </ol>
    </section>
    <?php
    $sql->get_row('tb_pemeriksaan',array('ref_idp'=>$idpengajuan),array('tgl_periksa','id_periksa'));
    $found=$sql->num_rows;
    if($found>0){
      $r=$sql->result;
      $tgl_periksa=date('m/d/Y',strtotime($r['tgl_periksa']));
      $idperiksa=$r['id_periksa'];
    }else{
      $arr_insert=array(
        'tgl_periksa'=>date('Y-m-d'),
        'ref_idp'=>$idpengajuan,
        'date_insert'=>date('Y-m-d H:i:s'));
      $sql->insert('tb_pemeriksaan',$arr_insert);
      $idperiksa=$sql->insert_id;
    }
    
    $dt=$sql->run("SELECT c.nama_lengkap, p.tujuan FROM tb_permohonan p JOIN tb_userpublic c ON(c.iduser=p.ref_iduser) WHERE p.idp='$idpengajuan' LIMIT 1");
    $rdt=$dt->fetch();
    $nama_lengkap=$rdt['nama_lengkap'];
    $tujuan=$rdt['tujuan'];
    ?>

    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Hasil Pemeriksaan</h3>
        </div>
        <div class="box-body">
          <form class="form-horizontal" id="update_pemeriksaan" method="post">
            <input type="hidden" name="a" value="update-dt-periksa">
            <input type="hidden" name="idpr" value="<?php echo base64_encode($idperiksa);?>" >
            <div class="form-group">
              <label class="control-label col-md-3">Nama Pemilik</label>
              <div class="col-md-4">
                <input type="text" readonly name="nm_pemilik" class="form-control" value="<?php echo $nama_lengkap;?>">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Tujuan Pengiriman</label>
              <div class="col-md-4">
                <input type="text" readonly name="tujuan" class="form-control" value="<?php echo $tujuan;?>">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Tanggal Pemeriksaan</label>
              <div class="col-md-3">
                <input type="text" name="tgl_pemeriksaan" class="datepicker form-control" value="<?php echo $tgl_periksa;?>">
                <small class="text-alert alert-danger">Format : Bulan/Hari/Tahun (mm/dd/yyyy)</small>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3"></label>
              <div class="col-md-9">
                <button type="submit" class="btn btn-sm btn-primary btn-flat btn_simpan">Simpan Perubahan</button>
                <a href="fh-add.php?p=<?php echo base64_encode($idperiksa);?>" class="btn btn-sm btn-danger btn-flat">[+] Tambah Hasil Pemeriksaan</a>
                <span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
              </div>
            </div>
          </form>
          <hr/>
          <center><h5>Tabel Hasil Pemeriksaan</h5></center>
          <div class="table-responsive">
          <table class="table table-bordered" id="tabelhasilperiksa">
            <thead><tr>
              <th rowspan='2' style="vertical-align: middle;" class="text-center">No</th>
              <th colspan='3' class="text-center">Ikan</th>
              <th colspan='4' class="text-center">Sampel (Terkecil/Terbesar)</th>
              <th rowspan='2' style="vertical-align: middle;" class="text-center">Berat Total(Kg)</th>
              <th rowspan='2' style="vertical-align: middle;" class="text-center">Keterangan</th>
              <th rowspan='2' style="vertical-align: middle;" class="text-center">Aksi</th>
            </tr>
            <tr>
              <td>Jenis</td>
              <td>Asal Komoditas</td>
              <td>Kemasan</td>
              <td>Jenis Produk</td>
              <td>Panjang (Cm)</td>
              <td>Lebar (Cm)</td>
              <td>Berat (Kg)</td>
            </tr>
            </thead>
            <tbody>
            <?php
            $tb=$sql->query("SELECT th.*,rdi.nama_ikan,rjs.jenis_sampel as jns_produk FROM tb_hsl_periksa th 
                  LEFT JOIN ref_data_ikan rdi ON (rdi.id_ikan=th.ref_idikan) 
                  LEFT JOIN ref_jns_sampel rjs ON (rjs.id_ref=th.ref_jns_sampel)
                  WHERE th.ref_idp='$idpengajuan' AND th.ref_idperiksa='$idperiksa'
                  ");
            if($tb->rowCount()>0){
              $no=1;
              foreach ($tb->fetchAll() as $dtrow) {
                $btnaksi='
                <a href="fh-edit.php?hsl='.base64_encode($dtrow['id_per']).'" class="btn btn-xs btn-success">Edit</a>  <a href="#" data-delid="'.base64_encode($dtrow['id_per']).'" class="btn btn-xs btn-danger btn_hps_hasilper">Hapus</a>';
                echo '
                <tr>
                  <td>'.$no.'</td>
                  <td>'.$dtrow['nama_ikan'].'</td>
                  <td>'.$dtrow['asal_komoditas'].'</td>
                  <td>'.$dtrow['kuantitas'].'</td>
                  <td>'.$dtrow['jns_produk'].'</td>
                  <td>'.$dtrow['pjg'].''.(($dtrow['pjg2']!='0.00')?" / ".$dtrow['pjg2']:"").'</td>
                  <td>'.$dtrow['lbr'].''.(($dtrow['lbr2']!='0.00')?" / ".$dtrow['lbr2']:"").'</td>
                  <td>'.$dtrow['berat'].''.(($dtrow['berat2']!='0.00')?" / ".$dtrow['berat2']:"").'</td>
                  <td>'.$dtrow['tot_berat'].'</td>
                  <td>'.$dtrow['ket'].'</td>
                  <td>'.$btnaksi.'</td>
                </tr>';
                $no++;
              }
            }else{
              echo '<tr><td colspan="11" class="text-center">Data Belum Diisi.</td></tr>';
            }
            ?>
            </tbody>
          </table>
          </div>
        </div>
      </div>
    </section>
</div>

</div>
</body>
<?php
}
include(AdminFooter);
?>
