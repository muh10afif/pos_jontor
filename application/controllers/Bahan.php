<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Bahan extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('username') == "")
        {
            $this->session->set_flashdata('danger', '<div class="alert alert-danger">Anda belum Log in</div>');
            redirect(base_url(), 'refresh');
        } elseif($this->session->userdata('id_role') < "2")
        {
            redirect(base_url('Dashboard'), 'refresh');
        }

        $this->id_user = $this->session->userdata('id_user');
	}

	public function index()
	{
		$data 	= [
			'title'		=> 'Bahan',
            'isi'		=> 'bahan/read',
            'kategori'  => $this->bahan->cari_data('mst_kategori', ['id_user' => $this->id_user])->result_array(),
            'bahan'     => $this->bahan->cari_data('mst_product', ['status_bahan' => 1, 'id_user' => $this->id_user])->result_array()
		];
		$this->load->view('template/wrapper', $data);
    }
    
    // menampilkan list stok 
    public function tampil_data_stok()
    {
        $list = $this->bahan->get_data_stok();

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();

            $tbody[]    = "<div align='center'>".$no.".</div>";
            $tbody[]    = $o['nama_product'];
            $tbody[]    = $o['stok'];
            $data[]     = $tbody;
        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->bahan->jumlah_semua_stok(),
                    "recordsFiltered"  => $this->bahan->jumlah_filter_stok(),   
                    "data"             => $data
                ];

        echo json_encode($output);
    }

    // aksi proses simpan data stok
    public function simpan_data_stok()
    {
        $aksi       = $this->input->post('aksi');
        $id_stok    = $this->input->post('id_stok');
        $id_product = $this->input->post('id_product');
        $stok       = $this->input->post('stok');

        $data = ['stok'         => $stok,
                 'id_product'   => $id_product,
                 'created_at'   => date("Y-m-d H:i:s", now('Asia/Jakarta'))
                ];

        if ($aksi == 'Tambah') {
            $this->bahan->input_data('mst_stok', $data);
        } elseif ($aksi == 'Ubah') {
            $this->bahan->ubah_data('mst_stok', $data, array('id' => $id_stok));
        } elseif ($aksi == 'Hapus') {
            $this->bahan->hapus_data('mst_stok', array('id' => $id_stok));
        }

        echo json_encode($aksi);
    }

    // ambil data stok
    public function ambil_data_stok($id_stok)
    {
        $data = $this->bahan->cari_data('mst_stok', array("id" => $id_stok))->row_array();

        echo json_encode($data);
    }

    // 05-08-2020
    // simpan stok
    public function simpan_stok()
    {
        $jumlah     = $this->input->post('jumlah');
        $id_product = $this->input->post('nm_bahan');
        $jns_barang = $this->input->post('jns_barang');
        $hitung     = $this->input->post('jml');

        for ($i=0; $i < $hitung; $i++) { 

            // cari id product
            $stok   = $this->bahan->cari_data('mst_stok', ['id_product' => $id_product[$i]])->row_array();

            if (empty($stok)) {

                $dt_stok = ['id_product'    => $id_product[$i],
                            'stok'          => $jumlah[$i],
                            'created_at'    => date("Y-m-d h:i:s", now('Asia/Jakarta'))
                           ];

                // input data mst_stok
                $this->bahan->input_data('mst_stok', $dt_stok);
                $id_stok = $this->db->insert_id();

            } else {

                $id_stok = $stok['id'];
                 
            }

            if ($jns_barang == "Barang Masuk") {

                $barang_masuk   = $jumlah[$i];
                $barang_keluar  = 0;
                $barang_retur   = 0;

            } else if ($jns_barang == "Barang Keluar") {

                $barang_masuk   = 0;
                $barang_keluar  = $jumlah[$i];
                $barang_retur   = 0;

            } else {

                $barang_masuk   = 0;
                $barang_keluar  = 0;
                $barang_retur   = $jumlah[$i];

            }
            
            $data_trn_stok  = [
                'id_stok'           => $id_stok,
                'barang_masuk'      => $barang_masuk,
                'barang_keluar'     => $barang_keluar,
                'barang_retur'      => $barang_retur,
                'created_at'   	    => date("Y-m-d h:i:s", now('Asia/Jakarta'))
            ];

            $this->bahan->input_data('trn_stok', $data_trn_stok);

            $stok           = 0;

            $trn_stok       = $this->bahan->cari_data('trn_stok', ['id_stok' => $id_stok])->result_array();

            foreach ($trn_stok as $r) 
            {
                $stok       += ($r['barang_masuk'] - ($r['barang_keluar'] + $r['barang_retur']));
            }

            $this->bahan->ubah_data('mst_stok', ['stok' => $stok], ['id' => $id_stok]);

        }

        echo json_encode(array("status" => $hitung));
    }

    // 04-08-2020

    // menampilkan list bahan 
    public function tampil_data_bahan()
    {
        $list = $this->bahan->get_data_bahan();

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();

            if ($o['minimal_stok'] == null) {
                $min_stok = 0;
            } else {
                $min_stok = $o['minimal_stok'];
            }

            $tbody[]    = "<div align='center'>".$no.".</div>";
            $tbody[]    = $o['nama_bahan'];
            $tbody[]    = $o['kategori'];
            $tbody[]    = $o['satuan'];
            $tbody[]    = $min_stok;
            $tbody[]    = "<span style='cursor:pointer' class='mr-3 text-primary edit-bahan' data-toggle='tooltip' data-placement='top' title='Ubah' data-id='".$o['id_bahan']."'><i class='fa fa-edit fa-lg'></i></span><span style='cursor:pointer' class='text-danger hapus-bahan' data-toggle='tooltip' data-placement='top' title='Hapus' data-id='".$o['id_bahan']."'><i class='fa fa-trash fa-lg'></i></span>";
            $data[]     = $tbody;
        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->bahan->jumlah_semua_bahan(),
                    "recordsFiltered"  => $this->bahan->jumlah_filter_bahan(),   
                    "data"             => $data
                ];

        echo json_encode($output);
    }

    // aksi proses simpan data bahan
    public function simpan_data_bahan()
    {
        $aksi           = $this->input->post('aksi_bahan');
        $id_bahan       = $this->input->post('id_bahan');
        $id_product     = $this->input->post('id_product');
        $nm_bahan       = $this->input->post('nama_bahan');
        $id_kat         = $this->input->post('kategori');
        $satuan         = $this->input->post('satuan');
        $minimal_stok   = $this->input->post('minimal_stok');

        $data = ['id_user'          => $this->session->userdata('id_user'),
                 'nama_product'     => $nm_bahan,
                 'id_kategori'      => $id_kat,
                 'satuan'           => $satuan,
                 'status_bahan'     => 1,
                 'status_tampil'    => 0,
                 'created_at'       => date("Y-m-d", now('Asia/Jakarta')),
                ];

        if ($aksi == 'Tambah') {

            $this->bahan->input_data('mst_product', $data);
            $id_pro = $this->db->insert_id();
            
            $data_stk = ['id_product'   => $id_pro,
                         'minimal_stok' => $minimal_stok,
                         'stok'         => 0,
                         'created_at'   => date("Y-m-d H:i:s", now('Asia/Jakarta'))
                        ];

            $this->bahan->input_data('mst_stok', $data_stk);
            
        } elseif ($aksi == 'Ubah') {

            $this->bahan->ubah_data('mst_product', $data, array('id' => $id_bahan));

            $data_stk = ['minimal_stok' => $minimal_stok];

            $this->bahan->ubah_data('mst_stok', $data_stk, array('id_product' => $id_bahan));

        } elseif ($aksi == 'Hapus') {

            $this->bahan->hapus_data('mst_product', array('id' => $id_bahan));
            $this->bahan->hapus_data('mst_stok', array('id_product' => $id_bahan));

        }

        $list_pro = $this->bahan->cari_data('mst_product', ['status_bahan' => 1, 'id_user' => $this->id_user])->result_array();

        $option = "<option selected disabled hidden>--PILIH--</option>";

        foreach ($list_pro as $a) {
            $option .= "<option value='".$a['id']."'>".$a['nama_product']."</option>";
        }

        $data2 = $this->produk->get_bahan_komposisi($id_product)->result_array();

        $option2 = "<option selected disabled hidden>--PILIH--</option>";

        foreach ($data2 as $d) {
            $option2 .= "<option value='".$d['id']."'>".$d['nama_product']."</option>";
        }

        echo json_encode(['dt' => $data, 'product' => $option, 'list_bahan' => $option2]);
    }

    // 07-08-2020
    public function ambil_list_bahan()
    {
        $list_pro = $this->bahan->cari_data('mst_product', ['status_bahan' => 1, 'id_user' => $this->id_user])->result_array();

        $option = "<option selected disabled hidden>--PILIH--</option>";

        foreach ($list_pro as $a) {
            $option .= "<option value='".$a['id']."'>".$a['nama_product']."</option>";
        }
        
        echo json_encode(['product' => $option]);
    }

    // ambil data bahan
    public function ambil_data_bahan($id_bahan)
    {
        $data = $this->bahan->cari_data('mst_product', array("id" => $id_bahan))->row_array();

        $dt = $this->bahan->cari_data('mst_stok', array("id_product" => $data['id']))->row_array();

        array_push($data, ['minimal_stok' => $dt['minimal_stok']]);

        echo json_encode($data);
    }

    // 05-08-2020
    public function report()
    {
        $data 	= [
			'title'		=> 'Stok',
            'isi'		=> 'report/stok',
            'stok'      => $this->bahan->get_data_order('mst_stok', 'id', 'asc')->result(),
		];
		$this->load->view('template/wrapper', $data);
    }

    // menampilkan list report stok 
    public function tampil_report_stok()
    {
        $awal   = $this->input->post('tanggal_awal');
        $akhir  = $this->input->post('tanggal_akhir');
        
        $list = $this->bahan->get_data_report_stok($awal, $akhir);

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();

            if ($o['stok'] == 0) {
                $css        = "style='cursor:pointer; pointer-events: none;'";
                $disabled   = 'disabled';
            } else {
                $css        = "style='cursor:pointer;'";
                $disabled   = '';
            }

            $tbody[]    = "<div align='center'>".$no.".</div>";
            $tbody[]    = $o['nama_product'];
            $tbody[]    = $o['stok'];
            $tbody[]    = "<span $css class='btn btn-sm btn-primary detail-stok $disabled' data-id='".$o['id']."' nm_product='".$o['nama_product']."'>Detail</span>";

            // $tbody[]  = '<a href="javascript:void(0)" class="btn btn-sm btn-primary detail-stok" data-toggle="modal" data-target="#detail'.$o['id'].'">Detail</a>';

            $data[]     = $tbody;
        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->bahan->jumlah_semua_report_stok($awal, $akhir),
                    "recordsFiltered"  => $this->bahan->jumlah_filter_report_stok($awal, $akhir),   
                    "data"             => $data
                ];

        echo json_encode($output);
    }

    // 06-08-2020

    // menampilkan list detail stok 
    public function tampil_detail_stok()
    {
        $id_stok    = $this->input->post('id_stok');
        $awal       = $this->input->post('tanggal_awal');
        $akhir      = $this->input->post('tanggal_akhir');
        
        $list = $this->bahan->get_data_detail_stok($id_stok, $awal, $akhir);

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();

            $tbody[]    = "<div align='center'>".$no.".</div>";
            $tbody[]    = $o['barang_masuk'];
            $tbody[]    = $o['barang_keluar'];
            $tbody[]    = $o['barang_retur'];
            $tbody[]    = nice_date($o['created_at'], 'd-m-Y');
            $data[]     = $tbody;
        }
 
        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->bahan->jumlah_semua_detail_stok($id_stok, $awal, $akhir),
                    "recordsFiltered"  => $this->bahan->jumlah_filter_detail_stok($id_stok, $awal, $akhir),   
                    "data"             => $data   
                  ];

        echo json_encode($output);
    }

    public function view_detail_stok()
    {
        $id_stok    = $this->input->post('id_stok');
        $tgl_awal   = $this->input->post('tgl_awal');
        $tgl_akhir  = $this->input->post('tgl_akhir');

        $nm = $this->bahan->get_nama_product($id_stok)->row_array();
        
        $data = ['nama_product' => $nm['nama_product'],
                 'tgl_awal'     => $tgl_awal,
                 'tgl_akhir'    => $tgl_akhir,
                 'id_stok'      => $id_stok
                ];

        $this->load->view('report/detail_stok', $data);
        
        
    }

    // download file
    public function download_file()
    {
        $tgl_awal   = $this->input->post('tgl_awal');
        $tgl_akhir  = $this->input->post('tgl_akhir');
        $jns        = $this->input->post('jns');

        $this->db->select("DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal");
        $this->db->from('trn_stok');
        $this->db->order_by('created_at', 'asc');
        $this->db->limit(1);
        $tgl = $this->db->get()->row_array();

        if ($tgl_awal != '') {
            $tgl_awal = nice_date($tgl_awal, 'Y-m-d');
        } else {
            $tgl_awal = $tgl['tanggal'];
        }

        if ($tgl_akhir != '') {
            $tgl_akhir = nice_date($tgl_akhir, 'Y-m-d');
        } else {
            $tgl_akhir = date("Y-m-d", now('Asia/Jakarta'));
        }

        $data   = [ 'report'        => 'Report Stok',
                    'tgl_awal'      => $tgl_awal,
                    'tgl_akhir'     => $tgl_akhir,
                    'jns'           => $jns,
                    'judul'         => 'Report Stok',
                    'd_stok'        => $this->bahan->get_report_stok($tgl_awal, $tgl_akhir)->result_array()
                  ]; 

        if ($jns == 'excel') {

            $temp = 'template/template_excel';
            $this->template->load("$temp", 'report/V_export_stok', $data);

        } else {
            // $temp = 'template/template_pdf';
            // $this->template->load("$temp", 'report/V_export_stok', $data);

            ob_start();
            $this->load->view('report/V_export_stok', $data);
            $html = ob_get_contents();
            // var_dump($html);die();
                ob_end_clean();
                require_once('./assets/html2pdf/html2pdf.class.php');
            $pdf = new HTML2PDF('P','A4','en', false, 'ISO-8859-15',array(10, 10, 10, 10));
            $pdf->WriteHTML($html);
            $pdf->Output('Laporan Stok.pdf', 'I');
        } 
        
    }
}

/* End of file Bahan.php */
