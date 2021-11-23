<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_discount extends CI_Model {

    public function __construct()
    {
        $this->id_user = $this->session->userdata('id_user');
    }

	var $table = 'mst_discount';

	public function count_filtered()
    {
        $this->db->select('mst_discount.id, mst_product.nama_product, mst_discount.satuan, mst_discount.discount')
		->from($this->table)
        ->join('mst_product', 'mst_product.id = mst_discount.id_product', 'inner')
        ->where('mst_discount.id_user', $this->id_user);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->select('mst_discount.id, mst_product.nama_product, mst_discount.satuan, mst_discount.discount')
		->from($this->table)
        ->join('mst_product', 'mst_product.id = mst_discount.id_product', 'inner')
        ->where('mst_discount.id_user', $this->id_user);
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

	public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

	public function read()
	{
		$this->db->select('mst_discount.id, mst_product.nama_product, mst_discount.satuan, mst_discount.discount')
		->from($this->table)
        ->join('mst_product', 'mst_product.id = mst_discount.id_product', 'inner')
        ->where('mst_discount.id_user', $this->id_user);
		$query = $this->db->get();
		return $query->result();		
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

/* End of file M_discount.php */
/* Location: ./application/models/M_discount.php */