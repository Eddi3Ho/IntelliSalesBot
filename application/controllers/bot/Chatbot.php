<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Chatbot extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->model('user_model');
        $this->load->model('sales_model');

        if (!$this->session->userdata('user_id') || !$this->session->userdata('user_role')) {
			redirect('users/login/verify_users/');
		}
    }

    public function index()
    {
        $data['title'] = 'IntelliSalesBot | Chatbot';
        $data['selected'] = 'chatbot';
        $data['include_js'] = 'chatbot';

        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('bot/chatbot_view');
        $this->load->view('internal_templates/footer');
    }

    public function generate_response()
    {
        
        // $prompt = 'Repeat "this is successful"';
        // $text = generate_text($prompt);

        // $data['response'] = $text;
        

    }
    public function load_conversation_history()
    {}
}
