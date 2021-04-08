<?php
Class Role_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	function get_roles($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('roles');
		return $result -> result();
	}

	function count_roles() {
		return $this -> db -> count_all_results('roles');
	}

	function get_role($id) {

		$result = $this -> db -> get_where('roles', array('id' => $id));
		return $result -> row();
	}

	function save($role) {
		if ($role['id']) {
			$this -> db -> where('id', $role['id']);
			$this -> db -> update('roles', $role);
			return $role['id'];
		} else {
			$this -> db -> insert('roles', $role);
			return $this -> db -> insert_id();
		}
	}

	function deactivate($id) {
		$role = array('id' => $id, 'active' => 0);
		$this -> save_role($role);
	}

	function delete($id) {
		/*
		 deleting a role will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//this deletes the roles record
		$this -> db -> where('id', $id);
		$this -> db -> delete('roles');

	}

	function check_email($str, $id = false) {
		$this -> db -> select('email');
		$this -> db -> from('roles');
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



	public function get_role_name($role_id) {
		return $this->db->get_where('roles', array('id'=>$role_id))->row()->name;
	}

}
