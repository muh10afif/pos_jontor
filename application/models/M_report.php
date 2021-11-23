<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_report extends CI_Model {

    public function __construct()
    {
        $this->id_user = $this->session->userdata('id_user');

        // $id_o = $this->session->userdata('id_owner');

		// if ($id_o == 0) {
		// 	$this->id_user = $this->session->userdata('id_user');
		// } else {
		// 	$this->id_user = $id_o;
		// }
    }

    // 20-09-2020
    public function cari_kasir()
    {
        if ($this->session->userdata('id_role') == 2 ) {
            $id_user = $this->session->userdata('id_user');
        } else {
            $id_user = $this->session->userdata('id_owner');
        }
        
        $this->db->select('t.created_by, k.nama_karyawan');
        $this->db->from('trn_transaksi as t');
        $this->db->join('mst_karyawan as k', 'k.id_user = t.created_by', 'left');
        $this->db->where('t.id_user', $id_user);
        $this->db->group_by('t.created_by');
        
        return $this->db->get();
    
    }

    // 20-09-2020
    public function cari_total_pendapatan()
    {
        $awal   = $this->input->post('tgl_awal');
        $akhir  = $this->input->post('tgl_akhir');
        $kar    = $this->input->post('id_karyawan');
        
        $this->db->select_sum('total_harga');
        $this->db->from('trn_transaksi');
        
        if ($awal != '' && $akhir != '') {
            $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$awal' AND '$akhir'");
        }
        if ($kar != '') {
            $this->db->where('created_by', $kar);
        }
        
        if ($this->session->userdata('id_role') == 2 ) {
            $this->db->where('id_user', $this->id_user);
        } else {
            $this->db->where('created_by', $this->id_user);
        }

        return $this->db->get();
        
    }

    // 20-09-2020
    public function cari_total_transaksi()
    {
        $awal   = $this->input->post('tgl_awal');
        $akhir  = $this->input->post('tgl_akhir');
        $kar    = $this->input->post('id_karyawan');
        
        $this->db->select('id');
        $this->db->from('trn_transaksi');
        
        if ($awal != '' && $akhir != '') {
            $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$awal' AND '$akhir'");
        }
        if ($kar != '') {
            $this->db->where('created_by', $kar);
        }
        
        if ($this->session->userdata('id_role') == 2 ) {
            $this->db->where('id_user', $this->id_user);
        } else {
            $this->db->where('created_by', $this->id_user);
        }

        return $this->db->get();
    }
    // 20-09-2020
    public function cari_total_pendapatan_r($awal, $akhir, $kar)
    {
        
        $this->db->select_sum('total_harga');
        $this->db->from('trn_transaksi');
        
        if ($awal != '' && $akhir != '') {
            $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$awal' AND '$akhir'");
        }
        if ($kar != '') {
            $this->db->where('created_by', $kar);
        }
        
        if ($this->session->userdata('id_role') == 2 ) {
            $this->db->where('id_user', $this->id_user);
        } else {
            $this->db->where('created_by', $this->id_user);
        }

        return $this->db->get();
        
    }

    // 20-09-2020
    public function cari_total_transaksi_r($awal, $akhir, $kar)
    {
        $this->db->select('id');
        $this->db->from('trn_transaksi');
        
        if ($awal != '' && $akhir != '') {
            $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$awal' AND '$akhir'");
        }
        if ($kar != '') {
            $this->db->where('created_by', $kar);
        }
        
        if ($this->session->userdata('id_role') == 2 ) {
            $this->db->where('id_user', $this->id_user);
        } else {
            $this->db->where('created_by', $this->id_user);
        }

        return $this->db->get();
    }

    // 20-09-2020
    public function cari_data_transaksi()
    {
        $id_transaksi = $this->input->post('id_transaksi');
        
        $this->db->select('id, kode_transaksi, total_harga, created_at, nomer_meja, tunai, potongan_harga, (SELECT nama_karyawan FROM mst_karyawan WHERE mst_karyawan.id_user = trn_transaksi.created_by) as kasir');
        $this->db->from('trn_transaksi');
        $this->db->where('id', $id_transaksi);
           
        return $this->db->get();
        
    }

    // 20-09-2020
    public function cari_kategori()
    {
        $id_transaksi = $this->input->post('id_transaksi');

        $this->db->select('k.id, k.kategori');
        $this->db->from('trn_detail_transaksi as t');
        $this->db->join('mst_product as p', 'p.id = t.id_product', 'inner');
        $this->db->join('mst_kategori as k', 'k.id = p.id_kategori', 'inner');
        $this->db->where('t.id_transaksi', $id_transaksi);
        $this->db->group_by('p.id_kategori');

        return $this->db->get();
        
    }

    // 20-09-2020
    public function cari_detail_det($id_kategori, $id_transaksi)
    {
        $this->db->select('t.*, p.nama_product, p.harga');
        $this->db->from('trn_detail_transaksi as t');
        $this->db->join('mst_product as p', 'p.id = t.id_product', 'inner');
        $this->db->where('t.id_transaksi', $id_transaksi);
        $this->db->where('p.id_kategori', $id_kategori);
        
        return $this->db->get();
        
    }

    // 20-09-2020
    public function get_report_transaksi($awal, $akhir, $kar)
    {
        $this->db->select('id, kode_transaksi, total_harga, created_at, nomer_meja, tunai, potongan_harga, (SELECT nama_karyawan FROM mst_karyawan WHERE mst_karyawan.id_user = trn_transaksi.created_by) as kasir');
        $this->db->from('trn_transaksi');
        
        if ($awal != '' && $akhir != '') {
            $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$awal' AND '$akhir'");
        }
        if ($kar != '') {
            $this->db->where('created_by', $kar);
        }
        
        if ($this->session->userdata('id_role') == 2 ) {
            $this->db->where('id_user', $this->id_user);
        } else {
            $this->db->where('created_by', $this->id_user);
        }

        return $this->db->get();
        
    }

    // 20-09-2020
    public function get_detail_report($id_transaksi)
    {
        $this->db->select('k.kategori, t.*, p.nama_product, p.harga');
        $this->db->from('trn_detail_transaksi as t');
        $this->db->join('mst_product as p', 'p.id = t.id_product', 'inner');
        $this->db->join('mst_kategori as k', 'k.id = p.id_kategori', 'inner');
        $this->db->where('t.id_transaksi', $id_transaksi);

        return $this->db->get();
        
    }

    // 20-09-2020
    public function get_tampil_report()
    {
        $this->_get_datatables_query_tampil_report();

        if ($this->input->post('length') != -1) {
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
            
            return $this->db->get()->result_array();
        }
    }

    // 20-09-2020
    var $kolom_order_tampil_report = [null, 'created_at', 'LOWER(kode_transaksi)', 'total_harga'];
    var $kolom_cari_tampil_report  = ['created_at', 'LOWER(kode_transaksi)', 'total_harga'];
    var $order_tampil_report       = ['created_at' => 'desc'];

    // 20-09-2020
    public function _get_datatables_query_tampil_report()
    {
        $awal   = $this->input->post('tgl_awal');
        $akhir  = $this->input->post('tgl_akhir');
        $kar    = $this->input->post('id_karyawan');
        
        $this->db->select('id, kode_transaksi, total_harga, created_at, nomer_meja, tunai, potongan_harga, (SELECT nama_karyawan FROM mst_karyawan WHERE mst_karyawan.id_user = trn_transaksi.created_by) as kasir');
        $this->db->from('trn_transaksi');
        
        if ($awal != '' && $akhir != '') {
            $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$awal' AND '$akhir'");
        }
        if ($kar != '') {
            $this->db->where('created_by', $kar);
        }
        
        if ($this->session->userdata('id_role') == 2 ) {
            $this->db->where('id_user', $this->id_user);
        } else {
            $this->db->where('created_by', $this->id_user);
        }
        
        $b = 0;
        
        $input_cari = strtolower($_POST['search']['value']);
        $kolom_cari = $this->kolom_cari_tampil_report;

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

            $kolom_order = $this->kolom_order_tampil_report;
            $this->db->order_by($kolom_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            
        } elseif (isset($this->order_tampil_report)) {
            
            $order = $this->order_tampil_report;
            $this->db->order_by(key($order), $order[key($order)]);
            
        }
        
    }

    // 20-09-2020
    public function count_tampil_report()
    {
        $awal   = $this->input->post('tgl_awal');
        $akhir  = $this->input->post('tgl_akhir');
        $kar    = $this->input->post('id_karyawan');
        
        $this->db->select('id, kode_transaksi, total_harga, created_at, nomer_meja, tunai, potongan_harga, (SELECT nama_karyawan FROM mst_karyawan WHERE id_user = trn_transaksi.created_by) as kasir');
        $this->db->from('trn_transaksi');
        
        if ($awal != '' && $akhir != '') {
            $this->db->where("DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '$awal' AND '$akhir'");
        }
        if ($kar != '') {
            $this->db->where('created_by', $kar);
        }
        
        if ($this->session->userdata('id_role') == 2 ) {
            $this->db->where('id_user', $this->id_user);
        } else {
            $this->db->where('created_by', $this->id_user);
        }

        return $this->db->count_all_results();
    }

    // 20-09-2020
    public function count_filtered_tampil_report()
    {
        $this->_get_datatables_query_tampil_report();

        return $this->db->get()->num_rows();
        
    }

    var $table = 'trn_transaksi';

    // 16-09-2020

    public function tgl_malam($tgl)
	{
		$a = date('Y-m-d H:i:s', strtotime("2020-09-16 08:00:00 +12 hours"));

		echo $a;
	}
 
    function get_datatables()
    {
        $this->db->select('trn_transaksi.*, mst_user.username')
        ->from('trn_transaksi')
        ->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        ->where('tunai !=', 0);
        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != '') {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {
                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
            }
        	
        }

        $this->db->order_by('trn_transaksi.id', 'asc');
        $this->db->where('mst_user.id', $this->id_user);
        $query = $this->db->get();
        return $query->result();
    }

    function get_datatables_blm()
    {
        $this->db->select('trn_transaksi.*, mst_user.username')
        ->from('trn_transaksi')
        ->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        ->where('tunai', 0);
        // if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        // {
        // 	$start = $this->input->post('start_date');
        // 	$end = $this->input->post('end_date');
        // 	$this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
        // }

        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != '') {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {
                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
            }
        	
        }

        $this->db->order_by('trn_transaksi.id', 'asc');
        $this->db->where('mst_user.id', $this->id_user);
        $query = $this->db->get();
        return $query->result();
    }

    function get_datatables_admin()
    {
        $this->db->select('trn_transaksi.*, mst_user.username')
        ->from('trn_transaksi')
        ->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        // ->where('trn_transaksi.id_user', $this->session->userdata('id_user'))
        ->where('tunai !=', 0);
        // if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        // {
        //     $start = $this->input->post('start_date');
        //     $end = $this->input->post('end_date');
        //     $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
        // }

        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != "") {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {

                    // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
            }
        	
        }

        $this->db->order_by('trn_transaksi.id', 'asc');
        // $this->db->where('mst_user.id', $this->id_user);
        $this->db->where('trn_transaksi.created_by', $this->id_user);
        $query = $this->db->get();
        return $query->result();
    }

    function get_datatables_admin_blm()
    {
        $this->db->select('trn_transaksi.*, mst_user.username')
        ->from('trn_transaksi')
        ->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        // ->where('trn_transaksi.id_user', $this->session->userdata('id_user'))
        ->where('tunai', 0);
        // if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        // {
        //     $start = $this->input->post('start_date');
        //     $end = $this->input->post('end_date');
        //     $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
        // }

        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != '') {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {
                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
            }
        	
        }

        $this->db->order_by('trn_transaksi.id', 'asc');
        // $this->db->where('mst_user.id', $this->id_user);
        $this->db->where('trn_transaksi.created_by', $this->id_user);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->db->select('trn_transaksi.*, mst_user.username');
        $this->db->from('trn_transaksi');
        $this->db->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        ->where('tunai !=', 0);
        // if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        // {
        // 	$start = $this->input->post('start_date');
        // 	$end = $this->input->post('end_date');
        // 	$this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
        // }
        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != '') {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {
                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
            }
        	
        }
        $this->db->where('mst_user.id', $this->id_user);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_blm()
    {
        $this->db->select('trn_transaksi.*, mst_user.username');
        $this->db->from('trn_transaksi');
        $this->db->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        ->where('tunai', 0);
        // if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        // {
        // 	$start = $this->input->post('start_date');
        // 	$end = $this->input->post('end_date');
        // 	$this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
        // }
        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != '') {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {
                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
            }
        	
        }
        $this->db->where('mst_user.id', $this->id_user);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_admin()
    {
        $this->db->select('trn_transaksi.*, mst_user.username');
        $this->db->from('trn_transaksi');
        $this->db->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        // ->where('trn_transaksi.id_user', $this->session->userdata('id_user'))
        ->where('tunai !=', 0);
        // if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        // {
        //     $start = $this->input->post('start_date');
        //     $end = $this->input->post('end_date');
        //     $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
        // }
        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != '') {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {
                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
            }
        	
        }
        // $this->db->where('mst_user.id', $this->id_user);
        $this->db->where('trn_transaksi.created_by', $this->id_user);
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_admin_blm()
    {
        $this->db->select('trn_transaksi.*, mst_user.username');
        $this->db->from('trn_transaksi');
        $this->db->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        // ->where('trn_transaksi.id_user', $this->session->userdata('id_user'))
        ->where('tunai', 0);
        // if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        // {
        //     $start = $this->input->post('start_date');
        //     $end = $this->input->post('end_date');
        //     $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
        // }
        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != '') {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {
                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN'".$start."' AND '".$end."'");
            }
        	
        }
        // $this->db->where('mst_user.id', $this->id_user);
        $this->db->where('trn_transaksi.created_by', $this->id_user);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
        $this->db->select('trn_transaksi.*, mst_user.username')
        ->from('trn_transaksi')
        ->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        ->where('tunai !=', 0);
        // if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        // {
        // 	$start = $this->input->post('start_date');
        // 	$end = $this->input->post('end_date');
        // 	$this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
        // }
        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != '') {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {
                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
            }
        	
        }
        $this->db->where('mst_user.id', $this->id_user);
        return $this->db->count_all_results();
    }

    public function count_all_blm()
    {
        $this->db->select('trn_transaksi.*, mst_user.username')
        ->from('trn_transaksi')
        ->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        ->where('tunai', 0);
        // if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        // {
        // 	$start = $this->input->post('start_date');
        // 	$end = $this->input->post('end_date');
        // 	$this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
        // }
        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != '') {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {
                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
            }
        	
        }
        $this->db->where('mst_user.id', $this->id_user);
        return $this->db->count_all_results();
    }

    public function count_all_admin()
    {
        $this->db->select('trn_transaksi.*, mst_user.username')
        ->from('trn_transaksi')
        ->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        // ->where('trn_transaksi.id_user', $this->session->userdata('id_user'))
        ->where('tunai !=', 0);
        // if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        // {
        //     $start = $this->input->post('start_date');
        //     $end = $this->input->post('end_date');
        //     $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
        // }
        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != '') {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {
                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
            }
        	
        }
        // $this->db->where('mst_user.id', $this->id_user);
        $this->db->where('trn_transaksi.created_by', $this->id_user);
        return $this->db->count_all_results();
    }

    public function count_all_admin_blm()
    {
        $this->db->select('trn_transaksi.*, mst_user.username')
        ->from('trn_transaksi')
        ->join('mst_user', 'mst_user.id = trn_transaksi.id_user', 'left')
        ->where('trn_transaksi.kode_transaksi <> ""')
        // ->where('trn_transaksi.id_user', $this->session->userdata('id_user'))
        ->where('tunai', 0);
        // if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        // {
        //     $start = $this->input->post('start_date');
        //     $end = $this->input->post('end_date');
        //     $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
        // }
        if($this->input->post('start_date') != '' && $this->input->post('end_date') != '')
        {
            
            $shift = $this->input->post('shift');
         
            if ($shift != '') {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
                // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

                $tgl    = $start;  
                $tgl_m  = $end;

                if ($shift == 'pagi') {
                    $tgl_awal   = "$tgl 08:00:00";
                    $tgl_akhir  = "$tgl_m 20:00:00";
                } else {
                    $tgl_awal   = "$tgl 20:00:00";
                    $tgl_akhir  = "$tgl_m 08:00:00";
                }
            
                $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
            } else {

                $start  = $this->input->post('start_date');
                $end    = $this->input->post('end_date');

                $this->db->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'");
            }
        	
        }
        // $this->db->where('mst_user.id', $this->id_user);
        $this->db->where('trn_transaksi.created_by', $this->id_user);
        return $this->db->count_all_results();
    }

    public function get($id = null)
    {
        $this->db->from($this->table)
        ->where('trn_transaksi.kode_transaksi <> ""');
        if($id)
        {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    public function get_detail($id)
    {
        $this->db->select('trn_detail_transaksi.*, trn_transaksi.kode_transaksi, trn_transaksi.total_harga, mst_product.nama_product')
        ->from('trn_detail_transaksi')
        ->join('trn_transaksi', 'trn_transaksi.id = trn_detail_transaksi.id_transaksi')
        ->join('mst_product', 'mst_product.id = trn_detail_transaksi.id_product')
        ->where('trn_transaksi.id_user', $this->id_user);
        $query = $this->db->get();
        return $query->result();

    }

    public function get_table()
    {
        $this->db->from($this->table)
        ->where('kode_transaksi <> ""');
        // ->where('id_user', $this->id_user)
        $id_o = $this->session->userdata('id_owner');

		if ($id_o == 0) {
            $this->db->where('id_user', $this->id_user);
		} else {
            $this->db->where('created_by', $this->id_user);
        }
        $this->db->order_by('id', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_table_tanggal($date)
    {
        $this->db->from($this->table)
        ->where('kode_transaksi <> ""')
        ->where("DATE_FORMAT(created_at, '%Y-%m-%d') =", $date);
        // ->where('id_user', $this->id_user)
        $id_o = $this->session->userdata('id_owner');

		if ($id_o == 0) {
            $this->db->where('id_user', $this->id_user);
		} else {
            $this->db->where('created_by', $this->id_user);
        }
        $this->db->order_by('id', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_table_periode($start, $end, $shift)
    {
        if ($shift != '') {

            // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
            // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

            $tgl    = $start;  
            $tgl_m  = $end;

            if ($shift == 'pagi') {
                $tgl_awal   = "$tgl 08:00:00";
                $tgl_akhir  = "$tgl_m 20:00:00";
            } else {
                $tgl_awal   = "$tgl 20:00:00";
                $tgl_akhir  = "$tgl_m 08:00:00";
            }

            $sh = "created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'";

        } else {

            $sh = "DATE_FORMAT(created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'";
        }

        

    	$this->db->from($this->table)
        ->where('kode_transaksi <> ""')
        ->where("$sh");
        $id_o = $this->session->userdata('id_owner');

		if ($id_o == 0) {
            $this->db->where('id_user', $this->id_user);
		} else {
            $this->db->where('created_by', $this->id_user);
        }
    	$this->db->order_by('id', 'asc');
    	$query = $this->db->get();
    	return $query->result();
    }

    public function get_total()
    {
        $this->db->select('sum(total_harga) as total')
        ->from('trn_transaksi')
        ->where('trn_transaksi.kode_transaksi <> ""');
        $id_o = $this->session->userdata('id_owner');

		if ($id_o == 0) {
            $this->db->where('id_user', $this->id_user);
		} else {
            $this->db->where('created_by', $this->id_user);
        }
        $query = $this->db->get();
        return $query->row();
    }

    public function get_total_tanggal($date)
    {
        $this->db->select('sum(total_harga) as total')
        ->from('trn_transaksi')
        ->where("DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') =", $date)
        ->where('trn_transaksi.kode_transaksi <> ""');
        // ->where('id_user', $this->id_user);

        $id_o = $this->session->userdata('id_owner');

		if ($id_o == 0) {
            $this->db->where('id_user', $this->id_user);
		} else {
            $this->db->where('created_by', $this->id_user);
        }

        $query = $this->db->get();
        return $query->row();
    }

    public function get_total_periode($start, $end, $shift)
    {
        if ($shift != '') {

            // $tgl    = date("Y-m-d", now('Asia/Jakarta'));  
            // $tgl_m  = date('Y-m-d', strtotime("$tgl +1 days"));

            $tgl    = $start;  
            $tgl_m  = $end;

            if ($shift == 'pagi') {
                $tgl_awal   = "$tgl 08:00:00";
                $tgl_akhir  = "$tgl_m 20:00:00";
            } else {
                $tgl_awal   = "$tgl 20:00:00";
                $tgl_akhir  = "$tgl_m 08:00:00";
            }

            $sh = "trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'";

        } else {

            $sh = "DATE_FORMAT(trn_transaksi.created_at, '%Y-%m-%d') BETWEEN '".$start."' AND '".$end."'";
        }

        $this->db->select('sum(total_harga) as total')
        ->from('trn_transaksi')
        ->where('trn_transaksi.kode_transaksi <> ""')
        ->where("$sh");
        $id_o = $this->session->userdata('id_owner');

		if ($id_o == 0) {
            $this->db->where('id_user', $this->id_user);
		} else {
            $this->db->where('created_by', $this->id_user);
        }
        $query = $this->db->get();
        return $query->row();
    }

}

/* End of file M_report.php */
/* Location: ./application/models/M_report.php */