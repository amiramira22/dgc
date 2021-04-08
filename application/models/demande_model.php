<?php
Class Demande_model extends CI_Model {

	//this is the expiration for a non-remember session
	

	function __construct() {
		parent::__construct();
	}


	function get_demandes($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('demande');
		return $result -> result();
	}

function save($demande) {
		if ($demande['id']) {
			$this -> db -> where('id', $demande['id']);
			$this -> db -> update('demande', $demande);
			return $demande['id'];
		} else {
			$this -> db -> insert('demande', $demande);
			return $this -> db -> insert_id();
		}
	}

	function delete($id) {

		//this deletes the channels record
		$this -> db -> where('id', $id);
		$this -> db -> delete('demande');

	}


function get_demande($id) {

		$result = $this -> db -> get_where('demande', array('id' => $id));
		return $result -> row();
	}


	function get_demande_by_users($id_user) {
$this -> db -> where('id_user', $id_user);
        $result = $this -> db -> get('demande');
		return $result -> result();
	}


	function get_admin($id) {

		$result = $this -> db -> get_where('admin', array('id' => $id));
		return $result -> row();
	}


}