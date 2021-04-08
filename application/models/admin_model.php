<?php
Class Admin_model extends CI_Model
{
		function get_admin_name($admin_id) {
		
				
				$last= $this->db->get_where('admin', array('id'=>$admin_id))->row()->name;
				return $last;
				
		
	}
	
	//recuperer le code de user connecté
		function get_admin_code($admin_id) {
		
				$code= $this->db->get_where('admin', array('id'=>$admin_id))->row()->code;
				return $code;
	}

	//recuper le nom de user par code
	function get_admin_name_by_code($code) {
		
				$first= $this->db->get_where('admin', array('code'=>$code))->row()->firstname;
				$last= $this->db->get_where('admin', array('code'=>$code))->row()->lastname;
				return $first.' '.$last;
				
		
	}
	
		function get_admin_by_id($admin_id)
	{

		$this -> db -> where('id', $admin_id);
		$result = $this -> db -> get('admin');
		return $result -> row();
		
		return $result;
	}
		
		
		function get_admins($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('admin');
		return $result -> result();
	}
		
		public function getAll() {
        return $this->db->from('outlets')->get()->result_array();
    }


function count_fo() {
	$this -> db -> where('access', 'Field Officer');
		return $this -> db -> count_all_results('admin');
	}

		
		function get_active_sfo($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		$this -> db -> where('active', 1);
		$this -> db -> where('access', 'Field Officer');
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('admin');
		return $result -> result();
	}
		
		
		
}