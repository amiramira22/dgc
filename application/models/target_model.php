<?php
Class Target_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 10200;

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	function get_targets($limit = 0, $offset = 0, $order_by = 'targets.id', $direction = 'DESC') {
		$this -> db -> select('targets.*,outlets.name as outlet_name');
		 $this -> db -> join('outlets', 'outlets.id=targets.outlet_id');
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}
		$result = $this -> db -> get('targets');
	
		return $result -> result();
	}
	
	function get_target($id) {

		$result = $this -> db -> get_where('targets', array('id' => $id));
		return $result -> row();
	}
	
	function count_targets() {
		return $this -> db -> count_all_results('targets');
	}

	function get_target_by_id($id) {

		$result = $this -> db -> get_where('targets', array('id' => $id));
		return $result -> row();
	}

	function save($target) {
		if ($target['id']) {
			$this -> db -> where('id', $target['id']);
			$this -> db -> update('targets', $target);
			return $target['id'];
		} else {
			$this -> db -> insert('targets', $target);
			return $this -> db -> insert_id();
		}
	}



	function delete($id) {
	
		$this -> db -> where('id', $id);
		$this -> db -> delete('targets');

	}


	public function get_target_name($target_id) {
		return $this->db->get_where('targets', array('id'=>$target_id))->row()->name;
	}

	function check_email($str, $id = false) {
		$this -> db -> select('email');
		$this -> db -> from('targets');
		$this -> db -> where('email', $str);
		if ($id) {
			$this -> db -> where('id !=', $id);
		}
		$count = $this -> db -> count_all_results();

		if ($count > 0) {
			return true;
		} else {
			return false;
		}
	}


}
