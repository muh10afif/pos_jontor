<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kategori extends CI_Model {

    public function __construct()
    {
        $this->id_user = $this->session->userdata('id_user');
    }

	var $table = 'mst_kategori';

	public function count_filtered()
    {
        $this->db->from($this->table);
		$this->db->where('id_user', $this->session->userdata('id_user'));
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->from($this->table);
		$this->db->where('id_user', $this->session->userdata('id_user'));
        return $this->db->count_all_results();
    }

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
    
	public function get_2()
	{
		$this->db->from($this->table);
		$this->db->where('id_user', $this->session->userdata('id_user'));
        
		$query = $this->db->get();
		return $query;
	}

	public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    // 11-08-2020
    public function cari_data($tabel, $where)
    {
        return $this->db->get_where($tabel, $where);
    }

}

/* End of file M_kategori.php */
/* Location: ./application/models/M_kategori.php */