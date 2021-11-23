<div class="container-fluid page-body-wrapper" style="background-color: #7094F1; margin: 0 !important;">
  <div class="main-panel">
    <div class="content-wrapper pb-0">
      <div class="row">
        <div class="col-sm">
          <div class="card">
            <div class="card-body m-5">
              <form id="form">
                <div class="form-group">
                    <select class="select2 form-control" name="id_karyawan" required>
                    <option selected disabled hidden>--PILIH--</option>
                    <?php foreach($karyawan as $row) { 
                      if(date('Y-m-d', strtotime($row->created_at)) != date('Y-m-d') || $row->created_at == null) {
                    ?>
                      <option value="<?php echo $row->id ?>"><?php echo $row->nama_karyawan ?></option>
                    <?php }} ?>
                  </select>
                </div>
                <center>
                  <div id="camera" style="margin-bottom: 10px; transform: scaleX(-1);"></div>
                  <!-- <canvas id="camera" width="640" height="480" style="margin-bottom: 10px; transform: scaleX(-1);"></canvas> -->
                </center>
                <center>
                  <button type="submit" id="submit" class="btn btn-flat" style="width: 650px; background-color: #7094F1; color: #fff;">Submit</button>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- .select2-search__field -->
    <script language="JavaScript">
      Webcam.set({
        width: 640,
        height: 480,
        image_format: 'jpeg',
        jpeg_quality: 90,
      });
      Webcam.attach( '#camera' );
    </script>
    <script type="text/JavaScript">
      // $('#camera').video2image({
      //   width: 640,
      //   height: 480,
      //   autoplay : true,
      //   onsuccess : function () {},
      //   onerror: function () {}
      // });
      // $('#camera').video2image(function(){
      //   var image = $(this).get(0).toDataURL();
      // });
      var image = '';
      // image = $('#camera').video2image('capture');
      $(document).ready(function() {
        // $('.select').select2();
        // $('#id_karyawan').autocomplete({
        //   source: '<?php echo base_url("Absensi/get_karyawan") ?>'
        // });
        $('#submit').attr('disabled', true);
        $('#submit').text('Mohon Pilih Nama Karyawan');
        $('.select2').change(function() {
        if ($('.select2').val() == 0) {
              $("#submit").prop('disabled',true);
              $('#submit').text('Mohon Pilih Nama Karyawan');
            } else {
              $("#submit").prop('disabled',false);
              $('#submit').text('Submit');
              $('#accent-keyboard').hide();
            }
        });
      });
      $('#form').on('submit', function (event) {
        event.preventDefault();
        $('#submit').attr('disabled', true);
        $('#submit').text('proses...');
        var id_karyawan = $('select[name=id_karyawan] option').filter(':selected').val()
        Webcam.snap( function(data_uri) {
          image = data_uri;
        });
        $.ajax({
          url: 'http://apicafe.mitrabagja.com/C_absen/simpan_gambar/',
          type: 'POST',
          data: {id_karyawan:id_karyawan, gambar:image.replace('data:image/jpeg;base64,','')},
          dataType: 'json',
        })
        .done(function(data) {
            swal({
            title: data.pesan, 
            text: "Terima Kasih", 
            type: "success"}).then(function(){ 
               location.reload();
              }
            );
        })
        .fail(function() {
          console.log("error");
        })
        
      });
    </script>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->