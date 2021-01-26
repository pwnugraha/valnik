<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AppBase extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('base_model');
    }

    public function adminview($child_view = "", $data = [])
    {
        $data['child_template'] = $child_view;
        $this->load->view('home/base', $data);
    }

    public function _result_msg($alert, $msg)
    {
        return $this->session->set_flashdata(array(
            'msg' => $msg,
            'alert' => 'alert-' . $alert
        ));
    }
}
