<?php
Class Monthly_visit_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 10200;
var $column = array('monthly_visits.date','monthly_visits.active','outlets.name','admin.firstname','admin.lastname');
	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	function get_visits($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('monthly_visits');
		return $result -> result();
	}
function count_visits_by_date_admin($start,$end,$admin_id) {
	$this -> db -> where('admin_id', $admin_id);
	$this -> db -> where('date >=', $start);
	$this -> db -> where('date <=', $end);
		return $this -> db -> count_all_results('weekly_visits');
	}

	function get_visits_by_date($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC', $date = '') {
		$this -> db -> select('monthly_visits.*');
		$this -> db -> where('date', $date);
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('monthly_visits');
		return $result -> result();
	}

function count_visits_by_date($start,$end) {
	$this -> db -> where('date >=', $start);
	$this -> db -> where('date <=', $end);
		return $this -> db -> count_all_results('monthly_visits');
	}
	function get_visits_by_zone($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC', $zone_id = '') {
		$this -> db -> select('monthly_visits.*');
		$this -> db -> join('outlets', 'outlets.id=monthly_visits.outlet_id');
		$this -> db -> join('location_zones', 'outlets.zone_id = location_zones.id');
		$this -> db -> where('outlets.zone_id', $zone_id);
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('monthly_visits');
		return $result -> result();
	}

	function get_visits_by_date_zone($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC', $date, $zone_id = '') {
		$this -> db -> select('monthly_visits.*');
		$this -> db -> join('outlets', 'outlets.id=monthly_visits.outlet_id');
		$this -> db -> join('zones', 'outlets.zone_id = zones.id');
		$this -> db -> where('outlets.zone_id', $zone_id);
		$this -> db -> where('date', $date);
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('monthly_visits');
		return $result -> result();
	}

	function get_visits_by_id($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC', $admin_id) {

		$this -> db -> where('admin_id', $admin_id);
		$this -> db -> order_by($order_by, $direction);
		if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}

		$result = $this -> db -> get('monthly_visits');
		return $result -> result();
	}

	function count_visits() {
		return $this -> db -> count_all_results('monthly_visits');
	}

	function get_visit($id) {

		$result = $this -> db -> get_where('monthly_visits', array('id' => $id));
		return $result -> row();
	}

	function get_visit_by_outlet($id) {

		$result = $this -> db -> get_where('monthly_visits', array('id' => $id));
		return $result -> row();
	}
	
	
	
	function copy($visit, $visit_id) {
		
			$this -> db -> insert('monthly_visits', $visit);

			$new_visit_id = $this -> db -> insert_id();

			$models = $this -> Monthly_model_model -> get_models($visit_id);
			foreach ($models as $model) {
				$data['id'] = false;
				$data['visit_id'] = $new_visit_id;
				$data['model_id'] = $model -> model_id;
				$data['category_id'] = $model -> category_id;
				$data['brand_id'] = $model -> brand_id;
				$data['range_id'] = $model -> range_id;
				$data['price_range_id'] = $model -> price_range_id;
				$data['shelf'] = $model -> shelf;
				$data['price'] = $model -> price;
				$data['ws'] = $model -> ws;
				$data['amount'] = $model -> amount;

				$this -> Monthly_model_model -> save_model($data);
			}

			return $new_visit_id;
		
	}
	
	

	function save($visit) {
		if ($visit['id']) {
			$this -> db -> where('id', $visit['id']);
			$this -> db -> update('monthly_visits', $visit);
			return $visit['id'];
		} else {

			$date = new DateTime('now', new DateTimeZone('Africa/Tunis'));
			$visit['modified'] = $date -> format('d/m/Y H:i');

			$this -> db -> insert('monthly_visits', $visit);

			$visit['id'] = $this -> db -> insert_id();

			$last_visit_id = $this -> Outlet_model -> get_visit_id($visit['outlet_id']);
			if ($last_visit_id != -1) {
				$last_models = $this -> Monthly_model_model -> get_models($last_visit_id);

				foreach ($last_models as $model) {
					$model_id=$model -> model_id;
					if ($this->Model_model->is_active(($model_id))){
					$data[] = array('model_id' => $model -> model_id, 'brand_id' => $model -> brand_id, 'category_id' => $model -> category_id, 'range_id' => $model -> range_id, 'price_range_id' => $model -> price_range_id, 'shelf' => $model -> shelf, 'ws' => 0, 'price' => $model -> price, 'amount' => $model -> amount);	
					}

				}
				
				// if new model => insert in monthly_models table
				$models = $this -> Model_model -> get_active_models();
				foreach ($models as $model) {
					$model_id=$model -> id;
					if (!$this->Monthly_model_model->is_exist($model_id,$last_visit_id)){
					$data[] = array('model_id' => $model -> id, 'brand_id' => $model -> brand_id, 'category_id' => $model -> category_id, 'range_id' => $model -> range_id, 'price_range_id' => $model -> price_range_id, 'shelf' => '0', 'ws' => '0', 'price' => $model -> price, 'amount' => '0');
					}
				}
				
			} else {
				// Get  active Models
				$models = $this -> Model_model -> get_active_models();

				foreach ($models as $model) {
					$data[] = array('model_id' => $model -> id, 'brand_id' => $model -> brand_id, 'category_id' => $model -> category_id, 'range_id' => $model -> range_id, 'price_range_id' => $model -> price_range_id, 'shelf' => '0', 'ws' => '0', 'price' => $model -> price, 'amount' => '0');

				}
			}

			//save models
			$this -> Monthly_model_model -> save($data, $visit['id']);
			$this -> Monthly_model_model -> create_shortage_models($visit['id']);

			//update outlet table
			$outlet['visit_id_temp'] = $last_visit_id;
			$outlet['visit_id'] = $visit['id'];
			$this -> db -> where('id', $visit['outlet_id']);
			$this -> db -> update('outlets', $outlet);

			return $this -> db -> insert_id();
		}
	}

	function update_outlet() {

	}

	function deactivate($id) {
		$visit = array('id' => $id, 'active' => 0);
		$this -> save($visit);
	}

	function activate($id) {
		$visit = array('id' => $id, 'active' => 1);
		$this -> save($visit);
	}

	function delete($id) {
		/*
		 deleting a visit will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//Update outlet table
		$outlet_id = $this -> get_outlet_id($id);
		$outlet = $this -> Outlet_model -> get_outlet($outlet_id);

		$temp = $outlet -> visit_id_temp;
		$vis = $outlet -> visit_id;

		if ($temp != $vis) {
			$outlet1['visit_id'] = $temp;
		} else{
			$outlet1['visit_id'] = -1;
		}
			

		$this -> db -> where('id', $outlet_id);
		$this -> db -> update('outlets', $outlet1);

		//this deletes the visits record
		$this -> db -> where('id', $id);
		$this -> db -> delete('monthly_visits');

	}

	public function get_outlet_id($visit_id) {
		return $this -> db -> get_where('monthly_visits', array('id' => $visit_id)) -> row() -> outlet_id;
	}

	function count_zones_by_date($dt, $zone_id) {
		$this -> db -> join('outlets', 'outlets.id=monthly_visits.outlet_id');
		$this -> db -> join('zones', 'outlets.zone_id = zones.id');
		$this -> db -> where('outlets.zone_id', $zone_id);
		$this -> db -> where('monthly_visits.date', $dt);
		return $this -> db -> count_all_results('monthly_visits');
	}

	function get_visit_date($visit_id) {

		return $this -> db -> get_where('monthly_visits', array('id' => $visit_id)) -> row() -> date;
	}




	private function _get_datatables_query($admin_id=-1)
	{
		
		
	//	$this -> db ->query("SET SQL_BIG_SELECTS=1");
		$this -> db -> select('outlets.name as outlet_name,admin.lastname as last,admin.firstname as first,monthly_visits.date as date,monthly_visits.active as active,monthly_visits.id as id');
	   $this -> db -> join('outlets', 'outlets.id=monthly_visits.outlet_id');
		$this -> db -> join('admin', 'admin.id=monthly_visits.admin_id');
		//$this -> db -> order_by('monthly_visits.id', 'DESC');
		
		$this->db->from('monthly_visits');
			if($admin_id!='-1'){
			$this -> db -> where('monthly_visits.admin_id', $admin_id);
		}
	
		//$this -> db -> order_by('weekly_visits.id', 'DESC');
		
		

      $i = 0;
		
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}

		
	
	}
	
	function get_datatables($admin_id)
	{
		$this->_get_datatables_query();

	
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}




}
