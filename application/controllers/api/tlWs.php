<?php

//bcm
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
 */
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class TlWs extends REST_Controller {

// Constructor function
    public function __construct() {
        parent::__construct();
        //$this->load->library('rest');
        //$this->load->library('curl');
        //$this->load->library('format');
        $this->load->model(array('Outlet_model', 'Zone_model', 'Channel_model', 'State_model', 'Visit_model', 'Brand_model', 'Product_model', 'Admin_model', 'Dashboard_model', 'Tl_visit_model', 'Report_model'));
        $this->load->helper(array('form', 'date'));
    }

//**********************************************************************************************************
//*********************************************indice_dashboard*************************************************************
    function zone_name_get() {
        $outlet_id = $this->get('outlet_id');
        if (!$outlet_id)
            $outlet_id = -1;

        $zone_id = $this->Outlet_model->get_outlet_zone_id($outlet_id);
        $zone_name = $this->Zone_model->get_zone_name($zone_id);

        if ($zone_name) {
            $this->response($zone_name, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function state_name_get() {
        $outlet_id = $this->get('outlet_id');
        if (!$outlet_id)
            $outlet_id = -1;

        $state_name = $this->Outlet_model->get_outlet_state_id($outlet_id);
        //$state_name = $this->State_model->get_state_name($state_id);

        if ($state_name) {
            $this->response($state_name, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function count_active_outlets_get() {
        $nb_active_outlets = $this->Outlet_model->count_active_outlets();

        if ($nb_active_outlets) {
            $this->response($nb_active_outlets, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function count_outlets_get() {
        $all_outlets = $this->Outlet_model->count_outlets();

        if ($all_outlets) {
            $this->response($all_outlets, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function count_daily_visits_get() {
        $count_daily_visits = $this->Dashboard_model->count_daily_visits();
        if ($count_daily_visits) {
            $this->response($count_daily_visits, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function count_daily_target_get() {
        $date_lib = date('l', strtotime('today'));
        $count_daily_target = $this->Dashboard_model->count_daily_target($date_lib);
        if ($count_daily_target) {
            $this->response($count_daily_target, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function count_month_visits_get() {
        $nb_month = $this->Dashboard_model->count_month_visits();
        if ($nb_month) {
            $this->response($nb_month, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function outlets_by_id_get() {
        $fo = $this->get('fo');

        $outlets = $this->Outlet_model->get_outlets_by_id($fo);
        if ($outlets) {
            $this->response($outlets, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

//**********************************************************************************************************
//*********************************************Visit_Fo*************************************************************


    function fos_get() {

        $fos = $this->auth->get_fo_list();

        if ($fos) {
            $this->response($fos, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function count_visits_search_get() {
        $search = $this->get('search');
        $fo_id = $this->get('fo_id');
        $start_date = $this->get('start_date');
        $end_date = $this->get('end_date');
        $visit_type = $this->get('visit_type');

        if (!$search)
            $search = -1;

        if (!$fo_id)
            $fo_id = -1;

        if (!$start_date)
            $start_date = -1;

        if (!$end_date)
            $end_date = -1;

        if (!$visit_type)
            $visit_type = -1;

        $nb_visits = $this->Visit_model->count_visits_search($search, $fo_id, $start_date, $end_date, $visit_type);
        if ($nb_visits) {
            $this->response($nb_visits, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function count_visit_get() {
        $search = $this->get('search');
        if (!$search)
            $search = -1;
        $nb_visits = $this->Visit_model->count_visits(-1, $search);
        if ($nb_visits) {
            $this->response($nb_visits, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function visits_get() {

        $per_page = $this->get('per_page');
        $page = $this->get('page');
        $order_by = $this->get('orders_by');
        $direction = $this->get('directions');
        if (empty($order_by)) {
            $order_by = 'visits.id';
        }
        if (!$direction) {
            $direction = 'DESC';
        }
        $search = $this->get('search');
        if (!$search)
            $search = -1;

        $visits = $this->Visit_model->get_visits($per_page, $page, $order_by, $direction, '-1', $search);

        if ($visits) {
            $this->response($visits, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function visits_search_get() {

        $per_page = $this->get('per_page');
        $page = $this->get('page');
        $order_by = $this->get('orders_by');
        $direction = $this->get('directions');

        $search = $this->get('search');
        $fo_id = $this->get('fo_id');
        $start_date = $this->get('start_date');
        $end_date = $this->get('end_date');
        $visit_type = $this->get('visit_type');


        if (empty($order_by)) {
            $order_by = 'visits.id';
        }
        if (!$direction) {
            $direction = 'DESC';
        }

        if (!$search)
            $search = -1;

        if (!$fo_id)
            $fo_id = -1;

        if (!$start_date)
            $start_date = -1;

        if (!$end_date)
            $end_date = -1;

        if (!$visit_type)
            $visit_type = -1;

        $visits = $this->Visit_model->get_visits_search($per_page, $page, $order_by, $direction, $search, $fo_id, $start_date, $end_date, $visit_type);

        if ($visits) {
            $this->response($visits, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    //******************************************TL_VISITS****************************************************

    function nb_visits_get() {
        $month = $this->get('month');
        if (!$month)
            $month = date('Y-m-01');

        $nb_visits = $this->Tl_visit_model->get_nb_visits($month);
        if ($nb_visits) {
            $this->response($nb_visits, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function visits_tl_search_get() {

        $per_page = $this->get('per_page');
        $page = $this->get('page');
        $order_by = $this->get('orders_by');
        $direction = $this->get('directions');

        $search = $this->get('search');
        $start_date = $this->get('start_date');
        $end_date = $this->get('end_date');
        $visit_type = $this->get('visit_type');


        if (empty($order_by)) {
            $order_by = 'tl_visits.id';
        }
        if (!$direction) {
            $direction = 'DESC';
        }

        if (!$search)
            $search = -1;

        if (!$start_date)
            $start_date = '';

        if (!$end_date)
            $end_date = '';

        if (!$visit_type)
            $visit_type = -1;

        $visits = $this->Tl_visit_model->get_visits_search($per_page, $page, $order_by, $direction, $search, $start_date, $end_date, $visit_type);

        if ($visits) {
            $this->response($visits, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function count_visits_tl_search_get() {
        $search = $this->get('search');
        $start_date = $this->get('start_date');
        $end_date = $this->get('end_date');
        $visit_type = $this->get('visit_type');

        if (!$search)
            $search = -1;

        if (!$start_date)
            $start_date = '';

        if (!$end_date)
            $end_date = '';

        if (!$visit_type)
            $visit_type = -1;

        $nb_visits = $this->Tl_visit_model->count_visits_search($search, $start_date, $end_date, $visit_type);
        if ($nb_visits) {
            $this->response($nb_visits, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function inetrventions_get() {

        $visit_id = $this->get('visit_id');
        if (!$visit_id)
            $visit_id = -1;

        $interventions = $this->Tl_visit_model->get_interventions_by_visit_id($visit_id);
        if ($interventions) {
            $this->response($interventions, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function visit_tl_get() {
        $visit_id = $this->get('visit_id');
        if (!$visit_id)
            $visit_id = -1;

        $visit = $this->Tl_visit_model->get_visit($visit_id);
        if ($visit) {
            $this->response($visit, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function action_get() {
        $action_id = $this->get('action_id');
        if (!$action_id)
            $action_id = -1;

        $action = $this->Tl_visit_model->get_action_by_id($action_id);
        if ($action) {
            $this->response($action, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function nb_intervention_get() {
        $visit_type = $this->get('visit_type');
        if (!$visit_type)
            $visit_type = -1;

        $tab_type = $this->get('tab_type');
        if (!$tab_type)
            $tab_type = -1;

        $month = $this->get('month');
        if (!$month)
            $month = date('Y-m-01');


        $data = $this->Tl_visit_model->get_nb_intervention($visit_type, $tab_type, $month);
        if ($data) {
            $this->response($data, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function nb_action_get() {
        $month = $this->get('month');
        if (!$month)
            $month = date('Y-m-01');

        $data = $this->Tl_visit_model->get_nb_action($month);
        if ($data) {
            $this->response($data, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function visits_today_for_map_get() {
        $visit = $this->Tl_visit_model->get_visits_today_for_map();
        if ($visit) {
            $this->response($visit, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function feeds_of_control_visit_get() {
        $month = $this->get('month');
        if (!$month)
            $month = date('Y-m-01');
        $feeds = $this->Tl_visit_model->get_feeds_of_control_visit($month);
        if ($feeds) {
            $this->response($feeds, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function feeds_of_new_visit_get() {
        $month = $this->get('month');
        if (!$month)
            $month = date('Y-m-01');
        $feeds = $this->Tl_visit_model->get_feeds_of_new_visit($month);
        if ($feeds) {
            $this->response($feeds, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    //**************************FO_information

    function fo_information_get() {
        $events = $this->Report_model->get_events();
        if ($events) {
            $this->response($events, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function events_details_by_date_get() {
        $date = $this->get('date');
        if (!$date)
            $date = -1;

        $events = $this->Report_model->get_events_details_by_date($date);
        if ($events) {
            $this->response($events, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function fo_name_get() {
        $fo_id = $this->get('fo_id');
        if (!$fo_id)
            $fo_id = -1;

        $fo_name = $this->Admin_model->get_admin_name($fo_id);
        if ($fo_name) {
            $this->response($fo_name, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function zones_get() {
        $zones = $this->Zone_model->get_zones();
        if ($zones) {
            $this->response($zones, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function channels_get() {
        $channels = $this->Channel_model->get_channels();
        if ($channels) {
            $this->response($channels, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function tracking_poss_get() {

        $start_date = $this->get('start_date');
        $end_date = $this->get('end_date');
        $zone = $this->get('zone');
        $channel = $this->get('channel');

        if (!$start_date)
            $start_date = -1;

        if (!$end_date)
            $end_date = -1;

        if (!$zone)
            $zone = -1;

        if (!$channel)
            $channel = -1;
//echo $start_date;
//echo $end_date;
//echo $zone;
//echo $channel;
//die();

        $tracking_pos = $this->Tl_visit_model->get_tracking_pos_for_excel($start_date, $end_date, $channel, $zone);

        if ($tracking_pos) {
            $this->response($tracking_pos, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function tracking_nan_poss_get() {
        $start_date = $this->get('start_date');
        $end_date = $this->get('end_date');
        $zone = $this->get('zone');
        $channel = $this->get('channel');

        if (!$start_date)
            $start_date = -1;

        if (!$end_date)
            $end_date = -1;

        if (!$zone)
            $zone = -1;

        if (!$channel)
            $channel = -1;
//echo $start_date;
//echo $end_date;
//echo $zone;
//echo $channel;
//die();

        $tracking_pos = $this->Tl_visit_model->get_not_tracking_pos_for_excel($start_date, $end_date, $channel, $zone);

        if ($tracking_pos) {

            $this->response($tracking_pos, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function detail_models_get() {
        $fo_visit_id = $this->get('visit_id');
        $result = $this->Visit_model->get_detail_models($fo_visit_id);
        if ($result) {
            $this->response($result, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function detail_daily_models_get() {
        $fo_visit_id = $this->get('visit_id');
        $result = $this->Visit_model->get_detail_daily_models($fo_visit_id);
        if ($result) {
            $this->response($result, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

    function pictures_get() {
        $fo_visit_id = $this->get('visit_id');
        $result = $this->Visit_model->get_visit($fo_visit_id);
        if ($result) {
            $this->response($result, 200); // 200 being the HTTP response code
        } else {
            $this->response(404);
        }
    }

}
