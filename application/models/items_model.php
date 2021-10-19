<?php

class items_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function select_all_items()
    {
        return $this->db->get('items')->result();
    }

    function select_item($item_id)
    {
        $this->db->where('item_id',$item_id);
        return $this->db->get('items')->row();
    }

    function insert_item($data)
    {
        $this->db->insert('items', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function update_item($data, $item_id)
    {
        $this->db->where('item_id', $item_id);
        if ($this->db->update('items', $data)) {
            return true;
        } else {
            return false;
        }
    }

    function delete_item($item_id)
    {
        $this->db->where('item_id', $item_id);
        $this->db->delete('items');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // -------- ITEM CATEGORIES -------- //

    function select_all_item_categories()
    {
        return $this->db->get('items_category')->result();
    }

    function select_item_categories_grouping() // relating it to sub category table
    {
        //taking everything in items_category table even if there is no related record in items_subcategory table
        $this->db->select('items_category.item_category_id, item_category_name, COUNT(item_subcategory_id) AS item_total_subcategories')
        ->from('items_category')
        ->join('items_subcategory', 'items_subcategory.item_category_id = items_category.item_category_id', 'left')
        ->group_by('items_category.item_category_id');
        return $this->db->get()->result();
    }

    // function select_item_category_and_sub($item_category_id) // selecting item and its item categories though
    // {
    //     $this->db->select('')
    //     ->from('items_category')
    //     ->join('items_subcategory', 'items_subcategory.item_category_id = items_category.item_category_id', 'left')
    //     ->where('items_category.item_category_id', $item_category_id);
    //     return $this->db->get()->row();
    // }

    function select_item_category($item_category_id) // just selecting a CATEGORY. no relation with SUB
    {
        $this->db->where('item_category_id',$item_category_id);
        return $this->db->get('items_category')->row();
    }

    function insert_item_category($data)
    {
        $this->db->insert('items_category', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function update_item_category($data, $item_category_id)
    {
        $this->db->where('item_category_id', $item_category_id);
        if ($this->db->update('items_category', $data)) {
            return true;
        } else {
            return false;
        }
    }

    function delete_item_category($item_category_id) // deleting a category will delete its related sub categories as well
    {
        $this->db->where('item_category_id', $item_category_id);
        $this->db->delete('items_subcategory');

        $this->db->where('item_category_id', $item_category_id);
        $this->db->delete('items_category');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    // -------- ITEM SUBCATEGORIES -------- //
   
    function select_all_item_subcategories()
    {
        return $this->db->get('items_subcategory')->result();
    }

    function delete_item_subcategory($item_subcategory_id) 
    {
        $this->db->where('item_subcategory_id', $item_subcategory_id);
        $this->db->delete('items_subcategory');
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // function select_condition($condition)
    // {
    //     $this->db->where($condition);
    //     return $this->db->get('universities')->result();
    // }
}
