<?php
Class Log_model extends CI_Model {

	//this is the expiration for a non-remember session
	var $session_expire = 10200;

	function __construct() {
		parent::__construct();
	}

	/***********************************************************************/

	function save_log($save)
	{
		$this->db->insert('log',$save);
	}
}
?>