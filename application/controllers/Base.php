<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AppBase extends CI_Controller
{

    protected $user_group = NULL;
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['ion_auth']);
        $this->_is_logged_in();
        $this->load->model('base_model');
        $this->user_group = $this->base_model->get_item('row', 'users_groups', 'group_id', ['user_id' => $this->session->userdata('user_id')]);
    }

    public function adminview($child_view = "", $data = [])
    {
        $data['child_template'] = $child_view;
        $data['authorization_group'] = $this->user_group['group_id'];
        $this->load->view('home/base', $data);
    }

    public function _result_msg($alert, $msg)
    {
        return $this->session->set_flashdata(array(
            'msg' => $msg,
            'alert' => 'alert-' . $alert
        ));
    }

    public function _is_logged_in()
    {
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            show_404();
        }
    }
}
