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
        $this->load->model('items_model');
        $this->load->model('sales_model');
        $this->load->model('sales_report_model');
        $this->load->model('chatbot_model');
        $this->load->helper('gpt');

        if (!$this->session->userdata('user_id') || !$this->session->userdata('user_role')) {
            redirect('users/login/verify_users/');
        }
    }

    public function testing()
    {

        // Retrieve the data from the POST request
        $prompt = 'compare the unit sold between Blackmores Proceive Care, Acetan 100mg Tablet and Diabetmin 500mg Tablet from october 2022 to may 2023.';
        //con_id can be 0 which means its new
        $con_id = 75;

        //Latest prompt
        $conversation = array(
            'role' => 'user',
            'content' => $prompt
        );

        $item_date = $this->items_model->select_all_items();
        $items_combine = '';
        foreach ($item_date as $item_date) {
            $items_combine .= '"' . $item_date->item_id . ' = ' . $item_date->item_name . '", ';
        }

        $gpt_response = generate_function($conversation, $items_combine);
        $function_name = $gpt_response['name'];
        $arguments = json_decode($gpt_response["arguments"], true);

        $item_array = $arguments["item_id"];
        $sales_unit_decision = $arguments["sales_or_unit"];

        $startDate = strtotime($arguments['start_month_year']);
        $endDate = strtotime($arguments['end_month_year']);

        $months_range = array();

        while ($startDate <= $endDate) {
            $monthNumber = date('m', $startDate); // Get the month number
            $year = date('Y', $startDate); // Get the year
            $monthName = date('F', $startDate); // Get the full month name

            // Add month and year to the array as a subarray
            $months_range[] = array(
                'year' => $year,
                'month' => $monthNumber
            );

            $startDate = strtotime("+1 month", $startDate); // Move to the next month
        }

        //array dataset for line graph
        $dataset = array();


        foreach ($item_array as $item_id) {
            $item_data = array();
            foreach ($months_range as $period) {
                $item_monthly_data = $this->chatbot_model->total_unit_sales_per_item($period['month'], $period['year'], $item_id);

                //If item has made at least one sales, else sales is 0
                if ($item_monthly_data == false) {
                    $item_data[] = 0;
                } else {
                    $item_data[] = $item_monthly_data->item_total_sale;
                }
                $item_name = $this->chatbot_model->get_item_name($item_id);
            }
            $dataset[] = array(
                'label' => $item_name,
                'data' => $item_data,
                'borderWidth' => 3,
                'fill' => false,
            );
        }
        print_r($dataset);


        // print_r($item_date);

        // Output the array of months
        die;
    }

    public function index()
    {
        // $this->testing2();
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
        $current_month = date('m');
        $current_year = date('Y');
        $data['sales_report_data'] = $this->sales_report_model->select_monthly_sales_report($current_month, $current_year);

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

        //Latest prompt
        $conversation = array(
            'role' => 'user',
            'content' => $prompt
        );

        $item_date = $this->items_model->select_all_items();
        $items_combine = '';
        foreach ($item_date as $item_date) {
            $items_combine .= '"' . $item_date->item_id . ' = ' . $item_date->item_name . '", ';
        }

        $gpt_response = generate_function($conversation, $items_combine);
        $function_name = $gpt_response['name'];

        // Create new table in conversation history and chat history if its new chat
        if ($this->input->post('new_chat') == "yes") {

            //Default uses first five word as the conversation name
            $words = explode(" ", $prompt);
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

        //Update latest_update datetime column
        $this->chatbot_model->update_last_update($con_id);

        //Update conversation_history no_of_message
        $this->chatbot_model->increase_no_of_message($con_id);

        //Create new chat regardless of whether its new chat or not
        //One for user prompt
        $chat_data =
            [
                'con_id' => $con_id,
                'message' => $prompt,
                'role' => 1,
            ];

        $chat_id = $this->chatbot_model->insert_chat($chat_data);

        if (method_exists($this, $function_name)) {

            //Choosing functions to call
            $arguments = json_decode($gpt_response["arguments"], true);
            //calls function given by gpt and pass the parameters given by gpt
            $response = call_user_func_array([$this, $function_name], array($arguments, $chat_id));

            #check if there are item missing 
            $has_null = 0;

            if ($response['type_graph'] == 4) {
                foreach ($response['dataset'] as $data) {
                    if ($data['label'] === null) {
                        $has_null = 1;
                    }
                }
            }

            if ($has_null) {
                $chat_data =
                    [
                        'con_id' => $con_id,
                        'message' => "One or more of the item you requested cannot be found from the inventory",
                        'role' => 2,
                    ];

                $this->chatbot_model->insert_chat($chat_data);
            } else {
                //Serialize array for visualization data and then save it
                $serialized_response = serialize($response);

                $chat_data =
                    [
                        'con_id' => $con_id,
                        'message' => $serialized_response,
                        'role' => 3,
                    ];
                $this->chatbot_model->insert_chat($chat_data);
            }


            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } else {

            //save fail response
            $chat_data =
                [
                    'con_id' => $con_id,
                    'message' => "I do not understand your question. Please try again or refer to the user guide for more information on how to structure your question",
                    'role' => 2,
                ];
            $this->chatbot_model->insert_chat($chat_data);

            //If function do not exist
            $response = ["success" => false];
            // Send the response as JSON
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
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

    public function serialize_message()
    {
        $chat_id = $this->input->post('chat_id');

        $test_data = $this->chatbot_model->one_chat_row($chat_id);
        $unserialize = unserialize($test_data->message);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($unserialize));
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

    //========================= Sales Chatbot called Functions ============================
    public function get_top_selling_category_dates($arguments, $chat_id)
    {

        $results = $this->chatbot_model->select_weekly_sales_report($arguments["start_date"], $arguments["end_date"], $arguments["limit"], "category", "unit");

        $itemSubcategories = [];
        $itemQuantities = [];
        $limit = 0;
        // Loop through the results and extract the desired columns
        foreach ($results as $row) {
            $itemSubcategories[] = $row->item_subcategory_name;
            $itemQuantities[] = $row->item_total_quantity;
            $limit++;
        }
        $new_start_date = (new DateTime($arguments["start_date"]))->format('d F Y');
        $new_end_date = (new DateTime($arguments["end_date"]))->format('d F Y');

        // Prepare the JSON response with the extracted data
        $response = [
            'success' => true,
            'xaxis' => $itemSubcategories,
            'yaxis' => $itemQuantities,
            'limit' => $limit,
            'chat_id' => $chat_id,
            'title' => 'Top ' . $limit . ' best selling category from ' . $new_start_date . ' to ' . $new_end_date,
            'label' => 'Total Units Sold',
            'time_frame' => 'date',
            'type_graph' => 1
        ];

        return $response;
    }

    public function get_top_selling_category_monthly($arguments, $chat_id)
    {

        $results = $this->chatbot_model->select_monthly_sales_report($arguments["month"], $arguments["year"], $arguments["limit"], "category", "unit");

        $itemSubcategories = [];
        $itemQuantities = [];
        $limit = 0;
        // Loop through the results and extract the desired columns
        foreach ($results as $row) {
            $itemSubcategories[] = $row->item_subcategory_name;
            $itemQuantities[] = $row->item_total_quantity;
            $limit++;
        }

        $dateObj = DateTime::createFromFormat('!m', $arguments["month"]);
        $monthName = $dateObj->format('F'); // March

        // Prepare the JSON response with the extracted data
        $response = [
            'success' => true,
            'xaxis' => $itemSubcategories,
            'yaxis' => $itemQuantities,
            'limit' => $limit,
            'chat_id' => $chat_id,
            'title' => 'Top ' . $limit . ' best selling category for ' . $monthName . ' ' . $arguments["year"],
            'label' => 'Total Units Sold',
            'time_frame' => 'month',
            'type_graph' => 1
        ];

        return $response;
    }

    public function get_top_earning_item_dates($arguments, $chat_id)
    {

        $results = $this->chatbot_model->select_weekly_sales_report($arguments["start_date"], $arguments["end_date"], $arguments["limit"], "item", "price");

        $item_name = [];
        $item_total_sale = [];
        $limit = 0;
        // Loop through the results and extract the desired columns
        foreach ($results as $row) {
            $item_name[] = $row->item_subcategory_name;
            $item_total_sale[] = $row->item_total_sale;
            $limit++;
        }
        $new_start_date = (new DateTime($arguments["start_date"]))->format('d F Y');
        $new_end_date = (new DateTime($arguments["end_date"]))->format('d F Y');

        // Prepare the JSON response with the extracted data
        $response = [
            'success' => true,
            'xaxis' => $item_name,
            'yaxis' => $item_total_sale,
            'limit' => $limit,
            'chat_id' => $chat_id,
            'title' => 'Top ' . $limit . ' best earning items in terms of sales from ' . $new_start_date . ' to ' . $new_end_date,
            'label' => 'Total Sales Earned',
            'time_frame' => 'date',
            'type_graph' => 2
        ];

        return $response;
    }

    public function get_top_earning_items_monthly($arguments, $chat_id)
    {
        $results = $this->chatbot_model->select_monthly_sales_report($arguments["month"], $arguments["year"], $arguments["limit"], "item", "price");

        $item_name = [];
        $item_total_sale = [];
        $limit = 0;
        // Loop through the results and extract the desired columns
        foreach ($results as $row) {
            $item_name[] = $row->item_name;
            $item_total_sale[] = $row->item_total_sale;
            $limit++;
        }
        $dateObj = DateTime::createFromFormat('!m', $arguments["month"]);
        $monthName = $dateObj->format('F'); // March

        // Prepare the JSON response with the extracted data
        $response = [
            'success' => true,
            'xaxis' => $item_name,
            'yaxis' => $item_total_sale,
            'limit' => $limit,
            'chat_id' => $chat_id,
            'title' => 'Top ' . $limit . ' best earning item in terms of sales for ' . $monthName . ' ' . $arguments["year"],
            'label' => 'Total Sales Earned',
            'time_frame' => 'month',
            'type_graph' => 2
        ];

        return $response;
    }

    public function sales_earning_table_dates($arguments, $chat_id)
    {
        $results = $this->chatbot_model->select_weekly_sales_report($arguments["start_date"], $arguments["end_date"], $arguments["limit"], "item", "price");

        if (!empty($results) && is_array($results)) {

            $limit = 0;

            $new_start_date = (new DateTime($arguments["start_date"]))->format('d F Y');
            $new_end_date = (new DateTime($arguments["end_date"]))->format('d F Y');

            $index = 1;
            // Initialize an empty variable to store the table HTML
            $tableHtml = '';

            // Start building the table HTML
            $tableHtml .=
                '<table class="table data-table" >
                <thead>
                    <tr>
                        <th colspan="5" style ="font-size:1.5rem;" class="text-center">Top ' . count($results) . ' best earning items in terms of sales from ' . $new_start_date . ' to ' . $new_end_date . '</th>
                    </tr>   
                </thead>
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Item Name</th>
                        <th scope="col">Subcategory</th>
                        <th scope="col">Unit Sold</th>
                        <th scope="col">Total Sales (RM)</th>
                    </tr>   
                </thead>';
            $tableHtml .= '<tbody>';

            // Generate table rows and cells
            foreach ($results as $results) {
                $tableHtml .=
                    '<tr>
                <td>' . $index . '</td>
                <td>' . $results->item_name . '</td>
                <td>' . $results->item_subcategory_name . '</td>
                <td>' . $results->item_total_quantity . '</td>
                <td>' . $results->item_total_sale . '</td>
            </tr>';

                $index++;
            }

            $tableHtml .= '</tbody>';
            $tableHtml .= '</table>';

            // Prepare the JSON response with the extracted data
            $response = [
                'success' => true,
                'table_data' => $tableHtml,
                'limit' => $limit,
                'chat_id' => $chat_id,
                'title' => 'Top ' . $limit . ' best earning items in terms of sales from ' . $new_start_date . ' to ' . $new_end_date,
                'label' => 'Total Sales Earned',
                'type_graph' => 3,
                'exist_data' => 1,
            ];
        } else {
            $response = [
                'success' => true,
                'chat_id' => $chat_id,
                'type_graph' => 3,
                'exist_data' => 0,
            ];
        }

        return $response;
    }

    public function sales_earning_table_monthly($arguments, $chat_id)
    {
        $results = $this->chatbot_model->select_monthly_sales_report($arguments["month"], $arguments["year"], $arguments["limit"], "item", "price");

        if (!empty($results) && is_array($results)) {

            $limit = 0;

            $dateObj = DateTime::createFromFormat('!m', $arguments["month"]);
            $monthName = $dateObj->format('F'); // March    

            $index = 1;
            // Initialize an empty variable to store the table HTML
            $tableHtml = '';

            // Start building the table HTML
            $tableHtml .=
                '
                <table class="table data-table">
                <thead>
                    <tr>
                        <th colspan="5" style ="font-size:1.5rem;" class="text-center">Top ' . count($results) . ' best earning item in terms of sales for ' . $monthName . ' ' . $arguments["year"] . '</th>
                    </tr>   
                </thead>
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Item Name</th>
                        <th scope="col">Subcategory</th>
                        <th scope="col">Unit Sold</th>
                        <th scope="col">Total Sales (RM)</th>
                    </tr>   
                </thead>';
            $tableHtml .= '<tbody>';

            // Generate table rows and cells
            foreach ($results as $results) {
                $tableHtml .=
                    '<tr>
                <td>' . $index . '</td>
                <td>' . $results->item_name . '</td>
                <td>' . $results->item_subcategory_name . '</td>
                <td>' . $results->item_total_quantity . '</td>
                <td>' . $results->item_total_sale . '</td>
            </tr>';

                $index++;
            }

            $tableHtml .= '</tbody>';
            $tableHtml .= '</table>';

            // Prepare the JSON response with the extracted data
            $response = [
                'success' => true,
                'table_data' => $tableHtml,
                'limit' => $limit,
                'chat_id' => $chat_id,
                'title' => 'Top ' . $limit . ' best earning item in terms of sales for ' . $monthName . ' ' . $arguments["year"],
                'label' => 'Total Sales Earned',
                'type_graph' => 3,
                'exist_data' => 1,
            ];
        } else {
            $response = [
                'success' => true,
                'chat_id' => $chat_id,
                'type_graph' => 3,
                'exist_data' => 0,
            ];
        }

        return $response;
    }

    public function compare_items_sales_unit_dates($arguments, $chat_id)
    {
        $item_array = $arguments["item_id"];
        $sales_unit_decision = $arguments["sales_or_unit"];

        $startDate = strtotime($arguments['start_month_year']);
        $endDate = strtotime($arguments['end_month_year']);

        $months_range = array();
        //array of months for the label in the line graph
        $months_labels = array();

        while ($startDate <= $endDate) {
            $monthNumber = date('m', $startDate); // Get the month number
            $year = date('Y', $startDate); // Get the year
            $monthName = date('F', $startDate); // Get the full month name

            // Add month and year to the array as a subarray
            $months_range[] = array(
                'year' => $year,
                'month' => $monthNumber
            );
            $months_labels[] = $monthName;


            $startDate = strtotime("+1 month", $startDate); // Move to the next month
        }

        //initializing the colors for the line graph
        $backgroundColors = [
            'rgba(59, 117, 242, 0.2)',
            'rgba(255, 99, 132, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(231, 76, 60, 0.2)',
            'rgba(255, 140, 0, 0.2)',
            'rgba(128, 0, 128, 0.2)',
        ];

        $borderColors = [
            'rgba(59, 117, 242, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(231, 76, 60, 1)',
            'rgba(255, 140, 0, 1)',
            'rgba(128, 0, 128, 1)',
        ];

        //array dataset for line graph
        $dataset = array();
        //Title for graph
        if ($sales_unit_decision == "sales") {
            $title = "Sales made by ";
        } else {
            $title = "Unit sold by ";
        }
        $index = 0;

        //Get sales data for all the requested months and put it into a dataset array that will be used for chatjs once its return to the ajax function
        foreach ($item_array as $item_id) {
            $item_data = array();
            foreach ($months_range as $period) {


                $item_monthly_data = $this->chatbot_model->total_unit_sales_per_item($period['month'], $period['year'], $item_id);

                //If item has made at least one sales, else sales is 0
                if ($item_monthly_data == false) {
                    $item_data[] = 0;
                } else {
                    //If user want sales graph or unit sold graph
                    if ($sales_unit_decision == "sales") {
                        $item_data[] = $item_monthly_data->item_total_sale;
                    } else {
                        $item_data[] = $item_monthly_data->item_total_quantity;
                    }
                }
            }
            $item_name = $this->chatbot_model->get_item_name($item_id);
            $title .= $item_name->item_name . ", ";

            $dataset[] = array(
                'label' => $item_name->item_name,
                'data' => $item_data,
                'backgroundColor' => $backgroundColors[$index],
                'borderColor' => $borderColors[$index],
                'borderWidth' => 3,
                'tension' => 0,
                'fill' => true,
            );

            $index++;
        }
        // print_r($dataset);
        // die;

        //complete title
        $title .= "from " . $arguments['start_month_year'] . " to " . $arguments['end_month_year'];

        // Prepare the JSON response with the extracted data
        $response = [
            'success' => true,
            'dataset' => $dataset,
            'chat_id' => $chat_id,
            'title' => $title,
            'label' => $months_labels,
            'type_graph' => 4
        ];


        return $response;
    }

    public function testing2()
    {
        // Retrieve the data from the POST request
        $prompt = "compare the unit sold between Blackmores Proceive Care, Acetan 100mg Tablet and Diabetmin 500mg Tablet from october 2022 to may 2023";
        //con_id can be 0 which means its new
        $con_id = 75;

        //Latest prompt
        $conversation = array(
            'role' => 'user',
            'content' => $prompt
        );

        $item_date = $this->items_model->select_all_items();
        $items_combine = '';
        foreach ($item_date as $item_date) {
            $items_combine .= '"' . $item_date->item_id . ' = ' . $item_date->item_name . '", ';
        }

        $gpt_response = generate_function($conversation, $items_combine);
        $function_name = $gpt_response['name'];


        if (method_exists($this, $function_name)) {

            // Create new table in conversation history and chat history if its new chat
            if ($this->input->post('new_chat') == "yes") {

                //Default uses first five word as the conversation name
                $words = explode(" ", $prompt);
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

            //Update latest_update datetime column
            $this->chatbot_model->update_last_update($con_id);

            //Update conversation_history no_of_message
            $this->chatbot_model->increase_no_of_message($con_id);

            //Create new chat regardless of whether its new chat or not
            //One for user prompt
            $chat_data =
                [
                    'con_id' => 75,
                    'message' => $prompt,
                    'role' => 1,
                ];

            $chat_id = $this->chatbot_model->insert_chat($chat_data);


            //Choosing functions to call
            $arguments = json_decode($gpt_response["arguments"], true);
            //calls function given by gpt and pass the parameters given by gpt
            $response = call_user_func_array([$this, $function_name], array($arguments, $chat_id));

            //one for gpt response
            //================change
            // $chat_data =
            //     [
            //         'con_id' => $con_id,
            //         'message' => $gpt_response,
            //         'role' => 2,
            //     ];

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        } else {
            //If function do not exist
            $response = ["success" => false];
            // Send the response as JSON
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }
}
