var isi     = [];
var tunai   = [];
var diskon  = [];
var pot     = [];


$(document).ready(function () {

    $('.easy-get').on('click', () => {

        var isi     = $('.easy-put').val()
        var aksi    = $('.easy-get').data('id');

        show_easy_numpad();
        $('#easy-numpad-output').text(isi);
        $('.aksi').val(aksi);
    });

    $('.easy-get2').on('click', () => {
        var isi         = $('.easy-put2').val();
        var isi2        = isi.replace(".", "");
        var aksi        = $('.easy-get2').data('id');
        var harga       = $('#harga').val();
        var diskon      = $('#diskon').val();
        var satuan      = $('#satuan').val();
        var diskon_pro  = $('#diskon_pro').val();
        var stok        = $('#stok').val();

        show_easy_numpad();
        $('#easy-numpad-output').text(isi2);
        $('.aksi').val(aksi);
        $('.harga').val(harga);
        $('.diskon').val(diskon);
        $('.satuan').val(satuan);
        $('.diskon_pro').val(diskon_pro);
        $('.stok').val(stok);
    });

    $('.easy-get3').on('click', () => {
        var isi     = $('.easy-put3').val();
        var isi2    = isi.replace(".", "");
        var aksi    = $('.easy-get3').data('id');
        var harga   = $('#harga').val();
        var jumlah  = $('#jumlah').val();
        var diskon  = $('#nilai_diskon').val();

        show_easy_numpad();
        $('#easy-numpad-output').text(isi2);
        $('.harga').val(harga);
        $('.jumlah').val(jumlah);
        $('.aksi').val(aksi);
        $('.diskon').val(diskon);
    });

    $('.easy-get4').on('click', () => {
        var isi     = $('.easy-put4').val()
        var aksi    = $('.easy-get4').data('id');
        // var harga   = $('#harga').val();
        var diskon  = $('#diskon').val();

        var isi2 = isi.replace(".", "");

        var nominal  = $('#total').text().replace("Rp. ", '');
        var harga    = nominal.split('.').join('');

        if (isi2 == '0') {
            tunai   = [];
        }

        show_easy_numpad();
        $('#easy-numpad-output').text(isi2);
        $('.aksi').val(aksi);
        $('.harga').val(harga);
        $('.diskon').val(diskon);
    });

    $('.easy-get5').on('click', function () {
        var id      = $(this).data('id');
        var isi     = $('#tunai'+id).val();
        var harga   = $(this).attr('harga');
        var aksi    = "tunai2";

        var isi2 = isi.replace(".", "");

        show_easy_numpad();
        $('#easy-numpad-output').text(isi2);
        $('.aksi').val(aksi);
        $('.harga').val(harga);
        $('.id_tr').val(id);
    });

    $('.easy-get6').on('click', () => {
        var isi     = $('.easy-put6').val()
        var aksi    = $('.easy-get6').data('id');
        var tunai   = $('#tunai').val();

        var isi2    = isi.replace(".", "");
        var tunai2  = tunai.replace(".", "");

        var diskon  = $('#nilai_diskon').val();
        var diskon2 = diskon.replace(".","");

        var nominal  = $('#total').text().replace("Rp. ", '');
        var harga    = nominal.split('.').join('');

        show_easy_numpad();
        $('#easy-numpad-output').text(isi2);
        $('.aksi').val(aksi);
        $('.harga').val(harga);
        $('.tunai').val(tunai2);
        $('.diskon').val(diskon2);
    });

});


function show_easy_numpad() {

    var easy_numpad = `
        <div class="easy-numpad-frame" id="easy-numpad-frame">
            <div class="easy-numpad-container">
                <input type="hidden" class="aksi">
                <input type="hidden" class="diskon">
                <input type="hidden" class="harga">
                <input type="hidden" class="tunai">
                <input type="hidden" class="satuan">
                <input type="hidden" class="diskon_pro">
                <input type="hidden" class="id_tr">
                <input type="hidden" class="stok">
                <input type="hidden" class="jumlah">
                <div class="easy-numpad-output-container">
                    <p class="easy-numpad-output separator" id="easy-numpad-output"></p>
                </div>
                <div class="easy-numpad-number-container">
                    <table>
                        <tr>
                            <td><a href="7" onclick="easynum()">7</a></td>
                            <td><a href="8" onclick="easynum()">8</a></td>
                            <td><a href="9" onclick="easynum()">9</a></td>
                            <td><a href="Del" class="del" id="del" onclick="easy_numpad_del()">Del</a></td>
                        </tr>
                        <tr>
                            <td><a href="4" onclick="easynum()">4</a></td>
                            <td><a href="5" onclick="easynum()">5</a></td>
                            <td><a href="6" onclick="easynum()">6</a></td>
                            <td><a href="Clear" class="clear" id="clear" onclick="easy_numpad_clear()">Clear</a></td>
                        </tr>
                        <tr>
                            <td><a href="1" onclick="easynum()">1</a></td>
                            <td><a href="2" onclick="easynum()">2</a></td>
                            <td><a href="3" onclick="easynum()">3</a></td>
                            <td><a href="Cancel" class="cancel" id="cancel" onclick="easy_numpad_cancel()">Cancel</a></td>
                        </tr>
                        <tr>
                            <td colspan="2" onclick="easynum()"><a href="0">0</a></td>
                            <td onclick="easynum()"><a href=".">.</a></td>
                            <td><a href="Done" class="done" id="done" onclick="easy_numpad_done()">Done</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    `;

    // var txt = $('.separator').text();
    // var easy_num_value = txt.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    // $('.separator').text(easy_num_value);

    // console.log(txt);

    $('body').append(easy_numpad);
}

function easy_numpad_close() {
    $('#easy-numpad-frame').remove();
}

function easynum() {
    event.preventDefault();

    navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;
    if (navigator.vibrate) {
        navigator.vibrate(60);
    }

    var easy_num_button = $(event.target);
    var easy_num_value  = easy_num_button.text();
    // var easy_num_value = easy_num_button.text().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

    $('#easy-numpad-output').append(easy_num_value);

    var aksi = $('.aksi').val();

    if (aksi == 'nomor_meja') {

        isi.push(easy_num_value);

        var a = isi.join();

        $('#easy-numpad-output').text(separator(a.split(',').join('')));
        
    } else if (aksi == 'tunai') {
        
        tunai.push(easy_num_value);

        var a = tunai.join();

        $('#easy-numpad-output').text(separator(a.split(',').join('')));
        
    } else if (aksi == 'nilai_diskon') {
        diskon.push(easy_num_value);

        var a = diskon.join();

        $('#easy-numpad-output').text(separator(a.split(',').join('')));
    } else if (aksi == 'potongan_harga') {
        pot.push(easy_num_value);

        var a = pot.join();

        $('#easy-numpad-output').text(separator(a.split(',').join('')));
    }

}

function easy_numpad_del() {
    event.preventDefault();
    var easy_numpad_output_val = $('#easy-numpad-output').text();
    var easy_numpad_output_val_deleted = easy_numpad_output_val.slice(0, -1);
    // $('#easy-numpad-output').text(easy_numpad_output_val_deleted);

    $('#easy-numpad-output').text(separator(easy_numpad_output_val_deleted.split('.').join('')));

    // var aksi = $('.aksi').val();

    // if (aksi == 'nomor_meja') {

    //     isi.push(easy_numpad_output_val_deleted);

    //     var a = isi.join();

    //     $('#easy-numpad-output').text(separator(a.split(',').join('')));
        
    // } else if (aksi == 'tunai') {

    //     $('#easy-numpad-output').text(separator(easy_numpad_output_val_deleted.split('.').join('')));
        
    // } else if (aksi == 'nilai_diskon') {
    //     diskon.push(easy_numpad_output_val_deleted);

    //     var a = diskon.join();

    //     $('#easy-numpad-output').text(separator(a.split(',').join('')));
    // }

    isi     = [];
    tunai   = [];
    diskon  = [];
    pot     = [];
}

function easy_numpad_clear() {
    event.preventDefault();
    $('#easy-numpad-output').text("");

    isi     = [];
    tunai   = [];
    diskon  = [];
    pot     = [];
}

function easy_numpad_cancel() {
    event.preventDefault();
    $('#easy-numpad-frame').remove();
}

function easy_numpad_done() {
    event.preventDefault();
    var easy_numpad_output_val = $('#easy-numpad-output').text();
    var aksi                   = $('.aksi').val();
    var harga                  = $('.harga').val();
    var diskon                 = $('.diskon').val();
    var tunai                  = $('.tunai').val();
    var satuan                 = $('.satuan').val();
    var diskon_pro             = $('.diskon_pro').val();
    var id_tr                  = $('.id_tr').val();
    var stok                   = $('.stok').val();
    var jumlah                 = $('.jumlah').val();

    if (aksi == 'nomor_meja') {
        $('#nomor_meja').val(easy_numpad_output_val);
    } else if (aksi == 'jumlah') {

        if (parseInt(easy_numpad_output_val) > parseInt(stok)) {

            event.preventDefault();
            $('#easy-numpad-frame').remove();

            swal({
                title               : "Peringatan",
                text                : 'Harap kurangi jumlah, melebihi stok yang ada!',
                buttonsStyling      : false,
                type                : 'warning',
                showConfirmButton   : false,
                timer               : 1000
            });  

            r$('#jumlah').val(0);

        } else {

            var dis  = 0;
            var dis0 = 0;

            // if (satuan == 'Harga') {
            //     dis = diskon_pro * easy_numpad_output_val;
            // } else {
            //     dis0 = (diskon_pro * harga) / 100;
            //     dis = dis0 * easy_numpad_output_val;
            // }

            dis = diskon_pro * easy_numpad_output_val;

            $('#nilai_diskon').val(separator(dis));

            $('#jumlah').val(easy_numpad_output_val);
        }

        
    } else if (aksi == 'tunai') {

        $('#tunai').val(separator(easy_numpad_output_val.replace(".", "")));
        
        var kembali = easy_numpad_output_val.replace(".", "") - harga;

        if (kembali < 0) {
            $('#btn_transaksi').attr('disabled', true);
        } else {
            $('#btn_transaksi').attr('disabled', false);
        }

        $('#kembali').text("Rp. "+separator(kembali));
    } else if (aksi == 'tunai2') {

        var isi2 = easy_numpad_output_val.replace(".", "");

        $('#tunai'+id_tr).val(separator(isi2));
        
        var kembali = isi2 - harga;

        $('#kembali'+id_tr).text("Rp. "+separator(kembali));

        if (kembali < 0) {

            event.preventDefault();
            $('#easy-numpad-frame').remove();

            swal({
                title               : "Peringatan",
                text                : 'Nominal Tunai Kurang!',
                buttonsStyling      : false,
                type                : 'warning',
                showConfirmButton   : false,
                timer               : 1000
            });  

            return false;

        } else {

            event.preventDefault();
            $('#easy-numpad-frame').remove();

            $.ajax({
                url         : "Transaksi/simpan_tunai",
                method      : "POST",
                data        : {id_tr:id_tr, tunai:isi2},
                dataType    : "JSON",
                success     : function (data) {

                    console.log('sukses'); 
                    
                },
                error       : function(xhr, status, error) {
                    // var err = eval("(" + xhr.responseText + ")");
                    alert("Error");
                }

            })

            return false;

        }

    } else if (aksi == 'potongan_harga') {

        var tt = harga - easy_numpad_output_val;

        $('#potongan_harga').val(separator(easy_numpad_output_val));
        $('#total').text("Rp. "+separator((harga) - (easy_numpad_output_val.replace(".", ""))));
        $('#kembali').text("Rp. "+separator(parseInt(tunai) - (parseInt(harga) - parseInt(easy_numpad_output_val.replace(".", "")))) );


    } else {
        // $('#nilai_diskon').val(separator(easy_numpad_output_val));

        // $tt_dis  = ($nilai_diskon * $cari_0['harga']) / 100;

        var tot_dis = (easy_numpad_output_val * harga) / 100;

        $('#diskon_diskon').val(easy_numpad_output_val);

        $('#nilai_diskon').val(separator(tot_dis * jumlah));
    }

    easy_numpad_close();

    isi     = [];
    tunai   = [];
    diskon  = [];
    pot     = [];
}

function separator(kalimat) {
    return kalimat.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}