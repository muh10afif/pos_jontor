<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title><?php echo $this->session->userdata('nama_umkm'); ?> | <?php echo $title ?></title>
    
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/vendors/jquery-bar-rating/css-stars.css" />
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/vendors/font-awesome/css/font-awesome.min.css" />
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/template/assets/css/demo_2/style.css" />
    <!-- font awesome -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/fontawesome/css/all.css" />
    <!-- <link rel="shortcut icon" href="<?php echo base_url() ?>assets/img/logo.png" /> -->
    <!-- End layout styles -->
    <link rel="shortcut icon" href="<?php echo base_url() ?>assets/img/<?php echo $this->session->userdata('logo'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/DataTables/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css">
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/select2/dist/css/select2.css"> -->
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/datepicker/css/datepicker.css"> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/datepicker_v4/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/visual_numpad/css/easy-numpad.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/select2_boostrap4/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/select2_boostrap4/css/select2-bootstrap4.css">
    <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/select2/dist/js/select2.js" defer></script> -->
    <script src="<?php echo base_url(); ?>assets/webcamjs/webcam.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
    <!-- <script src="<?php echo base_url(); ?>assets/video2image/video2image.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.js"></script> -->
    <style>
        .page-body-wrapper {
            margin: 10px;
        }
        /* .select2-container--default .select2-selection--single .select2-selection__rendered {
            position: relative;
            bottom: 15px;
        } */
        .help-block {
            color: red;
        }
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            display: table;
        }

        .horizontal-menu .bottom-navbar .page-navigation > .nav-item.active > .nav-link .menu-title, .horizontal-menu .bottom-navbar .page-navigation > .nav-item.active > .nav-link i, .horizontal-menu .bottom-navbar .page-navigation > .nav-item.active > .nav-link .menu-arrow:active {
            color: blue !important;
        }

        .horizontal-menu .bottom-navbar .page-navigation > .nav-item {
            border-right: 0px !important;
        }

        footer {
            background-color: #f8f9fb !important;
            display: table-row;
            height: 10px;
        }

        @media only screen and (min-width: 1024px) {
          .top-navbar {
            display: none;
          }
        }

        .select2-selection__rendered {
            line-height: 48px !important;
        }
        .select2-container .select2-selection--single {
            height: 48px !important;
        }


        /*#accent-keyboard {
            display: block !important;
        }*/
    </style>
  </head>