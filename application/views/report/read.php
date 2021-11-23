<style>
  .input {
      height: 10px !important;
      border-radius: 10px;
      border-color: #f2a654;
  }
  .nav-tabs .nav-link:not(.active) {
      border-color: #f2a654 !important;
      color: grey;
  }
  .nav-tabs .nav-link.active {
      border-color: #f2a654 !important;
      background-color: #faa307;
      font-weight: bold;
      color: white;
  }

  .date td, .datepicker th {
      width: 2.5rem;
      height: 2.5rem;
      font-size: 0.85rem;
  }

  .date {
      margin-bottom: 3rem;
  }
</style>
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper pb-0">
        
      <div class="row">
          <div class="col-sm">
              <div class="card shadow">
                  <div class="card-header" style="background-color: #ffbf00;">
                      <h3 id="judul" class="font-weight-blod"><b><i class="fa fa-file-alt mr-3"></i>Report Transaksi</b></h3>
                  </div>
                  <form id="form-filter" class="form-horizontal" method="post" action="<?php echo base_url('Report/download_file') ?>">
                      <input type="hidden" id="aksi" name="jns">
                      <?php
                        if ($this->session->userdata('id_role') == 2) {
                          $hd = '';
                        } else {
                          $hd = 'hidden';
                        }
                      ?>
                      <div class="card-body">
                          <div class="d-flex justify-content-center">
                              <div class="col-md-6">
                                  <div class="input-daterange input-group" id="date-range-2">
                                      <input type="text" class="form-control date" name="tgl_awal" id="start" placeholder="Awal Periode" readonly/>
                                      <div class="input-group-append">
                                          <span class="input-group-text bg-primary b-0 text-white">s / d</span>
                                      </div>
                                      <input type="text" class="form-control date" name="tgl_akhir" id="end" placeholder="Akhir Periode" readonly/>
                                  </div>
                              </div>
                              <div class="col-md-3" <?= $hd ?>>
                                <select class=" select2 form-control" name="id_karyawan" id="id_karyawan">
                                  <option value="">--PILIH KASIR--</option>
                                  <?php foreach ($kasir as $k): 
                                    $id_user  = $this->session->userdata('id_user');
                                    $username = $this->session->userdata('username');
                                    
                                    if ($k['created_by'] == $id_user) {
                                      $nama = ucwords($username);
                                    } else {
                                      $nama = $k['nama_karyawan'];
                                    }
                                    
                                    ?>
                                    <option value="<?= $k['created_by'] ?>"><?= $nama ?></option>
                                  <?php endforeach; ?>
                                </select> 
                              </div>
                          </div>
                      </div>
                      <div class="card-footer ">
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="d-flex justify-content-start">
                                      <button type="submit" class="btn btn-sm btn-primary mr-2" name="export" data="excel" data-toggle='tooltip' data-placement='top' title='Downnload Excel' hidden><i class="fa fa-file-excel"></i></button>
                                      <button type="submit" class="btn btn-sm btn-warning" name="export" data="pdf" data-toggle='tooltip' data-placement='top' title='Downnload Pdf'><i class="fa fa-file-pdf"></i></button>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <div class="d-flex justify-content-end">
                                      <button type="button" class="btn btn-primary mr-2" id="btn-filter">Tampilkan</button>
                                      <button type="button" class="btn btn-warning" id="btn-reset">Reset</button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
      
      <div class="row mt-3">
          <div class="col-sm">
              <div class="card shadow">
                  <div class="card-body table-responsive">
                    <div class="row mb-2">
                      <div class="col-md-6">
                        <div class="alert alert-info" role="alert">
                          <div class="row">
                            <div class="col-md-6"><h4 class="mb-0">Total Pendapatan :</h4></div>
                            <div class="col-md-6 d-flex justify-content-end"><h4 class="mb-0 t_pendapatan">Rp. <?= number_format($t_pendapatan, 0,'.','.') ?></h4></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                          <div class="alert alert-success" role="alert">
                            <div class="row">
                              <div class="col-md-6"><h4 class="mb-0">Total Transaksi :</h4></div>
                              <div class="col-md-6 d-flex justify-content-end"><h4 class="mb-0 t_transaksi"><?= $t_transaksi ?></h4></div>
                            </div>
                          
                        </div>
                      </div>
                      
                    </div>
                      
                  <table id="tabel_report" class="table w-100 display pb-30 table-bordered table-striped" width="100%">
                      <thead class="text-center">
                          <tr class="table-secondary">
                              <th class="font-weight-bold" width="5%">No.</th>
                              <th class="font-weight-bold">Tanggal Transaksi</th>
                              <th class="font-weight-bold">Kode Transaksi</th>
                              <th class="font-weight-bold">Total Harga</th>
                              <th class="font-weight-bold" width="7%">Aksi</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
                    
                  </div>
              </div>
          </div>
      </div>
    </div>

    <!-- 20-09-2020 -->
    <div id="modal_detail_transaksi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content f_detail_transaksi">
                
            </div>
        </div>
    </div>

    <script>
      $(document).ready(function () {

        // 20-09-2020
        var tabel_report = $('#tabel_report').DataTable({
            "processing"        : true,
            "serverSide"        : true,
            "order"             : [],
            "ajax"              : {
                "url"   : "Report/tampil_report",
                "type"  : "POST",
                "data"  : function (data) {
                    data.tgl_awal     = $('#start').val();
                    data.tgl_akhir    = $('#end').val();
                    data.id_karyawan  = $('#id_karyawan').val();
                }
            },
            "columnDefs"        : [{
                "targets"   : [0,4],
                "orderable" : false
            }, {
                'targets'   : [0,1,2,3,4],
                'className' : 'text-center',
            }]

        })

        // 20-09-2020
        $('#btn-filter').click(function(){

          tabel_report.ajax.reload();

          var tgl_awal     = $('#start').val();
          var tgl_akhir    = $('#end').val();
          var id_karyawan  = $('#id_karyawan').val();

          $.ajax({
            url     : "Report/tampil_total",
            type    : "POST",
            data    : {tgl_awal:tgl_awal, tgl_akhir:tgl_akhir, id_karyawan:id_karyawan},
            dataType: "JSON",
            success : function (data) {
              $('.t_pendapatan').text('Rp. '+separator(data.t_pendapatan));
              $('.t_transaksi').text(data.t_transaksi);
            }
          })

          return false;

        });

        function separator(kalimat) {
            return kalimat.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // 20-09-2020
        $('#btn-reset').click(function(){

          $('#start').val('');
          $('#end').val('');
          $('#id_karyawan').val('').trigger('change');
          tabel_report.ajax.reload();

          var tgl_awal     = $('#start').val();
          var tgl_akhir    = $('#end').val();
          var id_karyawan  = $('#id_karyawan').val();

          $.ajax({
            url     : "Report/tampil_total",
            type    : "POST",
            data    : {tgl_awal:tgl_awal, tgl_akhir:tgl_akhir, id_karyawan:id_karyawan},
            dataType: "JSON",
            success : function (data) {
              $('.t_pendapatan').text('Rp. '+separator(data.t_pendapatan));
              $('.t_transaksi').text(data.t_transaksi);
            }
          })

          return false;

        });

        // 20-09-2020
        $('.date').datepicker({

          "format"          : "yyyy-mm-dd",
          "todayHighlight"  : true,
          "autoclose"       : true,
          "clearBtn"        : true

        });

        // 20-09-2020
        $('#tabel_report').on('click', '.detail', function () {
          
          var id_transaksi = $(this).data('id');

          $.ajax({
            url     : "Report/tampil_detail",
            type    : "POST",
            beforeSend  : function () {
                  swal({
                    title   : 'Menunggu',
                    html    : 'Memproses Data',
                    onOpen  : () => {
                        swal.showLoading();
                    }
                })
            },
            data    : {id_transaksi:id_transaksi},
            success : function (data) {

              swal.close();
                    
              $('.f_detail_transaksi').html(data);
              $('#modal_detail_transaksi').modal('show');

            }
          })

          return false;

        })

        // 20-09-2020
        $('button[name="export"]').on('click', function () {
            var jns = $(this).attr('data');

            $('#aksi').val(jns);
        })

      })
    </script>