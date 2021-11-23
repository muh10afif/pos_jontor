<?php
header('Content-Type: application/xls');
header('Content-Disposition: attachment; filename=Laporan Rekapan Transaksi.xls');
?>
<!-- <style> .str{ mso-number-format:\@; } </style> -->
	<h1><?php echo $ket; ?></h1>
	<?php foreach($laporan as $row) { ?>
    <h3><b>Kode Transaksi: <?php echo $row->kode_transaksi ?></b></h3>
    <h3><b>Tanggal: <?php echo date('d-m-Y', strtotime($row->created_at)) ?></b></h3>
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
          <th width="105">No.</th>
          <th width="105">Nama Produk</th>
          <th width="105">Harga</th>
          <th width="105">Jumlah</th>
          <th width="105">Diskon</th>
          <th width="105">Sub Total</th>
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
        <tr>
          <td colspan="4" rowspan="4"></td>
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