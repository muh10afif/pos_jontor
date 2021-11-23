<style>
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
                        <h3 id="judul" class="font-weight-blod"><b><i class="fa fa-file-alt mr-3"></i>Report Stok</b></h3>
                    </div>
                    <form action="<?= base_url('bahan/download_file') ?>" method="post">
                        <input type="hidden" id="aksi" name="jns">
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <div class="col-md-8">
                                    <div class="input-daterange input-group" id="date-range-2">
                                        <input type="text" class="form-control date" name="tgl_awal" id="start" placeholder="Awal Periode" readonly/>
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-primary b-0 text-white">s / d</span>
                                        </div>
                                        <input type="text" class="form-control date" name="tgl_akhir" id="end" placeholder="Akhir Periode" readonly/>
                                    </div>
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
                                        <button type="button" class="btn btn-primary mr-2" id="tampilkan">Tampilkan</button>
                                        <button type="button" class="btn btn-warning" id="reset_filter">Reset</button>
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
                    <div class="card-body">
                        <table class="table w-100 display pb-30 table-bordered table-striped table-hover" id="tabel_report" width="100%">
                            <thead class="text-center thead-light">
                                <tr>
                                    <th class="font-weight-bold" width="5%">No.</th>
                                    <th class="font-weight-bold">Nama Product</th>
                                    <th class="font-weight-bold">Stok</th>
                                    <th class="font-weight-bold" width="10%">Aksi</th>
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

    <!-- Modal -->
    <div class="modal fade" id="modal_detail" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" id="detail">
                <div class="modal-header" style="background-color: #ffbf00;">
                    <h4 class="modal-title font-weight-bold" id="judul_detail">Stok Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="mr-2 text-dark">&times;</span>
                    </button>
                </div>
                <div class="modal-body table-responsive">
                    <table style="display: inline-table;" class="table w-100 pb-30 table-bordered table-hover" id="table_detail" width="100%">
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
                        <tfoot class="text-center thead-light">
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
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="id_stok">

<script>
    $(document).ready(function () {

        $('body').tooltip({selector: '[data-toggle="tooltip"]'});

        // 06-08-2020
        
        $('.date').datepicker({

          "format": "dd-MM-yyyy",
          "todayHighlight": true,
          "autoclose": true,
          "clearBtn": true

        });

        // $('.table_detail').DataTable();

        // detail stok 
        $('#tabel_report').on('click', '.detail-stok', function () {

            var id_stok     = $(this).data('id');
            var tgl_awal    = $(this).attr('tgl_awal');
            var tgl_akhir   = $(this).attr('tgl_akhir');
            var nm_product  = $(this).attr('nm_product');

            $('#id_stok').val(id_stok);

            $('#judul_detail').text("Stok Product "+nm_product);
            $('#modal_detail').modal('show');
            tabel_detail.ajax.reload(null, false);

        })

        // menampilkan tabel detail stok
        var tabel_detail = $('#table_detail').DataTable({

            "processing"        : true,
            "serverSide"        : true,
            "order"             : [],
            "ajax"              : {
                "url"   : "tampil_detail_stok",
                "type"  : "POST",
                "data"  : function (data) {
                    data.tanggal_awal   = $('#start').val();
                    data.tanggal_akhir  = $('#end').val();
                    data.id_stok        = $('#id_stok').val();
                }
            },
            "columnDefs"        : [{
                "targets"   : [0],
                "orderable" : false
            }, {
                'targets'   : [0,1,2,3,4],
                'className' : 'text-center',
            }],

            "aLengthMenu": [
                [10, 25, 50, 100, 200],
                [10, 25, 50, 100, 200]
            ],
            // "iDisplayLength": -1,
            "pageLength": 50,

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

        // menampilkan list stok
        var tabel_report = $('#tabel_report').DataTable({
            "processing"        : true,
            "serverSide"        : true,
            "order"             : [],
            "ajax"              : {
                "url"   : "tampil_report_stok",
                "type"  : "POST",
                "data"  : function (data) {
                    data.tanggal_awal   = $('#start').val();
                    data.tanggal_akhir  = $('#end').val();
                }
            },
            "columnDefs"        : [{
                "targets"   : [0,3],
                "orderable" : false
            }, {
                'targets'   : [0,2,3],
                'className' : 'text-center',
            }]

        })

        // 06-08-2020
        // aksi filter data
        $('#tampilkan').click(function () {
            tabel_report.ajax.reload(null, false);                   
        })

        // aksi reset data filter
        $('#reset_filter').click(function () {
            $('#start').datepicker('setDate', null);
            $('#end').datepicker('setDate', null);
          
            tabel_report.ajax.reload(null, false);    
        })

        $('button[name="export"]').on('click', function () {
            var jns = $(this).attr('data');

            $('#aksi').val(jns);
        })

    })
</script>