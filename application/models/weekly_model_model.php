<?php

Class Weekly_model_model extends CI_Model {

    //this is the expiration for a non-remember session
    var $session_expire = 14200;

    function __construct() {
        parent::__construct();
    }

    /*     * ******************************************************************

     * ****************************************************************** */

    function count_models() {
        return $this->db->count_all_results('weekly_models');
    }

    function get_sum_by_week() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    ////sales by brand
    function get_samsung_sales() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_huwawi_sales() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('brand_id =', 10);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_evertek_sales() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('brand_id =', 4);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_lenovo_sales() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('brand_id =', 21);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_others_sales() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $br = array(1, 10, 4, 21);
        $this->db->where_not_in('brand_id', $br);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    ////fin sales by brand
    ////sales by brand smart

    function get_samsung_smart_sales() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');

        $this->db->where('range2', 'Smart Phone');
        $this->db->where('test_weekly_models.brand_id', 1);

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_huwawi_smart_sales() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');

        $this->db->where('range2', 'Smart Phone');
        $this->db->where('test_weekly_models.brand_id', 10);

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_evertek_smart_sales() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');

        $this->db->where('range2', 'Smart Phone');
        $this->db->where('test_weekly_models.brand_id', 4);

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_lenovo_smart_sales() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');

        $this->db->where('range2', 'Smart Phone');
        $this->db->where('test_weekly_models.brand_id', 21);

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_others_smart_sales() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');

        $this->db->where('range2', 'Smart Phone');
        $br = array(1, 10, 4, 21);
        $this->db->where_not_in('test_weekly_models.brand_id', $br);

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    ////fin sales by brand
    ////amount by brand smart

    function get_samsung_smart_amount() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');

        $this->db->where('range2', 'Smart Phone');
        $this->db->where('test_weekly_models.brand_id', 1);

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_huwawi_smart_amount() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');

        $this->db->where('range2', 'Smart Phone');
        $this->db->where('test_weekly_models.brand_id', 10);

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_evertek_smart_amount() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');

        $this->db->where('range2', 'Smart Phone');
        $this->db->where('test_weekly_models.brand_id', 4);

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_lenovo_smart_amount() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');

        $this->db->where('range2', 'Smart Phone');
        $this->db->where('test_weekly_models.brand_id', 21);

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_others_smart_amount() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');

        $this->db->where('range2', 'Smart Phone');
        $br = array(1, 10, 4, 21);
        $this->db->where_not_in('test_weekly_models.brand_id', $br);

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    ////fin sales by brand
    /////amount by brand
    function get_samsung_amount() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_huwawi_amount() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('brand_id =', 10);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_evertek_amount() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('brand_id =', 4);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_lenovo_amount() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('brand_id =', 21);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_others_amount() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $br = array(1, 10, 4, 21);
        $this->db->where_not_in('brand_id', $br);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    /////fin amount by brand
    ///sum sales week
    function get_sum_sales_by_week() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    //added omar now
    function get_total_shortage() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' count(*) as totalshortage ,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');

        //$this -> db -> where('date >=', $start);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('weekly_models.shortage =', 0);
        $this->db->where('weekly_models.brand_id =', 1);
        $query = $this->db->get('weekly_models');
        return $query->row()->totalshortage;
    }

    //added now
    function get_shortage_by_model_week() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' count(*) as sum ,count(DISTINCT(test_weekly_visits.outlet_id)) as tot, models.name as nom,weekly_visits.date as date');
        $this->db->join('models', 'models.id = weekly_models.model_id');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');

        //$this -> db -> where('date >=', $start);
        //$this -> db -> where('date >=', $start);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('weekly_models.shortage =', 0);
        $this->db->where('models.brand_id =', 1);

        $this->db->limit(5);  //$this -> db -> where('date >=', $start);
        $this->db->group_by('nom');
        $this->db->order_by('sum', 'DESC');
        $result = $this->db->get('weekly_models');
        return $result->result();
    }

    ///////////fin add
    function get_sum_by_model_week() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.amount) as sum , models.name as nom,weekly_visits.date as date,test_weekly_models.ws as ws,test_weekly_models.brand_id');
        $this->db->join('models', 'models.id = weekly_models.model_id', 'left');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');

        $this->db->group_by('nom');
        $this->db->order_by('sum', 'DESC');  //$this -> db -> where('date >=', $start);
        //$this -> db -> where('date >=', $start);
        $this->db->where('ws >', 0);
        $this->db->where('date >=', $start);
        $this->db->where('date >=', $start);

        $this->db->where('test_weekly_models.brand_id', 1);
        $this->db->limit(5);  //$this -> db -> where('date >=', $start);

        $result = $this->db->get('weekly_models');
        return $result->result();
    }

    ///////////////////////////////////////manel tache
    function get_sum_week10() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sum_week9() {
        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));


        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sum_week8() {
        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));


        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sum_week7() {
        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));


        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sum_week6() {
        $start = date("Y-m-d", strtotime('monday this week - 28 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 28 days'));


        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sum_week5() {
        $start = date("Y-m-d", strtotime('monday this week - 35 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 35 days'));


        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sum_week4() {
        $start = date("Y-m-d", strtotime('monday this week - 42 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 42 days'));


        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sum_week3() {
        $start = date("Y-m-d", strtotime('monday this week - 49 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 49 days'));


        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sum_week2() {
        $start = date("Y-m-d", strtotime('monday this week - 56 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 56 days'));


        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sum_week1() {
        $start = date("Y-m-d", strtotime('monday this week - 63 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 63 days'));


        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sum_week0() {
        $start = date("Y-m-d", strtotime('monday this week - 70 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 70 days'));


        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    ////////////wekly sales
    ///////////////////////////////////////manel tache
    function get_sales_week10() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_week9() {
        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_week8() {
        $start = date("Y-m-d", strtotime('monday this week - 14days '));
        $end = date("Y-m-d", strtotime('sunday this week - 14days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_week7() {
        $start = date("Y-m-d", strtotime('monday this week -21 days'));
        $end = date("Y-m-d", strtotime('sunday this week -21 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_week6() {
        $start = date("Y-m-d", strtotime('monday this week - 28 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 28 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_week5() {
        $start = date("Y-m-d", strtotime('monday this week - 35 days'));
        $end = date("Y-m-d", strtotime('sunday this week -35 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_week4() {
        $start = date("Y-m-d", strtotime('monday this week - 42 days '));
        $end = date("Y-m-d", strtotime('sunday this week -42 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_week3() {
        $start = date("Y-m-d", strtotime('monday this week -49 days '));
        $end = date("Y-m-d", strtotime('sunday this week -49 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_week2() {
        $start = date("Y-m-d", strtotime('monday this week- 56 days '));
        $end = date("Y-m-d", strtotime('sunday this week -56 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_week1() {
        $start = date("Y-m-d", strtotime('monday this week - 63 days '));
        $end = date("Y-m-d", strtotime('sunday this week - 63 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_week0() {
        $start = date("Y-m-d", strtotime('monday this week -70 days '));
        $end = date("Y-m-d", strtotime('sunday this week -70 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    ///////ad sales week
    function get_sales_smart_week10() {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,models.range2');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_smart_week9() {
        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,models.range2');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_smart_week8() {
        $start = date("Y-m-d", strtotime('monday this week - 14 days '));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days '));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,models.range2');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_smart_week7() {
        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,models.range2');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_smart_week6() {
        $start = date("Y-m-d", strtotime('monday this week - 28 days '));
        $end = date("Y-m-d", strtotime('sunday this week - 28 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,models.range2');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_smart_week5() {
        $start = date("Y-m-d", strtotime('monday this week - 35 days '));
        $end = date("Y-m-d", strtotime('sunday this week - 35 days '));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,models.range2');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_smart_week4() {
        $start = date("Y-m-d", strtotime('monday this week - 42 days '));
        $end = date("Y-m-d", strtotime('sunday this week - 42 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,models.range2');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_smart_week3() {
        $start = date("Y-m-d", strtotime('monday this week - 49 days '));
        $end = date("Y-m-d", strtotime('sunday this week - 49 days '));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,models.range2');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_smart_week2() {
        $start = date("Y-m-d", strtotime('monday this week - 56 days '));
        $end = date("Y-m-d", strtotime('sunday this week - 56 days '));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,models.range2');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_smart_week1() {
        $start = date("Y-m-d", strtotime('monday this week - 63 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 63 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,models.range2');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_sales_smart_week0() {
        $start = date("Y-m-d", strtotime('monday this week - 71 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 71 days'));

        $this->db->select(' sum(test_weekly_models.ws) as sum,weekly_visits.date as date,models.range2');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    /////fin wekly smart sales
    /////begin amount smart
    function get_amount_smart_week10() {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,models.range2,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_amount_smart_week9() {
        $start = date("Y-m-d", strtotime('monday this week -7 days'));
        $end = date("Y-m-d", strtotime('sunday this week -7 days'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,models.range2,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_amount_smart_week8() {
        $start = date("Y-m-d", strtotime('monday this week -14 days'));
        $end = date("Y-m-d", strtotime('sunday this week -14 days'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,models.range2,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_amount_smart_week7() {
        $start = date("Y-m-d", strtotime('monday this week -21 days'));
        $end = date("Y-m-d", strtotime('sunday this week -21 days'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,models.range2,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_amount_smart_week6() {
        $start = date("Y-m-d", strtotime('monday this week -28 days'));
        $end = date("Y-m-d", strtotime('sunday this week -28 days'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,models.range2,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_amount_smart_week5() {
        $start = date("Y-m-d", strtotime('monday this week -35 days'));
        $end = date("Y-m-d", strtotime('sunday this week -35 days'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,models.range2,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_amount_smart_week4() {
        $start = date("Y-m-d", strtotime('monday this week -42 days'));
        $end = date("Y-m-d", strtotime('sunday this week -42 days'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,models.range2,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_amount_smart_week3() {
        $start = date("Y-m-d", strtotime('monday this week -49 days'));
        $end = date("Y-m-d", strtotime('sunday this week -49 days'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,models.range2,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_amount_smart_week2() {
        $start = date("Y-m-d", strtotime('monday this week -56 days'));
        $end = date("Y-m-d", strtotime('sunday this week -56 days'));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,models.range2,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    function get_amount_smart_week1() {
        $start = date("Y-m-d", strtotime('monday this week -63 days'));
        $end = date("Y-m-d", strtotime('sunday this week -63 days '));

        $this->db->select(' sum(test_weekly_models.amount) as sum,weekly_visits.date as date,models.range2,test_weekly_models.ws as ws');
        $this->db->join('weekly_visits', 'weekly_visits.id = weekly_models.visit_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('range2', 'Smart Phone');

        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->sum;
    }

    ///end amount smart
    //////////////////////////////fin manel tach///////////////////
    //////////////////////////////fin manel tach///////////////////
    ///////////fin add

    function get_models($visit_id) {
        $this->db->order_by('model_id', 'ASC');
        $result = $this->db->get_where('weekly_models', array('visit_id' => $visit_id));
        return $result->result();
    }

    function get_shortage_models($visit_id) {
        $this->db->order_by('model_id', 'ASC');
        $result = $this->db->get_where('shortage_models', array('visit_id' => $visit_id));
        return $result->result();
    }

// Add new model to recents visits
    function add_new_model($model, $visit_id) {

        if (!$this->is_exist($model->id, $visit_id)) {

            $save['visit_id'] = $visit_id;
            $save['shelf'] = 0;
            $save['ws'] = 0;
            $save['model_id'] = $model->id;
            $save['model_name'] = $model->name;

            $save['brand_id'] = $model->brand_id;
            $save['category_id'] = $model->category_id;
            $save['range_id'] = $model->range_id;
            $save['price_range_id'] = $model->price_range_id;
            $save['price'] = $model->price;

            $this->save_single($save);
        }
    }

    function save_bulk($model) {
        if ($model['id']) {
            $price = $this->Model_model->get_model_price($model['model_id']);
            $model['amount'] = $model['ws'] * $price;
            $this->db->where('id', $model['id']);
            $this->db->update('weekly_models', $model);
            return $model['id'];
        }
    }

    function shortage_save_bulk($model) {
        if ($model['id']) {

            $this->db->where('id', $model['id']);
            $this->db->update('shortage_models', $model);
            return $model['id'];
        }
    }

    function create_shortage_models($visit_id) {
        $models = $this->Model_model->get_shortage_models();
        foreach ($models as $model) {
            $model_id = $model->id;
            $brand_id = $model->brand_id;

            $data[] = array(
                'model_id' => $model_id,
                'visit_id' => $visit_id,
                'brand_id' => $brand_id,
                'shortage' => '0'
            );
        }

        $this->save_shortage($data, $visit_id);
    }

    function save_model($model) {
        if ($model['id']) {
            $this->db->where('id', $model['id']);
            $this->db->update('weekly_models', $model);
            return $model['id'];
        } else {
            $this->db->insert('weekly_models', $model);

            return $this->db->insert_id();
        }
    }

    function save_single($model) {

        $this->db->insert('weekly_models', $model);
        return $this->db->insert_id();
    }

    function save(&$models, $visit_id) {
        //Run these queries as a transaction, we want to make sure we do all or nothing
        $this->db->trans_start();

        $this->delete($visit_id);

        foreach ($models as $row) {
            $row['visit_id'] = $visit_id;
            $this->db->insert('weekly_models', $row);
        }

        $this->db->trans_complete();
        return true;
    }

    function save_shortage(&$models, $visit_id) {
        //Run these queries as a transaction, we want to make sure we do all or nothing
        $this->db->trans_start();

        $this->delete($visit_id);

        foreach ($models as $row) {
            $row['visit_id'] = $visit_id;
            $this->db->insert('shortage_models', $row);
        }

        $this->db->trans_complete();
        return true;
    }

    function is_exist($model_id, $visit_id) {
        $query = $this->db->get_where('weekly_models', array('model_id' => $model_id, 'visit_id' => $visit_id));
        $count = $query->num_rows();
        if ($count == 0) {
            return false;
        } else {
            return true;
        }
    }

    function deactivate($id) {
        $weekly_model = array('id' => $id, 'active' => 0);
        $this->save_weekly_model($weekly_model);
    }

    function delete($id) {
        /*
          deleting a weekly_model will remove all their orders from the system
          this will alter any report numbers that reflect total sales
          deleting a customer is not recommended, deactivation is preferred
         */

        //this deletes the weekly_models record
        $this->db->where('visit_id', $id);
        $this->db->delete('weekly_models');
    }

    function delete_by_visit($visit_id) {
        /*
          deleting a classic_model will remove all their orders from the system
          this will alter any report numbers that reflect total sales
          deleting a customer is not recommended, deactivation is preferred
         */

        //this deletes the classic_models record
        $this->db->where('visit_id', $visit_id);
        $this->db->delete('weekly_models');
    }

    function delete_shortage_by_visit($visit_id) {
        /*
          deleting a classic_model will remove all their orders from the system
          this will alter any report numbers that reflect total sales
          deleting a customer is not recommended, deactivation is preferred
         */

        //this deletes the classic_models record
        $this->db->where('visit_id', $visit_id);
        $this->db->delete('shortage_models');
    }

    function get_Ulc_models($visit_id) {
        $this->db->select('weekly_models.*');
        $this->db->from('weekly_models');
        $this->db->where('visit_id', $visit_id);

        $this->db->join('models', 'models.id=weekly_models.model_id');
        $this->db->where('weekly_models.range_id', 1);
        $this->db->order_by('weekly_models.brand_id', 'ASC');
        $this->db->order_by('models.code', 'ASC');

        $result = $this->db->get();
        return $result->result();
    }

    function get_all_models() {
        $this->db->select('weekly_models.*');
        $this->db->from('weekly_models');

        $result = $this->db->get();
        return $result->result();
    }

    function update($model) {

        $price_range_id = $this->Model_model->get_model_price_range($model->model_id);
        print_r($price_range_id);
        $data['price_range_id'] = $price_range_id;
        $data['id'] = $model->id;
        $this->db->where('id', $data['id']);
        $this->db->update('weekly_models', $data);
        return $data['id'];
    }

    function update_mod() {
        $models = $this->get_all_models();
        foreach ($models as $model) {
            $data['id'] = $model->id;
            $data['model_id'] = $model->model_id;
            $data['price_range_id'] = $this->Model_model->get_model_price_range($model->model_id);
            //echo $date['id'].'**'.$date['model_id'].'**'.$date['price_range_id'].'$$$$$$$$';
            $this->db->where('id', $data['id']);
            $this->db->update('weekly_models', $data);
            echo 'traiter';
        }
    }

    function get_Smart_models($visit_id) {
        $this->db->select('weekly_models.*');
        $this->db->from('weekly_models');
        $this->db->join('models', 'models.id=weekly_models.model_id');
        $this->db->order_by('weekly_models.brand_id', 'ASC');
        $this->db->order_by('models.code', 'ASC');
        $this->db->where('weekly_models.range_id', 2);
        $this->db->where('visit_id', $visit_id);
        $result = $this->db->get();
        return $result->result();
    }

    function get_Features_models($visit_id) {
        $this->db->select('weekly_models.*');
        $this->db->from('weekly_models');
        $this->db->join('models', 'models.id=weekly_models.model_id');
        $this->db->order_by('weekly_models.brand_id', 'ASC');
        $this->db->order_by('models.code', 'ASC');
        $this->db->where('weekly_models.range_id', 3);
        $this->db->where('visit_id', $visit_id);
        $result = $this->db->get();
        return $result->result();
    }

    function get_Tablet_models($visit_id) {
        $this->db->select('weekly_models.*');
        $this->db->from('weekly_models');
        $this->db->join('models', 'models.id=weekly_models.model_id');
        $this->db->order_by('weekly_models.brand_id', 'ASC');
        $this->db->order_by('models.code', 'ASC');
        $this->db->where('weekly_models.range_id', 4);
        $this->db->where('visit_id', $visit_id);
        $result = $this->db->get();
        return $result->result();
    }

    function get_Gear_models($visit_id) {
        $this->db->select('weekly_models.*');
        $this->db->from('weekly_models');
        $this->db->join('models', 'models.id=weekly_models.model_id');
        $this->db->order_by('weekly_models.brand_id', 'ASC');
        $this->db->order_by('models.code', 'ASC');
        $this->db->where('weekly_models.range_id', 5);
        $this->db->where('visit_id', $visit_id);
        $result = $this->db->get();
        return $result->result();
    }

    function get_models_by_brand($visit_id, $brand_id) {
        $this->db->select('weekly_models.*');
        //$this-> db -> DISTINCT('weekly_models.model_id');
        $this->db->from('weekly_models');
        $this->db->join('models', 'models.id=weekly_models.model_id');
        $this->db->order_by('models.code', 'DESC');
        $this->db->where('weekly_models.brand_id', $brand_id);
        $this->db->where('weekly_models.visit_id', $visit_id);

        $result = $this->db->get();
        return $result->result();
    }

    function get_summ_shortage_ws_by_brand($visit_id, $brand_id) {
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_visits');

        $this->db->join('weekly_models', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        //$this -> db -> order_by('models.code', 'DESC');
        if ($brand_id == -1) {
            $this->db->where('weekly_models.brand_id !=', 1);
            $this->db->where('weekly_models.brand_id !=', 3);
            $this->db->where('weekly_models.brand_id !=', 4);
            $this->db->where('weekly_models.brand_id !=', 10);
            $this->db->where('weekly_models.brand_id !=', 21);
        } else {
            $this->db->where('weekly_models.brand_id', $brand_id);
        }

        $this->db->where('weekly_models.visit_id', $visit_id);

        $result = $this->db->get();
        return $result->row();
    }

    //les vente par outlet chaque week
    function get_ws_week1($outlet_id) {
        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->ws;
    }

    function get_ws_week2($outlet_id) {
        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->ws;
    }

    function get_ws_week3($outlet_id) {
        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->ws;
    }

    function get_ws_week4($outlet_id) {
        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);

        $query = $this->db->get('weekly_models');
        return $query->row()->ws;
    }

    //fin
    //les vente samsung par outlet chaque week
    function get_summ_shortage_ws_by_outlet_samsung_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_samsung_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_samsung_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_samsung_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
    //les vente evertek par outlet
    function get_summ_shortage_ws_by_outlet_evertek_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 4);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_evertek_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 4);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_evertek_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 4);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_evertek_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 4);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
    //les vente nokia par outlet
    function get_summ_shortage_ws_by_outlet_nokia_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 3);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_nokia_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 3);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_nokia_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 3);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_nokia_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 3);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
    //les vente huawei par outlet
    function get_summ_shortage_ws_by_outlet_huawei_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 10);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_huawei_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 10);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_huawei_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 10);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_huawei_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 10);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
    //les vente lenovo par outlet
    function get_summ_shortage_ws_by_outlet_lenovo_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 21);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_lenovo_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 21);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_lenovo_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 21);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_lenovo_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 21);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
    //les vente others par outlet
    function get_summ_shortage_ws_by_outlet_others_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $br = array(1, 10, 4, 21, 3);
        $this->db->where_not_in('brand_id', $br);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_others_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $br = array(1, 10, 4, 21, 3);
        $this->db->where_not_in('brand_id', $br);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_others_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $br = array(1, 10, 4, 21, 3);
        $this->db->where_not_in('brand_id', $br);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_ws_by_outlet_others_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $br = array(1, 10, 4, 21, 3);
        $this->db->where_not_in('brand_id', $br);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('ws >', 0);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
    //les vente par outlet chaque week
    function get_shelf_week1($outlet_id) {
        $start = date("Y-m-d", strtotime('monday this week '));
        $end = date("Y-m-d", strtotime('sunday this week '));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->shelf;
    }

    function get_shelf_week2($outlet_id) {
        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->shelf;
    }

    function get_shelf_week3($outlet_id) {
        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->shelf;
    }

    function get_shelf_week4($outlet_id) {
        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);

        $query = $this->db->get('weekly_models');
        return $query->row()->shelf;
    }

    //fin
    //les vente samsung par outlet chaque week
    function get_summ_shortage_shelf_by_outlet_samsung_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_samsung_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_samsung_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_samsung_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 1);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
    //les vente evertek par outlet
    function get_summ_shortage_shelf_by_outlet_evertek_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 4);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_evertek_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 4);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_evertek_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 4);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_evertek_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 4);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
    //les vente nokia par outlet
    function get_summ_shortage_shelf_by_outlet_nokia_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 3);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_nokia_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 3);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_nokia_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 3);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_nokia_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 3);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
    //les vente huawei par outlet
    function get_summ_shortage_shelf_by_outlet_huawei_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 10);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_huawei_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 10);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_huawei_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 10);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_huawei_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 10);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
    //les vente lenovo par outlet
    function get_summ_shortage_shelf_by_outlet_lenovo_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 21);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_lenovo_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 21);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_lenovo_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 21);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_lenovo_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $this->db->where('brand_id =', 21);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
    //les vente others par outlet
    function get_summ_shortage_shelf_by_outlet_others_week1($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week'));
        $end = date("Y-m-d", strtotime('sunday this week'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $br = array(1, 10, 4, 21, 3);
        $this->db->where_not_in('brand_id', $br);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_others_week2($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 7 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 7 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $br = array(1, 10, 4, 21, 3);
        $this->db->where_not_in('brand_id', $br);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_others_week3($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 14 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 14 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $br = array(1, 10, 4, 21, 3);
        $this->db->where_not_in('brand_id', $br);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    function get_summ_shortage_shelf_by_outlet_others_week4($outlet_id) {

        $start = date("Y-m-d", strtotime('monday this week - 21 days'));
        $end = date("Y-m-d", strtotime('sunday this week - 21 days'));
        $this->db->select('SUM(test_weekly_models.ws) as ws,SUM(test_weekly_models.shelf) as shelf,brands.name as name', false);

        $this->db->from('weekly_models');

        $this->db->join('weekly_visits', 'weekly_models.visit_id=weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id=outlets.id');

        $this->db->join('brands', 'weekly_models.brand_id = brands.id');

        $this->db->where('weekly_visits.outlet_id', $outlet_id);
        $br = array(1, 10, 4, 21, 3);
        $this->db->where_not_in('brand_id', $br);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $result = $this->db->get();
        return $result->row();
    }

    //fin
}
