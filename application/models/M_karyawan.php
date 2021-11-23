<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_karyawan extends CI_Model {

    public function __construct()
    {
        $this->id_user = $this->session->userdata('id_user');
    }

	var $table = 'mst_karyawan';

	public function count_filtered()
    {
        $this->db->select('mst_karyawan.*, mst_user.username, mst_role.role')
    	->from($this->table)
    	->join('mst_user', 'mst_karyawan.id_user = mst_user.id', 'left')
        ->join('mst_role', 'mst_user.id_role = mst_role.id', 'left')
        ->where('mst_karyawan.id_owner', $this->id_user);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->select('mst_karyawan.*, mst_user.username, mst_role.role')
    	->from($this->table)
    	->join('mst_user', 'mst_karyawan.id_user = mst_user.id', 'left')
        ->join('mst_role', 'mst_user.id_role = mst_role.id', 'left')
        ->where('mst_karyawan.id_owner', $this->id_user);
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

    public function get_data($id)
    {
        $this->db->from($this->table)
        ->where('id', $id);
        $query = $this->db->get();
        $karyawan = $query->row();
        if(!empty($karyawan->id_user))
        {
            $this->db->select('mst_karyawan.*, mst_user.username')
            ->from($this->table)
            ->join('mst_user', 'mst_user.id = mst_karyawan.id_user', 'inner')
            ->where('mst_karyawan.id', $id);
            $query = $this->db->get();
            return $query->row();
        }
        else
        {
            return $karyawan;
        }
    }

	public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function read()
    {
    	$this->db->select('mst_karyawan.*, mst_user.username, mst_role.role')
    	->from($this->table)
    	->join('mst_user', 'mst_karyawan.id_user = mst_user.id', 'left')
        ->join('mst_role', 'mst_user.id_role = mst_role.id', 'left')
        ->where('mst_karyawan.id_owner', $this->id_user);
    	$query = $this->db->get();
    	return $query->result();
    }

    public function cek_user()
    {
    	$this->db->from($this->table)
    	->join('mst_user', 'mst_karyawan.id_user = mst_user.id', 'outer left')
    	->where('mst_user.id is NULL');
    	$query = $this->db->get();
    	return $query;
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

}

/* End of file M_karyawan.php */
/* Location: ./application/models/M_karyawan.php */