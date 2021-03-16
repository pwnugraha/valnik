<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'application/controllers/Base.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Data_pbi extends AppBase
{
    protected $data = [];
    protected $kec_data = NULL;
    function __construct()
    {
        parent::__construct();
        if ($this->user_group['group_id'] != 3) {
            show_404();
        }
        $this->kec_data = $this->base_model->get_item('row', 'users', 'company', ['id' => $this->session->userdata('user_id')]);
    }
    public function index()
    {
        $this->data['kec'] = $this->base_model->get_item('result', 'valnik_pbi', 'DISTINCT(kec)');
        $this->data['items'] = [];
        $this->data['current_kec'] = $this->kec_data['company'];
        $this->data['current_kel'] = $this->session->flashdata('kel') ? $this->session->flashdata('kel') : NULL;
        $this->data['current_status'] = $this->session->flashdata('status') ? $this->session->flashdata('status') : 0;
        $this->data['error_message'] = '';

        $this->form_validation->set_rules('kec', 'Kecamatan', 'trim|required');
        $this->form_validation->set_rules('kel', 'Kelurahan', 'trim|required');

        $this->data['data_kel'] = $this->base_model->get_item('result', 'valnik_pbi', 'DISTINCT(kel)', ['kec' => $this->data['current_kec']]);
        if ($this->form_validation->run() === FALSE) {
            $this->data['data_kel'] = $this->base_model->get_item('result', 'valnik_pbi', 'DISTINCT(kel)', ['kec' => $this->data['current_kec']]);
            $this->data['items'] = $this->base_model->get_item('result', 'valnik_pbi', '*', ['kec' => $this->data['current_kec'], 'kel' => $this->data['current_kel'], 'status NOT IN (5,6)' => NULL]);
            if ($this->data['current_status'] != 0) {
                $this->data['items'] = $this->base_model->get_item('result', 'valnik_pbi', '*', ['kec' => $this->data['current_kec'], 'kel' => $this->data['current_kel'], 'status' => $this->data['current_status']]);
            }
        } else {
            $items = $this->base_model->get_item('result', 'valnik_pbi', '*', ['kec' => $this->input->post('kec'), 'kel' => $this->input->post('kel'), 'status NOT IN (5,6)' => NULL]);
            if ($this->input->post('status') != 0) {
                $items = $this->base_model->get_item('result', 'valnik_pbi', '*', ['kec' => $this->input->post('kec'), 'kel' => $this->input->post('kel'), 'status' => $this->input->post('status')]);
            }

            $data_kel = $this->base_model->get_item('result', 'valnik_pbi', 'DISTINCT(kel)', ['kec' => $this->input->post('kec')]);
            if ($items) {
                $this->data['current_kec'] = $this->input->post('kec');
                $this->data['current_kel'] = $this->input->post('kel');
                $this->data['current_status'] = $this->input->post('status');
                $this->data['data_kel'] = $data_kel;
                $this->data['items'] = $items;
            }
        }
        $this->adminview('pbi/tables', $this->data);
    }

    public function get_kel()
    {
        $data = $this->base_model->get_item('result', 'valnik_pbi', 'DISTINCT(kel)', ['kec' => $this->input->post('kec')]);
        if (!empty($data)) {
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            echo json_encode(['status' => false, 'data' => []]);
        }
    }

    public function save()
    {
        $this->form_validation->set_rules('update_nik', 'Perbaikan NIK', 'trim|required|numeric');
        $this->form_validation->set_rules('update_nama', 'Perbaikan Nama', 'trim|required');
        $this->form_validation->set_rules('id_art_update', 'ID ART', 'trim|required|numeric');
        $this->form_validation->set_rules('kec_update', 'Kecamatan', 'trim|required');
        $this->form_validation->set_rules('kel_update', 'Kelurahan', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('message', validation_errors());
        } else {
            $id_art = $this->input->post('id_art_update', TRUE);
            $get_art = $this->base_model->get_item('row', 'valnik_pbi', 'id, kel, nik_art, nama_art, update_nik, update_nama, status', ['id_art' => $id_art]);
            $params = [
                'update_nik' => $this->input->post('update_nik', TRUE),
                'update_nama' => $this->input->post('update_nama', TRUE),
                'status' => 3,
                'updated_at' => time()
            ];

            //deny update if had been updated
            if ($get_art['status'] != 2) {
                $this->session->set_flashdata('message', 'Data telah diperbaiki. Pilih data lain yang belum diperbaiki.');
                redirect('data_pbi');
            }
            $this->base_model->update_item('valnik_pbi', $params, ['id_art' => $id_art]);
            $this->base_model->insert_item('log_pbi', ['data' => 'Username ' . $this->session->userdata('username') . ' mengupdate data. ID ART ' . $id_art . ' , desa ' . $get_art['kel'] . ' telah diupdate. NIK ' . $get_art['nik_art'] . ' NAMA ' . $get_art['nama_art'] . ' menjadi NIK ' . $params['update_nik'] . ' NAMA ' . $params['update_nama'], 'created_at' => time(), 'art_id' => $get_art['id'], 'user_id' => $this->session->userdata('user_id')]);
            $this->session->set_flashdata('message', 'Data telah disimpan. Menunggu pengecekan dan entri oleh operator.');
        }
        $this->session->set_flashdata('kec', $this->input->post('kec_update'));
        $this->session->set_flashdata('kel', $this->input->post('kel_update'));
        $this->session->set_flashdata('status', $this->input->post('status_update'));
        redirect('data_pbi');
    }

    public function export()
    {
        $export_data = $this->base_model->get_item('result', 'valnik_pbi', '*', ['kec' => $this->input->post('kec'), 'kel' => $this->input->post('kel')]);

        if ($this->input->post('status') != 0) {
            $export_data = $this->base_model->get_item('result', 'valnik_pbi', '*', ['kec' => $this->input->post('kec'), 'kel' => $this->input->post('kel'), 'status' => $this->input->post('status')]);
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $i = 2;
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'KEC');
        $sheet->setCellValue('C1', 'DESA');
        $sheet->setCellValue('D1', 'ID ART');
        $sheet->setCellValue('E1', 'ALAMAT');
        $sheet->setCellValue('F1', 'NIK ART');
        $sheet->setCellValue('G1', 'NAMA ART');
        $sheet->setCellValue('H1', 'KETERANGAN');
        $sheet->setCellValue('I1', 'STATUS');
        $sheet->setCellValue('J1', 'PERBAIKAN NIK');
        $sheet->setCellValue('K1', 'PERBAIKAN NAMA');
        if (!empty($export_data)) {
            foreach ($export_data as $v) {

                $sheet->setCellValue('A' . $i, $i - 1);
                $sheet->setCellValue('B' . $i, $v['kec']);
                $sheet->setCellValue('C' . $i, $v['kel']);
                $sheet->setCellValueExplicit('D' . $i, $v['id_art'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('E' . $i, $v['alamat']);
                $sheet->setCellValueExplicit('F' . $i, $v['nik_art'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('G' . $i, $v['nama_art']);
                $sheet->setCellValue('H' . $i, $v['is_bdt']);
                if ($v['status'] == 1) {
                    $sheet->setCellValue('I' . $i, 'valid');
                } else if ($v['status'] == 2) {
                    $sheet->setCellValue('I' . $i, 'Mohon perbaikan');
                } else if ($v['status'] == 3) {
                    $sheet->setCellValue('I' . $i, 'Menunggu dicek operator');
                } else if ($v['status'] == 4) {
                    $sheet->setCellValue('I' . $i, 'Sedang diajukan konsolidasi NIK');
                }
                $sheet->setCellValue('H' . $i, $v['is_bdt']);
                $sheet->setCellValueExplicit('J' . $i, $v['update_nik'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('K' . $i, $v['update_nama']);
                $i++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($this->input->post('kel') . '.xlsx') . '"');
        $writer->save('php://output');
    }

    public function get_history()
    {
        $data = $this->base_model->get_item('result', 'log_pbi', '*', ['art_id' => $this->input->post('art_id')]);
        if (!empty($data)) {
            echo json_encode(['status' => true, 'data' => $data]);
        } else {
            echo json_encode(['status' => false, 'data' => []]);
        }
    }
}
