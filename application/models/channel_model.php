<?php

Class Channel_model extends CI_Model {

    //this is the expiration for a non-remember session

    function __construct() {
        parent::__construct();
    }

    /*     * ******************************************************************

     * ****************************************************************** */

    function get_channels($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {

        $this->db->order_by('channels.id', 'ASC');
        //$this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get('channels');
        return $result->result();
    }
    
    function get_active_channels() {
        $this->db->where('active', 1);

        $result = $this->db->get('channels');
        return $result->result();
    }

    function count_channels() {
        return $this->db->count_all_results('channels');
    }

    function get_channel($id) {

        $result = $this->db->get_where('channels', array('id' => $id));
        return $result->row();
    }

    function save($channel) {
        if ($channel['id']) {
            $this->db->where('id', $channel['id']);
            $this->db->update('channels', $channel);
            return $channel['id'];
        } else {
            $this->db->insert('channels', $channel);
            return $this->db->insert_id();
        }
    }

    function delete($id) {
        //this deletes the channels record
        $this->db->where('id', $id);
        $this->db->delete('channels');
    }

    public function get_channel_name($channel_id) {
        return $this->db->get_where('channels', array('id' => $channel_id))->row()->name;
    }
    
      public function get_channel_by_id($channel_id) {
        return $this->db->get_where('channels', array('id' => $channel_id))->row();
    }

}
