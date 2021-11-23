<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_bahan extends CI_Model {

    public function __construct()
    {
        $this->id_user = $this->session->userdata('id_user');
    }

    var $table = 'mst_bahan';

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

    public function cari_data($tabel, $where)
    {
        return $this->db->get_where($tabel, $where);
    }

    public function get_data_order($tabel, $field, $order)
    {
        $this->db->order_by($field, $order);
        
        return $this->db->get($tabel);
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

    // Menampilkan list stok
    public function get_data_stok()
    {
        $this->_get_datatables_query_stok();

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
    }

    var $kolom_order_stok = [null, 'k.nama_product', 'b.stok'];
    var $kolom_cari_stok  = ['LOWER(k.nama_product)', 'b.stok'];
    var $order_stok       = ['k.nama_product' => 'asc'];

    public function _get_datatables_query_stok()
    {
        $this->db->select('k.nama_product, b.stok, b.id as id_stok, k.satuan');
        $this->db->from('mst_stok as b'); 
        $this->db->join('mst_product as k', 'k.id = b.id_product', 'inner');
        $this->db->where('k.id_user', $this->id_user);
        
        $b = 0;
        
        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_stok;

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

            $kolom_order = $this->kolom_order_stok;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_stok)) {
            
            $order = $this->order_stok;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
        
    }

    public function jumlah_semua_stok()
    {
        $this->db->select('b.nama_stok, k.product, b.id as id_stok, k.satuan');
        $this->db->from('mst_stok as b'); 
        $this->db->join('mst_product as k', 'k.id = b.id_product', 'inner');
        $this->db->where('k.id_user', $this->id_user);

        return $this->db->count_all_results();
    }

    public function jumlah_filter_stok()
    {
        $this->_get_datatables_query_stok();

        return $this->db->get()->num_rows();
        
    }

    // 04-08-2020

    // Menampilkan list bahan
    public function get_data_bahan()
    {
        $this->_get_datatables_query_bahan();

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
    }

    var $kolom_order_bahan = [null, 'b.nama_product', 'k.kategori', 'b.satuan', 'minimal_stok'];
    var $kolom_cari_bahan  = ['LOWER(b.nama_product)', 'LOWER(k.kategori)', 'b.satuan', 'minimal_stok'];
    var $order_bahan       = ['b.nama_product' => 'asc'];

    public function _get_datatables_query_bahan()
    {
        $this->db->select('k.kategori, b.nama_product as nama_bahan, b.satuan, b.id as id_bahan, (SELECT t.minimal_stok FROM mst_stok as t WHERE t.id_product = b.id) as minimal_stok');
        $this->db->from('mst_product as b'); 
        $this->db->join('mst_kategori as k', 'k.id = b.id_kategori', 'inner');
        $this->db->where('b.status_bahan', 1);
        $this->db->where('b.id_user', $this->session->userdata('id_user'));
        
        $b = 0;
        
        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_bahan;

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

            $kolom_order = $this->kolom_order_bahan;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_bahan)) {
            
            $order = $this->order_bahan;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
        
    }

    public function jumlah_semua_bahan()
    {
        $this->db->select('k.kategori, b.nama_product as nama_bahan, b.satuan, b.id as id_bahan, (SELECT t.minimal_stok FROM mst_stok as t WHERE t.id_product = b.id) as minimal_stok');
        $this->db->from('mst_product as b'); 
        $this->db->join('mst_kategori as k', 'k.id = b.id_kategori', 'inner');
        $this->db->where('b.status_bahan', 1);
        $this->db->where('b.id_user', $this->session->userdata('id_user'));

        return $this->db->count_all_results();
    }

    public function jumlah_filter_bahan()
    {
        $this->_get_datatables_query_bahan();

        return $this->db->get()->num_rows();
        
    }

    // 05-08-2020

    // Menampilkan list report_stok
    public function get_data_report_stok($awal, $akhir)
    {
        $this->_get_datatables_query_report_stok($awal, $akhir);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
    }

    var $kolom_order_report_stok = [null, 'p.nama_product', 'p.satuan', 'stok'];
    var $kolom_cari_report_stok  = ['LOWER(p.nama_product)', 'LOWER(p.satuan)', 'stok'];
    var $order_report_stok       = ['p.nama_product' => 'asc'];

    public function _get_datatables_query_report_stok($awal, $akhir)
    {
        $this->db->select("DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal");
        $this->db->from('trn_stok');
        $this->db->order_by('created_at', 'asc');
        $this->db->limit(1);
        $tgl = $this->db->get()->row_array();

        if ($awal != '') {
            $tgl_awal = nice_date($awal, 'Y-m-d');
        } else {
            $tgl_awal = $tgl['tanggal'];
        }

        if ($akhir != '') {
            $tgl_akhir = nice_date($akhir, 'Y-m-d');
        } else {
            $tgl_akhir = date("Y-m-d", now('Asia/Jakarta'));
        }
        

        $this->db->select("p.nama_product, p.satuan, s.id, COALESCE( (SELECT sum(m.barang_masuk) FROM trn_stok as m WHERE m.id_stok = s.id and DATE_FORMAT(m.created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir') - ((SELECT sum(k.barang_keluar) FROM trn_stok as k WHERE k.id_stok = s.id and DATE_FORMAT(k.created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir') + (SELECT sum(r.barang_retur) FROM trn_stok as r WHERE r.id_stok = s.id and DATE_FORMAT(r.created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir')) ,0) as stok");
        $this->db->from('mst_stok as s');
        $this->db->join('mst_product as p', 'p.id = s.id_product', 'inner');
        $this->db->where('p.id_user', $this->id_user);
        
        $b = 0;
        
        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_report_stok;

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

            $kolom_order = $this->kolom_order_report_stok;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_report_stok)) {
            
            $order = $this->order_report_stok;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
        
    }

    public function jumlah_semua_report_stok($awal, $akhir)
    {
        $this->db->select("DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal");
        $this->db->from('trn_stok');
        $this->db->order_by('created_at', 'asc');
        $this->db->limit(1);
        $tgl = $this->db->get()->row_array();

        if ($awal != '') {
            $tgl_awal = nice_date($awal, 'Y-m-d');
        } else {
            $tgl_awal = $tgl['tanggal'];
        }

        if ($akhir != '') {
            $tgl_akhir = nice_date($akhir, 'Y-m-d');
        } else {
            $tgl_akhir = date("Y-m-d", now('Asia/Jakarta'));
        }

        $this->db->select("p.nama_product, p.satuan, s.id, COALESCE( (SELECT sum(m.barang_masuk) FROM trn_stok as m WHERE m.id_stok = s.id and DATE_FORMAT(m.created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir') - ((SELECT sum(k.barang_keluar) FROM trn_stok as k WHERE k.id_stok = s.id and DATE_FORMAT(k.created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir') + (SELECT sum(r.barang_retur) FROM trn_stok as r WHERE r.id_stok = s.id and DATE_FORMAT(r.created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir')) ,0) as stok");
        $this->db->from('mst_stok as s');
        $this->db->join('mst_product as p', 'p.id = s.id_product', 'inner');
        $this->db->where('p.id_user', $this->id_user);

        return $this->db->count_all_results();
    }

    public function jumlah_filter_report_stok($awal, $akhir)
    {
        $this->_get_datatables_query_report_stok($awal, $akhir);

        return $this->db->get()->num_rows();
        
    }

    // 06-08-2020

    // Menampilkan list detail_stok
    public function get_data_detail_stok($id_stok, $awal, $akhir)
    {
        $this->_get_datatables_query_detail_stok($id_stok, $awal, $akhir);

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
    }

    var $kolom_order_detail_stok = [null, 'barang_masuk', 'barang_keluar', 'barang_retur', 'created_at'];
    var $kolom_cari_detail_stok  = ['barang_masuk', 'barang_keluar', 'barang_retur', 'created_at'];
    var $order_detail_stok       = ['barang_masuk' => 'asc'];

    public function _get_datatables_query_detail_stok($id_stok, $awal, $akhir)
    {
        $this->db->select("DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal");
        $this->db->from('trn_stok');
        $this->db->order_by('created_at', 'asc');
        $this->db->limit(1);
        $tgl = $this->db->get()->row_array();

        if ($awal != '') {
            $tgl_awal = nice_date($awal, 'Y-m-d');
        } else {
            $tgl_awal = $tgl['tanggal'];
        }

        if ($akhir != '') {
            $tgl_akhir = nice_date($akhir, 'Y-m-d');
        } else {
            $tgl_akhir = date("Y-m-d", now('Asia/Jakarta'));
        }

        $this->db->select('barang_masuk, barang_keluar, barang_retur, created_at');
        $this->db->from('trn_stok');
        $this->db->where('id_stok', $id_stok);
        $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir'");
        
        $b = 0;
        
        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_detail_stok;

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

            $kolom_order = $this->kolom_order_detail_stok;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_detail_stok)) {
            
            $order = $this->order_detail_stok;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
        
    }

    public function jumlah_semua_detail_stok($id_stok, $awal, $akhir)
    {
        $this->db->select("DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal");
        $this->db->from('trn_stok');
        $this->db->order_by('created_at', 'asc');
        $this->db->limit(1);
        $tgl = $this->db->get()->row_array();

        if ($awal != '') {
            $tgl_awal = nice_date($awal, 'Y-m-d');
        } else {
            $tgl_awal = $tgl['tanggal'];
        }

        if ($akhir != '') {
            $tgl_akhir = nice_date($akhir, 'Y-m-d');
        } else {
            $tgl_akhir = date("Y-m-d", now('Asia/Jakarta'));
        }

        $this->db->select('barang_masuk, barang_keluar, barang_retur, created_at');
        $this->db->from('trn_stok');
        $this->db->where('id_stok', $id_stok);
        $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir'");

        return $this->db->count_all_results();
    }

    public function jumlah_filter_detail_stok($id_stok, $awal, $akhir)
    {
        $this->_get_datatables_query_detail_stok($id_stok, $awal, $akhir);

        return $this->db->get()->num_rows();
        
    }    

    public function get_nama_product($id_stok)
    {
        $this->db->select('p.nama_product');
        $this->db->from('mst_stok as s');
        $this->db->join('mst_product as p', 'p.id = s.id_product', 'inner');
        $this->db->where('s.id', $id_stok);
        
        return $this->db->get();
    }   


    // 07-08-2020
    public function get_report_stok($tgl_awal, $tgl_akhir)
    {
        $this->db->select("p.nama_product, p.satuan, s.id, COALESCE( (SELECT sum(m.barang_masuk) FROM trn_stok as m WHERE m.id_stok = s.id and DATE_FORMAT(m.created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir') - ((SELECT sum(k.barang_keluar) FROM trn_stok as k WHERE k.id_stok = s.id and DATE_FORMAT(k.created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir') + (SELECT sum(r.barang_retur) FROM trn_stok as r WHERE r.id_stok = s.id and DATE_FORMAT(r.created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir')) ,0) as stok");
        $this->db->from('mst_stok as s');
        $this->db->join('mst_product as p', 'p.id = s.id_product', 'inner');
        $this->db->where('p.id_user', $this->id_user);

        return $this->db->get();
        
    }

    public function get_detail_stok_report($id_stok, $tgl_awal, $tgl_akhir)
    {
        $this->db->select('barang_masuk, barang_keluar, barang_retur, created_at');
        $this->db->from('trn_stok');
        $this->db->where('id_stok', $id_stok);
        $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$tgl_awal' and '$tgl_akhir'");

        return $this->db->get();
        
    }


}

/* End of file M_stok.php */
