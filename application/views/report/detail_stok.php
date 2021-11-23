<div class="modal-header" style="background-color: #ffbf00;">
    <h5 class="modal-title font-weight-bold text-white" id="judul_detail">Stok Product</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true" class="mr-2 text-dark">&times;</span>
    </button>
</div>
<div class="modal-body table-responsive">
    <table class="table w-100 display pb-30 table-bordered table-striped table-hover" id="table_detail" width="100%">
        <thead class="text-center thead-light">
            <tr>
                <th class="font-weight-bold" width="5%">No.</th>
                <th class="font-weight-bold">Barang Masuk</th>
                <th class="font-weight-bold">Barang Keluar</th>
                <th class="font-weight-bold">Barang Retur</th>
                <th class="font-weight-bold">Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot class="text-center">
            <tr>
                <th class="font-weight-bold">Total</th>
                <th class="font-weight-bold"></th>
                <th class="font-weight-bold"></th>
                <th class="font-weight-bold"></th>
                <th class="font-weight-bold"></th>
            </tr>
        </tfoot>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
</div>

<input type="hidden" id="start2" value="<?= $tgl_awal ?>">
<input type="hidden" id="end2" value="<?= $tgl_akhir ?>">
<input type="hidden" id="id_stok2" value="<?= $id_stok ?>">

<script>
    $(document).ready(function () {
        
        // menampilkan tabel detail stok
        var tabel_detail = $('#table_detail').DataTable({

            "processing"        : true,
            "serverSide"        : true,
            "order"             : [],
            "ajax"              : {
                "url"   : "tampil_detail_stok",
                "type"  : "POST",
                "data"  : function (data) {
                    data.tanggal_awal   = $('#start2').val();
                    data.tanggal_akhir  = $('#end2').val();
                    data.id_stok        = $('#id_stok2').val();
                }
            },
            "columnDefs"        : [{
                "targets"   : [0],
                "orderable" : false
            }, {
                'targets'   : [0,1,2,3],
                'className' : 'text-center',
            }],

            "aLengthMenu": [
                [10, 25, 50, 100, 200, -1],
                [10, 25, 50, 100, 200, "All"]
            ],
            "iDisplayLength": -1,

            "paging": true,
            "autoWidth": true,
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                nb_cols = api.columns().nodes().length;
                var j = 1;
                while(j < nb_cols){
                    var pageTotal = api
                        .column( j, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return Number(a) + Number(b);
                        }, 0 );
                        
                        // Update footer
                        $( api.column( j ).footer() ).html(pageTotal);
                        
                        j++;
                    } 
                }

        });

    })
</script>