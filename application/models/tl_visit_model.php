<?php

//bcm
Class Tl_visit_model extends CI_Model {

    //this is the expiration for a non-remember session
    var $dbprefix;

    function __construct() {
        parent::__construct();
        $this->dbprefix = $this->db->dbprefix;
    }

    /*     * ***************************************************************** */

    function get_nb_visits($month = -1) {
        $this->db->select('count(case when bcc_tl_visits.type = "0" then 1 else null end) as nb_control_visit,'
                . 'count(case when bcc_tl_visits.type = "1" then 1 else null end) as nb_field_visit', false);

        $this->db->from('tl_visits');


        if ($month != -1) {
            $date = 'm_date';
            $start_date = $month;
            $this->db->where('tl_visits.' . $date, $start_date);
        } else {
            $date = 'm_date';
            $start_date = date('Y-m-01');
            $this->db->where('tl_visits.' . $date, $start_date);
        }

        $result = $this->db->get();
        return $result->result();
        // return $this->db->count_all_results('tl_actions');
    }

    function get_visits_search($limit = 0, $offset = 0, $order_by = 'tl_visits.id', $direction = 'DESC', $search = -1, $start_date = '', $end_date = '', $visit_type = -1) {

        $this->db->select('tl_visits.id as id,'
                . 'tl_visits.outlet_name as outlet_name,'
                . 'tl_visits.type as type_visit,'
                . 'tl_visits.outlet_id as outlet_id,'
                . 'tl_visits.date as date,'
                . 'tl_visits.entry_time as entry_time,'
                . 'tl_visits.exit_time as exit_time,'
                . 'tl_visits.entry_location as entry_location,'
                . 'tl_visits.exit_location as exit_location,'
                . 'tl_interventions.id as intervention,'
                . 'tl_actions.id as action,'
                . 'tl_visits.rating as note,'
                . 'CAST(bcc_tl_visits.created as DATE) as created_date,'
                . 'CAST(bcc_tl_visits.created as TIME) as created_time,'
                . 'admin.name as fo,'
                . 'outlets.state as state,'
                . 'zones.name as zone', false);


        $this->db->from('tl_visits');
        $this->db->join('tl_interventions', 'tl_visits.id=tl_interventions.visit_id', 'left');
        $this->db->join('tl_actions', 'tl_interventions.id=tl_actions.intervention_id', 'left');
        $this->db->join('outlets', 'tl_visits.outlet_id = outlets.id');
        $this->db->join('admin', 'outlets.admin_id = admin.id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        if ($search != -1) {
            $search = strtoupper($search);
            $this->db->where("(UPPER(bcc_tl_visits.outlet_name) LIKE '%" . $search . "%')");
            $this->db->or_where("(UPPER(bcc_tl_interventions.name LIKE '%" . $search . "%')");
        }
        if ($visit_type == 0) {
            $this->db->where('tl_visits.type', 0);
        } else if ($visit_type == 1) {
            $this->db->where_in('tl_visits.type', 1);
        }

        if ($start_date != '' && $end_date != '') {
            $this->db->where('tl_visits.date >=', $start_date);
            $this->db->where('tl_visits.date <=', $end_date);
        }

        $this->db->group_by('tl_visits.id');

        $this->db->order_by($order_by, $direction);

        $result = $this->db->get();
        return $result->result_array();
    }

//    function count_visits_search($search = '-1', $start_date = '', $end_date = '', $visit_type = '-1') {
//
//        $this->db->join('tl_interventions', 'tl_visits.id=tl_interventions.visit_id', 'left');
//        $this->db->join('tl_actions', 'tl_interventions.id=tl_actions.intervention_id', 'left');
//
//
//        if ($search != '-1') {
//            $search = strtoupper($search);
//            $this->db->where("(UPPER(bcc_tl_visits.outlet_name) LIKE '" . $search . "%')");
//            $this->db->or_where("(UPPER(bcc_tl_interventions.name LIKE '" . $search . "%')");
//        }
//        if ($visit_type == 0) {
//            $this->db->where('tl_visits.type', 0);
//        } else if ($visit_type == 1) {
//            $this->db->where_in('tl_visits.type', 1);
//        }
//
//        if ($start_date != '' && $end_date != '') {
//            $this->db->where('tl_visits.date >=', $start_date);
//            $this->db->where('tl_visits.date <=', $end_date);
//        }
//
//        return $this->db->count_all_results('tl_visits');
//    }
    
    
        function count_visits_search($start_date = '', $end_date = '', $visit_type = '-1') {

//        $this->db->join('tl_interventions', 'tl_visits.id=tl_interventions.visit_id', 'left');
//        $this->db->join('tl_actions', 'tl_interventions.id=tl_actions.intervention_id', 'left');
//
//        if ($search != '-1') {
//            $search = strtoupper($search);
//            $this->db->where("(UPPER(cos_tl_visits.outlet_name) LIKE '" . $search . "%')");
//            $this->db->or_where("(UPPER(cos_tl_interventions.name) LIKE '" . $search . "%')");
//        }
        if ($visit_type == 2) {
            $this->db->where('tl_visits.type', 0);
        } else if ($visit_type == 1) {
            $this->db->where('tl_visits.type', 1);
        }

        if ($start_date != '' && $end_date != '') {
            $this->db->where('tl_visits.date >=', $start_date);
            $this->db->where('tl_visits.date <=', $end_date);
        }

        return $this->db->count_all_results('tl_visits');
    }

    function get_inetrvention_by_id($id) {
        $this->db->select('tl_interventions.name as name');
        $this->db->where('tl_interventions.visit_id', $id);
        $this->db->from('tl_interventions');
        $result = $this->db->get();
        return $result->result_array();
    }

    function get_visit($visit_id) {
        $this->db->select('tl_visits.id as visit_id,'
                . 'tl_visits.date as date,'
                . 'tl_visits.outlet_name as outlet_name,'
                . 'tl_interventions.id as intervention,'
                . 'tl_actions.id as action,'
                . 'tl_visits.outlet_id as outlet_id,'
                . 'tl_visits.longitude as visit_longitude,'
                . 'tl_visits.latitude as visit_latitude,'
                . 'outlets.longitude as outlet_longitude,'
                . 'outlets.latitude as outlet_latitude');

        $this->db->from('tl_visits');
        $this->db->join('outlets', 'tl_visits.outlet_id = outlets.id');

        $this->db->join('tl_interventions', 'tl_visits.id=tl_interventions.visit_id', 'left');
        $this->db->join('tl_actions', 'tl_interventions.id=tl_actions.intervention_id', 'left');

        if ($visit_id != -1)
            $this->db->where('tl_visits.id', $visit_id);

        $result = $this->db->get();
        return $result->result_array();
    }

    function get_interventions_by_visit_id($visit_id) {
        $this->db->select('tl_interventions.id as intervention_id,'
                . 'tl_interventions.name as name,'
                . 'tl_interventions.type as type,'
                . 'tl_interventions.remark as remark,'
                . 'tl_interventions.photo as photo,'
                . 'tl_interventions.before as befor,'
                . 'tl_interventions.after as after,'
                . 'tl_actions.id as action_id');

        $this->db->from('tl_interventions');
        $this->db->join('tl_actions', 'tl_interventions.id=tl_actions.intervention_id', 'left');
        if ($visit_id != -1)
            $this->db->where('tl_interventions.visit_id', $visit_id);

        $result = $this->db->get();
        return $result->result_array();
    }

    function get_action_by_id($id) {

        $result = $this->db->get_where('tl_actions', array('id' => $id));
        return $result->result_array();
    }

    function get_nb_intervention($visit_type = -1, $tab_type = -1, $month = -1) {

        $this->db->select('count(case when bcc_tl_interventions.type = "1" then 1 else null end) as anomalie,'
                . 'count(case when bcc_tl_interventions.type = "2" then 1 else null end) as warning,'
                . 'count(case when bcc_tl_interventions.type = "3" then 1 else null end)as recommendation,'
                . 'count(case when bcc_tl_interventions.type = "4" then 1 else null end) as branding,'
                . 'count(case when bcc_tl_interventions.type = "5" then 1 else null end) as PO,'
                . 'count(case when bcc_tl_interventions.type = "6" then 1 else null end) as Event,'
                . 'count(case when bcc_tl_interventions.type = "7" then 1 else null end) as Others,'
                , false);

        $this->db->from('tl_visits');
        $this->db->join('tl_interventions', 'tl_visits.id=tl_interventions.visit_id', 'left');
        //control
        if ($visit_type == 2) {
            $this->db->join('visits', 'tl_visits.visit_id = visits.id');
            $this->db->where('tl_visits.type', 0);
            //new
        } else if ($visit_type == 1) {
            $this->db->where('tl_visits.type', 1);
        }
        if ($tab_type == 'fo') {

            $this->db->select('admin.name as FO, (sum(bcc_tl_visits.rating)/(count(bcc_tl_visits.id)*5))*100 as note', false);

            $this->db->join('outlets', 'tl_visits.outlet_id = outlets.id');
            $this->db->join('admin', 'outlets.admin_id = admin.id');
            $this->db->group_by('admin.id');
        }

        if ($tab_type == 'zone') {
            $this->db->select('zones.name as zone');
            $this->db->join('outlets', 'tl_visits.outlet_id = outlets.id');
            $this->db->join('zones', 'outlets.zone_id = zones.id');
            $this->db->group_by('zones.id');
        }

        if ($tab_type == 'channel') {
            $this->db->select('channels.name as channel');
            $this->db->join('outlets', 'tl_visits.outlet_id = outlets.id');
            $this->db->join('channels', 'outlets.channel_id=channels.id');
            $this->db->group_by('channels.id');
        }


        if ($month != -1) {
            $date = 'm_date';
            $start_date = $month;
            $this->db->where('tl_visits.' . $date, $start_date);
        } else {
            $date = 'm_date';
            $start_date = date('Y-m-01');
            $this->db->where('tl_visits.' . $date, $start_date);
        }

        $this->db->order_by('tl_visits.id', 'desc');
        $result = $this->db->get();
        return $result->result();
    }

    function get_nb_action($month = -1) {
        $this->db->select('count(case when bcc_tl_interventions.type = "1" then 1 else null end) as nb_action_anomalie,'
                . 'count(case when bcc_tl_interventions.type = "2" then 1 else null end) as nb_action_warning', false);

        $this->db->from('tl_visits');
        $this->db->join('tl_interventions', 'tl_visits.id=tl_interventions.visit_id');
        $this->db->join('tl_actions', 'tl_interventions.id=tl_actions.intervention_id');
        //control visit
        $this->db->where('tl_visits.type', 0);

        if ($month != -1) {
            $date = 'm_date';
            $start_date = $month;
            $this->db->where('tl_visits.' . $date, $start_date);
        } else {
            $date = 'm_date';
            $start_date = date('Y-m-01');
            $this->db->where('tl_visits.' . $date, $start_date);
        }

        $this->db->order_by('tl_visits.id', 'desc');
        $result = $this->db->get();
        return $result->result();
        // return $this->db->count_all_results('tl_actions');
    }

    function get_visits_today_for_map() {
        $this->db->select('tl_visits.longitude as longitude,
            tl_visits.latitude as latitude,
            tl_visits.id as id,
            tl_visits.type as type,
            tl_visits.outlet_id as outlet_id ');

        $this->db->from('tl_visits');

        $today = date('Y-m-d');
        $this->db->where('tl_visits.date', $today);

        $result = $this->db->get();
        return $result->result();
    }

    function get_feeds_of_control_visit($month) {

        $this->db->select('tl_visits.id as id,'
                . 'tl_visits.date as date,'
                . 'tl_visits.type as type,'
                . 'tl_visits.outlet_id as outlet_id,'
                . 'outlets.name as outlet_name,'
                . 'tl_interventions.remark as interv_rem, '
                . 'tl_actions.remark as action_rem');


        $this->db->from('tl_visits');
        $this->db->join('outlets', 'tl_visits.outlet_id = outlets.id');
        $this->db->join('tl_interventions', 'tl_visits.id = tl_interventions.visit_id', 'left');
        $this->db->join('tl_actions', 'tl_interventions.id = tl_actions.intervention_id', 'left');

        $this->db->where('tl_visits.type', 0);

        $this->db->where('tl_visits.m_date', $month);

        $this->db->order_by('tl_visits.id', 'desc');

        $result = $this->db->get();
        return $result->result();
    }

    function get_feeds_of_new_visit($month) {

        $this->db->select('tl_visits.id as id,'
                . 'tl_visits.date as date,'
                . 'tl_visits.type as type,'
                . 'tl_visits.outlet_id as outlet_id,'
                . 'outlets.name as outlet_name,'
                . 'tl_interventions.remark as interv_rem, '
                . 'tl_actions.remark as action_rem');


        $this->db->from('tl_visits');
        $this->db->join('outlets', 'tl_visits.outlet_id = outlets.id');
        $this->db->join('tl_interventions', 'tl_visits.id = tl_interventions.visit_id', 'left');
        $this->db->join('tl_actions', 'tl_interventions.id = tl_actions.intervention_id', 'left');

        $this->db->where('tl_visits.type', 1);

        $this->db->where('tl_visits.m_date', $month);

        $this->db->order_by('tl_visits.id', 'desc');

        $result = $this->db->get();
        return $result->result();
    }

    function get_tracking_pos_for_excel($start_date, $end_date, $channel_id = -1, $zone_id = -1) {
        $this->db->select('outlets.name as name,channels.name as channel,zones.name as zone,zones.id as zone_id,COUNT( bcc_outlets.`id` ) as frequence');
        $this->db->from('outlets');
        $this->db->join('tl_visits', 'outlets.id=tl_visits.outlet_id', 'LEFT');
        $this->db->join('channels', 'outlets.channel_id=channels.id', 'LEFT');
        $this->db->join('zones', 'outlets.zone_id=zones.id', 'LEFT');
        $this->db->where('outlets.active ', 1);
        if ($channel_id != -1) {
            $this->db->where('channels.id', $channel_id);
        }
        if ($zone_id != -1) {
            $this->db->where('zones.id', $zone_id);
        }

        $this->db->where('tl_visits.date >=', $start_date);
        $this->db->where('tl_visits.date <=', $end_date);

        $this->db->group_by('bcc_outlets.id');
        $this->db->order_by('frequence', 'DESC');

        return $this->db->get()->result();
    }

    function get_not_tracking_pos_for_excel($start_date, $end_date, $channel_id = -1, $zone_id = -1) {
        $this->db->select('outlets.name as name,channels.name as channel,zones.name as zone');
        $this->db->from('outlets');

        $this->db->join('channels', 'channels.id=outlets.channel_id', 'LEFT');
        $this->db->join('zones', 'zones.id=outlets.zone_id', 'LEFT');
        $this->db->where('outlets.active ', 1);
        if ($channel_id != -1) {
            $this->db->where('channels.id  ', $channel_id);
        }
        if ($zone_id != -1) {
            $this->db->where('zones.id  ', $zone_id);
        }

        $query1 = $this->db->query("select `outlet_id` from bcc_tl_visits
                           WHERE `bcc_tl_visits`.`date` >= '$start_date' AND `bcc_tl_visits`.`date` <= '$end_date'");
        $query1_result = $query1->result();
        $outlet_id = array();
        foreach ($query1_result as $row) {
            $outlet_id[] = $row->outlet_id;
        }
        $outlet_ids = implode(",", $outlet_id);
        $ids = explode(",", $outlet_ids);
        $this->db->where_not_in('bcc_outlets.id', $ids);

        return $this->db->get()->result();
    }

}
