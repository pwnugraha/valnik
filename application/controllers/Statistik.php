<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once 'application/controllers/Base.php';

class Statistik extends AppBase
{
    function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->adminview('home/statistik');
    }
}
