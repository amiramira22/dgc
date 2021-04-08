<?php
Class Ws_model_bcm extends CI_Model {

	

	function __construct() {
		parent::__construct();
	}
	
	
	function login($email, $password)
 {
   $this -> db -> select();
   $this -> db -> from('admin');
   $this -> db -> where('email', $email);
   $this -> db -> where('password', sha1($password));
   $this -> db -> limit(1);
 
   $query = $this -> db -> get();
 
   if($query -> num_rows() == 1)
   {
     return $query->result();
   }
   else
   {
     return false;
   }
 }

 
	function get_ws_rayons() {
	
		$result = $this -> db -> get('rayons');
		return $result ->result_array();
	}
	
	
		function get_ws_ha() {
	
		$ha = $this -> db -> get('ha');
		return $ha ->result_array();
	}
	function get_ws_one_pictures() {
	
		$result = $this -> db -> get('one_pictures');
		return $result ->result_array();
	}
	
	// List of brands
	function get_ws_brands() {
	
		$result = $this -> db -> get('brands');
		return $result ->result_array();
	}
	//get clients for vivo
	function get_ws_clients() {
	
		$result = $this -> db -> get('client');
		return $result ->result_array();
	}

	// List of users
	function get_ws_users() {
	
		$result = $this -> db -> get('admin');
		return $result ->result_array();
	}
	
	// List of outlets
	function get_ws_outlets() {
	
		$this -> db -> where('active', 1);
		$result = $this -> db -> get('outlets');
		return $result ->result_array();
	}
	
	// List of products
	function get_ws_products() {
	
		$result = $this -> db -> get('products');
		return $result ->result_array();
	}
	
	function get_ws_categories() {
	
		$result = $this -> db -> get('categories');
		return $result ->result_array();
	}
	
	function get_ws_sub_categories() {
	
		$result = $this -> db -> get('sub_categories');
		return $result ->result_array();
	}
	
	
	function get_product_types() {
	
		$result = $this -> db -> get('product_types');
		return $result ->result_array();
	}
	
	function get_product_groups() {
	
		$result = $this -> db -> get('product_groups');
		return $result ->result_array();
	}
	
	function get_clusters() {
	
		$result = $this -> db -> get('clusters');
		return $result ->result_array();
	}
	
	function get_ws_zones() {
	
		$result = $this -> db -> get('zones');
		return $result ->result_array();
	}
	
	function get_ws_states() {
	
		$result = $this -> db -> get('states');
		return $result ->result_array();
	}
	
	// List of product_zones
	function get_ws_product_zones($zones) {
	    $this->db->where_in('zone', $zones);
		$result = $this -> db -> get('product_zones');
		return $result ->result_array();
	}
	
	// List of product_groups
	function get_ws_product_groups() {
	
		$result = $this -> db -> get('product_groups');
		return $result ->result_array();
	}
	
	// List of zones
	function get_ws_zones2($zones) {
	    $this->db->where_in('name', $zones);
		$result = $this -> db -> get('zones');
		return $result ->result_array();
	}
	
	// List of states
	function get_ws_states2($zones) {
	    $this->db->where_in('zone', $zones);
		$result = $this -> db -> get('states');
		return $result ->result_array();
	}
	
	// List of sectors
	function get_ws_sectors($zones) {
	    $this -> db -> select('sectors.*', false);
		$this -> db -> from('sectors');
		$this->db->where_in('states.zone', $zones);
		$this -> db -> join('states', 'states.name = sectors.state');
		$result = $this -> db -> get();
		return $result ->result_array();
	}
	
	// List of cities
	function get_ws_cities($zones) {
	
		$this -> db -> select('cities.*', false);
		$this -> db -> from('cities');
		$this->db->where_in('states.zone', $zones);
		$this -> db -> join('sectors', 'sectors.name = cities.sector');
		$this -> db -> join('states', 'states.name = sectors.state');
		
		$result = $this -> db -> get();
		return $result ->result_array();
	}
	

	//delete model
	function delete_models($id) {
	    $this -> db -> where('visit_id', $id);
		$this -> db -> delete('models');
	}

	//delete visit
	function delete_visit($id) {
	    $this -> db -> where('id', $id);
		$this -> db -> delete('visits');
	}
		//delete visit
	function delete_visitgroup($id) {
	    $this -> db -> where('id', $id);
		$this -> db -> delete('visits_monthly');
	}

	//save visit
	function save_visit($visit) {
		    
			$this -> db -> insert('visits', $visit);
			return $this -> db -> insert_id();
		
	}
		//save visit
	function save_visitgroup($visit) {
		    
			$this -> db -> insert('visits_monthly', $visit);
			return $this -> db -> insert_id();
		
	}
	
	function update_visit($visit) {
		if ($visit['id']) {

			$this -> db -> where('id', $visit['id']);
			$this -> db -> update('visits', $visit);
			return $visit['id'];
		}
	}
	
	
	function update_visit2($visit) {
		if ($visit['id']) {
			
			$this->db->trans_start();
			
			$this -> db -> where('id', $visit['id']);
			$this -> db -> update('visits', $visit);

$this->db->trans_complete();


			
			return $visit['id'];
		}
	}
	
	function update_outlet($outlet) {
		if ($outlet['id']) {
			$this -> db -> where('id', $outlet['id']);
			$this -> db -> update('outlets', $outlet);
			return $outlet['id'];
		}
	}
	
	//save model
	function save_model($model) {
		
			$this -> db -> insert('models', $model);
			return $this -> db -> insert_id();
		
	}
	
	function save_rayon($rayon) {
		
			$this -> db -> insert('rayons', $rayon);
			return $this -> db -> insert_id();
		
	}
	
	function save_picture($picture) {
		
			$this -> db -> insert('one_pictures', $picture);
			return $this -> db -> insert_id();
		
	}
	
	
	//save outlet
	function save_outlet($outlet) {
	       $orig_db_debug=$this->db->db_debug;
	    $this->db->db_debug=false;
	
		    //start the transaction
            $this->db->trans_begin();
			$this -> db -> insert('outlets', $outlet);
		    $this->db->db_debug=$orig_db_debug;
            return $this -> db -> insert_id();
      
			
				
	}
	
	
	function save_email($email) {
	       $orig_db_debug=$this->db->db_debug;
	    $this->db->db_debug=false;
	
		  
            $this->db->trans_begin();
			$this -> db -> insert('email', $email);
		    $this->db->db_debug=$orig_db_debug;
            return $this -> db -> insert_id();
      
				
	}
	
	function update_admin($id,$register_id){
	
		$this->db->set('register_id', $register_id);
		$this->db->where('id', $id);
		return $this->db->update('admin');
		
	}
	
	function get_messages_by_receiver_id($id)
	{
		$this->db->select('messages.id as message_id,admin.name as sender_name,messages.message as message,messages.created as created',false);
		$this -> db -> join('admin', 'admin.id=messages.sender_id');
		
		$this -> db -> where('messages.receiver_id',$id);
		
		$this -> db -> order_by('messages.created');
		
		$this->db->from('messages');
		$query=$this -> db -> get() -> result_array();
		
		return $query;
	}
	
	function get_outlet($id) {

		$result = $this -> db -> get_where('outlets', array('id' => $id));
		return $result -> row();
	}
	
	
	function get_responsible_mail($responsible_id)
	{
		$result = $this -> db -> get_where('admin', array('id' => $responsible_id));
		return $result -> row()->email;
	}
	public function get_product_name($product_id) {
		return $this->db->get_where('products', array('id'=>$product_id))->row()->name;
	}
	//save hors assortiment
	function save_ha($ha) {
		    if(! $this-> is_ha_uploaded($ha)){
			$this -> db -> insert('ha', $ha);
			return $this -> db -> insert_id();
		}
		
	}
		//delete model
	function delete_ha($ha) {
	    $this -> db -> where('product_id', $ha['product_id']);
	   	$this -> db -> where('outlet_id', $ha['outlet_id']);
		$this -> db -> delete('ha');
	}
		//return yes if visit is already uploaded
	function is_visit_uploaded($visit_uniqueId) {
		$this -> db -> where('uniqueId',$visit_uniqueId);
		$result = $this -> db -> get('visits');
		return $result->num_rows();
	}
	function is_ha_uploaded($ha) {
	    $this -> db -> where('product_id', $ha['product_id']);
	   	$this -> db -> where('outlet_id', $ha['outlet_id']);
		$result = $this -> db -> get('ha');
		return $result->num_rows();
	}
		//delete model
	function delete_model_unique_id($visit_uniqueId) {
	    $this -> db -> where('visit_uniqueId', $visit_uniqueId);
		$this -> db -> delete('models');
	}

	//delete visit
	function delete_visit_unique_id($uniqueId) {
	    $this -> db -> where('uniqueId', $uniqueId);
		$this -> db -> delete('visits');
	}
		function get_ws_ha_products($admin_id) {

		$this->db->select('ha.id as id,ha.product_id as product_id,ha.outlet_id as outlet_id' ,false);
		$this -> db -> join('outlets', 'outlets.id=ha.outlet_id');
		
		$this -> db -> where('outlets.admin_id',$admin_id);
		
		$this -> db -> order_by('ha.id');
		
		$this->db->from('ha');
		$query=$this -> db -> get() -> result_array();
		
		return $query;
	}
}