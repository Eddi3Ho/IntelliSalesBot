<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Items extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');

        // checks if user is signed in
        if (!$this->session->userdata('user_id') || !$this->session->userdata('user_role')) {
			redirect('users/login/verify_users/');
		}
		$users['user_role'] = $this->session->userdata('user_role');
    }

    // --------------------- ITEM --------------------------// 
    public function index()
    {
        // check if the user is not IT and not Manager
		if ($this->session->userdata('user_role') != 'Admin') {
			redirect('items/Items/items_categories_log');
		}

        $data['title'] = 'IntelliSalesBot | Items';
        $data['include_js'] = 'items_list';
        $data['selected'] = 'items';

        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('items/items_list_view');
        $this->load->view('internal_templates/footer');
    }

    function items_list()
    {
        // Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

        $items = $this->items_model->select_all_items();

		$data = array();
		$base_url = base_url();

        foreach($items as $item) {

            $view = '<span><button type="button" onclick="view_item('.$item->item_id.')" class="btn icon-btn btn-xs btn-white waves-effect waves-light" data-toggle="modal" data-target="#view_item"><span class="fas fa-eye" style="color: #3b75f2"></span></button></span>';
            $edit_link = $base_url."items/Items/edit_item/".$item->item_id;
            $edit_opt = '<span class="px-1"><a type="button" href="'.$edit_link.'"class="btn icon-btn btn-xs btn-white waves-effect waves-light"><span class="fas fa-pencil-alt" style="color: #3b75f2"></span></a></span>';
            $delete = '<span><button type="button" onclick="delete_item('.$item->item_id.')" class="btn icon-btn btn-xs btn-white waves-effect waves-light delete" ><span class="fas fa-trash" style="color: #3b75f2"></span></button></span>';
			
            $function = $view.$edit_opt.$delete;

            
            $data [] = [ 
				// '',
				$item->item_id,
				$item->item_category_name,
				$item->item_name,
                $item->item_quantity,
                "RM$item->item_price",
                $function,
                $item->item_updated_date
            ];

		}

        $output = array(
			"draw" => $draw,
			"recordsTotal" => count($items),
			"recordsFiltered" =>count($items),
			"data" => $data
		);

		echo json_encode($output);
		exit();
    }

    function view_item()
    {
        $item_details = $this->items_model->select_item($this->input->post('item_id'));
        // var_dump($item_details);
        // var_dump('hi');
        // die;

        $output ='
        <table class="table table-striped" style = "border:0;">
            <tbody>
                <tr style="text-align:center">
                    <td colspan="2"><img class="img_item" src="'.base_url("assets/img/items/").$item_details->item_pic.'" style="width: 250px; height: 150px; object-fit:contain;">
                    </td>  
                </tr>
                <tr>
                    <th scope="row">Item No.</th>
                    <td>'.$item_details->item_id.'</td>
                </tr>
                <tr>
                    <th scope="row">Subcategory</th>
                    <td><div class="badge badge-info text-wrap">'.$item_details->item_subcategory_name.'</div></td>
                </tr>
                <tr>
                    <th scope="row">Name</th>
                    <td>'.$item_details->item_name.'</td>
                </tr>
                <tr>
                    <th scope="row">Description</th>
                    <td style="white-space: pre-wrap; word-break: break-word; text-align: justify;">'.$item_details->item_description.'</td>
                </tr>
                <tr>
                    <th scope="row">Supplier</th>
                    <td>'.$item_details->item_supplier.'</td>
                </tr>
                <tr>
                    <th scope="row">Expiry Date</th>
                    <td>'.$item_details->item_expiry_date.'</td>
                </tr>
                <tr>
                    <th scope="row">Price Per Unit</th>
                    <td>RM'.$item_details->item_price.'</td>
                </tr>
                <tr>
                    <th scope="row">Quantity At Hand</th>
                    <td>'.$item_details->item_quantity.'</td>
                </tr>
                <tr>
                    <th scope="row">Restock Level</th>
                    <td><div class="badge badge-dark text-wrap" style="font-size: 1.0rem">'.$item_details->item_restock_level.'</div></td>
                </tr>
            </tbody>
        </table>
        
        ';

        echo $output;
    }

    function add_item()
    {
        // check if the user is not IT
		if ($this->session->userdata('user_role') != 'IT') {
			redirect('items/Items/');
		}

        $data['title'] = 'IntelliSalesBot | Add Item';
        $data['include_js'] = 'items_list';
        $data['item_data'] = $this->items_model->select_all_items();
        $data['item_subcategories_data'] = $this->items_model->select_all_item_subcategories();
        $data['item_categories_data'] = $this->items_model->select_all_item_categories();
        $data['selected'] = 'items';

		$this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('items/items_add_view');
        $this->load->view('internal_templates/footer');  
    }

    function fetch_subcategories()
    {
        echo $this->items_model->fetch_item_subcategories($this->input->post('item_category_id'));
    }

    function submit_added_item()
    {

        if($_FILES['item_pic']['name'] != "") {
			$item_pic= $this->upload_img('./assets/img/items', 'item_pic');
            $item_pic_data = $item_pic['file_name'];
		} else {
            $item_pic_data = htmlspecialchars($this->input->post('item_pic'));
        }

        $data=
		[
            'item_pic'=>htmlspecialchars($item_pic_data),
            'item_subcategory_id'=>htmlspecialchars($this->input->post('item_subcategory_id')),
            'item_supplier'=>htmlspecialchars($this->input->post('item_supplier')),
            'item_name'=>htmlspecialchars($this->input->post('item_name')),
            'item_expiry_date'=>htmlspecialchars($this->input->post('item_expiry_date')),
            'item_description'=>htmlspecialchars($this->input->post('item_description')),
            'item_price'=>htmlspecialchars($this->input->post('item_price')),
			'item_quantity'=>htmlspecialchars($this->input->post('item_quantity')),
			'item_restock_level'=>htmlspecialchars($this->input->post('item_restock_level'))
		];

        $this->items_model->insert_item($data);

        $this->session->set_flashdata('insert_message', 1); 
        $this->session->set_flashdata('item_name', $this->input->post('item_name')); 

        redirect('items/Items');
    }

    function edit_item($item_id)
    {
        // check if the user is not IT
		if ($this->session->userdata('user_role') != 'Admin') {
			redirect('items/Items/');
		}

        $data['title'] = 'IntelliSalesBot | Edit an Item';
        $data['include_js'] = 'items_list';
        $data['item_data'] = $this->items_model->select_item($item_id); 
        $data['item_subcategories_data'] = $this->items_model->select_all_item_subcategories();
        $data['item_categories_data'] = $this->items_model->select_all_item_categories();
        $data['selected'] = 'items';

        //var_dump($item_id);
        //die;
        // var_dump($this->items_model->select_item($item_id)); 
        // die;
		$this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('items/items_edit_view');
        $this->load->view('internal_templates/footer'); 
    }

    function submit_edited_item($item_id)
    {
        if($_FILES['item_pic']['name'] != "") {
            $original_details = $this->items_model->select_item($item_id);
            unlink('./assets/img/items/'.$original_details->item_pic);
			$item_pic = $this->upload_img('./assets/img/items', 'item_pic');
			$data = [
				'item_pic' => $item_pic['file_name'],
			];
			$this->items_model->update_item($data, $item_id);
		}

        $data=
		[
            'item_subcategory_id'=>htmlspecialchars($this->input->post('item_subcategory_id')),
            'item_supplier'=>htmlspecialchars($this->input->post('item_supplier')),
            'item_name'=>htmlspecialchars($this->input->post('item_name')),
            'item_expiry_date'=>htmlspecialchars($this->input->post('item_expiry_date')),
            'item_description'=>htmlspecialchars($this->input->post('item_description')),
            'item_price'=>htmlspecialchars($this->input->post('item_price')),
			'item_quantity'=>htmlspecialchars($this->input->post('item_quantity')),
            'item_restock_level'=>htmlspecialchars($this->input->post('item_restock_level')),
            'item_updated_date'=>date('y-m-d') // current date
		];
      
        $this->items_model->update_item($data, $item_id);

        $this->session->set_flashdata('edit_message', 1); 
        $this->session->set_flashdata('item_name', $this->input->post('item_name')); 

        redirect('items/Items');
    }

    function delete_item()
    {
        $item = $this->items_model->select_item($this->input->post('item_id'));
        unlink('./assets/img/items/'.$item->item_pic);
        $this->items_model->delete_item($this->input->post('item_id'));
    }

    function upload_img($path, $file_input_name) 
    {
        if ($_FILES){
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'jpeg|jpg|png';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload($file_input_name)) 
            {
                $this->session->set_flashdata('message','<div class="alert alert-danger" role="alert">
                The file format must be in "png, jpg or jpeg"</div>');
                redirect('items/Item');
            } else {
                $doc_data = $this->upload->data();
                return $doc_data;
            }
        }
    }

    // --------------------- ITEM CATEGORIES --------------------------// 
    function items_categories(){
        // check if the user is not IT
		if ($this->session->userdata('user_role') != 'IT') {
			redirect('items/Items/');
		}

        $data['title'] = 'IntelliSalesBot | Item Categories';
        $data['include_js'] = 'items_list';
        $data['selected'] = 'items_categories';

        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('items/items_categories_list_view');
        $this->load->view('internal_templates/footer');
    }

    function items_categories_list()
    {
        // Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

        $item_categories = $this->items_model->select_item_categories_grouping();
        
		$data = array();
		$base_url = base_url();

        foreach($item_categories as $item_category) {
            $item_subcategories_page_link = $base_url."items/Items/items_subcategories/".$item_category->item_category_id;

            $view = '<span><a type="button" href="'.$item_subcategories_page_link.'" class="btn icon-btn btn-xs btn-white waves-effect waves-light"><span class="fas fa-eye" style="color: black"></span></a></span>';
            $edit_opt = '<span class="px-1"><button type="button" onclick="edit_item_category('.$item_category->item_category_id.')" class="btn icon-btn btn-xs btn-white waves-effect waves-light edit_item_category" style="color: black" data-toggle="modal" data-id="'.$item_category->item_category_id.'" data-target="#edit_item_category"><span class="fas fa-pencil-alt"></span></button></span>';
            $delete = '<span><button type="button" onclick="delete_item_category('.$item_category->item_category_id.')" class="btn icon-btn btn-xs btn-white waves-effect waves-light delete" ><span class="fas fa-trash" style="color: black"></span></button></span>';
			$function = $view.$edit_opt.$delete;

			$data [] = [ 
				'',
				$item_category->item_category_name,
				$item_category->item_total_subcategories,
                $function
            ];

		}

        $output = array(
			"draw" => $draw,
			"recordsTotal" => count($item_categories),
			"recordsFiltered" =>count($item_categories),
			"data" => $data
		);

		echo json_encode($output);
		exit();
    }

    function add_item_category()
    {
        $data=
		[
            'item_category_name'=>htmlspecialchars($this->input->post('item_category_name'))
		];

        $this->items_model->insert_item_category($data);

        $this->session->set_flashdata('insert_message', 1); 
        $this->session->set_flashdata('item_category_name', $this->input->post('item_category_name')); 

        redirect('items/Items/items_categories');
    }

    function edit_item_category()
    {
        $item_category_details = $this->items_model->select_item_category($this->input->post('item_category_id'));

        $output ='
        <form method="post" action="'.base_url('items/Items/submit_edited_item_category/'.$item_category_details->item_category_id).'">
        <div class="form-row pt-2" style="background-color: #E1DFE2">
            <div class="form-group col-md-12 px-4 pr-5">
                <label for="item_category_name" class="font-weight-bold" style="color: black">Item Category Name</label>
                <input type="text" class="form-control" id="item_category_name" name="item_category_name" placeholder="Enter item category name" value="'.$item_category_details->item_category_name.'" required>
            </div>
        </div>
            
                    <div class="modal-footer">
                    
                    <button type="button" class="btn btn-secondary float-right ml-2" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn float-right" style="background-color: #FF545D; color: white;" >Submit<i class="fas fa-check pl-2"></i></button>

                    </div>
                    
                </tr>
            
        </form>
        ';

        echo $output;
    }

    function submit_edited_item_category($item_category_id)
    {
        $data=
		[
            'item_category_name'=>htmlspecialchars($this->input->post('item_category_name'))
		];
      
        $this->items_model->update_item_category($data, $item_category_id);

        $this->session->set_flashdata('edit_message', 1); 
        $this->session->set_flashdata('item_category_name', $this->input->post('item_category_name')); 

        redirect('items/Items/items_categories');
    }

    function delete_item_category()
    {
        $this->items_model->delete_item_category($this->input->post('item_category_id'));
    }

    // --------------------- ITEM SUBCATEGORIES --------------------------// 
    function items_subcategories($item_category_id){
        // check if the user is not IT
		if ($this->session->userdata('user_role') != 'IT') {
			redirect('items/Items/');
		}
        
        $data['title'] = 'IntelliSalesBot | Item Subcategories';
        $data['include_js'] = 'items_list';
        $data['item_category_data'] = $this->items_model->select_item_category($item_category_id);
        $data['selected'] = 'items_categories';
       
        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('items/items_subcategories_list_view');
        $this->load->view('internal_templates/footer');
    }
    
    function items_subcategories_list($item_category_id)
    {
        // Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

       // $item = $this->item_category_data;
        
        $item_subcategories = $this->items_model->select_item_subcategories_grouping($item_category_id);
        
		$data = array();
		$base_url = base_url();

        foreach($item_subcategories as $item_subcategory) {

            $item_page_link = $base_url."items/Items/items_in_subcategory/".$item_subcategory->item_subcategory_id;

            $view = '<span><a type="button" href="'.$item_page_link.'"class="btn icon-btn btn-xs btn-white waves-effect waves-light"><span class="fas fa-eye" style="color: black"></span></a></span>';
            $edit_opt = '<span class="px-1"><button type="button" onclick="edit_item_subcategory('.$item_subcategory->item_subcategory_id.')" class="btn icon-btn btn-xs btn-white waves-effect waves-light" data-toggle="modal" data-id="'.$item_subcategory->item_subcategory_id.'" data-target="#edit_item_subcategory"><span class="fas fa-pencil-alt" style="color: black"></span></button></span>';
            $delete = '<span><button type="button" onclick="delete_item_subcategory('.$item_subcategory->item_subcategory_id.')" class="btn icon-btn btn-xs btn-white waves-effect waves-light delete"><span class="fas fa-trash" style="color: black"></span></button></span>';
			$function = $view.$edit_opt.$delete;

			$data [] = [ 
				'',
				$item_subcategory->item_subcategory_name,
				$item_subcategory->total_items_in_subcategory,
                $function
            ];

		}

        $output = array(
			"draw" => $draw,
			"recordsTotal" => count($item_subcategories),
			"recordsFiltered" =>count($item_subcategories),
			"data" => $data
		);

		echo json_encode($output);
		exit();
    }

    function add_item_subcategory($item_category_id)
    {
        $data=
		[
            'item_category_id' => $item_category_id,
            'item_subcategory_name'=>htmlspecialchars($this->input->post('item_subcategory_name'))
		];

        $this->items_model->insert_item_subcategory($data);

        $this->session->set_flashdata('insert_message', 1); 
        $this->session->set_flashdata('item_subcategory_name', $this->input->post('item_subcategory_name')); 

        // $link = "items/Items/items_subcategories/'.$this->input->post('item_category_id').'";
        // redirect($link);

        $base_url = base_url();
        redirect($base_url."items/Items/items_subcategories/".$item_category_id);
    }

    function edit_item_subcategory()
    {
        $item_subcategory_details = $this->items_model->select_item_subcategory($this->input->post('item_subcategory_id'));

        $output ='
        <form method="post" action="'.base_url('items/Items/submit_edited_item_subcategory/'.$item_subcategory_details->item_subcategory_id).'">
        <div class="form-row pt-2" style="background-color: #E1DFE2">
            <div class="form-group col-md-12 px-4 pr-5">
                <label for="item_subcategory_name" class="font-weight-bold" style="color: black">Item Subcategory Name</label>
                <input type="text" class="form-control" id="item_subcategory_name" name="item_subcategory_name" placeholder="Enter item subcategory name" value="'.$item_subcategory_details->item_subcategory_name.'" required>
            </div>
        </div>
        <div class="modal-footer">      
            <button type="button" class="btn btn-secondary float-right ml-2" data-dismiss="modal">Close</button>
            <button type="submit" class="btn float-right" style="background-color: #FF545D; color: white;" >Submit<i class="fas fa-check pl-2"></i></button>
        </div>
                    
        </tr>
            
        </form>
        ';

        echo $output;
    }

    function submit_edited_item_subcategory($item_subcategory_id)
    {
        $data=
		[
            'item_subcategory_name'=>htmlspecialchars($this->input->post('item_subcategory_name'))
		];
      
        $this->items_model->update_item_subcategory($data, $item_subcategory_id);

        $this->session->set_flashdata('edit_message', 1);
        $this->session->set_flashdata('item_subcategory_name', $this->input->post('item_subcategory_name'));
        
        $item_subcategory_id = $this->items_model->select_item_subcategory($item_subcategory_id);

        $base_url = base_url();
        redirect($base_url."items/Items/items_subcategories/".$item_subcategory_id->item_category_id);
    }
    
    function delete_item_subcategory()
    {
        $this->items_model->delete_item_subcategory($this->input->post('item_subcategory_id'));
    }

    // --------------------- ITEMS IN A SPECIFIC SUBCATEGORY (IT ADMIN) --------------------------// 
    function items_in_subcategory($item_subcategory_id){
        // check if the user is not IT
		if ($this->session->userdata('user_role') != 'IT') {
			redirect('items/Items/');
		}

        $data['title'] = 'IntelliSalesBot | Items';
        $data['include_js'] = 'items_list';
        $data['item_subcategory_data'] = $this->items_model->select_item_subcategory($item_subcategory_id);
        $data['selected'] = 'items_categories';
      
        $this->load->view('internal_templates/header', $data);
        $this->load->view('internal_templates/sidenav');
        $this->load->view('internal_templates/topbar');
        $this->load->view('items/items_in_subcategory_list_view');
        $this->load->view('internal_templates/footer');
    }

    function items_in_subcategory_list($item_subcategory_id)
    {
        // Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
        
        $items_in_subcategory = $this->items_model->select_all_items_in_subcategory($item_subcategory_id);
    
		$data = array();
		$base_url = base_url();

        foreach($items_in_subcategory as $item) {

            $restock_level = '<div class="badge badge-dark text-wrap" style="font-size: 0.9rem">'.$item->item_restock_level.'</div>';

			$data [] = [ 
				'',
				$item->item_name,
                $item->item_quantity,
                $restock_level
            ];

		}

        $output = array(
			"draw" => $draw,
			"recordsTotal" => count($items_in_subcategory),
			"recordsFiltered" =>count($items_in_subcategory),
			"data" => $data
		);

		echo json_encode($output);
		exit();
    }

    // --------------------- ITEMS IN A SPECIFIC CATEGORY (EMPLOYEE) --------------------------// 
    function items_categories_log(){
        // check if the user is not Employee
		if ($this->session->userdata('user_role') != 'Employee') {
			redirect('items/Items/');
		}

        $data['title'] = 'IntelliSalesBot | Items by Category';
        $data['include_js'] = 'items_list';
        $data['items_categories_data'] = $this->items_model->select_all_item_categories();
        $data['selected'] = 'items';

        $this->load->view('external_templates/header', $data);
        $this->load->view('external_templates/topnav');
        $this->load->view('items/items_categories_log_view');
        $this->load->view('external_templates/footer');
    }

    function items_in_category($item_category_id){
        // check if the user is not Employee
		if ($this->session->userdata('user_role') != 'Employee') {
			redirect('items/Items/');
		}
        $data['title'] = 'IntelliSalesBot | Items';
        $data['include_js'] = 'items_list';
        $data['items_data'] = $this->items_model->select_all_items_in_category($item_category_id);
        $data['items_category_data'] = $this->items_model->select_item_category($item_category_id);
        $data['selected'] = 'items';
       
        //var_dump( $this->items_model->select_item_category());
        //die;
        $this->load->view('internal_templates/header', $data);
        $this->load->view('external_templates/topnav');
        $this->load->view('items/items_in_category_list_view');
        $this->load->view('internal_templates/footer');
    }

    function items_in_category_list($item_category_id){
        // Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
        
        $items_in_category = $this->items_model->select_all_items_in_category($item_category_id);
        
		$data = array();
		$base_url = base_url();

        foreach($items_in_category as $item) {

            $item_pic = '<img class="img_item" src="'.base_url("assets/img/items/").$item->item_pic.'" style="width: 150px; height: 150px; object-fit:contain;">';
            $view = '<span><button type="button" onclick="view_item('.$item->item_id.')" class="btn icon-btn btn-xs btn-white waves-effect waves-light" style="color: black" data-toggle="modal" data-target="#view_item"><span class="fas fa-eye"></span></button></span>';
            $restock_level = '<div class="badge badge-dark text-wrap" style="font-size: 0.9rem">'.$item->item_restock_level.'</div>';

			$data [] = [ 
				'',
                $item_pic,
                $item->item_subcategory_name,
				$item->item_name,
                $item->item_quantity,
                $restock_level,
                $view
            ];

		}

        $output = array(
			"draw" => $draw,
			"recordsTotal" => count($items_in_category),
			"recordsFiltered" =>count($items_in_category),
			"data" => $data
		);

		echo json_encode($output);
		exit();
    }

     // --------------------- ITEMS LOW ON STOCK (EMPLOYEE) --------------------------// 
     function items_low_on_stock(){
       // check if the user is not Manager and not Employee
		if ($this->session->userdata('user_role') != 'Admin' && $this->session->userdata('user_role') != 'Employee') {
			redirect('items/Items/');
		}
        $data['title'] = 'IntelliSalesBot | Items Running Low on Stock';
        $data['include_js'] = 'items_list';
        $data['items_data'] = $this->items_model->select_all_items_low_on_stock();
        $data['selected'] = 'stock';

        // manager and employee are sharing the same low on stock page. 
        // if user is manager, sidenav is shown. for employee, topnav.
        if (($this->session->userdata('user_role') == 'Admin' )) {
            $this->load->view('internal_templates/header', $data);
            $this->load->view('internal_templates/sidenav');
            $this->load->view('internal_templates/topbar');
            $this->load->view('items/items_low_on_stock_view');
            $this->load->view('internal_templates/footer');
        } else {
            $this->load->view('internal_templates/header', $data);
            $this->load->view('external_templates/topnav');
            $this->load->view('items/items_low_on_stock_view');
            $this->load->view('external_templates/footer');
        }
    }

    function items_low_on_stock_list(){
        // Datatables Variables
		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
        
        $items_low_on_stock = $this->items_model->select_all_items_low_on_stock();
        
		$data = array();
		$base_url = base_url();

        foreach($items_low_on_stock as $item) {

            $item_pic = '<img class="img_item" src="'.base_url("assets/img/items/").$item->item_pic.'" style="width: 150px; height: 150px; object-fit:contain;">';
            $item_quantity = '<div class="badge badge-danger text-wrap" style="font-size: 1.0rem">'.$item->item_quantity.'</div>';
            $restock_level = '<div class="badge badge-dark text-wrap" style="font-size: 1.0rem">'.$item->item_restock_level.'</div>';

			$data [] = [ 
				'',
                $item_pic,
                $item->item_subcategory_name,
				$item->item_name,
                $item_quantity,
                $restock_level,
            ];

		}

        $output = array(
			"draw" => $draw,
			"recordsTotal" => count($items_low_on_stock),
			"recordsFiltered" =>count($items_low_on_stock),
			"data" => $data
		);

		echo json_encode($output);
		exit();
    }

    // -------------------REFERENCE (TO BE DELETED LATER)----------------------------
    
    // public function index()
    // {
    //     $data['title'] = "iJEES | R&D Projects";
    //     $data['include_js'] = 'rd_projects_list';
    //     $data['include_css'] = 'projects';
    //     // Get RDs that are approved and their details
    //     $data['rds'] = $this->rd_projects_model->approved_rdps();

    //     // Check if session is established. Get User ID from session.
    //     $user_id = $this->session->userdata('user_id');
    //     $data['user_role'] = $this->session->userdata('user_role');
    //     if ($data['user_role'] == 'Education Partner') {
    //         // From the User ID, get Education Partner ID  
    //         $ep_details = $this->user_ep_model->ep_details($user_id);
    //         $data['ep_id'] = $ep_details['ep_id'];

    //         $this->load->view('internal/templates/header', $data);
    //         $this->load->view('internal/templates/sidenav');
    //         $this->load->view('internal/templates/topbar');
    //         $this->load->view('external/rd_projects_view');
    //         $this->load->view('internal/templates/footer');
    //     } else {
    //         $data['ep_id'] = '';
    //         // var_dump($eps);
    //         // die;
    //         $this->load->view('external/templates/header', $data);
    //         $this->load->view('external/rd_projects_view', $data);
    //         $this->load->view('external/templates/footer', $data);
    //     }
    // }
}