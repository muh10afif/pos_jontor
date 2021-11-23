<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Bagja Indonesia</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/vendors/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/vendors/css/vendor.bundle.base.css">
        <!-- endinject -->
        <!-- Plugin css for this page -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/vendors/select2/select2.min.css" />
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css" />
        <!-- End plugin css for this page -->
        <!-- inject:css -->
        <!-- endinject -->
        <!-- Layout styles -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/css/demo_2/style.css" />
        <!-- End layout styles -->
        <link rel="shortcut icon" href="<?php echo base_url() ?>assets/img/logo.png" />
        <!-- font awesome -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/fontawesome/css/all.css" />
        <!-- css log in -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/auth.css" />
    </head>
    <body>
      <div class="container">
        <div class="row">
          <div class="col-lg-10 col-xl-9 mx-auto">
            <div class="card card-signin flex-row my-5 shadow">
              <div class="card-img-left d-none d-md-flex">
              </div>
              <div class="card-body">
                <?php $this->view('messages') ?>
                <h3 class="card-title text-center font-weight-bold">B A G J A</h3>
                <form class="form-signin" action="<?php echo base_url('Auth/cek') ?>" method="post">
                  <div class="form-label-group">
                    <input type="text" id="username" name="username" class="form-control shadow" placeholder="Username" required autofocus autocomplete="off">
                    <label for="username">Username</label>
                  </div>
                  <div class="form-label-group mt-2">
                    <input type="password" id="password" name="password" class="form-control shadow" placeholder="Password" required autocomplete="off">
                    <label for="password">Password</label>
                  </div>
                  <hr>
                  <button type="submit" class="d-block text-center mt-2 btn btn-primary btn-block shadow" href="#">Log In</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </body>
    <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/fontawesome/js/all.js"></script>
</html>