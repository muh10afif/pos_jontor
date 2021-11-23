<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_transaksi extends CI_Model {

    /**
     * Class constructor.
     */
    public function __construct()
    {
        // if ($this->session->userdata('id_role') == 2 ) {
        //     $this->id_user = $this->session->userdata('id_user');
        // } else {
        //     $this->id_user = $this->session->userdata('id_owner');
        // }

        $this->id_user = $this->session->userdata('id_user');
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

    public function cari_data_order($tabel, $where, $order)
    {
        $this->db->order_by($order);

        return $this->db->get_where($tabel, $where);
    }

    public function cari_data_tr($id_tr)
    {
        $this->db->select('*, a.id as id_d_tr, b.id as id_product');
        $this->db->from("trn_detail_transaksi as a");
        $this->db->join('mst_product as b', 'b.id = a.id_product', 'inner');
        $this->db->join('mst_discount as d', 'd.id_product = b.id', 'left');
        $this->db->where('a.id_transaksi', $id_tr);
        $this->db->order_by('a.id', 'asc');

        return $this->db->get();
    }
    
    public function get_total_pesanan($id_tr)
    {
        $this->db->select_sum('subtotal');
        $this->db->from('trn_detail_transaksi');
        $this->db->where('id_transaksi', $id_tr);
        
        
        return $this->db->get();
    }

    public function get_diskon($id_tr)
    {
        $this->db->select('sum(d.discount * t.jumlah) as tot_diskon');
		$this->db->from('trn_detail_transaksi as t');
		$this->db->join('mst_discount as d', 'd.id_product = t.id_product', 'inner');
        $this->db->where('t.id_transaksi', $id_tr);
        
        return $this->db->get();
    }

    public function get_diskon_2($id_tr)
    {
        $this->db->select_sum('t.total_discount');
		$this->db->from('trn_detail_transaksi as t');
        $this->db->where('t.id_transaksi', $id_tr);
        
        return $this->db->get();
    }

    public function jml_diskon($id_product)
    {
        $this->db->select_sum('discount');
        $this->db->from('mst_discount');
        $this->db->where('id_product', $id_product);
        
        return $this->db->get();
    }

    // 11-07-2020
    public function get_tot_subtotal($id_tr)
    {
        $this->db->select_sum('subtotal');
        $this->db->from('trn_detail_transaksi');
        $this->db->where('id_transaksi', $id_tr);
        
        return $this->db->get();
        
    }

    public function cari_data_kd_tr($tabel, $where)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->where($where);
        $this->db->where('kode_transaksi !=', '');
        $this->db->order_by('id', 'desc');

        return $this->db->get();
        
    }

    // 12-07-2020
    public function get_diskon_sbl($id_tr, $id_product)
    {
        $this->db->select_sum('total_discount');
        $this->db->from('trn_detail_transaksi');
        $this->db->where('id_transaksi', $id_tr);
        $this->db->where('id_product', $id_product);
        
        return $this->db->get();
    }

    public function cari_detail_tr($id_transaksi)
    {
        $this->db->select('*');
        $this->db->from('trn_detail_transaksi as t');
        $this->db->join('mst_product as p', 'p.id = t.id_product', 'inner');
        $this->db->where('t.id_transaksi', $id_transaksi);
        
        return $this->db->get();
    }

    // 15-07-2020
    public function get_diskon_tr($id_tr)
    {
        $this->db->select_sum('total_discount');
        $this->db->from('trn_detail_transaksi');
        $this->db->where('id_transaksi', $id_tr);

        return $this->db->get();
    }

    // 28-09-2020
    public function get_product($id_kat, $id_user)
    {
        $this->db->select('*');
        $this->db->from('mst_product');
        $this->db->where('id_kategori', $id_kat);
        $this->db->where('id_user', $id_user);
        $this->db->where('status_tampil', 1);
        $this->db->order_by('nama_product', 'asc');
        
        return $this->db->get();
        
    }

    public function get_kategori($id_tr)
    {
        $this->db->select('k.kategori, k.id as id_kategori');
        $this->db->from('trn_detail_transaksi as t');
        $this->db->join('mst_product as p', 'p.id = t.id_product', 'inner');
        $this->db->join('mst_kategori as k', 'k.id = p.id_kategori', 'inner');
        $this->db->where('t.id_transaksi', $id_tr);
        $this->db->group_by('k.id');
        
        return $this->db->get();
    }

    public function get_product_kat($id_kat, $id_tr)
    {
        $this->db->select('p.nama_product, p.harga, t.*');
        $this->db->from('trn_detail_transaksi as t');
        $this->db->join('mst_product as p', 'p.id = t.id_product', 'inner');
        $this->db->join('mst_kategori as k', 'k.id = p.id_kategori', 'inner');
        $this->db->where('t.id_transaksi', $id_tr);
        $this->db->where('k.id', $id_kat);
        
        return $this->db->get();
    }

    // 20-07-2020
    public function get_data_kdtr_kosong()
    {
        return $this->db->get_where('trn_transaksi', ['kode_transaksi' => '']);
    }

    public function get_total_hari_ini($hari)
    {
        $this->db->select('sum(total_harga) as total')
        ->from('trn_transaksi');
        // ->where("DATE_FORMAT(created_at, '%Y-%m-%d') =", date('Y-m-d'));
        // $this->db->where('id_user', $this->id_user);
        $id_o = $this->session->userdata('id_owner');

		if ($id_o == 0) {
            $this->db->where('id_user', $this->id_user);
		} else {
            $this->db->where('created_by', $this->id_user);
        }

        $c = date("Y-m-d", now('Asia/Jakarta'));
		
        $a = date("Y-m-d H:i:s", now('Asia/Jakarta'));
        $b = date("$c 20:00:00");
        $f = date("$c 08:00:00");

        if ($a > $b) {
            $d = "Malam";

            $tgl_m  = date('Y-m-d', strtotime("$c +1 days"));

            $tgl_awal   = "$c 20:00:00";
            $tgl_akhir  = "$tgl_m 08:00:00";
        } else {
            if ($a > $f) {
                $d = "Pagi";
                $tgl_awal   = "$c 08:00:00";
                $tgl_akhir  = "$c 20:00:00";
            } else {
                $d = "Malam";
                $tgl_m  = date('Y-m-d', strtotime("$c -1 days"));

                $tgl_awal   = "$tgl_m 20:00:00";
                $tgl_akhir  = "$c 08:00:00";
            }
        }

        // $tgl_hi = date('Y-m-d', now("Asia/Jakarta"));

        // if ($hari == 'Pagi') {
        //     $tgl_awal   = "$tgl_hi 08:00:00";
        //     $tgl_akhir  = "$tgl_hi 20:00:00";
        // } else {
        //     $tgl_m  = date('Y-m-d', strtotime("$tgl_hi +1 days"));
        //     $tgl_b  = date('Y-m-d', strtotime("$tgl_hi -1 days"));

        //     $tgl_awal   = "$tgl_hi 20:00:00";
        //     $tgl_akhir  = "$tgl_m 08:00:00";
        // }
    
        $this->db->where("created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");

        $query = $this->db->get();
        return $query->row();
    }

    // 18-09-2020
    public function transaksi_hari_ini($hari)
    {
        $this->db->select('id')
        ->from('trn_transaksi');
        // ->where("DATE_FORMAT(created_at, '%Y-%m-%d') =", date('Y-m-d'));
        // $this->db->where('id_user', $this->id_user);
        $id_o = $this->session->userdata('id_owner');

		if ($id_o == 0) {
            $this->db->where('id_user', $this->id_user);
		} else {
            $this->db->where('created_by', $this->id_user);
        }

        $c = date("Y-m-d", now('Asia/Jakarta'));
		
        $a = date("Y-m-d H:i:s", now('Asia/Jakarta'));
        $b = date("$c 20:00:00");
        $f = date("$c 08:00:00");

        if ($a > $b) {
            $d = "Malam";

            $tgl_m  = date('Y-m-d', strtotime("$c +1 days"));

            $tgl_awal   = "$c 20:00:00";
            $tgl_akhir  = "$tgl_m 08:00:00";
        } else {
            if ($a > $f) {
                $d = "Pagi";
                $tgl_awal   = "$c 08:00:00";
                $tgl_akhir  = "$c 20:00:00";
            } else {
                $d = "Malam";
                $tgl_m  = date('Y-m-d', strtotime("$c -1 days"));

                $tgl_awal   = "$tgl_m 20:00:00";
                $tgl_akhir  = "$c 08:00:00";
            }
        }
    
        $this->db->where("created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
        
        return $this->db->get();
    }

    // 19-09-2020
    public function produk_hari_ini($hari)
    {
        $this->db->select('d.id_product');
        $this->db->from('trn_transaksi as t');
        $this->db->join('trn_detail_transaksi as d', 'd.id_transaksi = t.id', 'inner');
        // $this->db->where("DATE_FORMAT(t.created_at, '%Y-%m-%d') =", date('Y-m-d'));
        
        $id_o = $this->session->userdata('id_owner');

		if ($id_o == 0) {
            $this->db->where('t.id_user', $this->id_user);
		} else {
            $this->db->where('t.created_by', $this->id_user);
        }

        $tgl_hi = date('Y-m-d', now("Asia/Jakarta"));

        $c = date("Y-m-d", now('Asia/Jakarta'));
		
        $a = date("Y-m-d H:i:s", now('Asia/Jakarta'));
        $b = date("$c 20:00:00");
        $f = date("$c 08:00:00");

        if ($a > $b) {
            $d = "Malam";

            $tgl_m  = date('Y-m-d', strtotime("$c +1 days"));

            $tgl_awal   = "$c 20:00:00";
            $tgl_akhir  = "$tgl_m 08:00:00";
        } else {
            if ($a > $f) {
                $d = "Pagi";
                $tgl_awal   = "$c 08:00:00";
                $tgl_akhir  = "$c 20:00:00";
            } else {
                $d = "Malam";
                $tgl_m  = date('Y-m-d', strtotime("$c -1 days"));

                $tgl_awal   = "$tgl_m 20:00:00";
                $tgl_akhir  = "$c 08:00:00";
            }
        }
    
        $this->db->where("t.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");

        $this->db->group_by('d.id_product');
        
        return $this->db->get();
    }

    public function get_profit($hari)
    {
        // $query = $this->db->query('SELECT mst_product.hpp, trn_detail_transaksi.subtotal, trn_detail_transaksi.jumlah FROM trn_detail_transaksi INNER JOIN mst_product ON trn_detail_transaksi.id_product = mst_product.id WHERE trn_detail_transaksi.created_at ="'.date('Y-m-d').'"')->result();

        $this->db->select('mst_product.hpp, trn_transaksi.total_harga, trn_detail_transaksi.jumlah');
        $this->db->from('trn_detail_transaksi');
        $this->db->join('mst_product', 'trn_detail_transaksi.id_product = mst_product.id', 'inner');
        $this->db->join('trn_transaksi', 'trn_transaksi.id = trn_detail_transaksi.id_transaksi', 'inner');
        
        // $this->db->where("DATE_FORMAT(trn_detail_transaksi.created_at, '%Y-%m-%d') =", date('Y-m-d', now('Asia/Jakarta')));

        $id_o = $this->session->userdata('id_owner');

		if ($id_o == 0) {
            // $this->id_user = $this->session->userdata('id_user');
            $this->db->where('trn_transaksi.id_user', $this->id_user);
		} else {
            // $this->id_user = $id_o;
            $this->db->where('trn_transaksi.created_by', $this->id_user);
        }
        
        //$this->db->where('mst_product.id_user', $this->session->userdata('id_user'));

        $c = date("Y-m-d", now('Asia/Jakarta'));
		
        $a = date("Y-m-d H:i:s", now('Asia/Jakarta'));
        $b = date("$c 20:00:00");
        $f = date("$c 08:00:00");

        if ($a > $b) {
            $d = "Malam";

            $tgl_m  = date('Y-m-d', strtotime("$c +1 days"));

            $tgl_awal   = "$c 20:00:00";
            $tgl_akhir  = "$tgl_m 08:00:00";
        } else {
            if ($a > $f) {
                $d = "Pagi";
                $tgl_awal   = "$c 08:00:00";
                $tgl_akhir  = "$c 20:00:00";
            } else {
                $d = "Malam";
                $tgl_m  = date('Y-m-d', strtotime("$c -1 days"));

                $tgl_awal   = "$tgl_m 20:00:00";
                $tgl_akhir  = "$c 08:00:00";
            }
        }
    
        $this->db->where("trn_transaksi.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'");
        $this->db->group_by('trn_transaksi.id');

        return $this->db->get();
        
    }

    // 23-09-2020
    public function cari_nilai_diskon($id_product)
    {

        $this->db->select('d.satuan, d.discount, p.harga');
        $this->db->from('mst_discount d');
        $this->db->join('mst_product p', 'p.id = d.id_product', 'inner');
        $this->db->where('d.id_product', $id_product);
        $this->db->where('d.id_user', $this->id_user);
        
        $a = $this->db->get()->row_array();

        if ($a['satuan'] == '%') {
            $dis = ($a['discount'] / 100) * $a['harga'];
        } else {
            $dis = $a['discount'];
        }

        return ($dis == null) ? 0 : $dis;
        
    }
}

/* End of file M_transaksi.php */
/* Location: ./application/models/M_transaksi.php */