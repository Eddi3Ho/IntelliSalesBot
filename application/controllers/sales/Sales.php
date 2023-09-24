<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'sales_model'
		]);
		date_default_timezone_set("Asia/Kuala_Lumpur");

		if (!$this->session->userdata('user_id') || !$this->session->userdata('user_role')) {
			redirect('users/login/verify_users/');
		}
		$users['user_role'] = $this->session->userdata('user_role');

		// check user role is IT
		if ($users['user_role'] == "IT") {
			redirect('items/Items');
		}
	}

	public function index()
	{
		//directed to monthly sales if the user is the manager
		if ($this->session->userdata('user_role') == 'Admin') {
			$this->daily_sales_list(date('Y-m-d'));
		}
		//directed to sales list page with if the user is a staff
		else {
			$data['title'] = 'IntelliSalesBot | Sales';
			$data['include_js'] = 'sales_list';
			$data['subcategory_data'] = $this->sales_model->select_all_item_subcategory();
			$data['selected'] = 'sales';

			//loading views and passing data to view
			$this->load->view('internal_templates/header', $data);
			$this->load->view('external_templates/topnav');
			$this->load->view('sales/sales_list_view');
			$this->load->view('internal_templates/footer');
		}
	}

	//function to load received data from database and load it into datatable
	public function sales_list()
	{
		// Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		$sales_data = $this->sales_model->select_daily_sales(date('Y-m-d'));

		$data = array();
		$base_url = base_url();

		foreach ($sales_data as $r) {

			$edit_link = $base_url . "sales/sales/load_edit_page/" . $r->sale_id;

			$edit_opt = '<span class = "px-1"><a type="button" href = "' . $edit_link . '" class="btn icon-btn btn-lg btn-white waves-effect waves-light"><span style = "color:#3b75f2;" class="fas fa-pencil-alt"></span></a></span>';
			$view = '<span><button type="button" onclick="view_sale(' . $r->sale_id . ')" class="btn icon-btn btn-lg btn-white waves-effect waves-light" data-toggle="modal" data-target="#view_sales"><span style = "color:#3b75f2;" class="fas fa-eye"></span></button></span>';
			$function = $view . $edit_opt;

			$category_data = $this->sales_model->get_category_only($r->sale_id);
			$list_html = "";

			foreach ($category_data as $q) {
				$list_html .= '<li>' . $q->item_category_name . '</li>';
			}


			$data[] = array(
				'',
				$r->sale_id,
				$r->sale_date,
				"RM " . number_format($r->sale_total_price, 2, '.', ''),
				"RM " . number_format($r->sale_discounted_price, 2, '.', ''),
				$r->user_fname . " " . $r->user_lname,
				"<ul class = 'category_list'>" . $list_html . "</ul>",
				$function,
			);
		}

		$output = array(
			"draw" => $draw,
			"recordsTotal" => count($sales_data),
			"recordsFiltered" => count($sales_data),
			"data" => $data
		);

		echo json_encode($output);
		exit();
	}

	function add_sales()
	{
		//initializing arrays
		$item_id = $this->input->post('item_id');
		$sale_item_quantity = $this->input->post('sale_item_quantity');
		$sale_item_discount = $this->input->post('sale_item_discount');
		$sale_item_total_price = $this->input->post('sale_item_price');

		// echo $this->input->post('sale_total_price');
		// die;
		//if there are no item added
		if ($this->input->post('sale_total_price') == 0) {
			$this->session->set_userdata('no_item_message', '<div id = "alert_message" class="alert alert-danger px-4" role="alert">No item selected, please try again</div>');
		} else {
			//initializing sales data and inserting the data into sales table
			$data =
				[
					'sale_total_price' => htmlspecialchars($this->input->post('sale_total_price')),
					'sale_discounted_price' => htmlspecialchars($this->input->post('sale_discounted_price')),
					'user_id' => $this->session->userdata('user_id'),
				];

			$sale_id = $this->sales_model->insert($data);

			foreach ($item_id as $index => $item_id) {
				//initializing sales_item data and inserting the data into sales_item table row by row
				$data =
					[
						'sale_id' => $sale_id,
						'item_id' => $item_id,
						'sale_item_quantity' => $sale_item_quantity[$index],
						'sale_item_discount' => $sale_item_discount[$index],
						'sale_item_total_price' => $sale_item_total_price[$index],
					];

				$this->sales_model->insert_sales_item($data);

				//update item_quantity in items table
				$this->sales_model->update_quantity_from_item($item_id, $sale_item_quantity[$index]);
			}

			$this->session->set_userdata('add_sale_message', 1);
		}

		redirect('sales/sales/');
	}

	//Fetching item images to add sales modal based on selected subcategory
	function fetch_item_image()
	{
		$image_row = $this->sales_model->fetch_item_image($this->input->post('item_subcategory_id'));
		echo $image_row;
	}

	//Fetching item images to edit sales page based on selected subcategory
	function fetch_item_image_for_edit()
	{
		$image_row = $this->sales_model->fetch_item_image_for_edit($this->input->post('item_subcategory_id'));
		echo $image_row;
	}


	//function for viewing a single sale after user click on the view button
	function view_sale()
	{
		$sale_id = $this->input->post('sale_id');
		$sale_data = $this->sales_model->select_one_sale($sale_id);
		$sale_item_data = $this->sales_model->select_sales_item($sale_id);

		$sale_row_html = '';
		$counter = 0;
		foreach ($sale_item_data as $r) {
			$counter++;
			//implement background color for even number row

			$item_pic = '<img class="view_img_item" src="'.base_url("assets/img/items/").$r->item_pic.'" style="width: 60px; height: 60px; object-fit:contain;">';

			$sale_row_html .= '<tr>
								<td>' . $counter . '</td>
								<td>' . $item_pic . '</td>
								<td>' . $r->item_subcategory_name . '</td>
								<td>' . $r->item_name . '</td>
								<td>' . $r->sale_item_quantity . '</td>
								<td>' . $r->sale_item_discount . '%</td>
								<td>RM ' . number_format($r->sale_item_total_price, 2, '.', '') . '</td>
							   <tr>';
		}

		$output = '
		<style>
			.view_img_item {
				transition:transform 0.25s ease;
			}
			
			.view_img_item:hover {
					-webkit-transform:scale(2.0);
					transform:scale(2.0);
					border: 2px solid rgba(0, 0, 0, 0.5) !important;
			}
		</style>
		<div class="table-responsive mb-4">
			<table id="table_view_sale" class="table table-striped">
				<thead>
					<tr>
						<th>No.</th>
						<th>Picture of Item</th>
						<th>Subcategories</th>
						<th>Item Name</th>
						<th>Quantity</th>
						<th>Discount</th>
						<th>Total Item Price</th>
					</tr>
				</thead>
				<tbody>
					' . $sale_row_html . '
				</tbody>
			</table>
		</div>
		<div class="form-group row">
			<div class="col-xl-6"></div>
			<label for="staticEmail" class="col-xl-2 col-form-label">Total Price</label>
			<div class="col-xl-4">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon1">RM</span>
					<input type="number"  style="font-weight:600; float:right;" class="form-control" value="' . number_format($sale_data->sale_total_price, 2, '.', '') . '" readonly />
				</div>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-xl-6"></div>
			<label for="staticEmail" class="col-xl-2 col-form-label">Discounted Price</label>
			<div class="col-xl-4">
				<div class="input-group-prepend">
					<span class="input-group-text" id="basic-addon2">RM</span>
					<input type="number" style="font-weight:600; float:right;" class="form-control" value="' . number_format($sale_data->sale_discounted_price, 2, '.', '') . '" readonly />
				</div>
			</div>
		</div>
        ';

		echo $output;
	}

	//Function is loaded when user clicked on the pencil icon in the sales table
	function load_edit_page($sale_id)
	{
		$data['title'] = 'IntelliSalesBot | Edit Sales';
		$data['include_js'] = 'sales_edit';
		$data['subcategory_data'] = $this->sales_model->select_all_item_subcategory();
		$data['sales_data'] = $this->sales_model->select_one_sale($sale_id);
		$data['sales_item_data'] = $this->sales_model->select_sales_item($sale_id);
		$data['no_sales_item_data'] = count($this->sales_model->select_sales_item($sale_id));
		$data['sale_id'] = $sale_id;
		$data['selected'] = 'sales';

		//loading views and passing data to view
		$this->load->view('internal_templates/header', $data);
		$this->load->view('external_templates/topnav');
		$this->load->view('sales/sales_edit_view');
		$this->load->view('internal_templates/footer');
	}

	function edit_sales($sale_id)
	{
		//initializing arrays
		$item_id = $this->input->post('item_id');
		$sale_item_quantity = $this->input->post('sale_item_quantity');
		$sale_item_discount = $this->input->post('sale_item_discount');
		$sale_item_total_price = $this->input->post('sale_item_price');

		//if there are no item added
		if ($this->input->post('sale_total_price') == 0) {
			$this->session->set_userdata('no_item_message', '<div id = "alert_message" class="alert alert-danger px-4" role="alert">No item selected for edit, please try again</div>');
		} else {
			//initializing sales data and editing the data in sales table
			$data =
				[
					'sale_total_price' => htmlspecialchars($this->input->post('sale_total_price')),
					'sale_discounted_price' => htmlspecialchars($this->input->post('sale_discounted_price')),
					'user_id' => 1,
				];

			$this->sales_model->update_sale($data, $sale_id);

			//select old sales item data
			$old_sales_item_data =  $this->sales_model->select_sales_item($sale_id);

			//return item quantity of old sales item back to item table
			foreach ($old_sales_item_data as $row) {

				$this->sales_model->return_quantity_to_item($row->item_id, $row->sale_item_quantity);
			}

			//delete old sales item data before inserting new one
			$this->sales_model->delete_sales_item($sale_id);

			foreach ($item_id as $index => $item_id) {
				//initializing sales_item data and inserting the new data into sales_item table row by row
				$data =
					[
						'sale_id' => $sale_id,
						'item_id' => $item_id,
						'sale_item_quantity' => $sale_item_quantity[$index],
						'sale_item_discount' => $sale_item_discount[$index],
						'sale_item_total_price' => $sale_item_total_price[$index],
					];

				$this->sales_model->insert_sales_item($data);

				//update item_quantity in items table
				$this->sales_model->update_quantity_from_item($item_id, $sale_item_quantity[$index]);
			}

			$this->session->set_userdata('edit_sale_message', 1);
		}

		redirect('sales/sales/');
	}

	///////////////////////////// Function for manager pages /////////////////////////////

	//loads daily sales page
	public function daily_sales_list($date)
	{
		//check if the user is the manager
		if ($this->session->userdata('user_role') != 'Admin') {
			redirect('sales/sales/');
		}

		$data['title'] = 'IntelliSalesBot | Daily Sales';
		$data['selected'] = 'sales';
		$data['selected_period'] = 'daily';
		$data['sales_data'] = $this->sales_model->select_daily_sales($date);
		$data['date'] = $date;
		$data['include_js'] = 'sales_daily';

		$this->load->view('internal_templates/header', $data);
		$this->load->view('internal_templates/sidenav');
		$this->load->view('internal_templates/topbar');
		$this->load->view('sales/sales_daily_view');
		$this->load->view('internal_templates/footer');
	}

	//loads weekly sales page
	public function weekly_sales_list($start_date = 0, $end_date = 0)
	{

		//check if the user is the manager
		if ($this->session->userdata('user_role') != 'Admin') {
			redirect('sales/sales/');
		}

		//get date of 7days from today by default
		if ($end_date == 40) {
			$end = strtotime("+7 day");
			$end_date = date('Y-m-d', $end);
		}

		$data['title'] = 'IntelliSalesBot | Weekly Sales';
		$data['selected'] = 'sales';
		$data['selected_period'] = 'weekly';
		$data['sales_data'] = $this->sales_model->select_weekly_sales($start_date, $end_date);
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['include_js'] = 'sales_weekly';

		$this->load->view('internal_templates/header', $data);
		$this->load->view('internal_templates/sidenav');
		$this->load->view('internal_templates/topbar');
		$this->load->view('sales/sales_weekly_view');
		$this->load->view('internal_templates/footer');
	}

	//loads monthly sales page
	public function monthly_sales_list($month = 0, $year = 0)
	{
		//check if the user is the manager
		if ($this->session->userdata('user_role') != 'Admin') {
			redirect('sales/sales/');
		}

		$data['title'] = 'IntelliSalesBot | Monthly Sales';
		$data['selected'] = 'sales';
		$data['selected_period'] = 'monthly';
		$data['sales_data'] = $this->sales_model->select_monthly_sales($month, $year);
		$data['month'] = $month;
		$data['year'] = $year;
		$data['include_js'] = 'sales_monthly';

		$this->load->view('internal_templates/header', $data);
		$this->load->view('internal_templates/sidenav');
		$this->load->view('internal_templates/topbar');
		$this->load->view('sales/sales_monthly_view');
		$this->load->view('internal_templates/footer');
	}
}
