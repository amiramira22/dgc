<?php

class Geo extends CI_Controller {

    //this is used when editing or adding a customer

    function __construct() {
        parent::__construct();

        $this->load->library('Auth');
    }

    function index() {



        $admin = $this->admin_session->userdata('admin');
        $admin_id = $admin['id'];
        $save['id'] = $admin_id;
        $save['latitude'] = $_POST['x'];
        $save['longitude'] = $_POST['y'];

        $this->auth->update($save);
    }

    function test1() {

        $admin = $this->admin_session->userdata('admin');
        $admin_id = $admin['id'];
        $save['id'] = $admin_id;
        $save['latitude'] = $_POST['lat'];
        $save['longitude'] = $_POST['lng'];

        $this->auth->update($save);
    }

    function pos() {

        $admin = $this->session->userdata('admin');
        $admin_id = $admin['id'];
        $save['id'] = $admin_id;
        $save['latitude'] = $_POST['lat'];
        $save['longitude'] = $_POST['lng'];

        $this->auth->update($save);
    }

}
