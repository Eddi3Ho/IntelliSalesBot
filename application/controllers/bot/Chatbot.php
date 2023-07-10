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
        $this->load->model('chat_model');


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

        // Retrieve the data from the POST request
        $prompt = $this->input->post('prompt');
        $gpt_response = generate_text($prompt);


        //Save user prompt and gpt response
        $data =
            [
                'user_id' => $this->session->userdata('user_id'),
            ];

        $this->chat_model->insert_history($data);

        // Send the response as JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($gpt_response));
    }

    public function load_conversation_history()
    {
    }
}
