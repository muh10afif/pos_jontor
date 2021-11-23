<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function __construct()
    {
		parent::__construct();
		if($this->session->userdata('username') == "")
        {
            $this->session->set_flashdata('danger', '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>Anda belum Log in</div>');
            redirect(base_url(), 'refresh');
		}
		
        $this->id_user = $this->session->userdata('id_user');
    }

	public function index()
	{
		$total_p = $this->report->cari_total_pendapatan()->row_array();
		$total_t = $this->report->cari_total_transaksi()->num_rows();

		$data 	= [
			'title'			=> 'Report Transaksi',
			'report'    	=> $this->report->get()->result(),
			'kasir'			=> $this->report->cari_kasir()->result_array(),
			't_pendapatan'	=> ($total_p['total_harga'] == null) ? 0 : $total_p['total_harga'],
			't_transaksi' 	=> ($total_t == null) ? 0 : $total_t,
			'isi'			=> 'report/read'
		];
		$this->load->view('template/wrapper', $data);
	}

	public function tes()
	{
		$a = date('Y-m-d H:i:s', strtotime("2020-09-16 08:00:00 +12 hours"));

		$tgl_m = date('Y-m-d', strtotime("2020-09-16 +1 days"));

		echo $tgl_m;
	}

	// 20-09-2020
	public function tampil_report()
	{

		$list 	= $this->report->get_tampil_report();
		$data 	= [];
		$no		= 1;

		foreach($list as $r)
		{
			$row = [];
			$row[] = $no++.'.';
			$row[] = date('d-m-Y H:i:s', strtotime($r['created_at']));
			$row[] = $r['kode_transaksi'];
			$row[] = 'Rp. '.number_format($r['total_harga']);
			$row[] = '<button type="button" class="btn btn-primary btn-sm detail" data-id="'.$r['id'].'">Detail</button>';
			$data[] = $row;
		}

		$output = [
					"recordsTotal" 		=> $this->report->count_tampil_report(),
					"recordsFiltered" 	=> $this->report->count_filtered_tampil_report(),
					"data" 				=> $data,
					];

		echo json_encode($output);
		
	}

	// 20-09-2020
	public function tampil_detail()
	{
		$data = ['trn'		=> $this->report->cari_data_transaksi()->row_array(),
				 'kategori'	=> $this->report->cari_kategori()->result_array()
				];

		$this->load->view('report/V_detail_transaksi', $data);
		
	}

	// 20-09-2020
	public function tampil_total()
	{
		$total_p = $this->report->cari_total_pendapatan()->row_array();
		$total_t = $this->report->cari_total_transaksi()->num_rows();

		echo json_encode(['t_pendapatan' => ($total_p['total_harga'] == null) ? 0 : $total_p['total_harga'], 't_transaksi' => ($total_t == null) ? 0 : $total_t]);
	}

	// 20-09-2020
    public function download_file()
    {
        $tgl_awal   	= $this->input->post('tgl_awal');
        $tgl_akhir  	= $this->input->post('tgl_akhir');
        $id_karyawan  	= $this->input->post('id_karyawan');
        $jns        	= $this->input->post('jns');

        $this->db->select("DATE_FORMAT(created_at, '%Y-%m-%d') as tanggal");
		$this->db->from('trn_transaksi');

		if ($this->session->userdata('id_role') == 2 ) {
            $this->db->where('id_user', $this->id_user);
        } else {
            $this->db->where('created_by', $this->id_user);
		}
		
        $this->db->order_by('created_at', 'asc');
        $this->db->limit(1);
        $tgl = $this->db->get()->row_array();

        if ($tgl_awal != '') {
            $tgl_awal = $tgl_awal;
        } else {
            $tgl_awal = $tgl['tanggal'];
        }

        if ($tgl_akhir != '') {
            $tgl_akhir = $tgl_akhir;
        } else {
            $tgl_akhir = date("Y-m-d", now('Asia/Jakarta'));
		}

		$total_p = $this->report->cari_total_pendapatan()->row_array();
		$total_t = $this->report->cari_total_transaksi()->num_rows();

        $data   = [ 'report'        => 'Report Transaksi',
                    'tgl_awal'      => $tgl_awal,
                    'tgl_akhir'     => $tgl_akhir,
                    'jns'           => $jns,
					'judul'         => 'Report Transaksi',
					'total_p' 		=> ($total_p['total_harga'] == null) ? 0 : $total_p['total_harga'],
					'total_t' 		=> ($total_t == null) ? 0 : $total_t,
                    'trn'        	=> $this->report->get_report_transaksi($tgl_awal, $tgl_akhir, $id_karyawan)->result_array()
                  ]; 

        if ($jns == 'excel') {

            $temp = 'template/template_excel';
            $this->template->load("$temp", 'report/V_export_transaksi', $data);

        } else {

            ob_start();
            $this->load->view('report/V_export_transaksi', $data);
            $html = ob_get_contents();
            // var_dump($html);die();
                ob_end_clean();
                require_once('./assets/html2pdf/html2pdf.class.php');
            $pdf = new HTML2PDF('P','A4','en', false, 'ISO-8859-15',array(10, 10, 10, 10));
            $pdf->WriteHTML($html);
            $pdf->Output('Laporan Transaksi.pdf', 'I');
        } 
        
    }

	public function read()
	{
        if($this->session->userdata('id_role') > 1)
        {
    		$list 	= $this->report->get_datatables();
    		$data 	= [];
    		$no		= 1;
    		foreach($list as $report)
    		{
    			$row = [];
    			$row[] = $no++.'.';
                $row[] = date('d-m-Y H:i:s', strtotime($report->created_at));
                $row[] = $report->kode_transaksi ? $report->kode_transaksi : 'Tidak Ada';
                $row[] = 'Rp. '.number_format($report->total_harga);
                $row[] = '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#detail'.$report->id.'"><i class="mdi mdi-information-outline"></i></button>';
                $data[] = $row;
    		}
    		$output = [
                        "recordsTotal" 		=> $this->report->count_all(),
                        "recordsFiltered" 	=> $this->report->count_filtered(),
                        "data" 				=> $data,
    		          ];
            echo json_encode($output);
        }
        else
        {
            $list   = $this->report->get_datatables_admin();
            $data   = [];
            $no     = 1;
            foreach($list as $report)
            {
                $row = [];
                $row[] = $no++.'.';
                $row[] = date('d-m-Y H:i:s', strtotime($report->created_at));
                $row[] = $report->kode_transaksi ? $report->kode_transaksi : 'Tidak Ada';
                $row[] = 'Rp. '.number_format($report->total_harga);
                $row[] = '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#detail'.$report->id.'"><i class="mdi mdi-information-outline"></i></button>';
                $data[] = $row;
            }
            $output = [
                        "recordsTotal"      => $this->report->count_all_admin(),
                        "recordsFiltered"   => $this->report->count_filtered_admin(),
                        "data"              => $data,
                      ];
            echo json_encode($output);
        }
	}

	public function read_blm()
	{
        if($this->session->userdata('id_role') > 1)
        {
    		$list 	= $this->report->get_datatables_blm();
    		$data 	= [];
    		$no		= 1;
    		foreach($list as $report)
    		{
    			$row = [];
    			$row[] = $no++.'.';
                $row[] = date('d-m-Y', strtotime($report->created_at));
                $row[] = $report->kode_transaksi ? $report->kode_transaksi : 'Tidak Ada';
                $row[] = 'Rp. '.number_format($report->total_harga);
                $row[] = '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#detail'.$report->id.'"><i class="mdi mdi-information-outline"></i></button>';
                $data[] = $row;
    		}
    		$output = [
                        "recordsTotal" 		=> $this->report->count_all_blm(),
                        "recordsFiltered" 	=> $this->report->count_filtered_blm(),
                        "data" 				=> $data,
    		          ];
            echo json_encode($output);
        }
        else
        {
            $list   = $this->report->get_datatables_admin_blm();
            $data   = [];
            $no     = 1;
            foreach($list as $report)
            {
                $row = [];
                $row[] = $no++.'.';
                $row[] = date('d-m-Y', strtotime($report->created_at));
                $row[] = $report->kode_transaksi ? $report->kode_transaksi : 'Tidak Ada';
                $row[] = 'Rp. '.number_format($report->total_harga);
                $row[] = '<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#detail'.$report->id.'"><i class="mdi mdi-information-outline"></i></button>';
                $data[] = $row;
            }
            $output = [
                        "recordsTotal"      => $this->report->count_all_admin_blm(),
                        "recordsFiltered"   => $this->report->count_filtered_admin_blm(),
                        "data"              => $data,
                      ];
            echo json_encode($output);
        }
	}

	public function cetak()
    {
    	if($this->input->post('pdf') !== null)
    	{
    		if(!empty($this->input->post('start_date')) && !empty($this->input->post('end_date')))
    		{
				if ($this->input->post('shift') != '') {
					$x = date('Y-m-d', strtotime($this->input->post('start_date'))).'/'.date('Y-m-d', strtotime($this->input->post('end_date'))).'/'.$this->input->post('shift');
				} else {
					$x = date('Y-m-d', strtotime($this->input->post('start_date'))).'/'.date('Y-m-d', strtotime($this->input->post('end_date')));
				}

    			redirect('Report/cetak_pdf/'.$x);
    		}
    		elseif(!empty($this->input->post('start_date')))
    		{
    			$x = date('Y-m-d', strtotime($this->input->post('start_date')));
    			redirect('Report/cetak_pdf/'.$x);
    		}
    		elseif(!empty($this->input->post('end_date')))
    		{
    			$x = date('Y-m-d', strtotime($this->input->post('end_date')));
    			redirect('Report/cetak_pdf/'.$x);
    		}
    		else
    		{
    			redirect('Report/cetak_pdf');
    		}
    	}
    	else
    	{
    		if(!empty($this->input->post('start_date')) && !empty($this->input->post('end_date')))
    		{
    			$x = date('Y-m-d', strtotime($this->input->post('start_date'))).'/'.date('Y-m-d', strtotime($this->input->post('end_date')));
    			redirect('Report/cetak_excel/'.$x);
    		}
    		elseif(!empty($this->input->post('start_date')))
    		{
    			$x = date('Y-m-d', strtotime($this->input->post('start_date')));
    			redirect('Report/cetak_excel/'.$x);
    		}
    		elseif(!empty($this->input->post('end_date')))
    		{
    			$x = date('Y-m-d', strtotime($this->input->post('end_date')));
    			redirect('Report/cetak_excel/'.$x);
    		}
    		else
    		{
    			redirect('Report/cetak_excel');
    		}
    	}
    }

    public function cetak_pdf($x = null)
    {
    	if($x != null)
    	{
    		if($this->uri->segment(3) && empty($this->uri->segment(4)))
    		{
    			$tanggal 	= $this->uri->segment(3);
    			$ket 		= 'Laporan Rekapan Transaksi Tanggal '.date('d-m-Y', strtotime($tanggal));
				$laporan 	= $this->report->get_table_tanggal($tanggal);
	            $total 		= $this->report->get_total_tanggal($tanggal);
    		}
    		else
    		{
				if ($this->uri->segment(5)) {
					$sh = $this->uri->segment(5);
					$shi = " Shift ".ucwords($this->uri->segment(5));
				} else {
					$sh  = '';
					$shi = '';
				}
				$start 		= $this->uri->segment(3);
				$end 		= $this->uri->segment(4);
				$shift		= $sh;
				$ket 		= 'Laporan Rekapan Transaksi Periode '.date('d-m-Y', strtotime($start)).' s/d '.date('d-m-Y', strtotime($end)).$shi;
				$laporan 	= $this->report->get_table_periode($start, $end, $sh);
	            $total 		= $this->report->get_total_periode($start, $end, $sh);
	        }
		}
		else
		{
			$ket 		= 'Laporan Rekapan Transaksi Keseluruhan';
			$laporan 	= $this->report->get_table();
			$total 		= $this->report->get_total();
		}

		$data['ket'] 		= $ket;
        $data['laporan']	= $laporan;
        $data['total'] 		= floatval($total->total);
        ob_start();
	    $this->load->view('format/print_report', $data);
	    $html = ob_get_contents();
        // var_dump($html);die();
	        ob_end_clean();
	        require_once('./assets/html2pdf/html2pdf.class.php');
	    $pdf = new HTML2PDF('P','A4','en', false, 'ISO-8859-15',array(30, 0, 20, 0));
	    $pdf->WriteHTML($html);
	    $pdf->Output('Laporan Rekapan Transaksi.pdf', 'I');
        // $this->load->library('pdf');
        // $this->pdf->setPaper('A4', 'potrait');
        // $this->pdf->filename = $data['ket'].".pdf";
        // $this->pdf->load_view('format/print_report', $data);
    }

    public function cetak_excel($x =  null)
    {
    	if($x != null)
    	{
    		if($this->uri->segment(3) && empty($this->uri->segment(4)))
    		{
    			$tanggal 	= $this->uri->segment(3);
    			$ket 		= 'Laporan Rekapan Transaksi Tanggal '.date('d-m-Y', strtotime($tanggal));
				$laporan 	= $this->report->get_table_tanggal($tanggal);
	            $total 		= $this->report->get_total_tanggal($tanggal);
    		}
    		else
    		{
				$start 		= $this->uri->segment(3);
				$end 		= $this->uri->segment(4);
				$ket 		= 'Laporan Rekapan Transaksi Periode '.date('d-m-Y', strtotime($start)).' s/d '.date('d-m-Y', strtotime($end));
				$laporan 	= $this->report->get_table_periode($start, $end);
	            $total 		= $this->report->get_total_periode($start, $end);
	        }
		}
		else
		{
			$ket 		= 'Laporan Rekapan Transaksi Keseluruhan';
			$laporan 	= $this->report->get_table();
			$total 		= $this->report->get_total();
		}

		$data['ket'] 		= $ket;
        $data['laporan']	= $laporan;
        $data['total'] 		= floatval($total->total);
        ob_start();
	    $this->load->view('format/print_report_excel', $data);
    }

}

/* End of file Report.php */
/* Location: ./application/controllers/Report.php */