<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <style>
            body {
                color: black;
                font-family: calibri;
                margin: 100px;
            }

            @media print {
              @page { margin: 0; size: A4; }
              body { margin: 0.7cm; }
            }
        </style>
    </head>
    <body>
        <center><h3><span style="font-weight: bold; text-decoration: underline;">Bukti Transaksi</span></h3></center>
        <?= br(2) ?>
            <table>
            <tr>
                <td width="150">Kode Transaksi</td>
                <td>:<?= nbs(5); echo $transaksi['kode_transaksi'] ?></td>
            </tr>
            <tr>
                <td width="150">Total Harga</td>
                <td>:<?= nbs(5); ?>Rp. <?= number_format($transaksi['total_harga'],0,'.','.') ?></td>
            </tr>
            <tr>
                <td width="150">Nomer Meja</td>
                <td>:<?= nbs(5); echo $transaksi['nomer_meja'] ?></td>
            </tr>
        </table>
        <br>
        <table class="table table-bordered">
            <thead class="thead-light text-center">
                <th>No.</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Diskon</th>
                <th>Subtotal</th>
            </thead>
            <tbody>
                <?php $no=1; foreach ($det_tr as $d): ?>
                    <tr>
                        <td class="text-center"><?= $no ?></td>
                        <td><?= $d['nama_product'] ?></td>
                        <td class="text-center"><?= $d['jumlah'] ?></td>
                        <td>Rp. <?= number_format($d['total_discount'],0,'.','.') ?></td>
                        <td>Rp. <?= number_format($d['subtotal'],0,'.','.') ?></td>
                    </tr>
                <?php $no++; endforeach; ?>
                
            </tbody>
        </table>

        <script type="text/javascript">
            window.print();
        </script>

    </body>
</html>