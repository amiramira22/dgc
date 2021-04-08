<?php

class Visits extends CI_Controller {

	//this is used when editing or adding a customer
	var $visit_id = false;

	function __construct() {
		parent::__construct();

		$this -> load -> model(array('Outlet_model', 'Zone_model','New_models_model' ,'Visit_model',
                    'Admin_model', 'Brand_model', 'Visit_model_model', 'Model_model', 'Report_model','City_model','Competitor_ads_model'));
		$this -> load -> helper('formatting_helper');
		$this -> lang -> load('visit');
	}

	function index($field = 'id', $by = 'DESC', $page = 0) {
		//we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
		//$this->session->set_flashdata('message', 'this is our message');
		//$this->output->cache(3600);
		$this -> load -> helper(array('form', 'date'));
		$this -> load -> library('form_validation');
		$data['page_title'] = lang('visits');

		$data['zones'] = $this -> Zone_model -> get_zones();
		$date = $this -> input -> post('date');
		$zone_id = $this -> input -> post('zone_id');

		$date = $this -> input -> post('date');

		//echo 'date:'.$date.'-zone_id:'.$zone_id.'-chef_id:'.$chef_id;

		$admin = $this -> admin_session -> userdata('admin');

		$admin_id = $admin['id'];

		if ($this -> auth -> check_access('Admin') || $this -> auth -> check_access('Samsung')) {
			if ($date != '' && $zone_id == -1) {

				$data['visits'] = $this -> Visit_model -> get_visits_by_date(100, $page, 'id', $by, $date);

			} else if ($zone_id != -1 && $zone_id != '' && $date == '') {

				$data['visits'] = $this -> Visit_model -> get_visits_by_zone(200, $page, 'id', $by, $zone_id);
			} else if ($zone_id != -1 && $zone_id != '' && $date != '') {

				$data['visits'] = $this -> Visit_model -> get_visits_by_date_zone(100, $page, 'id', $by, $date, $zone_id);
			} else {

				$data['visits'] = $this -> Visit_model -> get_visits(300, $page, 'id', $by);
			}

		} else {

			$data['visits'] = $this -> Visit_model -> get_visits_by_id(50, $page, $field, $by, $admin_id);
		}

		$this -> load -> library('pagination');

		$config['base_url'] = base_url() . '/' . $this -> config -> item('admin_folder') . '/visits/index/' . $field . '/' . $by . '/';
		$config['total_rows'] = $this -> Visit_model -> count_visits();
		$config['per_page'] = 2000;
		$config['uri_segment'] = 6;
		$config['first_link'] = 'First';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['full_tag_open'] = '<div class="pagination"><ul>';
		$config['full_tag_close'] = '</ul></div>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';

		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$config['prev_link'] = '&laquo;';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		$config['next_link'] = '&raquo;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$this -> pagination -> initialize($config);

		$data['page'] = $page;
		$data['field'] = $field;
		$data['by'] = $by;

		$this -> load -> view($this -> config -> item('admin_folder') . '/visits', $data);
	}

	function form($id = false) {
		force_ssl();

		$this -> load -> helper(array('form', 'date'));
		$this -> load -> library('form_validation');

		$data['page_title'] = lang('visit_form');

		$admin = $this -> admin_session -> userdata('admin');

		$admin_id = $admin['id'];

		if ($this -> auth -> check_access('Field Officer')) {
			$data['outlets'] = $this -> Outlet_model -> get_outlets_by_id($admin_id);
			$data['admins'] = $this -> auth -> get_admin_by_id($admin_id);
		} else {
			$data['outlets'] = $this -> Outlet_model -> get_outlets();
			$data['admins'] = $this -> auth -> get_admin_list();
		}

		//default values are empty if the modern_visit is new
		$data['id'] = '';
		$data['date'] = '';
		$data['entry_time'] = '';
		$data['exit_time'] = '';
		$data['modified'] = '';
		$data['admin_id'] = '';
		$data['outlet_id'] = '';
		$data['remark'] = '';
		$data['active'] = false;

		if ($id) {
			$this -> visit_id = $id;
			$visit = $this -> Visit_model -> get_visit($id);
			//if the modern_visit does not exist, redirect them to the customer list with an error
			if (!$visit) {
				$this -> session -> set_flashdata('error', lang('error_not_found'));
				redirect($this -> config -> item('admin_folder') . '/visits');
			}

			//set values to db values
			$data['id'] = $visit -> id;
			$data['date'] = $visit -> date;
			$data['entry_time'] = $visit -> entry_time;
			$data['exit_time'] = $visit -> exit_time;
			$data['modified'] = $visit -> modified;
			$data['admin_id'] = $visit -> admin_id;
			$data['outlet_id'] = $visit -> outlet_id;
			$data['remark'] = $visit -> remark;
			$data['active'] = $visit -> active;

		}

		$this -> form_validation -> set_rules('active', 'lang:active');

		if ($this -> form_validation -> run() == FALSE) {
			$this -> load -> view($this -> config -> item('admin_folder') . '/visit_form', $data);
		} else {
			$save['id'] = $id;

			$save['date'] = date("Y-m-d", strtotime($this -> input -> post('date')));
			$save['entry_time'] = $this -> input -> post('entry_time');
			$save['exit_time'] = $this -> input -> post('exit_time');
			$save['admin_id'] = $this -> input -> post('admin_id');
			$save['outlet_id'] = $this -> input -> post('outlet_id');
			$save['remark'] = $this -> input -> post('remark');
			$save['active'] = $this -> input -> post('active');

			$this -> Visit_model -> save($save);

			$this -> session -> set_flashdata('message', lang('message_saved_visit'));

			//go back to the modern_visit list
			redirect($this -> config -> item('admin_folder') . '/visits');
		}
	}

	function copy($id = false) {

		$data['id'] = '';
		$data['date'] = '';
		$data['entry_time'] = '';
		$data['exit_time'] = '';
		$data['modified'] = '';
		$data['admin_id'] = '';
		$data['outlet_id'] = '';
		$data['remark'] = '';
		$data['active'] = false;

		$visit = $this -> Visit_model -> get_visit($id);
		$data['id'] = false;
		$data['date'] = $visit -> date;
		$data['entry_time'] = $visit -> entry_time;
		$data['exit_time'] = $visit -> exit_time;
		$data['modified'] = $visit -> modified;
		$data['admin_id'] = $visit -> admin_id;
		$data['outlet_id'] = $visit -> outlet_id;
		$data['remark'] = $visit -> remark;
		$data['active'] = $visit -> active;

		$this -> Visit_model -> copy($data, $id);

		$this -> session -> set_flashdata('message', lang('message_saved_visit'));

		//go back to the modern_visit list
		redirect($this -> config -> item('admin_folder') . '/visits');

	}

	function delete($id = false) {
		if ($id) {
			$visit = $this -> Visit_model -> get_visit($id);
			//if the modern_visit does not exist, redirect them to the modern_visit list with an error
			if (!$visit) {
				$this -> session -> set_flashdata('error', lang('error_not_found'));
				redirect($this -> config -> item('admin_folder') . '/visits');
			} else {
				//if the modern_visit is legit, delete them

				$this -> Visit_model_model -> delete_by_visit($id);
				$this -> Visit_model_model -> delete_shortage_by_visit($id);

				$delete = $this -> Visit_model -> delete($id);

				$this -> session -> set_flashdata('message', lang('message_visit_deleted'));
				redirect($this -> config -> item('admin_folder') . '/visits');
			}
		} else {
			//if they do not provide an id send them to the modern_visit list page with an error
			$this -> session -> set_flashdata('error', lang('error_not_found'));
			redirect($this -> config -> item('admin_folder') . '/visits');
		}
	}

	function report($visit_id = false) {
		$this -> load -> helper(array('form', 'date'));
		$data['brands'] = $this -> Brand_model -> get_brands_by_code();
		$data['visit_id'] = $visit_id;
		$data['id'] = $visit_id;

		$data['outlet_id'] = $this -> Visit_model -> get_outlet_id($visit_id);
		$data['date'] = format_week($this -> Visit_model -> get_visit_date($visit_id));
		$data['outlet_name'] = $this -> Outlet_model -> get_outlet_name($data['outlet_id']);
		$data['page_title'] = "Weekly Report | " . $data['outlet_name'] . " | " . $data['date'];
		$this -> load -> view($this -> config -> item('admin_folder') . '/visit_report', $data);
	}

	function models($visit_id = false) {
		//$this->output->cache(3600);
		force_ssl();
		$this -> load -> helper(array('form', 'date'));
		$this -> load -> library('form_validation');
		//$data['model_product'] = $this -> Product_model;

		$data['brands'] = $this -> Brand_model -> get_brands_by_code();

		$data['visit_id'] = $visit_id;
		$data['outlet_id'] = $this -> Visit_model -> get_outlet_id($visit_id);
		$data['date'] = format_week($this -> Visit_model -> get_visit_date($visit_id));
		$data['outlet_name'] = $this -> Outlet_model -> get_outlet_name($data['outlet_id']);
		$data['page_title'] = "Weekly Visit | " . $data['outlet_name'] . " | " . $data['date'];
		//$data['modern_vis_models'] = $this -> Modern_vis_model_model -> get_modern_vis_models($visit_id);

		//print_r($data['modern_vis_models']);

		$data['id'] = $visit_id;

		$this -> load -> view($this -> config -> item('admin_folder') . '/visit_model_form', $data);
	}

	function shortage($visit_id = false) {
		//$this->output->cache(3600);
		force_ssl();
		$this -> load -> helper(array('form', 'date'));
		$this -> load -> library('form_validation');
		//$data['model_product'] = $this -> Product_model;

		$data['brands'] = $this -> Brand_model -> get_brands_by_code();

		$data['visit_id'] = $visit_id;
		$data['outlet_id'] = $this -> Visit_model -> get_outlet_id($visit_id);
		$data['date'] = format_week($this -> Visit_model -> get_visit_date($visit_id));
		$data['outlet_name'] = $this -> Outlet_model -> get_outlet_name($data['outlet_id']);
		$data['page_title'] = "Shortage | " . $data['outlet_name'] . " | " . $data['date'];
		$data['models'] = $this -> Visit_model_model -> get_shortage_models($visit_id);

		//print_r($data['modern_vis_models']);

		$data['id'] = $visit_id;

		$this -> load -> view($this -> config -> item('admin_folder') . '/visit_shortage_form', $data);
	}

	function brands($visit_id = false) {
		//$this->output->cache(3600);
		force_ssl();
		$this -> load -> helper(array('form', 'date'));
		$this -> load -> library('form_validation');
		//$data['model_product'] = $this -> Product_model;

		$data['brands'] = $this -> Brand_model -> get_brands_by_code();

		$data['visit_id'] = $visit_id;
		$data['outlet_id'] = $this -> Visit_model -> get_outlet_id($visit_id);
		$data['date'] = format_week($this -> Visit_model -> get_visit_date($visit_id));
		$data['outlet_name'] = $this -> Outlet_model -> get_outlet_name($data['outlet_id']);
		$data['page_title'] = "Weekly Visit | " . $data['outlet_name'] . " | " . $data['date'];
		//$data['modern_vis_models'] = $this -> Modern_vis_model_model -> get_modern_vis_models($visit_id);

		//print_r($data['modern_vis_models']);

		$data['id'] = $visit_id;

		$this -> load -> view($this -> config -> item('admin_folder') . '/visit_brand_form', $data);
	}

	function specific_models($visit_id = false, $brand_id = false) {
		force_ssl();
		$this -> load -> helper(array('form', 'date'));
		$this -> load -> library('form_validation');

		$data['visit_id'] = $visit_id;
		$data['brand_id'] = $brand_id;
		$data['outlet_id'] = $this -> Visit_model -> get_outlet_id($visit_id);

		$brand_name = $this -> Brand_model -> get_brand_name($brand_id);

		$data['brand_name'] = $brand_name;

		$data['page_title'] = "Weekly Visit | " . $brand_name;

		$this -> load -> view($this -> config -> item('admin_folder') . '/visit_model_form1', $data);
	}

	function bulk_save($visit_id) {
		$models = $this -> input -> post('model');

		if (!$models) {
			$this -> session -> set_flashdata('error', lang('error_bulk_no_models'));
			redirect($this -> config -> item('admin_folder') . '/visits');
		}

		foreach ($models as $id => $model) {
			$model['id'] = $id;
			$this -> Visit_model_model -> save_bulk($model);

		}

		//$this -> session -> set_flashdata('message', lang('message_bulk_update'));
		//redirect($this -> config -> item('admin_folder') . '/visits');
		echo "<script>window.close();</script>";
	}

	function shortage_bulk_save($visit_id) {
		$models = $this -> input -> post('model');

		if (!$models) {
			$this -> session -> set_flashdata('error', lang('error_bulk_no_models'));
			redirect($this -> config -> item('admin_folder') . '/visits');
		}

		foreach ($models as $id => $model) {
			$model['id'] = $id;
			$this -> Visit_model_model -> shortage_save_bulk($model);

		}

		$this -> session -> set_flashdata('message', lang('message_bulk_update'));
		redirect($this -> config -> item('admin_folder') . '/visits');
		//echo "<script>window.close();</script>";
	}

	function bulk_activate() {
		$visits = $this -> input -> post('visit');

		if ($visits) {
			foreach ($visits as $visit) {
				$this -> Visit_model -> activate($visit);
			}
			$this -> session -> set_flashdata('message', lang('message_visits_activated'));
		} else {
			$this -> session -> set_flashdata('error', lang('error_no_visits_selected'));
		}
		//redirect as to change the url
		redirect($this -> config -> item('admin_folder') . '/visits');
	}

	function add_model($visit_id = false) {
		force_ssl();

		$this -> load -> helper(array('form', 'date'));
		$this -> load -> library('form_validation');

		$data['page_title'] = 'Add New Model';

		$data['model_ids'] = $this -> Model_model -> get_new_models($visit_id);
		$data['visit_id'] = $visit_id;

		$this -> form_validation -> set_rules('shelf', 'lang:shelf');

		if ($this -> form_validation -> run() == FALSE) {
			$this -> load -> view($this -> config -> item('admin_folder') . '/add_model_form', $data);
		} else {

			$model_id = $this -> input -> post('model_id');
			$shelf = $this -> input -> post('shelf');
			$ws = $this -> input -> post('ws');
			$price = $this -> input -> post('price');
			$brand_id = $this -> Model_model -> get_model_brand($model_id);
			$category_id = $this -> Model_model -> get_model_category($model_id);
			$range_id = $this -> Model_model -> get_model_range1($model_id);
			$price_range_id = $this -> Model_model -> get_model_price_range($model_id);

			$save['visit_id'] = $visit_id;
			$save['model_id'] = $model_id;
			$save['brand_id'] = $brand_id;
			$save['category_id'] = $category_id;
			$save['range_id'] = $range_id;
			$save['price_range_id'] = $price_range_id;
			$save['shelf'] = $shelf;
			$save['ws'] = $ws;
			$save['price'] = $price;
			$save['amount'] = $shelf * $ws;

			$this -> Visit_model_model -> save_single($save);

			$this -> session -> set_flashdata('message', lang('message_saved_visit'));

			//go back to the visit list
			redirect($this -> config -> item('admin_folder') . '/visits');
		}
	}

	function update_models() {
		//$this->output->cache(3600);

		$models = $this -> Visit_model_model -> get_all_models();

		foreach ($models as $model) {
			print_r($model);
			echo '*******************************************************************';
			$this -> Visit_model_model -> update($model);
		}

	}

	function up_price() {

		$this -> Visit_model_model -> update_mod();
	}

	function shortage_report($visit_id) {
		$this -> load -> helper(array('form', 'date'));
		$data = array();

		$data['outlet_id'] = $this -> Visit_model -> get_outlet_id($visit_id);
		$data['date'] = format_week($this -> Visit_model -> get_visit_date($visit_id));
		$data['outlet_name'] = $this -> Outlet_model -> get_outlet_name($data['outlet_id']);
		$data['page_title'] = "Shortage Report | " . $data['outlet_name'] . " | " . format_week($data['date']);

		$data['visit_id'] = $visit_id;
	

		$this -> load -> view($this -> config -> item('admin_folder') . '/shortage_report', $data);
	}

}
