<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata('username') == "")
        {
            $this->session->set_flashdata('danger', '<div class="alert alert-danger">Anda belum Log in</div>');
            redirect(base_url(), 'refresh');
        }
	}

	public function index()
	{
		$data 	= [
			'title'				=> 'Absensi',
			'isi'				=> 'absensi/read',
			'karyawan'			=> $this->absensi->get_karyawan()
		];
		$this->load->view('template/wrapper', $data);
	}

	public function jepret()
	{
		$id_karyawan 	= $this->input->post('id_karyawan');
		$karyawan 		= $this->karyawan->get($id_karyawan)->row();
		$image 		 	= $this->input->post('image');
		// $path 			= FCPATH.'/assets/img/upload/absensi/';
		// $image_parts 	= explode(';base64', $image);
		// $image_type_aux 	= explode("image/", $image_parts[0]);
		// $image_type 		= $image_type_aux[1];
  		// $image_base64 	= base64_decode($image_parts[1]);
		// $file 			= $path . 'Foto Absen'.str_replace(' ', '-', $karyawan->nama_karyawan).'-'.date('Y-m-d') . '.png';
		// file_put_contents($file, $image_base64);
		$image 		 	= str_replace('data:image/jpeg;base64,','', $image);
		$image 			= base64_decode($image);
		$filename 		= str_replace(' ', '', $karyawan->nama_karyawan).date('Ymdhis').'.png';
		file_put_contents(FCPATH.'/assets/img/upload/absensi/'.$filename,$image);
		$data = [
			'id_karyawan' 	=> $id_karyawan,
			'foto' 			=> $filename,
			'created_at'   	=> date("Y-m-d h:i:s", now('Asia/Jakarta'))
		];
		$res = $this->absensi->create($data);
		echo json_encode($res);
	}
}

/* End of file Absensi.php */
/* Location: ./application/controllers/Absensi.php */