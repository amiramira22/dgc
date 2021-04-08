<?php
Class Commun_model extends CI_Model {

	//this is the expiration for a non-remember session
	

	function __construct() {
		parent::__construct();
	}


function count_ads_nonactif() {
	
	$this -> db -> where('active', 0);
		return $this -> db -> count_all_results('ads');
	}

   
   function count_models_nonactif() {
	
	$this -> db -> where('active', 0);
		return $this -> db -> count_all_results('new_models');
	}
   function count_no_viewed_messages($admin_id) {
			$this -> db -> where('active', 0);
			$this -> db -> where('admin_id', $admin_id);
		return $this -> db -> count_all_results('messages');
	}
	
	
		
	
	
}