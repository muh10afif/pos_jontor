<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karyawan extends CI_Controller {

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
			'title'			=> 'Karyawan',
			'user'			=> $this->user->get()->result(),
			'jumlah_user'	=> $this->karyawan->cek_user()->num_rows(),
			'cek_user'		=> $this->karyawan->cek_user()->result(),
			'isi'			=> 'karyawan/read'
		];
		$this->load->view('template/wrapper', $data);
	}

	public function read()
	{
		$list 	= $this->karyawan->read();
		$data 	= [];
		$no		= 1;
		foreach($list as $karyawan)
		{
			$row = [];
			$row[] = $no++.'.';
            $row[] = $karyawan->nama_karyawan;
            $row[] = $karyawan->kode_karyawan;
            $row[] = $karyawan->username ? $karyawan->username : 'Belum ada';
            $row[] = $karyawan->role ? $karyawan->role : 'Belum ada';
            $row[] = 
            		'<a class="text-primary mr-2" href="javascript:void(0)" title="Edit" onclick="update('."'".$karyawan->id."'".')"><i class="fa fa-edit fa-lg"></i></a>
                     <a class="text-danger" href="javascript:void(0)" title="Hapus" onclick="delete_data('."'".$karyawan->id."'".')"><i class="fa fa-trash fa-lg"></i></a>';
            $data[] = $row;
		}
		$output = [
                    "recordsTotal" 		=> $this->karyawan->count_all(),
                    "recordsFiltered" 	=> $this->karyawan->count_filtered(),
                    "data" 				=> $data,
		          ];
        echo json_encode($output);
	}

	public function edit($id)
    {
        $data = $this->karyawan->get_data($id);
        echo json_encode($data);
    }

	public function create()
    {
        $urutan     = $this->karyawan->count_filtered();
        $nomor      = ($urutan+1);
        $random     = mt_rand(100000, 999999);
        if($urutan < 1)
        {
            $no = 001;
        }
        elseif($urutan > 0 && $urutan < 8)
        {
            $no = '00'.$nomor;
        }
        elseif($urutan > 9 && $urutan < 99)
        {
            $no = '0'.$nomor;
        }
        else
        {
            $no = $nomor;
        }
        $this->_validate();
        $id_user = null;
        if(!empty($this->input->post('username')) && !empty($this->input->post('pass')))
        {
            $data_user  = [
                'username'      => $this->input->post('username'),
                'pass'          => password_hash($this->input->post('pass'), PASSWORD_DEFAULT),
                'active'        => 1,
                'id_role'       => 1,
                'id_owner'      => $this->session->userdata('id_user'),
                'logo'          => 'ayam_jontor.png',
                'nama_umkm'     => 'Ayam Goreng Sambal Jontor',
                'created_at'    => date("Y-m-d", now('Asia/Jakarta'))
            ];
            $this->db->insert('mst_user', $data_user);
            $id_user    = $this->db->insert_id();
        }
        $data = [
                    'kode_karyawan'  	=> "KRN$random$no",
                    'id_owner'          => $this->session->userdata('id_user'),
                    'id_user'           => $id_user ? $id_user : null,
                    'nama_karyawan'  	=> $this->input->post('nama_karyawan'),
                    'created_at'   		=> date("Y-m-d", now('Asia/Jakarta'))
                ];
        $this->db->insert('mst_karyawan', $data);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate_update();
        $id_user = null;
        $karyawan = $this->karyawan->get_data($this->input->post('id'));
        if($karyawan->id_user == null)
        {
            if(!empty($this->input->post('username')) && !empty($this->input->post('pass')))
            {
                $data_user  = [
                    'username'      => $this->input->post('username'),
                    'pass'          => password_hash($this->input->post('pass'), PASSWORD_DEFAULT),
                    'active'        => 1,
                    'id_role'       => 1,
                    'id_owner'      => $this->session->userdata('id_user'),
                    'logo'          => 'shyo.png',
                    'created_at'    => date("Y-m-d", now('Asia/Jakarta'))
                ];
                $this->db->insert('mst_user', $data_user);
                $id_user    = $this->db->insert_id();
            }
            $data = [
                        'id_user'           => $id_user ? $id_user : null,
                        'id_owner'          => $this->session->userdata('id_user'),
                        'nama_karyawan'     => $this->input->post('nama_karyawan')
                    ];
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('mst_karyawan', $data);
            echo json_encode(array("status" => TRUE));
        }
        else
        {
            if(!empty($this->input->post('username')))
            {
                $data_user  = [
                    'username'      => $this->input->post('username')
                ];
                if(!empty($this->input->post('pass')))
                {
                    $data_user  = [
                        'pass'          => password_hash($this->input->post('pass'), PASSWORD_DEFAULT),
                    ];
                }
                $this->db->where('id', $karyawan->id_user);
                $this->db->update('mst_user', $data_user);
            }
            $data = [
                        'nama_karyawan'     => $this->input->post('nama_karyawan')
                    ];
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('mst_karyawan', $data);
            echo json_encode(array("status" => TRUE));
        }
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $data = $this->karyawan->delete($id);
        echo json_encode($data);
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        if(($this->input->post('nama_karyawan')) == '')
        {
            $data['inputerror'][] = 'nama_karyawan';
            $data['error_string'][] = 'Nama Karyawan belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('username')) == '' && !empty($this->input->post('pass')))
        {
            $data['inputerror'][] = 'username';
            $data['error_string'][] = 'Username belum Diisi';
            $data['status'] = FALSE;
        }
        if(($this->input->post('pass')) == '' && !empty($this->input->post('username')))
        {
            $data['inputerror'][] = 'pass';
            $data['error_string'][] = 'Password belum Diisi';
            $data['status'] = FALSE;
        }
        if(strlen($this->input->post('pass')) < 6 && strlen($this->input->post('pass')) > 0)
        {
            $data['inputerror'][] = 'pass';
            $data['error_string'][] = 'Password minimal harus mengandung 6 karakter';
            $data['status'] = FALSE;
        }
        if(strlen($this->input->post('Username')) < 6 && strlen($this->input->post('Username')) > 0)
        {
            $data['inputerror'][] = 'Username';
            $data['error_string'][] = 'Usernam minimal harus mengandung 6 karakter';
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
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        if(($this->input->post('nama_karyawan')) == '')
        {
            $data['inputerror'][] = 'nama_karyawan';
            $data['error_string'][] = 'Nama Karyawan belum Diisi';
            $data['status'] = FALSE;
        }
        if(strlen($this->input->post('pass')) < 6 && strlen($this->input->post('pass')) > 0)
        {
            $data['inputerror'][] = 'pass';
            $data['error_string'][] = 'Password minimal harus mengandung 6 karakter';
            $data['status'] = FALSE;
        }
        if(strlen($this->input->post('username')) < 6 && strlen($this->input->post('username')) > 0)
        {
            $data['inputerror'][] = 'username';
            $data['error_string'][] = 'Username minimal harus mengandung 6 karakter';
            $data['status'] = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

}

/* End of file Karyawan.php */
/* Location: ./application/controllers/Karyawan.php */