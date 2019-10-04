<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since 5.0
* @category     Themes Menu
*/

if (USER) {
?>
<div class="inner-wrapper">
    <aside id="sidebar-left" class="sidebar-left">
                
    <div class="sidebar-header">
        <div class="sidebar-title">Navigation</div>
        <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle"><i class="fa fa-bars" aria-label="Toggle sidebar"></i></div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">
                    <li class="landing"><a href="<?php echo c_DOMAIN;?>"><i class="fa fa-home" aria-hidden="true"></i><span>Dashboard</span></a></li>
                    <?php if(U_LEVEL==100){ ?>
                    <li class="nav1">
                        <a href="<?php echo c_URL.$ModuleDir;?>rekomendasi/list-permohonan-masuk.php">
                            <i class="fa fa-file-text" aria-hidden="true"></i>
                            <span>Pemohonan Masuk</span>
                        </a>
                    </li>
                    <li class="nav-st">
                        <a href="<?php echo c_URL.$ModuleDir;?>rekomendasi/stat-permohonan.php">
                            <i class="fa fa-file-text" aria-hidden="true"></i>
                            <span>Status Permohonan</span>
                        </a>
                    </li>
                    <li class="navph">
                        <a href="<?php echo c_URL.$ModuleDir;?>pemohon/list.php">
                            <i class="fa fa-file-text" aria-hidden="true"></i>
                            <span>Data Pemohon</span>
                        </a>
                    </li>
                    <?php }

                    if(U_LEVEL==95) {                    ?>
                    <li class="nav2">
                        <a href="<?php echo c_URL.$ModuleDir;?>rekomendasi/pemeriksaan-sample.php">
                            <i class="fa fa-file-text" aria-hidden="true"></i>
                            <span>Pemeriksaan Sample</span>
                        </a>
                    </li>
                    <?php } 

                    if(U_LEVEL==90 OR U_LEVEL==91){
                    ?>
                    <li class="nav3">
                        <a href="<?php echo c_URL.$ModuleDir;?>rekomendasi/list-persetujuan.php">
                            <i class="fa fa-file-text" aria-hidden="true"></i>
                            <span>Pengesahan</span>
                        </a>
                    </li>
                    <?php } 

                    if(U_LEVEL==100){ ?>
                    <li class="nav-parent nav-kuis"><a href="#"><i class="fa fa-tag" aria-hidden="true"></i><span>Kuisioner</span></a>
                        <ul class="nav nav-children">
                            <!-- <li class="kuis-p"><a href="<?php echo c_URL.$ModuleDir;?>kuisioner/list-pertanyaan.php">Data Pertanyaan</a></li> -->
                            <li class="kuis-hasil"><a href="<?php echo c_URL.$ModuleDir;?>kuisioner/hasil.php">Hasil Kuisioner</a></li>
                            <li class="kuis-rekap"><a href="<?php echo c_URL.$ModuleDir;?>kuisioner/rekap.php">Rekap Kuisioner</a></li>
                        </ul>
                    </li>
                    <li class="nav-parent nav-download"><a href="#"><i class="fa fa-download" aria-hidden="true"></i><span>Download</span></a>
                        <ul class="nav nav-children">
                            <li class="dw-dok"><a href="<?php echo c_URL.$ModuleDir;?>download/dok-pemeriksaan.php">Dokumentasi Pemeriksaan</a></li>
                        </ul>
                    </li>
                    <li class="nav-sop">
                        <a href="<?php echo c_URL.$ModuleDir;?>sop/index.php">
                            <i class="fa fa-file-text" aria-hidden="true"></i>
                            <span>Prosedur Pelayanan LPSPL</span>
                        </a>
                    </li>
                    <li class="nav-peg">
                        <a href="<?php echo c_URL.$ModuleDir;?>pegawai/">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Data Pegawai</span>
                        </a>
                    </li>
                    <li class="nav-parent nav-user"><a href="#"><i class="fa fa-users" aria-hidden="true"></i></i><span>Manajemen User</span></a>
                        <ul class="nav nav-children">
                            <li class="user-adm"><a href="<?php echo c_URL.$ModuleDir;?>user/">Pegawai & Admin</a></li>
                            <li class="user-guest"><a href="<?php echo c_URL.$ModuleDir;?>user/guest/">Tamu</a></li>
                        </ul>
                    </li>
                    <li class="nav-parent nav-dtref"><a href="#"><i class="fa fa-tag" aria-hidden="true"></i><span>Data Referensi</span></a>
                        <ul class="nav nav-children">
                            <li class="df-ikan"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/data-ikan.php">Data Ikan</a></li>
                            <li class="df-satker"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/satuan-kerja.php">Satuan Kerja</a></li>
                            <li class="df-bk"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/balai-karantina.php">Balai Karantina</a></li>
                            <li class="df-prl"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/upt-prl.php">UPT PRL</a></li>
                            <li class="df-psdkp"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/psdkp.php">PSDKP</a></li>
                            <li class="df-jp"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/jenis-produk.php">Jenis Produk</a></li>
                            <li class="df-bw"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/web-index.php">Bahan Rekap</a></li>
                            <li class="nav-parent nav-redaksi"><a href="#">Redaksi Surat</a>
                                <ul class="nav nav-children nav-red">
                                    <li class="red-st"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/surat/st.php">Surat Tugas</a></li>
                                    <!-- <li class="red-bap"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/surat/bap.php">BAP</a></li> -->
                                    <!-- <li class="red-rek"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/surat/rekomendasi.php">Rekomendasi</a></li> -->
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-parent nav-lap"><a href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>Statistik</span></a>
                        <ul class="nav nav-children">
                            <li class="stat-rek"><a href="<?php echo c_URL.$ModuleDir;?>lap-statistik/">S. Rekomendasi</a></li>
                            <li class="stat-pr"><a href="<?php echo c_URL.$ModuleDir;?>lap-statistik/stat_produk/">S. Produk</a></li>
                            <!-- <li class="stat-pr_pemohon"><a href="<?php echo c_URL.$ModuleDir;?>lap-statistik/stat_produk_by_pemohon/">S. Produk Berdasarkan Pemohon</a></li> -->
                        </ul>
                    </li>
                    <?php } ?>
                    <li class="nav-profile">
                        <a href="<?php echo c_URL.$ModuleDir;?>user/my-profile.php">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>Akun Saya</span>
                        </a>
                    </li>
                    <li class="navlogout">
                        <a href="?keluar">
                            <i class="fa fa-power-off" aria-hidden="true"></i>
                            <span>Keluar/Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <hr class="separator" />
            <div class="sidebar-widget widget-stats">
                <div class="sidebar-copyright">
                    <p>&copy;2016 <?php echo c_APP;?></p>
                </div>
            </div>
        </div>
    </div>
</aside>
<?php
}
?>