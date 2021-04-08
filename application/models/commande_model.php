<?php

Class Commande_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_today_visits($date) {

        $this->db->select('
            visits.longitude as longitude,
            visits.latitude as latitude,visits.id as id,
            visits.monthly_visit as monthly_visit,
            outlets.name as outlet_name,
            outlets.id as outlet_id,
            outlets.zone as outlet_zone,
            outlets.state as outlet_state,
            admin.name as name,
            visits.date as date,
            visits.active as active,
            remark as remark,
            visits.oos_perc as oos_perc,
            visits.entry_time as entry_time,
            visits.exit_time as exit_time');
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('admin', 'admin.id=visits.admin_id');
        $this->db->from('visits');

        $this->db->where('visits.date', $date);
        $this->db->where('visits.monthly_visit', 0);



        $result = $this->db->get();
        return $result->result();
    }

    function get_oos_detail($visit_id) {

        $this->db->select('models.id as id,products.name as product_name');
        $this->db->from('models');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('visits', 'visits.id = models.visit_id');

        $this->db->order_by('products.code', 'ASC');
        $this->db->where('visits.id', $visit_id);
        $this->db->where('visits.monthly_visit', 0);
        $this->db->where('bcc_models.av', 0);
        $this->db->where('models.brand_id', 18);

        $result = $this->db->get();
        return $result->result();
    }

    function get_pictures($id) {

        $this->db->select('visits.*');
        $this->db->from('visits');
        $this->db->where('visits.id =', $id);
        $result = $this->db->get();
        return $result->row();
    }

    function get_historique_cmd_per_fo($fo_id, $start_date, $end_date) {
        $this->db->distinct();
        $this->db->select('visits.date as date,'
                . 'admin.name as fo,'
                . 'outlets.name as outlet_name,'
                . 'visits.order_picture as order_picture,'
                . 'visits.order_num as order_num');

        $this->db->from('models');
        $this->db->join('brands', 'models.brand_id = brands.id');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('admin', 'visits.admin_id = admin.id');


        $this->db->where('visits.date >=', $start_date);
        $this->db->where('visits.date <=', $end_date);
        $this->db->where('admin.id', $fo_id);
        $this->db->where('visits.order_picture is NOT NULL', NULL, FALSE);



        $this->db->where('visits.monthly_visit', 0);
        $this->db->where('brands.id', 18);
        $this->db->order_by('visits.id', 'Desc');

        $result = $this->db->get();
        return $result->result();
    }

    function get_historique_cmd_per_pos($outlet_id, $start_date, $end_date) {
        $this->db->distinct();
        $this->db->select('visits.date as date,'
                . 'admin.name as fo,'
                . 'outlets.name as outlet_name,'
                . 'visits.order_picture as order_picture,'
                . 'visits.order_num as order_num');

        $this->db->from('models');
        $this->db->join('brands', 'models.brand_id = brands.id');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('admin', 'visits.admin_id = admin.id');


        $this->db->where('visits.date >=', $start_date);
        $this->db->where('visits.date <=', $end_date);
        $this->db->where('outlets.id', $outlet_id);
        $this->db->where('visits.order_picture is NOT NULL', NULL, FALSE);



        $this->db->where('visits.monthly_visit', 0);
        $this->db->where('brands.id', 18);
        $this->db->order_by('visits.id', 'Desc');

        $result = $this->db->get();
        return $result->result();
    }

    function get_outlet_by_zone_channel($zone_id, $channel_id) {

        if ($zone_id != -1) {
            $this->db->where('outlets.zone_id', $zone_id);
        }

        if ($channel_id != -1) {
            $this->db->where('outlets.channel_id', $channel_id);
        }
        $this->db->not_like('outlets.name', 'test');

        $this->db->distinct();
        $this->db->order_by('outlets.name', 'ASC');
        $result = $this->db->get('outlets');
        return $result->result();
    }

    function get_nbr_cde_per_fo($start_date, $end_date) {
        $this->db->distinct();

        $this->db->select("COUNT(CASE WHEN bcc_visits.order_picture IS NOT NULL THEN 1 ELSE NULL END) AS nbr_cde,"
                . 'bcc_admin.name as fo', false);

        $this->db->from('visits');
//        $this->db->join('brands', 'models.brand_id = brands.id');
//        $this->db->join('visits', 'visits.id = models.visit_id');
//        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('admin', 'visits.admin_id = admin.id');
//        $this->db->where('visits.order_picture is NOT NULL', NULL, FALSE);
        $this->db->where('visits.date >=', $start_date);
        $this->db->where('visits.date <=', $end_date);

        $this->db->where('visits.monthly_visit', 0);
//        $this->db->where('brands.id', 18);
        $this->db->group_by('visits.admin_id');
        $this->db->order_by('nbr_cde', 'Desc');

        $result = $this->db->get();
        return $result->result();
    }

}
