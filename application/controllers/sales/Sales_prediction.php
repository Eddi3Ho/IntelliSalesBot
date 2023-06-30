<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_prediction extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['sales_model', 'items_model']);

        if (!$this->session->userdata('user_id') || !$this->session->userdata('user_role')) {
            redirect('users/login/verify_users/');
        }
        $users['user_role'] = $this->session->userdata('user_role');

        // check if user role is NOT Manager
        if ($users['user_role'] != "Manager") {
            redirect('users/login/verify_users/');
        }
    }

    public function index()
    {
        $data['title'] = 'IntelliSalesBot | Monthly Sales Prediction';
        $data['include_js'] = 'sales_prediction';
        $data['selected'] = 'sales_prediction';

        date_default_timezone_set("Asia/Kuala_Lumpur");
        $date = date('m');

        $data['item_subcategories_data'] = $this->items_model->select_all_item_subcategories();
        
        $most_sold_item = $this->sales_model->select_most_sold_item($date);
        $most_sold_item_subcategory = $this->sales_model->select_most_sold_item_subcategory($date);

        // checks if current month has items sold. if yes, what is the most sold item
        if ($most_sold_item) {
            $most_sold_item = MAX($this->sales_model->select_most_sold_item($date));
        } else{
            $most_sold_item = null;
        }

        // checks if current month has items sold. if yes, what is the most sold item subcategory
        if ($most_sold_item_subcategory) {
            $most_sold_item_subcategory = MAX($this->sales_model->select_most_sold_item_subcategory($date));
        } else {
            $most_sold_item_subcategory = null;
        }

        $data['most_sold_item'] = $most_sold_item;
        $data['most_sold_item_subcategory'] = $most_sold_item_subcategory;

        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('sales/sales_prediction_main_view');
        $this->load->view('internal_templates/footer');
    }

    public function fetch_items()
    {
        echo $this->items_model->fetch_items($this->input->post('item_subcategory_id'));
    }

    public function generate_sales_prediction()
    {
        $data['title'] = 'IntelliSalesBot | Monthly Sales Prediction';
        $data['include_js'] = 'sales_prediction';
        $data['selected'] = 'sales_prediction';

        $three_months_ago = date("Y-m-01", strtotime('-2 month', strtotime(date('Y-m-d')))); // current date - 3 months (01 - first day of the month)
        $three_months_later = date("Y-m-01", strtotime('+3 month', strtotime(date('Y-m-d')))); // current date + 3 months (01 - first day of the month)

        $period = new DatePeriod(
            new DateTime($three_months_ago), // first date
            new DateInterval('P1M'),
            new DateTime($three_months_later) // end date
        );

        $data['period'] = $period;

        $date_within_range = array(); // store the range from x-3 to x+3 of month x in this array
        $value_of_date = array();
        foreach ($period as $key => $value) {
            $date_within_range[] = $value->format('Y-m-d');
        }
        if ($this->input->post('item_id') == "all_items") {

            $all_items = $this->items_model->select_all_items_in_subcategory($this->input->post('item_subcategory_id'));
            $data['all_items'] = $all_items;
            $items = array();
            $value_of_date = array();

            foreach ($all_items as $item) {

                foreach ($date_within_range as $this_date) { // WILL LOOP 5 TIMES

                    if ($this_date <= date('Y-m-d')) { // WILL LOOP 3 TIMES
                        $one_item = $this->sales_model->select_item_sale_details_by_month($item->item_id, $this_date); // array of sales from 3 months
                        $items[] = $one_item;
                    }
                }

                $value_of_date = $items;
            }

        } else {
            foreach ($date_within_range as $this_date) {
              
                if ($this_date <= date('Y-m-d')) { // i only want to call the model for the PREVIOUS months. i dont want it to call for the future. 
                        $value_of_date[] = $this->sales_model->select_item_sale_details_by_month($this->input->post('item_id'), $this_date);
                }

            }
        }
    
        $data['date_within_range'] = $date_within_range;
        $data['value_of_date'] = $value_of_date;
        $data['item_subcategory_data'] = $this->items_model->select_all_items_in_subcategory($this->input->post('item_subcategory_id'));
        $data['item_data'] = $this->items_model->select_item($this->input->post('item_id'));
        $data['item_id'] = $this->input->post('item_id');

        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('sales/sales_generated_prediction_view');
        $this->load->view('internal_templates/footer');
    }

}
