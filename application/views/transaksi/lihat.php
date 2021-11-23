<!-- first row starts here -->
<style>
      body {
        --table-width: 100%; /* Or any value, this will change dinamically */
      }
      #tabel-list, tbody {
        display:block;
        max-height:500px;
        overflow-y:auto;
        overflow-x: hidden;
      }
      #tabel-list, thead, tbody tr {
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

                        $kat = $this->transaksi->cari_data('mst_product', ['id_kategori' => $a['id'], 'status_tampil' => 1, 'id_user' => $id_u], ['nama_product' => 'asc'])->result_array();
                        
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
                                    ?>

                                    <!-- pointer-events: none; -->
                                    
                                    <div class="col-md-4 mt-3 menu-card" id="menu-<?= $t['id'] ?>">
                                        <div class="card text-white bg-<?= $warna[$wr] ?> text-center nm_product shadow card-hover pulse" style="height: 10rem; cursor: pointer; border-radius: 20px;" data-id="<?= $t['id'] ?>" nama-produk="<?= $t['nama_product'] ?>" data-toggle="<?= $wc ?>" data-placement="top" title="<?= $t['nama_product'] ?>">
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
    <div class="col-xl-5 stretch-card grid-margin">
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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class="mr-2 text-white">&times;</span>
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
                        <label class="col-sm-3 col-form-label">QTY</label>
                        <div class="col-sm-5 easy-get2" data-id="jumlah">
                            <input type="hidden" id="jumlah_lama" name="jumlah_lama" class="form-control">
                            <input type="text" class="form-control angka easy-put2 text-center"  style="font-size: 14px;" name="jumlah" id="jumlah" placeholder="Qty">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Diskon</label>
                        <div class="col-sm-8 easy-get3" data-id="nilai_diskon">
                            <input type="hidden" id="nilai_diskon_lama" name="nilai_diskon_lama" class="form-control">

                            <div class="input-group mb-3">
                              <input type="text" class="form-control angka easy-put3 text-right"  style="font-size: 14px;" id="diskon_diskon" placeholder="Diskon">
                              <div class="input-group-append">
                                <span class="input-group-text" id="basic-addon2">%</span>
                              </div>
                            </div>
                            
                            <input type="text" class=" mt-2 form-control angka text-right"  style="font-size: 14px;" name="nilai_diskon" id="nilai_diskon" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" style="background-color: #faa307;" class="btn text-white" id="simpan_produk">Simpan</button>
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

        $('[data-toggle="tooltip"]').tooltip()

        // 14-08-2020
        $('#potongan_harga').keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });

        function removeA(arr) {
            var what, a = arguments, L = a.length, ax;
            while (L > 1 && arr.length) {
                what = a[--L];
                while ((ax= arr.indexOf(what)) !== -1) {
                    arr.splice(ax, 1);
                }
            }
            return arr;
        }

        $('#potongan_harga').on('keyup', function () {

            var pot_harga = $(this).val();

            var harga = [];

            $('.harga_awal').each(function(){

                harga.push($(this).text().replace("Rp. ","").split(".").join(""));
                
            });

            var a   = 0;
            var tot = 0;

            var id_pro_b = [];

            $.each(id_pdc, function(i, el){
                if($.inArray(el, id_pro_b) === -1) id_pro_b.push(el);
            });

            id_pro_b.forEach(function (h) {

                var dis     = $("#diskon"+id_pro_b[a]).text().replace("Rp. ","").split(".").join("");
                var hrg     = harga[a] - pot_harga;
                var jml     = $("#jumlah"+id_pro_b[a]).text();

                $("#harga"+id_pro_b[a]).text("Rp. "+separator(hrg));
                $("#total"+id_pro_b[a]).text("Rp. "+separator((hrg * jml) - dis));

                // hitung total
                tot += ((hrg * jml) - dis);

            a++;
            })

            console.log(id_pro_b);

            // isi total
            $("#total").text("Rp. "+separator(tot));
            
        })

        // 07-08-2020
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

        $(".pulse").click(function(){
            $(this).addClass("active").delay(300).queue(function(next){
                $(this).removeClass("active");
                next();
                $('.search').val("");
                $('.menu-card').show();
            });
        });

        // $( ".pinpad" ).pinpad( {
        //     digitOnly: true
        // } );

        $('#btn_transaksi').attr('hidden', true);
        
        // menampilkan list bpr
        var tabel_list = $('.tabel-list').DataTable({
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

        function separator(kalimat) {
            return kalimat.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        var id_pdc = [];

        $('.nm_product').on('click', function () {
            
            var id_product = $(this).data('id');

            id_pdc.push(id_product);

            $.ajax({
                url     : "Transaksi/add_row",
                type    : "POST",
                data    : {id_product:id_product},
                dataType: "JSON",
                success : function (data) {
                    
                    var counter         = 1;
                    var id_product      = data.id_product;
                    var nama_product    = data.nama_product;
                    var dis_produk      = data.dis_produk;
                    var stk             = data.stok;

                    var pot_harga       = $('#potongan_harga').val().replace(".","");

                    // untuk menambahkan row
                    if($('.'+id_product+'').text() == nama_product)
                    {

                        if (stk != null) {
                            if(parseInt($('#jumlah'+data.id_product+'').text()) >= $('#jumlah'+data.id_product+'').data('stok'))
                            {
                                swal({
                                        title               : "Peringatan",
                                        text                : 'Jumlah tidak boleh Melebihi Stok',
                                        buttonsStyling      : false,
                                        type                : 'warning',
                                        showConfirmButton   : false,
                                        timer               : 1000
                                    });  
                                    return false;
                            }
                            else
                            {

                                $('#jumlah'+id_product+'').text(parseFloat($('#jumlah'+id_product+'').text()) + 1);

                                var nominal     = $('#harga'+data.id_product+'').text().replace("Rp. ", '');
                                var harga       = parseFloat(nominal.split('.').join(''));

                                var diskon      = $('#diskon'+data.id_product+'').text().replace("Rp. ", '');
                                var diskon1     = parseFloat(diskon.split('.').join(''));
                                var jumlah      = parseFloat($('#jumlah'+id_product+'').text());
                                var subtotal    = (harga*jumlah) - (dis_produk * jumlah);

                                $('#total'+id_product+'').text('Rp. '+separator(subtotal));
                                $('#diskon'+id_product+'').text('Rp. '+separator(dis_produk * jumlah));

                            }

                        } else {

                            $('#jumlah'+id_product+'').text(parseFloat($('#jumlah'+id_product+'').text()) + 1);

                            var nominal     = $('#harga'+data.id_product+'').text().replace("Rp. ", '');
                            var harga       = parseFloat(nominal.split('.').join(''));

                            var diskon      = $('#diskon'+data.id_product+'').text().replace("Rp. ", '');
                            var diskon1     = parseFloat(diskon.split('.').join(''));
                            var jumlah      = parseFloat($('#jumlah'+id_product+'').text());
                            var subtotal    = (harga*jumlah) - (dis_produk * jumlah);

                            $('#total'+id_product+'').text('Rp. '+separator(subtotal));
                            $('#diskon'+id_product+'').text('Rp. '+separator(dis_produk * jumlah));

                        }

                    } else {

                        var dt = data.total.replace("Rp. ","").split(".").join("");
                        var pt = (pot_harga == 0) ? 0 : pot_harga;
                        var hg = dt - pt;

                        tabel_list.row.add([
                            "<div class='"+data.id_product+" nama_product'>"+data.nama_product+"</div>",
                            "<div class='text-right' id='harga"+data.id_product+"'>Rp. "+separator(hg)+"</div><div class='text-right harga_awal' id='hargaT"+data.id_product+"' hidden>"+data.total+"</div>",
                            "<label class='badge badge-danger jumlah' id='jumlah"+data.id_product+"' data-stok='"+data.stok+"'>"+counter+"</label>",
                            "<div id='diskon"+data.id_product+"' class='text-right diskon'>Rp. "+separator(data.total_diskon)+"</div>",
                            "<div class='text-right subtotal' id='total"+data.id_product+"'>Rp. "+separator((hg*counter) - data.total_diskon)+"</div>",
                            "<div><label style='cursor:pointer' class='badge badge-success mr-1 ubah-list text-white' data-toggle='tooltip' data-placement='top' title='Edit' product='"+data.nama_product+"' data-id='"+data.id_product+"' harga='"+data.harga+"' diskon='"+data.dis_produk+"' satuan='"+data.satuan+"' stok='"+data.stok+"'><i class='fa fa-pen-alt'></i></label><label class='badge badge-danger text-white hapus-list' style='cursor:pointer' data-toggle='tooltip' data-placement='top' title='Hapus' data-id='"+data.id_product+"'><i class='fa fa-trash-alt'></i></label></div>"
                        ]).draw(false);
                        counter++;

                    }

                    // untuk atribut nilai lain
                    if(tabel_list.rows().count() < 1)
                    {
                        $('#diskon').text(data.diskon);
                        $('#total_diskon').text(data.total_diskon);
                        $('#total').text(data.tot_bayar);
                        $('#harga').val(data.tot_tr);

                        var tunai1      = $('#tunai').val();
                        var tunai       = tunai1.replace(".","");
                        var t_kembali   = tunai - data.tot_tr;

                        if (tunai > 0) {
                            $('#kembali').text("Rp. "+separator(t_kembali));
                        }

                    } else {

                        if(tabel_list.rows().count() < 2)
                        {

                            var nominal     = $('.subtotal').text().replace("Rp. ", '');
                            var subtotal    = nominal.split('.').join('');

                            var diskon   = 0;
                            $('.diskon').each(function(){
                                var nominal_diskon  = $(this).text().replace("Rp. ", '');
                                var nilai_diskon    = nominal_diskon.split('.').join('');
                                diskon              += parseInt(nilai_diskon);
                            });

                            $('#total_diskon').text(data.total_diskon);
                            $('#diskon').text('Rp. '+separator(diskon));
                            $('#total').text('Rp. '+separator(subtotal,0,',','.'));

                            var tunai1      = $('#tunai').val();
                            var tunai       = tunai1.split('.').join('');
                            var t_kembali   = tunai - subtotal;
                            if (tunai > 0) {
                                $('#kembali').text("Rp. "+separator(t_kembali));
                            }


                        } else {

                            var subtotal = 0;
                            var diskon   = 0;
                            $('.subtotal').each(function(){
                                var nominal_harga   = $(this).text().replace("Rp. ", '');
                                var harga           = nominal_harga.split('.').join('');
                                subtotal            += parseInt(harga);
                            });
                            $('.diskon').each(function(){
                                var nominal_diskon  = $(this).text().replace("Rp. ", '');
                                var nilai_diskon    = nominal_diskon.split('.').join('');
                                diskon              += parseInt(nilai_diskon);
                            });
                            // $('#total_diskon').text('Rp. '+separator(diskon));
                            $('#diskon').text('Rp. '+separator(diskon));
                            $('#total').text('Rp. '+separator(subtotal));

                            var tunai1      = $('#tunai').val();
                            var tunai       = tunai1.split('.').join('');
                            var t_kembali   = tunai - subtotal;
                            if (tunai > 0) {
                                $('#kembali').text("Rp. "+separator(t_kembali));
                            }

                        }
                    }
                    $('#btn_transaksi').removeAttr('hidden');

                }
            })

        })

        $(".angka").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
                $("#errmsg").html("Digits Only").show().fadeOut("slow");
                    return false;
            }
        });

        // ubah list
        $('.tabel-list').on('click', '.ubah-list', function () {

            var id              = $(this).data('id');
            var product         = $(this).attr('product');
            var harga           = $(this).attr('harga');
            var diskon_pro      = $(this).attr('diskon');
            var satuan          = $(this).attr('satuan');
            var stok            = $(this).attr('stok');

            var pot_harga       = $("#potongan_harga").val().replace(".","");

            console.log(stok);

            $('#diskon_pro').val(diskon_pro);
            $('#harga').val(harga - pot_harga);
            $('#satuan').val(satuan);
            $('#stok').val(stok);

            var nominal_diskon  = $('#diskon'+id+'').text().replace("Rp. ", '');
            var diskon          = parseFloat(nominal_diskon.split('.').join(''));
            var jumlah          = parseFloat($('#jumlah'+id+'').text());

            if (diskon == 0) {
                $('#diskon_diskon').val(0);
            } else {
                var hg  = harga - pot_harga;
                var dis = (diskon/hg) * 100;
                
                $('#diskon_diskon').val(dis);

                console.log(hg);
                console.log(pot_harga);
            }

            $('.judul').text("Product "+product);
            $('#id').val(id);
            $('#jumlah').val(jumlah);
            $('#nilai_diskon').val(separator(diskon));
            $('#modal_ubah_list').modal('show');
            
        })

        // simpan produk
        $('#simpan_produk').on('click', function () {
            
            id = $('#id').val();

            $('#jumlah'+id+'').text($('#jumlah').val());
            $('#diskon'+id+'').text('Rp. '+separator($('#nilai_diskon').val()));

            var nominal_harga       = $('#harga'+id+'').text().replace("Rp. ", '');
            var harga               = parseFloat(nominal_harga.split('.').join(''));
            var nominal_diskon      = $('#diskon'+id+'').text().replace("Rp. ", '');
            var diskon              = parseFloat(nominal_diskon.split('.').join(''));
            var jumlah              = parseFloat($('#jumlah'+id+'').text());
            var subtotal            = (harga*jumlah)-diskon;

            $('#total'+id+'').text('Rp. '+separator(subtotal));

            if(tabel_list.rows().count() < 2)
            {
                $('#total_diskon').text('Rp. '+separator($('#nilai_diskon').val()));
                $('#total').text('Rp. '+separator(subtotal,0,',','.'));
                $('#harga').val(subtotal);
                var tunai1      = $('#tunai').val();
                var tunai       = tunai1.split('.').join('');
                var t_kembali   = tunai - subtotal;

                var diskon   = 0;
                $('.diskon').each(function(){
                    var nominal_diskon  = $(this).text().replace("Rp. ", '');
                    var nilai_diskon    = nominal_diskon.split('.').join('');
                    diskon              += parseInt(nilai_diskon);
                });

                $('#diskon').text('Rp. '+separator(diskon));

                var tunai1      = $('#tunai').val();
                var tunai       = tunai1.split('.').join('');
                var t_kembali   = tunai - subtotal;
                if (tunai > 0) {
                    $('#kembali').text("Rp. "+separator(t_kembali));
                }
                
                // if (tunai > 0) {
                //     $('#kembali').text("Rp. "+separator(t_kembali));
                // }
                $('#modal_ubah_list').modal('hide');
            }
            else
            {
                var nilai_subtotal = 0;
                var nilai_diskon   = 0;
                $('.subtotal').each(function(){
                    var nominal_harga   = $(this).text().replace("Rp. ", '');
                    var harga_plain     = nominal_harga.split('.').join('');
                    nilai_subtotal      += parseInt(harga_plain);
                });
                $('.diskon').each(function(){
                    var nominal_diskon  = $(this).text().replace("Rp. ", '');
                    var diskon_plain    = nominal_diskon.split('.').join('');
                    nilai_diskon        += parseInt(diskon_plain);
                });
                // $('#total_diskon').text('Rp. '+separator(nilai_diskon));
                $('#diskon').text('Rp. '+separator(nilai_diskon));
                $('#total').text('Rp. '+separator(nilai_subtotal));
                var tunai1      = $('#tunai').val();
                var tunai       = tunai1.split('.').join('');
                var t_kembali   = tunai - nilai_subtotal;
                if (tunai > 0) {
                    $('#kembali').text("Rp. "+separator(t_kembali));
                }
                $('#modal_ubah_list').modal('hide');
            }

        })

        // hapus list
        $('.tabel-list').on('click', '.hapus-list', function () {

            var id_prod = $(this).data('id');

            tabel_list.row($(this).parents('tr')).remove().draw();
            if(tabel_list.rows().count() < 1) 
            {
                $('#btn_transaksi').attr('hidden', true);
                $('#diskon').text('Rp. 0');
                $('#total_diskon').text('Rp. 0');
                $('#diskon').text('Rp. 0');
                $('#total').text('Rp. 0');
                $('#harga').val('Rp. 0');
                $('#kembali').text("Rp. 0");
            }
            else
            {
                $('#btn_transaksi').removeAttr('hidden');
                if(tabel_list.rows().count() < 2)
                {
                    var nominal_subtotal    = $('.subtotal').text().replace("Rp. ", '');
                    var subtotal            = nominal_subtotal.split('.').join('');
                    var nominal_diskon      = $('.diskon').text().replace("Rp. ", '');
                    var diskon              = nominal_diskon.split('.').join('');

                    // $('#total_diskon').text('Rp. '+separator($('#nilai_diskon').val()));
                    $('#total').text('Rp. '+separator(subtotal,0,',','.'));
                    $('#harga').val(subtotal);
                    var tunai1      = $('#tunai').val();
                    var tunai       = tunai1.split('.').join('');
                    var t_kembali   = tunai - subtotal;

                    var diskon1   = 0;
                    $('.diskon').each(function(){
                        var nominal_diskon  = $(this).text().replace("Rp. ", '');
                        var nilai_diskon    = nominal_diskon.split('.').join('');
                        diskon1              += parseInt(nilai_diskon);
                    });

                    $('#diskon').text('Rp. '+separator(diskon1));

                    var tunai1      = $('#tunai').val();
                    var tunai       = tunai1.split('.').join('');
                    var t_kembali   = tunai - subtotal;
                    if (tunai > 0) {
                        $('#kembali').text("Rp. "+separator(t_kembali));
                    }

                    removeA(id_pdc, id_prod);
                    
                    // if (tunai > 0) {
                    //     $('#kembali').text("Rp. "+separator(t_kembali));
                    // }
                }
                else
                {
                    var nilai_subtotal = 0;
                    var nilai_diskon   = 0;
                    $('.subtotal').each(function(){
                        var nominal_harga   = $(this).text().replace("Rp. ", '');
                        var harga_plain     = nominal_harga.split('.').join('');
                        nilai_subtotal      += parseInt(harga_plain);
                    });
                    $('.diskon').each(function(){
                        var nominal_diskon  = $(this).text().replace("Rp. ", '');
                        var diskon_plain    = nominal_diskon.split('.').join('');
                        nilai_diskon        += parseInt(diskon_plain);
                    });
                    // $('#total_diskon').text('Rp. '+separator(nilai_diskon));
                    $('#diskon').text('Rp. '+separator(nilai_diskon));
                    $('#total').text('Rp. '+separator(nilai_subtotal));
                    var tunai1      = $('#tunai').val();
                    var tunai       = tunai1.split('.').join('');
                    var t_kembali   = tunai - nilai_subtotal;
                    if (tunai > 0) {
                        $('#kembali').text("Rp. "+separator(t_kembali));
                    }

                    removeA(id_pdc, id_prod);
                }
                
            }

            if (tabel_list.rows().count() == 0) {
                $('#potongan_harga').val(0);
            }

        })

        // aksi ubah jumlah product
        $('.tabel-list').on('click', '.ubah-jml', function () {

            var id_d_tr = $(this).data('id');
            var aksi    = $(this).attr('aksi');
            var harga   = $(this).attr('harga');
            var id_tr   = $('#id_transaksi').val();

            $.ajax({
                url         : "Transaksi/simpan_data_list",
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
                data        : {id_d_tr:id_d_tr, aksi:aksi, id_tr:id_tr, harga:harga},
                dataType    : "JSON",
                success     : function (data) {

                    $('#subtotal').text(data.total);
                    $('#diskon').text(data.diskon);
                    $('#total').text(data.tot_bayar);

                    if (data.tot == null) {
                        $('#btn_transaksi').attr('hidden', true);
                    } else {
                        $('#btn_transaksi').removeAttr('hidden');
                    }

                    tabel_list.ajax.reload(null,false);   

                    swal.close();
                    
                },
                error       : function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                }

            })

            return false;

        })

        // button transaksi
        $('#simpan_email').on('click', function () {

            var email  = $('#nm_email').val();
            var id_tr  = $('.id_tr').val();

            console.log(email);
            console.log(id_tr);

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

                            $('#modal_selesai').modal('show');
                            $('#modal_email').modal('hide');
                        } else {
                            alert("gagal");
                        }
                        
                    },
                    error       : function(xhr, status, error) {
                        // var err = eval("(" + xhr.responseText + ")");
                        alert(xhr.responseText);
                    }

                })

                return false;

            }

        })

        // 14-07-2020

        $('.kirim-email').on('click', function () {
            $('#modal_selesai').modal('hide');
            $('#modal_email').modal('show');
        })

        $('.keluar-email').on('click', function () {
            $('#modal_selesai').modal('show');
            $('#modal_email').modal('hide');
        })

        // kirim email
        $('#btn_transaksi').on('click', function () {

            var nomor_meja              = $('#nomor_meja').val();
            var tunai                   = $('#tunai').val().replace(".", '');
            var pot_harga               = $('#potongan_harga').val().replace(".", '');
            var nominal_total_harga     = $('#total').text().replace("Rp. ", '');
            var total_harga             = nominal_total_harga.split('.').join('');
            var nominal_kembalian       = $('#kembali').text().replace("Rp. ", '');
            var kembalian               = nominal_kembalian.split('.').join('');
            var nominal_total_diskon    = $('#diskon').text().replace("Rp. ", '');
            var total_diskon            = nominal_total_diskon.split('.').join('');
            var nama_product            = [];

            $('.nama_product').each(function() { 
                nama_product.push($(this).text()); 
            });
            var jumlah                  = [];
            $('.jumlah').each(function() {
                jumlah.push($(this).text());
            });
            var discount                = [];
            $('.diskon').each(function() {
                discount.push($(this).text().replace("Rp. ", '').split('.').join(''));
            });
            var subtotal                = [];
            $('.subtotal').each(function() {
                subtotal.push($(this).text().replace("Rp. ", '').split('.').join(''));
            });

            // if (nomor_meja == 0) {

            //     swal({
            //         title               : "Peringatan",
            //         text                : 'Nomer Meja Harap Diisi!',
            //         buttonsStyling      : false,
            //         type                : 'warning',
            //         showConfirmButton   : false,
            //         timer               : 1000
            //     });  

            //     return false;

            // } else 
            
            if(tunai == "") {

                swal({
                    title               : "Peringatan",
                    text                : 'Nilai Tunai Harap Diisi!',
                    buttonsStyling      : false,
                    type                : 'warning',
                    showConfirmButton   : false,
                    timer               : 1000
                });  
                return false;

            } else {
                $.ajax({
                    url         : "Transaksi/simpan_transaksi",
                    method      : "POST",
                    // beforeSend  : function () {
                    //     swal({
                    //         title   : 'Menunggu',
                    //         html    : 'Memproses Data',
                    //         onOpen  : () => {
                    //             swal.showLoading();
                    //         }
                    //     })
                    // },
                    data        : {tunai:tunai, nomor_meja:nomor_meja, total_harga:total_harga, total_diskon:total_diskon, nama_product:nama_product, jumlah:jumlah, discount:discount, subtotal:subtotal, pot_harga:pot_harga},
                    dataType    : "JSON",
                    success     : function (data) {
                        $('#subtotal').text("Rp. 0");
                        $('#diskon').text("Rp. 0");
                        $('#total_diskon').text("Rp. 0");
                        $('#total').text("Rp. 0");
                        $('#tunai').val(0);
                        $('#nomor_meja').val(0);
                        $('#kembali').text("Rp. 0");
                        $('#btn_transaksi').attr('hidden', true);
                        $('.id_tr').val(data.id_tr);
                        swal.close()  
                        // $('#modal_selesai').modal('show');
                        tabel_list.clear().draw();     

                        id_pdc = [];     

                        $.ajax({
                            url         : "Transaksi/cetak_nota/"+data.id_tr+"/"+pot_harga,
                            method      : "POST",
                            data        : {id_tr:data.id_tr, pot_harga:pot_harga},
                            dataType    : "JSON",
                            success     : function (data2) {

                                
                            },
                            error       : function(xhr, status, error) {
                                // var err = eval("(" + xhr.responseText + ")");
                                // alert(err.Message);

                                $('#potongan_harga').val(0);

                                console.log('masuk');
                            }

                        })

                        return false;

                    },
                    error       : function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        alert(err.Message);
                    }
                });
                return false;
            }
        })

        // card cetak nota
        $('.c_nota').on('click', function () {

            var id_tr       = $('.id_tr').val();
            var pot_harga   = $('#potongan_harga').val().replace(".","");

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

    })

</script>