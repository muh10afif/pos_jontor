<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_absensi extends CI_Model {

	var $table = 'trn_absen';

	public function get_karyawan()
	{
		$this->db->select('mst_karyawan.id')
		->from($this->table)
		->join('mst_karyawan', 'trn_absen.id_karyawan = mst_karyawan.id', 'right')
		->where('DATE(trn_absen.created_at) =', date('Y-m-d'));
		$a = $this->db->get()->result_array();

		$x = [];
		foreach ($a as $b) {
            $x[] = $b['id'];
        }

        $b = implode(',', $x);
        $id_karyawan = explode(',', $b);


		$this->db->select('mst_karyawan.*, trn_absen.created_at')
		->from($this->table)
		->join('mst_karyawan', 'trn_absen.id_karyawan = mst_karyawan.id', 'right')
		->group_by('mst_karyawan.id')
		->order_by('mst_karyawan.nama_karyawan', 'asc');
		if($id_karyawan[0] != null)
		{
			$this->db->where_not_in('mst_karyawan.id', $id_karyawan);
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

}

/* End of file M_absensi.php */
/* Location: ./application/models/M_absensi.php */