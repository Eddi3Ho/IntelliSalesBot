<?php

class document_chatbot_model extends CI_Model
{
    private $chatbot_type;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->chatbot_type = 2; // Assign the value within the constructor method
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

    //document table functions
    function get_documents_detail()
    {
        return $this->db->get('document')->result();
    }

    function insert_document($data)
    {
        $this->db->insert('document', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    function select_one_doc($doc_id)
    {
        $this->db->select('*');
        $this->db->from('document');
        $this->db->where('doc_id', $doc_id);
        $query = $this->db->get()->row();
        return $query;
    }

    function delete_doc($doc_id, $doc_name)
    {
            $pdf_path = FCPATH . 'assets/files/'.$doc_name.'.pdf';            
            $thumbnail_path = FCPATH . 'assets/thumbnail/'.$doc_name.'.png';         
            $txt_path = FCPATH . 'assets/text_file/'.$doc_name.'.txt';         

            // Delete the file and its thumbnail
            if (unlink($pdf_path) && unlink($thumbnail_path) && unlink($txt_path)) {
                $this->db->where('doc_id', $doc_id)->delete('document');
                return true;
            }
        
        // Return false if the file was not deleted
        return false;
    }

}
