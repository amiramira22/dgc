<?php
Class City_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	function get_cities($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('cities');
		return $result -> result();
	}

	function count_cities() {
		return $this -> db -> count_all_results('cities');
	}

	function get_city_by_id($id) {

		$result = $this -> db -> get_where('cities', array('id' => $id));
		return $result -> row();
	}

	function save($city) {
		if ($city['id']) {
			$this -> db -> where('id', $city['id']);
			$this -> db -> update('cities', $city);
			return $city['id'];
		} else {
			$this -> db -> insert('cities', $city);
			return $this -> db -> insert_id();
		}
	}

	

	function delete($id) {
		
		$this -> db -> where('id', $id);
		$this -> db -> delete('cities');

	}

	function get_cities_by_state($state_id) {
		$this -> db -> select('cities.name,cities.id');
		$this -> db -> from('cities');
		$this -> db -> where('cities.state_id', $state_id);
		return $this -> db -> get();
	}



	public function get_city_name($city_id) {
		
	   return $this->db->get_where('cities', array('id'=>$city_id))->row()->name;
			
		
	}

}
