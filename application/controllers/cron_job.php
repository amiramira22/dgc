<?php

//HCM CRON JOB 
class Cron_job extends CI_Controller {

//these are used when editing, adding or deleting an admin
    var $admin_id = false;
    var $current_admin = false;

    function __construct() {
        parent::__construct();
//$this->auth->check_access('Admin', true);
//load the admin language file in
        $this->lang->load('admin');
        $this->load->helper('form');
        $this->load->helper('date');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $this->load->model(array('Channel_model', 'Product_model', 'Cluster_model', 'Sub_category_model',
            'Visit_model', 'Outlet_model', 'Brand_model', 'Admin_model'
            , 'State_model', 'Zone_model', 'Cron_model', 'Report_model'));
        $this->current_admin = $this->session->userdata('admin');
    }

    function index() {
        $save = array();

        $admins = $this->auth->get_fo_list();

        foreach ($admins as $admin) {

            $admin_id = $admin->id;
            $date = date('Y-m-d');
            $w_date = firstDayOf('week', new DateTime($date));
            $m_date = firstDayOf('month', new DateTime($date));

            $visits = $this->Cron_model->get_visits_by_admin($admin_id, $date);
            $nb_visits = sizeof($visits);
            $worked_time = 0;
            $was_there = 1;
            if ($nb_visits != 0) {
                $uhd = $this->Cron_model->get_oos($admin_id, $date, 1)->oos;
                $gemo = $this->Cron_model->get_oos($admin_id, $date, 2)->oos;
                $mg = $this->Cron_model->get_oos($admin_id, $date, 3)->oos;

                $entry_time = $visits[0]->entry_time;
                $exit_time = $visits[$nb_visits - 1]->exit_time;
                if ($exit_time == '')
                    $exit_time = $visits[$nb_visits - 1]->system_exit_time;
                if ($visits[0]->was_there == 0) {
                    $was_there = 0;
                }

                /*
                  if ($exit_time < $entry_time) {
                  echo 'exit';
                  $aux = $entry_time;
                  $entry_time = $exit_time;
                  $exit_time = $aux;
                  } else {
                  echo 'entry';
                  }
                 */

                $entryInSeconds = strtotime($entry_time) - strtotime('TODAY');
                $exitInSeconds = strtotime($exit_time) - strtotime('TODAY');
//$avg=($entryInSeconds+$exitInSeconds)/2;
// $hours = floor($avg / 3600);
//$mins = floor($avg / 60 % 60);
//$secs = floor($avg % 60);
// print_r($hours.':'.$mins.':'.$secs);
//  die();

                foreach ($visits as $visit) {
                    if ($visit->worked_time == '') {
                        $visit_worked_time = (strtotime($visit->system_exit_time) - strtotime($visit->entry_time)) * 1000;
                    } else
                        $visit_worked_time = $visit->worked_time;

                    $worked_time = $worked_time + $visit_worked_time;
                    $nb_branding = count(json_decode($visit->branding_pictures));
                    $total_branding = $total_branding + $nb_branding;
                }


                $worked_time = $worked_time / 1000;
                $save['id'] = null;
                $save['admin_id'] = $admin_id;
//entry time in secondes
                $save['entry_time'] = $entryInSeconds;
//exit time in secondes
                $save['exit_time'] = $exitInSeconds;
                $save['entry'] = $entry_time;
                $save['exit'] = $exit_time;
                $save['date'] = $date;
                $save['w_date'] = $w_date;
                $save['m_date'] = $m_date;

//working hours in secondes
                $save['working_hours'] = $worked_time;
// travel hours in seconds

                if ($nb_visits > 1) {
                    $save['travel_hours'] = ($exitInSeconds - $entryInSeconds) - $worked_time;
                } else {
                    $save['travel_hours'] = 0;
                }
                $save['gemo'] = $gemo;
                $save['uhd'] = $uhd;
                $save['mg'] = $mg;

                $save['nb_visits'] = $nb_visits;
                $save['total_branding'] = $total_branding;

                $save['was_there'] = $was_there;
                $this->Cron_model->save($save);
            }

            print_r($save);
            echo '<br>*********************</br>';
        }
    }

//gdi
    function performance($date = false) {
        $save = array();
        //$date = date('Y-m-d');

        $admins = $this->auth->get_fo_list();
        foreach ($admins as $admin) {
            $total_branding = 0;
            $admin_id = $admin->id;
            //$date = date('2017-11-29');
            $w_date = firstDayOf('week', new DateTime($date));
            $m_date = firstDayOf('month', new DateTime($date));

            $visits = $this->Cron_model->get_visits_by_admin($admin_id, $date);

            $nb_visits = sizeof($visits);
            $worked_time = 0;
            $was_there = 1;
            if ($nb_visits != 0) {
                $uhd = $this->Cron_model->get_oos($admin_id, $date, 1)->oos;
                $gemo = $this->Cron_model->get_oos($admin_id, $date, 2)->oos;
                $mg = $this->Cron_model->get_oos($admin_id, $date, 3)->oos;


                $entry_time = $visits[0]->entry_time;
                $exit_time = $visits[$nb_visits - 1]->exit_time;

                if ($visits[0]->was_there == 0) {
                    $was_there = 0;
                }

                /*
                  if ($exit_time < $entry_time) {
                  echo 'exit';
                  $aux = $entry_time;
                  $entry_time = $exit_time;
                  $exit_time = $aux;
                  } else {
                  echo 'entry';
                  }

                 */

                $entryInSeconds = strtotime($entry_time) - strtotime('TODAY');
                $exitInSeconds = strtotime($exit_time) - strtotime('TODAY');

                foreach ($visits as $visit) {
                    $worked_time = $worked_time + $visit->worked_time;
                    $nb_branding = count(json_decode($visit->branding_pictures));
                    $total_branding = $total_branding + $nb_branding;
                }

                $worked_time = $worked_time / 1000;

                $save['id'] = false;
                $save['admin_id'] = $admin_id;
//entry time in secondes
                $save['entry_time'] = $entryInSeconds;
//exit time in secondes
                $save['exit_time'] = $exitInSeconds;
                $save['entry'] = $entry_time;
                $save['exit'] = $exit_time;
                $save['date'] = $date;
                $save['w_date'] = $w_date;
                $save['m_date'] = $m_date;
//working hours in secondes
                $save['working_hours'] = $worked_time;
// travel hours in seconds
                if ($nb_visits > 1) {

                    $save['travel_hours'] = ($exitInSeconds - $entryInSeconds) - $worked_time;
                } else {
                    $save['travel_hours'] = 0;
                }
                $save['gemo'] = $gemo;
                $save['uhd'] = $uhd;
                $save['mg'] = $mg;


                $save['nb_visits'] = $nb_visits;
                $save['total_branding'] = $total_branding;
                $save['was_there'] = $was_there;
                $this->Cron_model->save($save);
            }
            print_r($save);
            echo '<br>*********************</br>';
        }
    }

//hcm
    function up_monthly_visits() {
        ini_set('memory_limit', -1);

        $this->db->select(''
                . 'sum(bcc_models.shelf) as sum_shelf,'
                . 'sum(bcc_models.price) as sum_price,'
                . 'visits.*', false);

        $this->db->from('visits');
        $this->db->join('models', 'models.visit_id=visits.id');

        $this->db->group_by('models.visit_id');

        $result = $this->db->get();
        $visits = $result->result();

        foreach ($visits as $visit) {
            if ($visit->sum_shelf > 0 && $visit->sum_price == 0) {
                $save_shelf['id'] = $visit->id;
                $save_shelf['monthly_visit'] = 1;
                $this->Visit_model->save($save_shelf);
//print_r($save_shelf);
            } else if ($visit->sum_shelf > 0 && $visit->sum_price > 0) {
                $save_shelf_price['id'] = $visit->id;
                $save_shelf_price['monthly_visit'] = 3;
                $this->Visit_model->save($save_shelf_price);
            } else if ($visit->sum_shelf == 0 && $visit->sum_price > 0) {
                $save_price['id'] = $visit->id;
                $save_price['monthly_visit'] = 2;
                $this->Visit_model->save($save_price);
            } else {
                $save_av['id'] = $visit->id;
                $save_av['monthly_visit'] = 0;
                $this->Visit_model->save($save_av);
            }
            echo $visit->id . '---' . $visit->sum_shelf . '***' . $visit->sum_price . '</br></br>';
        }
    }

    function update_tracking_oos($date = false) {
//2018-01-01 2018-01-31
//$products = $this->Product_model->get_all_active_products();
//        for ($i = $debut; $i <= $fin; $i++) {
//            if ($i == 1 || $i == 2 || $i == 3 || $i == 4 || $i == 5 || $i == 6 || $i == 7 || $i == 8 || $i == 9)
//                $i = '0' . $i;
//
//            $date = '2018-' . $m . '-' . $i;
//            echo $date;
//            echo "<br>";
//foreach ($products as $pr) {
        //$date = date('Y-m-d');
        $this->db->select('visits.date as date,
                          models.product_id as product_id,
                          visits.outlet_id as outlet_id,
                          models.av as av', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', ' outlets.id = visits.outlet_id');
        $this->db->join('products', 'models.product_id=products.id');
        $this->db->join('product_groups', 'products.product_group_id=product_groups.id');
        $this->db->join('brands', ' product_groups.brand_id=brands.id');

        $this->db->where('outlets.active', 1);
        $this->db->where('products.active', 1);

        $this->db->where('visits.date = ', $date);
//$this->db->where('products.id', $pr->id);
        $this->db->where('brands.id', 1);
        $this->db->where('visits.monthly_visit', 0);
        $this->db->where('models.product_id is NOT NULL', NULL, FALSE);

        $results = $this->db->get()->result();
        if (!empty($results)) {
            foreach ($results as $r) {
                $save['product_id'] = $r->product_id;
                $save['outlet_id'] = $r->outlet_id;
                $save['av'] = $r->av;
                $save['date'] = $r->date;
                $save['w_date'] = firstDayOf('week', new DateTime($r->date));
                $save['m_date'] = firstDayOf('month', new DateTime($r->date));
//                    print_r($save);
//                    echo'<br>';
//                    echo'<br>';
                $this->Report_model->save_oos_tracking($save);
            }
        }
    }

}
