<?php
Class User_model extends CI_Model
{
		
		
	public function getAll() {
        return $this->db->from('admin')->get()->result_array();
    }
	function get_users($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('admin');
		return $result -> result();
	}
	
	function count_users() {
		return $this -> db -> count_all_results('admin');
	}


		
		
}