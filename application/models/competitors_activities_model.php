<?php

Class Competitors_activities_model extends CI_Model {

    //this is the expiration for a non-remember session


    function __construct() {
        parent::__construct();
    }

    function get_competitors_activities($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get('competitors_activities');
        return $result->result();
    }

    function get_competitors_activities_by_admin($admin_id) {

        $this->db->where('admin_id', $admin_id);
        $result = $this->db->get('competitors_activities');
        return $result->result();
    }

    function save($competitors_activities) {
        
        if ($competitors_activities['id']) {
            $this->db->where('id', $competitors_activities['id']);
            $this->db->update('competitors_activities', $competitors_activities);
            return $competitors_activities['id'];
        } else {
            $this->db->insert('competitors_activities', $competitors_activities);
            return $this->db->insert_id();
        }
    }

    function delete($id) {

        //this deletes the channels record
        $this->db->where('id', $id);
        $this->db->delete('competitors_activities');
    }

    function get_competitors_activities_by_id($id) {

        $result = $this->db->get_where('competitors_activities', array('id' => $id));
        return $result->row();
    }

}
