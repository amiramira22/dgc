<?php
Class Daily_route_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	function get_daily_routes($limit = 0, $offset = 0, $order_by = 'code', $direction = 'ASC') {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('daily_routes');
		return $result -> result();
	}



	function get_daily_routes_by_admin($admin_id) {
		$this -> db -> where('admin_id', $admin_id);
		$date=date('Y-m-d');
		$this -> db -> where('date', $date);
		

		$result = $this -> db -> get('daily_routes');
		return $result -> result();
	}

	function count_daily_routes() {
		return $this -> db -> count_all_results('daily_routes');
	}

	function get_daily_route($id) {

		$result = $this -> db -> get_where('daily_routes', array('id' => $id));
		return $result -> row();
	}
	/////get routes by admin/////////
	function get_routes_by_id($admin_id) {
		$this -> db -> where('admin_id', $admin_id);
		//$this -> db -> where('active', 1);
		$result = $this -> db -> get('daily_routes');
		return $result -> result();
	}
	/////end get routes by admin/////////
    /////get routes by admin/////////
	function get_routes_by_admin_id($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC',$admin_id) {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}
		
		$this -> db -> where('admin_id', $admin_id);
		//$this -> db -> where('active', 1);
		$result = $this -> db -> get('daily_routes');
		return $result -> result();
	}
	/////end get routes by admin/////////
	/////count routes by admin/////////
	function count_routes_by_id($admin_id) {
		$this -> db -> where('admin_id', $admin_id);
		//$this -> db -> where('active', 1);
	return $this -> db -> count_all_results('daily_routes');

	}
	/////end count routes by admin/////////
	
	function save($daily_route) {
		if ($daily_route['id']) {
			$this -> db -> where('id', $daily_route['id']);
			$this -> db -> update('daily_routes', $daily_route);
			return $daily_route['id'];
		} else {
			$this -> db -> insert('daily_routes', $daily_route);
			return $this -> db -> insert_id();
		}
	}

	
	
	function save2($daily_route) {
	
			$this -> db -> insert('daily_routes', $daily_route);
			return $this -> db -> insert_id();
	
	}
	function deactivate($id) {
		$daily_route = array('id' => $id, 'active' => 0);
		$this -> save_daily_route($daily_route);
	}

	function delete($id) {
	
		$this -> db -> where('id', $id);
		$this -> db -> delete('daily_routes');

	}

	function check_email($str, $id = false) {
		$this -> db -> select('email');
		$this -> db -> from('daily_routes');
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



	public function get_daily_route_name($daily_route_id) {
		return $this->db->get_where('daily_routes', array('id'=>$daily_route_id))->row()->name;
	}

}
