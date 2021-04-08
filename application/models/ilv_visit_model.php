<?php
Class Ilv_visit_model extends CI_Model {

	//this is the expiration for a non-remember session

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

function get_ilv_visits2($limit = 0, $offset = 0, $order_by = '', $direction = 'DESC',$admin_id) {
		$this -> db -> select('ilv_visits.id as visit_id,ilv_visits.outlet_id as outlet_id,outlets.code as outlet_code,outlets.name as outlet_name,outlet_types.name as outlet_type_name,location_zones.name as zone_name,admin.firstname as first,
		ilv_visits.modified as modified,admin.lastname as last,ilv_visits.date as date,developers.name as developer_name,ilv_visits.active as active,ilv_visits.remark as remark,ilv_visits.entry_time as entry_time,ilv_visits.exit_time as exit_time
		,ilv_visits.orange_msg as orange_msg,location_states.name as state_name
		');

		$this -> db -> join('outlets', 'outlets.id=ilv_visits.outlet_id');
		$this -> db -> join('admin', 'admin.id=ilv_visits.admin_id');
		$this -> db -> join('developers', 'developers.id=outlets.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=outlets.zone_id');
		$this -> db -> join('outlet_types', 'outlet_types.id=outlets.outlet_type_id');
		$this -> db -> join('location_states', 'location_states.id=outlets.state_id');
		$this -> db -> where('ilv_visits.admin_id', $admin_id);

		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('ilv_visits');
		return $result -> result();
	}


	function get_ilv_visits($limit = 0, $offset = 0, $order_by = '', $direction = 'DESC') {
		$this -> db -> select('ilv_visits.id as visit_id,ilv_visits.outlet_id as outlet_id,outlets.code as outlet_code,outlets.name as outlet_name,admin.firstname as first,
		ilv_visits.modified as modified,admin.lastname as last,ilv_visits.date as date,ilv_visits.active as active,ilv_visits.remark as remark,ilv_visits.entry_time as entry_time,ilv_visits.exit_time as exit_time
		,ilv_visits.orange_msg as orange_msg
		');

		$this -> db -> join('outlets', 'outlets.id=ilv_visits.outlet_id');
		$this -> db -> join('admin', 'admin.id=ilv_visits.admin_id');
		//$this -> db -> join('developers', 'developers.id=outlets.developer_id');
		//$this -> db -> join('location_zones', 'location_zones.id=outlets.zone_id');
	//	$this -> db -> join('outlet_types', 'outlet_types.id=outlets.outlet_type_id');
		//$this -> db -> join('location_states', 'location_states.id=outlets.state_id');
		

		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('ilv_visits');
		return $result -> result();
	}

    function get_av_anom_visits() {
		$this -> db -> select('ilv_visits.id as visit_id,ilv_visits.outlet_id as outlet_id,outlets.code as outlet_code,outlets.name as outlet_name,outlet_types.name as outlet_type_name,location_zones.name as zone_name,admin.firstname as first,
		ilv_visits.modified as modified,admin.lastname as last,ilv_visits.date as date,developers.name as developer_name,ilv_visits.active as active,ilv_visits.remark as remark,ilv_visits.entry_time as entry_time,ilv_visits.exit_time as exit_time
		,ilv_visits.orange_msg as orange_msg,location_states.name as state_name');

		$this -> db -> join('outlets', 'outlets.id=ilv_visits.outlet_id');
		$this -> db -> join('admin', 'admin.id=ilv_visits.admin_id');
		$this -> db -> join('developers', 'developers.id=outlets.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=outlets.zone_id');
		$this -> db -> join('outlet_types', 'outlet_types.id=outlets.outlet_type_id');
		$this -> db -> join('location_states', 'location_states.id=outlets.state_id');

		$this -> db -> order_by('visit_id', 'DESC');
		

		$result = $this -> db -> get('ilv_visits');
		return $result -> result();
	}


	function get_ilv_visits_by_id($limit = 0, $offset = 0, $order_by = 'ilv_visits.id', $direction = 'DESC', $admin_id) {

		$this -> db -> select('ilv_visits.id as visit_id,ilv_visits.outlet_id as outlet_id,outlets.code as outlet_code,outlets.name as outlet_name,outlet_types.name as outlet_type_name,location_zones.name as zone_name,admin.firstname as first,
		ilv_visits.modified as modified,admin.lastname as last,ilv_visits.date as date,developers.name as developer_name,ilv_visits.active as active,ilv_visits.remark as remark,ilv_visits.entry_time as entry_time,ilv_visits.exit_time as exit_time
		,ilv_visits.orange_msg as orange_msg,location_states.name as state_name');

		$this -> db -> join('outlets', 'outlets.id=ilv_visits.outlet_id');
		$this -> db -> join('admin', 'admin.id=ilv_visits.admin_id');
		$this -> db -> join('developers', 'developers.id=outlets.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=outlets.zone_id');
		$this -> db -> join('outlet_types', 'outlet_types.id=outlets.outlet_type_id');
		$this -> db -> join('location_states', 'location_states.id=outlets.state_id');

		$this -> db -> where('ilv_visits.admin_id', $admin_id);
		$this -> db -> order_by('ilv_visits.id', 'DESC');
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('ilv_visits');
		return $result -> result();
	}

	function get_ilv_visits_by_date($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC', $from, $to) {

		$this -> db -> select('ilv_visits.id as visit_id,ilv_visits.outlet_id as outlet_id,outlets.code as outlet_code,outlets.name as outlet_name,outlet_types.name as outlet_type_name,location_zones.name as zone_name,admin.firstname as first,
		ilv_visits.modified as modified,admin.lastname as last,ilv_visits.date as date,developers.name as developer_name,ilv_visits.active as active,ilv_visits.remark as remark,ilv_visits.entry_time as entry_time,ilv_visits.exit_time as exit_time
		,ilv_visits.orange_msg as orange_msg,location_states.name as state_name');

		$this -> db -> join('outlets', 'outlets.id=ilv_visits.outlet_id');
		$this -> db -> join('admin', 'admin.id=ilv_visits.admin_id');
		$this -> db -> join('developers', 'developers.id=outlets.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=outlets.zone_id');
		$this -> db -> join('outlet_types', 'outlet_types.id=outlets.outlet_type_id');
		$this -> db -> join('location_states', 'location_states.id=outlets.state_id');

		$this -> db -> order_by('ilv_visits.id', 'DESC');
		$this -> db -> where('ilv_visits.date >=', $from);
		$this -> db -> where('ilv_visits.date <=', $to);

		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('ilv_visits');
		return $result -> result();
	}

	function get_ilv_visits_by_admin($limit = 0, $offset = 0, $order_by = 'date', $direction = 'DESC', $admin_id = '') {
		$this -> db -> select('ilv_visits.id as visit_id,ilv_visits.outlet_id as outlet_id,outlets.code as outlet_code,outlets.name as outlet_name,outlet_types.name as outlet_type_name,location_zones.name as zone_name,admin.firstname as first,
		ilv_visits.modified as modified,admin.lastname as last,ilv_visits.date as date,developers.name as developer_name,ilv_visits.active as active,ilv_visits.remark as remark,ilv_visits.entry_time as entry_time,ilv_visits.exit_time as exit_time
		,ilv_visits.orange_msg as orange_msg,location_states.name as state_name');

		$this -> db -> join('outlets', 'outlets.id=ilv_visits.outlet_id');
		$this -> db -> join('admin', 'admin.id=ilv_visits.admin_id');
		$this -> db -> join('developers', 'developers.id=outlets.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=outlets.zone_id');
		$this -> db -> join('outlet_types', 'outlet_types.id=outlets.outlet_type_id');
		$this -> db -> join('location_states', 'location_states.id=outlets.state_id');

		$this -> db -> where('outlets.admin_id', $admin_id);
		$this -> db -> order_by('ilv_visits.id', 'DESC');
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('ilv_visits');
		return $result -> result();
	}

	function get_ilv_visits_by_date_admin($limit = 0, $offset = 0, $order_by = 'date', $direction = 'DESC', $from, $to, $admin_id) {
		$this -> db -> select('ilv_visits.id as visit_id,ilv_visits.outlet_id as outlet_id,outlets.code as outlet_code,outlets.name as outlet_name,outlet_types.name as outlet_type_name,location_zones.name as zone_name,admin.firstname as first,
		ilv_visits.modified as modified,admin.lastname as last,ilv_visits.date as date,developers.name as developer_name,ilv_visits.active as active,ilv_visits.remark as remark,ilv_visits.entry_time as entry_time,ilv_visits.exit_time as exit_time
		,ilv_visits.orange_msg as orange_msg,location_states.name as state_name');

		$this -> db -> join('outlets', 'outlets.id=ilv_visits.outlet_id');
		$this -> db -> join('admin', 'admin.id=ilv_visits.admin_id');
		$this -> db -> join('developers', 'developers.id=outlets.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=outlets.zone_id');
		$this -> db -> join('outlet_types', 'outlet_types.id=outlets.outlet_type_id');
		$this -> db -> join('location_states', 'location_states.id=outlets.state_id');

		$this -> db -> where('ilv_visits.admin_id', $admin_id);
		$this -> db -> where('ilv_visits.date >=', $from);
		$this -> db -> where('ilv_visits.date <=', $to);
		$this -> db -> order_by('ilv_visits.id', 'DESC');
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('ilv_visits');
		return $result -> result();
	}

                                         
	function get_ilv_visits_by_date_admin_pdv($from, $to, $admin_id,$pdv_id) {
		$this -> db -> select('ilv_visits.id as visit_id,ilv_visits.outlet_id as outlet_id,outlets.code as outlet_code,outlets.name as outlet_name,outlet_types.name as outlet_type_name,location_zones.name as zone_name,admin.firstname as first,
		ilv_visits.modified as modified,admin.lastname as last,ilv_visits.date as date,developers.name as developer_name,ilv_visits.active as active,ilv_visits.remark as remark,ilv_visits.entry_time as entry_time,ilv_visits.exit_time as exit_time
		,ilv_visits.orange_msg as orange_msg,location_states.name as state_name');

		$this -> db -> join('outlets', 'outlets.id=ilv_visits.outlet_id');
		$this -> db -> join('admin', 'admin.id=ilv_visits.admin_id');
		$this -> db -> join('developers', 'developers.id=outlets.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=outlets.zone_id');
		$this -> db -> join('outlet_types', 'outlet_types.id=outlets.outlet_type_id');
		$this -> db -> join('location_states', 'location_states.id=outlets.state_id');

	
		$this -> db -> where('ilv_visits.date >=', $from);
		$this -> db -> where('ilv_visits.date <=', $to);
		$this -> db -> where('ilv_visits.admin_id', $admin_id);
		$this -> db -> where('outlets.outlet_type_id', $pdv_id);
		
		

		$result = $this -> db -> get('ilv_visits');
		return $result -> result();
	}




	function get_ilv_visits_by_pdv($limit = 0, $offset = 0, $order_by = 'date', $direction = 'DESC', $pdv_id = '') {
		$this -> db -> select('ilv_visits.id as visit_id,ilv_visits.outlet_id as outlet_id,outlets.code as outlet_code,outlets.name as outlet_name,outlet_types.name as outlet_type_name,location_zones.name as zone_name,admin.firstname as first,
		ilv_visits.modified as modified,admin.lastname as last,ilv_visits.date as date,developers.name as developer_name,ilv_visits.active as active,ilv_visits.remark as remark,ilv_visits.entry_time as entry_time,ilv_visits.exit_time as exit_time
		,ilv_visits.orange_msg as orange_msg,location_states.name as state_name');

		$this -> db -> join('outlets', 'outlets.id=ilv_visits.outlet_id');
		$this -> db -> join('admin', 'admin.id=ilv_visits.admin_id');
		$this -> db -> join('developers', 'developers.id=outlets.developer_id');
		$this -> db -> join('location_zones', 'location_zones.id=outlets.zone_id');
		$this -> db -> join('outlet_types', 'outlet_types.id=outlets.outlet_type_id');
		$this -> db -> join('location_states', 'location_states.id=outlets.state_id');

		$this -> db -> where('outlets.outlet_type_id', $pdv_id);
		$this -> db -> order_by('ilv_visits.id', 'DESC');
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('ilv_visits');
		return $result -> result();
	}

	function count_ilv_visits() {
		return $this -> db -> count_all_results('ilv_visits');
	}

	function get_ilv_visit($id) {

		$result = $this -> db -> get_where('ilv_visits', array('id' => $id));
		return $result -> row();
	}

	function save($ilv_visit) {
		$data = array();
		if ($ilv_visit['id']) {
			$this -> db -> where('id', $ilv_visit['id']);
			$this -> db -> update('ilv_visits', $ilv_visit);
			return $ilv_visit['id'];
		} else {




	        $admin_id = $ilv_visit['admin_id'];		
            $outlet_id = $ilv_visit['outlet_id'];
			
			
			
			$date = new DateTime('now', new DateTimeZone('Africa/Tunis'));
			$ilv_visit['modified'] = $date -> format('H:i');
			$this -> db -> insert('ilv_visits', $ilv_visit);
			$new_visit_id = $this -> db -> insert_id();

			
			$this->save_ilvs($admin_id,$new_visit_id);
			

			return $this -> db -> insert_id();
		}
	}

    function save_ilvs($admin_id,$visit_id) {
    	$ilvs	= $this->Ilv_model->get_active_ilvs();
	    foreach ($ilvs as $ilv) {
	    	
			
			$stock=$this->Stock_model->get_actual_stock($admin_id,$ilv->id);
			//echo $admin_id.'-'.$ilv->id.','.$stock.'***';
			//print_r($stock);
			if($stock==null){
				$stock=0;
			}
			$save['id'] = false;
			$save['visit_id'] = $visit_id;
		    $save['ilv_id'] = $ilv->id;
			$save['admin_id'] = $admin_id;
			$save['code'] = $ilv->code;
			$save['name'] = $ilv->name;
			$save['type'] = $ilv->type;
			$save['image'] = $ilv->image;
			$save['quantity'] = 0;
			$save['stock'] = $stock;
			$save['av'] = 0;

			$this -> Ilv_model_model -> save_ilv($save);

		}
	}

	function deactivate($id) {
		$ilv_visit = array('id' => $id, 'active' => 0);
		$this -> save($ilv_visit);
	}

	function delete($id) {
		/*
		 deleting a modern_visit will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//this deletes the modern_visits record
		$this -> db -> where('id', $id);
		$this -> db -> delete('ilv_visits');

	}

	public function get_outlet_id($visit_id) {
		return $this -> db -> get_where('ilv_visits', array('id' => $visit_id)) -> row() -> outlet_id;
	}

	function get_visit_date($visit_id) {

		return $this -> db -> get_where('ilv_visits', array('id' => $visit_id)) -> row() -> date;
	}
	
	function get_admin_id($visit_id) {

		return $this -> db -> get_where('ilv_visits', array('id' => $visit_id)) -> row() -> admin_id;
	}
	
	
	
	

}
