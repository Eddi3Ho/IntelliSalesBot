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

        //Dont allow user to access login page
        if ($this->session->userdata('has_login')) {

            if ($this->session->userdata('user_role') == "Admin") {
                redirect('users/Dashboard/Manager');
            }
            // check user role is Employee
            else {
                redirect('users/Dashboard/Employee');
            }
        }
    }

    public function index()
    {

        $this->form_validation->set_rules('user_fname', 'First Name', 'required|trim');
        $this->form_validation->set_rules('user_lname', 'Last Name', 'required|trim');
        $this->form_validation->set_rules('user_email', 'Email', 'required|trim|valid_email|is_unique[users.user_email]', [
            'is_unique' => 'This email has already registered!'
        ]);

        $this->form_validation->set_rules('user_password', 'Password', 'required|trim|min_length[3]|matches[confirm_password]', [
            'matches' => 'password do not match!',
            'min_length' => 'Password too short'
        ]);
        $this->form_validation->set_rules('confirm_password', 'Password', 'required|trim|matches[user_password]');

        //if input validated as error, move user back to register page
        if ($this->form_validation->run() == false) {

            $base_url = base_url();
            $data['title'] = 'IntelliSalesBot | Register';
            $data['bootstrap_css'] = '
                <link rel="stylesheet" type="text/css" href="' . $base_url . 'login/vendor/bootstrap/css/bootstrap.min.css">
                <link rel="stylesheet" type="text/css" href="' . $base_url . 'login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
                <link rel="stylesheet" type="text/css" href="' . $base_url . 'login/vendor/animate/animate.css">
                <link rel="stylesheet" type="text/css" href="' . $base_url . 'login/vendor/css-hamburgers/hamburgers.min.css">
                <link rel="stylesheet" type="text/css" href="' . $base_url . 'login/vendor/select2/select2.min.css">
                <link rel="stylesheet" type="text/css" href="' . $base_url . 'login/css/util.css">
                <link rel="stylesheet" type="text/css" href="' . $base_url . 'login/css/main.css">';
    
            $data['bootstrap_js'] = '<script src="' . $base_url . 'login/vendor/jquery/jquery-3.2.1.min.js"></script>
                <script src="' . $base_url . 'login/vendor/bootstrap/js/popper.js"></script>
                <script src="' . $base_url . 'login/vendor/bootstrap/js/bootstrap.min.js"></script>
                <script src="' . $base_url . 'login/vendor/select2/select2.min.js"></script>
                <script src="' . $base_url . 'login/vendor/tilt/tilt.jquery.min.js"></script>
                <script src="' . $base_url . 'login/js/main.js"></script>';
    
            $data['no_footer'] = 1;
    
            $this->load->view('internal_templates/header', $data);
            $this->load->view('users/register_view');
            $this->load->view('internal_templates/footer');

        } else {

            $data =
                [
                    'user_fname' => htmlspecialchars($this->input->post('user_fname', true)),
                    'user_lname' => htmlspecialchars($this->input->post('user_lname', true)),
                    'user_email' => htmlspecialchars($this->input->post('user_email', true)),
                    'user_password' => password_hash($this->input->post('user_password'), PASSWORD_DEFAULT),
                    'user_role' => "Employee",
                ];

            //insert user data
            $this->user_model->insert($data);

            $this->session->set_userdata('message','<div class="alert alert-success" role="alert" id="alert_message">Your account has been registered!</div>');
            redirect('users/login/verify_users');
        }

    }
}
