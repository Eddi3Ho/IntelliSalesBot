<?php

class chat_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
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

    // -------- SALES  -------- //


    function insert_sales_item($data)
    {
        $this->db->insert('sales_item', $data);
    }

    function update_quantity_from_item($item_id, $item_quantity)
    {
        $this->db->where('item_id', $item_id);
        $this->db->set('item_quantity', 'item_quantity-' . $item_quantity . '', FALSE);
        $this->db->update('items');
    }

    function update_sale($data, $sale_id)
    {
        $this->db->where('sale_id', $sale_id);
        if ($this->db->update('sales', $data)) {
            return true;
        } else {
            return false;
        }
    }

    function return_quantity_to_item($item_id, $item_quantity)
    {
        $this->db->where('item_id', $item_id);
        $this->db->set('item_quantity', 'item_quantity+' . $item_quantity . '', FALSE);
        $this->db->update('items');
    }

    function delete_sales_item($sale_id)
    {
        $this->db->where('sale_id', $sale_id);
        $this->db->delete('sales_item');
    }

    function select_all_from_sales()
    {
        $this->db->select('*');
        $this->db->from('sales');
        $this->db->join('users', 'users.user_id = sales.user_id');
        $query = $this->db->get()->result();
        return $query;
    }

    function select_sales_item($sale_id)
    {
        $this->db->select('*');
        $this->db->from('sales_item');
        $this->db->where('sale_id', $sale_id);
        $this->db->join('items', 'items.item_id = sales_item.item_id');
        $this->db->join('items_subcategory', 'items_subcategory.item_subcategory_id = items.item_subcategory_id');
        $this->db->join('items_category', 'items_category.item_category_id  = items_subcategory.item_category_id');
        $query = $this->db->get()->result();
        return $query;
    }

    function select_one_sale($sale_id)
    {
        $this->db->select('*');
        $this->db->from('sales');
        $this->db->where('sale_id', $sale_id);
        $this->db->join('users', 'users.user_id = sales.user_id');
        $query = $this->db->get()->row();
        return $query;
    }
}
