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
                    <div class="table-wrap">
                        <table class="table w-100 display pb-30 table-bordered table-striped table-hover" width="100%">
                            <thead class="thead-light text-center">
                              <tr>
                                    <th width="5%" class="font-weight-bold" style="color: black;">No.</th>
                                    <th class="font-weight-bold" style="color: black;">Nama Karyawan</th>
                                    <th class="font-weight-bold" style="color: black;">Kode Karyawan</th>
                                    <th class="font-weight-bold" style="color: black;">Username</th>
                                    <th class="font-weight-bold" style="color: black;">Role</th>
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
    <script type="text/javascript">
      var save_method;
      var table;

      $(document).ready(function() {
          var table = $('.table').DataTable({
              "processing": true,
              "order": [[0, 'asc']],
              "ajax": {
                  "url": "<?php echo base_url('Karyawan/read')?>",
                  "type": "POST"
              },
              stateSave: true,
              "columnDefs": [
                  { 
                      "targets": [ 5 ],
                      "orderable": false,
                  },
                  {
                      "targets": [0,5],
                      "className": "text-center",
                  }
              ],
          });
      })

      function reload_table()
      {
          table.ajax.reload(null,false);
      }

      function create()
      {
        save_method = 'create';
        $('#form')[0].reset();
        $('#kode').hide();
        $('#pass_help').hide();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_form').modal('show');
        $('.modal-title').text('Tambah Karyawan Baru');
      }

      function update(id)
      {
          save_method = 'update';
          $('#form')[0].reset();
          $('.form-group').removeClass('has-error');
          $('.help-block').empty();
          $.ajax({
              url : "<?php echo site_url('Karyawan/edit/')?>/" + id,
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
                  $('#kode').show();
                  $('[name="id"]').val(data.id);
                  $('[name="nama_karyawan"]').val(data.nama_karyawan);
                  $('[name="kode_karyawan"]').val(data.kode_karyawan);
                  $('[name="username"]').val(data.username ? data.username : null);
                  if(data.username)
                  {
                    $('#pass_help').show();
                  }
                  else
                  {
                    $('#pass_help').hide();
                  }
                  $('#modal_form').modal('show');
                  $('.help-block').empty();
                  $('.modal-title').text('Sunting Data Karyawan');
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
        var table = $('.table').DataTable({
            "processing": true,
            "order": [[0, 'asc']],
            "ajax": {
                "url": "<?php echo base_url('Karyawan/read')?>",
                "type": "POST"
            },
            stateSave: true,
            "columnDefs": [
                { 
                    "targets": [ 2 ],
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
          title: 'Konfirmasi',
          text: "Yakin Ingin Menghapus Data ini?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Hapus',
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          cancelButtonText: 'Tidak',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url:"<?= base_url('Karyawan/delete')?>",  
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
                swal(
                  'Hapus',
                  'Data Berhasil Terhapus',
                  'success'
                )
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
            url = "<?php echo base_url('Karyawan/create')?>";
            isi_title = 'Tambah Karyawan';
            isi_text = 'Anda Berhasil Menambah Data Karyawan';
        } else {
            url = "<?php echo base_url('Karyawan/update')?>";
            isi_title = 'Update Karyawan';
            isi_text = 'Anda Berhasil Mengupdate Data Karyawan';
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
                        type: 'success',
                        title: isi_title,
                        text: isi_text
                    })
                    $('#modal_form').modal('hide');
                    $('.table').DataTable().ajax.reload();
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
                $('#btnSave').attr('disabled',false);
            }
        });
      }
    </script>
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
                          <label for="nama_karyawan" class="control-label">Nama Karyawan</label>
                          <input type="text" name="nama_karyawan" id="nama_karyawan" class="form-control" autocomplete="off" autofocus>
                          <span class="help-block"></span>
                        </div>
                        <div class="form-group" id="kode">
                          <label for="kode_karyawan" class="control-label">Kode Karyawan</label>
                          <input type="text" name="kode_karyawan" id="kode_karyawan" class="form-control" autocomplete="off" readonly>
                          <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                          <label for="username" class="control-label">Username</label>
                          <input type="text" name="username" id="username" class="form-control" autocomplete="off">
                          <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                          <label for="pass" class="control-label">Password <small id="pass_help" class="text-danger">Biarkan Kosong jika tidak Ingin Diisi</small></label>
                          <input type="password" name="pass" id="pass" class="form-control" autocomplete="off">
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
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->