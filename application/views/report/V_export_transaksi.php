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

<h6 style="font-weight: bold; margin: 5px;">Report Transaksi</h6>
<h6 style="font-weight: bold; margin: 5px;">Tanggal <?= nice_date($tgl_awal, 'd F Y') ?> s/d <?= nice_date($tgl_akhir, 'd F Y') ?></h6>
<h6>Pendapatan: <?= number_format($total_p, 0,'.','.') ?> | Transaksi: <?= $total_t ?></h6><br>

<?php $no=1; foreach ($trn as $d): ?>

  <table border="0">
        <tr>
            <td>Kode Transaksi</td>
            <td>: <?= $d['kode_transaksi'] ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: <?= nice_date($d['created_at'], 'd F Y H:i:s') ?></td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>: <?= ($d['kasir'] == null) ? $this->session->userdata('username') : $d['kasir'] ?></td>
        </tr>
  </table>
  <br>

  <?php $dt = $this->report->get_detail_report($d['id'])->result_array(); ?>

  <table border="1" cellspacing="0" width="100%" align="center">
      <thead>
          <tr>
              <th>No</th>
              <th>Kategori</th>
              <th>Product</th>
              <th>Qty</th>
              <th>Harga</th>
              <th>Diskon</th>
              <th>Subtotal</th>
          </tr>
      </thead>
      <tbody>
        <?php 

        $i=1; 
        $tot_dis = 0;

        foreach ($dt as $t): ?>
          <tr>
            <td align="center"><?= $i ?>.</td>
            <td align="left"><?= $t['kategori'] ?></td>
            <td align="left"><?= $t['nama_product'] ?></td>
            <td align="center"><?= $t['jumlah'] ?></td>
            <td align="right"><?= number_format($t['harga'], 0,'.','.') ?></td>
            <td align="right"><?= number_format($t['total_discount'], 0,'.','.') ?></td>
            <td align="right"><?= number_format($t['subtotal'], 0,'.','.') ?></td>
          </tr>
        <?php $i++; $tot_dis += $t['total_discount']; endforeach; ?>
      </tbody>
  </table>
  <br>
  <table border="0">
    <tr>
        <td>Total Diskon</td>
        <td>: RP. <?= number_format($tot_dis, 0,'.','.') ?></td>
    </tr>
    <tr>
        <td>Total</td>
        <td>: Rp. <?= number_format($d['total_harga'], 0,'.','.') ?></td>
    </tr>
  </table>
  <br><hr>
<?php $no++; endforeach; ?>

</body>

</html>