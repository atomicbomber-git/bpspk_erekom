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
                    <li class="nav1">
                        <a href="<?php echo c_URL;?>?pengajuan">
                            <i class="fa fa-file-text" aria-hidden="true"></i>
                            <span>Pengajuan Rekomendasi</span>
                        </a>
                    </li>
                    <li class="nav4">
                        <a href="<?php echo c_URL;?>?riwayat">
                            <i class="fa fa-file-text" aria-hidden="true"></i>
                            <span>Riwayat Pengajuan Rekomendasi</span>
                        </a>
                    </li>
                    <li class="nav2">
                        <a href="<?php echo c_URL;?>?biodata">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>Biodata</span>
                        </a>
                    </li>
                    <li class="navakun">
                        <a href="?akun">
                            <i class="fa fa-gear" aria-hidden="true"></i>
                            <span>Pengaturan Akun</span>
                        </a>
                    </li>
                    <li class="navlogout">
                        <a href="<?php echo c_URL;?>?keluar">
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
                    <p>&copy;2019 <?php echo c_APP;?></p>
                </div>
            </div>
        </div>
    </div>
</aside>
<?php
}
?>