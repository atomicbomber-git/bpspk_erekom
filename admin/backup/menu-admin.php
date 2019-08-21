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
                    <li class="nav-peg">
                        <a href="<?php echo c_URL.$ModuleDir;?>pegawai/">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Data Pegawai</span>
                        </a>
                    </li>
                    <li class="nav-user">
                        <a href="<?php echo c_URL.$ModuleDir;?>user/">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Manajemen User</span>
                        </a>
                    </li>
                    <li class="nav-parent nav-dtref"><a href="#"><i class="fa fa-tag" aria-hidden="true"></i><span>Data Referensi</span></a>
                        <ul class="nav nav-children">
                            <li class="df-ikan"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/data-ikan.php">Data Ikan</a></li>
                            <li class="df-satker"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/satuan-kerja.php">Satuan Kerja</a></li>
                            <li class="df-bk"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/balai-karantina.php">Balai Karantina</a></li>
                            <li class="df-jp"><a href="<?php echo c_URL.$ModuleDir;?>data-referensi/jenis-produk.php">Jenis Produk</a></li>
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
                    <!--<li class="nav-parent nav1"><a href="#"><i class="fa fa-file-text" aria-hidden="true"></i>
                        <span>Berita</span></a>
                        <ul class="nav nav-children">
                            <li class="add_bd"><a href="#">Tambah Berita</a></li>
                            <li class="bd"><a href="#">Berita</a></li>
                        </ul>
                    </li>-->
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