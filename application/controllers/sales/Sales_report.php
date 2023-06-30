<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'sales_report_model'
        ]);
        date_default_timezone_set("Asia/Kuala_Lumpur");

        if (!$this->session->userdata('user_id') || !$this->session->userdata('user_role')) {
            redirect('users/login/verify_users/');
        }
        $users['user_role'] = $this->session->userdata('user_role');

        // check user role is manager
        if ($users['user_role'] != "Admin") {
            redirect('users/login/verify_users/');
        }
    }

    //loads weekly sales report page
    public function weekly_sales_report($start_date = 0, $end_date = 0)
    {
        //get date of 7days from today by default
        if ($end_date == 40) {
            $end = strtotime("+7 day");
            $end_date = date('Y-m-d', $end);
        }

        $data['title'] = 'IntelliSalesBot | Weekly Sales';
        $data['selected'] = 'sales_report';
        $data['selected_period'] = 'weekly';
        $data['sales_report_data'] = $this->sales_report_model->select_weekly_sales_report($start_date, $end_date);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['include_js'] = 'sales_weekly_report';

        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('sales/sales_weekly_report_view');
        $this->load->view('internal_templates/footer');
    }

    //loads monthly sales report page
    public function monthly_sales_report($month = 0, $year = 0)
    {

        $data['title'] = 'IntelliSalesBot | Monthly Sales';
        $data['selected'] = 'sales_report';
        $data['selected_period'] = 'monthly';
        $data['sales_report_data'] = $this->sales_report_model->select_monthly_sales_report($month, $year);
        $data['month'] = $month;
        $data['year'] = $year;
        $data['include_js'] = 'sales_monthly_report';

        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('sales/sales_monthly_report_view');
        $this->load->view('internal_templates/footer');
    }

    function export_weekly_report($start_date = 0, $end_date = 0)
    {

        header("Content-type: application/csv");
        //set file name
        header("Content-Disposition: attachment; filename=\"Sales Report between " . $start_date . " and " . $end_date . ".csv\"");
        header("Content-Description: File Transfer"); 

        $handle = fopen('php://output', 'w');

        //get sales report data
        $sales_report_data = $this->sales_report_model->export_weekly_sales_report($start_date, $end_date);

        //insert title row
        $title = array("From:", $start_date, "To: ", $end_date);
        fputcsv($handle, $title);

        //insert header row
        $header = array("Item ID", "Item Name", "Item Subcategory", "Quantity", "Total Sales (RM)");
        fputcsv($handle, $header);

        //insert data row
        foreach ($sales_report_data as $key => $line) {
            fputcsv($handle, $line);
        }

        //get total for total sales
        $total = 0;
        foreach ($sales_report_data as $row) {
            $total += $row['item_total_sale'];
        }
        $total_sales = array("", "", "", "Total: ", $total);
        fputcsv($handle, $total_sales);

        fclose($handle);
        exit;
    }

    function export_monthly_report($month = 0, $year = 0)
    {

        //Get full name of month based on number of the month
        $monthNum  = $month;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); 

        header("Content-type: application/csv");
        //set file name
        header("Content-Disposition: attachment; filename=\"Sales Report for ".$monthName." ".$year.".csv\"");
        header("Content-Description: File Transfer"); 

        $handle = fopen('php://output', 'w');

        //get sales report data
        $sales_report_data = $this->sales_report_model->export_monthly_sales_report($month, $year);

        //insert title row
        $title = array("Month:", $monthName, "Year: ", $year);
        fputcsv($handle, $title);

        //insert header row
        $header = array("Item ID", "Item Name", "Item Subcategory", "Quantity", "Total Sales (RM)");
        fputcsv($handle, $header);

        //insert data row
        foreach ($sales_report_data as $key => $line) {
            fputcsv($handle, $line);
        }

        //get total for total sales
        $total = 0;
        foreach ($sales_report_data as $row) {
            $total += $row['item_total_sale'];
        }
        $total_sales = array("", "", "", "Total: ", $total);
        fputcsv($handle, $total_sales);
        
        fclose($handle);
        exit;
    }
}
