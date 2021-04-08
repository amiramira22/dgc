<?php
Class Competitor_ads_model extends CI_Model {

	//this is the expiration for a non-remember session
	

	function __construct() {
		parent::__construct();
	}

function get_competitor_ads_by_admin($admin_id) {
		$this -> db -> where('admin_id', $admin_id);

        $this -> db -> order_by('id', 'DESC');
		$result = $this -> db -> get('ads');
		return $result -> result();
	}
	
	
	
	
	
	function count_competitor_ads($start_date, $end_date) {
	

	
		if ($start_date != '' && $end_date != '') {
			$this -> db -> where('ads.date >=', $start_date);
			$this -> db -> where('ads.date <=', $end_date);
					return $this -> db -> count_all_results('ads');

		}
		

		return $this -> db -> count_all_results('ads');
		//$result = $this -> db -> get('sales');
		//return count($result -> result());
	}

    function get_competitor_ads_by_id($id) {

		$result = $this -> db -> get_where('ads', array('id' => $id));
		return $result -> row();
	}



	function get_all() {
	$this -> db -> select('*');
	$this -> db -> order_by('id','DESC');

		$result = $this -> db -> get('ads');
		return $result -> result();	
		
	}
    function get_ads_by_users($id_user) {
		$this->db-> select('*');
       $this->db-> where('admin_id', $id_user);
        $result =$this->db-> get('ads');
		return $result -> result();
	}

function get_ads_by_id($id) {

		$result = $this -> db -> get_where('ads', array('id' => $id));
		return $result -> row();
	}
	
	function save($ads) {
		if ($ads['id']) {
			$this -> db -> where('id', $ads['id']);
			$this -> db -> update('ads', $ads);
		
		} else {
			$this -> db -> insert('ads', $ads);
				$data_notif = array(
             'visit_id'=>$inserted_visit_id,
             'type'=>'ads'
               );

$this->db->insert('notification',$data_notif);

			return $this -> db -> insert_id();
		}
	}

	   public function get_competitor_ads($limit = 0, $offset = 0,$week_debut,$week_end) {
if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}
		$this -> db -> select('*');

		$this -> db -> from('ads');


		
		if($week_debut!='' and $week_end!=''){
		
		$w1=date("W", strtotime($week_debut ));
        $w2=date("W", strtotime($week_end ));
        $year=date("Y", strtotime($week_debut ));
		$this -> db -> where('WEEK(date) >=', $w1);
      	$this -> db -> where('WEEK(date) <=', $w2);
		$this -> db -> where('YEAR(date) =', $year);

}



		return $this -> db -> get() -> result();

	}

	function delete($id) {
		/*
		 deleting a city will remove all their orders from the system
		 this will alter any report numbers that reflect total sales
		 deleting a customer is not recommended, deactivation is preferred
		 */

		//this deletes the cities record
		$this -> db -> where('id', $id);
		$this -> db -> delete('ads');

	}
	
	
}