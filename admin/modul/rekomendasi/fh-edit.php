<?php
include("config.php");
$idpengajuan=base64_decode($_GET['data']);
$idhslperiksa=base64_decode($_GET['hsl']);
if(!ctype_digit($idpengajuan)){
	exit();
}
if(!ctype_digit($idhslperiksa)){
	exit();
}
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
				var dtket=$('.ket').val();
				$('.ket').html(dtket+' \\n'+html);
			}
		});
	}
	</script>
	<script src=\"custom-2.js\"></script>
";

$sql->get_row('tb_hsl_periksa',array('id_per'=>$idhslperiksa,'ref_idp'=>$idpengajuan));
if($sql->num_rows<1){
	exit();
}
$dtrow=$sql->result;
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Edit Hasil Pemeriksaan</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><a href="./form-hasil.php?data=<?php echo $_GET['data'];?>"><span>Pemeriksaan Sampel</span></a></li>
				<li><span>Edit Hasil Pemeriksaan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
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
					<form class="form-horizontal" action="" method="POST" name="fupdatehslperiksa" id="fupdatehslperiksa">
                        <input type="hidden" name="a" value="update-hsl-periksa">
						<input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>" >
						<input type="hidden" name="idhsl" value="<?php echo base64_encode($idhslperiksa);?>" >
						<div class="form-group">
							<label class="col-sm-3 control-label">Jenis Ikan</label>
							<div class="col-sm-9">
								<select class="form-control jns_ikan" name="jenis_ikan">
									<option value="">-Pilih-</option>
									<?php
									$sql->get_all('ref_data_ikan');
									if($sql->num_rows>0){
										foreach($sql->result as $r){
											if($dtrow['ref_idikan']==$r['id_ikan']){
												echo '<option selected value="'.$r['id_ikan'].'">'.$r['nama_ikan'].' ('.$r['nama_latin'].')</option>';
											}else{
												echo '<option value="'.$r['id_ikan'].'">'.$r['nama_ikan'].' ('.$r['nama_latin'].')</option>';
											}
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
											if($rak['ak']==$dtrow['asal_komoditas']){
												echo '<option selected value="'.$rak['ak'].'">'.$rak['ak'].'</option>';
											}else{
												echo '<option value="'.$rak['ak'].'">'.$rak['ak'].'</option>';
											}
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
								<input 
									value="<?= $dtrow['kuantitas'] ?>"
									type="number" step="any" name="kemasan" class="form-control">
							</div>
						</div>

						<?php 
							$satuan_barangs = App\Models\SatuanBarang::all();
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
										<option 
											<?= $dtrow["id_satuan_barang"] == $satuan_barang->id ? 'selected' : '' ?>
											value="<?= $satuan_barang->id ?>">
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
										product_classification: JSON.parse('<?= json_encode(App\Constants\ProductClassification::get()) ?>'),
										product_type: '<?= $dtrow['produk'] ?>',
										product_condition: '<?= $dtrow['kondisi_produk'] ?>',
										product_category: '<?= $dtrow['jenis_produk'] ?>',
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
							<label class="col-sm-3 control-label">Panjang (Cm)</label>
							<div class="col-md-3">
								<input type="text" name="pjg" class="form-control" value="<?php echo $dtrow['pjg'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Lebar (Cm)</label>
							<div class="col-md-3">
								<input type="text" name="lbr" class="form-control" value="<?php echo $dtrow['lbr'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Berat Sampel(Kg)</label>
							<div class="col-md-3">
								<input type="text" name="berat" class="form-control" value="<?php echo $dtrow['berat'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4">-- Sampel Terbesar --</label>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Panjang (Cm)</label>
							<div class="col-md-3">
								<input type="text" name="pjg2" class="form-control" value="<?php echo $dtrow['pjg2'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Lebar (Cm)</label>
							<div class="col-md-3">
								<input type="text" name="lbr2" class="form-control" value="<?php echo $dtrow['lbr2'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Berat Sampel(Kg)</label>
							<div class="col-md-3">
								<input type="text" name="berat2" class="form-control" value="<?php echo $dtrow['berat2'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Berat Total (Kg)</label>
							<div class="col-md-4">
								<input type="text" name="berat_tot" class="form-control" value="<?php echo $dtrow['tot_berat'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Keterangan</label>
							<div class="col-md-5">
								<textarea class="form-control ket" rows="5" name="ket"><?php echo $dtrow['ket'];?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-md-5">
								<button type="submit" class="btn btn-sm btn-primary" id="btn_save">Tambah Hasil</button>
								<span id="actloadingmd" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
							</div>
						</div>
					</form>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12">
							<a href="./form-hasil.php?data=<?php echo $_GET['data'];?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</section>
<?php
include(AdminFooter);
?>