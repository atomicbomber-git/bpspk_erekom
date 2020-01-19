<?php

use App\Constants\ProductClassification;
use App\Models\SatuanBarang;

require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
    $('nav li.nav2').addClass('nav-active');
    
    var ik;
    var pr;

    $('.jns_ikan').change(function(){
        ik=$('.jns_ikan').val();
        if(pr!='' &&ik!='' && pr!=undefined){
            get_ket(ik,pr);
        }
    });

    $('.jns_produk').change(function(){
        pr=$('.jns_produk').val();
        if(ik!='' && pr!='' && ik!=undefined){
            get_ket(ik,pr);
        }
    });
});

function get_ket(ik,pr){
    $.ajax({
        url:'ajax.php',
        dataType:'html',
        type:'post',
        data:'a=getciri&ik='+ik+'&pr='+pr,
        beforeSend:function(){
        },
        success: function(html){
            $('.ket').html(html);
        }
    });
}
</script>
<script src=\"custom-2.js\"></script>
";

$idpengajuan=base64_decode($_GET['data']);
if(ctype_digit($idpengajuan)){
?>
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Input Hasil Pemeriksaan</h2>
    
        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="<?php echo c_MODULE;?>">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><a href="./pemeriksaan-sample.php"><span>Pemeriksaan Sampel</span></a></li>
                <li><span>Input Hasil Pemeriksaan</span></li>
            </ol>
            <a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>
    <?php
    
    $sql->get_row('tb_pemeriksaan',array('ref_idp'=>$idpengajuan),array('tgl_periksa','id_periksa'));
    $found=$sql->num_rows;
    if($found>0){
        $r=$sql->result;
        $tgl_periksa=date('m/d/Y',strtotime($r['tgl_periksa']));
        $idperiksa=$r['id_periksa'];
    }else{
        $arr_insert=array(
            'tgl_periksa'=>date('m/d/Y'),
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
    <div class="row">
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <div class="panel-actions">
                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                    </div>
                    <h2 class="panel-title">Hasil Pemeriksaan</h2>
                </header>
                <div class="panel-body">
                    <form id="update_pemeriksaan" method="post">
                        <input type="hidden" name="a" value="update-dt-periksa">
                        <input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>" >
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
                                <input type="text" name="tgl_pemeriksaan" data-plugin-datepicker data-date-orientation="top" class="form-control" value="<?php echo $tgl_periksa;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3"></label>
                            <div class="col-md-9">
                                <button type="submit" class="btn btn-sm btn-primary btn_simpan">Simpan Perubahan</button>
                                <span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
                            </div>
                        </div>
                    </form>
                    <hr/>	
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="toggle" data-plugin-toggle="" id="formaddhasil">
                                <section class="toggle">
                                    <label>Tambah Hasil Pemeriksaan</label>
                                    <div class="toggle-content panel-body">
                                        <form class="form-horizontal" action="" method="POST" name="formhasilperiksa" id="formhasilperiksa">
                                            <input type="hidden" name="a" value="add-hsl-periksa">
                                            <input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>" >
                                            <input type="hidden" name="idpr" value="<?php echo base64_encode($idperiksa);?>" >
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Jenis Ikan</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control jns_ikan" name="jenis_ikan">
                                                        <option value="">-Pilih-</option>
                                                        <?php
                                                        $sql->get_all('ref_data_ikan');
                                                        if($sql->num_rows>0){
                                                            foreach($sql->result as $r){
                                                            echo '<option value="'.$r['id_ikan'].'">'.$r['nama_ikan'].' ('.$r['nama_latin'].')</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Asal Komoditas</label>
                                                <div class="col-md-4 ak_div">
                                                    <select name="asal_komoditas_opt" class="form-control asal_komoditas">
                                                        <option value="">-Pilih-</option>
                                                        <?php
                                                        $ak=$sql->run("SELECT DISTINCT(asal_komoditas) ak FROM tb_hsl_periksa where ref_idp='$idpengajuan'");
                                                        if($ak->rowCount()>0){
                                                            foreach($ak->fetchAll() as $rak){
                                                                echo '<option value="'.$rak['ak'].'">'.$rak['ak'].'</option>';
                                                            }
                                                        }
                                                        ?>
                                                        <option value="lainnya">Lainnya</option>
                                                    </select>
                                                    <input type="text" style="display:none" name="asal_komoditas" class="form-control custom_ak">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label"> Jumlah Kemasan </label>
                                                <div class="col-md-2">
                                                    <input type="number" step="any" name="kemasan" class="form-control">
                                                </div>
                                            </div>

                                            <?php 
                                                $satuan_barangs = SatuanBarang::all();
                                            ?>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label"> Satuan Kemasan </label>
                                                <div class="col-md-2">
                                                    <select 
                                                        class="form-control"
                                                        name="id_satuan_barang"
                                                        id="id_satuan_barang"
                                                        >
                                                        <?php foreach($satuan_barangs as $satuan_barang): ?>
                                                        <option value="<?= $satuan_barang->id ?>">
                                                            <?= $satuan_barang->nama ?>
                                                        </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <script>
                                                window.onload = function() {
                                                    

                                                    new Vue({
                                                        el: "#app",

                                                        props: {
                                                        },

                                                        data: {

                                                            product_classification: JSON.parse('<?= json_encode(ProductClassification::get()) ?>'),
                                                        
                                                            product_type: null,
                                                            product_condition: null,
                                                            product_category: null,
                                                        },

                                                        watch: {
                                                            product_type: function() {
                                                                this.product_condition = null
                                                                this.product_category = null
                                                            },
                                                            
                                                            product_condition: function() {
                                                                this.product_category = null
                                                            },
                                                        },

                                                        computed: {
                                                            product_condition_options() {
                                                                if (!this.product_type) {
                                                                    return []
                                                                }

                                                                return this.product_classification[this.product_type].items
                                                            },

                                                            product_category_options() {
                                                                if (!this.product_condition) {
                                                                    return []
                                                                }

                                                                return this.product_classification[this.product_type].items
                                                                    [this.product_condition].items
                                                            }

                                                        }
                                                    })


                                                }
                                            </script>

                                            <div id="app">
                                                <div class="form-group">
                                                    <label 
                                                        class="control-label col-sm-3"
                                                        for="product_type">
                                                        Produk:
                                                    </label>

                                                    <div class="col-md-6">
                                                        <select 
                                                            class="form-control"
                                                            name="product_type"
                                                            id="product_type"
                                                            v-model="product_type"
                                                            >

                                                            <option 
                                                                v-for="(product_type_data, product_type_name) in product_classification"
                                                                >
                                                                {{ product_type_name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label 
                                                        class="control-label col-sm-3"
                                                        for="product_condition">
                                                        Kondisi:
                                                    </label>

                                                    <div class="col-md-6">
                                                        <select 
                                                            class="form-control"
                                                            name="product_condition"
                                                            id="product_condition"
                                                            v-model="product_condition"
                                                            >

                                                            <option 
                                                                v-for="(product_condition_data, product_condition_name) in product_condition_options"
                                                                >
                                                                {{ product_condition_name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label 
                                                        class="control-label col-sm-3"
                                                        for="product_category">
                                                        Jenis Produk:
                                                    </label>

                                                    <div class="col-md-6">
                                                        <select 
                                                            class="form-control"
                                                            name="product_category"
                                                            id="product_category"
                                                            v-model="product_category"
                                                            >

                                                            <option 
                                                                v-for="(product_category_data, product_category_name) in product_category_options"
                                                                >
                                                                {{ product_category_name }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="control-label col-md-4">-- Sampel Terkecil --</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Panjang Sampel (Cm)</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="pjg" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Lebar Sampel (Cm)</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="lbr" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Berat Sampel(Kg)</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="berat" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-4">-- Sampel Terbesar --</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Panjang Sampel (Cm)</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="pjg2" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Lebar Sampel (Cm)</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="lbr2" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Berat Sampel(Kg)</label>
                                                <div class="col-md-3">
                                                    <input type="text" name="berat2" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Berat Total (Kg)</label>
                                                <div class="col-md-4">
                                                    <input type="text" name="berat_tot" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Keterangan</label>
                                                <div class="col-md-5">
                                                    <textarea class="form-control ket" rows="5" name="ket"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label"></label>
                                                <div class="col-md-5">
                                                    <button type="submit" class="btn btn-sm btn-primary" id="btn_save">Tambah Hasil</button>
                                                    <span id="actloadingmd" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label"></label>
                                                <div class="col-md-6">
                                                <p>catatan : <span class="text-alert alert-danger">Angka Desimal Menggunakan . <strong>(titik) cth: 90.2Kg</strong></span></p>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
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
                            <td> Jumlah </td>
                            <td>Jenis Produk</td>
                            <td>Panjang (Cm)</td>
                            <td>Lebar (Cm)</td>
                            <td>Berat (Kg)</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $tb=$sql->query("SELECT 
                                            th.*,
                                            rdi.nama_ikan,
                                            rjs.jenis_sampel AS jns_produk,
                                            satuan_barang.nama AS nama_satuan_barang
                                                FROM tb_hsl_periksa th 
                                                    LEFT JOIN ref_data_ikan rdi ON (rdi.id_ikan=th.ref_idikan) 
                                                    LEFT JOIN ref_jns_sampel rjs ON (rjs.id_ref=th.ref_jns_sampel)
                                                    LEFT JOIN satuan_barang ON (satuan_barang.id = th.id_satuan_barang)
                                                WHERE th.ref_idp='$idpengajuan' AND th.ref_idperiksa='$idperiksa'
                                        ");
                        if($tb->rowCount()>0){
                            $no=1;
                            foreach ($tb->fetchAll() as $dtrow) {
                                $btnaksi='
                                <a href="fh-edit.php?data='.base64_encode($idpengajuan).'&hsl='.base64_encode($dtrow['id_per']).'" class="btn btn-xs btn-success">Edit</a>  <a href="#" data-delid="'.base64_encode($dtrow['id_per']).'" class="btn btn-xs btn-danger btn_hps_hasilper">Hapus</a>';
                                echo '
                                <tr>
                                    <td>'.$no.'</td>
                                    <td>'.$dtrow['nama_ikan'].'</td>
                                    <td>'.$dtrow['asal_komoditas'].'</td>
                                    <td>'.$dtrow['kuantitas'] . ' ' . $dtrow['nama_satuan_barang'] . '</td>
                                    <td>' . $dtrow['produk'] . ' ' . $dtrow['kondisi_produk'] . ' ' . $dtrow['jenis_produk'] . '</td>
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
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="./pemeriksaan-sample.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div id="DelHasilModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Hapus Data?</h2>
            </header>
            <div class="panel-body">
                <div class="modal-wrapper">
                    <div class="modal-icon">
                        <i class="fa fa-question-circle"></i>
                    </div>
                    <div class="modal-text">
                        <p>Apakah anda yakin akan menghapus Data ini?</p>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button id="btndelhasilpem" class="btn btn-primary modal-confirm">Confirm</button>
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                    </div>
                </div>
            </footer>
        </section>
    </div>
</section>
<?php
}
include(AdminFooter);
?>
