<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
            echo $this->load->view('/admin/admin', '', TRUE);
            //echo 'test';
    }
	
}