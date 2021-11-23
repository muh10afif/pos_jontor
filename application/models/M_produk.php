<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_produk extends CI_Model {

    public function __construct()
    {
        $this->id_user = $this->session->userdata('id_user');
    }

	var $table = 'mst_product';

	public function count_filtered()
    {
        $this->db->select('mst_product.*, mst_kategori.kategori')
    	->from($this->table)
        ->join('mst_kategori', 'mst_product.id_kategori = mst_kategori.id', 'left');
        $this->db->where('mst_product.id_user', $this->session->userdata('id_user'));
        $this->db->where('status_tampil', 1);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->select('mst_product.*, mst_kategori.kategori')
    	->from($this->table)
        ->join('mst_kategori', 'mst_product.id_kategori = mst_kategori.id', 'left');
        $this->db->where('mst_product.id_user', $this->session->userdata('id_user'));
        $this->db->where('status_tampil', 1);
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
    	$this->db->select('mst_product.*, mst_kategori.kategori')
    	->from($this->table)
        ->join('mst_kategori', 'mst_product.id_kategori = mst_kategori.id', 'left');
        $this->db->where('mst_product.id_user', $this->session->userdata('id_user'));
        $this->db->where('status_tampil', 1);
        $this->db->order_by('mst_product.nama_product', 'asc');
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

    // 11-08-2020
    public function cari_data($tabel, $where)
    {
        return $this->db->get_where($tabel, $where);
    }

    public function get_produk_dis($id_user)
    {
        $this->db->select('*');
        $this->db->from('mst_discount');
        $this->db->where('id_user', $id_user);
        
        $a  = $this->db->get()->result();
        $ar = array();

        foreach ($a as $b) {
            $ar[] = $b->id_product;
        }
        
        $im     = implode(",", $ar);
        $hasil  = explode(",", $im);

        $this->db->from('mst_product as p');
        $this->db->where('p.id_user', $id_user);
        $this->db->where_not_in('p.id', $hasil);
        
        return $this->db->get();
        
    }

    // 12-08-2020
    public function get_bahan_komposisi($id_produk)
    {
        $this->db->select('*');
        $this->db->from('trn_komposisi');
        $this->db->where('id_product', $id_produk);
        
        $a  = $this->db->get()->result();
        $ar = array();

        foreach ($a as $b) {
            $ar[] = $b->id_bahan;
        }
        
        $im     = implode(",", $ar);
        $hasil  = explode(",", $im);

        $this->db->from('mst_product as p');
        $this->db->where('p.id_user', $this->id_user);
        $this->db->where('p.status_bahan', 1);
        $this->db->where('p.status_tampil', 0);
        $this->db->where_not_in('p.id', $hasil);
        
        return $this->db->get();
    }

    // 11-08-2020

    // Menampilkan list komposisi
    public function get_data_komposisi($id_produk)
    {
        $this->_get_datatables_query_komposisi($id_produk);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
    }

    var $kolom_order_komposisi = [null, 'p.nama_product', 'k.nilai_komposisi'];
    var $kolom_cari_komposisi  = ['LOWER(p.nama_product)', 'k.nilai_komposisi'];
    var $order_komposisi       = ['p.nama_product' => 'asc'];

    public function _get_datatables_query_komposisi($id_produk)
    {
        $this->db->select('p.nama_product, k.nilai_komposisi, k.id_komposisi, p.status_bahan, p.status_tampil');
        $this->db->from('trn_komposisi as k'); 
        $this->db->join('mst_product as p', 'p.id = k.id_bahan', 'inner');
        $this->db->where('k.id_product', $id_produk);
        
        $b = 0;
        
        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_komposisi;

        foreach ($kolom_cari as $cari) {
            if ($input_cari) {
                if ($b === 0) {
                    $this->db->group_start();
                    $this->db->like($cari, $input_cari);
                } else {
                    $this->db->or_like($cari, $input_cari);
                }

                if ((count($kolom_cari) - 1) == $b ) {
                    $this->db->group_end();
                }
            }

            $b++;
        }

        if (isset($_POST['order'])) {

            $kolom_order = $this->kolom_order_komposisi;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_komposisi)) {
            
            $order = $this->order_komposisi;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
        
    }

    public function jumlah_semua_komposisi($id_produk)
    {
        $this->db->select('p.nama_product, k.nilai_komposisi, k.id_komposisi, p.status_bahan, p.status_tampil');
        $this->db->from('trn_komposisi as k'); 
        $this->db->join('mst_product as p', 'p.id = k.id_bahan', 'inner');
        $this->db->where('k.id_product', $id_produk);   

        return $this->db->count_all_results();
    }

    public function jumlah_filter_komposisi($id_produk)
    {
        $this->_get_datatables_query_komposisi($id_produk);

        return $this->db->get()->num_rows();
        
    }

    public function input_data($tabel, $data)
    {
        $this->db->insert($tabel, $data);
    }

    public function ubah_data($tabel, $data, $where)
    {
        return $this->db->update($tabel, $data, $where);
    }

    public function hapus_data($tabel, $where)
    {
        $this->db->delete($tabel, $where);
    }

}

/* End of file M_produk.php */
/* Location: ./application/models/M_produk.php */