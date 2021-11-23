<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  <style type="text/css" media="print">
    @media print{  
      *{
        box-sizing: border-box;
        -moz-box-sizing:border-box;
      }
      @page{  
        size: 24cm 14cm;
        page-break-after: always;
        margin: 0;
      }
    } 

  .tabel{
    font-family: sans-serif;
    font-size: 9pt;
    color: #232323;
    border-collapse: collapse;
  }
</style>  
</head>
<body>
  <?php 

    $usr = $this->session->userdata('id_user');
    
    $nm = $this->transaksi->cari_data('mst_user', ['id' => $usr])->row_array();
  
  ?>

  <h1><?php echo $ket; ?></h1>
  <h2>Pendapatan: Rp. <?= number_format($total, 0, '.','.') ?></h2>
  <?php 
  
    if ($nm['username']) {
      echo $n = "<h3>Nama Kasir: ".ucwords($nm['username'])."</h3><br>";
    } else {
      echo $n = "";
    }
  
  ?>
  
  
<!-- 	<table border="1" cellspacing="0" id="table">
    <thead>
      <tr>
        <th>No.</th>
        <th>Tanggal</th>
        <th>Kode Transaksi</th>
        <th>Nama Produk</th>
        <th>Jumlah</th>
        <th>Harga</th>
        <th>Sub Total</th>
        <th>Total Harga</th>
        <th>Petugas</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if($laporan)
      {
        $no = 1;
        foreach($laporan as $row){
          ?>
          <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo date('d-m-Y', strtotime($row->created_at)); ?></td>
            <td><?php echo $row->kode_transaksi ? $row->kode_transaksi : 'Tidak Ada'; ?></td>
            <td><?php echo $row->nama_product; ?></td>
            <td><?php echo $row->jumlah; ?></td>
            <td><?php echo 'Rp. '.number_format($row->harga); ?></td>
            <td><?php echo 'Rp. '.number_format($row->subtotal); ?></td>
            <td><?php echo 'Rp. '.number_format($row->total_harga); ?></td>
            <td><?php echo ucfirst($row->username); ?></td>
          </tr>
          <?php
        }
      }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='7' style='font-weight:bold;text-align: center;background-color: black;color: white;'></td>
        <td colspan="2">Rp. <?php echo number_format($total) ?></td>
      </tr>
    </tfoot>
  </table> -->
  <?php foreach($laporan as $row) { ?>
    <h3><b>Kode Transaksi: <?php echo $row->kode_transaksi ?></b></h3>
    <h3><b>Tanggal: <?php echo date('d-m-Y H:i:s', strtotime($row->created_at)) ?></b></h3>
    <br>
    <?php  
    $this->db->select('trn_detail_transaksi.*, trn_transaksi.total_harga, mst_product.nama_product, mst_product.harga')
    ->from('trn_detail_transaksi')
    ->join('trn_transaksi', 'trn_transaksi.id = trn_detail_transaksi.id_transaksi', 'inner')
    ->join('mst_product', 'mst_product.id = trn_detail_transaksi.id_product', 'left')
    ->where('trn_detail_transaksi.id_transaksi', $row->id)
    ->where('trn_transaksi.id_user', $row->id_user);
    $query = $this->db->get();
    $detil = $query->result();
    $i = 1;
    $diskon = 0;
    $sb_total = 0;
    ?>
    <table class="table" border="1" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>No.</th>
          <th>Nama Produk</th>
          <th>Harga</th>
          <th>Jumlah</th>
          <th>Diskon</th>
          <th>Sub Total</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($detil as $row2) { 
          $sb_total += (($row2->harga - $row->potongan_harga) * $row2->jumlah);
          $diskon += $row2->total_discount;
          ?>
          <tr>
            <td><?php echo $i++; ?>.</td>
            <td><?php echo $row2->nama_product ?></td>
            <td>Rp. <?php echo number_format($row2->harga - $row->potongan_harga) ?></td>
            <td><?php echo $row2->jumlah ?></td>
            <?php if(strlen($row2->total_discount > 2)) { ?>
              <td>Rp. <?php echo number_format($row2->total_discount) ?></td>
            <?php } elseif(strlen($row2->total_discount < 3) && $row2->total_discount > 0) { 
               $harga_diskon = ($row2)
              ?>
              <td><?php echo $row2->total_discount ?> %</td>
            <?php } elseif($row2->total_discount == 0) { ?>
            <td>Tidak Ada</td>
            <?php } ?>
            <td>Rp. <?php echo number_format($row2->subtotal) ?></td>
          </tr>
        <?php } ?>
        <tr></tr>
        <tr></tr>
        <tr>
          <td colspan="4" rowspan="5"></td>
          <td><b>Subtotal</b></td>
          <td><b>Rp. <?php echo number_format($sb_total) ?></b></td>
        </tr>
        <tr>
          <td><b>Potongan Harga</b></td>
          <td><b>Rp. <?php echo number_format($row->potongan_harga) ?></b></td>
        </tr>
        <tr>
          <td><b>Diskon</b></td>
          <td><b>Rp. <?php echo number_format($diskon) ?></b></td>
        </tr>
        <tr>
          <td><b>Total</b></td>
          <td><b>Rp. <?php echo number_format($sb_total - $diskon) ?></b></td>
        </tr>
      </tbody>
    </table>
  <?php } ?>
</body>
</html>