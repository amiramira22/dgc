<?php

//bcs
Class Upload_model extends CI_Model {

    //this is the expiration for a non-remember session
    var $session_expire = 7200;

    function __construct() {
        parent::__construct();
    }

    /*     * ******************************************************************

     * ****************************************************************** */

    function get_file($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
        $this->db->select('*');
        $this->db->from('downloads');
//        $this->db->where('active', 1);
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $result = $this->db->get();
        return $result->result();
    }

    function save($file) {
        if ($file['id']) {
            $this->db->where('id', $file['id']);
            $this->db->update('downloads', $file);
            return $file['id'];
        } else {
            $up['active'] = 0;
            $this->db->update('downloads', $up);
            $this->db->insert('downloads', $file);
            return $this->db->insert_id();
        }
    }

    function delete($id) {
        /*
          deleting a product will remove all their orders from the system
          this will alter any report numbers that reflect total sales
          deleting a customer is not recommended, deactivation is preferred
         */

        //this deletes the products record
        $this->db->where('id', $id);
        $this->db->delete('downloads');
    }

}
