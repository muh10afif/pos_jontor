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

        <div class="card shadow">
            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist" style="border: 0;">

                        <a class="nav-item nav-link mr-2 active font-weight-bold shadow" style="border-radius: 7px;" id="nav-bahan-tab" data-toggle="tab" href="#nav-bahan" role="tab" aria-controls="nav-bahan" aria-selected="true"><h3 class="mt-1">Bahan</h3></a>

                        <a class="nav-item nav-link mr-2 font-weight-bold shadow" style="border-radius: 7px;" id="nav-stok-tab" data-toggle="tab" href="#nav-stok" role="tab" aria-controls="nav-stok" aria-selected="true"><h3 class="mt-1">Stok</h3></a>

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show active" id="nav-bahan" role="tabpanel" aria-labelledby="nav-bahan-tab">
                        <div class="row mt-3">
                            <div class="col-sm">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-success btn-sm shadow" id="tambah_bahan">Tambah Data</button>
                                </div>
                                <div class="table-wrap mt-3">
                                    <table class="table w-100 display table-bordered table-striped table-hover" id="tabel_bahan" width="100%">
                                        <thead class="text-center thead-light">
                                            <tr>
                                                <th width="5%" class="font-weight-bold">No.</th>
                                                <th class="font-weight-bold">Bahan</th>
                                                <th class="font-weight-bold">Kategori</th>
                                                <th class="font-weight-bold">Satuan</th>
                                                <th class="font-weight-bold">Minimal Stok</th>
                                                <th width="10%" class="font-weight-bold">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="nav-stok" role="tabpanel" aria-labelledby="nav-stok-tab">
                        <div class="row mt-3">
                            <div class="col-sm">
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-success btn-sm shadow mr-2 tambah_stok" data-id="Barang Masuk">Barang Masuk</button>
                                    <button class="btn btn-warning btn-sm shadow mr-2 tambah_stok" data-id="Barang Keluar">Barang Keluar</button>
                                    <button class="btn btn-danger btn-sm shadow tambah_stok" data-id="Barang Return">Retur Barang</button>
                                </div>
                                <div class="table-wrap mt-3">
                                    <table class="table w-100 display table-bordered table-striped table-hover" id="tabel_stok" width="100%">
                                        <thead class="text-center thead-light">
                                            <tr>
                                                <th width="5%" class="font-weight-bold">No.</th>
                                                <th class="font-weight-bold">Product</th>
                                                <th class="font-weight-bold">Stok</th>
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
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_stok" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background-color: #ffbf00;">
                <h5 class="modal-title font-weight-bold text-white" id="judul_stok">Tambah Data stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="mr-2 text-dark">&times;</span>
                </button>
            </div>
                <form id="form_stok" autocomplete="off">
                    <input type="hidden" name="id_stok" id="id_stok">
                    <input type="hidden" name="aksi" id="aksi" value="Tambah">
                    <div class="modal-body">
                        <input type="hidden" id="jenis_barang">
                        <div class="controls">
                            <div class="entry row" data="0">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-12 col-form-label">Bahan</label>
                                        <div class="col-sm-12">
                                            <select name="nm_bahan[]" id="list_bahan" class="form-control">
                                                <?php foreach ($bahan as $k): ?>
                                                    <option value="<?= $k['id'] ?>"><?= $k['nama_product'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>  
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-12 col-form-label">Jumlah</label>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="simpan_stok">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_bahan" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header" style="background-color: #ffbf00;">
                <h5 class="modal-title font-weight-bold text-white" id="judul_modal">Tambah Data Bahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="mr-2 text-dark">&times;</span>
                </button>
            </div>
                <!-- <form action="#" method="post">
                    <div class="controls"> 
                        <div class="form-group mt-2 mb-2">
                            <div class="entry input-group">
                                <input class="form-control" name="data[]" type="text" placeholder="Ketik di sini..." required>
                                    <span class="input-group-btn">
                                        <button class="btn btn-success btn-add" type="button">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </span>
                            </div>
                    </div>
                    </div>
                </form> -->
                <form id="form_bahan" autocomplete="off">
                    <input type="hidden" name="id_bahan" id="id_bahan">
                    <input type="hidden" name="aksi_bahan" id="aksi_bahan" value="Tambah">
                    <div class="modal-body">
                        <div class="col-md-12 p-3">
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Nama Bahan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" style="font-size: 14px;" name="nama_bahan" id="nama_bahan" placeholder="Masukkan Nama Bahan">
                                </div>
                            </div>  
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Kategori</label>
                                <div class="col-sm-9">
                                    <select name="kategori" id="kategori" class="form-control select2">
                                        <option selected disabled hidden>--PILIH--</option>
                                        <?php foreach ($kategori as $k): ?>
                                        <option value="<?= $k['id'] ?>"><?= $k['kategori'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Satuan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" style="font-size: 14px;" name="satuan" id="satuan" placeholder="Dus / Ikat / Renteng">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 col-form-label">Minimal Stok</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control number-separator" style="font-size: 14px;" name="minimal_stok" id="minimal_stok" placeholder="Masukkan Minimal Stok">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success" id="simpan_bahan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
    
<script>

    $(document).ready(function () {
        
        // 3-08-2020

        // menampilkan list stok
        var tabel_stok = $('#tabel_stok').DataTable({
            "processing"        : true,
            "serverSide"        : true,
            "order"             : [],
            "ajax"              : {
                "url"   : "bahan/tampil_data_stok",
                "type"  : "POST"
            },
            "columnDefs"        : [{
                "targets"   : [0],
                "orderable" : false
            }, {
                'targets'   : [0,2],
                'className' : 'text-center',
            }]

        })

        // menampilkan modal tambah stok
        $('.tambah_stok').on('click', function () {
            $('#form_stok').trigger('reset');
            $('#aksi').val('Tambah');
            $('#modal_stok').modal('show');

            var judul = $(this).data('id');

            $('#jenis_barang').val(judul);
            $('#judul_stok').text("Tambah "+judul);

            // $(".entry").hide();

            $(".entry").each(function(){

                if ($(this).attr('data') == 1) {
                    $(this).remove();
                }
                
            });
            
            $('.tombol').removeClass('btn-remove').addClass('btn-add').removeClass('btn-danger').addClass('btn-success').html('Tambah Form');

            $.ajax({
                url         : "bahan/ambil_list_bahan",
                type        : "POST",
                beforeSend 	: function (e) {
                    if (e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charshet=UTF-8");
                    }				
                },
                dataType    : "JSON",
                success     : function (data) {
                    $('#list_bahan').html(data.product);
                },
                error 		: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        })

        // 07-08-2020
        $('#nav-stok-tab').on('click', function () {
            
            tabel_stok.ajax.reload(null, false);

        })

        $('#nav-bahan-tab').on('click', function () {
            
            tabel_bahan.ajax.reload(null, false);

        })

        // 04-08-2020

        // menampilkan list bahan
        var tabel_bahan = $('#tabel_bahan').DataTable({
            "processing"        : true,
            "serverSide"        : true,
            "order"             : [],
            "ajax"              : {
                "url"   : "bahan/tampil_data_bahan",
                "type"  : "POST"
            },
            "columnDefs"        : [{
                "targets"   : [0,4],
                "orderable" : false
            }, {
                'targets'   : [0,4,5],
                'className' : 'text-center',
            }]

        })

        // menampilkan modal tambah stok
        $('#tambah_bahan').on('click', function () {
            $('#form_bahan').trigger('reset');
            $('#aksi').val('Tambah');
            $('#modal_bahan').modal('show');
            $("#kategori").val(null).trigger("change");
        })

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

                                $('#modal_bahan').modal('hide');
                                
                                swal({
                                    title               : "Berhasil",
                                    text                : 'Data berhasil disimpan',
                                    buttonsStyling      : false,
                                    confirmButtonClass  : "btn btn-success",
                                    type                : 'success',
                                    showConfirmButton   : false,
                                    timer               : 1000
                                });    
                
                                tabel_bahan.ajax.reload(null,false);        
                                
                                $('#form_bahan').trigger("reset");
                                
                                $('#aksi_bahan').val('Tambah');

                                $('#list_bahan').html(data.product);
                                
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

        // edit data bahan
        $('#tabel_bahan').on('click', '.edit-bahan', function () {

            var id_bahan  = $(this).data('id');

            $.ajax({
                url         : "Bahan/ambil_data_bahan/"+id_bahan,
                type        : "GET",
                beforeSend  : function () {
                    swal({
                        title   : 'Menunggu',
                        html    : 'Memproses Data',
                        onOpen  : () => {
                            swal.showLoading();
                        }
                    })
                },
                dataType    : "JSON",
                success     : function(data)
                {
                    console.log(data);

                    swal.close();

                    $('#modal_bahan').modal('show');
                    
                    $('#id_bahan').val(data.id);

                    $('#nama_bahan').val(data.nama_product);
                    $('#satuan').val(data.satuan);
                    $('#minimal_stok').val(data[0].minimal_stok);

                    $("#kategori").val(data.id_kategori).trigger("change");

                    $('#aksi_bahan').val('Ubah');

                    return false;

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            })

            return false;

        })

        // hapus bahan
        $('#tabel_bahan').on('click', '.hapus-bahan', function () {

            var id_bahan = $(this).data('id');

            console.log(id_bahan);

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
                        url         : "Bahan/simpan_data_bahan",
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
                        data        : {aksi_bahan:'Hapus', id_bahan:id_bahan},
                        dataType    : "JSON",
                        success     : function (data) {

                                tabel_bahan.ajax.reload(null,false);   

                                swal({
                                    title               : 'Hapus bahan',
                                    text                : 'Data Berhasil Dihapus',
                                    buttonsStyling      : false,
                                    confirmButtonClass  : "btn btn-success",
                                    type                : 'success',
                                    showConfirmButton   : false,
                                    timer               : 1000
                                }); 

                                $('#form_bahan').trigger("reset");

                                $('#aksi_bahan').val('Tambah');
                            
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
                            text                : 'Anda membatalkan hapus bahan',
                            buttonsStyling      : false,
                            confirmButtonClass  : "btn btn-primary",
                            type                : 'error',
                            showConfirmButton   : false,
                            timer               : 1000
                        }); 
                }
            })

        })

        function checkIfDuplicateExists(w){
            return new Set(w).size !== w.length 
        }

        // simpan stok
        $('#simpan_stok').on('click', function () {

            var jumlah = $('input[name="jumlah[]"]').map(function () {
                return this.value;
            }).get();

            var nm_bahan = $('select[name="nm_bahan[]"] option:selected').map(function () {
                return this.value;
            }).get();

            var cek = checkIfDuplicateExists(nm_bahan);

            var jns_barang = $('#jenis_barang').val();

            var jml_bahan = nm_bahan.length;

            console.log(nm_bahan.length);

            if (cek) {

                swal({
                    title               : "Peringatan",
                    text                : 'Terdapat bahan yang sama, harap pilih satu bahan diantaranya!',
                    buttonsStyling      : false,
                    type                : 'warning',
                    showConfirmButton   : false,
                    timer               : 1300
                }); 
                
                return false;

            } else {

                $.ajax({
                    url     : "Bahan/simpan_stok",
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
                    data    : {jumlah:jumlah, nm_bahan:nm_bahan, jns_barang:jns_barang, jml:jml_bahan},
                    dataType: "JSON",
                    success : function (data) {

                        $('#modal_stok').modal('hide');
                        
                        swal({
                            title               : "Berhasil",
                            text                : 'Data berhasil disimpan',
                            buttonsStyling      : false,
                            confirmButtonClass  : "btn btn-success",
                            type                : 'success',
                            showConfirmButton   : false,
                            timer               : 1000
                        });    
        
                        tabel_stok.ajax.reload(null,false);        
                        
                        // location.reload();

                        $('[href="#nav-stok"]').tab('show');
                        
                    }
                })
        
                return false;

            }
        })

    })

</script>