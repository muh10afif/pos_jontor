<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function index()
	{
		if($this->session->userdata('username') != null)
        {
            redirect('Dashboard');
        }
		$this->load->view('auth');
	}

	public function cek()
	{
		$username	= $this->input->post('username');
		$password	= $this->input->post('password');
		$jumlah 	= $this->user->get()->num_rows();
		if($jumlah > 0)
		{
			$user 	= $this->user->cek_user($username);
			if($user != null)
			{
				if(password_verify($password, $user->pass))
				{
					$this->session->set_userdata([
						'id_user'		=> $user->id,
						'username' 		=> $user->username,
						'id_role'		=> $user->id_role,
						'id_owner'		=> $user->id_owner,
						'username'		=> $user->username,
						'logo'			=> $user->logo,
						'nama_umkm'		=> $user->nama_umkm
					]);
	                redirect('Transaksi');
				}
				else
				{
					$this->session->set_flashdata('danger','Password tidak cocok');
	            	redirect(base_url());
				}
			}
			else
			{
				$this->session->set_flashdata('danger','Username tidak ditemukan');
	            redirect(base_url());
			}
		}
	}

	public function out()
	{
		$this->session->sess_destroy();
		redirect('Auth');
	}

}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */