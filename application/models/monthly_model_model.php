<?php
Class Monthly_model_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	function count_models() {
		return $this -> db -> count_all_results('monthly_models');
	}

	function get_models($visit_id) {
		$this -> db -> order_by('model_id', 'ASC');
		$result = $this -> db -> get_where('monthly_models', array('visit_id' => $visit_id));
		return $result -> result();
	}

	function get_shortage_models($visit_id) {
		$this -> db -> order_by('model_id', 'ASC');
		$result = $this -> db -> get_where('shortage_models', array('visit_id' => $visit_id));
		return $result -> result();
	}

	function save_bulk($model) {
		if ($model['id']) {
			$price = $this -> Model_model -> get_model_price($model['model_id']);
			$model['amount'] = $model['ws'] * $price;
			$this -> db -> where('id', $model['id']);
			$this -> db -> update('monthly_models', $model);
			return $model['id'];
		}

	}

	function shortage_save_bulk($model) {
		if ($model['id']) {

			$this -> db -> where('id', $model['id']);
			$this -> db -> update('shortage_models', $model);
			return $model['id'];
		}

	}

	function create_shortage_models($visit_id) {
		$models = $this -> Model_model -> get_shortage_models();
		foreach ($models as $model) {
			$model_id = $model -> id;
			$brand_id = $model -> brand_id;
			
				$data[] = array(
				'model_id' => $model_id,
				'visit_id' => $visit_id,
				'brand_id' => $brand_id,
				'shortage' => '0'
				 );
		  }
		
		$this -> save_shortage($data, $visit_id);
	}

	function save_model($model) {
		if ($model['id']) {
			$this -> db -> where('id', $model['id']);
			$this -> db -> update('monthly_models', $model);
			return $model['id'];
		} else {
			$this -> db -> insert('monthly_models', $model);

			return $this -> db -> insert_id();
		}
	}

	function save_single($model) {

		$this -> db -> insert('monthly_models', $model);
		return $this -> db -> insert_id();

	}

	function save(&$models, $visit_id) {
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this -> db -> trans_start();

		$this -> delete($visit_id);

		foreach ($models as $row) {
			$row['visit_id'] = $visit_id;
			$this -> db -> insert('monthly_models', $row);
		}

		$this -> db -> trans_complete();
		return true;
	}
	
	function save_shortage(&$models, $visit_id) {
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this -> db -> trans_start();

		$this -> delete($visit_id);

		foreach ($models as $row) {
			$row['visit_id'] = $visit_id;
			$this -> db -> insert('shortage_models', $row);
		}

		$this -> db -> trans_complete();
		return true;
	}

	function is_exist($model_id, $visit_id) {
		$query = $this -> db -> get_where('monthly_models', array('model_id' => $model_id, 'visit_id' => $visit_id));
		$count = $query -> num_rows();
		if ($count == 0) {
			return false;
		} else {
			return true;
		}
	}

	function deactivate($id) {
		$monthly_model = array('id' => $id, 'active' => 0);
		$this -> save_monthly_model($monthly_model);
	}

	function delete($id) {
		/*
		 deleting a monthly_model will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//this deletes the monthly_models record
		$this -> db -> where('id', $id);
		$this -> db -> delete('monthly_models');

	}

	function delete_by_visit($visit_id) {
		/*
		 deleting a classic_model will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//this deletes the classic_models record
		$this -> db -> where('visit_id', $visit_id);
		$this -> db -> delete('monthly_models');

	}
	
	function delete_shortage_by_visit($visit_id) {
		/*
		 deleting a classic_model will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//this deletes the classic_models record
		$this -> db -> where('visit_id', $visit_id);
		$this -> db -> delete('shortage_models');

	}

	function get_Ulc_models($visit_id) {
		$this -> db -> select('monthly_models.*');
		$this -> db -> from('monthly_models');
		$this -> db -> where('visit_id', $visit_id);

		$this -> db -> join('models', 'models.id=monthly_models.model_id');
		$this -> db -> where('monthly_models.range_id', 1);
		$this -> db -> order_by('monthly_models.brand_id', 'ASC');
		$this -> db -> order_by('models.code', 'ASC');

		$result = $this -> db -> get();
		return $result -> result();
	}

	function get_all_models() {
		$this -> db -> select('monthly_models.*');
		$this -> db -> from('monthly_models');

		$result = $this -> db -> get();
		return $result -> result();
	}

	function update($model) {

		$price_range_id = $this -> Model_model -> get_model_price_range($model -> model_id);
		print_r($price_range_id);
		$data['price_range_id'] = $price_range_id;
		$data['id'] = $model -> id;
		$this -> db -> where('id', $data['id']);
		$this -> db -> update('monthly_models', $data);
		return $data['id'];
	}

	function update_mod() {
		$models = $this -> get_all_models();
		foreach ($models as $model) {
			$data['id'] = $model -> id;
			$data['model_id'] = $model -> model_id;
			$data['price_range_id'] = $this -> Model_model -> get_model_price_range($model -> model_id);
			//echo $date['id'].'**'.$date['model_id'].'**'.$date['price_range_id'].'$$$$$$$$';
			$this -> db -> where('id', $data['id']);
			$this -> db -> update('monthly_models', $data);
			echo 'traiter';

		}

	}

	function get_Smart_models($visit_id) {
		$this -> db -> select('monthly_models.*');
		$this -> db -> from('monthly_models');
		$this -> db -> join('models', 'models.id=monthly_models.model_id');
		$this -> db -> order_by('monthly_models.brand_id', 'ASC');
		$this -> db -> order_by('models.code', 'ASC');
		$this -> db -> where('monthly_models.range_id', 2);
		$this -> db -> where('visit_id', $visit_id);
		$result = $this -> db -> get();
		return $result -> result();
	}

	function get_Features_models($visit_id) {
		$this -> db -> select('monthly_models.*');
		$this -> db -> from('monthly_models');
		$this -> db -> join('models', 'models.id=monthly_models.model_id');
		$this -> db -> order_by('monthly_models.brand_id', 'ASC');
		$this -> db -> order_by('models.code', 'ASC');
		$this -> db -> where('monthly_models.range_id', 3);
		$this -> db -> where('visit_id', $visit_id);
		$result = $this -> db -> get();
		return $result -> result();
	}

	function get_Tablet_models($visit_id) {
		$this -> db -> select('monthly_models.*');
		$this -> db -> from('monthly_models');
		$this -> db -> join('models', 'models.id=monthly_models.model_id');
		$this -> db -> order_by('monthly_models.brand_id', 'ASC');
		$this -> db -> order_by('models.code', 'ASC');
		$this -> db -> where('monthly_models.range_id', 4);
		$this -> db -> where('visit_id', $visit_id);
		$result = $this -> db -> get();
		return $result -> result();
	}

	function get_Gear_models($visit_id) {
		$this -> db -> select('monthly_models.*');
		$this -> db -> from('monthly_models');
		$this -> db -> join('models', 'models.id=monthly_models.model_id');
		$this -> db -> order_by('monthly_models.brand_id', 'ASC');
		$this -> db -> order_by('models.code', 'ASC');
		$this -> db -> where('monthly_models.range_id', 5);
		$this -> db -> where('visit_id', $visit_id);
		$result = $this -> db -> get();
		return $result -> result();
	}

	function get_models_by_brand($visit_id, $brand_id) {
		$this -> db -> select('monthly_models.*');
		$this -> db -> from('monthly_models');
		$this -> db -> join('models', 'models.id=monthly_models.model_id');
		$this -> db -> order_by('models.code', 'ASC');
		$this -> db -> where('monthly_models.brand_id', $brand_id);
		$this -> db -> where('visit_id', $visit_id);
		$result = $this -> db -> get();
		return $result -> result();
	}

}
