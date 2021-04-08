<?php
Class List_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 10200;

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	function get_lists($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('lists');
		return $result -> result();
	}
	
	function get_active_lists() {
		$this -> db -> order_by('code', 'DESC');
		$this -> db -> where('active', 1);
		$result = $this -> db -> get('lists');
		return $result -> result();
	}
	
	function get_lists_by_outlet_type($outlet_type_id) {
		
		
		$result = $this -> db -> get_where('lists', array('outlet_type_id' => $outlet_type_id));
		return $result -> result();
	}

	function count_lists() {
		return $this -> db -> count_all_results('lists');
	}

	function get_list($id) {

		$result = $this -> db -> get_where('lists', array('id' => $id));
		return $result -> row();
	}

	function save($list) {
		if ($list['id']) {
			$this -> db -> where('id', $list['id']);
			$this -> db -> update('lists', $list);
			return $list['id'];
		} else {
			$this -> db -> insert('lists', $list);
			return $this -> db -> insert_id();
		}
	}

	function deactivate($id) {
		$list = array('id' => $id, 'active' => 0);
		$this -> save_list($list);
	}

	function delete($id) {
		/*
		 deleting a list will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//this deletes the lists record
		$this -> db -> where('id', $id);
		$this -> db -> delete('lists');

	}

	function is_active($checklist_id) {
		$query = $this -> db -> get_where('lists', array('id' => $checklist_id, 'active' => 1));
		$count = $query -> num_rows();
		if ($count == 0) {
			return false;
		} else
			return true;
	}



	public function get_list_name($list_id) {	
	return $this->db->get_where('lists', array('id'=>$list_id))->row()->name;
	
	}
    public function get_category_name($list_id) {	
	return $this->db->get_where('lists', array('id'=>$list_id))->row()->category;
	
	}
    public function get_sub_category_name($list_id) {	
	return $this->db->get_where('lists', array('id'=>$list_id))->row()->sub_category;
	
	}

}
