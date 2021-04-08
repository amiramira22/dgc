<?php
Class Brand_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	function get_brands($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('brands');
		return $result -> result();
	}
	
	function get_selected_brands() {
		$this -> db -> order_by('id','ASC');
		
		$this->db->where('selected',1);

		$result = $this -> db -> get('brands');
		return $result -> result();
	}
	
	function get_brands_by_code() {
		$this -> db -> order_by('code', 'ASC');
		$result = $this -> db -> get('brands');
		return $result -> result();
	}

	function count_brands() {
		return $this -> db -> count_all_results('brands');
	}

	function get_brand($id) {

		$result = $this -> db -> get_where('brands', array('id' => $id));
		return $result -> row();
	}
        
        function get_brand_by_name($name) {

		$result = $this -> db -> get_where('brands', array('name' => $name));
		return $result -> row();
	}

	function save($brand) {
		if ($brand['id']) {
			$this -> db -> where('id', $brand['id']);
			$this -> db -> update('brands', $brand);
			return $brand['id'];
		} else {
			$this -> db -> insert('brands', $brand);
			return $this -> db -> insert_id();
		}
	}

	function deactivate($id) {
		$brand = array('id' => $id, 'active' => 0);
		$this -> save_brand($brand);
	}

	function delete($id) {
		/*
		 deleting a brand will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//this deletes the brands record
		$this -> db -> where('id', $id);
		$this -> db -> delete('brands');

	}




	public function get_brand_name($brand_id) {
            
		return $this->db->get_where('brands', array('id'=>$brand_id))->row()->name;
	}

}
