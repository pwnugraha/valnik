<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'application/controllers/Base.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Entry extends AppBase
{
    protected $data = [];
    protected $kec_data = NULL;
    function __construct()
    {
        parent::__construct();
        if (!in_array($this->user_group['group_id'], [1, 2])) {
            show_404();
        }
    }
    public function index()
    {
        $this->data['kec'] = $this->base_model->get_item('result', 'art', 'DISTINCT(kec)');
        $this->data['items'] = [];
        $this->data['current_kec'] = $this->session->flashdata('kec') ? $this->session->flashdata('kec') : NULL;
        $this->data['current_kel'] = $this->session->flashdata('kel') ? $this->session->flashdata('kel') : NULL;
        $this->data['current_status'] = $this->session->flashdata('status') ? $this->session->flashdata('status') : 0;
        $this->data['error_message'] = '';

        $this->form_validation->set_rules('kec', 'Kecamatan', 'trim|required');
        $this->form_validation->set_rules('kel', 'Kelurahan', 'trim|required');

        $this->data['data_kel'] = $this->base_model->get_item('result', 'art', 'DISTINCT(kel)', ['kec' => $this->data['current_kec']]);
        if ($this->form_validation->run() === FALSE) {
            $this->data['data_kel'] = $this->base_model->get_item('result', 'art', 'DISTINCT(kel)', ['kec' => $this->data['current_kec']]);
            $this->data['items'] = $this->base_model->get_item('result', 'art', '*', ['kec' => $this->data['current_kec'], 'kel' => $this->data['current_kel']]);
            if ($this->data['current_status'] != 0) {
                $this->data['items'] = $this->base_model->get_item('result', 'art', '*', ['kec' => $this->data['current_kec'], 'kel' => $this->data['current_kel'], 'status' => $this->data['current_status']]);
            }
        } else {
            $items = $this->base_model->get_item('result', 'art', '*', ['kec' => $this->input->post('kec'), 'kel' => $this->input->post('kel')]);
            if ($this->input->post('status') != 0) {
                $items = $this->base_model->get_item('result', 'art', '*', ['kec' => $this->input->post('kec'), 'kel' => $this->input->post('kel'), 'status' => $this->input->post('status')]);
            }

            $data_kel = $this->base_model->get_item('result', 'art', 'DISTINCT(kel)', ['kec' => $this->input->post('kec')]);
            if ($items) {
                $this->data['current_kec'] = $this->input->post('kec');
                $this->data['current_kel'] = $this->input->post('kel');
                $this->data['current_status'] = $this->input->post('status');
                $this->data['data_kel'] = $data_kel;
                $this->data['items'] = $items;
            }
        }
        $this->adminview('home/entry', $this->data);
    }

    public function get_kel()
    {
        $data = $this->base_model->get_item('result', 'art', 'DISTINCT(kel)', ['kec' => $this->input->post('kec')]);
        if (!empty($data)) {
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            echo json_encode(['status' => false, 'data' => []]);
        }
    }

    public function save()
    {
        $this->form_validation->set_rules('id_art_update', 'ID ART', 'trim|required|numeric');
        $this->form_validation->set_rules('status_valid', 'Status Hasil Entry', 'trim|required|numeric');
        $this->form_validation->set_rules('kec_update', 'Kecamatan', 'trim|required');
        $this->form_validation->set_rules('kel_update', 'Kelurahan', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('message', validation_errors());
        } else {
            $id_art = $this->input->post('id_art_update', TRUE);
            $status_valid = $this->input->post('status_valid', TRUE);
            $get_art = $this->base_model->get_item('row', 'art', 'id, update_nik, update_nama', ['id_art' => $id_art]);
            $params = [
                'status' => $this->input->post('status_valid'),
                'updated_at' => time()
            ];
            $status_entry = 0;
            if ($status_valid == 1) {
                $status_entry = 'Data Valid berhasil dientri';
            } else if ($status_valid == 4) {
                $status_entry = 'Data tidak berhasil dientri. Ajukan konsolidasi NIK';
            } else if ($status_valid == 2) {
                $status_entry = 'Perbaikan data belum meyakinkan. Ajukan perbaikan kembali';
            }

            $this->base_model->update_item('art', $params, ['id_art' => $id_art]);
            $this->base_model->insert_item('log', ['data' => 'Username ' . $this->session->userdata('username') . ' mengupdate data. ID ART ' . $id_art . ' telah diupdate. Status Hasil Entry ' . $status_entry . '.', 'created_at' => time(), 'art_id' => $get_art['id'], 'user_id' => $this->session->userdata('user_id')]);
            $this->session->set_flashdata('message', 'Data telah disimpan. Status data : ' . $status_entry);
        }
        $this->session->set_flashdata('kec', $this->input->post('kec_update'));
        $this->session->set_flashdata('kel', $this->input->post('kel_update'));
        $this->session->set_flashdata('status', $this->input->post('status_update'));
        redirect('entry');
    }

    public function export()
    {
        $export_data = $this->base_model->get_item('result', 'art', '*', ['kec' => $this->input->post('kec'), 'kel' => $this->input->post('kel')]);

        if ($this->input->post('status') != 0) {
            $export_data = $this->base_model->get_item('result', 'art', '*', ['kec' => $this->input->post('kec'), 'kel' => $this->input->post('kel'), 'status' => $this->input->post('status')]);
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $i = 2;
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'KEC');
        $sheet->setCellValue('C1', 'DESA');
        $sheet->setCellValue('D1', 'ID DTKS');
        $sheet->setCellValue('E1', 'ID ART');
        $sheet->setCellValue('F1', 'NAMA KRT');
        $sheet->setCellValue('G1', 'ALAMAT');
        $sheet->setCellValue('H1', 'NIK ART');
        $sheet->setCellValue('I1', 'NAMA ART');
        $sheet->setCellValue('J1', 'BSP');
        $sheet->setCellValue('K1', 'PKH');
        $sheet->setCellValue('L1', 'PBI');
        $sheet->setCellValue('M1', 'STATUS');
        $sheet->setCellValue('N1', 'PERBAIKAN NIK');
        $sheet->setCellValue('O1', 'PERBAIKAN NAMA');
        if (!empty($export_data)) {
            foreach ($export_data as $v) {

                $sheet->setCellValue('A' . $i, $i - 1);
                $sheet->setCellValue('B' . $i, $v['kec']);
                $sheet->setCellValue('C' . $i, $v['kel']);
                $sheet->setCellValueExplicit('D' . $i, $v['id_dtks'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('E' . $i, $v['id_art'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('F' . $i, $v['nama_krt']);
                $sheet->setCellValue('G' . $i, $v['alamat']);
                $sheet->setCellValueExplicit('H' . $i, $v['nik_art'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('I' . $i, $v['nama_art']);
                $sheet->setCellValue('J' . $i, $v['bsp']);
                $sheet->setCellValue('K' . $i, $v['pkh']);
                $sheet->setCellValue('L' . $i, $v['pbi']);
                if ($v['status'] == 1) {
                    $sheet->setCellValue('M' . $i, 'valid');
                } else if ($v['status'] == 2) {
                    $sheet->setCellValue('M' . $i, 'Mohon perbaikan');
                } else if ($v['status'] == 3) {
                    $sheet->setCellValue('M' . $i, 'Menunggu dicek operator');
                } else if ($v['status'] == 4) {
                    $sheet->setCellValue('M' . $i, 'Sedang diajukan konsolidasi NIK');
                }

                $sheet->setCellValueExplicit('N' . $i, $v['update_nik'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('O' . $i, $v['update_nama']);
                $i++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($this->input->post('kel') . '.xlsx') . '"');
        $writer->save('php://output');
    }
}
