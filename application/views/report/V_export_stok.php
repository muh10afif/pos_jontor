<!doctype html>
<html>
    <head>
        <title>Report Stok</title>

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->

    <style>

    #ad thead tr th {
      vertical-align: middle;
      text-align: center;
    }

    th, td {
      padding: 5px;
      font-size: 10px;
    }

    th {
      text-align: center;
    }

    tr th {
      background-color: #122E5D; color: white;
    }
    .a tr td {
      font-weight: bold;
    }
    /* body {
      margin: 20px 20px 20px 20px;
      color: black;
    } */
    h5, h6 {
      font-weight: bold;
      text-align: center;
      font-size: 15px;
    }
    #d th {
      background-color: #122E5D; color: white;
    }
    #tot {
      background-color: #d2e0f7; font-weight: bold;
    }
    #tot_1 {
      font-weight: bold;
    }
    </style>
</head>
<body>

<h6 style="font-weight: bold; margin: 5px;">Report Stok</h6>
<h6 style="font-weight: bold; margin: 5px;">Tanggal Awal <?= nice_date($tgl_awal, 'd F Y') ?> s/d <?= nice_date($tgl_akhir, 'd F Y') ?></h6>
<br>

<?php $no=1; foreach ($d_stok as $d): ?>

  <?= $no ?>. Product <?= $d['nama_product'] ?> | Stok <?= $d['stok'] ?><br><br>

  <?php $dt = $this->bahan->get_detail_stok_report($d['id'], $tgl_awal, $tgl_akhir)->result_array(); ?>

  <table border="1" cellspacing="0" width="100%" align="center">
      <thead>
          <tr>
              <th>No</th>
              <th>Barang Masuk</th>
              <th>Barang Keluar</th>
              <th>Barang Retur</th>
              <th>Tanggal Transaksi</th>
          </tr>
      </thead>
      <tbody>
        <?php 

        $i=1; 
        $tot_bm = 0;
        $tot_bk = 0;
        $tot_br = 0;

        foreach ($dt as $t): ?>
          <tr>
            <td align="center"><?= $i ?>.</td>
            <td align="center"><?= $t['barang_masuk'] ?></td>
            <td align="center"><?= $t['barang_keluar'] ?></td>
            <td align="center"><?= $t['barang_retur'] ?></td>
            <td align="center"><?= nice_date($t['created_at'], 'd-m-Y') ?></td>
          </tr>
        <?php 

        $i++; 
        $tot_bm += $t['barang_masuk'];
        $tot_bk += $t['barang_keluar'];
        $tot_br += $t['barang_retur'];

        endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th>Total</th>
          <th><?= $tot_bm ?></th>
          <th><?= $tot_bk ?></th>
          <th><?= $tot_br ?></th>
          <th></th>
        </tr>
      </tfoot>
  </table>
  <br>
<?php $no++; endforeach; ?>

</body>

</html>