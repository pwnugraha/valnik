<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'application/controllers/Base.php';

class Statistik extends AppBase
{
    protected $data = [];
    function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->data['kec'] = $this->base_model->get_item('result', 'art', 'DISTINCT(kec)', NULL, NULL, 'kec ASC');
        $this->data['grafik'] = [];
        $group_per_status = $this->base_model->get_item('result', 'art', 'status, COUNT(*) as count', NULL, ['status']);
        $this->data['count_per_status'][1] = 0;
        $this->data['count_per_status'][2] = 0;
        $this->data['count_per_status'][3] = 0;
        $this->data['count_per_status'][4] = 0;
        $this->data['count_per_status'][5] = 0;
        $this->data['count_per_status'][6] = 0;
        foreach ($group_per_status as $v) {
            $this->data['count_per_status'][$v['status']] = $v['count'];
        }

        foreach ($this->data['kec'] as $v) {
            $this->data['grafik'][1][$v['kec']] = 0;
            $this->data['grafik'][2][$v['kec']] = 0;
            $this->data['grafik'][3][$v['kec']] = 0;
            $this->data['grafik'][4][$v['kec']] = 0;
        }

        $grafik_statistik = $this->base_model->get_item('result', 'art', 'kec, status, COUNT(*) as count', NULL, ['kec', 'status'], 'kec ASC, status ASC');
        foreach ($grafik_statistik as $v) {
            $this->data['grafik'][$v['status']][$v['kec']] = $v['count'];
        }

        if (!empty($this->data['grafik'][1])) {
            $this->data['grafik_valid'] = implode(',', $this->data['grafik'][1]);
        }
        if (!empty($this->data['grafik'][2])) {
            $this->data['grafik_perbaikan'] = implode(',', $this->data['grafik'][2]);
        }
        if (!empty($this->data['grafik'][3])) {
            $this->data['grafik_entri'] = implode(',', $this->data['grafik'][3]);
        }
        if (!empty($this->data['grafik'][4])) {
            $this->data['grafik_konsolidasi'] = implode(',', $this->data['grafik'][4]);
        }

        // var_dump($this->data['grafik']);
        // die();

        //recent activities
        $this->data['activities'] = $this->base_model->get_join_item('result', 'log.*, users.username', 'created_at DESC', 'log', ['users'], ['log.user_id = users.id'], ['inner']);
        $this->adminview('home/statistik', $this->data);
    }
}
