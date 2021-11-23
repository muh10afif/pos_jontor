<!DOCTYPE html>
<html>
<head>
<!-- <meta charset=”utf-8″ /> -->
<title></title>
<!-- <meta name=”viewport” content=”width=device-width, initial-scale=1.0″ /> -->
<style>
    .detail {
        border-collapse: collapse;
    }

    .detail, thead, th, tr, td {
        border: 1px solid black;
        padding: 5px;
    }

    .awal, tr, td {
        border: none;
        padding: 5px;
    }

</style>
</head>
<body>


Terimakasih Kasih atas kepercayaan Anda telah mengunjungi Toko kami. <br> Berikut merupakan informasi transaksi yang telah anda lakukan: <br><br>
<table class="awal">
    <tr><td width='150'>Tanggal/Jam</td><td>: <?= date("d-M-Y H:i:s", now("Asia/Jakarta")) ?></td></tr>
    <tr><td width='150'>Kode Transaksi</td><td>: <?= $tr['kode_transaksi'] ?></td></tr>
    <tr><td width='150'>Nomor Meja </td><td>: <?= $tr['nomer_meja'] ?></td></tr>
</table> <br>
Berikut Detail Transaksi dibawah ini: <br><br>
<?php $tot_st = 0; foreach ($kat as $k): ?>

    <b><?= $k['kategori'] ?></b>

    <?php $pro = $this->transaksi->get_product_kat($k['id_kategori'], $id_tr)->result_array(); ?>

    <table class="detail">
        <thead>
            <th>No</th>
            <th>Item</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Diskon</th>
            <th>Subtotal</th>
        </thead>
        <tbody>
            <?php $no=1; foreach ($pro as $d): ?>
                <tr style="border: 1px solid black;">
                    <td style="border: 1px solid black;" align="center"><?= $no ?></td>
                    <td style="border: 1px solid black;"><?= $d['nama_product'] ?></td>
                    <td style="border: 1px solid black;">Rp. <?= number_format($d['harga'],0,'.','.') ?></td>
                    <td style="border: 1px solid black;" align="center"><?= $d['jumlah'] ?></td>
                    <td style="border: 1px solid black;">Rp. <?= number_format($d['total_discount'],0,'.','.') ?></td>
                    <td style="border: 1px solid black;">Rp. <?= number_format($d['subtotal'],0,'.','.') ?></td>
                </tr>
            <?php
            
            $tot_st += ($d['harga'] * $d['jumlah']);
            $no++; endforeach; ?>
        </tbody>
    </table><br>

<?php endforeach; ?>
        
<table class="awal">
    <tr><td width='150'>Subtotal</td><td>: Rp. <?= number_format($tot_st,0,'.','.') ?></td></tr>
    <tr><td width='150'>Total Diskon</td><td>: Rp. <?= number_format($dis_tr['total_discount'],0,'.','.') ?></td></tr>
    <tr><td width='150'></td><td></td></tr>
    <tr><td width='150'>Total</td><td>: Rp. <?= number_format($tr['total_harga'],0,'.','.') ?></td></tr>
    <tr><td width='150'>Tunai</td><td>: Rp. <?= number_format($tr['tunai'],0,'.','.') ?></td></tr>
    <tr><td width='150'>Kembali</td><td>: Rp. <?= number_format($tr['tunai'] - $tr['total_harga'],0,'.','.') ?></td></tr>
</table> <br>

<br>
Harap simpan email ini sebagai referensi dari transaksi Anda. Sekian semoga informasi ini bermanfaat bagi Anda.<br><br>
Hormat kami,<br><br>
BAGJA INDONESIA

</body>
</html>