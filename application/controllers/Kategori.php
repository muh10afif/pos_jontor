<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller {

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
	}

	public function index()
	{
		$data 	= [
			'title'		=> 'Kategori',
			'isi'		=> 'kategori/read',
		];
		$this->load->view('template/wrapper', $data);
	}

	public function read()
	{
		$list 	= $this->kategori->get_2()->result();
		$data 	= [];
		$no		= 1;
		foreach($list as $kategori)
		{
			$row = [];
			$row[] = $no++.'.';
            $row[] = $kategori->kategori;
            $row[] = 
            		'<a class="text-primary mr-2" href="javascript:void(0)" title="Edit" onclick="update('."'".$kategori->id."'".')"><i class="fa fa-edit fa-lg"></i></a>
                     <a class="text-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$kategori->id."'".')"><i class="fa fa-trash fa-lg"></i></a>';
            $data[] = $row;
		}
		$output = [
                    "recordsTotal" 		=> $this->kategori->count_all(),
                    "recordsFiltered" 	=> $this->kategori->count_filtered(),
                    "data" 				=> $data,
		          ];
        echo json_encode($output);
	}

	public function edit($id)
    {
        $data = $this->kategori->get($id)->row();
        echo json_encode($data);
    }

	public function create()
    {
        $this->_validate();
        $data = [
                    'id_user'       => $this->session->userdata('id_user'),
                    'kategori'  	=> $this->input->post('kategori'),
                    'created_at'   	=> date("Y-m-d", now('Asia/Jakarta'))
                ];
        $insert = $this->kategori->create($data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $data = [
                    'kategori'  	=> $this->input->post('kategori')
                ];
        $this->kategori->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->kategori->delete($id);
        echo json_encode($data);
    }

	private function _validate()
    {
        $kategori = $this->input->post('kategori');
        $this->db->from('mst_kategori')
        ->where('id_user', $this->id_user)
        ->where('kategori', $kategori);
        $query = $this->db->get();
        $user = $query->row_array();
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        if(($this->input->post('kategori')) == '')
        {
            $data['inputerror'][] = 'kategori';
            $data['error_string'][] = 'Kategori belum Diisi';
            $data['status'] = FALSE;
        }
        if ($user['kategori'] != null) 
        {
            $data['inputerror'][] = 'kategori';
            $data['error_string'][] = 'Kategori '.$kategori.' sudah Digunakan';
            $data['status'] = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

}

/* End of file Kategori.php */
/* Location: ./application/controllers/Kategori.php */