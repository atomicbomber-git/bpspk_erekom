<?php 
  @include (c_THEMES."meta.php"); 
?>
<div class="wrapper">

  <header class="main-header">
    <a href="#" class="logo">
      <span class="logo-mini">E-Rekomendasi</span>
      <span class="logo-lg">E-Rekomendasi LPSPL Serang</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo IMAGES;?>user.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo U_NAME;?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="<?php echo IMAGES;?>user.jpg" class="img-circle" alt="User Image">
                <p>
                  <?php echo U_NAME;?>
                  <small>NIP. <?php echo U_NIP;?></small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-right">
                  <a href="?keluar" class="btn btn-default btn-flat">Keluar/Logout</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <section class="sidebar">
      <?php 
        @include (c_THEMES."menu.php"); 
      ?>
    </section>
  </aside>