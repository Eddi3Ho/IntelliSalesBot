<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['sales_model', 'items_model']);

        if (!$this->session->userdata('user_id')||!$this->session->userdata('user_role')){  
            redirect('users/login/verify_users/');
        }
        if ($this->session->has_userdata('has_login') && $this->session->userdata('user_role') != "Employee") {
            $users['user_role'] = $this->session->userdata('user_role');

            // check user role is IT
            if ($users['user_role'] == "IT") {
                redirect('items/Items');
            }
        }
    }

    public function Employee()
    {
        if ($this->session->userdata('user_role') == "Manager") {
            redirect('users/login/verify_users');
        }

        $data['title'] = 'IntelliSalesBot | Dashboard';
        $data['include_js'] = 'employee_dashboard';
        $data['selected'] = 'dashboard';

        //total items
        $data['total_items'] = count($this->items_model->select_all_items());
        // total items by category
        $data['total_items_by_category'] = $this->items_model->show_item_quantity();
        //total item low on stock
        $data['items_low_on_stock']= count($this->items_model->show_item_low_on_stock());
   
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $date=date('Y-m-d');
        // total sales in latest date
        $data['total_latest_sales'] = $this->sales_model->select_latest_sales($date);
       
        $this->load->view('internal_templates/header', $data);
        $this->load->view('external_templates/topnav');
        $this->load->view('users/employee_dashboard_view');
        $this->load->view('internal_templates/footer');
    }

    public function Manager()
    {
        if ($this->session->userdata('user_role') == "Employee") {
            redirect('users/login/verify_users');
        }

        $data['title'] = 'IntelliSalesBot | Dashboard';
        $data['include_js'] = 'manager_dashboard';
        $data['selected'] = 'dashboard';

        //total items
        $data['total_items'] = count($this->items_model->select_all_items());
        //total items by category
        $data['total_items_by_category'] = $this->items_model->show_item_quantity();
        //total item low on stock
        $data['items_low_on_stock']= count($this->items_model->show_item_low_on_stock());
   
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $date=date('Y-m-d');
        // total sales in latest date
        $data['total_latest_sales'] = $this->sales_model->select_latest_sales($date);

         $Jan = $this->get_monthly_sales('2021-01-01', '2021-01-31');
         $Feb = $this->get_monthly_sales('2021-02-01', '2021-02-28');
         $Mar = $this->get_monthly_sales('2021-03-01', '2021-03-31');
         $Apr = $this->get_monthly_sales('2021-04-01', '2021-04-30');
         $May = $this->get_monthly_sales('2021-05-01', '2021-05-31');
         $Jun = $this->get_monthly_sales('2021-06-01', '2021-06-30');
         $Jul = $this->get_monthly_sales('2021-07-01', '2021-07-31');
         $Aug = $this->get_monthly_sales('2021-08-01', '2021-08-31');
         $Sept= $this->get_monthly_sales('2021-09-01', '2021-09-30');
         $Oct = $this->get_monthly_sales('2021-10-01', '2021-10-31');
         $Nov = $this->get_monthly_sales('2021-11-01', '2021-11-30');
         $Dec = $this->get_monthly_sales('2021-12-01', '2021-12-31');

         $data['monthly_sales'] =
             [
                 $Jan,
                 $Feb,
                 $Mar,
                 $Apr,
                 $May,
                 $Jun,
                 $Jul,
                 $Aug,
                 $Sept,
                 $Oct,
                 $Nov,
                 $Dec
             ];
        
        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('users/manager_dashboard_view');
        $this->load->view('internal_templates/footer');
    }

    public function get_monthly_sales($date1, $date2) //total sales
    {
        $total_per_month = $this->sales_model->get_monthly_sales($date1, $date2) ;
        return $total_per_month;
    }
}
