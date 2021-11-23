<body>
  <div class="container-scroller">
    <!-- partial:partials/_horizontal-navbar.html -->
    <div class="horizontal-menu">
      <nav class="navbar top-navbar col-lg-12 col-12 p-0">
        <div class="container">
<!--           <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="<?php echo base_url() ?>"><img src="<?= base_url('assets/img/shyo.png') ?>" style="width: 400px !important;"></a>
          </div> -->
          <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
              <span class="mdi mdi-menu"></span>
            </button>
          </div>
        </div>
      </nav>
      <!-- #ffbf00 -->
      <nav class="bottom-navbar" style="position: static !important; background-color: #ffbf00 !important; background: #ffbf00 !important;">
        <div class="container-fluid">
          <ul class="nav page-navigation">
            <li class="nav-item mb-2">
              <p style="margin-right: 10px; margin-top: 15px; font-size:20px;"><img src="<?= base_url('assets/img/'.$this->session->userdata('logo')) ?>" width="65px" height="50px"></p>
            </li>
            <li class="nav-item"></li>
            <li class="nav-item"></li>
            <li class="nav-item mt-2 <?php echo $this->uri->segment(1) == 'Dashboard' ? 'active' : null ?>">
              
                <a href="<?php echo base_url() ?>" class="nav-link text-black font-weight-semibold" style='color: #000;'>
                    <i class="mdi mdi-home mdi-24px"></i>
                    <span class="menu-title font-weight-bold" style="font-size: 20px;"><?= nbs(2) ?>Dashboard</span>
                </a>
            </li>
            <?php if($this->session->userdata('id_role') == 2) { ?>
            <li class="nav-item mt-2 <?php echo $this->uri->segment(1) == 'Kategori' || $this->uri->segment(1) == 'Produk' || $this->uri->segment(1) == 'Karyawan' ? 'active' : '' ?>">
                <a href="javascript:void(0)" class="nav-link" style='color: #000;'>
                    <i class="mdi mdi-package menu-icon mdi-24px" style="color: black;"></i>
                    <span class="menu-title font-weight-bold" style="font-size: 20px;">Master Data</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="submenu">
                  <ul class="submenu-item">
                    <li class="nav-item">
                      <a class="nav-link" href="<?php echo base_url('Kategori') ?>"><span class="font-weight-bold">Kategori</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="<?php echo base_url('Produk') ?>"><span class="font-weight-bold">Produk</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="<?php echo base_url('Karyawan') ?>"><span class="font-weight-bold">Karyawan</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="<?php echo base_url('Discount') ?>"><span class="font-weight-bold">Discount</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="<?php echo base_url('Bahan') ?>"><span class="font-weight-bold">Bahan</span></a>
                    </li>
                  </ul>
                </div>
              </li>
            <?php } ?>
            <li class="nav-item mt-2 <?php echo $this->uri->segment(1) == 'Transaksi' ? 'active' : null ?>">
              <a href="<?php echo base_url('Transaksi') ?>" class="nav-link text-black font-weight-semibold" style='color: #000;'>
                <i class="mdi mdi-credit-card mdi-24px"></i>
                <span class="menu-title font-weight-bold" style="font-size: 20px;"><?= nbs(2) ?>Transaksi</span>
              </a>
            </li>
            <li class="nav-item mt-2 <?php echo $this->uri->segment(1) == 'Absensi' ? 'active' : null ?>" hidden>
              
                <a href="<?php echo base_url('Absensi') ?>" class="nav-link text-black font-weight-semibold" style='color: #000;'>
                  <i class="mdi mdi-human-male mdi-24px"></i>
                  <span class="menu-title font-weight-bold" style="font-size: 20px;"><?= nbs(2) ?>Absensi</span>
                </a>
              
            </li>
            <?php if($this->session->userdata('id_role') == 2) { ?>
            <li class="nav-item mt-2 <?php echo $this->uri->segment(1) == 'Report' ? 'active' : null ?>">
                <a href="javascript:void(0)" class="nav-link" style='color: #000;'>
                    <i class="mdi mdi-file menu-icon mdi-24px" style="color: black;"></i>
                    <span class="menu-title font-weight-bold" style="font-size: 20px;">Report</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="submenu">
                  <ul class="submenu-item">
                    <li class="nav-item">
                      <a class="nav-link" href="<?php echo base_url('Report') ?>"><span class="font-weight-bold">Transaksi</span></a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="<?php echo base_url('Bahan/report') ?>"><span class="font-weight-bold">Stok</span></a>
                    </li>
                  </ul>
                </div>
              </li>
              <?php } else { ?>
                <li class="nav-item mt-2 <?php echo $this->uri->segment(1) == 'Report' ? 'active' : null ?>">
                
                  <a href="<?php echo base_url('Report') ?>" class="nav-link text-black font-weight-semibold" style='color: #000;'>
                    <i class="mdi mdi-file mdi-24px"></i>
                    <span class="menu-title font-weight-bold" style="font-size: 20px;"><?= nbs(2) ?>Report</span>
                  </a>
                
              </li>
              <?php } ?>
            <li class="nav-item"></li>
            <li class="nav-item"></li>
            <li class="nav-item" style="margin-top: 5px;">

                <?php 
                
                  $c = date("Y-m-d", now('Asia/Jakarta'));
      
                  $e = date("$c 01:01:00");
                  $a = date("Y-m-d H:i:s", now('Asia/Jakarta'));
                  $b = date("$c 20:00:00");
                  $f = date("$c 08:00:00");
              
                  if ($a > $b) {
                    $d = "Shift Malam";
                  } else {
                    if ($a > $f) {
                      $d = "Shift Pagi";
                    } else {
                      $d = "Shift Malam";
                    }
                  }

                  $ses = $this->session->userdata('id_role');

                  if ($ses > 1) {
                    $kt = "Super Admin";
                  } else {
                    $kt = $d;
                  }
                
                ?>

                <h5 class="mb-0 text-right mt-2">Hi, <?= $this->session->userdata('username') ?> | <?= $kt ?></h5> 
                <a href="<?php echo base_url('Auth/Out') ?>" class="nav-link text-black font-weight-semibold ml-3 mr-0 d-flex justify-content-end" style='color: #000;'>
                <i class="fa fa-sign-out-alt mr-2"></i>
                  <span class="menu-title font-weight-bold" style="font-size: 20px;">Log Out</span>
                </a>
              
            </li>
          </ul>
        </div>
      </nav>
    </div>
    <!-- partial -->