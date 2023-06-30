<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->model('user_model');
    }

    public function verify_users()
    {

         //Dont allow user to access login page
         if ($this->session->userdata('has_login') ){  

            // check user role is  IT
            if($this->session->userdata('user_role') =="IT")
            {
                redirect('items/Items');
            }
            // check user role is  Manager
            else if ($this->session->userdata('user_role')=="Admin")
            {
                redirect('users/Dashboard/Manager');
            }
            // check user role is Employee
            else
            {
                redirect('users/Dashboard/Employee');
            }
        }

        $this->form_validation->set_rules('user_email','Email','trim|required|valid_email');
        $this->form_validation->set_rules('user_password','Password','trim|required');
        
        if($this->form_validation->run() ==false)
        {
            $base_url = base_url();
            $data['title'] = 'IntelliSalesBot | Login';
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
    
            $this->load->view('internal_templates/header', $data);
            $this->load->view('users/login_view');
            $this->load->view('internal_templates/footer');
        }
        else
        {
            $this->user_login();
        }     
    }

    private function user_login()
    {
        
        $user_email= $this->input->post('user_email');
        $user_password=$this->input->post('user_password');
        $users=$this->user_model->valid_email($user_email);
       
        // if user exists
        if($users)
        {
            // verify the password
            if($user_password==$users['user_password'])
            {
                
                $data=
                [
                    'user_fname'=>$users['user_fname'],
                    'user_lname'=>$users['user_lname'],
                    'user_email'=>$users['user_email'],
                    'user_role'=>$users['user_role'],
                    'user_id'=>$users['user_id'],
                    'has_login'=>1,
                ];
                
                $this->session->set_userdata($data);
        
                // check user role is IT
                if($users['user_role']=="IT")
                {
                    redirect('items/Items');
                }

                // check user role is Manager
                else if ($users['user_role']=="Admin")
                {
                    redirect('users/Dashboard/Manager');
                }
                //check user role is Employee
                else 
                {
                    redirect('users/Dashboard/Employee');
                }
            }
            else
            {
                $this->session->set_userdata('message','<div class="alert alert-danger" role="alert" id="alert_message">
                Invalid password!</div>');
                redirect('users/login/verify_users');
            }
        }

        // if user account does not exist
        else
        {
            $this->session->set_userdata('message','<div class="alert alert-danger" role="alert" id="alert_message">
            Account does not exist!</div>');
            redirect('users/login/verify_users');
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('user_email');
        $this->session->unset_userdata('user_fname');
        $this->session->unset_userdata('user_lname');
        $this->session->unset_userdata('user_role');
        $this->session->unset_userdata('has_login');
        $this->session->set_userdata('message','<div class="alert alert-success" role="alert" id="alert_message">You have been logged out!</div>');
        redirect('users/login/verify_users');
    }
}