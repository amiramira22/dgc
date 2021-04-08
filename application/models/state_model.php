<?php
Class State_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	function get_states() {
		
		$result = $this -> db -> get('states');
		return $result -> result();
	}

	function count_states() {
		return $this -> db -> count_all_results('states');
	}

	function get_state_by_id($id) {

		$result = $this -> db -> get_where('states', array('id' => $id));
		return $result -> row();
	}

	function save($state) {
		if ($state['id']) {
			$this -> db -> where('id', $state['id']);
			$this -> db -> update('states', $state);
			return $state['id'];
		} else {
			$this -> db -> insert('states', $state);
			return $this -> db -> insert_id();
		}
	}

	function deactivate($id) {
		$state = array('id' => $id, 'active' => 0);
		$this -> save_state($state);
	}

	function delete($id) {
	
		$this -> db -> where('id', $id);
		$this -> db -> delete('states');

	}

	function get_states_by_zone($zone_id) {
		$this -> db -> select('states.name,states.id');
		$this -> db -> from('states');
		$this -> db -> where('states.zone_id', $zone_id);
		return $this -> db -> get();
	}



	public function get_state_name($state_id) {
		
	return $this->db->get_where('states', array('id'=>$state_id))->row()->name;

				
		
	}

}
