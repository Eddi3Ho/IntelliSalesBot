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
        $this->load->model('chatbot_model');
        $this->load->helper('ml');

        if (!$this->session->userdata('user_id') || !$this->session->userdata('user_role')) {
            redirect('users/login/verify_users/');
        }
    }

    public function index()
    {
        // Load the CSV dataset
        $csvFile = FCPATH . 'assets/files/dataset.csv';
        $groupedData  = groupItemDescription($csvFile);

        // Display the CSV data for testing
        echo '<pre>';
        print_r($groupedData);
        echo '</pre>';
        
        die;
        
        $data['title'] = 'IntelliSalesBot | Chatbot';
        $data['selected'] = 'chatbot';
        $data['include_js'] = 'chatbot';

        //First check if there is any conversation
        $has_conversation = $this->chatbot_model->check_if_user_has_conversation($this->session->userdata('user_id'));

        //if there is convseration         
        if ($has_conversation) {
            //1. Get the last inserted con_id
            $latest_row = $this->chatbot_model->get_latest_con_id($this->session->userdata('user_id'));
            $data['latest_con_id'] = $latest_row->con_id;

            //2. Get all existing conversation
            $data['conversation_history_data'] = $this->chatbot_model->select_conversation_history($this->session->userdata('user_id'));

            $data['new_chat'] = "no";
        }
        //if there is no convseration    
        else {
            $data['latest_con_id'] = 0;
            $data['new_chat'] = "yes";
        }

        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('bot/chatbot_view');
        $this->load->view('internal_templates/footer');
    }

    public function generate_response()
    {

        // Retrieve the data from the POST request
        $prompt = $this->input->post('prompt');
        //con_id can be 0 which means its new
        $con_id = $this->input->post('con_id');

        $sentence = $this->sales_reports();

        $conversation = array(
            array('role' => 'system', 'content' => 'You uses "\n" when there is a line break. 
            You are an AI sales analyst that is able to understand the monthly sales report provided and answer question related to the sales.
            The currency for all items are in riggit Malaysia (RM)\n
            The monthly sales report are as followed:\n'.$sentence),
        );

        // Get chat history if exist
        if ($this->input->post('new_chat') == "no") {
            $chat_data = $this->chatbot_model->select_chat_history($con_id);

            foreach ($chat_data as $chat_data_row) {

                if ($chat_data_row->role == "ai") {
                    $conversation[] = array(
                        'role' => 'assistant',
                        'content' => $chat_data_row->message
                    );
                } else {
                    $conversation[] = array(
                        'role' => 'user',
                        'content' => $chat_data_row->message
                    );
                }
            }
        }

        //Latest prompt
        $conversation[] = array(
            'role' => 'user',
            'content' => $prompt
        );

        $gpt_response = generate_text($conversation);

        // Create new table in conversation history and chat history if its new chat
        if ($this->input->post('new_chat') == "yes") {

            //Default uses first five word as the conversation name
            $words = explode(" ", $gpt_response);
            $first_five_words = array_slice($words, 0, 5);
            $first_five_words = implode(" ", $first_five_words);

            $con_data =
                [
                    'user_id' => $this->session->userdata('user_id'),
                    'con_name' => $first_five_words,
                    'chatbot_type' => 1
                ];
            $con_id = $this->chatbot_model->insert_history($con_data);
        }

        //Create new chat regardless of whether its new chat or not
        //One for user prompt
        $chat_data =
            [
                'con_id' => $con_id,
                'message' => $prompt,
                'role' => 1,
            ];

        $this->chatbot_model->insert_chat($chat_data);
        //one for gpt response
        $chat_data =
            [
                'con_id' => $con_id,
                'message' => $gpt_response,
                'role' => 2,
            ];

        $response_chat_id = $this->chatbot_model->insert_chat($chat_data);

        //Update latest_update datetime column
        $this->chatbot_model->update_last_update($con_id);

        //Update conversation_history no_of_message
        $this->chatbot_model->increase_no_of_message($con_id);

        //Get ai response message from databse
        $chat_row_data = $this->chatbot_model->one_chat_row($response_chat_id);
        $gpt_response = $chat_row_data->message;

        // Send the response as JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($gpt_response));
    }

    public function sales_reports()
    {

    }

    public function load_conversation_history()
    {
        $con_id = $this->input->post('con_id');
        $chat_data = $this->chatbot_model->select_chat_history($con_id);

        // Send the response as JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($chat_data));
    }

    public function load_convo_card()
    {
        $conversation_history_data = $this->chatbot_model->select_conversation_history($this->session->userdata('user_id'));

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($conversation_history_data));
    }

    public function check_has_conversation()
    {
        //check if there is any conversation
        $has_conversation = $this->chatbot_model->check_if_user_has_conversation($this->session->userdata('user_id'));

        if ($has_conversation) {
            $check = "yes";
        } else {
            $check = "no";
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($check));
    }

    public function get_latest_con_id()
    {
        $latest_con_id = $this->chatbot_model->get_latest_con_id($this->session->userdata('user_id'));

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($latest_con_id));
    }

    public function edit_conversation_name()
    {
        $con_id = $this->input->post('con_id');
        $con_name = $this->input->post('con_name');


        $this->chatbot_model->edit_conversation_name($con_id, $con_name);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($con_id));
    }

    public function delete_conversation()
    {
        $con_id = $this->input->post('con_id');

        //delete converation and delete chat
        $this->chatbot_model->delete_conversation($con_id);
        $this->chatbot_model->delete_chat($con_id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($con_id));
    }
}
