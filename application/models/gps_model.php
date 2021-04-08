<?php
Class Gps_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	function get_gpss($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('gps');
		return $result -> result();
	}
	
	

	function count_gps() {
		return $this -> db -> count_all_results('gps');
	}

	function get_gps($id) {

		$result = $this -> db -> get_where('gps', array('id' => $id));
		return $result -> row();
	}

	function save($gps) {
		if ($gps['id']) {
			$this -> db -> where('id', $gps['id']);
			$this -> db -> update('gps', $gps);
			return $gps['id'];
		} else {
			$this -> db -> insert('gps', $gps);
			return $this -> db -> insert_id();
		}
	}

	function deactivate($id) {
		$gps = array('id' => $id, 'active' => 0);
		$this -> save_gps($gps);
	}

	function delete($id) {
		
		$this -> db -> where('id', $id);
		$this -> db -> delete('gps');

	}

	

}
