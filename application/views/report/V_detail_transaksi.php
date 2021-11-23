<style>
    .table-sm {
        border-collapse: separate;
        border-spacing: 0.7em;
    }
</style>
<div class="modal-header text-dark" style="background-color: #ffbf00;">
    <h4 class="font-weight-bold" id="my-modal-title" style="color: black;"><i class="fa fa-info mr-3"></i>Detail Transaksi</h4>
    <button class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 table-responsive">
            <table class="table table-hover table-borderless table-sm">
                <tbody>
                <tr>
                    <th scope="row" width="35%">Tanggal</th>
                    <td class="font-weight-bold" width="5%">:</td>
                    <td class="text-right font-weight-bold" ><?= nice_date($trn['created_at'], 'd F Y H:i:s') ?></td>
                </tr>
                <tr>
                    <th scope="row">Kode Transaksi</th>
                    <td class="font-weight-bold" width="5%">:</td>
                    <td class="text-right font-weight-bold" ><?= $trn['kode_transaksi'] ?></td>
                </tr>
                <tr>
                    <th scope="row">Kasir</th>
                    <td class="font-weight-bold" width="5%">:</td>
                    <td class="text-right font-weight-bold" ><?= ($trn['kasir'] == null) ? $this->session->userdata('username') : $trn['kasir'] ?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-12 mt-2 mb-2">
            <div class="progress" style="height: 5px;">
                <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #ffbf00;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="col-md-12 table-responsive mt-2">

            <table class="table table-hover table-borderless table-sm">
                <tbody>
                    <?php $tot_dis = 0; foreach ($kategori as $t): 
                        
                        $det = $this->report->cari_detail_det($t['id'], $trn['id'])->result_array();
                        
                    ?>

                        <tr class="font-weight-bold">
                            <th scope="row" colspan="3"><mark><u><?= $t['kategori'] ?></u></mark></th>
                        </tr>

                        <?php $no=1; foreach ($det as $k): ?>
                            <tr class="font-weight-bold">
                                <th scope="row" colspan="3"><?= $k['nama_product'] ?></th>
                            </tr>
                            <tr>
                                <td align="left"><?= $k['jumlah'] ?> x <?= number_format($k['harga'],0,'.','.') ?></td>
                                <td align="right">(<?= number_format($k['total_discount'],0,'.','.') ?>)</td>
                                <td align="right"><?= number_format($k['subtotal'],0,'.','.') ?></td>
                            </tr>
                        <?php $no++; $tot_dis += $k['total_discount']; endforeach; ?>
                        
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-12 mt-2">
            <div class="progress" style="height: 5px;">
                <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #ffbf00;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="col-md-12 table-responsive mt-3 d-flex justify-content-center">
            <table class="table table-hover table-borderless table-sm">
                <tbody>
                <tr>
                    <th scope="row" width="35%">Total Diskon</th>
                    <td class="font-weight-bold">: Rp.</td>
                    <td class="text-right font-weight-bold"><?= number_format($tot_dis,0,'.','.') ?></td>
                </tr>
                <tr>
                    <th scope="row">Total</th>
                    <td class="font-weight-bold">: Rp.</td>
                    <td class="text-right font-weight-bold"><?= number_format($trn['total_harga'],0,'.','.') ?></td>
                </tr>
                <tr>
                    <th scope="row">Total Bayar</th>
                    <td class="font-weight-bold">: Rp.</td>
                    <td class="text-right font-weight-bold">
                        <?= number_format($trn['tunai'],0,'.','.') ?>
                
                    <!-- <div class="easy-get5" data-id="<?= $trn['id'] ?>" harga="<?= $trn['total_harga'] ?>">
                        <input type="text" style="font-size: 18px;"  class="form-control input text-right easy-put5" name="tunai" id="tunai<?= $trn['id'] ?>" size="1" value="<?php echo $trn['tunai'] ? number_format($trn['tunai'],0,'.','.') : 0 ?>">
                    </div> -->
                    </td>
                </tr>
                <tr>
                    <th scope="row">Kembali</th>
                    <td class="font-weight-bold">: Rp.</td>
                    <td class="text-right font-weight-bold"><span id="kembali<?= $trn['id'] ?>"><?= number_format($trn['tunai'] - $trn['total_harga'],0,'.','.') ?></span></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center">
                <button type="button" class="btn btn-danger btn-md mr-2" data-dismiss="modal"><i class="mdi mdi-close-circle mr-2"></i><span style="font-size: 18px;">Close</span></button>
                <button type="button" class="btn btn-warning btn-md mr-2 kirim-email" data-id="<?= $trn['id'] ?>" hidden><i class="mdi mdi-gmail mr-2"></i><span style="font-size: 18px;">Kirim Email</span></button>
                <button type="button" class="btn btn-primary btn-md mr-2 cetak" data-id="<?= $trn['id'] ?>" potongan_harga="<?= $trn['potongan_harga'] ?>"><i class="mdi mdi-printer mr-2"></i><span style="font-size: 18px;">Cetak</span></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_email<?= $trn['id'] ?>" role="dialog" aria-labelledby="exampleModalCenterTitle2" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered w-75" role="document">
    <div class="modal-content">
        <div class="modal-header" style="background-color: #faa307;">
            <h5 class="modal-title font-weight-bold text-white judul">Kirim E-mail</h5>
            <button type="button" class="close keluar-email" data-id="<?= $trn['id'] ?>"  data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="mr-2 text-white">&times;</span>
            </button>
        </div>
        <form id="form-email<?= $trn['id'] ?>">
        <div class="modal-body row">
            <div class="col-md-12 mt-3">
                <input type="hidden" class="id_tr">
                <div class="form-group row">
                    <label for="nama_kategori" class="col-sm-3 col-form-label text-right">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" style="font-size: 14px;" name="nm_email" id="nm_email<?= $trn['id'] ?>" placeholder="Masukkan Email">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-md-4 offset-md-8 text-right">
                <button class="btn btn-success simpan_email" type="button" data-id="<?= $trn['id'] ?>">Kirim</button>
            </div>
        </div>
        </form>
    </div>
    </div>
</div>

<script>
    // card cetak nota
    $('.cetak').on('click', function () {

    var id_tr       = $(this).data('id');
    var pot_harga   = $(this).attr('potongan_harga');

    console.log(id_tr);

    $.ajax({
        url         : "Transaksi/cetak_nota/"+id_tr+"/"+pot_harga,
        method      : "POST",
        data        : {id_tr:id_tr, pot_harga:pot_harga},
        dataType    : "JSON",
        success     : function (data) {

            
        },
        error       : function(xhr, status, error) {
            // var err = eval("(" + xhr.responseText + ")");
            // alert(err.Message);

            $('#potongan_harga').val(0);
        }

    })

    return false;

    })

    $('.kirim-email').on('click', function () {
        var id = $(this).data('id');

        $('#nm_email'+id).val('');
        $('#modal_email'+id).modal('show');
        $('#detail'+id).modal('hide');
    })

    $('.keluar-email').on('click', function () {
    var id = $(this).data('id');

    $('#modal_email'+id).modal('hide');
    $('#detail'+id).modal('show');
    })

    // button transaksi
    $('.simpan_email').on('click', function () {

    var id_tr  = $(this).data('id');
    var email  = $('#nm_email'+id_tr).val();

    if (email == '') {

        swal({
            title               : "Peringatan",
            text                : 'Email Harap Diisi!',
            buttonsStyling      : false,
            type                : 'warning',
            showConfirmButton   : false,
            timer               : 1000
        });  

        return false;

    } else {

        $.ajax({
            url         : "Transaksi/kirim_email",
            method      : "POST",
            beforeSend  : function () {
                swal({
                    title   : 'Harap Tunggu',
                    html    : 'Memproses Kirim Data',
                    onOpen  : () => {
                        swal.showLoading();
                    }
                })
            },
            data        : {id_tr:id_tr, email:email},
            dataType    : "JSON",
            success     : function (data) {

                if (data.status == 'OK') {

                    swal({
                        title               : "Berhasil",
                        text                : 'Data berhasil dikirim ke '+email,
                        buttonsStyling      : false,
                        confirmButtonClass  : "btn btn-success",
                        type                : 'success',
                        showConfirmButton   : false,
                        timer               : 1500
                    });

                    $('#detail'+id_tr).modal('show');
                    $('#modal_email'+id_tr).modal('hide');
                } 
                
            },
            error       : function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                alert(err.Message);
            }

        })

        return false;

    }

    })

</script>