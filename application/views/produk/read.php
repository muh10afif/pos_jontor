<style type="text/css">
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
</style>
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper pb-0">
      <div class="row">
            <div class="col-sm">
              <div class="card shadow">
                <div class="card-header" style="background-color: #ffbf00 ;">
                  <button class="btn btn-success btn-sm float-right shadow" onclick="create()">Tambah Data</button>
                  <h3 id="judul" class="font-weight-blod"><b><i class="mdi mdi-package menu-icon mdi-24px mr-2" style="color: black;"></i>Master <?php echo $title ?></b></h3>
                </div>
                <div class="card-body">
                    <div class="table-wrap table-responsive">
                        <table class="table w-100 display pb-30 table-bordered table-striped table-hover" width="100%" id="tabel_produk">
                            <thead class="text-center thead-light">
                              <tr>
                                    <th width="5%" class="font-weight-bold" style="color: black;">No.</th>
                                    <th class="font-weight-bold" style="color: black;">Nama Produk</th>
                                    <th class="font-weight-bold" style="color: black;">Kategori</th>
                                    <th class="font-weight-bold" style="color: black;">Harga</th>
                                    <th class="font-weight-bold" style="color: black;">HPP</th>
                                    <th class="font-weight-bold" style="color: black;">Diskon</th>
                                    <th width="10%" class="font-weight-bold" style="color: black;">Aksi</th>
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
    </div>

    <!-- 11-08-2020 -->
    <!-- Modal -->
    <div class="modal fade" id="modal_komposisi" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="detail">
                <div class="modal-header" style="background-color: #ffbf00;">
                    <h3 class="modal-title font-weight-bold" id="judul_komposisi"><i class="fa fa-clipboard-list mr-3"></i>List Komposisi</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="mr-2 text-dark">&times;</span>
                    </button>
                </div>
                <div class="modal-body table-responsive">
                  <!-- tab bar -->
                  <nav>
                      <div class="nav nav-tabs" id="nav-tab" role="tablist" style="border: 0;">

                          <a class="nav-item nav-link mr-2 active font-weight-bold shadow" style="border-radius: 7px;" id="nav-komposisi-tab" data-toggle="tab" href="#nav-komposisi" role="tab" aria-controls="nav-komposisi" aria-selected="true"><h4 class="mt-1">Komposisi</h4></a>

                          <a class="nav-item nav-link mr-2 font-weight-bold shadow" style="border-radius: 7px;" id="nav-tambah-komposisi-tab" data-toggle="tab" href="#nav-tambah-komposisi" role="tab" aria-controls="nav-tambah-komposisi" aria-selected="true"><h4 class="mt-1">Tambah Komposisi</h4></a>

                          <a class="nav-item nav-link mr-2 font-weight-bold shadow" style="border-radius: 7px;" id="nav-tambah-bahan-tab" data-toggle="tab" href="#nav-tambah-bahan" role="tab" aria-controls="nav-tambah-bahan" aria-selected="true"><h4 class="mt-1">Tambah Bahan</h4></a>

                      </div>
                  </nav>

                  <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show active" id="nav-komposisi" role="tabpanel" aria-labelledby="nav-komposisi-tab">
                        <div class="row mt-3">
                            <div class="col-sm">
                              <div class="card shadow">
                                <div class="card-body">
                                    <div class="table-wrap mt-3 table-responsive">
                                      <table class="table w-100 display table-bordered table-striped table-hover" id="tabel_komposisi" width="100%">
                                          <thead class="text-center thead-light">
                                              <tr>
                                                  <th width="5%" class="font-weight-bold" style="color: black;">No.</th>
                                                  <th class="font-weight-bold" style="color: black;">Bahan</th>
                                                  <th class="font-weight-bold" style="color: black;">Nilai Komposisi</th>
                                                  <th width="10%" class="font-weight-bold" style="color: black;">Aksi</th>
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
                    </div>

                    <div class="tab-pane fade" id="nav-tambah-komposisi" role="tabpanel" aria-labelledby="nav-tambah-komposisi-tab">
                        <div class="row mt-3">
                            <div class="col-sm">
                              <div class="card shadow">
                                <form id="form_komposisi" autocomplete="off">
                                  <div class="card-body">
                                    <div class="col-md-8 offset-md-2">
                                      <div class="controls">
                                        <div class="entry row" data="0">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="email" class="col-sm-12 col-form-label">Bahan</label>
                                                    <div class="col-sm-12">
                                                        <select name="nm_bahan[]" id="list_bahan" class="form-control list_bahan">
                                                            <?php foreach ($bahan as $k): ?>
                                                                <option value="<?= $k['id'] ?>"><?= $k['nama_product'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="email" class="col-sm-12 col-form-label">Nilai Komposisi</label>
                                                    <div class="col-sm-12">
                                                        <input type="text" class="form-control number-separator" style="font-size: 14px;" name="jumlah[]" placeholder="Masukkan Jumlah">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start ml-3">
                                                <button class="btn btn-success btn-sm shadow btn-add tombol" type="button">Tambah Form</button>
                                            </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card-footer">
                                    <div class="row ml-2">
                                      <div class="col-md-8 offset-md-2">
                                        <button type="button" class="btn btn-primary" id="simpan_komposisi">Simpan Komposisi</button>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-tambah-bahan" role="tabpanel" aria-labelledby="nav-tambah-bahan-tab">
                        <div class="row mt-3">
                            <div class="col-sm">
                              <div class="card shadow">
                                <form id="form_bahan" autocomplete="off">
                                  <input type="hidden" name="id_product" id="id_product_t">
                                  <input type="hidden" name="aksi_bahan" id="aksi_bahan" value="Tambah">
                                  <div class="card-body">
                                    <div class="col-md-8 offset-md-2">
                                        <div class="entry row" data="0">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="nama_bahan" class="col-sm-12 col-form-label">Nama Bahan</label>
                                                    <div class="col-sm-12">
                                                      <input type="text" class="form-control" style="font-size: 14px;" id="nama_bahan" name="nama_bahan" placeholder="Masukkan Nama Bahan">
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="kategori" class="col-sm-12 col-form-label">Kategori</label>
                                                    <div class="col-sm-12">
                                                        <select name="kategori" id="kategori" class="form-control">
                                                            <?php foreach ($kategori as $e): ?>
                                                                <option value="<?= $e->id ?>"><?= $e->kategori ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="satuan" class="col-sm-12 col-form-label">Satuan</label>
                                                    <div class="col-sm-12">
                                                      <input type="text" class="form-control" style="font-size: 14px;" id="satuan" name="satuan" placeholder="Kilo/Dus/Botol">
                                                    </div>
                                                </div>  
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label for="minimal_stok" class="col-sm-12 col-form-label">Minimal Stok</label>
                                                    <div class="col-sm-12">
                                                      <input type="text" class="form-control number-separator" style="font-size: 14px;" id="minimal_stok" name="minimal_stok" placeholder="Masukkan Minimal Stok">
                                                    </div>
                                                </div>  
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="card-footer">
                                    <div class="row ml-2">
                                      <div class="col-md-8 offset-md-2">
                                        <button type="button" class="btn btn-primary" id="simpan_bahan">Simpan Bahan</button>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_form" role="dialog">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h3 class="modal-title">Form</h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body form">
                  <form action="#" id="form">
                      <input type="hidden" value="" name="id"/> 
                      <div class="form-body">
                        <div class="form-group">
                          <label for="id_kategori" class="control-label">Kategori</label>
                          <select name="id_kategori" id="id_kategori" class="form-control">
                            <option selected disabled hidden>--PILIH--</option>
                            <?php 
                            foreach($kategori as $row) {
                            ?>
                              <option value="<?php echo $row->id ?>"><?php echo $row->kategori ?></option>
                            <?php } ?>
                          </select>
                          <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                          <label for="nama_product" class="control-label">Nama Produk</label>
                          <input type="text" name="nama_product" id="nama_product" class="form-control" autocomplete="off" autofocus>
                          <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                          <label for="harga" class="control-label">Harga</label>
                          <input type="text" name="harga" id="harga" class="form-control" autocomplete="off" autofocus>
                          <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                          <label for="hpp" class="control-label">HPP</label>
                          <input type="text" name="hpp" id="hpp" class="form-control" autocomplete="off" autofocus>
                          <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                          <label for="have_discount" class="control-label">Diskon</label>
                          <select name="have_discount" id="have_discount" class="form-control">
                            <option selected disabled hidden>--PILIH--</option>
                            <option value="1">Ada</option>
                            <option value="0">Tidak ada</option>
                          </select>
                          <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                          <label for="status_bahan" class="control-label">Bahan Stok</label>
                          <select name="status_bahan" id="status_bahan" class="form-control">
                            <option selected disabled hidden>--PILIH--</option>
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                          </select>
                          <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                          <label for="status_tampil" class="control-label">Tampilkan pada transaksi</label>
                          <select name="status_tampil" id="status_tampil" class="form-control">
                            <option selected disabled hidden>--PILIH--</option>
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                          </select>
                          <span class="help-block"></span>
                        </div>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
                  <button type="button" id="btnSave" onclick="save()" class="btn btn-success"><i class="fa fa-paper-plane"></i> Save</button>
              </div>
          </div>
      </div>
    </div>

    <!-- modal edit komposisi -->
    <div class="modal fade" id="modal_edit_komposisi" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background-color: #ffbf00;">
                <h5 class="modal-title font-weight-bold text-white" id="judul_edit">Edit Komposisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="mr-2 text-dark">&times;</span>
                </button>
            </div>
                <form id="form_edit_komposisi" autocomplete="off">
                    <input type="hidden" id="id_komposisi">
                    <div class="modal-body">
                        <div class="col-md-12 p-3">
                            <div class="form-group row">
                                <label for="email" class="col-sm-4 col-form-label">Nama Bahan</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" style="font-size: 14px;" name="nama_bahan_edit" id="nama_bahan_edit" readonly>
                                </div>
                            </div>  
                            <div class="form-group row">
                                <label for="nilai_komposisi" class="col-sm-4 col-form-label">Nilai Komposisi</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control number-separator" style="font-size: 14px;" name="nilai_komposisi" id="nilai_komposisi">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success" id="simpan_komposisi_edit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="id_product">

    <script>
      var harga = document.getElementById('harga');
      harga.addEventListener('keyup', function(e){
          harga.value = formatRupiah(this.value, '');
        });
      var hpp = document.getElementById('hpp');
      hpp.addEventListener('keyup', function(e){
          hpp.value = formatRupiah(this.value, '');
        });
      function formatRupiah(angka, prefix){
          var number_string = angka.replace(/[^,\d]/g, '').toString(),
          split           = number_string.split(','),
          sisa            = split[0].length % 3,
          rupiah          = split[0].substr(0, sisa),
          ribuan          = split[0].substr(sisa).match(/\d{3}/gi);
          if(ribuan){
              separator = sisa ? '.' : '';
              rupiah += separator + ribuan.join('.');
          }

          rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
          return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
      }
      $(document).ready(function(){
            $('#harga').on('keypress', function(e) {
                var c = e.keyCode || e.charCode; switch (c) {
                case 8: case 9: case 27: case 13: return;  case 65:   if (e.ctrlKey === true) return; 
                } 
                if (c < 48 || c > 57) 
                e.preventDefault();
            });
            $('#hpp').on('keypress', function(e) {
                var c = e.keyCode || e.charCode; switch (c) {
                case 8: case 9: case 27: case 13: return;  case 65:   if (e.ctrlKey === true) return; 
                } 
                if (c < 48 || c > 57) 
                e.preventDefault();
            });
        })
    </script>

    <!-- 12-08-2020 -->
    <script>
        $(function(){
          $(document).on('click', '.btn-add', function(e) {

                    e.preventDefault();

                    var controlForm = $('.controls:first'),
                        currentEntry = $(this).parents('.entry:first'),
                        newEntry = $(currentEntry.clone()).appendTo(controlForm);

                    newEntry.find('input').val('');

                    controlForm.find('.entry:not(:last) .btn-add')
                        .removeClass('btn-add').addClass('btn-remove')
                        .removeClass('btn-success').addClass('btn-danger')
                        .html('Hapus');

                    controlForm.find('.entry:not(:first)').attr("data", 1);
                
          }).on('click', '.btn-remove', function(e) {

                    $(this).parents('.entry:first').remove();

                    e.preventDefault();
                    return false;

          });
        }
      );
    </script>

    <script type="text/javascript">
      var save_method;
      var table;

      $(document).ready(function() {

        // 12-08-2020
        // aksi simpan data bahan
        $('#simpan_bahan').on('click', function () {

          var form_bahan    = $('#form_bahan').serialize();
          var nama_bahan    = $('#nama_bahan').val();
          var kategori      = $('#kategori').val();
          var satuan        = $('#satuan').val();
          var minimal_stok  = $('#minimal_stok').val();

          if (nama_bahan == '') {
              
              $('#nama_bahan').focus();

              swal({
                  title               : "Peringatan",
                  text                : 'Nama bahan harus terisi !',
                  buttonsStyling      : false,
                  type                : 'warning',
                  showConfirmButton   : false,
                  timer               : 700
              }); 
              

          } else if (kategori == null) {

              $('#kategori').focus();

              swal({
                  title               : "Peringatan",
                  text                : 'Kategori harus terisi !',
                  buttonsStyling      : false,
                  type                : 'warning',
                  showConfirmButton   : false,
                  timer               : 700
              }); 

          } else if (satuan == '') {

              $('#satuan').focus();

              swal({
                  title               : "Peringatan",
                  text                : 'Satuan harus terisi !',
                  buttonsStyling      : false,
                  type                : 'warning',
                  showConfirmButton   : false,
                  timer               : 1000
              }); 

          } else if (minimal_stok == '') {

              $('#minimal_stok').focus();

              swal({
                  title               : "Peringatan",
                  text                : 'Minimal Stok harus terisi !',
                  buttonsStyling      : false,
                  type                : 'warning',
                  showConfirmButton   : false,
                  timer               : 1000
              }); 

          } else {

              swal({
                  title       : 'Konfirmasi',
                  text        : 'Yakin akan kirim data',
                  type        : 'warning',

                  buttonsStyling      : false,
                  confirmButtonClass  : "btn btn-primary",
                  cancelButtonClass   : "btn btn-warning mr-3",

                  showCancelButton    : true,
                  confirmButtonText   : 'Ya',
                  confirmButtonColor  : '#3085d6',
                  cancelButtonColor   : '#d33',
                  cancelButtonText    : 'Batal',
                  reverseButtons      : true
              }).then((result) => {
                  if (result.value) {
                      $.ajax({
                          url     : "Bahan/simpan_data_bahan",
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
                          data    : form_bahan,
                          dataType: "JSON",
                          success : function (data) {

                              swal({
                                  title               : "Berhasil",
                                  text                : 'Data berhasil disimpan',
                                  buttonsStyling      : false,
                                  confirmButtonClass  : "btn btn-success",
                                  type                : 'success',
                                  showConfirmButton   : false,
                                  timer               : 1000
                              });    

                              $('[href="#nav-tambah-komposisi"]').tab('show');
                    
                              $('#form_bahan').trigger("reset");

                              $('.list_bahan').html(data.list_bahan);
                              
                          }
                      })
              
                      return false;

                  } else if (result.dismiss === swal.DismissReason.cancel) {

                      swal({
                          title               : "Batal",
                          text                : 'Anda membatalkan simpan data',
                          buttonsStyling      : false,
                          confirmButtonClass  : "btn btn-primary",
                          type                : 'error',
                          showConfirmButton   : false,
                          timer               : 1000
                      }); 
                  }
              })

              return false;

            }

        })

        $('#nav-komposisi-tab').on('click', function () {
            
          tabel_komposisi.ajax.reload(null, false);
          tabel_komposisi.clear().draw();
          tabel_komposisi.search("").draw();

        })

        $('#nav-tambah-komposisi-tab').on('click', function () {

          var id_product = $('#id_product').val();
          
          $.ajax({
              url     : "Produk/ambil_option_bahan",
              type    : "POST",
              data    : {id_product:id_product},
              dataType: "JSON",
              success: function(data)
              {
                  $('.list_bahan').html(data.list_pro);

                  swal.close();
              },
              error: function (jqXHR, textStatus, errorThrown)
              {
                  swal({
                        icon  : 'Error',
                        title : 'Gagal',
                        text  : 'Data tidak Ditemukan'
                    })
              }
          });

        })

        function checkIfDuplicateExists(w){
            return new Set(w).size !== w.length 
        }

        // simpan stok
        $('#simpan_komposisi').on('click', function () {

          var jumlah = $('input[name="jumlah[]"]').map(function () {
              return this.value;
          }).get();

          var nm_bahan = $('select[name="nm_bahan[]"] option:selected').map(function () {
              return this.value;
          }).get();

          var cek = checkIfDuplicateExists(nm_bahan);

          var jml = jumlah.find(element => element === "");

          var id_product = $('#id_product').val();

          var jml_bahan = nm_bahan.length;

          if (jml == "") {
            jml = "ada";
          } else {
            jml = "tidak ada";
          }

          console.log(nm_bahan);

          if (cek) {

              swal({
                  title               : "Peringatan",
                  text                : 'Terdapat bahan yang sama, harap pilih satu bahan diantaranya!',
                  buttonsStyling      : false,
                  confirmButtonClass  : "btn btn-primary",
                  type                : 'warning',
                  showConfirmButton   : true
              }); 
              
              return false;

          } else if (jml === "ada") {

              swal({
                  title               : "Peringatan",
                  text                : 'Harap lengkap semua input jumlah!',
                  buttonsStyling      : false,
                  confirmButtonClass  : "btn btn-primary",
                  type                : 'warning',
                  showConfirmButton   : true
              }); 
              
              return false;
          
          } else {

              $.ajax({
                  url     : "Produk/simpan_komposisi",
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
                  data    : {aksi:'tambah', jumlah:jumlah, nm_bahan:nm_bahan, jml:jml_bahan, id_product:id_product},
                  dataType: "JSON",
                  success : function (data) {

                      swal({
                          title               : "Berhasil",
                          text                : 'Data berhasil disimpan',
                          buttonsStyling      : false,
                          confirmButtonClass  : "btn btn-success",
                          type                : 'success',
                          showConfirmButton   : false,
                          timer               : 1000
                      });    

                      $('[href="#nav-komposisi"]').tab('show');    

                      tabel_komposisi.ajax.reload(null,false);    
                      
                      $('#form_komposisi').trigger("reset");
                      
                  }
              })

              return false;

          }

        })

        // edit komposisi
        $('#tabel_komposisi').on('click', '.edit-komposisi', function () {
          
          var id_komposisi = $(this).data('id');
          var nm_bahan     = $(this).attr('nm_product');
          var n_komposisi  = $(this).attr('nilai_komposisi');

          $('#nama_bahan_edit').val(nm_bahan);
          $('#nilai_komposisi').val(n_komposisi);
          $('#id_komposisi').val(id_komposisi);

          $('#modal_edit_komposisi').modal('show');

        })

        // hapus komposisi
        $('#tabel_komposisi').on('click', '.hapus-komposisi', function () {

          var id_komposisi = $(this).data('id');

          swal({
              title       : 'Konfirmasi',
              text        : 'Yakin akan hapus data',
              type        : 'warning',

              buttonsStyling      : false,
              confirmButtonClass  : "btn btn-primary",
              cancelButtonClass   : "btn btn-danger mr-3",

              showCancelButton    : true,
              confirmButtonText   : 'Hapus',
              confirmButtonColor  : '#d33',
              cancelButtonColor   : '#3085d6',
              cancelButtonText    : 'Batal',
              reverseButtons      : true
          }).then((result) => {
              if (result.value) {
                  $.ajax({
                      url         : "Produk/simpan_komposisi",
                      method      : "POST",
                      beforeSend  : function () {
                          swal({
                              title   : 'Menunggu',
                              html    : 'Memproses Data',
                              onOpen  : () => {
                                  swal.showLoading();
                              }
                          })
                      },
                      data        : {aksi:'hapus', id_komposisi:id_komposisi},
                      dataType    : "JSON",
                      success     : function (data) {

                              tabel_komposisi.ajax.reload(null,false);   

                              swal({
                                  title               : 'Hapus komposisi',
                                  text                : 'Data Berhasil Dihapus',
                                  buttonsStyling      : false,
                                  confirmButtonClass  : "btn btn-success",
                                  type                : 'success',
                                  showConfirmButton   : false,
                                  timer               : 1000
                              }); 
                          
                      },
                      error       : function(xhr, status, error) {
                          var err = eval("(" + xhr.responseText + ")");
                          alert(err.Message);
                      }

                  })

                  return false;
              } else if (result.dismiss === swal.DismissReason.cancel) {

                  swal({
                          title               : 'Batal',
                          text                : 'Anda membatalkan hapus komposisi',
                          buttonsStyling      : false,
                          confirmButtonClass  : "btn btn-primary",
                          type                : 'error',
                          showConfirmButton   : false,
                          timer               : 1000
                      }); 
              }
          })

        })

        // simpan edit komposisi
        $('#simpan_komposisi_edit').on('click', function () {

            var jumlah       = $('#nilai_komposisi').val();
            var id_komposisi = $('#id_komposisi').val();

            $.ajax({
                url     : "Produk/simpan_komposisi",
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
                data    : {aksi:'edit', jumlah:jumlah, id_komposisi:id_komposisi},
                dataType: "JSON",
                success : function (data) {

                    swal({
                        title               : "Berhasil",
                        text                : 'Data berhasil disimpan',
                        buttonsStyling      : false,
                        confirmButtonClass  : "btn btn-success",
                        type                : 'success',
                        showConfirmButton   : false,
                        timer               : 1000
                    });      
                    
                    $('#modal_edit_komposisi').modal('hide');

                    tabel_komposisi.ajax.reload(null,false);    
                    
                    
                }
            })

            return false;

        })

        // 11-08-2020

        $('#tabel_produk').on('click', '.komposisi', function () {

          var id_product = $(this).data('id');
          var nm_product = $(this).attr('nm_produk');

          $('[href="#nav-komposisi"]').tab('show');
          
          $('#modal_komposisi').modal('show');

          $('#id_product').val(id_product);
          $('#id_product_t').val(id_product);
          $('#judul_komposisi').html("<i class='fa fa-clipboard-list mr-2'></i>Komposisi "+nm_product);

          tabel_komposisi.ajax.reload(null, false);
          tabel_komposisi.clear().draw();
          tabel_komposisi.search("").draw();

        })

        var tabel_komposisi = $('#tabel_komposisi').DataTable({
            "processing": true,
            "order": [[0, 'asc']],
            "ajax": {
                "url": "<?php echo base_url('Produk/tampil_data_komposisi')?>",
                "type": "POST",
                "data"  : function (data) {
                    data.id_product   = $('#id_product').val();
                }
            },
            stateSave: true,
            "oLanguage" : {
                "sProcessing"   : "Harap Tunggu.."
            },
            "language"  : {
                "emptyTable"    : "List Kosong"
            },
            "columnDefs": [
                { 
                    "targets": [0,3],
                    "orderable": false,
                },
                {
                    "targets": [0,2,3],
                    "className": "text-center",
                }
            ],
        });

        // $('body').tooltip({selector: '[data-toggle="tooltip"]'});

        var table = $('#tabel_produk').DataTable({
            "processing": true,
            "order": [[0, 'asc']],
            "ajax": {
                "url": "<?php echo base_url('Produk/read')?>",
                "type": "POST"
            },
            stateSave: true,
            "columnDefs": [
                { 
                    "targets": [ 6 ],
                    "orderable": false,
                },
                {
                    "targets": [0,6],
                    "className": "text-center",
                }
            ],
        });

      })

      function number_format (number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
      }

      function reload_table()
      {
          table.ajax.reload(null,false);
      }

      function create()
      {
        save_method = 'create';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_form').modal('show');
        $('.modal-title').text('Tambah Produk Baru');
      }

      function update(id)
      {
          save_method = 'update';
          $('#form')[0].reset();
          $('.form-group').removeClass('has-error');
          $('.help-block').empty();
          $.ajax({
              url : "<?php echo site_url('Produk/edit/')?>/" + id,
              beforeSend :function () {
              swal({
                  title: 'Menunggu',
                  html: 'Memproses data',
                  onOpen: () => {
                    swal.showLoading()
                  }
                })      
              },
              type: "GET",
              dataType: "JSON",
              success: function(data)
              {
                  swal.close();
                  $('[name="id"]').val(data.id);
                  $('[name="id_kategori"]').val(data.id_kategori);
                  $('[name="nama_product"]').val(data.nama_product);
                  $('[name="harga"]').val(number_format(data.harga,0,',','.'));
                  $('[name="hpp"]').val(number_format(data.hpp,0,',','.'));
                  $('[name="have_discount"]').val(data.have_discount);
                  $('[name="status_bahan"]').val(data.status_bahan);
                  $('[name="status_tampil"]').val(data.status_tampil);
                  $('#modal_form').modal('show');
                  $('.help-block').empty();
                  $('.modal-title').text('Sunting Data Produk');
              },
              error: function (jqXHR, textStatus, errorThrown)
              {
                  swal({
                        icon: 'Error',
                        title: 'Gagal',
                        text: 'Data tidak Ditemukan'
                    })
              }
          });
      }

      function delete_data(id) {
        console.log("hapus");

        var table = $('.table').DataTable({
            "processing": true,
            "order": [[0, 'asc']],
            "ajax": {
                "url": "<?php echo base_url('Produk/read')?>",
                "type": "POST"
            },
            stateSave: true,
            "columnDefs": [
                { 
                    "targets": [0],
                    "orderable": false,
                },
                {
                    "targets": [0],
                    "className": "text-center",
                }
            ],
            'bDestroy': true,
          });
        swal({
          title             : 'Konfirmasi',
          text              : "Yakin Ingin Menghapus Data ini?",
          type              : 'warning',
          showCancelButton  : true,
          confirmButtonText : 'Hapus',
          confirmButtonColor: '#d33',
          cancelButtonColor : '#3085d6',
          cancelButtonText  : 'Batal',
          reverseButtons    : true
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url:"<?= base_url('Produk/delete')?>",  
              method:"post",
              beforeSend :function () {
              swal({
                  title: 'Menunggu',
                  html: 'Memproses data',
                  onOpen: () => {
                    swal.showLoading()
                  }
                })      
              },    
              data:{id:id},
              success:function(data){

                swal({
                    title               : "Berhasil",
                    text                : 'Data berhasil terhapus',
                    buttonsStyling      : false,
                    confirmButtonClass  : "btn btn-success",
                    type                : 'success',
                    showConfirmButton   : false,
                    timer               : 500
                }); 

                table.ajax.reload(null, false);
              }
            })
        } else if (result.dismiss === swal.DismissReason.cancel) {
            swal(
              'Batal',
              'Anda membatalkan penghapusan',
              'error'
            )
          }
        })
      }

      function save() {
        var url;
        if(save_method == 'create') {
            url = "<?php echo base_url('Produk/create')?>";
            isi_title = 'Tambah Produk';
            isi_text = 'Anda Berhasil Menambah Data Produk';
        } else {
            url = "<?php echo base_url('Produk/update')?>";
            isi_title = 'Update Produk';
            isi_text = 'Anda Berhasil Mengupdate Data Produk';
        }
        $.ajax({
            url : url,
            beforeSend :function () {
            swal({
                title: 'Menunggu',
                html: 'Memproses data',
                onOpen: () => {
                  swal.showLoading()
                }
              })      
            },
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status)
                {
                    swal({
                        title               : isi_title,
                        text                : isi_text,
                        buttonsStyling      : false,
                        confirmButtonClass  : "btn btn-success",
                        type                : 'success',
                        showConfirmButton   : false,
                        timer               : 500
                    }); 

                    $('#modal_form').modal('hide');
                    $('#tabel_produk').DataTable().ajax.reload();
                }
                else
                {
                    swal.close();
                    for (var i = 0; i < data.inputerror.length; i++) 
                    {
                        $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSave').text('save');
                $('#btnSave').attr('disabled',false);
            }
        });
      }

    </script>
    
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->