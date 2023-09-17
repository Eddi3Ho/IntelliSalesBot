<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Document extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->model('user_model');
        $this->load->model('sales_model');
        $this->load->model('document_chatbot_model');
        $this->load->helper('convert');
        $this->load->helper('file');
        $this->load->library('upload');

        if (!$this->session->userdata('user_id') || !$this->session->userdata('user_role')) {
            redirect('users/login/verify_users/');
        }
    }

    public function index()
    {
        // $sentence = "";
        // $document_list = $this->document_chatbot_model->get_documents_detail();

        // foreach ($document_list as $document_row) {
        //     $sentence .= 'Document Name: '. $document_row->doc_name. ', Document Content: {{'. $document_row->extracted_text.'}}\n';
        // }

        // $conversation = array(
        //     array('role' => 'system', 'content' => 'You uses "\n" when there is a line break. 
        //     You are a document analyst that is able to answer question according to the documents content that was extracted from pdf documents. 
        //     The following are document contents, each document labelled with the documents name and the content for the document is wrap inside a "{{}}".\n
        //     The documents are as followed:\n 
        //     '.$sentence),
        // );

        // print_r($conversation);
        $data['title'] = 'IntelliSalesBot | Chatbot';
        $data['selected'] = 'document';
        $data['include_js'] = 'document_chatbot';

        //File information
        $data['pdf_files'] = $this->document_chatbot_model->get_documents_detail();
        $data['pdf_files_name'] = $this->document_chatbot_model->get_documents_detail();

        //First check if there is any conversation
        $has_conversation = $this->document_chatbot_model->check_if_user_has_conversation($this->session->userdata('user_id'));

        //if there is convseration         
        if ($has_conversation) {
            //1. Get the last inserted con_id
            $latest_row = $this->document_chatbot_model->get_latest_con_id($this->session->userdata('user_id'));
            $data['latest_con_id'] = $latest_row->con_id;

            //2. Get all existing conversation
            $data['conversation_history_data'] = $this->document_chatbot_model->select_conversation_history($this->session->userdata('user_id'));

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
        $this->load->view('bot/document_view');
        $this->load->view('internal_templates/footer');
    }

    //=================== Document Management functions ===========================

    public function upload_file()
    {
        // Specify the upload configuration
        $config['upload_path'] =  FCPATH . 'assets/files/'; // Set your upload folder path
        $config['allowed_types'] = 'pdf'; // Allow only PDF files
        $config['max_size'] = 204008; // Maximum file size (in KB)

        $this->upload->initialize($config);

        if ($this->upload->do_upload('pdfFile')) {
            // File upload successful
            $uploadData = $this->upload->data();

            $fullFileName = $uploadData['file_name'];
            $file_name = pathinfo($fullFileName, PATHINFO_FILENAME);
            // You can access the uploaded file's details using $uploadData
            $fileFullPath = $config['upload_path'] . $uploadData['file_name'];

            $file_contents = $this->convert_pdf_txt($file_name);

            $doc_data =
                [
                    'doc_name' => $file_name,
                    'extracted_text' => $file_contents,
                ];

            //insert documents
            $inserted_id = $this->document_chatbot_model->insert_document($doc_data);

            if ($inserted_id) {
                $new_doc_data = $this->document_chatbot_model->select_one_doc($inserted_id);
                $formated_date = date("F j, Y, g:i a", strtotime($new_doc_data->upload_date));

                $response = [
                    'success' => true,
                    'message' => 'File uploaded successfully.',
                    'doc_id' => $new_doc_data->doc_id,
                    'doc_name' => $new_doc_data->doc_name,
                    'upload_date' => $formated_date
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'File uploaded, but document insertion failed.'
                ];
            }
        } else {
            // File upload failed
            $error = $this->upload->display_errors();
            echo $error; // Display error message
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function convert_pdf_txt($file_name)
    {
        //Convert pdf to txt file using api
        convertFileToPdf($file_name);
        //Get thumbnail(png image file) using api
        convertGetThumbnail($file_name);

        $file_path = FCPATH . 'assets/text_file/' . $file_name . '.txt';
        $file_contents = read_file($file_path);

        if ($file_contents !== FALSE) {
            // You now have all the contents of the file in $file_contents as a single string
            return $file_contents;
        } else {
            echo "Failed to read the file.";
        }
    }

    public function delete_document()
    {
        $doc_id = $this->input->post('doc_id');
        $doc_name = $this->input->post('doc_name');

        if ($this->document_chatbot_model->delete_doc($doc_id, $doc_name)) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    //=================== Chatbot Functions ========================================

    public function generate_response()
    {

        // Retrieve the data from the POST request
        $prompt = $this->input->post('prompt');
        //con_id can be 0 which means its new
        $con_id = $this->input->post('con_id');

        //Set up conversation history
        // $conversation = array();

        $sentence = "";

        // $conversation[] = array(
        //     'role' => 'user',
        //     'content' => $sentence
        // );

        $document_list = $this->document_chatbot_model->get_documents_detail();

        foreach ($document_list as $document_row) {
            $sentence .= 'Document Name: '. $document_row->doc_name. ', Document Content: {'. $document_row->extracted_text.'}\n ';
        }

        $conversation = array(
            array('role' => 'system', 'content' => 'You uses "\n" when there is a line break. 
            You are a document analyst that is able to answer question according to the documents content that was extracted from pdf documents. 
            The following are document contents, each document labelled with the documents name and the content for the document is wrap inside a "{}".\n
            The documents are as followed:\n 
            '.$sentence),
        );

        // Get chat history if exist
        if ($this->input->post('new_chat') == "no") {
            $chat_data = $this->document_chatbot_model->select_chat_history($con_id);

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

        //======================= Need to change ========================
        // $user_prompt = "";
        // foreach ($conversation as $conversation) {
        //     $user_prompt .= $conversation['role'] . ": " . $conversation['content'] . "\n";
        // }

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
                    'chatbot_type' => 2
                ];
            $con_id = $this->document_chatbot_model->insert_history($con_data);
        }

        //Create new chat regardless of whether its new chat or not
        //One for user prompt
        $chat_data =
            [
                'con_id' => $con_id,
                'message' => $prompt,
                'role' => 1,
            ];

        $this->document_chatbot_model->insert_chat($chat_data);
        //one for gpt response
        $chat_data =
            [
                'con_id' => $con_id,
                'message' => $gpt_response,
                'role' => 2,
            ];

        $response_chat_id = $this->document_chatbot_model->insert_chat($chat_data);

        //Update latest_update datetime column
        $this->document_chatbot_model->update_last_update($con_id);

        //Update conversation_history no_of_message
        $this->document_chatbot_model->increase_no_of_message($con_id);

        //Get ai response message from databse
        $chat_row_data = $this->document_chatbot_model->one_chat_row($response_chat_id);
        $gpt_response = $chat_row_data->message;

        // Send the response as JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($gpt_response));
    }

    public function load_conversation_history()
    {
        $con_id = $this->input->post('con_id');
        $chat_data = $this->document_chatbot_model->select_chat_history($con_id);

        // Send the response as JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($chat_data));
    }

    public function load_convo_card()
    {
        $conversation_history_data = $this->document_chatbot_model->select_conversation_history($this->session->userdata('user_id'));

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($conversation_history_data));
    }

    public function check_has_conversation()
    {
        //check if there is any conversation
        $has_conversation = $this->document_chatbot_model->check_if_user_has_conversation($this->session->userdata('user_id'));

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
        $latest_con_id = $this->document_chatbot_model->get_latest_con_id($this->session->userdata('user_id'));

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($latest_con_id));
    }

    public function edit_conversation_name()
    {
        $con_id = $this->input->post('con_id');
        $con_name = $this->input->post('con_name');


        $this->document_chatbot_model->edit_conversation_name($con_id, $con_name);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($con_id));
    }

    public function delete_conversation()
    {
        $con_id = $this->input->post('con_id');

        //delete converation and delete chat
        $this->document_chatbot_model->delete_conversation($con_id);
        $this->document_chatbot_model->delete_chat($con_id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($con_id));
    }
}
