<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

	var $table = 'mst_user';

	public function get($id = null)
	{
		$this->db->from($this->table);
		if($id)
		{
			$this->db->where('id', $id);
		}
		$query = $this->db->get();
		return $query;
	}

	public function cek_user($username)
	{
		$this->db->from($this->table)
		->where('username', $username);
		$query = $this->db->get();
		return $query->row();
	}

}

/* End of file M_user.php */
/* Location: ./application/models/M_user.php */