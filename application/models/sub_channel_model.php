<?php

Class Sub_channel_model extends CI_Model {

    //this is the expiration for a non-remember session

    function __construct() {
        parent::__construct();
    }

    /*     * ******************************************************************

     * ****************************************************************** */

    function get_sub_channels($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {

        $this->db->order_by('sub_channels.id', 'ASC');
        //$this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get('sub_channels');
        return $result->result();
    }
    
    function get_active_sub_channels() {
        $this->db->where('active', 1);

        $result = $this->db->get('sub_channels');
        return $result->result();
    }

    function count_sub_channels() {
        return $this->db->count_all_results('sub_channels');
    }

    function get_sub_channel($id) {

        $result = $this->db->get_where('sub_channels', array('id' => $id));
        return $result->row();
    }

    function save($sub_channel) {
        if ($sub_channel['id']) {
            $this->db->where('id', $sub_channel['id']);
            $this->db->update('sub_channels', $sub_channel);
            return $sub_channel['id'];
        } else {
            $this->db->insert('sub_channels', $sub_channel);
            return $this->db->insert_id();
        }
    }

    function delete($id) {
        //this deletes the sub_channels record
        $this->db->where('id', $id);
        $this->db->delete('sub_channels');
    }

    public function get_sub_channel_name($sub_channel_id) {
        return $this->db->get_where('sub_channels', array('id' => $sub_channel_id))->row()->name;
    }
    
    public function get_sub_channel_by_id($sub_channel_id) {
        return $this->db->get_where('sub_channels', array('id' => $sub_channel_id))->row();
    }
    
    public function get_sub_channel_by_name($sub_channel_name) {
        return $this->db->get_where('sub_channels', array('name' => $sub_channel_name))->row();
    }

}
