<?php
Class Notification_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 7200;

	function __construct() {
		parent::__construct();
	}

	/********************************************************************

	 ********************************************************************/

	

	function save($notif) {
		if ($notif['id']) {
			$this -> db -> where('id', $notif['id']);
			$this -> db -> update('notification', $notif);
			return $notif['id'];
		} else {
			$this -> db -> insert('notification', $notif);
			return $this -> db -> insert_id();
		}
	}



//////////////////////////////////////////////////////////////
function count_model_non_actif(){
$this -> db -> select(' count(*) as count,active');
$this -> db -> where('active',0);
$this -> db -> where('type','new_model');

$query = $this->db->get('notification');
return $query->row()->count;
	}

function get_models_non_actif()
	{
    $this -> db -> select('*');
    $this -> db -> where('active',0);
    $this -> db -> where('type','new_model');
    $result = $this -> db -> get('notification');
	return $result -> result();
	}
///////////////////////////////////////////////////////

function count_visit_non_actif(){
$this -> db -> select(' count(*) as count,active');
$this -> db -> where('active',0);
$this -> db -> where('type','visit');

$query = $this->db->get('notification');
return $query->row()->count;
	}
function get_visits_non_actif()
	{
    $this -> db -> select('*');
    $this -> db -> where('active',0);
    $this -> db -> where('type','visit');
    $result = $this -> db -> get('notification');
	return $result -> result();
	}


	///////////////////////////////////////////////////////

function count_ads_non_actif(){
$this -> db -> select(' count(*) as count,active');
$this -> db -> where('active',0);
$this -> db -> where('type','ads');

$query = $this->db->get('notification');
return $query->row()->count;
	}
function get_ads_non_actif()
	{
    $this -> db -> select('*');
    $this -> db -> where('active',0);
    $this -> db -> where('type','ads');
    $result = $this -> db -> get('notification');
	return $result -> result();
	}
//////////////////////////////////
	function list_pictures_non_actif(){
    $this -> db -> select('id, count(*) as count,active,visit_id');
    $this -> db -> where('active',0);
    $this -> db -> where('type','picture');
    $this -> db -> group_by('visit_id');

    $result = $this -> db -> get('notification');

		return $result -> result();
	}

		function get_pictures_non_actif($visit_id)
	{
    $this -> db -> select('id,active,visit_id,type');
    $this -> db -> where('active',0);
    $this -> db -> where('type','picture');
    $this -> db -> where('visit_id',$visit_id);

    $result = $this -> db -> get('notification');
	return $result -> result();
	}
	//////////////pictures
}
