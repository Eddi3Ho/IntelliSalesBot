<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Template extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'Admin | Dashboard';
        $data['include_js'] = 'admin_dashboard';

		// Value for bootstrap css and js plugin that is NOT from SB Admin
		// $data['bootstrap_css'] = "";
		// $data['bootstrap_js'] = "";

        $this->load->view('external_templates/header');
        $this->load->view('external_templates/sidenav');
        $this->load->view('external_templates/topbar');
        $this->load->view('template_view');
        $this->load->view('external_templates/footer');
    }


}
