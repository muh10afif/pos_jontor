<!-- first row starts here -->
<style>
      body {
        --table-width: 100%; /* Or any value, this will change dinamically */
      }
      .tabel-list, tbody {
        display:block;
        max-height:550px;
        overflow-y:auto;
        overflow-x: hidden;
      }
      .tabel-list, thead, tbody tr {
        display:table;
        width: var(--table-width);
        table-layout:fixed;
      }
      #tabel-bawah, thead, tbody, tr, td {
        border-top: none !important;
      }
      .input {
          height: 10px !important;
          border-radius: 10px;
          border-color: #f2a654;
      }
      .input2 {
          height: 10px !important;
          border-radius: 10px;
          border-color: #f2a654;
      }
      .input3 {
          border-radius: 10px;
          border-color: blue;
      }
      /* .fixed {
        position: fixed;
        width: 50%;
        margin-left: 67%;
        margin-top: -2px;
      } */

      /* let's animate this */
    @keyframes bounce {
        0%{transform: scale(1);}
        50%{transform: scale(1.1);}
        100%{transform: scale(1);}
    }

    .pulse.active {
        animation: bounce 0.3s ease-out 1;
    }

    .tabel-list td {
        text-overflow:ellipsis;
        overflow:hidden;
        white-space:pre-wrap;
    }

</style>
<style>
    
</style>
<style type="text/css">
    .controlgroup-textinput{
        padding-top: .22em;
        padding-bottom: .22em;
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

</style>

<div class="row mt-3 ml-2 mr-2">
    <div class="col-xl-7 stretch-card grid-margin">
        <div class="card shadow" style="height: 55rem; margin-bottom: -20px;">
            <div class="card-body" style="overflow-y: scroll;">

            <nav class="mb-3">
                <div class="nav nav-tabs" id="nav-tab" role="tablist" style="border: 0;">
                    <?php $i = 0; foreach ($kategori as $k) : ?>
                        
                        <a class="nav-item nav-link mr-3 <?= ($i == 0) ? 'active' : '' ?> font-weight-bold shadow" style="border-radius: 7px;" id="nav-home-tab<?= $k['id'] ?>" data-toggle="tab" href="#nav-home-<?= $k['id'] ?>" role="tab" aria-controls="nav-home<?= $k['id'] ?>" aria-selected="true"><h3 class="mt-1"><?= $k['kategori'] ?></h3></a>

                    <?php $i++; endforeach; ?>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <?php $c = 0; foreach ($kategori as $a) : ?>

                    <?php 

                        if ($this->session->userdata('id_role') == 2 ) {
                            $id_u = $this->session->userdata('id_user');
                        } else {
                            $id_u = $this->session->userdata('id_owner');
                        }

                        $kat = $this->transaksi->get_product($a['id'],$id_u)->result_array();
                        
                    ?>
                        <div class="tab-pane fade show <?= ($c == 0) ? 'active' : '' ?>" id="nav-home-<?= $a['id'] ?>" role="tabpanel" aria-labelledby="nav-home-tab<?= $a['id'] ?>">
                            <div class="row">

                                <div class="col-xl-12 mt-3">
                                    <input type="text" class="input3 form-control search" autocomplete="off" placeholder="Cari..">
                                </div>
                                
                                <?php $j = 0; $k = 0; foreach ($kat as $t) : ?>
                                    
                                    <?php 
                                        $warna = ['primary', 'danger', 'warning', 'success'];
                                        $n = random_element($warna) ;

                                        if ($k <= 3) {
                                            $wr = $k;
                                        } else {
                                            $wr = $k % 4;
                                        }

                                        $vv = word_limiter($t['nama_product'], 3);

                                        $cc = strpos($vv, "...");
                                        $dd = strlen($vv);
                                        
                                        // if (character_limiter($t['nama_product'], 13)) {
                                        //     $wc = "tooltip";
                                        // } else {
                                        //     $wc = "";
                                        // }

                                        $string = $t['nama_product'];

                                        if (strlen($string) > 18) {
                                            $trimstring = substr($string, 0, 18). '...';
                                            $wc = "tooltip";
                                        } else {
                                            $trimstring = $string;
                                            $wc = "";
                                        }

                                        $cr_dis = $this->transaksi->cari_nilai_diskon($t['id']);

                                        $stk = $this->transaksi->cari_data('mst_stok', ['id_product' => $t['id']])->row_array();

                                        if ($stk['stok'] == null) {
                                            $stok = 0;
                                        } else {
                                            $stok = $stk['stok'];
                                        }
                                        
                                    ?>

                                    <!-- pointer-events: none; -->
                                    
                                    <div class="col-md-4 mt-3 menu-card" id="menu-<?= $t['id'] ?>">
                                        <div class="card text-white bg-<?= $warna[$wr] ?> text-center nm_product shadow card-hover pulse" style="height: 10rem; cursor: pointer; border-radius: 20px;" data-id="<?= $t['id'] ?>" nama-produk="<?= $t['nama_product'] ?>" harga="<?= $t['harga'] ?>" diskon="<?= $cr_dis ?>" satuan="<?= $t['satuan'] ?>" stok="<?= $stk['stok'] ?>" kategori="<?= $a['kategori'] ?>"  data-toggle="<?= $wc ?>" data-placement="top" title="<?= $t['nama_product'] ?>">
                                            <div class="card-body">
                                                <h3 class="nama-product" hidden data-id="<?= $t['id'] ?>"><?= $t['nama_product'] ?></h3>
                                                <h3 class="text-white mb-2" ><?= $trimstring ?></h3>
                                                <h3 class="card-text">Rp. <?= number_format($t['harga'],0,'.','.') ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                <?php $j++; $k++; endforeach; ?>
                            </div>
                            
                        </div>

                <?php $c++; endforeach; ?>
            </div>
            </div>
        </div>
    </div>
    <div class="col-xl-5 stretch-card grid-margin f_list">
        <div class="card shadow" style="height: 55rem; margin-bottom: -20px;">
            <div class="">
            <!-- <h4 class="text-title mb-0 font-weight-bold">List Pesanan</h4> -->
                <input type="hidden" name="id_transaksi" id="id_transaksi">
                <input type="hidden" name="id_d_tr" id="id_d_tr">
            </div>
            <div class="card-body m-0 p-0">
            <div class="" style="margin-top: -2px;">
                <table class="table tabel-list table-borderless table-striped" width="100%">
                    <thead class="text-center" style="background-color: #ffbf00;">
                        <th class="font-weight-bold" style="color: black;">Product</th>
                        <th class="font-weight-bold" style="color: black;">Harga</th>
                        <th class="font-weight-bold" style="color: black;">Qty</th>
                        <th class="font-weight-bold" style="color: black;">Diskon</th>
                        <th class="font-weight-bold text-center" style="color: black;">Total</th>
                        <th class="font-weight-bold" style="color: black;">Aksi</th>
                    </thead>
                    <tbody>

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
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_ubah_list" role="dialog" aria-labelledby="exampleModalCenterTitle2" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered w-50" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #faa307;">
        <h5 class="modal-title font-weight-bold text-white judul">Product</h5>
        <button type="button" class="close p-3" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="p-2 text-white">&times;</span>
        </button>
      </div>
      <form id="form_list" autocomplete="off">
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="id_d_tr" id="id_d_tr2">
            <input type="hidden" name="harga" id="harga">
            <input type="hidden" name="diskon" id="diskon">
            <input type="hidden" name="satuan" id="satuan">
            <input type="hidden" name="diskon_pro" id="diskon_pro">
            <div class="modal-body">
                <div class="col-md-8 offset-md-2 pt-2">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label mt-0">QTY</label>
                        <div class="col-sm-8 easy-get2" data-id="jumlah">
                            <input type="hidden" id="jumlah_lama" name="jumlah_lama" class="form-control">
                            <input type="text" class="form-control angka easy-put2"  style="font-size: 14px;" name="jumlah" id="jumlah" placeholder="Qty">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label mt-0">Diskon</label>
                        <div class="col-sm-8">
                            <div class="easy-get3" data-id="nilai_diskon">

                                <input type="hidden" id="nilai_diskon_lama" name="nilai_diskon_lama" class="form-control">

                                <div class="input-group mb-3">
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">%</span>
                                    </div>
                                    <input type="text" class="form-control angka easy-put3 text-left"  style="font-size: 14px;" id="diskon_diskon" placeholder="Diskon">
                                </div>
                                
                            </div>
                            
                            <input type="text" class="mt-2 form-control text-left"  style="font-size: 14px;" name="nilai_diskon" id="nilai_diskon" value="0" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 offset-md-4">
                            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Batal</button>
                            <button type="button" style="background-color: #faa307;" class="btn text-white" id="simpan_produk">Simpan</button>
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_selesai" role="dialog" aria-labelledby="exampleModalCenterTitle2" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered w-75" role="document">
    <div class="modal-content">
        <div class="modal-header" style="background-color: #faa307;">
            <h5 class="modal-title font-weight-bold text-white judul">Transaksi Berhasil</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="mr-2 text-white">&times;</span>
            </button>
        </div>
        <div class="modal-body row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card text-center shadow pulse c_nota" style="cursor: pointer; border-radius: 10px;">
                    <div class="card-body text-dark">
                        <h4 class="text-primary mb-2"><i class="mdi mdi-printer mdi-48px"></i></h4>
                        <h5 class="card-text">Cetak Nota</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4" hidden>
                <div class="card text-center shadow kirim-email" style="cursor: pointer; border-radius: 10px;">
                    <div class="card-body">
                        <h4 class="text-success mb-2"><i class="mdi mdi-email mdi-48px"></i></h4>
                        <h5 class="card-text">Kirim <br> E-mail</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center shadow pulse" data-dismiss="modal" style="cursor: pointer; border-radius: 10px;">
                    <div class="card-body">
                        <h4 class="text-warning mb-2"><i class="mdi mdi-arrow-right-box mdi-48px"></i></h4>
                        <h5 class="card-text">Kembali Transaksi</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_email" role="dialog" aria-labelledby="exampleModalCenterTitle2" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered w-75" role="document">
    <div class="modal-content">
        <div class="modal-header" style="background-color: #faa307;">
            <h5 class="modal-title font-weight-bold text-white judul">Kirim E-mail</h5>
            <button type="button" class="close keluar-email" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="mr-2 text-white">&times;</span>
            </button>
        </div>
        <form id="form-email">
        <div class="modal-body row">
            <div class="col-md-12 mt-3">
                <input type="hidden" class="id_tr">
                <div class="form-group row">
                    <label for="nama_kategori" class="col-sm-3 col-form-label text-right">Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" style="font-size: 14px;" name="nm_email" id="nm_email" placeholder="Masukkan Email">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-md-4 offset-md-8 text-right">
                <button class="btn btn-success" type="button" id="simpan_email">Kirim</button>
            </div>
        </div>
        </form>
    </div>
  </div>
</div>

<input type="hidden" id="stok">
<input type="hidden" id="id_product_t">

<script>
    $(document).ready(function () {

        // 23-09-2020

        $('[data-toggle="tooltip"]').tooltip()

        // animasi tab
        $('a[data-toggle="tab"]').on('hide.bs.tab', function(e) {
            var $old_tab = $($(e.target).attr("href"));
            var $new_tab = $($(e.relatedTarget).attr("href"));

            if ($new_tab.index() < $old_tab.index()) {
                $old_tab.css('position', 'relative').css("right", "0").show();
                $old_tab.animate({
                "right": "-100%"
                }, 300, function() {
                $old_tab.css("right", 0).removeAttr("style");
                });
                $('.search').val("");
                $('.menu-card').show();
            } else {
                $old_tab.css('position', 'relative').css("left", "0").show();
                $old_tab.animate({
                "left": "-100%"
                }, 300, function() {
                $old_tab.css("left", 0).removeAttr("style");
                });
                $('.search').val("");
                $('.menu-card').show();
            }
        });

        // animasi tab
        $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            var $new_tab = $($(e.target).attr("href"));
            var $old_tab = $($(e.relatedTarget).attr("href"));

            if ($new_tab.index() > $old_tab.index()) {
                $new_tab.css('position', 'relative').css("right", "-2500px");
                $new_tab.animate({
                "right": "0"
                }, 500);
                $('.search').val("");
                $('.menu-card').show();
            } else {
                $new_tab.css('position', 'relative').css("left", "-2500px");
                $new_tab.animate({
                "left": "0"
                }, 500);
                $('.search').val("");
                $('.menu-card').show();
            }
        });

        // 23-09-2020

        // Live Search
        $('.search').keyup(function(event) {
            var filter = $(this).val();
            $('.nama-product').each(function() {
                var id = $(this).data('id');
                if($(this).text().search(new RegExp(filter, 'i')) < 0) {
                $('#menu-'+id).hide();
                }
                else
                {
                    $('#menu-'+id).show();
                }
            });
        });

        // 23-09-2020
        $(".pulse").click(function(){
            $(this).addClass("active").delay(300).queue(function(next){
                $(this).removeClass("active");
                next();
                $('.search').val("");
                $('.menu-card').show();
            });
        });

        // 25-09-2020
        // tabel list
        var tabel_list = $('.tabel-list2').DataTable({
            "processing"        : true,
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

        // 25-09-2020
        function separator(kalimat) {
            return kalimat.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // 25-09-2020
        $('.nm_product').on('click', function () {
            
            var id_product  = $(this).data('id');
            var harga       = $(this).attr('harga');
            var diskon      = $(this).attr('diskon');
            var nm_produk   = $(this).attr('nama-produk');
            var satuan      = $(this).attr('satuan');
            var stok        = $(this).attr('stok');
            var kategori    = $(this).attr('kategori');

            $('#btn_transaksi').removeAttr('hidden');

            var total = (harga * 1) - (diskon * 1);

            var tbody = "";
            var qty

            if ($('.id'+id_product).text() == nm_produk) {
                

                var q = $('#qty'+id_product).text();

                var y = parseInt(q) + 1;
                $('#qty'+id_product).text(y);

                var dis = (diskon * y);

                var total = (harga * y) - dis;
                $('#total'+id_product).text(separator(total));
                $('#diskon'+id_product).text(separator(dis));
                
            } 

            if ($('.id'+id_product).text() != nm_produk) {
                tbody = 
                "<tr class='list_"+id_product+" list'>"+
                    "<td><div class='id"+id_product+" nama_list' kategori='"+kategori+"' data-id='"+id_product+"'>"+nm_produk+"</div></td>"+
                    "<td class='text-right'><div class='harga_list' harga='"+harga+"' id='harga"+id_product+"'>"+separator(harga)+"</div></td>"+
                    "<td class='text-center'><div><label class='badge badge-danger qty_list' id='qty"+id_product+"'>1</label></div></td>"+
                    "<td class='text-left'><div class='diskon_list' id='diskon"+id_product+"'>"+separator(diskon)+"</div></td>"+
                    "<td class='text-right'><div class='subtotal_list' id='total"+id_product+"'>"+separator(total)+"</div></td>"+
                    "<td class='text-center'><div><label style='cursor:pointer; margin-bottom: 5px; margin-left: 2px; margin-right: 2px' class='badge badge-success ubah-list text-white' data-toggle='tooltip' data-placement='top' title='Edit' product='"+nm_produk+"' data-id='"+id_product+"' harga='"+harga+"' diskon='"+diskon+"' satuan='"+satuan+"' stok='"+stok+"'><i class='fa fa-pen-alt'></i></label><label class='badge badge-danger text-white hapus-list' style='cursor:pointer' data-id='"+id_product+"'><i class='fa fa-trash-alt'></i></label></div></td>"+
                "</tr>";
            }

            $('.tabel-list').append(tbody);

            // untuk total total

                var nilai_subtotal = 0;
                $('.subtotal_list').each(function(){
                    var total   = $(this).text().replace(".", '');
                    nilai_subtotal  += parseInt(total);
                });

                var nilai_diskon = 0;
                $('.diskon_list').each(function(){
                    var diskon   = $(this).text().replace(".", '');
                    nilai_diskon  += parseInt(diskon);
                });

                $('#total').text("Rp. "+separator(nilai_subtotal));
                $('#diskon').text("Rp. "+separator(nilai_diskon));

                // tunai
                var total   = $('#total').text().replace("Rp. ","").replace(".", '');
                var tunai   = $('#tunai').val().replace(".", '');

                $('#kembali').text("Rp. "+separator(tunai - total));

            // akhir untuk total total

            if ((tunai - total) < 0) {
                $('#btn_transaksi').attr('disabled', true);
            } else {
                $('#btn_transaksi').attr('disabled', false);
            }

        })

        // 25-09-2020
        $('.tabel-list').on('click', '.hapus-list', function () {
            
            var id = $(this).data('id');

            $(".list_"+id).remove();

            var nilai_subtotal = 0;
            $('.subtotal_list').each(function(){
                var total   = $(this).text().replace(".", '');
                nilai_subtotal  += parseInt(total);
            });

            var nilai_diskon = 0;
            $('.diskon_list').each(function(){
                var diskon   = $(this).text().replace(".", '');
                nilai_diskon  += parseInt(diskon);
            });

            $('#total').text("Rp. "+separator(nilai_subtotal));
            $('#diskon').text("Rp. "+separator(nilai_diskon));

            // tunai
            var total   = $('#total').text().replace("Rp. ","").replace(".", '');
            var tunai   = $('#tunai').val().replace(".", '');

            if (total == '0') {
                $('#tunai').val(0);
                $('#kembali').text("Rp. 0");
                
                $('#btn_transaksi').attr('hidden', true);
            } else {
                $('#kembali').text("Rp. "+separator(tunai - total));
            }

            if ((tunai - total) < 0) {
                $('#btn_transaksi').attr('disabled', true);
            } else {
                $('#btn_transaksi').attr('disabled', false);
            }

        })

        // 25-09-2020
        $('.tabel-list').on('click', '.ubah-list', function () { 

            var id              = $(this).data('id');
            var product         = $(this).attr('product');
            var harga           = $(this).attr('harga');
            var diskon_pro      = $(this).attr('diskon');
            var satuan          = $(this).attr('satuan');
            var stok            = $(this).attr('stok');

            var pot_harga       = $("#potongan_harga").val().replace(".","");

            $('#diskon_pro').val(diskon_pro);
            $('#harga').val(harga - pot_harga);
            $('#satuan').val(satuan);
            $('#stok').val(stok);

            var nominal_diskon  = $('#diskon'+id+'').text().replace("Rp. ", '');
            var diskon          = parseFloat(nominal_diskon.split('.').join(''));
            var jumlah          = $('#qty'+id).text();

            $('.judul').text("Product "+product);
            $('#id').val(id);
            $('#jumlah').val(jumlah);
            $('#nilai_diskon').val(separator(diskon));
            $('#modal_ubah_list').modal('show');
            
        })

        // 25-09-2020
        $('#simpan_produk').on('click', function () {

            var id_product  = $('#id').val();
            var qty         = $('#jumlah').val();
            var harga       = $('#harga').val();
            var diskon      = $('#nilai_diskon').val().replace(".", '');

            if (qty == 0) {
                $(".list_"+id_product).remove();
            }

            var y = parseInt(qty);
            $('#qty'+id_product).text(y);

            var dis = (diskon);

            var total = (harga * y) - dis;
            $('#total'+id_product).text(separator(total));
            $('#diskon'+id_product).text(separator(dis));
            
            // untuk total total

                var nilai_subtotal = 0;
                $('.subtotal_list').each(function(){
                    var total   = $(this).text().replace(".", '');
                    nilai_subtotal  += parseInt(total);
                });

                var nilai_diskon = 0;
                $('.diskon_list').each(function(){
                    var diskon   = $(this).text().replace(".", '');
                    nilai_diskon  += parseInt(diskon);
                });

                $('#total').text("Rp. "+separator(nilai_subtotal));
                $('#diskon').text("Rp. "+separator(nilai_diskon));

                // tunai
                var total   = $('#total').text().replace("Rp. ","").replace(".", '');
                var tunai   = $('#tunai').val().replace(".", '');

                $('#kembali').text("Rp. "+separator(tunai - total));

            // akhir untuk total total

            if ((tunai - total) < 0) {
                $('#btn_transaksi').attr('disabled', true);
            } else {
                $('#btn_transaksi').attr('disabled', false);
            }

            $('#modal_ubah_list').modal('hide');

        })

        // 26-09-2020
        $('#btn_transaksi').on('click', function () {

            var nomor_meja      = $('#nomor_meja').val();
            var pot_harga       = $('#potongan_harga').val().replace(".", '');
            var total_diskon    = $('#diskon').text().replace("Rp. ", '').replace(".",'');
            var total_harga     = $('#total').text().replace("Rp. ", '').replace(".",'');
            var tunai           = $('#tunai').val().replace(".", '');
            var kembalian       = $('#kembali').text().replace("Rp. ", '').replace(".",'');

            var kategori        = [];
            var id_produk       = [];
            var nm_produk       = [];
            $('.nama_list').each(function() { 
                kategori.push($(this).attr('kategori')); 
                id_produk.push($(this).data('id'));
                nm_produk.push($(this).text());
            });

            // remove duplicate array
            var nm_kategori = [];
            $.each(kategori, function(i, el){
                if($.inArray(el, nm_kategori) === -1) nm_kategori.push(el);
            });

            var harga_list  = [];
            $('.harga_list').each(function() { 
                harga_list.push($(this).text()); 
            });

            var qty_list    = [];
            $('.qty_list').each(function () {
                qty_list.push($(this).text());
            });

            var diskon_list = [];
            $('.diskon_list').each(function () {
                diskon_list.push($(this).text());
            })

            var subtotal_list   = [];
            $('.subtotal_list').each(function () {
                subtotal_list.push($(this).text());
            });

            $('#diskon').text("Rp. 0");
            $('#total').text("Rp. 0");
            $('#tunai').val(0);
            $('#nomor_meja').val(0);
            $('#kembali').text("Rp. 0");
            $('#btn_transaksi').attr('hidden', true);

            $('.list').remove();

            $.ajax({
                url     : "Transaksi/simpan_list_transaksi",
                method  : "POST",
                data    : {tunai:tunai, nomor_meja:nomor_meja, total_harga:total_harga, total_diskon:total_diskon, nm_produk:nm_produk, harga_list:harga_list, qty_list:qty_list, diskon_list:diskon_list, subtotal_list:subtotal_list, pot_harga:pot_harga, kembalian:kembalian, kategori:kategori, id_produk:id_produk, nm_kategori:nm_kategori},
                dataType: "JSON",
                success : function (data) {
                    
                    
                    
                }
            })

        })
        
    })
</script>