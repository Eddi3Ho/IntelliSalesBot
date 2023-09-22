<?php

class chatbot_model extends CI_Model
{
    private $chatbot_type;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->chatbot_type = 1; // Assign the value within the constructor method
    }

    public function get_global_variable() {
        return $this->chatbot_type;
    }

    function insert_chat($data)
    {
        $this->db->insert('chat_history', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    function insert_history($data)
    {
        $this->db->insert('conversation_history', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    function edit_conversation_name($con_id, $con_name)
    {
        $this->db->where('con_id', $con_id);
        $this->db->set('con_name', $con_name);
        $this->db->update('conversation_history');
    }

    function delete_conversation($con_id)
    {
        $this->db->where('con_id', $con_id);
        $this->db->delete('conversation_history');
    }

    function delete_chat($con_id)
    {
        $this->db->where('con_id', $con_id);
        $this->db->delete('chat_history');
    }

    function increase_no_of_message($con_id)
    {
        $this->db->set('no_of_message', 'no_of_message + 1', false);
        $this->db->where('con_id', $con_id);
        $this->db->update('conversation_history');

        return $this->db->affected_rows();
    }

    function select_conversation_history($user_id)
    {
        $this->db->select('*');
        $this->db->from('conversation_history');
        $this->db->where('user_id', $user_id);
        $this->db->where('chatbot_type', $this->get_global_variable()); //addedd
        $this->db->order_by('last_update', 'DESC');        
        $query = $this->db->get()->result();
        return $query;
    }

    function select_chat_history($con_id)
    {
        $this->db->select('*');
        $this->db->from('chat_history');
        $this->db->where('con_id', $con_id);
        $this->db->order_by('message_datetime', 'ASC');
        $query = $this->db->get()->result();
        return $query;
    }

    function update_last_update($con_id) {
        $currentDateTime = date('Y-m-d H:i:s'); 
        $this->db->set('last_update', $currentDateTime);
        $this->db->where('con_id', $con_id);
        $this->db->update('conversation_history');
    }

    function one_chat_row($chat_id)
    {
        $this->db->where('chat_id', $chat_id);
        return $this->db->get('chat_history')->row();
    }

    function get_latest_con_id($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('chatbot_type', $this->get_global_variable()); //addedd
        $this->db->order_by('last_update', 'DESC');        
        $this->db->limit(1);
        $query = $this->db->get('conversation_history');

        return $query->row();
    }

    function check_if_user_has_conversation($user_id)
    {
        $this->db->select('*');
        $this->db->from('conversation_history');
        $this->db->where('user_id', $user_id);
        $this->db->where('chatbot_type', $this->get_global_variable()); //addedd

        $query = $this->db->get();
        $rowCount = $query->num_rows();

        if ($rowCount > 0) {
            return true; // Rows exist
        } else {
            return false; // No rows
        }
    }

    //==================== Sales Report Functions =============================
    function select_weekly_sales_report($start_date, $end_date, $limit, $type, $focus)
    {
        $this->db->select('sales_item.item_id, items.item_name, items_subcategory.item_subcategory_name, SUM(sales_item.sale_item_quantity) AS item_total_quantity, SUM(sales_item.sale_item_total_price) AS item_total_sale');
        $this->db->from('sales_item');
        $this->db->join('sales', 'sales.sale_id = sales_item.sale_id');
        $this->db->join('items', 'items.item_id = sales_item.item_id');
        $this->db->join('items_subcategory', 'items_subcategory.item_subcategory_id = items.item_subcategory_id');
        $this->db->where('sales.sale_date >=', $start_date);
        $this->db->where('sales.sale_date <=', $end_date);
        if($type == 'item'){
            $this->db->group_by("sales_item.item_id");
        }elseif($type == 'category'){
            $this->db->group_by("items_subcategory.item_subcategory_id");
        }
        if($focus == 'unit'){
            $this->db->order_by('SUM(sales_item.sale_item_quantity)', 'DESC');
        }elseif($focus == 'price'){
            $this->db->order_by('SUM(sales_item.sale_item_total_price)', 'DESC');
        }
        $this->db->limit($limit);

        $query = $this->db->get()->result();
        return $query;
    }    

    function select_monthly_sales_report($month, $year, $limit, $type, $focus)
    {
        $start_date = $year . "-" . $month . "-01";
        $d = new DateTime($start_date);
        $end_date = $d->format('Y-m-t');

        $this->db->select('sales_item.item_id, items.item_name, items_subcategory.item_subcategory_name, SUM(sales_item.sale_item_quantity) AS item_total_quantity, SUM(sales_item.sale_item_total_price) AS item_total_sale');
        $this->db->from('sales_item');
        $this->db->join('sales', 'sales.sale_id = sales_item.sale_id');
        $this->db->join('items', 'items.item_id = sales_item.item_id');
        $this->db->join('items_subcategory', 'items_subcategory.item_subcategory_id = items.item_subcategory_id');
        $this->db->where('sales.sale_date >=', $start_date);
        $this->db->where('sales.sale_date <=', $end_date);
        $this->db->group_by("sales_item.item_id");
        if($type == 'item'){
            $this->db->group_by("sales_item.item_id");
        }elseif($type == 'category'){
            $this->db->group_by("items_subcategory.item_subcategory_id");
        }
        if($focus == 'unit'){
            $this->db->order_by('SUM(sales_item.sale_item_quantity)', 'DESC');
        }elseif($focus == 'price'){
            $this->db->order_by('SUM(sales_item.sale_item_total_price)', 'DESC');
        }
        $this->db->limit($limit);

        $query = $this->db->get()->result();
        return $query;
    }  


}
