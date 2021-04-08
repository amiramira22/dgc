<?php
Class Message_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;

	function __construct() {
parent::__construct();
		$this->dbprefix= $this->db->dbprefix;
		$this->db->query("SET SESSION sql_mode = 'TRADITIONAL'");	}

	function get_messages($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		
		$result = $this -> db -> get('messages');
		return $result -> result();
	}
	
	function get_messages_by_admin($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC',$admin_id) {
		$this -> db -> where('receiver_id', $admin_id);
		$this -> db ->or_where('sender_id', $admin_id);

		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}
        $this -> db -> order_by('created', 'DESC');
		$result = $this -> db -> get('messages');
		return $result -> result();
	}

	
	function get_new_models($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		
			
	$this -> db -> where('active', 0);

		$result = $this -> db -> get('new_models');
		return $result -> result();
	}
	
	function get_new_competitors($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		
			
	$this -> db -> where('active', 1);

		$result = $this -> db -> get('competitors_activities');
		return $result -> result();
	}


	function get_messages_by_admin_no_view($admin_id) {
		$this -> db -> where('admin_id', $admin_id);
		$this -> db -> where('active', 0);
		
       
		$result = $this -> db -> get('messages');
		return $result -> result();
	}


	function count_messages() {
		return $this -> db -> count_all_results('messages');
		
	}
	
		function count_no_viewed_messages($admin_id) {
			$this -> db -> where('active', 0);
			$this -> db -> where('admin_id', $admin_id);
		return $this -> db -> count_all_results('messages');
	}
	
	
		function count_competitor() {
			$this -> db -> where('active', 1);
		
		return $this -> db -> count_all_results('competitors_activities');
	}
	
	
	function count_new_models() {
			$this -> db -> where('active', 0);
		
		return $this -> db -> count_all_results('new_models');
	}
		
	function viewed($id) {
		$msg = array('id' => $id, 'active' => 1);
		$this -> save($msg);
	}

	function get_message($id) {

		$result = $this -> db -> get_where('messages', array('id' => $id));
		return $result -> row();
	}

	function save($message) {
		if ($message['id']) {
			$this -> db -> where('id', $message['id']);
			$this -> db -> update('messages', $message);
			return $message['id'];
		} else {
			$this -> db -> insert('messages', $message);
			return $this -> db -> insert_id();
		}
	}
	
		function delete($id)
	{
	
		
		//this deletes the channels record
		$this->db->where('id', $id);
		$this->db->delete('messages');
		
	
	}
	
	function get_message_name($channel_id) {
		
				return $this->db->get_where('messages', array('id'=>$message_id))->row()->name;
				
				
		
	}

}
