<?php

// bcm visit model

Class Visit_model extends CI_Model {

    //this is the expiration for a non-remember session
    var $dbprefix;

    function __construct() {
        parent::__construct();
        $this->dbprefix = $this->db->dbprefix;
    }

    /*     * ******************************************************************

     * ****************************************************************** */

    function get_pictures($id) {

        $this->db->select('visits.*,'
                . 'visit_pictures.branding_pictures as branding_pictures,'
                . 'visit_pictures.one_pictures as one_pictures');

        $this->db->from('visits');
        $this->db->join('visit_pictures', 'visit_pictures.visit_id=visits.id', 'left');
        $this->db->where('visits.id =', $id);
        $result = $this->db->get();
        return $result->row();
    }

    function get_visits($limit = 0, $offset = 0, $order_by = 'visits.id', $direction = 'DESC', $current_admin_id = -1, $search = -1) {
        $this->db->select('visits.id as id,
            visits.monthly_visit as monthly_visit,
            visits.date as date,
            visits.active as active,
            visits.updated as date_upload,
            visits.remark as remark,
            visits.oos_perc as oos_perc,
            visits.shelf_perc as shelf_perc,
            visits.entry_time as entry_time,
            visits.exit_time as exit_time,
            visits.was_there as was_there,
            visits.exit_latitude as exit_latitude,
            visits.exit_longitude as exit_longitude,
             visits.branding_pictures,
              visits.order_picture,
            visits.updated as created,
            outlets.name as outlet_name,
            outlets.id as outlet_id,
            zones.name as outlet_zone,
            outlets.state as outlet_state,
            admin.name as name,
            CAST(bcc_visits.updated as DATE) as created_date, 
            CAST(bcc_visits.updated as TIME) as created_time', false);

        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id=outlets.id');
        $this->db->join('admin', 'visits.admin_id=admin.id');
        $this->db->join('zones', 'outlets.zone_id=zones.id');

        //$this->db->where('visits.monthly_visit', 0);
        $this->db->order_by('id', 'desc');
        //$this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        if ($search != '-1') {
            $search = strtoupper($search);
            $this->db->where("(UPPER(bcc_outlets.name) LIKE '%" . $search . "%')");
            $this->db->or_where("(UPPER(bcc_admin.name) LIKE '%" . $search . "%')");
            $this->db->or_where("(UPPER(bcc_zones.name) LIKE '%" . $search . "%')");
        }
        if ($current_admin_id != '-1') {
            $this->db->where('admin.id', $current_admin_id);
        }

        $result = $this->db->get();
        return $result->result();
    }

    function get_detail_models($visit_id) {


        $this->db->select('models.id as id,
                            models.product_group_id as product_group_id,
                            product_groups.name as product_name,
                            models.brand_id as brand_id,
                            brands.name as brand_name,

                            models.nb_sku as nb_sku,
                            models.av_sku as av_sku,
                            models.sku_display as sku_display,
                            brands.name as brand_name,
                            models.shelf as shelf,
                            models.av as av,
                            models.y as ny,
                            models.price as price,
                            models.promo_price as promo_price');
        $this->db->from('models');
        $this->db->join('product_groups', 'product_groups.id=models.product_group_id');
        $this->db->join('brands', 'brands.id=product_groups.brand_id');
        $this->db->order_by('brands.code', 'ASC');
        $this->db->order_by('product_groups.code', 'ASC');
        $this->db->order_by('product_groups.category_id', 'ASC');
        $this->db->where('models.visit_id', $visit_id);
        $result = $this->db->get();
        return $result->result();
    }

    function get_detail_daily_models($visit_id) {

        $this->db->select('models.id as id,
                            models.product_id as product_id,
                            products.name as product_name,
                            models.brand_id as brand_id,
                            brands.name as brand_name,

                            models.nb_sku as nb_sku,
                            models.av_sku as av_sku,
                            models.sku_display as sku_display,
                            brands.name as brand_name,
                            models.shelf as shelf,
                            models.av as av,
                            models.price as price,
                             models.promo_price as promo_price');
        $this->db->from('models');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id=product_groups.brand_id');

        $this->db->order_by('brands.code', 'ASC');
        $this->db->order_by('products.code', 'ASC');
        $this->db->order_by('categories.id', 'ASC');
        $this->db->where('models.visit_id', $visit_id);
        $result = $this->db->get();
        return $result->result();
    }

    function get_detail_monthly_models($visit_id) {
        $this->db->select('models.id as id,
            models.nb_sku as nb_sku,,
            models.av_sku as av_sku,
            models.sku_display as sku_display,
            models.shelf as shelf,
            models.av as av,
            models.price as price,
            brands.name as brand_name,
            brands.id as brand_id,
            product_groups.id as product_group_id,
            product_groups.name as product_name');

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');

        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');

        $this->db->join('brands', 'brands.id = product_groups.brand_id');
        $this->db->where('visits.id', $visit_id);
        $this->db->order_by('brands.code', 'ASC');
        $this->db->order_by('product_groups.code', 'ASC');
        $this->db->order_by('categories.id', 'ASC');


        $result = $this->db->get();
        return $result->result();
    }

    function get_today_visits($limit = 0, $offset = 0, $order_by = 'visits.id', $direction = 'DESC', $current_admin_id = -1) {
        $this->db->select('visits.longitude as longitude,visits.latitude as latitude,visits.id as id,visits.monthly_visit as monthly_visit,outlets.name as outlet_name,outlets.id as outlet_id,outlets.zone as outlet_zone,outlets.state as outlet_state,admin.name as name,
		visits.date as date,visits.active as active,visits.updated as date_upload,
		remark as remark,visits.oos_perc as oos_perc,visits.entry_time as entry_time,visits.exit_time as exit_time');
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
        $this->db->order_by('zones.name');
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
        $this->db->order_by('zones.name');
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

    function get_outlets_not_visited() {



        $active_outlets = $this->Outlet_model->get_active_outlets();

        $active_outlets_id = array();
        foreach ($active_outlets as $outlet) {
            $active_outlets_id[] = $outlet->id;
        }



        $this->db->select('visits.outlet_id', false);
        $this->db->from('visits');



        $first_day_of_month = date('01-m-y');

        $this->db->where('date', $first_day_of_month);

        $this->db->group_by('visits.outlet_id');

        $result = $this->db->get();
        $res = $result->result();



        foreach ($res as $r) {
            $outlets[] = $r->outlet_id;
        }





        $outlet_ids = array_diff($active_outlets_id, $outlets);


        foreach ($outlet_ids as $id) {
            $outlets_not_visited[] = $this->Outlet_model->get_outlet_name($id);
        }

        return $outlets_not_visited;
    }

    function get_visit_av_per_zone($zone) {

        $this->db->select('sum(hcs_models.av) as oos_perc,sum(CASE WHEN hcs_models.sku_display != 0 THEN 1 ELSE 0 END) as oos_perc_other,count(hcs_models.id) as total,brands.name as brand_name', false);
        $this->db->from('visits');



        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = hcs_models.brand_id');

        $this->db->where('outlets.zone', $zone);
        $this->db->where('zones.zone_for_chart', 1);
        $this->db->where('brands.brand_for_chart', 1);
        $this->db->group_by('brands.id');
        //$this->db->group_by('zones.id');
        $result = $this->db->get();
        $zones = ($result->result());


        foreach ($zones as $zone) {
            if ($zone->brand_name == 'Henkel') {
                $perc = $zone->oos_perc / $zone->total;
            } else {
                $perc = $zone->oos_perc_other / $zone->total;
            }

            $number = $perc * 100;

            $data[] = number_format($number, 2, '.', '') * 1;
        }
        return json_encode($data);
        //return $data;
    }

    function get_visit_oos_per_zone($zone) {
        $this->db->select('sum(hcs_models.av) as oos_perc,sum(CASE WHEN hcs_models.sku_display != 0 THEN 1 ELSE 0 END) as oos_perc_other,count(hcs_models.id) as total,brands.name as brand_name', false);
        $this->db->from('visits');



        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = hcs_models.brand_id');

        $this->db->where('outlets.zone', $zone);
        $this->db->where('zones.zone_for_chart', 1);
        $this->db->where('brands.brand_for_chart', 1);
        $this->db->group_by('brands.id');
        //$this->db->group_by('zones.id');
        $result = $this->db->get();
        $zones = ($result->result());


        foreach ($zones as $zone) {
            if ($zone->brand_name == 'Henkel') {
                $perc = $zone->oos_perc / $zone->total;
            } else {
                $perc = $zone->oos_perc_other / $zone->total;
            }
            $number = (1 - $perc) * 100;
            $data[] = number_format($number, 2, '.', '') * 1;
        }
        return json_encode($data);
        //return $data;
    }

    function get_visit_av() {

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
            $number = (1 - $perc) * 100;
            $data[] = number_format($number, 2, '.', '') * 1;
        }
        return json_encode($data);
        //return $data;
    }

    function get_visits_update_models($limit = 800, $offset = 0, $order_by = 'id', $direction = 'DESC') {
        $this->db->order_by($order_by, $direction);
        $this->db->limit($limit, $offset);


        $result = $this->db->get('visits');
        return $result->result();
    }

    function count_visits($current_admin_id = '-1', $search = '-1') {

        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('admin', 'admin.id=visits.admin_id');
        $this->db->join('zones', 'zones.id=outlets.zone_id');

        if ($current_admin_id != '-1') {
            $this->db->where('visits.admin_id', $current_admin_id);
        }

        if ($search != '-1') {
            $search = strtoupper($search);
            $this->db->where("(UPPER(bcc_outlets.name) LIKE '%" . $search . "%')");
            $this->db->or_where("(UPPER(bcc_admin.name) LIKE '%" . $search . "%')");
            $this->db->or_where("(UPPER(bcc_zones.name) LIKE '%" . $search . "%')");
        }


        return $this->db->count_all_results('visits');
    }

    function count_visits_search($search = '-1', $fo_id = '-1', $start_date = '-1', $end_date = '-1', $search_type = '-1') {
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('admin', 'admin.id=visits.admin_id');
        $this->db->join('zones', 'zones.id=outlets.zone_id');

        if ($search != '-1') {
            $search = strtoupper($search);
            $this->db->where("(UPPER(bcc_outlets.name) LIKE '%" . $search . "%')");
            $this->db->or_where("(UPPER(bcc_admin.name) LIKE '%" . $search . "%')");
            $this->db->or_where("(UPPER(bcc_zones.name) LIKE '%" . $search . "%')");
        }

        if ($search_type == 1 || $search_type == 2) {
            $this->db->where_in('visits.monthly_visit', array(1, 3));
        }
//        else if ($search_type == 2) {
//            $this->db->where_in('visits.monthly_visit', array(2, 3));
//        }

        if ($fo_id != '-1') {
            $this->db->where('visits.admin_id', $fo_id);
        }

        if ($start_date != '-1' && $end_date != '-1') {
            $this->db->where('visits.date >=', $start_date);
            $this->db->where('visits.date <=', $end_date);
        }



        return $this->db->count_all_results('visits');
        //$result = $this -> db -> get('sales');
        //return count($result -> result());
    }

    function get_visits_search($limit = 0, $offset = 0, $order_by = 'visits.id', $direction = 'DESC', $search, $fo_id, $start_date, $end_date, $search_type) {
        $this->db->select('visits.id as id,
            visits.monthly_visit as monthly_visit,
            outlets.name as outlet_name,
            outlets.id as outlet_id,
            outlets.zone as outlet_zone,
            outlets.state as outlet_state,
            admin.name as name,
            visits.shelf_perc as shelf_perc,
            visits.oos_perc as oos_perc,
            visits.date as date,
            visits.active as active,
            visits.updated as date_upload,
            remark as remark,
               visits.branding_pictures,
              visits.order_picture,
             visits.entry_time as entry_time,
            visits.exit_time as exit_time,
            visits.was_there as was_there,
            visits.exit_latitude as exit_latitude,
            visits.exit_longitude as exit_longitude,
            CAST(bcc_visits.updated as DATE) as created_date,
            CAST(bcc_visits.updated as TIME) as created_time', false);


        $this->db->from('visits');
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('admin', 'admin.id=visits.admin_id');
        $this->db->join('zones', 'zones.id=outlets.zone_id');


        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        if ($search != '-1') {
            $search = strtoupper($search);
            $this->db->where("(UPPER(bcc_outlets.name) LIKE '%" . $search . "%')");
            $this->db->or_where("(UPPER(bcc_admin.name) LIKE '%" . $search . "%')");
            $this->db->or_where("(UPPER(bcc_zones.name) LIKE '%" . $search . "%')");
        }
        if ($fo_id != '-1') {
            $this->db->where('visits.admin_id', $fo_id);
        }
        if ($start_date != '-1' && $end_date != '-1') {
            $this->db->where('visits.date >=', $start_date);
            $this->db->where('visits.date <=', $end_date);
        }
        // 1 shelf   2 price
//        if ($search_type == 1) {
//            $this->db->where_in('visits.monthly_visit', array(1, 3));
//        } 
//        //price
//        else if ($search_type == 2) {
//            $this->db->where_in('visits.monthly_visit', array(2, 3));
//        } 
        if ($search_type == 1 || $search_type == 2) {
            $this->db->where_in('visits.monthly_visit', array(1, 3));
        }
        //daily
        else if ($search_type == 0) {
            $this->db->where_in('visits.monthly_visit', array(0));
        }

        $result = $this->db->get();
        return $result->result();
    }

    function get_visit($id) {

        $result = $this->db->get_where('visits', array('id' => $id));
        return $result->row();
    }

    function save($visit) {
        if ($visit['id']) {
            $this->db->where('id', $visit['id']);
            $this->db->update('visits', $visit);
            return $visit['id'];
        } else {
            $last_visit_id = $this->Outlet_model->get_outlet($visit['outlet_id'])->visit_id;
            $visit['last_visit_id'] = $last_visit_id;

            //$this->db->trans_start(); # Starting Transaction

            $this->db->insert('visits', $visit);
            $inserted_visit_id = $this->db->insert_id();

            $products = $this->Product_model->get_active_products();

            foreach ($products as $product) {
                $data[] = array(
                    'visit_id' => $inserted_visit_id,
                    'product_id' => $product->id,
                    'brand_id' => $product->brand_id,
                    'category_id' => $product->category_id,
                    'sub_category_id' => $product->sub_category_id,
                    'product_group_id' => $product->product_group_id,
                    'cluster_id' => $product->cluster_id,
                    'nb_sku' => $product->nb_sku,
                    'av_sku' => 0,
                    'av' => 1,
                    'shelf' => 0,
                    'price' => 0);
            }

            //save models
            $this->db->insert_batch('models', $data);
            return $inserted_visit_id;
        }
    }

    function get_bccail_report_models($visit_id) {
        $this->db->select('models.id as id,models.product_id as product_id,models.model_name as model_name,
		brands.name as brand_name,models.shelf as shelf,models.ws as ws,models.price as price,
		models.amount as amount,models.price as std_price');
        $this->db->join('brands', 'brands.id=models.brand_id');
        $this->db->join('models', 'models.id=models.product_id');
        $this->db->from('models');
        $this->db->order_by('brands.code', 'ASC');
        $this->db->order_by('models.product_id', 'ASC');
        $this->db->where('models.visit_id', $visit_id);
        $this->db->where('models.ws >', 0);
        $result = $this->db->get();
        return $result->result();
    }

    function save_bulk($model) {
        if ($model['id']) {
            $this->db->where('id', $model['id']);
            $this->db->update('models', $model);
            return $model['id'];
        }
    }

    function save_visit_picture($comp) {
        if ($comp['id']) {
            $this->db->where('id', $comp['id']);
            $this->db->update('visits', $comp);
            return $comp['id'];
        } else {

            $this->db->insert('visits', $comp);
            return $this->db->insert_id();
        }
    }

    ///////////////////////
    function count_visits_new($search = '') {
        if ($search != '') {
            $this->db->where("(
		UPPER(visits.outlet_id) LIKE '%" . $search . "%'
		
		)");
        }
        return $this->db->count_all_results('visit');
    }

    ///////////
    function get_all_visits($limit = 0, $offset = 0, $order_by = 'visits.id', $direction = 'DESC', $search = '') {
        $this->db->select('visits.*');
        $this->db->from('visits');


        if ($search != '') {
            $this->db->where("(
		UPPER(visits.outlet_id) LIKE '%" . $search . "%'
		)");
        }

        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get();
        return $result->result();
    }

    ////////////////
    //////////////////////
    //////////////////////
    function get_visits_by_date($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC', $date = '') {
        $this->db->select('visits.*');
        $this->db->where('date', $date);
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get('visits');
        return $result->result();
    }

    function get_recent_visits() {
        $this->db->select('visits.*', false);
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.id = outlets.visit_id');
        $result = $this->db->get();
        return $result->result();
    }

    function get_visits_by_zone($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC', $zone_id = '') {
        $this->db->select('visits.*');
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');
        $this->db->where('outlets.zone_id', $zone_id);
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get('visits');
        return $result->result();
    }

    function get_visits_by_date_zone($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC', $date, $zone_id = '') {
        $this->db->select('visits.*');
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');
        $this->db->where('outlets.zone_id', $zone_id);
        $this->db->where('date', $date);
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get('visits');
        return $result->result();
    }

    function get_visits_by_id($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC', $admin_id) {

        $this->db->where('admin_id', $admin_id);
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get('visits');
        return $result->result();
    }

    function count_visits_by_date($start, $end) {
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        return $this->db->count_all_results('visits');
    }

    function count_shortage_visits_by_date($start, $end) {
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('sales', 1);
        return $this->db->count_all_results('visits');
    }

    function count_shortage_visits_by_date_admin($start, $end, $admin_id) {
        $this->db->where('admin_id', $admin_id);
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('sales', 1);
        return $this->db->count_all_results('visits');
    }

    function count_all_weekly_admin($admin_id) {
        $this->db->where('admin_id', $admin_id);

        return $this->db->count_all_results('visits');
    }

    function count_all_shortage_visits_by_admin($admin_id) {
        $this->db->where('admin_id', $admin_id);
        $this->db->where('sales', 1);
        return $this->db->count_all_results('visits');
    }

    function count_all_shortage_visits() {

        $this->db->where('sales', 1);
        return $this->db->count_all_results('visits');
    }

    function count_visits_by_date_admin($start, $end, $admin_id) {
        $this->db->where('admin_id', $admin_id);
        $today = date('Y-m-d');
        $this->db->where('date >=', $start);
        $this->db->where('date <=', $end);
        $this->db->where('type', 'Weekly visit');


        return $this->db->count_all_results('check_positions');
    }

    function count_today_visits() {

        $today = date('Y-m-d');
        $this->db->where('date', $today);




        return $this->db->count_all_results('visits');
    }

    function count_month_visits() {

        $month = date('01-m-d');
        $this->db->where('date >=', $month);




        return $this->db->count_all_results('visits');
    }

    // function count_visits_by_date2($start,$end) {
    // $this -> db -> where('date >=', $start);
    // $this -> db -> where('date <=', $end);
    // $this -> db -> where('type', 'Weekly visit');
    // return $this -> db -> count_all_results('check_positions');
    // }




    function get_visit_by_outlet($id) {

        $result = $this->db->get_where('visits', array('id' => $id));
        return $result->row();
    }

    function copy($visit, $visit_id) {

        $this->db->insert('visits', $visit);

        $new_visit_id = $this->db->insert_id();

        $models = $this->get_models($visit_id);

        foreach ($models as $model) {
            $data['id'] = false;
            $data['visit_id'] = $new_visit_id;
            $data['visit_uniqueId'] = $model->visit_uniqueId;
            $data['av'] = $model->av;
            $data['price'] = $model->price;
            $data['promo_price'] = $model->promo_price;
            $data['shelf'] = $model->shelf;
            $data['total_metrage'] = $model->total_metrage;
            $data['metrage_unit'] = $model->metrage_unit;
            $data['product_id'] = $model->product_id;
            $data['brand_id'] = $model->brand_id;
            $data['category_id'] = $model->category_id;
            $data['cluster_id'] = $model->cluster_id;
            $data['product_group_id'] = $model->product_group_id;
            $data['target'] = $model->target;
            $data['av_sku'] = $model->av_sku;
            $data['nb_sku'] = $model->nb_sku;
            $data['sku_display'] = $model->sku_display;

            //$data['model_id'] = $model->model_id;
            //$data['category_id'] = $model->category_id;
            //$data['brand_id'] = $model->brand_id;
            //$data['range_id'] = $model->range_id;
            //$data['price_range_id'] = $model->price_range_id;
            //$data['shelf'] = $model->shelf;
            //$data['price'] = $model->price;
            //$data['shortage'] = $model->shortage;
            //$data['ws'] = $model->ws;
            //$data['amount'] = $model->amount;

            $this->insert_model($data);
        }

        return $new_visit_id;
    }

    function get_models($visit_id) {
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get_where('models', array('visit_id' => $visit_id));
        return $result->result();
    }

    function get_all_models() {
        $result = $this->db->get('models');
        return $result->result();
    }

    function save_model($model) {
        if ($model['id']) {
            $this->db->where('id', $model['id']);
            $this->db->update('models', $model);
            return $model['id'];
        } else {
            $this->db->insert('models', $model);
            return $this->db->insert_id();
        }
    }

    function insert_model($model) {
        $this->db->insert('models', $model);
        return $this->db->insert_id();
    }

    function update_outlet() {
        
    }

    function deactivate($id) {
        $visit = array('id' => $id, 'active' => 0);
        $this->save($visit);
    }

    function activate($id) {
        $visit = array('id' => $id, 'active' => 1);
        $this->save($visit);
    }

    function delete($id) {


        $this->db->where('id', $id);

        $this->db->delete('visits');
    }

    function delete_models($id) {


        $this->db->where('visit_id', $id);

        $this->db->delete('models');
    }

    public function get_outlet_id($visit_id) {
        return $this->db->get_where('visits', array('id' => $visit_id))->row()->outlet_id;
    }

    function count_zones_by_date($dt, $zone_id) {
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');
        $this->db->where('outlets.zone_id', $zone_id);
        $this->db->where('visits.date', $dt);
        return $this->db->count_all_results('visits');
    }

    function get_visit_date($visit_id) {

        return $this->db->get_where('visits', array('id' => $visit_id))->row()->date;
    }

    function compare_models1($visit_id) {
        $this->db->where('models.active', 1);
        $this->db->where('mb_models.id NOT IN (SELECT mb_models.model_id FROM mb_models  WHERE mb_models.visit_id = ' . $visit_id . ')', NULL, FALSE);
        $result = $this->db->get('models');
        return $result->result();
    }

    function compare_models($id) {
        //$subquery=$this -> db -> query('SELECT mb_models.model_id FROM mb_models  WHERE mb_models.visit_id = '.$visit_id);
        //$this -> db -> where('models.active', 1);
        //$this->db->where_not_in('mb_models.id', $subquery);
        //$this->db->where_not_in('mb_models.id NOT IN (SELECT mb_models.model_id FROM mb_models  WHERE mb_models.visit_id = '.$visit_id.')', NULL, FALSE);
        //$result = $this -> db -> get('models');
        $this->db->select('models.id');
        $this->db->where('models.active', 1);
        $query1 = $this->db->get('models');
        $tab1 = array();
        foreach ($query1->result() as $row) {
            $tab1[] = $row->id;
        }

        $this->db->select('models.model_id');
        $this->db->where('models.visit_id', $id);
        $query2 = $this->db->get('models');
        $tab2 = array();
        foreach ($query2->result() as $row) {
            $tab2[] = $row->model_id;
        }

        //weekly models
        return array_values(array_diff($tab1, $tab2));
    }

    function compare_models2($id) {

        $this->db->select('models.model_id');
        $this->db->where('models.visit_id', $id);
        $query = $this->db->get('models');
        $tab = array();
        foreach ($query->result() as $row) {
            $tab[] = $row->model_id;
        }
        return $tab;
    }

    function get_last_visit_id($outlet_id = false) {
        $maxid = -1;
        $this->db->select_max('id');
        $result = $this->db->get_where('visits', array('outlet_id' => $outlet_id));
        $row = $result->row();
        $num = $result->num_rows();

        if ($num > 2) {
            $maxid = $row->id;
        } else {

            $maxid = -1;
        }
        return $maxid;
    }

    function get_bccail_report_visit_pictures($visit_id) {
        $result = $this->db->get_where('visits', array('id' => $visit_id));
        return $result->row();
    }

    function save_picture($save) {
        $this->db->insert('visit_pictures', $save);
        $inserted_picture_id = $this->db->insert_id();
        return $inserted_picture_id;
    }

}
