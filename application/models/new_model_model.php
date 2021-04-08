<?php
Class New_model_model extends CI_Model {

	//this is the expiration for a non-remember session
	

	function __construct() {
		parent::__construct();
		
	}




   function count_models_nonactif() {
	
	$this -> db -> where('active', 0);
		return $this -> db -> count_all_results('new_models');
	}
  
  function count_new_models($start_date, $end_date,$fo_id) {
	
	
	if($fo_id != -1)
      {
	
	$this -> db -> where('fo_id', $fo_id);
	
       }
		if ($start_date != '' && $end_date != '') {
			$this -> db -> where('new_models.date >=', $start_date);
			$this -> db -> where('new_models.date <=', $end_date);
		return $this -> db -> count_all_results('new_models');

		}
		
		return $this -> db -> count_all_results('new_models');
		
	}

   public function get_new_models($limit = 0, $offset = 0,$week_debut,$week_end,$fo_id=-1) {
if ($limit > 0) {
			$this -> db -> limit($limit, $offset);
		}
		$this -> db -> select('*');

		$this -> db -> from('new_models');

if($fo_id != -1)
{
	
	$this -> db -> where('fo_id', $fo_id);
	
}
		
		if($week_debut!='' and $week_end!=''){
		
		$w1=date("W", strtotime($week_debut ));
        $w2=date("W", strtotime($week_end ));
        $year=date("Y", strtotime($week_debut ));
		$this -> db -> where('WEEK(date) >=', $w1);
      	$this -> db -> where('WEEK(date) <=', $w2);
		$this -> db -> where('YEAR(date) =', $year);

}
		$this -> db -> order_by('id','DESC');



		return $this -> db -> get() -> result();

	}


	function get_last_five_new_models() {
		$this -> db -> order_by('id', 'DESC');
		
		$this -> db -> limit(5);
	

		$result = $this -> db -> get('new_models');
		
		return $result -> result();
	}

     function save($new_models) {
		if ($new_models['id']) {
			$this -> db -> where('id', $new_models['id']);
			$this -> db -> update('new_models', $new_models);
			//return $new_models['id'];
		} else {
			$this -> db -> insert('new_models', $new_models);
			  $data_notif = array(
             'visit_id'=>$inserted_visit_id,
             'type'=>'new_model'
               );

$this->db->insert('notification',$data_notif);


			return $this -> db -> insert_id();
		}
	}

	function delete($id) {

		//this deletes the channels record
		$this -> db -> where('id', $id);
		$this -> db -> delete('new_models');

	}
	


    function get_new_models_by_id($id) {

		$result = $this -> db -> get_where('new_models', array('id' => $id));
		return $result -> row();
	}




}