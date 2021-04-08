<?php
class Location_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	//locations
	function save_location($data) {
		if (!$data['id']) {
			$this -> db -> insert('locations', $data);
			return $this -> db -> insert_id();
		} else {
			$this -> db -> where('id', $data['id']);
			$this -> db -> update('locations', $data);
			return $data['id'];
		}
	}

	function delete_locations($zone_id) {
		$this -> db -> where('state_id', $zone_id) -> delete('locations');
	}

	function delete_location($id) {
		$this -> db -> where('id', $id);
		$this -> db -> delete('locations');
	}

	function get_locations($zone_id) {
		$this -> db -> where('state_id', $zone_id);
		return $this -> db -> get('locations') -> result();
	}

	function get_all_locations() {
		$this -> db -> select('*');
		$this -> db -> order_by('name', 'ASC');
		$result = $this -> db -> get('locations');
		$result = $result -> result();

		return $result;
	}

	function get_location($id) {
		$this -> db -> where('id', $id);
		return $this -> db -> get('locations') -> row();
	}

	//states
	function save_state($data) {
		if (!$data['id']) {
			$this -> db -> insert('location_states', $data);
			return $this -> db -> insert_id();
		} else {
			$this -> db -> where('id', $data['id']);
			$this -> db -> update('location_states', $data);
			return $data['id'];
		}
	}

	function delete_states($zone_id) {
		$this -> db -> where('zone_id', $zone_id) -> delete('location_states');
	}

	function delete_state($id) {
		$this -> delete_locations($id);

		$this -> db -> where('id', $id);
		$this -> db -> delete('location_states');
	}

	function get_states($zone_id) {
		$this -> db -> where('zone_id', $zone_id);
		return $this -> db -> get('location_states') -> result();
	}

	function get_states_by_zone($zone_id) {
		$this -> db -> where('zone_id', $zone_id);
		return $this -> db -> get('location_states');
	}

	function get_all_states() {

		return $this -> db -> order_by('name', 'ASC') -> get('location_states') -> result();
	}

	function get_state($id) {
		$this -> db -> where('id', $id);
		return $this -> db -> get('location_states') -> row();
	}

	//zones
	function save_zone($data) {
		if (!$data['id']) {
			$this -> db -> insert('location_zones', $data);
			return $this -> db -> insert_id();
		} else {
			$this -> db -> where('id', $data['id']);
			$this -> db -> update('location_zones', $data);
			return $data['id'];
		}
	}

	function organize_zones($zones) {
		//now loop through the products we have and add them in
		$sequence = 0;
		foreach ($zones as $zone) {
			$this -> db -> where('id', $zone) -> update('location_zones', array('sequence' => $sequence));
			$sequence++;
		}
	}

	function get_zones() {
		$this -> db -> where('status', 0);
		return $this -> db -> order_by('sequence', 'ASC') -> get('location_zones') -> result();
	}
function get_zones2($n) {
		$this -> db -> where('status', $n);
		return $this -> db -> order_by('sequence', 'ASC') -> get('location_zones') -> result();
	}
	function get_zone_by_state_id($id) {
		$state = $this -> get_state($id);
		return $this -> get_zone($state -> zone_id);
	}

	function get_zone($id) {
		$this -> db -> where('id', $id);
		return $this -> db -> get('location_zones') -> row();
	}

	function delete_zone($id) {
		$this -> db -> where('id', $id);
		$this -> db -> delete('location_zones');
	}

	function get_zones_menu() {
		$zones = $this -> db -> order_by('sequence', 'ASC') -> where('status', 1) -> get('location_zones') -> result();
		$return = array();
		foreach ($zones as $z) {
			$return[$z -> id] = $z -> name;
		}
		return $return;
	}

	function get_states_menu($zone_id) {
		$states = $this -> db -> where(array('status' => 1, 'zone_id' => $zone_id)) -> get('location_states') -> result();
		$return = array();
		foreach ($states as $s) {
			$return[$s -> id] = $s -> name;
		}
		return $return;
	}

	function get_zone_name($zone_id) {

		return $this -> db -> get_where('location_zones', array('id' => $zone_id)) -> row() -> name;

	}

	function get_state_name($state_id) {

		return $this -> db -> get_where('location_states', array('id' => $state_id)) -> row() -> name;

	}

	function get_states_by_zone_area($zone_id) {
		$this -> db -> select('location_states.name,location_states.id');
		$this -> db -> from('location_states');
		$this -> db -> where('location_states.zone_id', $zone_id);
		//$this -> db -> where('location_states.active', 1);
		//$this -> db -> order_by('outlets.id', 'DESC');
		return $this -> db -> get();
	}

}
