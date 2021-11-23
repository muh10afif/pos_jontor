<div class="container" style="margin-top: 25px;">
  <div class="row">
   
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mt-3">
      <div class="mb-5">
        <div class="d-flex justify-content-start">

          <div class="card bg-primary text-white shadow">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <i class="fa fa-money-check fa-2x mr-3"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="card shadow" style="width: 100%;">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h3 class="text-primary font-weight-bold">Transaksi</h3>
                </div>
                <div class="col-md-6">
                  <h3 class="text-primary font-weight-bold text-right"><?php echo $tr_hari ?></h3>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mt-3">
      <div class="mb-5">
        <div class="d-flex justify-content-start">

          <div class="card bg-warning text-white shadow">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <i class="fa fa-cubes fa-2x mr-3"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="card shadow" style="width: 100%;">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h3 class="text-warning font-weight-bold">Produk</h3>
                </div>
                <div class="col-md-6">
                  <h3 class="text-warning font-weight-bold text-right"><?php echo $pr_hari ?></h3>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>

    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
      <div class="mb-5">
        <div class="d-flex justify-content-start">

          <!-- <div>
            <div class="hexagon">
              <div class="hex-mid hexagon-warning">
                <i class="mdi mdi-cash"></i>
              </div>
            </div>
          </div>
          <h3 class="text-warning font-weight-bold">Pendapatan</h3>
          <h3 class="text-warning font-weight-bold">Rp. <?php echo number_format($pendapatan->total) ?></h3>
        </div> -->

        <div class="card bg-success text-white shadow">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <i class="fa fa-money-check-alt fa-2x mr-3"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="card shadow" style="width: 100%;">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h3 class="text-success font-weight-bold">Pendapatan</h3>
                </div>
                <div class="col-md-6">
                  <h3 class="text-success font-weight-bold text-right">Rp. <?php echo number_format($pendapatan->total) ?></h3>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
      <div class="mb-5">
        <div class="d-flex justify-content-start">

          <div class="card bg-info text-white shadow">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <i class="fa fa-wallet fa-2x mr-3"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="card shadow" style="width: 100%;">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h3 class="text-info font-weight-bold">Profit</h3>
                </div>
                <div class="col-md-6">
                <?php  
                  $profit   = 0;
                  $hpp      = 0;
                  $subtotal = 0;
                  foreach($dt_profit as $row) {
                    $hpp      += ($row->hpp*$row->jumlah);
                    $subtotal += $row->total_harga;
                  }
                  $profit = $subtotal - $hpp;
                  ?>
                  <h3 class="text-info font-weight-bold text-right">Rp. <?php echo number_format($profit) ?></h3>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->