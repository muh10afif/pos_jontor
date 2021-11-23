<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

    public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('username') == "")
        {
            $this->session->set_flashdata('danger', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>Anda belum Log in</div>');
            redirect(base_url(), 'refresh');
        }
	}

	public function tes()
	{
		
		$list = [];

		$data = ['product'		=> "sd",
				 'harga'		=> "112",
				 'qty'			=> "2323",
				 'total'		=> "23"
				];

		array_push($list, $data);
		
		echo "<pre>";
		print_r($list);
		echo "</pre>";
	}

	public function index()
	{
		// cari id transaksi yg kd transaksi kosong
		// $list_id = $this->transaksi->get_data_kdtr_kosong()->result_array();

		// hapus id transaksi kosong pd detail
		// foreach ($list_id as $d) {
		// 	$this->transaksi->hapus_data('trn_detail_transaksi', ['id_transaksi' => $d['id']]);
		// 	$this->transaksi->hapus_data('trn_transaksi', ['id' => $d['id']]);
		// }

		if ($this->session->userdata('id_role') == 2 ) {
			$id_u = $this->session->userdata('id_user');
		} else {
			$id_u = $this->session->userdata('id_owner');
		}

		$kategori = $this->db->query('SELECT DISTINCT mst_kategori.id, mst_kategori.kategori, mst_kategori.created_at FROM mst_kategori INNER JOIN mst_product ON mst_kategori.id = mst_product.id_kategori WHERE mst_product.id_user = '.$id_u.'LIMIT 1')->result_array();
		$data 	= [
			'title'		=> 'Transaksi',
			// 'isi'		=> 'transaksi/lihat',
			'isi'		=> 'transaksi/lihat_tr',
			// 'kategori'	=> $this->transaksi->get_data_order('mst_kategori', 'id', 'DESC')->result_array()
			'kategori'	=> $kategori
		];
		$this->load->view('template/wrapper', $data);
	}

	public function tampil_data_list()
	{
		$id_tr = $this->input->post('id_tr');

		if ($id_tr != "") {

			$list = $this->transaksi->cari_data_tr($id_tr)->result_array();

			foreach ($list as $v) {
				$tbody = array();

				// $tbody[] = "<div width='5%'><label style='cursor:pointer' class='badge badge-warning ubah-jml' aksi='min' data-id='".$v['id_d_tr']."' harga='".$v['harga']."'>-</label> <label class='badge badge-danger'>".$v['jumlah']."</label> <label style='cursor:pointer' class='badge badge-warning ubah-jml' aksi='tambah' data-id='".$v['id_d_tr']."' harga='".$v['harga']."'>+</label></div>";

				$st = $this->transaksi->cari_data('mst_stok', ['id_product' => $v['id_product']])->row_array();

				$tbody[] = wordwrap($v['nama_product'],10,"<br>\n");
				$tbody[] = "<div class='text-right'>Rp. ".number_format($v['harga'],0,'.','.')."</div>";
				// $tbody[] = "<input type='text' class='form-control input text-center' value='".$v['jumlah']."'>";
				$tbody[] = "<label class='badge badge-danger'>".$v['jumlah']."</label>";
				// $tbody[] = "<div><input type='text' class='form-control input2 text-right' value='".$v['total_discount']."'></div>";
				$tbody[] = "Rp. ".number_format($v['total_discount'],0,'.','.');
				$tbody[] = "<div class='text-right'>Rp. ".number_format($v['subtotal'],0,'.','.')."</div>";
				$tbody[] = "<div><span style='cursor:pointer' class='text-success ubah-list mr-3' data-toggle='tooltip' data-placement='top' title='Edit' stok='".$st['stok']."' harga='".$v['harga']."' diskon='".$v['discount']."' product='".$v['nama_product']."' data-id='".$v['id_d_tr']."'><i class='fa fa-pencil-alt'></i></span><span style='cursor:pointer' class='text-danger hapus-list' data-toggle='tooltip' data-placement='top' title='Hapus' data-id='".$v['id_d_tr']."'><i class='fa fa-trash-alt'></i></span></div>";
				$data[]  = $tbody; 
			}

			if ($list) {
				echo json_encode(array('data'=> $data, 'id_tr' => $id_tr));
			} else {
				echo json_encode(array('data'=> 0, 'id_tr' => $id_tr));
			}
			
		}else{
			echo json_encode(array('data'=> 0, 'id_tr' => $id_tr));
		}
	}

	function coba_tr() 
	{
		$this->db->trans_begin();

		$this->transaksi->input_data('trn_transaksi', ['total_harga' => 2]);

		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();

				echo "gagal";
		}
		else
		{
				$this->db->trans_commit();

				echo "ok";
		}
	}

	public function simpan_list()
	{
		$this->db->trans_begin();

		$id_product = $this->input->post('id_product');
		$id_tr 		= $this->input->post('id_tr');
		$id_d_tr	= $this->input->post('id_d_tr');

		$cari_0 = $this->transaksi->cari_data('mst_product', ['id' => $id_product])->row_array();

		// cari data transksi dan product
		$cari_tr = $this->transaksi->cari_data('trn_detail_transaksi', ['id_transaksi' => $id_tr]);

		// simpan tr_transaksi
		$data_1 = ['id_user'		=> $this->session->userdata('id_user'),
				   'kode_transaksi'	=> "",
				   'total_harga'	=> 0,
				   'created_at'		=> date("Y-m-d", now('Asia/Jakarta'))
				  ];

		if ($cari_tr->num_rows() == 0) {
			$this->transaksi->input_data('trn_transaksi', $data_1);
			$id_tr = $this->db->insert_id();
		} else {
			$this->transaksi->ubah_data('trn_transaksi', $data_1, ['id' => $id_tr]);
		}

		// cari data transksi dan product
		$cari_1 = $this->transaksi->cari_data('trn_detail_transaksi', ['id_transaksi' => $id_tr, 'id_product' => $id_product]);

		$cr = $cari_1->row_array();

		if ($cari_1->num_rows() > 0) {
			$jml = $cr['jumlah'] + 1;
		} else {
			$jml = 1;
		}

		// cari jumlah discount yang ada sebelumnya
		$cr_dis = $this->transaksi->get_diskon_sbl($id_tr, $id_product)->row_array();

		// cari type diskon
		$jns = $this->transaksi->cari_data('mst_discount', ['id_product' => $id_product])->row_array();

		if ($jns['satuan'] == 'Harga') {
			// cari diskon per product
			$cr_pro = $this->transaksi->jml_diskon($id_product)->row_array();
			

			// $tot_dis = $cr_pro['discount'] * $jml;
			$tot_dis = ($cr_pro['discount'] * $jml);
		} else {
			$nilai_diskon = $jns['discount'];

			//Nilai persen = [Nilai Persen] x [Nilai Pecahan] : [100]

			$tt_dis  = ($nilai_diskon * $cari_0['harga']) / 100;
			$tot_dis = $tt_dis * $jml;
		}

		$data_2 = ['id_transaksi'	=> $id_tr,
				   'id_product'		=> $id_product,
				   'jumlah'			=> $jml,
				   'total_discount'	=> $tot_dis,
				   'subtotal'		=> ($cari_0['harga'] * $jml) - $tot_dis,
				   'created_at'		=> 	date("Y-m-d", now('Asia/Jakarta'))
				  ];

		$data_3 = ['jumlah'			=> $jml,
				   'total_discount'	=> $tot_dis,
				   'subtotal'		=> ($cari_0['harga'] * $jml) - $tot_dis
				  ];

		if ($cari_1->num_rows() == 0) {
			$this->transaksi->input_data('trn_detail_transaksi', $data_2);
			$id_d_tr = $this->db->insert_id();
		} else {
			$this->transaksi->ubah_data('trn_detail_transaksi', $data_3, ['id' => $cr['id']]);	
		}

		// mencari total harga
		$tot = $this->transaksi->get_total_pesanan($id_tr)->row_array();

		$subtotal = "Rp. ".number_format($tot['subtotal'],0,'.','.');

		// mencari diskon
		$dis = $this->transaksi->get_diskon_2($id_tr)->row_array();

		$dis_text = "Rp. ".number_format($dis['total_discount'],0,'.','.');

		$tb = $tot['subtotal'];

		$total_bayar = "Rp. ".number_format($tb,0,'.','.');

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			echo json_encode(['status' => 'Gagal']);
		}
		else
		{
			$this->db->trans_commit();

			echo json_encode(
				['status' 		=> 'Sukses', 
				 'id_transaksi' => $id_tr, 
				 'id_d_tr' 		=> $id_d_tr, 
				 'total' 		=> $subtotal,
				 'diskon'		=> $dis_text,
				 'tot_bayar'	=> $total_bayar,
				 'tot_tr'		=> $tb,
				 'satuan'		=> $jns['satuan'],
				 'diskon_pro'	=> $jns['discount']
				]);
		}
	}

	public function simpan_data_list()
	{
		$id_d_tr = $this->input->post('id_d_tr');
		$id_tr 	 = $this->input->post('id_tr');
		$harga 	 = $this->input->post('harga');
		$aksi	 = $this->input->post('aksi');

		
		if ($aksi == 'Hapus') {
			$this->transaksi->hapus_data('trn_detail_transaksi', ['id' => $id_d_tr]);
		} else {
			$cr = $this->transaksi->cari_data('trn_detail_transaksi', ['id' => $id_d_tr])->row_array();

			if ($aksi == 'min') {
				$data = ['jumlah' 	=> $cr['jumlah'] - 1,
						 'subtotal'	=> $harga * ($cr['jumlah'] - 1)
						];
			} else {
				$data = ['jumlah' 	=> $cr['jumlah'] + 1,
						 'subtotal'	=> $harga * ($cr['jumlah'] + 1)
						];
			}

			if ($data['subtotal'] == 0) {
				$this->transaksi->hapus_data('trn_detail_transaksi', ['id' => $id_d_tr]);
			} else {
				$this->transaksi->ubah_data('trn_detail_transaksi', $data, ['id' => $id_d_tr]);		
			}
				
		}

		// mencari total harga
		$tot = $this->transaksi->get_total_pesanan($id_tr)->row_array();

		$subtotal = "Rp. ".number_format($tot['subtotal'],0,'.','.');

		// mencari diskon
		$dis = $this->transaksi->get_diskon_2($id_tr)->row_array();

		$dis_text = "Rp. ".number_format($dis['total_discount'],0,'.','.');

		$tb = $tot['subtotal'];

		$total_bayar = "Rp. ".number_format($tb,0,'.','.');

		echo json_encode(
			['status' 		=> TRUE, 
			 'total' 		=> $subtotal, 
			 'tot'			=> $tot['subtotal'],
			 'diskon'		=> $dis_text,
			 'tot_bayar'	=> $total_bayar
			]);
	}

	public function ambil_data_list()
	{
		$id_d_tr = $this->input->post('id_d_tr');

		$cr_1 = $this->transaksi->cari_data('trn_detail_transaksi', ['id' => $id_d_tr])->row_array();

		// cari type diskon
		$jns = $this->transaksi->cari_data('mst_discount', ['id_product' => $cr_1['id_product']])->row_array();
		$hrg = $this->transaksi->cari_data('mst_product', ['id' => $cr_1['id_product']])->row_array();

		$data = ['jumlah'			=> $cr_1['jumlah'],
				 'total_discount'	=> $cr_1['total_discount'],
				 'id'				=> $cr_1['id'],
				 'harga'			=> $hrg['harga'],
				 'satuan'			=> $jns['satuan'],
				 'diskon_pro'		=> $jns['discount']
				];

		echo json_encode($data);
	}

	public function simpan_data()
	{
		$id_d_tr 		= $this->input->post('id_d_tr');
		$jumlah  		= $this->input->post('jumlah');
		$jumlah_lama  	= $this->input->post('jumlah_lama');
		$diskon  		= $this->input->post('nilai_diskon');

		// cari harga
		$cr_1 = $this->transaksi->cari_data('trn_detail_transaksi', ['id' => $id_d_tr])->row_array();
		$cr_2 = $this->transaksi->cari_data('mst_product', ['id' => $cr_1['id_product']])->row_array();
		$cr_3 = $this->transaksi->cari_data('mst_discount', ['id_product' => $cr_1['id_product']])->row_array();

		if ($jumlah != $jumlah_lama) {

			if ($cr_3['satuan'] == 'Harga') {
				// cari diskon per product
				$cr_pro = $this->transaksi->jml_diskon($cr_1['id_product'])->row_array();
				
			
				// $tot_dis = $cr_pro['discount'] * $jml;
				$dis = ($cr_pro['discount'] * $jumlah);
			} else {
				$nilai_diskon = $cr_3['discount'];
			
				//Nilai persen = [Nilai Persen] x [Nilai Pecahan] : [100]
			
				$tt_dis  = ($nilai_diskon * $cr_2['harga']) / 100;
				$dis = $tt_dis * $jumlah;
			}

		} else {
			$dis = $diskon;
		}
		
		$data = ['jumlah'			=> $jumlah,
				 'total_discount'	=> $dis,
				 'subtotal' 		=> ($cr_2['harga'] * $jumlah) - $dis
				];

		// ubah data
		$this->transaksi->ubah_data('trn_detail_transaksi', $data, ['id' => $id_d_tr]);

		$id_tr = $cr_1['id_transaksi'];

		// mencari total harga
		$tot = $this->transaksi->get_total_pesanan($id_tr)->row_array();

		$subtotal = "Rp. ".number_format($tot['subtotal'],0,'.','.');

		// mencari diskon
		$dis = $this->transaksi->get_diskon_2($id_tr)->row_array();

		$dis_text = "Rp. ".number_format($dis['total_discount'],0,'.','.');

		$tb = $tot['subtotal'];

		$total_bayar = "Rp. ".number_format($tb,0,'.','.');

		echo json_encode(
			['status' 		=> TRUE, 
			 'total' 		=> $subtotal, 
			 'tot'			=> $tot['subtotal'],
			 'diskon'		=> $dis_text,
			 'tot_bayar'	=> $total_bayar
			]);
	}

	public function test()
	{
		// Output: 36e5e490f14b031e
		// echo substr(strtoupper(md5(time())), 0, 5);

		$d = date('dmy');
		$e = "100720";

		if (strtotime($d) > strtotime($e)) {
			$kode = 1;
		} else {
			$kode = 2;
		}

		$kodemax = str_pad($kode, 5, "0", STR_PAD_LEFT);

		$a = "TRN$d$kodemax";

		$b = substr($a, 9, 7) + 3;

		$cari_tgl = $this->transaksi->cari_data_kd_tr('trn_transaksi', ['id_user' => 1])->row_array(1);

		print_r($cari_tgl['kode_transaksi']);
	}

	// 23-07-2020
	public function add_row()
	{
		$id_product 	= $this->input->post('id_product');
		$product 		= $this->transaksi->cari_data('mst_product', ['id' => $id_product])->row();

		// cari stok
		$st = $this->transaksi->cari_data('mst_stok', ['id_product' => $id_product])->row_array();

		// cari type diskon
		$jns = $this->transaksi->cari_data('mst_discount', ['id_product' => $id_product])->row_array();

		if ($jns['satuan'] == 'Harga') {
			// cari diskon per product
			$cr_pro = $this->transaksi->jml_diskon($id_product)->row_array();
			

			// $tot_dis = $cr_pro['discount'] * $jml;
			// $tot_dis = ($cr_pro['discount']);
			$tot_dis = ($jns['discount']);
		} else {
			$nilai_diskon = $jns['discount'];

			//Nilai persen = [Nilai Persen] x [Nilai Pecahan] : [100]

			$tt_dis  = ($nilai_diskon * ($product->harga)) / 100;
			$tot_dis = $tt_dis;
		}

		$nama_product	= $product->nama_product;
		$id_product		= $product->id;
		$diskon 		= 0;
		$harga 			= $product->harga;
		$total 			= "Rp. ".number_format($harga,0,'.','.');
		$total_diskon 	= $tot_dis;

		echo json_encode([
			'status' 		=> 'Sukses', 
			'id_product'	=> $id_product,
			'nama_product'	=> $nama_product,
			'total' 		=> $total,
			'diskon'		=> $diskon,
			'total_diskon'	=> $total_diskon,
			'tot_bayar'		=> $total,
			'tot_tr'		=> $harga,
			'harga'			=> $harga,
			'dis_produk'	=> $tot_dis,
			'satuan'		=> $jns['satuan'],
			'stok'			=> $st['stok']
		]);
	}

	// 23-09-2020
    public function get_file_json()
    {
        // File json yang akan dibaca
        $file = "data_transaksi.json";

        // Mendapatkan file json
        $dt = file_get_contents($file);

        $data = json_decode($dt, true);

        return $data;
    }

	// 23-09-2020get_file_json
    public function put_file_json($data)
    {
        $jsonfile = json_encode($data, JSON_PRETTY_PRINT);

        file_put_contents('data_transaksi.json', $jsonfile);
    }

	// 23-09-2020
	public function add_row_tr()
	{
		$id_product = $this->input->post('id_product'); 
		$harga 		= $this->input->post('harga'); 
		$diskon 	= $this->input->post('diskon'); 
		$nm_produk 	= $this->input->post('nm_produk');

		$dt = $this->get_file_json();

		foreach ($dt as $key => $d) {
			
			if ($d['id_produk'] === $id_product) {
				$a = 'sama';

				break;
			} else {
				$a = 'beda';
			}

		}  

		if ($a == 'sama') {
			foreach ($dt as $key => $d) {
				$q 		= $d['qty'];
				$tt_q	= $q + 1;
	
				$ttl 	= ($harga * $tt_q) - ($diskon * $tt_q);
				$dis 	= $diskon * $tt_q;
				
				if ($d['id_produk'] === $id_product) {
					$dt[$key]['id_produk']	= $id_product;
					$dt[$key]['nama_produk']= $nm_produk;
					$dt[$key]['harga']      = $harga;
					$dt[$key]['qty']   		= "$tt_q";
					$dt[$key]['diskon']   	= "$dis";
					$dt[$key]['total']      = "$ttl";
				}
			}  
		} else {
			$total = ($harga * 1) - ($diskon * 1);

			$dt[] = ['id_produk'	=> $id_product,
					 'nama_produk'	=> $nm_produk,
					 'harga'		=> $harga,
					 'qty'			=> "1",
					 'diskon'		=> $diskon,
					 'total'		=> "$total"
					];
		}

		$this->put_file_json($dt);
		
		$fl = $this->get_file_json();

		
		foreach ($fl as $key => $v) {
            if ($v['qty'] == 0) {
                array_splice($fl, $key, 1);
            }
        }

		$data = ['id_produk' => $id_product,
				 'list'		 => $fl
				];
		
		$this->load->view('transaksi/list_tr', $data);
		
	}

	// 11-07-2020
	public function simpan_transaksi()
	{
		$post 				= $this->input->post();
		$total_harga		= $post['total_harga'];
		$total_diskon		= $post['total_diskon'];
		$nama_product		= $post['nama_product'];
		$jumlah				= $post['jumlah'];
		$discount			= $post['discount'];
		$subtotal			= $post['subtotal'];
		$nm_meja			= $post['nomor_meja'];
		$tunai				= $post['tunai'];
		$pot_harga			= $post['pot_harga'];
		$tanggal 			= date('Y-m-d');
		$default_tanggal	= date('dmy', now('Asia/Jakarta'));
		$jumlah_transaksi 	= $this->transaksi->cari_data('trn_transaksi', ['created_at' => $tanggal])->num_rows();
		if($jumlah < 1)
		{
			$kode = "1";
		}
		else
		{
			//$transaksi 			= $this->transaksi->cari_data('trn_transaksi', ['created_at' => $tanggal])->row_array();
			if ($this->session->userdata('id_role') == 2 ) {
				$id_user = $this->session->userdata('id_user');
			} else {
				$id_user = $this->session->userdata('id_owner');
			}

			$transaksi = $this->transaksi->cari_data_kd_tr('trn_transaksi', ['id_user' => $id_user])->row_array();

			$bagian_tanggal 	= substr($transaksi['kode_transaksi'], 3, 6);
			$bagian_urutan 		= substr($transaksi['kode_transaksi'], 9, 7);
			
			if($default_tanggal == $bagian_tanggal)
			{
				$kode = $bagian_urutan + 1;
			}
			else
			{
				$kode = '1';
			}
		}
		$generated_code		= str_pad($kode, 5, '0', STR_PAD_LEFT);
		$kode_transaksi		= "TRN$default_tanggal$generated_code";

		$id_o = $this->session->userdata('id_owner');

		if ($id_o == 0) {
			$id_u = $this->session->userdata('id_user');
		} else {
			$id_u = $id_o;
		}

		$data_trn_transaksi	= [
			'id_user'			=> $id_u,
			'kode_transaksi'	=> $kode_transaksi,
			'total_harga' 		=> $total_harga,
			'nomer_meja'		=> $nm_meja,
			'tunai'				=> $tunai,
			'potongan_harga'	=> $pot_harga,
			'created_by'		=> $this->session->userdata('id_user'),
			'created_at'		=> date("Y-m-d H:i:s", now('Asia/Jakarta'))
		];

		$this->db->insert('trn_transaksi', $data_trn_transaksi);
		$id_transaksi		= $this->db->insert_id();
		$batas_array 		= count($nama_product);

		for($i = 0; $i < $batas_array; $i++)
		{
			$row 						= [];
			$produk						= $this->transaksi->cari_data('mst_product', ['nama_product' => $nama_product[$i]])->row();
			$id_product 				= $produk->id;

			$data_trn_detail_transaksi	= [
					'id_transaksi'		=> $id_transaksi,
					'id_product'		=> $id_product,
					'jumlah'			=> $jumlah[$i],
					'total_discount'	=> $discount[$i],
					'subtotal'			=> $subtotal[$i],
					'created_at'		=> date("Y-m-d H:i:s", now('Asia/Jakarta'))
				];

			$this->transaksi->input_data('trn_detail_transaksi', $data_trn_detail_transaksi);


			// cari data di product
			$pro1 = $this->transaksi->cari_data('mst_stok', ['id_product' => $id_product]);

			$pro = $pro1->row_array();

			if ($pro1->num_rows() > 0) {

				// input ke trn stok
				$data_trn_stok = ['id_stok'		 => $pro['id'],
								  'barang_masuk' => 0,
								  'barang_keluar'=> $jumlah[$i],
								  'barang_retur' => 0,
								  'created_at'	 => date("Y-m-d H:i:s", now('Asia/Jakarta'))
								 ];
				
				$this->transaksi->input_data('trn_stok', $data_trn_stok);
				
				// update ke mst stok
				$this->transaksi->ubah_data('mst_stok', ['stok' => ($pro['stok'] - $jumlah[$i])], ['id' => $pro['id']]);

			}

			// cari id product di trn stk
			$cari_id_pro = $this->transaksi->cari_data('trn_komposisi', ['id_product' => $id_product])->result_array();
				
			foreach ($cari_id_pro as $cp) {

				// cari stok
				$crs = $this->transaksi->cari_data('mst_stok', ['id_product' => $cp['id_bahan']])->row_array();
				
				$this->transaksi->ubah_data('mst_stok', ['stok' => $crs['stok'] - ($cp['nilai_komposisi'] * $jumlah[$i])], ['id_product' => $cp['id_bahan']]);

				// input ke barang keluar
				$dt_trn_stok = [  'id_stok'		 => $crs['id'],
								  'barang_masuk' => 0,
								  'barang_keluar'=> ($cp['nilai_komposisi'] * $jumlah[$i]),
								  'barang_retur' => 0,
								  'created_at'	 => date("Y-m-d H:i:s", now('Asia/Jakarta'))
								 ];
				
				$this->transaksi->input_data('trn_stok', $dt_trn_stok);

			}

		}

		echo json_encode(['status' => TRUE, 'id_tr' => $id_transaksi]);
	}

	// 13-02=7-2020
	// public function cetak_nota($id_transaksi)
	// {	
	// 	$data = ['id_transaksi'	=> $id_transaksi,
	// 			 'transaksi'	=> $this->transaksi->cari_data('trn_transaksi', ['id' => $id_transaksi])->row_array(),
	// 			 'det_tr'		=> $this->transaksi->cari_detail_tr($id_transaksi)->result_array()
	// 			];

	// 	$this->load->view('Transaksi/cetak', $data);
		
	// }

	// 14-07-2020
	public function kirim_email(){
		//pengaturan email
		$this->load->library('email');//panggil library email codeigniter

		$email = $this->input->post('email');
		$id_tr = $this->input->post('id_tr');

		$config = array(
			'protocol' 	=> 'smtp',
			'smtp_host' => 'smtp.hostinger.co.id',
			'smtp_port' => 587,
			// 'smtp_host' => 'ssl://smtp.googlemail.com',
        	// 'smtp_port' => 465,
			'smtp_user' => 'info@mitrabagja.com',//alamat email gmail
			'smtp_pass' => 'Mitrabagja777',//password email
			'mailtype' 	=> 'html',
			'charset' 	=> 'utf-8',
			'wordwrap' 	=> TRUE
		);

		$data = ['tr'		=> $this->transaksi->cari_data('trn_transaksi', ['id' => $id_tr])->row_array(),
				 'det_tr' 	=> $this->transaksi->cari_detail_tr($id_tr)->result_array(),
				 'kat' 		=> $this->transaksi->get_kategori($id_tr)->result_array(),
				 'id_tr'	=> $id_tr,
				 'dis_tr' 	=> $this->transaksi->get_diskon_tr($id_tr)->row_array()
				];
	
		$this->email->initialize($config);
		$this->email->set_newline("\r\n");
		$this->email->from($config['smtp_user'], 'Bagja Indonesia');
		$this->email->to($email);
		$this->email->subject('Bukti Transaksi');//subjek email
		$message = $this->load->view('transaksi/temp_email.php',$data,TRUE);
		$this->email->message($message);

		
		//proses kirim email
		if($this->email->send()){
			// echo "sukses";

			// $this->session->set_flashdata('message','Sukses kirim email');

			echo json_encode(['status' => "OK", 'error' => ""]);
		}
		else{

			$err = $this->email->print_debugger();

			echo json_encode(['status' => "gagal", 'error' => $err]);
		}
	}

	public function tes_dir()
	{
		$gambar = "\shyo_print.png";

		$path = __DIR__;

		$path2 = str_replace("application\controllers","",$path);

		echo $path2."assets\img$gambar";
	}

	public function cetak_nota2() {
        // me-load library escpos
		$this->load->library('escpos');
 
        // membuat connector printer ke shared printer bernama "printer_a" (yang telah disetting sebelumnya)
        $connector 	= new Escpos\PrintConnectors\WindowsPrintConnector("Printer-POS-58-2");
 
        // membuat objek $printer agar dapat di lakukan fungsinya
		$printer 	= new Escpos\Printer($connector);
		
		$printer->initialize();
		$printer->selectPrintMode(Escpos\Printer::MODE_EMPHASIZED);
		$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
		$printer->selectPrintMode(Escpos\Printer::MODE_DOUBLE_HEIGHT);
		$printer->text("SHYO FOOD HOUSE\n");
		
        /* ---------------------------------------------------------
         * Menyelesaikan printer
         */
        $printer->feed(4); // mencetak 2 baris kosong, agar kertas terangkat ke atas
		$printer->close();
		
	}

	public function tes_fungsi()
	{
		$v = (20 * 25000) / 100;

		$v = (5000/25000) * 100;

		echo $v;
	}

	// 27-09-2020
	public function cetak_transaksi($post)
	{

		$total_harga		= str_replace('.','', $post['total_harga']);
		$total_diskon		= str_replace('.','', $post['total_diskon']);
		$nama_product		= $post['nm_produk'];
		$harga_list			= str_replace('.','', $post['harga_list']);
		$qty_list			= $post['qty_list'];
		$diskon_list		= str_replace('.','', $post['diskon_list']);
		$subtotal_list		= str_replace('.','', $post['subtotal_list']);
		$nm_meja			= $post['nomor_meja'];
		$tunai				= str_replace('.','', $post['tunai']);
		$pot_harga			= str_replace('.','', $post['pot_harga']);
		$nm_kategori		= $post['nm_kategori'];
		$kategori			= $post['kategori'];
		$kembalian			= str_replace('.','', $post['kembalian']);
		$id_produk			= $post['id_produk'];
		$tanggal 			= date('Y-m-d');
		$default_tanggal	= date('dmy', now('Asia/Jakarta'));

		// untuk print

			// me-load library escpos
			$this->load->library('escpos');

			// membuat connector printer ke shared printer bernama "printer_a" (yang telah disetting sebelumnya)
			$connector 	= new Escpos\PrintConnectors\WindowsPrintConnector("Printer-POS-58-2");

			// membuat objek $printer agar dapat di lakukan fungsinya
			$printer 	= new Escpos\Printer($connector);

			/* Cut the receipt and open the cash drawer */
			// $printer->cut();
			$printer->pulse();

			$gambar = "\print_ayam_jontor.png";

			$path  = __DIR__;

			$path2 = str_replace("application\controllers","",$path);

			$path3 = $path2."assets\img$gambar";

			$tux = Escpos\EscposImage::load($path3);

			$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
			$printer->bitImageColumnFormat($tux);
			$printer->setReverseColors(true);
			$printer->text("\n");

			$printer->initialize();
			$printer->selectPrintMode(Escpos\Printer::MODE_EMPHASIZED);
			$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
			$printer->selectPrintMode(Escpos\Printer::MODE_DOUBLE_HEIGHT);
			$printer->text("Ayam Goreng Sambal Jontor \n");

			$dt_judul = $this->ambil_string("Raden Rangga Kencana No. 2B, Mekarwangi, Bandung");

			$printer->initialize();
			$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
			foreach ($dt_judul as $d) {
				if($d == '')
				{
					unset($d);
				} else {
					$printer->text($d."\n");
				}
			}
			$printer->text("082130301757 \n");

			$printer->initialize();
			$printer->text("--------------------------------\n \n");

			$printer->initialize();
			$tgl_tr = sprintf('%1$-15s %2$-16s',date("d F Y", now('Asia/Jakarta')), date("H:i:s", now('Asia/Jakarta')));
			$printer->text("$tgl_tr \n");
			$printer->initialize();

			$tt_sb = 0;
			foreach ($nm_kategori as $k) {
				
				$nm_kat = $k;

				$printer->text("--------------------------------\n");
				$printer->text("$nm_kat\n");
				$printer->text("--------------------------------\n");
				$header = sprintf('%1$-8s %2$-7s %3$-7s %4$-7s',"Qty", "Harga", "Diskon", "Total");
				$printer->text("$header\n");
				$printer->text("--------------------------------\n");

				$i = 0;
				foreach ($nama_product as $p) {
					
					if ($nm_kat == $kategori[$i]) {
						// echo $p." ".$post['qty_list'][$i]."x".$post['harga_list'][$i]." (".$post['diskon_list'][$i].") ".$post['subtotal_list'][$i]."<br>";

						$nm_p = $p;

						$printer->text("$nm_p \n");
						$line = sprintf('%1$-8s %2$-7s %3$-7s %4$-7s', "X".number_format($qty_list[$i],0,'.','.'),  number_format($harga_list[$i] - $pot_harga,0,'.','.'), "(".number_format($diskon_list[$i],0,'.','.').")", number_format($subtotal_list[$i],0,'.','.'));
						$printer->text($line);
						$printer->text("\n"); 

						$tt_sb += (($harga_list[$i] - $pot_harga) * $qty_list[$i]);

					}

					// $printer->text("\n");

				$i++;
				}
			}

			$printer->text("\n"); 

			$tot_sb = sprintf('%1$-15s %2$-16s','SUBTOTAL', ": Rp. ".number_format($tt_sb,0,'.','.'));
			$printer->text("$tot_sb\n");
			$t_dis = sprintf('%1$-15s %2$-16s','TOTAL DISKON', ": Rp. ".number_format($total_diskon,0,'.','.'));
			$printer->text("$t_dis\n");
			$printer->text("\n");
			$tot_harga = sprintf('%1$-15s %2$-16s','TOTAL', ": Rp. ".number_format($total_harga,0,'.','.'));
			$printer->text("$tot_harga\n");
			$tunai = sprintf('%1$-15s %2$-16s','TUNAI', ": Rp. ".number_format($tunai,0,'.','.'));
			$printer->text("$tunai\n");
			$kembali = sprintf('%1$-15s %2$-16s','KEMBALI', ": Rp. ".number_format($kembalian,0,'.','.'));
			$printer->text("$kembali\n \n");

			$printer->initialize();
			$printer->text("\n"); 
			$printer->text("--------------------------------\n");
			$printer->text("           TERIMA KASIH         \n");
			$printer->text("--------------------------------\n");
			$printer->text("   Powered By BAGJA INDONESIA   \n");
			$printer->text("--------------------------------\n");

			$this->fun_simpan_transaksi($post);

			/* ---------------------------------------------------------
			* Menyelesaikan printer
			*/
			$printer->feed(4); // mencetak 2 baris kosong, agar kertas terangkat ke atas
			$printer->close();

		// akhir print
	}

	// 27-09-2020
	public function fun_simpan_transaksi($post)
	{
		$total_harga		= str_replace('.','', $post['total_harga']);
		$total_diskon		= str_replace('.','', $post['total_diskon']);
		$nama_product		= $post['nm_produk'];
		$harga_list			= str_replace('.','', $post['harga_list']);
		$qty_list			= $post['qty_list'];
		$diskon_list		= str_replace('.','', $post['diskon_list']);
		$subtotal_list		= str_replace('.','', $post['subtotal_list']);
		$nm_meja			= $post['nomor_meja'];
		$tunai				= str_replace('.','', $post['tunai']);
		$pot_harga			= str_replace('.','', $post['pot_harga']);
		$nm_kategori		= $post['nm_kategori'];
		$kategori			= $post['kategori'];
		$kembalian			= str_replace('.','', $post['kembalian']);
		$id_produk			= $post['id_produk'];
		$tanggal 			= date('Y-m-d');
		$default_tanggal	= date('dmy', now('Asia/Jakarta'));

		// untuk simpan transaksi

			if ($this->session->userdata('id_role') == 2 ) {
				$id_user = $this->session->userdata('id_user');
			} else {
				$id_user = $this->session->userdata('id_owner');
			}

			$transaksi = $this->transaksi->cari_data_kd_tr('trn_transaksi', ['id_user' => $id_user])->row_array();

			$bagian_tanggal 	= substr($transaksi['kode_transaksi'], 3, 6);
			$bagian_urutan 		= substr($transaksi['kode_transaksi'], 9, 7);
			
			if($default_tanggal == $bagian_tanggal)
			{
				$kode = $bagian_urutan + 1;
			}
			else
			{
				$kode = '1';
			}

			$generated_code		= str_pad($kode, 5, '0', STR_PAD_LEFT);
			$kode_transaksi		= "TRN$default_tanggal$generated_code";

			$id_o = $this->session->userdata('id_owner');

			if ($id_o == 0) {
				$id_u = $this->session->userdata('id_user');
			} else {
				$id_u = $id_o;
			}

			$data_trn_transaksi	= [
				'id_user'			=> $id_u,
				'kode_transaksi'	=> $kode_transaksi,
				'total_harga' 		=> $total_harga,
				'nomer_meja'		=> $nm_meja,
				'tunai'				=> $tunai,
				'potongan_harga'	=> $pot_harga,
				'created_by'		=> $this->session->userdata('id_user'),
				'created_at'		=> date("Y-m-d H:i:s", now('Asia/Jakarta'))
			];

			$this->db->insert('trn_transaksi', $data_trn_transaksi);
			$id_transaksi		= $this->db->insert_id();
			$batas_array 		= count($id_produk);	

			for($i = 0; $i < $batas_array; $i++)
			{

				$data_trn_detail_transaksi	= [
						'id_transaksi'		=> $id_transaksi,
						'id_product'		=> $id_produk[$i],
						'jumlah'			=> $qty_list[$i],
						'total_discount'	=> $diskon_list[$i],
						'subtotal'			=> $subtotal_list[$i],
						'created_at'		=> date("Y-m-d H:i:s", now('Asia/Jakarta'))
					];

				$this->transaksi->input_data('trn_detail_transaksi', $data_trn_detail_transaksi);


				// cari data di product
				$pro1 = $this->transaksi->cari_data('mst_stok', ['id_product' => $id_produk[$i]]);

				$pro = $pro1->row_array();

				if ($pro1->num_rows() > 0) {

					// input ke trn stok
					$data_trn_stok = [	'id_stok'		=> $pro['id'],
										'barang_masuk' 	=> 0,
										'barang_keluar'	=> $qty_list[$i],
										'barang_retur' 	=> 0,
										'created_at'	=> date("Y-m-d H:i:s", now('Asia/Jakarta'))
									];
					
					$this->transaksi->input_data('trn_stok', $data_trn_stok);
					
					// update ke mst stok
					$this->transaksi->ubah_data('mst_stok', ['stok' => ($pro['stok'] - $qty_list[$i])], ['id' => $pro['id']]);

				}

				// cari id product di trn stk
				$cari_id_pro = $this->transaksi->cari_data('trn_komposisi', ['id_product' => $id_produk[$i]])->result_array();
					
				foreach ($cari_id_pro as $cp) {

					// cari stok
					$crs = $this->transaksi->cari_data('mst_stok', ['id_product' => $cp['id_bahan']])->row_array();
					
					$this->transaksi->ubah_data('mst_stok', ['stok' => $crs['stok'] - ($cp['nilai_komposisi'] * $qty_list[$i])], ['id_product' => $cp['id_bahan']]);

					// input ke barang keluar
					$dt_trn_stok = ['id_stok'		=> $crs['id'],
									'barang_masuk' 	=> 0,
									'barang_keluar'	=> ($cp['nilai_komposisi'] * $qty_list[$i]),
									'barang_retur' 	=> 0,
									'created_at'	=> date("Y-m-d H:i:s", now('Asia/Jakarta'))
								];
					
					$this->transaksi->input_data('trn_stok', $dt_trn_stok);

				}

			} // end for

			return $id_transaksi;

		// akhir simpan transaksi
	}

	// 27-09-2020
	public function simpan_list_transaksi()
	{
		$post	= $this->input->post();

		$this->cetak_transaksi($post);
		// $id_transaksi = $this->fun_simpan_transaksi($post);

		echo json_encode(['status' => TRUE]);
	}

	public function cetak_nota($id_transaksi, $pot_harga) {

        // me-load library escpos
		$this->load->library('escpos');
		
		$transaksi	= $this->transaksi->cari_data('trn_transaksi', ['id' => $id_transaksi])->row_array();
		$det_tr		= $this->transaksi->cari_detail_tr($id_transaksi)->result_array();
		$dis_tr 	= $this->transaksi->get_diskon_tr($id_transaksi)->row_array();

		$user 		= $this->transaksi->cari_data('mst_user', ['id' => $transaksi['id_user']])->row_array();
 
        // membuat connector printer ke shared printer bernama "printer_a" (yang telah disetting sebelumnya)
        $connector 	= new Escpos\PrintConnectors\WindowsPrintConnector("Printer-POS-58-2");
 
        // membuat objek $printer agar dapat di lakukan fungsinya
		$printer 	= new Escpos\Printer($connector);

		/* Cut the receipt and open the cash drawer */
		// $printer->cut();
		$printer->pulse();

		$id = $this->transaksi->cari_data('mst_user', ['id' => $this->session->userdata('id_user')])->row_array();

		$gb = $id['logo'];
		$nm = $id['nama_umkm'];
		
		$gambar = "\print_".$gb;

		$path  = __DIR__;

		$path2 = str_replace("application\controllers","",$path);

		$path3 = $path2."assets\img$gambar";

		$tux = Escpos\EscposImage::load($path3);

		$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
		$printer->bitImageColumnFormat($tux);
		$printer->setReverseColors(true);
		$printer->text("\n");
		
		$printer->initialize();
		$printer->selectPrintMode(Escpos\Printer::MODE_EMPHASIZED);
		$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
		$printer->selectPrintMode(Escpos\Printer::MODE_DOUBLE_HEIGHT);
		$printer->text("$nm \n");
		
		$dt_judul = $this->ambil_string("Raden Rangga Kencana No. 2B, Mekarwangi, Bandung");
		
        $printer->initialize();
		$printer->setJustification(Escpos\Printer::JUSTIFY_CENTER);
		foreach ($dt_judul as $d) {
			if($d == '')
			{
				unset($d);
			} else {
				$printer->text($d."\n");
			}
		}
        $printer->text("082130301757 \n");
		
		$printer->initialize();
		$printer->text("--------------------------------\n \n");

		$printer->initialize();
		$kd_tr = sprintf('%1$-15s %2$-16s',date("d F Y", now('Asia/Jakarta')), date("H:i:s", now('Asia/Jakarta')));
		$printer->text("$kd_tr\n");
		$printer->initialize();
		// kategori makanan
		$kat = $this->transaksi->get_kategori($id_transaksi)->result_array();

		$tt_sb = 0;
		foreach ($kat as $k) {

			$nm_kat = $k['kategori'];
			$id_kat = $k['id_kategori'];

			$printer->text("--------------------------------\n");
			$printer->text("$nm_kat\n");
			$printer->text("--------------------------------\n");
			$header = sprintf('%1$-8s %2$-7s %3$-7s %4$-7s',"Qty", "Harga", "Diskon", "Total");
			$printer->text("$header\n");
			$printer->text("--------------------------------\n");

			// cari product
			$pro = $this->transaksi->get_product_kat($id_kat, $id_transaksi)->result_array();

			foreach ($pro as $d) {
				$nm_p = $d['nama_product'];

				$printer->text("$nm_p\n");
				$line = sprintf('%1$-8s %2$-7s %3$-7s %4$-7s', "X".$d['jumlah'],  number_format($d['harga'] - $pot_harga,0,'.','.'), number_format($d['total_discount'],0,'.','.'), number_format($d['subtotal'],0,'.','.'));
				$printer->text($line);
				$printer->text("\n"); 

				$tt_sb += (($d['harga'] - $pot_harga) * $d['jumlah']);
			}
			$printer->text("\n");
		}

		$printer->text("\n"); 

		$tot_sb = sprintf('%1$-15s %2$-16s','SUBTOTAL', ": ".number_format($tt_sb,0,'.','.'));
		$printer->text("$tot_sb\n");
		$t_dis = sprintf('%1$-15s %2$-16s','TOTAL DISKON', ": ".number_format($dis_tr['total_discount'],0,'.','.'));
		$printer->text("$t_dis\n");
		$printer->text("\n");
		$tot_harga = sprintf('%1$-15s %2$-16s','TOTAL', ": ".number_format($transaksi['total_harga'],0,'.','.'));
		$printer->text("$tot_harga\n");
		$tunai = sprintf('%1$-15s %2$-16s','TUNAI', ": ".number_format($transaksi['tunai'],0,'.','.'));
		$printer->text("$tunai\n");
		$kembali = sprintf('%1$-15s %2$-16s','KEMBALI', ": ".number_format($transaksi['tunai'] - $transaksi['total_harga'],0,'.','.'));
		$printer->text("$kembali\n \n");

		$printer->initialize();
		$printer->text("\n"); 
		$printer->text("--------------------------------\n");
		$printer->text("           TERIMA KASIH         \n");
		$printer->text("--------------------------------\n");
		$printer->text("   Powered By BAGJA INDONESIA   \n");
		$printer->text("--------------------------------\n");
 
        /* ---------------------------------------------------------
         * Menyelesaikan printer
         */
        $printer->feed(4); // mencetak 2 baris kosong, agar kertas terangkat ke atas
		$printer->close();
		
		echo json_encode(['status' => "Berhasil cetak struk"]);
	}

	public function get_text($text)
	{
		// panjang text awal
		$text = $text." ";
		$lng = strlen($text);

		// panjang text kertas
		if ($lng < 32) {
			$num_char = $lng;
		} else {
			$num_char = 32;
		}

        // memotong yang kata yang terpotong
        $char     = $text{$num_char - 1};
        while($char != ' ') {
            $char = $text{--$num_char}; // Cari spasi pada posisi 49, 48, 47, dst...
		}
		$str_1 = substr($text, 0, $num_char);

		return $str_1;
	}
	
	public function ambil_string($text)
    {
		// $text = "Jln. Gunung Bandung No. 25 Kec.Cicendo";

        // panjang text awal
		$lng = strlen($text);
		
		$tot = ceil($lng / 32);

		$arr = [];

		// panjang text kertas
		if ($lng < 32) {
			$num_char = $lng;
		} else {
			$num_char = 32;
		}

        // memotong yang kata yang terpotong
        $char     = $text{$num_char - 1};
        while($char != ' ') {
            $char = $text{--$num_char}; // Cari spasi pada posisi 49, 48, 47, dst...
		}
		$str_1 = substr($text, 0, $num_char);

		array_push($arr, $str_1);
		
		$l_str = strlen($str_1);

		$t_str_2 	= (substr($text, $l_str));

		// ambil string kedua
		$str_2 		= $this->get_text($t_str_2);
		$l_str_2 	= strlen($str_2);

		if (count($arr) <= $tot) {
			array_push($arr, trim($str_2));
		}

		$t_str_3 	= substr($text, $l_str + $l_str_2);
		$str_3 		= $this->get_text($t_str_3);
		$l_str_3 	= strlen($str_3);

		if (count($arr) <= $tot) {
			array_push($arr, trim($str_3));
		}

		$t_str_4 	= substr($text, ($l_str + $l_str_2 + $l_str_3));
		$str_4 		= $this->get_text($t_str_4);
		$l_str_4 	= strlen($str_4);

		if (count($arr) <= $tot) {
			array_push($arr, trim($str_4));
		}

		// foreach ($arr as $r) {
		// 	if($r == '')
		// 	{
		// 		unset($link);
		// 	} else {
		// 		print_r($r);
		// 	}
		// }
		
		// echo "<pre>";
		// print_r($arr);
		// echo "</pre>";

		return $arr;
	}
	
	// 18-07-2020
	public function simpan_tunai()
	{
		$id_tr = $this->input->post('id_tr');
		$tunai = $this->input->post('tunai');
		
		$this->transaksi->ubah_data('trn_transaksi', ['tunai' => $tunai], ['id' => $id_tr]);

		echo json_encode(['status' => TRUE]);
		
	}



}

/* End of file Transaksi.php */
