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

        // $sales_data = $this->sales_model->select_all_sales();

        // // Loop through the data and convert it into a sentence
        // foreach ($sales_data as $sales_row) {
        //     $sale_id = $sales_row->sale_id;
        //     $sale_total_price = $sales_row->sale_total_price;
        //     $sale_discounted_price = $sales_row->sale_discounted_price;
        //     $sale_date = $sales_row->sale_date;

        //     $sentence = "On " . $sale_date . ", one customer bought ";


        //     // Query the second table for sales item details
        //     $item_sales_data = $this->sales_model->select_sales_item($sale_id);

        //     // Nested loop for item details
        //     foreach ($item_sales_data as $item_sales_row) {
        //         $item_name = $item_sales_row->item_name;
        //         $item_subcategory_name = $item_sales_row->item_subcategory_name;
        //         $sale_item_quantity = $item_sales_row->sale_item_quantity;
        //         $sale_item_total_price = $item_sales_row->sale_item_total_price;
        //         $sale_item_discount = $item_sales_row->sale_item_discount;

        //         // Combine item details with the main sentence
        //         $sentence .= ", ".$sale_item_quantity . " " . $item_name ." under the category of " .$item_subcategory_name. " were sold at a price of " . $sale_item_total_price . " with a discount of " . $sale_item_discount ."%";
        //     }

        //     $sentence .= ". The total price for this sales before discount is RM " . $sale_total_price . " and after discount is RM ". $sale_discounted_price . ".";

        //     echo $sentence;

        //     // Add the sentence to the conversation array as a user role
        //     $conversation[] = array(
        //         'role' => 'user',
        //         'content' => $sentence
        //     );
        // }
        // die;

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
            // $prompt = $this->input->post('prompt');
            // $gpt_response = generate_text($prompt);


        //Save user prompt and gpt response
            // $data =
            //     [
            //         'user_id' => $this->session->userdata('user_id'),
            //     ];

            // $this->chat_model->insert_history($data);

        //Querying Sales report and convert them into sentence language
        $conversation = array(
            array('role' => 'system', 'content' => 'You are an assistant that can provide information and insight about sales reports you were given.'),
            array('role' => 'system', 'content' => 'You are an assistant that can provide information and insight about sales reports you were given.')
        );


        // Query for sales data
        $sales_data = $this->sales_model->select_all_sales();

        // Loop through the data and convert it into a sentence
        foreach ($sales_data as $sales_row) {
            $sale_id = $sales_row->sale_id;
            $sale_total_price = $sales_row->sale_total_price;
            $sale_discounted_price = $sales_row->sale_discounted_price;
            $sale_date = $sales_row->sale_date;

            $sentence = "On " . $sale_date;


            // Query the second table for sales item details
            $item_sales_data = $this->sales_model->select_sales_item($sale_id);

            // Nested loop for item details
            foreach ($item_sales_data as $item_sales_row) {
                $item_name = $item_sales_row->item_name;
                $item_subcategory_name = $item_sales_row->item_subcategory_name;
                $sale_item_quantity = $item_sales_row->sale_item_quantity;
                $sale_item_total_price = $item_sales_row->sale_item_total_price;
                $sale_item_discount = $item_sales_row->sale_item_discount;

                // Combine item details with the main sentence
                $sentence .= ", ".$sale_item_quantity . " " . $item_name ." under the category of " .$item_subcategory_name. " were sold at a price of " . $sale_item_total_price . " with a discount of " . $sale_item_discount ."%";
            }

            $sentence .= ". The total price for this sales before discount is " . $sale_total_price . " and after discount is ". $sale_discounted_price . ".";

            echo $sentence;

            // Add the sentence to the conversation array as a user role
            $conversation[] = array(
                'role' => 'user',
                'content' => $sentence
            );
        }

        // Send the response as JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($conversation));
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('email');
        $this->load->model('user_model');
        $this->load->model('sales_model');
        $this->load->model('chatbot_model');


        if (!$this->session->userdata('user_id') || !$this->session->userdata('user_role')) {
            redirect('users/login/verify_users/');
        }
    }

    public function index()
    {
        $data['title'] = 'IntelliSalesBot | Chatbot';
        $data['selected'] = 'document';
        $data['include_js'] = 'document_chatbot';

        
        $conversation = array(
            array('role' => 'system', 'content' => 'You uses "\n" when there is a line break'),
            array('role' => 'system', 'content' => 'You are an AI sales analyst. Please provide insights on the following sales data')
        );

        
        $sales_data = $this->sales_model->select_all_sales();
        $sentence = '';
        $row_counter = 0;

        // Loop through the data and convert it into a sentence
        foreach ($sales_data as $sales_row) {
            $sale_id = $sales_row->sale_id;
            $sale_total_price = $sales_row->sale_total_price;
            $sale_discounted_price = $sales_row->sale_discounted_price;
            $sale_date = $sales_row->sale_date;

            $timestamp = strtotime($sale_date);
            $formatted_date = date("F j, Y", $timestamp);

            // $sentence = "On " . $formatted_date . ", a customer purchased ";

            // Query the second table for sales item details
            $item_sales_data = $this->sales_model->select_sales_item($sale_id);
            //Get last iteration
            // $last_item = end($item_sales_data);

            $total_profit = 0;

            // Nested loop for item details
            foreach ($item_sales_data as $item_sales_row) {
                //item info
                $item_name = $item_sales_row->item_name;
                $item_subcategory_name = $item_sales_row->item_subcategory_name;
                $item_price = $item_sales_row->item_price;
                $item_cost_price = $item_sales_row->item_cost_price;

                //individual sales info
                $sale_item_quantity = $item_sales_row->sale_item_quantity;
                $sale_item_total_price = $item_sales_row->sale_item_total_price;
                $sale_item_discount = $item_sales_row->sale_item_discount;

                $cost_price = $item_cost_price * $sale_item_quantity;
                $profit = $sale_item_total_price - ($item_cost_price * $sale_item_quantity);
                $total_profit += $profit;
                $row_counter++;

                // Combine item details with the main sentence
                // $sentence .= "On " . $formatted_date . ", " . $sale_item_quantity . " unit(s) of '" . $item_name . "' (" . $item_subcategory_name . ") were sold for RM" . $item_price . " each, generating a total sales revenue of RM" . $sale_item_total_price . " and a profit of RM" . $profit . ". <br>";
                // Add the sentence to the conversation array as a user role
                $sentence .= "Row ".$row_counter. ": {".$formatted_date.", ".$sale_item_quantity.", ".$item_name.", ".$item_subcategory_name.", ".$item_price.", ".$profit."}<br>";

                $conversation[] = array(
                    'role' => 'user',
                    'content' => $sentence
                );
            }

            // $sentence .= ". The total sale was RM" . $sale_total_price . " generating a profit of RM" . $total_profit . ". ";

        }
        echo $sentence;
        // print_r($conversation);

        $data['conversation'] = $sentence;

        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('bot/document_view');
        $this->load->view('internal_templates/footer');
    }
}
