<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('username') == "")
        {
            $this->session->set_flashdata('danger', '<div class="alert alert-danger">Anda belum Log in</div>');
            redirect(base_url(), 'refresh');
		}
		
		// if ($this->session->userdata('id_role') == 2 ) {
        //     $this->id_user = $this->session->userdata('id_user');
        // } else {
        //     $this->id_user = $this->session->userdata('id_owner');
		// }
		
		$this->id_user = $this->session->userdata('id_user');
	}

	public function index()
	{
		if ($this->session->userdata('id_role') == 2 ) {
            $this->id_user = $this->session->userdata('id_user');
        } else {
            $this->id_user = $this->session->userdata('id_owner');
		}

		$c = date("Y-m-d", now('Asia/Jakarta'));
		
		$a = date("Y-m-d H:i:s", now('Asia/Jakarta'));
		$b = date("$c 20:00:00");
		$f = date("$c 08:00:00");

		if ($a > $b) {
			$d = "Malam";
		} else {
			if ($a > $f) {
				$d = "Pagi";
			} else {
				$d = "Malam";
			}
		}

		$data 	= [
			'title'				=> 'Dashboard',
			'kategori'			=> $this->kategori->cari_data('mst_kategori', ['id_user' => $this->id_user])->num_rows(),
			'produk'			=> $this->produk->cari_data('mst_product', ['id_user' => $this->id_user, 'status_tampil' => 1])->num_rows(),
			'user'				=> $this->user->get()->num_rows(),
			'pendapatan'		=> $this->transaksi->get_total_hari_ini($d),
			'dt_profit'			=> $this->transaksi->get_profit($d)->result(),
			'tr_hari'			=> $this->transaksi->transaksi_hari_ini($d)->num_rows(),
			'pr_hari'			=> $this->transaksi->produk_hari_ini($d)->num_rows(),
			'isi'				=> 'dashboard'
		];

		// print_r($data['dt_profit']);

		$this->load->view('template/wrapper', $data);
	}

	public function tes()
    {
        // $this->db->select('sum(total_harga) as total')
        // ->from('trn_transaksi')
        // ->where("DATE_FORMAT(created_at, '%Y-%m-%d') =", date('Y-m-d'))
        // ->where('id_user', $this->id_user);
        // $query = $this->db->get();
		// print_r($this->session->userdata('id_user'));
		$c = date("Y-m-d", now('Asia/Jakarta'));
		
		$e = date("$c 01:01:00");
		$a = date("Y-m-d H:i:s", now('Asia/Jakarta'));
		$b = date("$c 20:00:00");
		$f = date("$c 08:00:00");

		if ($a > $b) {
			$d = "Malam";
		} else {
			if ($a > $f) {
				$d = "Pagi";
			} else {
				$d = "Malam";
			}
		}

		echo $d;
    }

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */