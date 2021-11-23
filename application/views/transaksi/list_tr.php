<div class="card shadow" style="height: 55rem; margin-bottom: -20px;">
    <div class="">
    <!-- <h4 class="text-title mb-0 font-weight-bold">List Pesanan</h4> -->
        <input type="hidden" name="id_transaksi" id="id_transaksi">
        <input type="hidden" name="id_d_tr" id="id_d_tr">
    </div>
    <div class="card-body m-0 p-0">
    <div class="" style="margin-top: -7px;">
        <table class="table tabel-list table-borderless table-striped" width="100%">
            <thead class="text-center" style="background-color: #fcb93f;">
                <th class="font-weight-bold" style="color: black;">Product</th>
                <th class="font-weight-bold" style="color: black;">Harga</th>
                <th class="font-weight-bold" style="color: black;">Qty</th>
                <th class="font-weight-bold" style="color: black;">Diskon</th>
                <th class="font-weight-bold text-center" style="color: black;">Total</th>
                <th class="font-weight-bold" style="color: black;">Aksi</th>
            </thead>
            <tbody>
                <?php foreach ($list as $key => $t) : ?>
                    <tr>
                        <td><?= $t['nama_produk'] ?></td>
                        <td><?= $t['harga'] ?></td>
                        <td><?= $t['qty'] ?></td>
                        <td><?= $t['diskon'] ?></td>
                        <td><?= $t['total'] ?></td>
                        <td></td>
                    </tr> 
                <?php endforeach ?>
                
            
            </tbody>
        </table>
        
    </div>
    </div>
    <div class="card-header">
        <table class="table table-no-border mb-2" id="tabel-bawah">
            <tbody>
            <tr class="font-weight-bold" hidden>
                <td style="font-size: 18px;">Nomor Meja</td>
                <td class="text-right">
                <div class="row">
                    <div class="col-md-10 offset-md-2 easy-get" data-id="nomor_meja">
                        <input type="text" style="font-size: 18px;"  class="form-control input text-right easy-put" name="nomor_meja" id="nomor_meja" value="0">
                    </div>
                </div></td>
            </tr>
            <tr class="font-weight-bold" hidden>
                <td style="font-size: 18px;">Potongan Harga</td>
                <td class="text-right">
                <div class="row">
                    <div class="col-md-10 offset-md-2" data-id="potongan_harga">
                        <input type="text" style="font-size: 18px;"  class="form-control input divide text-right" name="potongan_harga" id="potongan_harga" value="0">
                    </div>
                </div></td>
            </tr>
            <tr class="font-weight-bold">
                <td style="font-size: 18px;">Diskon</td>
                <td class="text-right" style="font-size: 18px;"><span id="diskon">Rp. 0</span></td>
            </tr>
            <tr class="bg-warning text-white font-weight-bold">
                <td style="font-size: 18px;">Total</td>
                <td class="text-right" style="font-size: 18px;"><span id="total">Rp. 0</span></td>
            </tr>
            <tr class="font-weight-bold">
                <td style="font-size: 18px;">Tunai</td>
                <td class="text-right">
                <div class="row">
                    <div class="col-md-10 offset-md-2 easy-get4" data-id="tunai">
                        <input type="text" style="font-size: 18px;"  class="form-control input text-right easy-put4" name="tunai" id="tunai" value="0" autocomplete="off"> 
                    </div>
                </div></td>
            </tr>
            <tr class="bg-primary text-white font-weight-bold">
                <td style="font-size: 18px;">Kembali</td>
                <td class="text-right" style="font-size: 18px;"><span id="kembali">Rp. 0</span></td>
            </tr>
            </tbody>
        </table>
    </div>
    <button class="btn-block btn btn-lg btn-success btn-fw" style="font-size: 18px;" id="btn_transaksi" hidden>TRANSAKSI</button>
</div>

<script>
    $(document).ready(function () {

        var tabel_list = $('.tabel-list').DataTable({
            "order"             : [],
            "oLanguage" : {
                "sProcessing"   : "Harap Tunggu.."
            },
            "language"  : {
                "emptyTable"    : "List Kosong"
            },
            "columnDefs"        : [{
                "targets"   : [0,1,2,3,4,5],
                "orderable" : false
            }, {
                'targets'   : [2,5],
                'className' : 'text-center',
            }, {
                'targets'   : [4],
                'className' : 'text-right'
            }, {
                render: function (data, type, full, meta) {
                    return "<div class='text-wrap width-50'>" + data + "</div>";
                },
                'targets': [0,3,4]
            }],
            "paging"        : false,
            "info"          : false,
            "searching"     : false,

        })
        
    })
</script>