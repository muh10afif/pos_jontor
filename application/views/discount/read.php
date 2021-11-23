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
                            <thead class="text-center thead-light">
                              <tr>
                                    <th width="5%" class="font-weight-bold" style="color: black;">No.</th>
                                    <th class="font-weight-bold" style="color: black;">Nama Produk</th>
                                    <th class="font-weight-bold" style="color: black;">Satuan</th>
                                    <th class="font-weight-bold" style="color: black;">Discount</th>
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

      function number_format (number, decimals, dec_point, thousands_sep) {
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
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
      };

      $(document).ready(function() {
          var table = $('.table').DataTable({
              "processing": true,
              "order": [[0, 'asc']],
              "ajax": {
                  "url": "<?php echo base_url('Discount/read')?>",
                  "type": "POST"
              },
              stateSave: true,
              "columnDefs": [
                  { 
                      "targets": [ 4 ],
                      "orderable": false,
                  },
                  {
                      "targets": [0],
                      "className": "text-center",
                  }
              ],
          });

          $('#satuan').change(function(event) {
            $('#input-discount').show();
            if($('select[name=satuan] option').filter(':selected').val() == '%')
            {
              $('#discount_persen').show();
              $('#discount_harga').hide();
              $('#discount_persen').val('');
            }
            else
            {
              $('#discount_harga').show();
              $('#discount_persen').hide();
              $('#discount_harga').val('');
            }
          });
          $('#discount_persen').keydown(function(e) {
            var key = e.charCode || e.keyCode || 0;
            return (
                key == 8 || 
                key == 9 ||
                key == 13 ||
                key == 46 ||
                key == 110 ||
                key == 190 ||
                (key >= 35 && key <= 40) ||
                (key >= 48 && key <= 57) ||
                (key >= 96 && key <= 105));
          });
          $('#discount_persen').keypress(function(event) {
            if($(this).val().length >= 2) {
              $(this).val($(this).val().slice(0, 2));
              return false;
            }
          });
          $('#discount_harga').keyup(function(event) {
            if(event.which >= 37 && event.which <= 40) return;
            $(this).val(function(index, value) {
              return value
              .replace(/\D/g, "")
              .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
              ;
            });
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
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_form').modal('show');
        $('#input-discount').hide();
        $('.modal-title').text('Tambah Data DIskon Baru');

        $.ajax({
              url : "<?php echo site_url('Discount/ambil_data_produk')?>",
              type: "POST",
              dataType: "JSON",
              success: function(data)
              {
                  $('#id_product').html(data.list_pro);

                  swal.close();
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

      function update(id)
      {
          save_method = 'update';
          $('#form')[0].reset();
          $('.form-group').removeClass('has-error');
          $('.help-block').empty();
          $.ajax({
              url : "<?php echo site_url('Discount/edit/')?>" + id,
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
                console.log(data.list_pro);
                $('#id_product').html(data.list_pro);

                  swal.close();
                  $('[name="id"]').val(data.dt.id);
                  $('[name="id_product"]').val(data.dt.id_product);
                  $('[name="satuan"]').val(data.dt.satuan);
                  if(data.dt.satuan == '%')
                  {
                    $('#discount_persen').show();
                    $('#discount_harga').hide();
                    $('#discount_persen').val(data.dt.discount);
                  }
                  else
                  {
                    $('#discount_harga').show();
                    $('#discount_persen').hide();
                    $('#discount_harga').val(number_format(data.dt.discount,0,',',','));
                  }
                  
                  $('#modal_form').modal('show');
                  $('.help-block').empty();
                  $('.modal-title').text('Sunting Data Discount');
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
                  "url": "<?php echo base_url('Discount/read')?>",
                  "type": "POST"
              },
              stateSave: true,
              "columnDefs": [
                  { 
                      "targets": [ 4 ],
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
              url:"<?= base_url('Discount/delete')?>",  
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
            url = "<?php echo base_url('Discount/create')?>";
            isi_title = 'Tambah Discount';
            isi_text = 'Anda Berhasil Menambah Data Discount';
        } else {
            url = "<?php echo base_url('Discount/update')?>";
            isi_title = 'Update Discount';
            isi_text = 'Anda Berhasil Mengupdate Data Discount';
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

                    if(save_method == 'create') {
                      $('#id_product').html(data.list_pro);
                    }
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
                          <label for="id_product" class="control-label">Produk</label>
                          <select name="id_product" id="id_product" class="form-control" required>
                              <option selected disabled hidden>--PILIH--</option>
                            <?php foreach($produk_dis as $row) { ?>
                              <option value="<?php echo $row->id ?>"><?php echo $row->nama_product ?></option>
                            <?php } ?>
                          </select>
                          <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                          <label for="satuan" class="control-label">Satuan</label>
                          <select name="satuan" id="satuan" class="form-control" required>
                            <option selected disabled hidden>--PILIH--</option>
                            <option value="%">Persen</option>
                            <option value="Harga">Harga</option>
                          </select>
                          <span class="help-block"></span>
                        </div>
                        <div class="form-group" id="input-discount">
                          <label>Nilai Discount</label>
                          <input type="text" id="discount_persen" class="form-control" required name="discount_persen">
                          <input type="text" id="discount_harga" class="form-control" required name="discount_harga">
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