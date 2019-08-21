<ul class="sidebar-menu">
   	<li class="header">MAIN NAVIGATION</li>
   <li class="nav-dashboard"><a href="<?php echo c_URL;?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
   <li class="nav-check"><a href="<?php echo c_URL.$ModuleDir;?>rekomendasi/check.php"><i class="fa fa-file-text"></i> <span>Cek Surat</span></a></li>
   <li class="treeview nav-lap">
   		<a href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i><span>Statistik</span>
		<span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
   		</a>
        <ul class="treeview-menu">
            <li class="stat-rek"><a href="<?php echo c_URL.$ModuleDir;?>lap-statistik/">S. Rekomendasi</a></li>
            <li class="stat-pr"><a href="<?php echo c_URL.$ModuleDir;?>lap-statistik/stat_produk/">S. Produk</a></li>
        </ul>
    </li>
   <li><a href="?keluar"><i class="fa fa-sign-out"></i> <span>Keluar/Logout</span></a></li>
</ul>
