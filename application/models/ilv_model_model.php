<?php
Class Ilv_model_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	function get_all_ilv_models($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('ilv_models');
		return $result -> result();
	}

	function count_ilv_models() {
		return $this -> db -> count_all_results('ilv_models');
	}

	function count_anom_merch_models($visit_id, $av) {
		$this -> db -> where('visit_id', $visit_id);
		$this -> db -> where('av', $av);
		return $this -> db -> count_all_results('ilv_models');
	}

	function count_all_models($visit_id) {
		$this -> db -> where('visit_id', $visit_id);
		return $this -> db -> count_all_results('ilv_models');
	}

	function get_picture($visit_id, $model_id) {

		$result = $this -> db -> get_where('ilv_models', array('visit_id' => $visit_id, 'id' => $model_id));
		return $result -> row();
	}

	
	
	function get_ilv_models($visit_id) {

		$this -> db -> order_by('ilv_id', 'ASC');
		$result = $this -> db -> get_where('ilv_models', array('visit_id' => $visit_id));
		return $result -> result();
	}

	function is_exist($list_id, $visit_id) {
		$query = $this -> db -> get_where('ilv_models', array('checklist_id' => $list_id, 'visit_id' => $visit_id));
		$count = $query -> num_rows();
		if ($count == 0) {
			return false;
		} else {
			return true;
		}
	}


	function ilv_save_bulk($model) {
		if ($model['id']) {
			$this -> db -> where('id', $model['id']);
			$this -> db -> update('ilv_models', $model);
			return $model['id'];
		}

	}



	function save_ilv($model) {
		if ($model['id']) {
			$this -> db -> where('id', $model['id']);
			$this -> db -> update('ilv_models', $model);
			return $model['id'];
		} else {
			$this -> db -> insert('ilv_models', $model);
			return $this -> db -> insert_id();
		}
	}

	function deactivate($id) {
		$modern_model = array('id' => $id, 'active' => 0);
		$this -> save($modern_model);
	}

	function delete($id) {
		/*
		 deleting a modern_model will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//this deletes the modern_models record
		$this -> db -> where('id', $id);
		$this -> db -> delete('ilv_models');

	}



	function delete_by_visit($visit_id) {
		/*
		 deleting a ilv_model will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//this deletes the ilv_models record
		$this -> db -> where('visit_id', $visit_id);
		$this -> db -> delete('ilv_models');

	}

	
}
