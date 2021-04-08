<?php
Class Fuel_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;
	private $ad;

	function __construct() {
		parent::__construct();
		$this-> ad = $this->load->database('ad',TRUE);
	}

	/********************************************************************

	 ********************************************************************/

	function get_fuels($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> ad -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> ad -> limit($limit, $offset);
		}

		$result = $this -> ad -> get('fuels');
		return $result -> result();
	}
	
	function get_fuels_by_admin_id($admin_id, $project_id) {
		$this -> ad -> order_by('id', 'DESC');
		$this -> ad -> where('admin_id', $admin_id);
        $this -> ad -> where('project_id', $project_id);
		$result = $this -> ad -> get('fuels');
		return $result -> result();
	}

	function count_fuels() {
		return $this -> ad -> count_all_results('fuels');
	}

	function get_fuel($id) {

		$result = $this -> ad -> get_where('fuels', array('id' => $id));
		return $result -> row();
	}

	function save($fuel) {
		if ($fuel['id']) {
			$this -> ad -> where('id', $fuel['id']);
			$this -> ad -> update('fuels', $fuel);
			return $fuel['id'];
		} else {
			$this -> ad -> insert('fuels', $fuel);
			return $this -> ad -> insert_id();
		}
	}

	function deactivate($id) {
		$fuel = array('id' => $id, 'active' => 0);
		$this -> save($fuel);
	}

	function delete($id) {
		/*
		 deleting a fuel will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//this deletes the fuels record
		$this -> ad -> where('id', $id);
		$this -> ad -> delete('fuels');

	}

	


}
