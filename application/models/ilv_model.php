<?php
Class Ilv_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;

	function __construct() {
		parent::__construct();
	}

	function get_ilvs($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);

		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}
	
	function get_ilvs_view() {
		$this -> db -> select('ilvs.visit_id as visit_id,ilvs.code as ilv_code,ilvs.name as ilv_name,ilv_types.name as ilv_type_name,location_zones.name as zone_name,admin.firstname as first,
		admin.lastname as last,developers.name as developer_name,location_states.name as state_name');
		
		
		$this -> db -> join('admin', 'admin.id=ilvs.admin_id');
		$this -> db -> join('developers', 'developers.id=ilvs.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=ilvs.zone_id');
		$this -> db -> join('ilv_types', 'ilv_types.id=ilvs.ilv_type_id');
		$this -> db -> join('location_states', 'location_states.id=ilvs.state_id');
		
		$this -> db -> order_by('ilv_code', 'DESC');
		

		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}
	
	function get_ilvs_view_by_zone($zone_id) {
		$this -> db -> select('ilvs.visit_id as visit_id,ilvs.code as ilv_code,ilvs.name as ilv_name,ilv_types.name as ilv_type_name,location_zones.name as zone_name,admin.firstname as first,
		admin.lastname as last,developers.name as developer_name,location_states.name as state_name');
		
		
		$this -> db -> join('admin', 'admin.id=ilvs.admin_id');
		$this -> db -> join('developers', 'developers.id=ilvs.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=ilvs.zone_id');
		$this -> db -> join('ilv_types', 'ilv_types.id=ilvs.ilv_type_id');
		$this -> db -> join('location_states', 'location_states.id=ilvs.state_id');
		
		$this -> db -> order_by('ilv_code', 'DESC');
		$this -> db -> where('ilvs.zone_id', $zone_id);
		

		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}
	
	function get_ilvs_view_by_zone_pdv($zone_id,$pdv_id) {
		$this -> db -> select('ilvs.visit_id as visit_id,ilvs.code as ilv_code,ilvs.name as ilv_name,ilv_types.name as ilv_type_name,location_zones.name as zone_name,admin.firstname as first,
		admin.lastname as last,developers.name as developer_name,location_states.name as state_name');
		
		
		$this -> db -> join('admin', 'admin.id=ilvs.admin_id');
		$this -> db -> join('developers', 'developers.id=ilvs.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=ilvs.zone_id');
		$this -> db -> join('ilv_types', 'ilv_types.id=ilvs.ilv_type_id');
		$this -> db -> join('location_states', 'location_states.id=ilvs.state_id');
		
		$this -> db -> order_by('ilv_code', 'DESC');
		$this -> db -> where('ilvs.zone_id', $zone_id);
		$this -> db -> where('ilvs.ilv_type_id', $pdv_id);
		

		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}
	
	
	function get_ilvs_view_by_state_pdv($state_id,$pdv_id) {
		$this -> db -> select('ilvs.visit_id as visit_id,ilvs.code as ilv_code,ilvs.name as ilv_name,ilv_types.name as ilv_type_name,location_zones.name as zone_name,admin.firstname as first,
		admin.lastname as last,developers.name as developer_name,location_states.name as state_name');
		
		
		$this -> db -> join('admin', 'admin.id=ilvs.admin_id');
		$this -> db -> join('developers', 'developers.id=ilvs.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=ilvs.zone_id');
		$this -> db -> join('ilv_types', 'ilv_types.id=ilvs.ilv_type_id');
		$this -> db -> join('location_states', 'location_states.id=ilvs.state_id');
		
		$this -> db -> order_by('ilv_code', 'DESC');
		$this -> db -> where('ilvs.state_id', $state_id);
		$this -> db -> where('ilvs.ilv_type_id', $pdv_id);
		

		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}
	
	function get_ilvs_view_by_state($state_id) {
		$this -> db -> select('ilvs.visit_id as visit_id,ilvs.code as ilv_code,ilvs.name as ilv_name,ilv_types.name as ilv_type_name,location_zones.name as zone_name,admin.firstname as first,
		admin.lastname as last,developers.name as developer_name,location_states.name as state_name');
		
		
		$this -> db -> join('admin', 'admin.id=ilvs.admin_id');
		$this -> db -> join('developers', 'developers.id=ilvs.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=ilvs.zone_id');
		$this -> db -> join('ilv_types', 'ilv_types.id=ilvs.ilv_type_id');
		$this -> db -> join('location_states', 'location_states.id=ilvs.state_id');
		
		$this -> db -> order_by('ilv_code', 'DESC');
		$this -> db -> where('ilvs.state_id', $state_id);
		

		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}
	
	function get_ilvs_view_by_pdv($pdv_id) {
		$this -> db -> select('ilvs.visit_id as visit_id,ilvs.code as ilv_code,ilvs.name as ilv_name,ilv_types.name as ilv_type_name,location_zones.name as zone_name,admin.firstname as first,
		admin.lastname as last,developers.name as developer_name,location_states.name as state_name');
		
		
		$this -> db -> join('admin', 'admin.id=ilvs.admin_id');
		$this -> db -> join('developers', 'developers.id=ilvs.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=ilvs.zone_id');
		$this -> db -> join('ilv_types', 'ilv_types.id=ilvs.ilv_type_id');
		$this -> db -> join('location_states', 'location_states.id=ilvs.state_id');
		
		$this -> db -> order_by('ilv_code', 'DESC');
		$this -> db -> where('ilvs.ilv_type_id', $pdv_id);
		

		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}
	
	function get_ilvs_view_by_pdv_comb() {
		$this -> db -> select('ilvs.visit_id as visit_id,ilvs.code as ilv_code,ilvs.name as ilv_name,ilv_types.name as ilv_type_name,location_zones.name as zone_name,admin.firstname as first,
		admin.lastname as last,developers.name as developer_name,location_states.name as state_name');
		
		
		$this -> db -> join('admin', 'admin.id=ilvs.admin_id');
		$this -> db -> join('developers', 'developers.id=ilvs.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=ilvs.zone_id');
		$this -> db -> join('ilv_types', 'ilv_types.id=ilvs.ilv_type_id');
		$this -> db -> join('location_states', 'location_states.id=ilvs.state_id');
		
		$this -> db -> order_by('ilv_code', 'DESC');
		$this->db->where("(
		or_ilvs.ilv_type_id ='1'
		OR or_ilvs.ilv_type_id ='2' 
		)");
		

		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}
	
	function get_ilvs_view_by_zone_pdv_comb($zone_id) {
		$this -> db -> select('ilvs.visit_id as visit_id,ilvs.code as ilv_code,ilvs.name as ilv_name,ilv_types.name as ilv_type_name,location_zones.name as zone_name,admin.firstname as first,
		admin.lastname as last,developers.name as developer_name,location_states.name as state_name');
		
		
		$this -> db -> join('admin', 'admin.id=ilvs.admin_id');
		$this -> db -> join('developers', 'developers.id=ilvs.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=ilvs.zone_id');
		$this -> db -> join('ilv_types', 'ilv_types.id=ilvs.ilv_type_id');
		$this -> db -> join('location_states', 'location_states.id=ilvs.state_id');
		
		
		$this -> db -> where('ilvs.zone_id', $zone_id);
		$this->db->where("(
		or_ilvs.ilv_type_id ='1'
		OR or_ilvs.ilv_type_id ='2' 
		)");
		
       $this -> db -> order_by('ilv_code', 'DESC');
		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}
	
	function get_ilvs_view_by_state_pdv_comb($state_id) {
		$this -> db -> select('ilvs.visit_id as visit_id,ilvs.code as ilv_code,ilvs.name as ilv_name,ilv_types.name as ilv_type_name,location_zones.name as zone_name,admin.firstname as first,
		admin.lastname as last,developers.name as developer_name,location_states.name as state_name');
		
		
		$this -> db -> join('admin', 'admin.id=ilvs.admin_id');
		$this -> db -> join('developers', 'developers.id=ilvs.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=ilvs.zone_id');
		$this -> db -> join('ilv_types', 'ilv_types.id=ilvs.ilv_type_id');
		$this -> db -> join('location_states', 'location_states.id=ilvs.state_id');
		
		
		$this -> db -> where('ilvs.state_id', $state_id);
		$this->db->where("(
		or_ilvs.ilv_type_id ='1'
		OR or_ilvs.ilv_type_id ='2' 
		)");
		
       $this -> db -> order_by('ilv_code', 'DESC');
		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}
	
	
		function get_ilvs_view_by_search($search) {
		$this -> db -> select('ilvs.visit_id as visit_id,ilvs.code as ilv_code,ilvs.name as ilv_name,ilv_types.name as ilv_type_name,location_zones.name as zone_name,admin.firstname as first,
		admin.lastname as last,developers.name as developer_name,location_states.name as state_name');
		
		
		$this -> db -> join('admin', 'admin.id=ilvs.admin_id');
		$this -> db -> join('developers', 'developers.id=ilvs.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=ilvs.zone_id');
		$this -> db -> join('ilv_types', 'ilv_types.id=ilvs.ilv_type_id');
		$this -> db -> join('location_states', 'location_states.id=ilvs.state_id');
		
		
		//$this -> db -> where('ilvs.ilv_type_id', $pdv_id);
		$this->db->where("(
		or_ilvs.name LIKE '%".$search."%'
		OR or_admin.firstname LIKE '%".$search."%' 
		OR or_admin.lastname LIKE '%".$search."%'
		OR or_location_zones.name LIKE '%".$search."%'
		OR or_location_states.name LIKE '%".$search."%'
		OR or_ilv_types.name LIKE '%".$search."%'
		OR or_developers.name LIKE '%".$search."%'
		)");
		$this -> db -> order_by('ilv_code', 'DESC');

		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}
	

	function get_active_ilvs($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		$this -> db -> where('active', 1);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}

	function get_ilvs_by_id($admin_id) {
		$this -> db -> where('admin_id', $admin_id);
		$this -> db -> where('active', 1);
		$result = $this -> db -> get('ilvs');
		return $result -> result();
	}

	function count_ilvs() {
		return $this -> db -> count_all_results('ilvs');
	}

	function get_ilv($id) {

		$result = $this -> db -> get_where('ilvs', array('id' => $id));
		return $result -> row();
	}

	function get_ilvs_by_chef($chef_id) {

		$this -> db -> select('ilvs.*');
		$this -> db -> from('ilvs');
		$this -> db -> join('channels', 'ilvs.channel_id = channels.id');
		$this -> db -> join('chefs', 'channels.chef_id = chefs.id');
		$this -> db -> where('chefs.id', $chef_id);
		$this -> db -> where('ilvs.active', 1);

		$query = $this -> db -> get();

		return $query -> result();
	}

	function get_ilvs_by_chef_zone($chef_id, $zone_id) {
		$this -> db -> select('ilvs.name,ilvs.id');
		$this -> db -> from('ilvs');
		$this -> db -> join('channels', 'ilvs.channel_id = channels.id');
		$this -> db -> join('chefs', 'channels.chef_id = chefs.id');
		$this -> db -> where('ilvs.zone_id', $zone_id);
		$this -> db -> where('chefs.id', $chef_id);
		$this -> db -> where('ilvs.active', 1);
		//$this -> db -> order_by('ilvs.id', 'DESC');
		return $this -> db -> get();
	}

	function get_ilvs_by_zone_area($zone_id) {
		$this -> db -> select('ilvs.name,ilvs.id');
		$this -> db -> from('ilvs');
		$this -> db -> where('ilvs.zone_id', $zone_id);
		
		//$this -> db -> order_by('ilvs.id', 'DESC');
		return $this -> db -> get();
	}
	
	function get_ilvs_by_state($state_id) {
		$this -> db -> select('ilvs.name,ilvs.id');
		$this -> db -> from('ilvs');
		$this -> db -> where('ilvs.state_id', $state_id);
		
		//$this -> db -> order_by('ilvs.id', 'DESC');
		return $this -> db -> get();
	}

function get_ilvs_by_pdv($pdv_id) {
		$this -> db -> select('ilvs.name,ilvs.id');
		$this -> db -> from('ilvs');
		$this -> db -> where('ilvs.ilv_type_id', $pdv_id);
		
		//$this -> db -> order_by('ilvs.id', 'DESC');
		return $this -> db -> get();
	}

	function get_ilvs_by_zone($zone_id) {

		$this -> db -> select('ilvs.*');
		$this -> db -> from('ilvs');
		$this -> db -> where('ilvs.zone_id', $zone_id);
		$this -> db -> where('ilvs.active', 1);

		$query = $this -> db -> get();

		return $query -> result();
	}

	function save($ilv) {
		if ($ilv['id']) {
			$this -> db -> where('id', $ilv['id']);
			$this -> db -> update('ilvs', $ilv);
			return $ilv['id'];
		} else {
			$this -> db -> insert('ilvs', $ilv);
			return $this -> db -> insert_id();
		}
	}

	function delete($id) {

		//this deletes the ilvs record
		$this -> db -> where('id', $id);
		$this -> db -> delete('ilvs');

	}

	public function get_ilv_name($ilv_id) {
		return $this -> db -> get_where('ilvs', array('id' => $ilv_id)) -> row() -> name;
	}

	public function get_ilv_name2($ilv_id) {
		return $this -> db -> get_where('ilv_models', array('id' => $ilv_id)) -> row() -> name;
	}

	public function get_ilv_image($ilv_id) {
		return $this -> db -> get_where('ilvs', array('id' => $ilv_id)) -> row() -> image;
	}

	public function get_ilv_type($ilv_id) {
		return $this -> db -> get_where('ilvs', array('id' => $ilv_id)) -> row() -> type;
	}

	

	public function get_ilv_type2($ilv_id) {
		return $this -> db -> get_where('ilv_models', array('id' => $ilv_id)) -> row() -> type;
	}

	public function get_state_id($ilv_id) {
		return $this -> db -> get_where('ilvs', array('id' => $ilv_id)) -> row() -> state_id;
	}

	public function get_zone_id($ilv_id) {
		return $this -> db -> get_where('ilvs', array('id' => $ilv_id)) -> row() -> zone_id;
	}
	
	public function get_visit_id($ilv_id) {
		return $this -> db -> get_where('ilvs', array('id' => $ilv_id)) -> row() -> visit_id;
	}

}
