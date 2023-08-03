<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->model('user_model');
    }

    public function index()
	{

		//directed to sales list page with if the user is a staff

        $base_url = base_url();
        $data['title'] = 'IntelliSalesBot | Register';
        $data['bootstrap_css'] = '
            <link rel="stylesheet" type="text/css" href="'.$base_url.'login/vendor/bootstrap/css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="'.$base_url.'login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" type="text/css" href="'.$base_url.'login/vendor/animate/animate.css">
            <link rel="stylesheet" type="text/css" href="'.$base_url.'login/vendor/css-hamburgers/hamburgers.min.css">
            <link rel="stylesheet" type="text/css" href="'.$base_url.'login/vendor/select2/select2.min.css">
            <link rel="stylesheet" type="text/css" href="'.$base_url.'login/css/util.css">
            <link rel="stylesheet" type="text/css" href="'.$base_url.'login/css/main.css">';

        $data['bootstrap_js'] = '<script src="'.$base_url.'login/vendor/jquery/jquery-3.2.1.min.js"></script>
            <script src="'.$base_url.'login/vendor/bootstrap/js/popper.js"></script>
            <script src="'.$base_url.'login/vendor/bootstrap/js/bootstrap.min.js"></script>
            <script src="'.$base_url.'login/vendor/select2/select2.min.js"></script>
            <script src="'.$base_url.'login/vendor/tilt/tilt.jquery.min.js"></script>
            <script src="'.$base_url.'login/js/main.js"></script>';	
            
        $data['no_footer'] = 1;

        $this->load->view('internal_templates/header', $data);
        $this->load->view('users/register_view');
        $this->load->view('internal_templates/footer');

	}




}