<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Welcome extends CI_Controller {
        function __construct()
        {
            parent::__construct();
            $this->load->helper("form");
            $this->load->library("session");
        }


        public function index()
        {

            $this->session->sess_destroy();
            $this->load->view('index');
        }
}