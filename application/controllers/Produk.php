<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {

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
			'title'		=> 'Produk',
            'kategori'	=> $this->kategori->cari_data('mst_kategori', ['id_user' => $this->id_user])->result(),
            'bahan'     => $this->bahan->cari_data('mst_product', ['status_bahan' => 1, 'id_user' => $this->id_user])->result_array(),
			'isi'		=> 'produk/read'
		];
		$this->load->view('template/wrapper', $data);
	}

	public function read()
	{
		$list 	= $this->produk->read();
		$data 	= [];
		$no		= 1;
		foreach($list as $produk)
		{
            if (($produk->status_tampil && $produk->status_bahan) == 1) {
                $kom = '';
            } else {
                $kom = '
                <a class="text-success komposisi" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" data-id='.$produk->id.' nm_produk="'.$produk->nama_product.'" title="Komposisi"><i class="fa fa-list-ul fa-lg"></i></a>';
            }

			$row = [];
			$row[] = $no++.'.';
            $row[] = $produk->nama_product;
            $row[] = $produk->kategori;
            $row[] = 'Rp. '.number_format($produk->harga);
            $row[] = 'Rp. '.number_format($produk->hpp);
            $row[] = $produk->have_discount == 1 ? 'Ada' : 'Tidak ada';
            $row[] = 
            		'<a class="text-primary mr-3" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Edit" onclick="update('."'".$produk->id."'".')"><i class="fa fa-edit fa-lg"></i></a>
                     <a class="text-danger mr-3" href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="Hapus" onclick="delete_data('."'".$produk->id."'".')"><i class="fa fa-trash fa-lg"></i></a>'.$kom.'';
            $data[] = $row;
		}
		$output = [
                    "recordsTotal" 		=> $this->produk->count_all(),
                    "recordsFiltered" 	=> $this->produk->count_filtered(),
                    "data" 				=> $data,
		          ];
        echo json_encode($output);
    }

    // 12-08-2020
    public function simpan_komposisi()
    {
        $id_product     = $this->input->post('id_product');
        $jumlah         = $this->input->post('jumlah');
        $id_bahan       = $this->input->post('nm_bahan');
        $hitung         = $this->input->post('jml');
        $aksi           = $this->input->post('aksi');
        $id_komposisi   = $this->input->post('id_komposisi');

        if ($aksi == 'tambah') {

            for ($i=0; $i < $hitung; $i++) { 
                
                $data = ['id_product'       => $id_product,
                        'nilai_komposisi'  => $jumlah[$i],
                        'id_bahan'         => $id_bahan[$i],
                        'created_at'       => date("Y-m-d H:i:s", now('Asia/Jakarta'))
                        ];
                
                $this->produk->input_data('trn_komposisi', $data);
            }

        } elseif ($aksi == 'hapus') {
            
            $this->produk->hapus_data('trn_komposisi', ['id_komposisi' => $id_komposisi]);

        } else {

            $this->produk->ubah_data('trn_komposisi', ['nilai_komposisi' => $jumlah], ['id_komposisi' => $id_komposisi]);

        }

        echo json_encode(['status' => TRUE]);

    }

    public function ambil_option_bahan()
    {
        $id_product = $this->input->post('id_product');

        $data2 = $this->produk->get_bahan_komposisi($id_product)->result_array();

        $option = "<option selected disabled hidden>--PILIH--</option>";

        foreach ($data2 as $d) {
            $option .= "<option value='".$d['id']."'>".$d['nama_product']."</option>";
        }

        echo json_encode(['list_pro' => $option]);
        
    }
    
    // 11-08-2020
    public function tampil_data_komposisi()
    {
        $id_produk = $this->input->post('id_product');
        
        $list = $this->produk->get_data_komposisi($id_produk);

        $data = array();

        $no   = $this->input->post('start');

        foreach ($list as $o) {
            $no++;
            $tbody = array();

            $tbody[]    = "<div align='center'>".$no.".</div>";
            $tbody[]    = $o['nama_product'];
            $tbody[]    = $o['nilai_komposisi'];
            $tbody[]    = 
            "<a class='text-primary edit-komposisi mr-2' data-id='".$o['id_komposisi']."' nm_product='".$o['nama_product']."' nilai_komposisi='".$o['nilai_komposisi']."'  href='javascript:void(0)' title='Edit'><i class='fa fa-edit fa-lg'></i></a>
            <a class='text-danger hapus-komposisi' data-id='".$o['id_komposisi']."' href='javascript:void(0)' title='Hapus'><i class='fa fa-trash fa-lg'></i></a>";
            $data[]     = $tbody;
        }

        $output = [ "draw"             => $_POST['draw'],
                    "recordsTotal"     => $this->produk->jumlah_semua_komposisi($id_produk),
                    "recordsFiltered"  => $this->produk->jumlah_filter_komposisi($id_produk),   
                    "data"             => $data
                ];

        echo json_encode($output);
    }

	public function edit($id)
    {
        $data = $this->produk->get($id)->row();
        echo json_encode($data);
    }

	public function create()
    {
        $this->_validate();
        $data = [
                    'id_user'           => $this->session->userdata('id_user'),  
                    'id_kategori'  		=> $this->input->post('id_kategori'),
                    'nama_product'  	=> $this->input->post('nama_product'),
                    'harga'  			=> str_replace('.', '', $this->input->post('harga')),
                    'hpp'  				=> str_replace('.', '', $this->input->post('hpp')),
                    'have_discount'  	=> $this->input->post('have_discount'),
                    'created_at'   		=> date("Y-m-d", now('Asia/Jakarta')),
                    'status_bahan'      => $this->input->post('status_bahan'),
                    'status_tampil'     => $this->input->post('status_tampil')
                ];
                
        $insert = $this->produk->create($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate_update();
        $data = [
                    'id_kategori'  		=> $this->input->post('id_kategori'),
                    'nama_product'  	=> $this->input->post('nama_product'),
                    'harga'  			=> str_replace('.', '', $this->input->post('harga')),
                    'hpp'  				=> str_replace('.', '', $this->input->post('hpp')),
                    'have_discount'  	=> $this->input->post('have_discount'),
                    'created_at'   		=> date("Y-m-d", now('Asia/Jakarta')),
                    'status_bahan'      => $this->input->post('status_bahan'),
                    'status_tampil'     => $this->input->post('status_tampil')
                ];
        $this->produk->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->produk->delete($id);
        echo json_encode($data);
    }

    private function _validate()
    {
        $nama_product = $this->input->post('nama_product');
        $this->db->from('mst_product')
        ->where('nama_product', $nama_product)
        ->where('id_user', $this->session->userdata('id_user'));
        $query = $this->db->get();
        $user = $query->row_array();
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        if(($this->input->post('id_kategori')) == '')
        {
            $data['inputerror'][] = 'id_kategori';
            $data['error_string'][] = 'Kategori belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('nama_product')) == '')
        {
            $data['inputerror'][] = 'nama_product';
            $data['error_string'][] = 'Nama Produk belum Diisi';
            $data['status'] = FALSE;
        }
        if ($user['nama_product'] != null) 
        {
            $data['inputerror'][] = 'nama_product';
            $data['error_string'][] = 'Produk '.$nama_product.' sudah Tersedia';
            $data['status'] = FALSE;
        }
        if(($this->input->post('harga')) == '')
        {
            $data['inputerror'][] = 'harga';
            $data['error_string'][] = 'Harga belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('hpp')) == '')
        {
            $data['inputerror'][] = 'hpp';
            $data['error_string'][] = 'HPP belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('have_discount')) == '')
        {
            $data['inputerror'][] = 'have_discount';
            $data['error_string'][] = 'Keterangan Diskon belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('status_bahan')) == '')
        {
            $data['inputerror'][] = 'status_bahan';
            $data['error_string'][] = 'Status Bahan belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('status_tampil')) == '')
        {
            $data['inputerror'][] = 'status_tampil';
            $data['error_string'][] = 'Status tampil belum Diisi';
            $data['status'] = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

    private function _validate_update()
    {
        $nama_product = $this->input->post('nama_product');
        $this->db->from('mst_product')
        ->where('nama_product', $nama_product)
        ->where('id <>', $this->input->post('id'))
        ->where('id_user', $this->session->userdata('id_user'));
        $query = $this->db->get();
        $user = $query->row_array();
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        if(($this->input->post('id_kategori')) == '')
        {
            $data['inputerror'][] = 'id_kategori';
            $data['error_string'][] = 'Kategori belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('nama_product')) == '')
        {
            $data['inputerror'][] = 'nama_product';
            $data['error_string'][] = 'Nama Produk belum Diisi';
            $data['status'] = FALSE;
        }
        if ($user['nama_product'] != null) 
        {
            $data['inputerror'][] = 'nama_product';
            $data['error_string'][] = 'Produk '.$nama_product.' sudah Tersedia';
            $data['status'] = FALSE;
        }
        if(($this->input->post('harga')) == '')
        {
            $data['inputerror'][] = 'harga';
            $data['error_string'][] = 'Harga belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('hpp')) == '')
        {
            $data['inputerror'][] = 'hpp';
            $data['error_string'][] = 'HPP belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('have_discount')) == '')
        {
            $data['inputerror'][] = 'have_discount';
            $data['error_string'][] = 'Keterangan Diskon belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('status_bahan')) == '')
        {
            $data['inputerror'][] = 'status_bahan';
            $data['error_string'][] = 'Status Bahan belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('status_tampil')) == '')
        {
            $data['inputerror'][] = 'status_tampil';
            $data['error_string'][] = 'Status tampil belum Diisi';
            $data['status'] = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

}

/* End of file Produk.php */
/* Location: ./application/controllers/Produk.php */