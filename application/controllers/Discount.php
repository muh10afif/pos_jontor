<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount extends CI_Controller {

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
			'title'			=> 'Discount',
			'produk_dis'    => $this->produk->get_produk_dis($this->id_user)->result(),
			'isi'			=> 'discount/read'
        ];
        
		$this->load->view('template/wrapper', $data);
	}

	public function read()
	{
		$list 	= $this->discount->read();
		$data 	= [];
		$no		= 1;
		foreach($list as $discount)
		{
			$row = [];
			$row[] = $no++.'.';
            $row[] = $discount->nama_product;
            $row[] = $discount->satuan;
            if($discount->satuan == '%') {
            	$row[] = $discount->discount.'%';
            }
            else
            {
            	$row[] = 'Rp. '.number_format($discount->discount);
            }
            $row[] = 
            		'<a class="text-primary mr-2" href="javascript:void(0)" title="Edit" onclick="update('."'".$discount->id."'".')"><i class="fa fa-edit fa-lg"></i></a>
                     <a class="text-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$discount->id."'".')"><i class="fa fa-trash fa-lg"></i></a>';
            $data[] = $row;
		}
		$output = [
                    "recordsTotal" 		=> $this->discount->count_all(),
                    "recordsFiltered" 	=> $this->discount->count_filtered(),
                    "data" 				=> $data,
		          ];
        echo json_encode($output);
	}

	public function edit($id)
    {
        $data = $this->discount->get($id)->row();

        $data2 = $this->produk->cari_data('mst_product', ['id_user' => $this->id_user])->result_array();

        $option = "<option selected disabled hidden>--PILIH--</option>";

        foreach ($data2 as $d) {
            $option .= "<option value='".$d['id']."'>".$d['nama_product']."</option>";
        }

        echo json_encode(['dt' => $data, 'list_pro' => $option]);
    }

    // 11-08-2020
    public function ambil_data_produk()
    {
        $data2 = $this->produk->get_produk_dis($this->id_user)->result_array();

        $option = "<option selected disabled hidden>--PILIH--</option>";

        foreach ($data2 as $d) {
            $option .= "<option value='".$d['id']."'>".$d['nama_product']."</option>";
        }

        echo json_encode(['list_pro' => $option]);
    }

	public function create()
    {
        $this->_validate();
        $data = [
                    'id_user'  			=> $this->session->userdata('id_user'),
                    'id_product'  		=> $this->input->post('id_product'),
                    'satuan'  			=> $this->input->post('satuan'),
                    'created_at'   		=> date("Y-m-d", now('Asia/Jakarta'))
                ];
        if($this->input->post('satuan') == '%')
        {
        	$data['discount'] = $this->input->post('discount_persen');
        }
        else
        {
        	$data['discount'] = str_replace(',', '', $this->input->post('discount_harga'));
        }
        $insert = $this->discount->create($data);

        $dt = $this->produk->get_produk_dis($this->id_user)->result_array();

        $option = "<option selected disabled hidden>--PILIH--</option>";

        foreach ($dt as $d) {

            $option .= "<option value=".$d['id'].">".$d['nama_product']."</option>";

        }

        echo json_encode(array("status" => TRUE, "list_pro" => $option));
    }

    public function update()
    {
        $this->_validate();
        $data = [
                    'id_user'  			=> $this->session->userdata('id_user'),
                    'id_product'  		=> $this->input->post('id_product'),
                    'satuan'  			=> $this->input->post('satuan'),
                    'created_at'   		=> date("Y-m-d", now('Asia/Jakarta'))
                ];
        if($this->input->post('satuan') == '%')
        {
        	$data['discount'] = $this->input->post('discount_persen');
        }
        else
        {
        	$data['discount'] = str_replace(',', '', $this->input->post('discount_harga'));
        }
        $this->discount->update(array('id' => $this->input->post('id')), $data);


        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->discount->delete($id);
        echo json_encode($data);
    }

	private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        if(($this->input->post('id_product')) == '')
        {
            $data['inputerror'][] = 'id_product';
            $data['error_string'][] = 'Produk belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('satuan')) == '')
        {
            $data['inputerror'][] = 'satuan';
            $data['error_string'][] = 'Satuan belum Diisi';
            $data['status'] = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

}

/* End of file Discount.php */
/* Location: ./application/controllers/Discount.php */