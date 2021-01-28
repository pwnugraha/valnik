<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'application/controllers/Base.php';

class Data extends AppBase
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
        $this->data['kec'] = $this->base_model->get_item('result', 'art', 'DISTINCT(kec)');
        $this->data['items'] = [];
        $this->data['current_kec'] = $this->kec_data['company'];
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
        $this->adminview('home/tables', $this->data);
    }

    public function maintenance()
    {
        $this->load->view('home/maintenance');
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
        $this->form_validation->set_rules('update_nik', 'Perbaikan NIK', 'trim|required|numeric');
        $this->form_validation->set_rules('update_nama', 'Perbaikan Nama', 'trim|required');
        $this->form_validation->set_rules('id_art_update', 'ID ART', 'trim|required|numeric');
        $this->form_validation->set_rules('kec_update', 'Kecamatan', 'trim|required');
        $this->form_validation->set_rules('kel_update', 'Kelurahan', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('message', validation_errors());
        } else {
            $id_art = $this->input->post('id_art_update', TRUE);
            $get_art = $this->base_model->get_item('row', 'art', 'id, update_nik, update_nama', ['id_art' => $id_art]);
            $params = [
                'update_nik' => $this->input->post('update_nik', TRUE),
                'update_nama' => $this->input->post('update_nama', TRUE),
                'status' => 3,
                'updated_at' => time()
            ];
            $this->base_model->update_item('art', $params, ['id_art' => $id_art]);
            $this->base_model->insert_item('log', ['data' => 'ID ART ' . $id_art . ' telah diupdate. NIK ' . $get_art['update_nik'] . ' NAMA ' . $get_art['update_nama'] . ' menjadi NIK ' . $params['update_nik'] . ' NAMA ' . $params['update_nama'], 'created_at' => time(), 'art_id' => $get_art['id']]);
            $this->session->set_flashdata('message', 'Data telah disimpan. Menunggu pengecekan dan entri oleh operator.');
        }
        $this->session->set_flashdata('kec', $this->input->post('kec_update'));
        $this->session->set_flashdata('kel', $this->input->post('kel_update'));
        $this->session->set_flashdata('status', $this->input->post('status_update'));
        redirect('data');
    }

    public function get_capil()
    {
        $this->form_validation->set_rules('nik', 'Perbaikan NIK', 'trim|numeric|exact_length[16]|required');
        if ($this->form_validation->run() === FALSE) {
            echo json_encode(['status' => false, 'message' => validation_errors()]);
        } else {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.integrasvc.id/qm38q?nik=' . $this->input->post('nik', TRUE),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Basic NGJmdWh0OGI6M0ZkUmozcGtHdWdjNk9sYzIyYXBkeU1Kc04yUUx0dUg=',
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            //var_dump(json_decode($response, true));
            echo json_encode(['status' => true, 'data' => $response]);
        }
    }
}
