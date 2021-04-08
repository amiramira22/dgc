<?php

Class Zone_model extends CI_Model {

    //this is the expiration for a non-remember session
    var $session_expire = 10200;

    function __construct() {
        parent::__construct();
    }

    /*     * ******************************************************************

     * ****************************************************************** */

    function get_zones() {
        $this->db->order_by('zones.code', 'ASC');
        $result = $this->db->get('zones');
        return $result->result();
    }

    function get_selected_zones() {
        $this->db->where('zone_for_chart', 1);
        $this->db->order_by('zones.code', 'ASC');
        $result = $this->db->get('zones');
        return $result->result();
    }

    function get_zones_for_charts() {

        $this->db->select('zones.name as name');
        $this->db->from('zones');
        $this->db->where('zone_for_chart', 1);
        $this->db->order_by('zones.code', 'ASC');

        $result = $this->db->get();
        $zones = ($result->result());


        foreach ($zones as $zone) {
            $data[] = $zone->name;
        }
        return json_encode($data, JSON_HEX_TAG);
    }

    function get_zones_av() {

        $this->db->select('zones.id as id');
        $this->db->from('zones');
        $this->db->where('zone_for_chart', 1);
        $this->db->group_by('name');

        $result = $this->db->get();
        $zones = ($result->result());


        foreach ($zones as $zone) {
            $data[] = $zone->id * 2;
        }
        return json_encode($data);
        //return $data;
    }

    function get_zones_oos() {

        $this->db->select('zones.id as id');
        $this->db->from('zones');
        $this->db->where('zone_for_chart', 1);
        $this->db->group_by('name');

        $result = $this->db->get();
        $zones = ($result->result());


        foreach ($zones as $zone) {
            $data[] = $zone->id * 5;
        }
        return json_encode($data);
        //return $data;
    }

    function get_today_visits($limit = 0, $offset = 0, $order_by = 'visits.id', $direction = 'DESC', $current_admin_id = -1) {
        $this->db->select('visits.longitude as longitude,visits.latitude as latitude,visits.id as id,visits.monthly_visit as monthly_visit,outlets.name as outlet_name,outlets.id as outlet_id,outlets.zone as outlet_zone,outlets.state as outlet_state,admin.name as name,
		visits.date as date,visits.active as active,visits.modified as modified,
		remark as remark,visits.av_perc as av_perc,visits.oos_perc as oos_perc,visits.entry_time as entry_time,visits.exit_time as exit_time');
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('admin', 'admin.id=visits.admin_id');
        $this->db->from('visits');


        $today = date('Y-m-d');
        $this->db->where('visits.date', $today);

        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }


        if ($current_admin_id != '-1') {
            $this->db->where('visits.admin_id', $current_admin_id);
        }


        $result = $this->db->get();
        return $result->result();
    }

    function get_visit_oos() {

        $this->db->select('sum(hcs_visits.oos_perc) as oos_perc,count(hcs_visits.id) as total', false);
        $this->db->from('visits');



        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->where('zones.zone_for_chart', 1);
        $this->db->group_by('zones.id');

        $result = $this->db->get();
        $zones = ($result->result());


        foreach ($zones as $zone) {
            $perc = $zone->oos_perc / $zone->total;
            $number = $perc * 100;

            $data[] = number_format($number, 2, '.', '') * 1;
        }
        return json_encode($data);
        //return $data;
    }

    function get_visit_oos_by_brand($brand_id) {

        $this->db->select('sum(CASE WHEN hcs_models.sku_display != 0 THEN 1 ELSE 0 END) as oos_perc,count(hcs_models.id) as total,zones.name as zone_name', false);
        $this->db->from('models');


        $this->db->join('visits', 'models.visit_id = visits.id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('zones', 'outlets.zone = zones.name');


        $this->db->where('zones.zone_for_chart', 1);
        $this->db->where('models.brand_id', $brand_id);
      	$this->db->order_by('zones.code','ASC');
        $this->db->group_by('zones.id');

        $result = $this->db->get();
        $zones = ($result->result());


        foreach ($zones as $zone) {
            $perc = $zone->oos_perc / $zone->total;
            $number = $perc * 100;

            $data[] = number_format($number, 2, '.', '') * 1;
        }
        return json_encode($data);
        //return $data;
    }

    function get_visit_av_by_brand($brand_id) {

        $this->db->select('sum(CASE WHEN hcs_models.sku_display != 0 THEN 0 ELSE 1 END) as oos_perc,count(hcs_models.id) as total', false);
        $this->db->from('models');


        $this->db->join('visits', 'models.visit_id = visits.id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('zones', 'outlets.zone = zones.name');


        $this->db->where('zones.zone_for_chart', 1);
        $this->db->where('models.brand_id', $brand_id);
   	$this->db->order_by('zones.code','ASC');
        $this->db->group_by('zones.id');

        $result = $this->db->get();
        $zones = ($result->result());


        foreach ($zones as $zone) {
            $perc = $zone->oos_perc / $zone->total;
            $number = $perc * 100;

            $data[] = number_format($number, 2, '.', '') * 1;
        }
        return json_encode($data);
        //return $data;
    }

    function count_zones() {
        return $this->db->count_all_results('zones');
    }

    function get_zone_by_id($id) {

        $result = $this->db->get_where('zones', array('id' => $id));
        return $result->row();
    }

    function save($zone) {
        if ($zone['id']) {
            $this->db->where('id', $zone['id']);
            $this->db->update('zones', $zone);
            return $zone['id'];
        } else {
            $this->db->insert('zones', $zone);
            return $this->db->insert_id();
        }
    }

    function delete($id) {

        $this->db->where('id', $id);
        $this->db->delete('zones');
    }

    public function get_zone_name($zone_id) {
        return $this->db->get_where('zones', array('id' => $zone_id))->row()->name;
    }

    function check_email($str, $id = false) {
        $this->db->select('email');
        $this->db->from('zones');
        $this->db->where('email', $str);
        if ($id) {
            $this->db->where('id !=', $id);
        }
        $count = $this->db->count_all_results();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

}
