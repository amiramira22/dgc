<?php

//bcm
Class Report_model extends CI_Model {

    //this is the expiration for a non-remember session
    var $dbprefix;

    function __construct() {
        parent::__construct();
        $this->dbprefix = $this->db->dbprefix;
        $this->db->query("SET SESSION sql_mode = 'TRADITIONAL'");
    }

    /*     * **************************************** */

    // Stock issues report (Stats)   
    /*     * **************************************** */
    function get_active_henkel_products($id = 0, $limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
        $this->db->select('products.*,'
                . 'product_groups.name as product_group_name,'
                . 'brands.name as brand_name,'
                . 'clusters.name as cluster_name,'
                . 'sub_categories.name as sub_category_name,'
                . 'categories.name as category_name');
        $this->db->from('products');
        $this->db->join('brands', 'brands.id = products.brand_id', 'left');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id', 'left');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id', 'left');
        $this->db->join('sub_categories', 'sub_categories.id=clusters.sub_category_id', 'left');
        $this->db->join('categories', 'categories.id = sub_categories.category_id ', 'left');
        $this->db->where('brands.id', 1);


        $this->db->where('products.active', 1);
        if ($id != 0) {
            $this->db->where('product_groups.id', $id);
        }
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $result = $this->db->get();
        return $result->result();
    }

    // Availibility grouped by Brands and Dates (Multi Dates) --- Boulbaba 26/01/2018
    public function get_av_multi_date_brand($date_type, $start_date, $end_date, $category_id, $zone_ids, $channel_ids) {

        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select(
                'visits.' . $date . ' as date,
		brands.name as brand_name,
                brands.color as brand_color,
                count(bcc_models.id) as total,
		(sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
                (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }

        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.id', 1);

        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('brands.id');
        $this->db->order_by('visits.' . $date, 'ASC');
        return $this->db->get()->result_array();
    }

    // Availibility grouped by Brands and Zones (Single date) --- Boulbaba 27/01/2018
    public function get_av_single_date_brand_zones($date_type, $start_date, $end_date, $category_id, $zone_ids, $channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
                zones.name as zone,
		brands.name as brand_name,
                count(bcc_models.id) as total,
		(sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
                (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha ', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.id', 1);

        $this->db->group_by('zones.id');
        $this->db->group_by('brands.id');
        return $this->db->get()->result_array();
    }

    // Availibility grouped by Brands and Outlet types (Single date)  --- Boulbaba 27/01/2018
    public function get_av_single_date_brand_channels($date_type, $start_date, $end_date, $category_id, $zone_ids, $channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
                channels.name as channel,
		brands.name as brand_name,
                count(bcc_models.id) as total,
		(sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
                (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha ', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');

        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.id', 1);

        $this->db->group_by('channels.id');
        $this->db->group_by('brands.id');
        return $this->db->get()->result_array();
    }
    
    public function get_av_single_date_brand_sub_channels($date_type, $start_date, $end_date, $category_id, $zone_ids, $channel_ids, $sub_channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
                sub_channels.name as sub_channel,
		brands.name as brand_name,
                count(bcc_models.id) as total,
		(sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
                (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha ', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
         $this->db->join('sub_channels', 'sub_channels.id = outlets.sub_channel_id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');

        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        
         if (!empty($sub_channel_ids) && $sub_channel_ids != '-1') {
            $this->db->where_in('sub_channels.id', $sub_channel_ids);
            $this->db->order_by('sub_channels.id', 'ASC');
        }
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.id', 1);

        $this->db->group_by('sub_channels.id');
        $this->db->group_by('brands.id');
        return $this->db->get()->result_array();
    }

    // Availibility for each cluster : grouped by Brands and Dates (Multi date)  --- Boulbaba 27/01/2018
    public function get_av_cluster($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_id, $channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('visits.' . $date . ' as date,
		products.id as product_id,
                count(bcc_models.id) as total,
		(sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
                (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha ', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('channels', 'channels.id=outlets.channel_id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');


        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }

        if ($zone_id != '-1') {
            $this->db->where('zones.id  ', $zone_id);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        $this->db->where('clusters.id', $cluster_id);
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.id', 1);

        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('products.id');
        $this->db->order_by('visits.' . $date, 'ASC');
        return $this->db->get()->result_array();
    }

    // Availibility for each cluster : grouped by Brands and Zones (Single date)  --- Boulbaba 27/01/2018
    public function get_av_cluster_zones($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_ids, $channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
                zones.name as zone,
		products.id as product_id,
                count(bcc_models.id) as total,
		(sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
                (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha ', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        $this->db->where('clusters.id', $cluster_id);

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        //$this->db->where('products.active', 0);

        $this->db->where('brands.id', 1); //
        $this->db->group_by('zones.id');
        $this->db->group_by('products.id');
        return $this->db->get()->result_array();
    }

    // Availibility for each cluster : grouped by Brands and Outlet types (Single date)  --- Boulbaba 27/01/2018
    public function get_av_cluster_channels($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_ids, $channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
                channels.name as channel,
		products.id as product_id,
                count(bcc_models.id) as total,
		(sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
                (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha ', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }

        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        $this->db->where('clusters.id', $cluster_id);

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        $this->db->where('brands.id', 1); //
        //$this->db->where('products.active', 0);

        $this->db->group_by('channels.id');
        $this->db->group_by('products.id');
        return $this->db->get()->result_array();
    }
    
    
     public function get_av_cluster_sub_channels($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_ids, $channel_ids, $sub_channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
                sub_channels.name as sub_channel,
		products.id as product_id,
                count(bcc_models.id) as total,
		(sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
                (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha ', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('sub_channels', 'sub_channels.id = outlets.sub_channel_id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }

        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        
         if (!empty($sub_channel_ids) && $sub_channel_ids != '-1') {
            $this->db->where_in('sub_channels.id', $sub_channel_ids);
            $this->db->order_by('sub_channels.id', 'ASC');
        }
        $this->db->where('clusters.id', $cluster_id);

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        $this->db->where('brands.id', 1); //
        //$this->db->where('products.active', 0);

        $this->db->group_by('sub_channels.id');
        $this->db->group_by('products.id');
        return $this->db->get()->result_array();
    }


    /*     * ************************ End Stock Issues Reports  ************************** */
    /*     * ***************************************************************************** */
    /*     * ***************************************************************************** */


    /*     * **************************************** */

    // Shelf share report (Stats)   
    /*     * **************************************** */

// Shelf share grouped by Brands and Dates (Multi Dates) --- Amira 29/01/2018
    public function get_shelf_multi_date_brand($date_type, $start_date, $end_date, $category_id, $zone_ids, $channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select(
                'visits.' . $date . ' as date,
		brands.name as brand_name,
                brands.color as brand_color,
                sum(bcc_models.shelf) as shelf,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id =visits.outlet_id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
//        if ($zone_id != '-1') {
//            $this->db->where('zones.id  ', $zone_id);
//            $this->db->order_by('zones.code', 'ASC');
//        }
//        if ($channel_id && $channel_id != '-1') {
//            $this->db->where('channels.id', $channel_id);
//            $this->db->order_by('channels.id', 'ASC');
//        }


        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);
        $this->db->where_in('visits.monthly_visit', array('1', '3'));

        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('brands.id');
        $this->db->order_by('visits.' . $date, 'asc');
        $this->db->order_by('metrage', 'desc');

        return $this->db->get()->result_array();
    }

    // Availibility grouped by Brands and Zones (Single date) --- Boulbaba 27/01/2018
    public function get_shelf_single_date_brand_zones($date_type, $start_date, $end_date, $category_id, $zone_ids, $channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
                zones.name as zone,
		brands.name as brand_name,
                brands.color as color,
                sum(bcc_models.shelf) as shelf,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit', array('1', '3'));

        $this->db->group_by('zones.id');
        $this->db->group_by('brands.id');

        $this->db->order_by('metrage', 'desc');
        return $this->db->get()->result_array();
    }

    // Shelf Share grouped by Brands and Outlet types (Single date)  --- Boulbaba 27/01/2018
    public function get_shelf_single_date_brand_channels($date_type, $start_date, $end_date, $category_id, $zone_ids, $channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
                channels.name as channel,
		brands.name as brand_name,
                brands.color as color,
                sum(bcc_models.shelf) as shelf,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit', array('1', '3'));

        $this->db->group_by('channels.id');
        $this->db->group_by('brands.id');

        $this->db->order_by('metrage', 'desc');
        return $this->db->get()->result_array();
    }

    // Shelf share for each cluster : grouped by Brands and Dates (Multi date)  --- Amira 29/01/2018
    public function get_shelf_cluster($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_id, $channel_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }

        $this->db->select(
                'visits.' . $date . ' as date,
		product_groups.id as product_id,
                sum(bcc_models.shelf) as shelf,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('channels', 'channels.id=outlets.channel_id');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
        if ($zone_id != '-1') {
            $this->db->where('zones.id  ', $zone_id);
            $this->db->order_by('zones.code', 'ASC');
        }
        if ($channel_id && $channel_id != '-1') {
            $this->db->where('channels.id', $channel_id);
            $this->db->order_by('channels.id', 'ASC');
        }
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit ', array('1', '3'));
        $this->db->where('clusters.id', $cluster_id);

        $this->db->group_by('product_groups.id');
        $this->db->group_by('visits.' . $date);
        $this->db->order_by('visits.' . $date, 'asc');
        $this->db->order_by('metrage', 'desc');

        return $this->db->get()->result_array();
    }

    // Availibility for each cluster : grouped by Brands and Zones (Single date)  --- Boulbaba 27/01/2018
    public function get_shelf_cluster_zones($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_ids, $channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
                zones.name as zone,
		product_groups.id as product_id,
                sum(bcc_models.shelf) as shelf,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage ', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('channels', 'channels.id=outlets.channel_id');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        $this->db->where('clusters.id', $cluster_id);
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit ', array('1', '3'));

        $this->db->group_by('zones.name');
        $this->db->group_by('product_groups.id');

        $this->db->order_by('metrage', 'desc');
        $this->db->order_by('product_groups.id', 'asc');
        return $this->db->get()->result_array();
    }

    // Shelf Share for each cluster : grouped by Brands and Outlet types (Single date)  --- Boulbaba 27/01/2018
    public function get_shelf_cluster_channels($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_ids, $channel_ids) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
                channels.name as channel,
		product_groups.id as product_id,
                sum(bcc_models.shelf) as shelf,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage ', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }

        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
            $this->db->order_by('zones.code', 'ASC');
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
            $this->db->order_by('channels.id', 'ASC');
        }
        $this->db->where('clusters.id', $cluster_id);
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit ', array('1', '3'));

        $this->db->group_by('channels.name');
        $this->db->group_by('product_groups.id');

        $this->db->order_by('metrage', 'desc');
        return $this->db->get()->result_array();
    }

    // Shelf share grouped by Brands and Dates (Multi Dates) --- Amira 29/01/2018
    public function get_shelf_zone_pie_chart($date_type, $start_date, $end_date, $category_id, $zone_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
		brands.name as brand_name,
                brands.color as brand_color,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage
		', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');
        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }

        if ($zone_id != '-1') {
            $this->db->where('zones.id  ', $zone_id);
        }

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit', array('1', '3'));
        $this->db->group_by('brands.name');
        $this->db->order_by('visits.' . $date, 'asc');
        $this->db->order_by('metrage', 'desc');

        $query = $this->db->get()->result();
        $row_data = array();
        $data = array();
        foreach ($query as $row) {
            $row_data['brand_name'] = $row->brand_name;
            $row_data['brand_color'] = $row->brand_color;
            $row_data['metrage'] = number_format($row->metrage, 2);
            $data[] = $row_data;
        }
        //print_r($data);
        return json_encode($data);
    }

    // Shelf share grouped by Brands and Dates (Multi Dates) --- Amira 29/01/2018
    public function get_shelf_channel_pie_chart($date_type, $start_date, $end_date, $category_id, $channel_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select('
		brands.name as brand_name,
                brands.color as brand_color,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage
		', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');
        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }

        if ($channel_id && $channel_id != '-1') {
            $this->db->where('channels.id', $channel_id);
        }

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit', array('1', '3'));
        $this->db->group_by('brands.name');
        $this->db->order_by('visits.' . $date, 'asc');
        $this->db->order_by('metrage', 'desc');
        $query = $this->db->get()->result();
        $row_data = array();
        $data = array();
        foreach ($query as $row) {
            $row_data['brand_name'] = $row->brand_name;
            $row_data['brand_color'] = $row->brand_color;
            $row_data['metrage'] = number_format($row->metrage, 2);
            $data[] = $row_data;
        }
        //print_r($data);
        return json_encode($data);
    }

    public function get_total_metrage($date_type, $start_date, $end_date, $category_id, $zone_ids, $channel_ids) {
        //$results = array();
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select(
                'visits.' . $date . ' as date,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id= visits.outlet_id ');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        //jointure avec product group pour un correct cluster
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit ', array('1', '3'));

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
        if (!empty($zone_ids) && $zone_ids != '-1') {
            $this->db->where_in('zones.id  ', $zone_ids);
        }
        if (!empty($channel_ids) && $channel_ids != '-1') {
            $this->db->where_in('channels.id', $channel_ids);
        }
        $this->db->group_by('visits.' . $date);
        $this->db->order_by('visits.' . $date, 'asc');
        $results = $this->db->get()->result();
        $sum_metrage_array = array();
        foreach ($results as $row) {
            $sum_metrage_array[$row->date] = $row->metrage;
        }

        return $sum_metrage_array;
    }

    public function get_total_metrage_by_zone($date_type, $start_date, $end_date, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select(
                'zones.name as zone,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id= visits.outlet_id ');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit ', array('1', '3'));


        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }

        $this->db->group_by('zones.name');

        $this->db->order_by('visits.' . $date, 'asc');
        $results = $this->db->get()->result();
        $sum_metrage = array();
        foreach ($results as $row) {
            $sum_metrage[$row->zone] = $row->metrage;
        }

        return $sum_metrage;
    }

    public function get_total_metrage_by_channels($date_type, $start_date, $end_date, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select(
                'channels.name as channel,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id= visits.outlet_id ');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit ', array('1', '3'));

        $this->db->where('brands.active ', 1);

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
        $this->db->group_by('channels.name');
        $this->db->order_by('visits.' . $date, 'asc');
        $results = $this->db->get()->result();
        $sum_metrage = array();
        foreach ($results as $row) {
            $sum_metrage[$row->channel] = $row->metrage;
        }

        return $sum_metrage;
    }

    public function get_total_metrage_by_outlets($date_type, $start_date, $end_date, $category_id, $channel_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }
        $this->db->select(
                'outlets.name as outlet,
                sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id= visits.outlet_id ');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit ', array('1', '3'));

        $this->db->where('brands.active ', 1);

        if ($category_id != '-1') {
            $this->db->where('categories.id', $category_id);
        }
        if ($channel_id && $channel_id != '-1') {
            $this->db->where('channels.id ', $channel_id);
        }
        $this->db->group_by('outlets.name');
        $this->db->order_by('visits.' . $date, 'asc');
        $results = $this->db->get()->result();
        $sum_metrage = array();
        foreach ($results as $row) {
            $sum_metrage[$row->outlet] = $row->metrage;
        }

        return $sum_metrage;
    }

    /*     * ************************ End Shelf share Reports  ************************** */
    /*     * ***************************************************************************** */
    /*     * ***************************************************************************** */

//    public function get_tracking_oos($outlet_id, $product_id) {
//
//        $this->db->select('visits.date as date,
//                          products.id as product_id,
//                          (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END))/(count(bcc_models.id)) as av', false);
//
//        $this->db->from('models');
//
//        $this->db->join('visits', 'visits.id = models.visit_id');
//        $this->db->join('outlets', ' outlets.id = visits.outlet_id');
//        $this->db->join('products', 'models.product_id=products.id');
//        $this->db->join('product_groups', 'products.product_group_id=product_groups.id');
//        $this->db->join('brands', ' product_groups.brand_id=brands.id');
//
//        $this->db->where('visits.m_date >= ', date('Y-01-01'));
//        $this->db->where('outlets.id', $outlet_id);
//        $this->db->where('products.id', $product_id);
//        $this->db->where('brands.id', 18);
//
//        //$this->db->group_by('products.id');
//        $this->db->group_by('visits.date');
//        $this->db->order_by('visits.date', 'asc');
//        //$this->db->order_by('brands.id', 'asc');
//
//
//        return $this->db->get()->result_array();
//    }
//
//    public function get_tracking_oos_old($outlet_id) {
//
//        $this->db->select('visits.date as date,
//                          products.id as product_id,
//                          (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END))/(count(bcc_models.id)) as av', false);
//
//        $this->db->from('models');
//
//        $this->db->join('visits', 'visits.id = models.visit_id');
//        $this->db->join('outlets', ' outlets.id = visits.outlet_id');
//        $this->db->join('products', 'models.product_id=products.id');
//        $this->db->join('product_groups', 'products.product_group_id=product_groups.id');
//        $this->db->join('brands', ' product_groups.brand_id=brands.id');
//
//        $this->db->where('visits.m_date >= ', date('Y-01-01'));
//        $this->db->where('outlets.id', $outlet_id);
//
//        $this->db->group_by('products.id');
//        $this->db->group_by('visits.date');
//
//        $this->db->order_by('visits.date', 'asc');
//        //$this->db->order_by('brands.id', 'asc');
//
//
//        return $this->db->get()->result_array();
//    }
    // Stock Issues POS Report ---- Boulbaba 18/02/2018 bcm
    function get_pos_stock_issues($date_type, $start_date, $end_date, $category_id, $channel_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('
        products.id as product_id,
        outlets.id as outlet_id,
        outlets.name as outlet_name,
        count(bcc_models.id) as total,
        (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
        (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
        (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        if ($channel_id && $channel_id != '-1') {
            $this->db->where('channels.id ', $channel_id);
        }

        if ($category_id) {
            $this->db->where('categories.id ', $category_id);
        }

        //$this->db->where('brands.name', 'HENKEL');

        
        $this->db->group_by('outlets.id');
        $this->db->group_by('products.id');
        $this->db->order_by('outlets.name', 'ASC');
        $this->db->order_by('brands.code', 'DESC');
        //print_r($this->db->get()->result_array());die();
        return $this->db->get()->result_array();
    }

    // Shelf share POS Report ---- Boulbaba 18/02/2018
    function get_pos_shelf_share($date_type, $start_date, $end_date, $category_id, $channel_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'q_date';
        }

        $this->db->select('
        product_groups.id as product_id,
        outlets.name as outlet_name,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage
        ', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        $this->db->where_in('visits.monthly_visit ', array('1', '3'));

        if ($channel_id && $channel_id != '-1') {
            $this->db->where('channels.id ', $channel_id);
        }

        if ($category_id) {
            $this->db->where('categories.id ', $category_id);
        }

        //$this->db->where('brands.name', 'HENKEL');

        $this->db->group_by('product_groups.id');
        $this->db->group_by('outlets.name');
        $this->db->order_by('outlets.name', 'ASC');
        $this->db->order_by('brands.code', 'ASC');
        return $this->db->get()->result_array();
    }

    //08/02/2018 bcm
    public function get_pos_data($outlet_id, $start_date, $end_date) {

        $this->db->select('visits.date as date,
        products.id as product_id,
        bcc_models.av as av', false);
//((sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END))/(count(bcc_models.id)))*100 as av
        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', ' outlets.id = visits.outlet_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');

        $this->db->where('visits.date >= ', $start_date);
        $this->db->where('visits.date <= ', $end_date);
        $this->db->where('visits.monthly_visit', 0);


        if ($outlet_id != '-1') {
            $this->db->where('visits.outlet_id ', $outlet_id);
        }
        $this->db->where('brands.id', 1);
        $this->db->group_by('products.id');
        $this->db->group_by('date');
        return $this->db->get()->result_array();
    }

    //bcm filtre pos_data
    function get_outlet_by_channel_fo($channel_id, $fo_id) {

        if ($channel_id != -1) {
            $this->db->where('outlets.channel_id', $channel_id);
        }

        if ($fo_id != -1) {
            $this->db->where('outlets.admin_id', $fo_id);
        }

        $this->db->distinct();
        $this->db->where('outlets.active = ', 1);
        $this->db->order_by('outlets.name', 'ASC');
        $result = $this->db->get('outlets');
        return $result->result();
    }

    //Daily report
    //Summary report
    public function get_av_daily_report($date, $merch_id, $selected_channel_id) {

        $this->db->select(
                'outlets.id as outlet_id, '
                . ' products.id as product, '
                . 'models.shelf as shelf, '
                . 'models.av as av', false);
        $this->db->from('models');
        $this->db->join('visits', 'models.visit_id = visits.id');
        $this->db->join('admin', 'visits.admin_id = admin.id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('products', ' products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('brands', ' brands.id = product_groups.brand_id');
        // $this->db->join('categories', ' products.category_id = categories.id');

        $this->db->where('visits.date ', $date);
        $this->db->where('brands.id ', 1);
        if ($merch_id != '-1') {
            $this->db->where('visits.admin_id', $merch_id);
        }

        if ($selected_channel_id!='-1') {
            $this->db->where('channels.id', $selected_channel_id);
        }
        $this->db->group_by('outlets.id');
        $this->db->group_by('products.name');

        $this->db->order_by('zones.code', 'ASC');
        $this->db->order_by('products.code', 'ASC');

        return $this->db->get()->result_array();
    }

    // Performance Report 13/02/2018 bcm
    public function get_fo_performance($date_type, $start_date, $end_date) {

        if ($date_type == 'month') {
            $date = 'm_date';
        } else if ($date_type == 'week') {
            $date = 'w_date';
        } else {
            $date = 'date';
        }

        $this->db->select('fo_performance.' . $date . ' as date,'
                . 'avg(bcc_fo_performance.entry_time) as entry_time, '
                . 'avg(bcc_fo_performance.exit_time) as exit_time,'
                . 'avg(gemo) as gemo,'
                . 'avg(uhd) as uhd,'
                . 'avg(mg) as mg,'
                . 'admin.name as admin,'
                . 'admin.id as admin_id,'
                . 'sum(nb_visits) as visits,'
                . 'sum(total_branding) as branding,'
                . 'sum(working_hours) as working_hours,'
                . 'sum(travel_hours) as travel_hours,'
                . 'was_there as alert', false);

        $this->db->from('fo_performance');

        $this->db->join('admin', 'admin.id = fo_performance.admin_id');

        //$this->db->join('visits', 'admin.id = visits.admin_id');
        //$this->db->where('visits.' . $date . ' >= ', $start_date);
        //$this->db->where('visits.' . $date . ' <= ', $end_date);

        $this->db->where('fo_performance.' . $date . ' >= ', $start_date);
        $this->db->where('fo_performance.' . $date . ' <= ', $end_date);


        $this->db->group_by('fo_performance.admin_id'); //
        $this->db->group_by('fo_performance.' . $date);

        $this->db->order_by('date', 'asc');
        $this->db->order_by('working_hours', 'desc');
        //$this->db->order_by('admin.name', 'asc');
        return $this->db->get()->result_array();
    }

    // Routing Trend Report 05/11/2018 bcm
    public function get_routing_trend($date, $start_date, $end_date) {

//        for ($j = 0; $j < 56; $j += 7) {
//            $dates[] = date('Y-m-d', strtotime("-" . $j . "day", strtotime(date('Y-m-d', strtotime($date)))));
//        }
//        print_r($dates);
//        die();
//*******************
        $dates = array();
        $choosed_day_lettre = date('l', strtotime($date));
        //$dates[] = $date;
        $start_date_traitement = $start_date;
        while (strtotime($start_date_traitement) <= strtotime($end_date)) {
            $today_letter = date('l', strtotime($start_date_traitement));
            if ($today_letter == $choosed_day_lettre) {
                $dates[] = $start_date_traitement;
            }
            $start_date_traitement = date('Y-m-d', strtotime($start_date_traitement . '+ 1 days'));
        }
//        echo $start_date;
//        echo $end_date;
//        print_r($dates);
//        die();
//*********************
        $this->db->select('fo_performance.date as date,
                avg(bcc_fo_performance.entry_time) as entry_time,
                avg(bcc_fo_performance.exit_time) as exit_time,
              
                admin.name as admin ,
                admin.id as admin_id,
                sum(nb_visits) as visits,
                sum(total_branding) as branding,
                sum(working_hours) as working_hours,
                sum(travel_hours) as travel_hours,
                was_there as alert', false);

        $this->db->from('fo_performance');

        $this->db->join('admin', 'admin.id = fo_performance.admin_id');

        $this->db->where_in('fo_performance.date', $dates);
        //$this->db->where('fo_performance.date <= ', $end_date);

        $this->db->group_by('fo_performance.admin_id'); //
        $this->db->group_by('fo_performance.date');

        $this->db->order_by('date', 'asc');
        //$this->db->order_by('admin.name', 'asc');
        return $this->db->get()->result_array();
    }

    // Report 28/11/2018 bcm
    public function get_routing_survey_data($fo_id, $start_date, $end_date) {

        $this->db->select('visits.id as visit_id,'
                . 'visits.w_date,'
                . 'visits.date,'
                . 'outlets.name as outlet_name,'
                . 'outlets.id as outlet_id,'
                . 'admin.id as admin_id,'
                . 'admin.name as admin_name', false);

        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('admin', 'visits.admin_id = admin.id');



        if ($fo_id != '-1') {
            $this->db->where('admin.id', $fo_id);
        }
        $this->db->where('visits.w_date >=', $start_date);
        $this->db->where('visits.w_date <= ', $end_date);
        //$this->db->order_by('admin.id', 'asc');
        $this->db->order_by('visits.id', 'asc');

        //$this->db->group_by('admin.id');
        //$this->db->group_by('visits.date');
//
//
//        $this->db->group_by('fo_performance.admin_id'); //
//        $this->db->group_by('fo_performance.date');
//
//        $this->db->order_by('date', 'asc');
//        //$this->db->order_by('admin.name', 'asc');
        return $this->db->get()->result_array();
    }

    //hcm new
    public function get_store_album($outlet_id, $fo_id, $zone_id) {
        $this->db->distinct();
        $this->db->select('visits.id as id, '
                . 'max(bcc_visits.date) as date, '
                . 'outlets.name as outlet_name, '
                . 'outlets.zone as zone, '
                . 'outlets.photos as outlet_picture, '
                . 'admin.name as admin_name, '
                . 'visit_pictures.one_pictures as one_pictures', false);

        $this->db->from('visit_pictures');
        $this->db->join('visits', 'visit_pictures.visit_id = visits.id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('admin', 'admin.id = visits.admin_id');

        if ($outlet_id != '-1') {
            $this->db->where('outlets.id', $outlet_id);
        }
        if ($zone_id != -1) {
            $this->db->where('zones.id', $zone_id);
        }

        if ($fo_id != -1) {
            $this->db->where('admin.id', $fo_id);
        }

        $where = "(bcc_visit_pictures.one_pictures != '')  and ( bcc_visit_pictures.one_pictures != '[]' ) ";
        $this->db->where($where);

        $this->db->group_by('outlets.id');

        $this->db->order_by('date', 'desc');
        $this->db->order_by('visits.id', 'desc');

        return $this->db->get()->result();
    }

    //new
    public function get_branding_data($from, $to, $outlet_id, $fo_id, $zone_id) {
        $this->db->distinct();

        $this->db->select(
                'visits.date as date, '
                . 'visits.outlet_id as outlet_id, '
                . 'outlets.name as outlet_name, '
                . 'outlets.zone as zone, '
                . 'outlets.photos as outlet_picture, '
                . 'visits.id as id, '
                . 'admin.name as admin_name, '
                . 'visits.admin_id as admin_id,'
                . 'visit_pictures.branding_pictures as branding_pictures, '
                . 'visit_pictures.one_pictures as one_pictures');

        $this->db->from('visits');
        $this->db->join('visit_pictures', 'visits.id = visit_pictures.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'zones.id = outlets.zone_id');
        $this->db->join('admin', 'admin.id = visits.admin_id');

        if ($from != '') {
            $this->db->where('visits.date >= ', $from);
            $this->db->where('visits.date <= ', $to);
        }

        if ($outlet_id != '-1') {
            $this->db->where('outlets.id', $outlet_id);
        }

        if ($zone_id != -1) {
            $this->db->where('zones.id', $zone_id);
        }

        if ($fo_id != -1) {
            $this->db->where('admin.id', $fo_id);
        }
        $where = "(bcc_visit_pictures.branding_pictures != '[]' or bcc_visit_pictures.one_pictures != '[]')";

        $this->db->where($where);

        //$this->db->group_by('outlets.id');

        $this->db->order_by('date', 'desc');
        $this->db->order_by('outlets.id', 'desc');

        return $this->db->get()->result();
    }

//    //bcm new
//    public function get_store_album($outlet_id, $fo_id, $zone_id) {
//        $this->db->distinct();
//        $this->db->select('visits.id as id, '
//                . 'max(bcc_visits.date) as date, '
//                . 'outlets.name as outlet_name, '
//                . 'outlets.zone as zone, '
//                . 'outlets.photos as outlet_picture, '
//                . 'admin.name as admin_name, '
//                . 'visits.one_pictures as one_pictures', false);
//
//        $this->db->from('models');
//
//        $this->db->join('visits', 'visits.id = models.visit_id');
//        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
//        $this->db->join('zones', 'zones.id = outlets.zone_id');
//        $this->db->join('admin', 'admin.id = visits.admin_id');
//
//        if ($outlet_id != '-1') {
//            $this->db->where('outlets.id', $outlet_id);
//        }
//        if ($zone_id != -1) {
//            $this->db->where('zones.id', $zone_id);
//        }
//
//        if ($fo_id != -1) {
//            $this->db->where('admin.id', $fo_id);
//        }
//
//        $where = "(bcc_visits.one_pictures != '')  and ( bcc_visits.one_pictures != '[]' ) ";
//        $this->db->where($where);
//
//        $this->db->group_by('outlets.id');
//
//        $this->db->order_by('date', 'desc');
//        $this->db->order_by('visits.id', 'desc');
//
////        $this->db->limit(1);
//
//        return $this->db->get()->result();
//    }
//
//    //new
//    public function get_branding_data($from, $to, $outlet_id, $fo_id, $zone_id) {
//        $this->db->distinct();
//        $this->db->select('visits.id as id, '
//                . 'visits.date as date, '
//                . 'outlets.name as outlet_name, '
//                . 'outlets.zone as zone, '
//                . 'outlets.photos as outlet_picture, '
//                . 'admin.name as admin_name, '
//                . 'visits.branding_pictures as branding_pictures, '
//                . 'visits.one_pictures as one_pictures');
//
//        $this->db->from('models');
//
//        $this->db->join('visits', 'visits.id = models.visit_id');
//        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
//        $this->db->join('zones', 'zones.id = outlets.zone_id');
//        $this->db->join('admin', 'admin.id = visits.admin_id');
//
//        if ($from != '') {
//            $this->db->where('visits.date >= ', $from);
//            $this->db->where('visits.date <= ', $to);
//        }
//
//        if ($outlet_id != '-1') {
//            $this->db->where('outlets.id', $outlet_id);
//        }
//
//        if ($zone_id != -1) {
//            $this->db->where('zones.id', $zone_id);
//        }
//
//        if ($fo_id != -1) {
//            $this->db->where('admin.id', $fo_id);
//        }
//
//        $where = "(bcc_visits.branding_pictures != '' or bcc_visits.one_pictures != '')  and (bcc_visits.branding_pictures != '[]' or bcc_visits.one_pictures != '[]' ) ";
//        $this->db->where($where);
//
//        $this->db->order_by('date', 'desc');
//        $this->db->order_by('visits.outlet_id', 'asc');
//
//        return $this->db->get()->result();
//    }
    //hcm filtre branding
    function get_outlet_by_zone_fo($zone_id, $fo_id) {

        if ($zone_id != -1) {
            $this->db->where('outlets.zone_id', $zone_id);
        }

        if ($fo_id != -1) {
            $this->db->where('outlets.admin_id', $fo_id);
        }
        $this->db->distinct();
        $this->db->order_by('outlets.name', 'ASC');
        $result = $this->db->get('outlets');
        return $result->result();
    }

//*************************************************************************************************************************************************
    //rectifi� le 17/01/2017 by Amira 1er tab bcs
    public function get_shelf_zone_brand($date_type, $start_date, $end_date, $zone_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('
        outlets.zone as zone,
        brands.name as brand_name,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.total_metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit', array(2, 3));
        //$this->db->where('visits.monthly_visit', 1);

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        $this->db->where('brands.active', 1);

        if ($category_id != '-1') {
            $this->db->where('product_groups.category_id', $category_id);
        }
        $this->db->group_by('brand_name');
        $this->db->group_by('zone');

        $this->db->order_by('bcc_zones.code', 'desc');
        $this->db->order_by('brands.id', 'desc');

        return $this->db->get()->result_array();
    }

    //rectifi� le 17/01/2017 somme metrage pr brand zone
    public function get_total_metrage_henkel_by_zone($date_type, $start_date, $end_date, $category_id, $zone_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('
        outlets.zone as zone,
        brands.name as brand_name,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.total_metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit', array(2, 3));
        //$this->db->where('visits.monthly_visit', 1);

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        $this->db->where('brands.active', 1);

        if ($category_id != '-1') {
            $this->db->where('product_groups.category_id', $category_id);
        }
        //$this->db->group_by('brand_name');
        $this->db->group_by('zone');

        $this->db->order_by('bcc_zones.code', 'desc');
        $this->db->order_by('brands.id', 'desc');

        $results = $this->db->get()->result();

        $sum_metrage = array();
        foreach ($results as $row) {
            $sum_metrage[$row->zone] = $row->metrage;
        }
        /*
          foreach ($sum_metrage as $channel_name => $componentBrand) {
          $sum_metrage_channel[$channel_name] = array_sum(array_values($componentBrand));
          }

         */
        return $sum_metrage;
    }

    //rectifi� le 17/01/2017 by Amira bcs 1er tab
    public function load_shelf_all_zones_group_by_channel($date_type, $start_date, $end_date, $cluster_id, $category_id, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('
        outlets.channel as channel,
        brands.name as brand_name,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.total_metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        //$this->db->join('zones', 'outlets.zone = zones.name');
        //jointure avec product group pour un !correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit', array(2, 3));
        //$this->db->where('visits.monthly_visit', 1);

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('activity.id ', $channel);
            } else {
                $this->db->where('activity.id ', $channel);
            }
        }

        $this->db->where('brands.active', 1);

        if ($category_id != '-1') {
            $this->db->where('product_groups.category_id', $category_id);
        }
        $this->db->group_by('brand_name');
        $this->db->group_by('channel');

        $this->db->order_by('channel', 'desc');
        $this->db->order_by('brands.id', 'desc');

        return $this->db->get()->result_array();
    }

    //rectifi� le 12/01/2017 somme metrage pr brand channel
    public function get_total_metrage_henkel_by_channel($date_type, $start_date, $end_date, $category_id, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('
        outlets.activity as channel,
        brands.name as brand_name,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.total_metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('activity', 'activity.id = outlets.id_activity');

        $this->db->join('brands', 'brands.id = models.brand_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        if ($category_id != '-1') {
            $this->db->where('product_groups.category_id', $category_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where_in('visits.monthly_visit', array(2, 3));

        //$this->db->where('visits.monthly_visit', 1);

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('activity.id ', $channel);
            } else {
                $this->db->where('activity.id ', $channel);
            }
        }

        $this->db->where('brands.active', 1);


        //$this->db->group_by('brand_name');
        $this->db->group_by('channel');

        $this->db->order_by('channel', 'desc');
        $this->db->order_by('brands.id', 'desc');

        $results = $this->db->get()->result();

        $sum_metrage = array();
        foreach ($results as $row) {
            $sum_metrage[$row->channel] = $row->metrage;
        }
        /*
          foreach ($sum_metrage as $channel_name => $componentBrand) {
          $sum_metrage_channel[$channel_name] = array_sum(array_values($componentBrand));
          }

         */
        return $sum_metrage;
    }

    public function get_quarter($date_type, $start_date, $end_date) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->distinct();
        $this->db->select('quarter(' . $date . ') as quarter', false);
        $this->db->from('visits');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        return $results = $this->db->get()->result();
    }

    //le 27/01 zone=1 channel=0 date=1 tab1
    public function get_total_metrage_group_by_brand_1_1_0($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('quarter(' . $date . ') as date,
        product_groups.id as product_id,
        brands.name as brand_name,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        sum(bcc_models.shelf) as shelf', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');


        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        //$where='visits.quarter = QUARTER('.$start_date.')';
        //$this->db->where($where);
        //$this->db->where('visits.quarter == quarter(' . $date . ')');
        //$this->db->where('visits.quarter',$quarter);

        $this->db->where('visits.monthly_visit ', 1);

        $this->db->where('brands.active', 1);

        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('visits.' . $date . ' ', 'desc');
        $this->db->order_by('metrage', 'desc');


        $results = $this->db->get()->result();

        $sum_metrage = array();
        foreach ($results as $row) {
            $sum_metrage[$row->date] = $row->metrage;
        }

        return $sum_metrage;
    }

    //le 29/01 zone=0 channel=1 date=1 tab1
    public function get_total_metrage_group_by_brand_0_1_1($date_type, $start_date, $end_date, $channel, $cluster_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('quarter(' . $date . ') as date,
        product_groups.id as product_id,
        brands.name as brand_name,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        sum(bcc_models.shelf) as shelf', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');


        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        //$where='visits.quarter = QUARTER('.$start_date.')';
        //$this->db->where($where);
        //$this->db->where('visits.quarter == quarter(' . $date . ')');
        //$this->db->where('visits.quarter',$quarter);

        $this->db->where('visits.monthly_visit ', 1);

        $this->db->where('brands.active', 1);

        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('visits.' . $date . ' ', 'desc');
        $this->db->order_by('metrage', 'desc');


        $results = $this->db->get()->result();

        $sum_metrage = array();
        foreach ($results as $row) {
            $sum_metrage[$row->date] = $row->metrage;
        }

        return $sum_metrage;
    }

    //le 27/01 zone=1 channel=0 date=1 tab1
    public function get_shelf_data_group_by_brand_1_1_0($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('quarter(' . $date . ') as date,
        product_groups.id as product_id,
        brands.name as brand_name,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        sum(bcc_models.shelf) as shelf', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');


        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        //$where='visits.quarter = QUARTER('.$start_date.')';
        //$this->db->where($where);
        //$this->db->where('visits.quarter == quarter(' . $date . ')');
        //$this->db->where('visits.quarter',$quarter);

        $this->db->where('visits.monthly_visit ', 1);

        $this->db->where('brands.active', 1);

        $this->db->group_by('models.brand_id');
        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('metrage', 'desc');
        $this->db->order_by('visits.' . $date, 'desc');


        return $this->db->get()->result_array();
    }

    //le 27/01 zone=1 channel=0 date=1 tab2
    public function get_shelf_data_group_by_brand_product_1_1_0($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('quarter(' . $date . ') as date,
        product_groups.id as product_id,
        brands.name as brand_name,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        sum(bcc_models.shelf) as shelf', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        if ($cluster_id != '-1') {
            $this->db->where('product_groups.cluster_id ', $cluster_id);
        }

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        //$where='visits.quarter = QUARTER('.$start_date.')';
        //$this->db->where($where);
        //$this->db->where('visits.quarter == quarter(' . $date . ')');
        //$this->db->where('visits.quarter',$quarter);

        $this->db->where('visits.monthly_visit ', 1);

        $this->db->where('brands.active', 1);

        $this->db->group_by('product_groups.id');
        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('metrage', 'desc');
        $this->db->order_by('visits.' . $date, 'desc');


        return $this->db->get()->result_array();
    }

    //le 29/01 zone=0 channel=1 date=1 tab1
    public function get_shelf_data_group_by_brand_0_1_1($date_type, $start_date, $end_date, $channel, $cluster_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('quarter(' . $date . ') as date,
        product_groups.id as product_id,
        brands.name as brand_name,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        sum(bcc_models.shelf) as shelf', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');


        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        //$where='visits.quarter = QUARTER('.$start_date.')';
        //$this->db->where($where);
        //$this->db->where('visits.quarter == quarter(' . $date . ')');
        //$this->db->where('visits.quarter',$quarter);

        $this->db->where('visits.monthly_visit ', 1);

        $this->db->where('brands.active', 1);

        $this->db->group_by('models.brand_id');
        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('metrage', 'desc');
        $this->db->order_by('visits.' . $date, 'desc');


        return $this->db->get()->result_array();
    }

    //le 29/01 zone=0 channel=1 date=1 tab2
    public function get_shelf_data_group_by_brand_product_0_1_1($date_type, $start_date, $end_date, $channel, $cluster_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('quarter(' . $date . ') as date,
        product_groups.id as product_id,
        brands.name as brand_name,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        sum(bcc_models.shelf) as shelf', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        if ($cluster_id != '-1') {
            $this->db->where('product_groups.cluster_id ', $cluster_id);
        }

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        //$where='visits.quarter = QUARTER('.$start_date.')';
        //$this->db->where($where);
        //$this->db->where('visits.quarter == quarter(' . $date . ')');
        //$this->db->where('visits.quarter',$quarter);

        $this->db->where('visits.monthly_visit ', 1);

        $this->db->where('brands.active', 1);

        $this->db->group_by('product_groups.id');
        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('metrage', 'desc');
        $this->db->order_by('visits.' . $date, 'desc');


        return $this->db->get()->result_array();
    }

    //le 27/01 zone=0 channel=0 date=0 tab1
    public function load_shelf_group_by_one_date($date_type, $start_date, $end_date, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('quarter(' . $date . ') as date,
        product_groups.id as product_id,
        brands.name as brand_name,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        sum(bcc_models.shelf) as shelf', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        //
        //$where='visits.quarter = QUARTER('.$start_date.')';
        //$this->db->where($where);
        //$this->db->where('visits.quarter == quarter(' . $date . ')');
        //$this->db->where('visits.quarter', $test);

        /*
          if (is_array($test)) {
          $this->db->where_in('visits.quarter', $test);
          } else {
          $this->db->where('visits.quarter', $test);
          }
         */


        $this->db->where('visits.monthly_visit ', 1);

        $this->db->where('brands.active', 1);


        $this->db->group_by('models.brand_id');
        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('metrage', 'desc');
        $this->db->order_by('visits.' . $date, 'desc');


        return $this->db->get()->result_array();
    }

    //le 27/01 zone=0 channel=0 date=0 tab2
    public function get_shelf_product_group_one_date_by_categorie($date_type, $start_date, $end_date, $cluster_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('quarter(' . $date . ') as date,
        product_groups.id as product_id,
        brands.name as brand_name,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        sum(bcc_models.shelf) as shelf', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        if ($cluster_id != '-1') {
            $this->db->where('product_groups.cluster_id ', $cluster_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        //$where='visits.quarter = QUARTER('.$start_date.')';
        //$this->db->where($where);
        //$this->db->where('visits.quarter == quarter(' . $date . ')');
        //$this->db->where('visits.quarter',$quarter);

        $this->db->where('visits.monthly_visit ', 1);

        $this->db->where('brands.active', 1);

        $this->db->group_by('product_groups.id');
        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('metrage', 'desc');
        $this->db->order_by('visits.' . $date, 'desc');


        return $this->db->get()->result_array();
    }

    //rectifi� le 12/01/2017 somme metrage pr brand date
    //le 27/01
    public function get_total_metrage_henkel_by_one_day($date_type, $start_date, $end_date, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('quarter(' . $date . ') as date,
        brands.name as brand_name,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        $this->db->where('brands.active ', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        //$this->db->group_by('brand_name');
        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('visits.' . $date . ' ', 'desc');
        $this->db->order_by('metrage', 'desc');


        $results = $this->db->get()->result();

        $sum_metrage = array();
        foreach ($results as $row) {
            $sum_metrage[$row->date] = $row->metrage;
        }

        return $sum_metrage;
    }

    // chart shelf bcm  filtre  zone=1 channel=0 date=1 
    // le 27/01
    function get_brand_for_shelf_1_1_0($date_type, $start_date, $end_date, $category_id, $zone_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('quarter(' . $date . ') as date,
        brands.name as brand_name,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('zones', 'outlets.zone = zones.name');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        $this->db->where('brands.active', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }
        $this->db->group_by('models.brand_id');
        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('metrage', 'desc');
        $this->db->order_by('visits.' . $date, 'desc');


        $query = $this->db->get()->result_array();

        $count_date = 0;
        $dates = array();
        $components = array();
        foreach ($query as $row) {

            $date_name = ($row['date']);
            if (!in_array($date_name, $dates)) {
                $dates[] = $date_name;
                $count_date++;
            }
            //create an array for every brand and the count at a outlet
            $components[$row['brand_name']][$row['date']] = $row['metrage'];
        }// end foreach report_data

        foreach ($components as $brand_name => $componentdates) {

            $total_avg = (array_sum(array_values($componentdates))) / $count_date;

            $row_data['brand_name'] = $brand_name;
            $row_data['metrage'] = number_format($total_avg, 2, '.', '');
            $brand_color = $this->Brand_model->get_brand_by_name($brand_name)->color;
            $row_data['color'] = $brand_color;
            $data[] = $row_data;
        }
        //print_r($data);
        return json_encode(array_reverse($data));
    }

    // chart shelf bcm  filtre  zone=0 channel=1 date=1 
    // le 29/01
    function get_brand_for_shelf_0_1_1($date_type, $start_date, $end_date, $category_id, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('quarter(' . $date . ') as date,
        brands.name as brand_name,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        $this->db->where('brands.active', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }

        $this->db->group_by('models.brand_id');
        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('metrage', 'desc');
        $this->db->order_by('visits.' . $date, 'desc');


        $query = $this->db->get()->result_array();

        $count_date = 0;
        $dates = array();
        $components = array();
        foreach ($query as $row) {

            $date_name = ($row['date']);
            if (!in_array($date_name, $dates)) {
                $dates[] = $date_name;
                $count_date++;
            }
            //create an array for every brand and the count at a outlet
            $components[$row['brand_name']][$row['date']] = $row['metrage'];
        }// end foreach report_data

        foreach ($components as $brand_name => $componentdates) {

            $total_avg = (array_sum(array_values($componentdates))) / $count_date;

            $row_data['brand_name'] = $brand_name;
            $row_data['metrage'] = number_format($total_avg, 2, '.', '');
            $brand_color = $this->Brand_model->get_brand_by_name($brand_name)->color;
            $row_data['color'] = $brand_color;
            $data[] = $row_data;
        }
        //print_r($data);
        return json_encode(array_reverse($data));
    }

    // chart shelf bcm aucun filtre one date
    //le 27/01
    function get_brand_for_shelf_0_0_0($date_type, $start_date, $end_date, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('quarter(' . $date . ') as date,
        brands.name as brand_name,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        $this->db->where('brands.active', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->group_by('models.brand_id');
        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('metrage', 'desc');
        $this->db->order_by('visits.' . $date, 'desc');


        $query = $this->db->get()->result_array();

        $count_date = 0;
        $dates = array();
        $components = array();
        foreach ($query as $row) {

            $date_name = ($row['date']);
            if (!in_array($date_name, $dates)) {
                $dates[] = $date_name;
                $count_date++;
            }
            //create an array for every brand and the count at a outlet
            $components[$row['brand_name']][$row['date']] = $row['metrage'];
        }// end foreach report_data

        foreach ($components as $brand_name => $componentdates) {

            $total_avg = (array_sum(array_values($componentdates))) / $count_date;

            $row_data['brand_name'] = $brand_name;
            $row_data['metrage'] = number_format($total_avg, 2, '.', '');
            $brand_color = $this->Brand_model->get_brand_by_name($brand_name)->color;
            $row_data['color'] = $brand_color;
            $data[] = $row_data;
        }
        //print_r($data);
        return json_encode(array_reverse($data));
    }

    // chart shelf bcm aucun filtre  multi date
    //le 27/017
    //a verifier
    function get_brand_for_shelf_0_0_1($start_date, $end_date, $date_type, $category_id) {

        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select(' visits.' . $date . ' as date, '
                . 'visits.id as visit_id, '
                . 'quarter(' . $date . ') as date2,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        brands.name as brand_name,
        brands.color as brand_color', false);
        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');


        $this->db->where('visits.monthly_visit', 1);

        $this->db->where('product_groups.active', 1);
        // $this->db->where('models.av != ', 2);

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->order_by('visits.' . $date, 'desc');
        $this->db->order_by('brands.id', 'desc');

        $this->db->group_by('quarter(' . $date . ')');
        $this->db->group_by('models.brand_id');

        $query = $this->db->get()->result_array();

        $components = array();
        $brands = array();
        $brand_temp = array();

        $dates = array();
        $count_date = 0;

        foreach ($query as $row) {

            $date_quarter = ($row['date2']);

            if (!in_array($date_quarter, $dates)) {
                $dates[] = $date_quarter;
                $count_date += 1;
            }

            $br = array();
            if (!in_array($row['brand_name'], $brand_temp)) {
                $brand_temp[] = $row['brand_name'];
                $br['name'] = $row['brand_name'];
                $br['color'] = $row['brand_color'];
                $brands[] = $br;
            }

            if ($row['metrage'] != 0) {

                if ($date_type == 'month') {
                    $date = format_month($row['date']);
                } else {
                    $date = format_week($row['date']);
                }

                $year = strtotime($row['date']);
                $only_year = date("Y", strtotime("+0 month", $year));

                $tot = $this->get_summ_0_0_1($only_year, $row['date2'], $date_type, $category_id)->metrage;
                if ($tot != 0) {
                    $pourcentage = (($row['shelf']) / $tot) * 100;
                    $components[$date_quarter] [$row['brand_name']] = number_format(($pourcentage), 2, '.', '');
                }
            }
        }// end foreach query
        //
       // die();
        // $components[$date] [''] = 90;
        $br = array();
        $br['name'] = '';
        $br['color'] = 'rgba(255, 255, 255, .4)';
        $brands[] = $br;
        $data = array();

        foreach ($components as $date_quarter => $componentBrands) {

            $row_data = array();
            $row_data['date'] = $date_quarter;
            foreach ($brands as $brand) {
                if (isset($componentBrands[$brand['name']])) {
                    $row_data[$brand['name']] = $componentBrands[$brand['name']];
                }
            }
            $data[] = $row_data;
        }

        $result['brands'] = $brands;
        $result['data'] = $data;
        return $result;
    }

    function get_summ_0_0_1($year, $quarter, $date_type, $category_id) {

        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);
        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        $this->db->where('visits.year ', $year);
        $this->db->where('quarter', $quarter);
        $this->db->where('product_groups.active', 1);
        $this->db->where('brands.active', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $query = $this->db->get()->row();
        return $query;
    }

    // chart shelf bcm par brand/zone/one date
    function get_brand_single_zone_for_shelf_d0_z1_c0($date_type, $start_date, $end_date, $zone_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        brands.name as brand_name,
        outlets.zone as zone,
        brands.color as brand_color', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }
        $this->db->where('brands.active ', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->group_by('brand_name');
        $this->db->group_by('zone');

        $this->db->order_by('bcc_zones.code', 'desc');
        $this->db->order_by('brands.id', 'desc');

        $query = $this->db->get()->result_array();

        $count_zone = 0;
        $zones = array();
        $components = array();
        $data = array();
        foreach ($query as $row) {

            $zone_name = ($row['zone']);
            if (!in_array($zone_name, $zones)) {
                $zones[] = $zone_name;
                $count_zone++;
            }

            //create an array for every brand and the count at a outlet
            $components[$row['brand_name']][$row['zone']] = $row['metrage'];
        }// end foreach report_data

        foreach ($components as $brand_name => $componentzones) {

            $total_avg = (array_sum(array_values($componentzones))) / $count_zone;

            $row_data['brand_name'] = $brand_name;

            $row_data['metrage'] = number_format($total_avg, 2, '.', '');
            $brand_color = $this->Brand_model->get_brand_by_name($brand_name)->color;
            $row_data['color'] = $brand_color;
            $data[] = $row_data;
        }
        //print_r($data);
        return json_encode(array_reverse($data));
    }

    // chart shelf bcm par brand/channel
    //le 27/01
    function get_brand_single_channel_for_shelf($date_type, $start_date, $end_date, $category_id, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('brands.name as brand_name,
        outlets.channel as channel_name,
        brands.color as color,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }
        $this->db->where('brands.active ', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->group_by('brand_name');
        $this->db->group_by('channel_name');

        $this->db->order_by('outlets.channel', 'desc');
        $this->db->order_by('brands.id', 'desc');

        $query = $this->db->get()->result_array();

        $count_channel = 0;
        $channels = array();
        $components = array();
        $data = array();
        foreach ($query as $row) {

            $channel_name = ($row['channel_name']);
            if (!in_array($channel_name, $channels)) {
                $channels[] = $channel_name;
                $count_channel++;
            }

            //create an array for every brand and the count at a outlet
            $components[$row['brand_name']][$row['channel_name']] = $row['metrage'];
        }// end foreach report_data

        foreach ($components as $brand_name => $componentChannels) {

            $total_avg = (array_sum(array_values($componentChannels))) / $count_channel;

            $row_data['brand_name'] = $brand_name;
            $row_data['metrage'] = number_format($total_avg, 2, '.', '');
            $brand_color = $this->Brand_model->get_brand_by_name($brand_name)->color;
            $row_data['color'] = $brand_color;
            $data[] = $row_data;
        }
        //print_r($data);
        return json_encode(array_reverse($data));
    }

    //rectifi� le 12/01/2017 1er tab bcm 
    //rectifi� le 27/01 
    public function load_shelf_group_by_channel_brand($date_type, $start_date, $end_date, $category_id, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('brands.name as brand_name,
        outlets.channel as channel_name,
        brands.color as color,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }
        $this->db->where('brands.active ', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->group_by('brand_name');
        $this->db->group_by('channel_name');

        $this->db->order_by('outlets.channel', 'desc');
        $this->db->order_by('brands.id', 'desc');

        return $this->db->get()->result_array();
    }

    //rectifi� le 12/01/2017 2eme tab bcm shelf 
    public function load_shelf_all_zones_group_by_channel_product($date_type, $start_date, $end_date, $cluster_id, $category_id, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('
        outlets.channel as channel,
        product_groups.id as product_id,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        //$this->db->where('models.brand_id', 18);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        //$this->db->where_in('visits.monthly_visit', array(1, 3));
        $this->db->where('visits.monthly_visit ', 1);


        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }
        $this->db->where('brands.active ', 1);

        if ($cluster_id != '-1') {
            $this->db->where('product_groups.cluster_id ', $cluster_id);
        }

        $this->db->group_by('outlets.channel');
        $this->db->group_by('product_groups.id');

        $this->db->order_by('outlets.channel', 'desc');
        $this->db->order_by('metrage', 'desc');

        return $this->db->get()->result_array();
    }

    //rectifi� le 12/01/2017 somme metrage pr brand channel
    public function get_total_metrage_henkel_by_channels($date_type, $start_date, $end_date, $category_id, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('brands.name as brand_name,
        outlets.channel as channel,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }
        $this->db->where('brands.active ', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        //$this->db->group_by('brand_name');
        $this->db->group_by('channel');

        $this->db->order_by('outlets.channel', 'desc');
        //$this->db->order_by('shelf', 'desc');   
        $this->db->order_by('brands.id', 'desc');
        //$this->db->order_by('metrage', 'desc');
        $results = $this->db->get()->result();

        $sum_metrage = array();
        foreach ($results as $row) {
            $sum_metrage[$row->channel] = $row->metrage;
        }
        /*
          foreach ($sum_metrage as $channel_name => $componentBrand) {
          $sum_metrage_channel[$channel_name] = array_sum(array_values($componentBrand));
          }

         */
        return $sum_metrage;
    }

    //rectifi� le 12/01/2017 by Amira 1er tab bcm
    public function get_shelf_brand_zone($date_type, $start_date, $end_date, $zone_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('brands.name as brand_name,
        outlets.zone as zone,
        brands.color as color,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }
        $this->db->where('brands.active ', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->group_by('brand_name');
        $this->db->group_by('zone');

        $this->db->order_by('bcc_zones.code', 'desc');
        $this->db->order_by('brands.id', 'desc');

        return $this->db->get()->result_array();
    }

    //rectifi� le 27/01/2017 by Amira 2eme tab bcm
    public function get_shelf_all_product_zones($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id) {

        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('
        product_groups.name as product_name,
        models.product_group_id as product_group_id,
        brands.name as brand_name,
        outlets.zone as zone,
        brands.color as color,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        //$this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        if ($cluster_id != '-1') {
            $this->db->where('product_groups.cluster_id ', $cluster_id);
        }

        $this->db->group_by('zone');
        $this->db->group_by('models.product_group_id');

        $this->db->order_by('bcc_zones.code', 'desc');
        $this->db->order_by('shelf', 'desc');
        return $this->db->get()->result_array();
    }

    //rectifi� le 12/01/2017
    public function get_total_shelf_henkel_by_channels($date_type, $start_date, $end_date, $category_id, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('
        sum(bcc_models.shelf) as shelf,
        outlets.channel as channel, ', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        $this->db->where('models.brand_id', 1);


        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }
        $this->db->group_by('outlets.channel ');
        //$this->db->group_by('models.product_group_id');

        $results = $this->db->get()->result();

        $sum_shelf = array();
        foreach ($results as $row) {
            $sum_shelf[$row->channel] = $row->shelf;
        }
        return $sum_shelf;
    }

    //bcm rectifi� le 19/01/2018
    public function get_price_monitoring_data($start_date, $channel, $category_id, $cluster_id) {

        $this->db->select('outlets.name as outlet_name, '
                . 'product_groups.id as product_group_id, '
                . 'models.price as price, '
                . 'promo_price as promo_price, '
                , false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        //$this->db->where('product_groups.cluster_id ', $cluster_id);
        $this->db->where('models.category_id ', $category_id);

        $this->db->where('visits.m_date', $start_date);

        $this->db->where_in('visits.monthly_visit', array('2', '3'));

        $this->db->where('brands.active ', 1);

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }
        $this->db->where('models.price >', 0);
        //$this -> db -> where('promo_price >',0);
        //$where = "((bcc_models.promo_price >0) or (bcc_models.price >0))";
        //$this->db->where($where);

        $this->db->group_by('outlets.id');
        $this->db->group_by('product_groups.id');

        $this->db->order_by('models.brand_id', 'ASC');
        $this->db->order_by('outlets.name', 'ASC');
        return $this->db->get()->result_array();
    }

    // chart shelf bcm par brand/zone 
    function get_brand_single_zone_for_shelf($date_type, $start_date, $end_date, $zone_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        brands.name as brand_name,
        outlets.zone as zone,
        brands.color as brand_color', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'outlets.id = visits.outlet_id ');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }
        $this->db->where('brands.active ', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->group_by('brand_name');
        $this->db->group_by('zone');

        $this->db->order_by('bcc_zones.code', 'desc');
        $this->db->order_by('brands.id', 'desc');

        $query = $this->db->get()->result_array();

        $count_zone = 0;
        $zones = array();
        foreach ($query as $row) {

            $zone_name = ($row['zone']);
            if (!in_array($zone_name, $zones)) {
                $zones[] = $zone_name;
                $count_zone++;
            }

            //create an array for every brand and the count at a outlet
            $components[$row['brand_name']][$row['zone']] = $row['metrage'];
        }// end foreach report_data

        foreach ($components as $brand_name => $componentzones) {

            $total_avg = (array_sum(array_values($componentzones))) / $count_zone;

            $row_data['brand_name'] = $brand_name;

            $row_data['metrage'] = number_format($total_avg, 2, '.', ' ');
            $brand_color = $this->Brand_model->get_brand_by_name($brand_name)->color;
            $row_data['color'] = $brand_color;
            $data[] = $row_data;
        }
        //print_r($data);
        return json_encode(array_reverse($data));
    }

    //rectifi� le 25/01 groupe by date 1er tab 
    public function get_shelf_data_groupe_by_date($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('quarter(' . $date . ') as date,
        zones.name as zone,
        brands.name as brand_name,
        count(bcc_models.id) as total,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        $this->db->where('brands.active', 1);
        //$this->db->where('product_groups.active', 1);
        //$this->db->where('brands.id != ', 8);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->group_by('models.brand_id');
        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('date', 'desc');
        $this->db->order_by('shelf', 'desc');

        return $this->db->get()->result_array();
    }

    //bcm rectifi� le 25/01/2018 bcm shelf chart/zone
    function get_brand_single_date_json_data_for_shelf($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select(
                '
        sum(bcc_models.shelf) as shelf,
        brands.name as brand_name,
        outlets.channel as channel_name,
        brands.color as brand_color
        ', false);
        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'zones.name = outlets.zone');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');


        $this->db->where('product_groups.active', 1);

        $this->db->where('models.av != ', 2);
        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('brands.active', 1);
        $this->db->where('models.brand_id != ', 8);
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        $this->db->group_by('brand_name');
        $this->db->group_by('channel_name');

        $query = $this->db->get()->result_array();

        $count_channel = 0;
        $channels = array();
        foreach ($query as $row) {

            $channel_name = ($row['channel_name']);
            if (!in_array($channel_name, $channels)) {
                $channels[] = $channel_name;
                $count_channel++;
            }

            //create an array for every brand and the count at a outlet
            $components[$row['brand_name']][$row['channel_name']] = $row['shelf'];
        }// end foreach report_data

        foreach ($components as $brand_name => $componentChannels) {

            $total_avg = (array_sum(array_values($componentChannels))) / $count_channel;

            $row_data['brand_name'] = $brand_name;
            $row_data['shelf'] = round($total_avg);
            $brand_color = $this->Brand_model->get_brand_by_name($brand_name)->color;
            $row_data['color'] = $brand_color;
            $data[] = $row_data;
        }
        //print_r($data);
        return json_encode(array_reverse($data));
    }

    //one date shelf bcm 2eme tab 
    public function get_shelf_product_group_one_date($date_type, $start_date, $end_date, $cluster_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('quarter(' . $date . ') as date,
        product_groups.name as product_name,
        product_groups.id as product_id,
        brands.name as brand_name,
        sum(bcc_models.shelf) as shelf,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');

        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');

        $this->db->where('product_groups.active', 1);
        //$this->db->where('models.av != ', 2);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        if ($cluster_id != '-1') {
            $this->db->where('product_groups.cluster_id ', $cluster_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('visits.monthly_visit', 1);

        $this->db->where('brands.active', 1);

        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('models.product_group_id');

        $this->db->order_by('visits.' . $date . ' ', 'desc');
        $this->db->order_by('shelf', 'desc');

        return $this->db->get()->result_array();
    }

    //multi date 2eme tab 
    //zone=0 channel=0 date=1
    public function get_shelf_data_groupe_by_date_product_0_0_1($date_type, $start_date, $end_date, $cluster_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('quarter(' . $date . ') as date,
        product_groups.name as product_name,
        product_groups.id as product_id,
        outlets.zone as zone,
        brands.name as brand_name,
        count(bcc_models.id) as total, sum(bcc_models.shelf) as shelf', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        $this->db->where('product_groups.active', 1);
        $this->db->where('visits.monthly_visit', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);

        if ($cluster_id != '-1') {
            $this->db->where('product_groups.cluster_id ', $cluster_id);
        }
        $this->db->group_by('quarter(' . $date . ')');
        $this->db->group_by('models.product_id');

        $this->db->order_by('date', 'desc');
        $this->db->order_by('shelf', 'desc');

        return $this->db->get()->result_array();
    }

    //le 27/01
    public function get_shelf_data_groupedate($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('quarter(' . $date . ') as date,
        product_groups.id as product_id,
        brands.name as brand_name,
        sum(bcc_models.shelf * bcc_product_groups.metrage) as metrage,
        sum(bcc_models.shelf) as shelf', false);

        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        //jointure avec product group pour un correct cluster
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        //$this->db->where('visits.quarter','quarter(' . $date . ')');
        $this->db->where('visits.monthly_visit ', 1);

        $this->db->where('brands.active', 1);

        if ($category_id == '-1') {
            $this->db->group_by('models.brand_id');
        } else {
            $this->db->group_by('models.product_id');
        }

        $this->db->group_by('quarter(' . $date . ')');

        $this->db->order_by('visits.' . $date, 'desc');
        $this->db->order_by('shelf', 'desc');

        return $this->db->get()->result_array();
    }

    // Tracking Visited outlet Report
    public function get_tracking_visited_data($start_date, $end_date, $merch_id) {

        $this->db->select('outlets.name as outlet_name, '
                . 'outlets.state as state_name, '
                . 'outlets.zone as zone_name, '
                . 'admin.name as hfo, '
                . 'visits.date as date', false);

        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('admin', 'admin.id = outlets.admin_id');

        $this->db->where('visits.m_date >= ', $start_date);
        $this->db->where('visits.m_date <= ', $end_date);

        if ($merch_id != -1) {
            $this->db->where('admin.id', $merch_id);
        }

        $this->db->where_in('visits.monthly_visit', array('1', '3'));
        $this->db->where('outlets.active ', 1);

        $this->db->group_by('visits.outlet_id');
        $this->db->order_by('visits.date', 'DESC');

        return $this->db->get()->result();
    }

    // Tracking unvisited outlet Report
    public function get_tracking_unvisited_data($start_date, $end_date, $merch_id) {
        // Sub Query
        $this->db->select('visits.outlet_id');
        $this->db->where('visits.m_date >= ', $start_date);
        $this->db->where('visits.m_date <= ', $end_date);
        //$this->db->where('visits.admin_id', $merch_id);
        $this->db->where_in('visits.monthly_visit', array('1', '3'));
        $this->db->from('visits');
        $subQuery = $this->db->get_compiled_select();

        $this->db->select('outlets.name as outlet_name, '
                . 'outlets.state as state_name, '
                . 'outlets.zone as zone_name, '
                . 'admin.name as hfo', false);

        $this->db->from('outlets');
        $this->db->join('admin', 'admin.id = outlets.admin_id');
        $this->db->where('outlets.active ', 1);

        if ($merch_id != -1) {
            $this->db->where('admin.id', $merch_id);
        }

        $this->db->where("outlets.id NOT IN ($subQuery)", NULL, FALSE);

        return $this->db->get()->result();
    }

    public function get_shelf_data_group_by_brand($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('quarter(' . $date . ') as date,
        product_groups.name as product_name,
        product_groups.id as product_id, outlets.zone as zone,
        brands.name as brand_name,
        count(bcc_models.id) as total, sum(bcc_models.shelf) as shelf,
        product_groups.metrage as metrage,
        ', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');



        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av != ', 2);
        $this->db->where('visits.monthly_visit', 1);

        if ($zone_id != '-1') {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }


        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        $this->db->where('brands.active', 1);

        if ($cluster_id != '-1') {
            $this->db->where('product_groups.cluster_id ', $cluster_id);
        }
        $this->db->group_by('quarter(' . $date . ')');
        $this->db->group_by('models.brand_id');
        $this->db->order_by('visits.' . $date . ' ', 'desc');
        $this->db->order_by('shelf', 'desc');
        return $this->db->get()->result_array();
    }

    // Stock issues reports
    public function get_stock_issues_data($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date, products.name as product_name, products.id as product_id, outlets.zone as zone,
        brands.name as brand_name, sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total,
        (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)/count(bcc_models.id)) as av,
        (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)/count(bcc_models.id)) as oos,
        (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)/count(bcc_models.id)) as ha', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        if ($zone_id != '-1') {

            //$this -> db -> where('zones.id', $zone_id);
        }

        // if($activity!='-1'){
        // $this -> db -> where('outlets.activity', $activity);	
        // }
        // if($super_market_project!='-1'){
        // $this -> db -> where('outlets.super_market_project', $super_market_project);	
        // }

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        //$this -> db -> where('models.brand_id != ',8);//
        $this->db->where('brands.active', 1);
        if ($zone_id == '-1') {
            
        } else {
            $this->db->where_in('zones.id ', json_decode($zone_id));
        }
        if ($cluster_id != '-1') {
            $this->db->where('products.cluster_id ', $cluster_id);
        }
        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('models.product_id');
        $this->db->order_by('models.brand_id', 'desc');
        return $this->db->get()->result_array();
    }

    function get_outlet_by_admin_date($admin_id, $date) {
        $this->db->where('admin_id', $admin_id);
        $this->db->like('visit_day', $date);
        $this->db->order_by('name', 'ASC');
        $this->db->where('active', 1);
        $result = $this->db->get('outlets');
        return $result->result();
    }

    public function get_av_oos_brand($date_type, $start_date, $end_date, $zone_id, $category_id, $activity, $super_market_project, $cluster_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date, products.name as product_name, products.id as product_id, outlets.zone as zone,
        brands.name as brand_name, sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total,
        sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END) AS ha,
        sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END) AS av,
        sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END) AS oos,
        (1-(sum(bcc_models.av)/count(bcc_models.id))) as oos_henkel', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        // $this->db->where('models.av != ', 2);

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        if ($cluster_id != '-1') {
            $this->db->where('products.cluster_id ', $cluster_id);
        }




        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);







        $this->db->group_by('visits.' . $date . ' ');
        if ($cluster_id != '-1') {
            $this->db->group_by('models.product_id');
        } else {
            $this->db->group_by('models.brand_id');
        }

        return $this->db->get()->result_array();
    }

    public function get_av_oos_brand_channel($date_type, $start_date, $end_date, $zone_id, $category_id, $activity, $super_market_project, $channel, $cluster_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date, outlets.channel as channel,
        products.name as product_name, products.id as product_id, outlets.zone as zone,
        brands.name as brand_name, sum(bcc_models.av) as sum_av_sku,
        count(bcc_models.id) as total,
        (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)/count(bcc_models.id)) as av,
        (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)/count(bcc_models.id)) as oos,
        (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)/count(bcc_models.id)) as ha', false);


        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);


        if ($cluster_id != '-1') {
            $this->db->where('products.cluster_id ', $cluster_id);
        }

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);


        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }




        $this->db->group_by('outlets.channel');
        $this->db->group_by('models.brand_id');
        $this->db->order_by('brands.id', "desc");
        return $this->db->get()->result_array();
    }

    public function get_av_oos_product_channel($date_type, $start_date, $end_date, $zone_id, $category_id, $activity, $super_market_project, $channel, $cluster_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date, outlets.channel as channel,
        products.name as product_name, products.id as product_id, outlets.zone as zone,
        brands.name as brand_name, sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total,
        (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)/count(bcc_models.id)) as av,
        (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)/count(bcc_models.id)) as oos,
        (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)/count(bcc_models.id)) as ha


        ', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);


        if ($cluster_id != '-1') {
            $this->db->where('products.cluster_id ', $cluster_id);
        }


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);


        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }




        $this->db->group_by('outlets.channel');
        $this->db->group_by('models.product_id');
        return $this->db->get()->result_array();
    }

    public function get_av_oos_channel($date_type, $start_date, $end_date, $zone_id, $category_id, $activity, $super_market_project) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date, products.name as product_name, products.id as product_id, outlets.zone as zone, outlets.channel as channel,
        brands.name as brand_name, sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total,
        (sum(bcc_models.av)/count(bcc_models.id)) as av, (sum(bcc_models.av)/count(bcc_models.id)) as av_henkel, (1-(sum(bcc_models.av)/count(bcc_models.id))) as oos, (1-(sum(bcc_models.av)/count(bcc_models.id))) as oos_henkel', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');



        if ($zone_id != 'false') {

            //$this -> db -> where('zones.id', $zone_id);
            $this->db->where_in('zones.id ', $zone_id);
        }

        // if($activity!='-1'){
        // $this -> db -> where('outlets.activity', $activity);	
        // }
        // if($super_market_project!='-1'){
        // $this -> db -> where('outlets.super_market_project', 1);	
        // }

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av != ', 2);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);







        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('outlets.channel');
        return $this->db->get()->result_array();
    }

    public function get_av_oos_by_channel($date_type, $start_date, $end_date, $zone_id, $category_id, $activity, $super_market_project, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date, products.name as product_name, products.id as product_id, outlets.zone as zone, outlets.channel as channel,
        brands.name as brand_name, sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total,
        (sum(bcc_models.av)/count(bcc_models.id)) as av, (sum(bcc_models.av)/count(bcc_models.id)) as av_henkel, (1-(sum(bcc_models.av)/count(bcc_models.id))) as oos, (1-(sum(bcc_models.av)/count(bcc_models.id))) as oos_henkel', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        if ($zone_id != 'false') {

            //$this -> db -> where('zones.id', $zone_id);
            $this->db->where_in('zones.id ', $zone_id);
        }

        // if($activity!='-1'){
        // $this -> db -> where('outlets.activity', $activity);	
        // }
        // if($super_market_project!='-1'){
        // $this -> db -> where('outlets.super_market_project', 1);	
        // }
        $this->db->where('outlets.channel', $channel);
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');


        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av != ', 2);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);







        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('outlets.channel');
        return $this->db->get()->result_array();
    }

    public function get_av_oos_zone($date_type, $start_date, $end_date, $zone_id, $category_id, $activity, $super_market_project) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date, products.name as product_name, products.id as product_id, outlets.zone as zone, outlets.channel as channel,
        brands.name as brand_name, sum(bcc_models.av) as sum_av_sku,
        count(bcc_models.id) as total,
        (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
        (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
        (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha', false);
        $this->db->from('visits');



        $this->db->join('outlets', 'visits.outlet_id = outlets.id');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        if ($zone_id != '-1') {

            //$this -> db -> where('zones.id', $zone_id);
            $this->db->where('zones.id ', $zone_id);
        }

        // if($activity!='-1'){
        // $this -> db -> where('outlets.activity', $activity);	
        // }
        // if($super_market_project!='-1'){
        // $this -> db -> where('outlets.super_market_project', 1);	
        // }


        $this->db->where('product_groups.active', 1);


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);



        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('models.brand_id');
        return $this->db->get()->result_array();
    }

    public function get_av_oos_zone_cluster($date_type, $start_date, $end_date, $zone_id, $category_id, $activity, $super_market_project, $cluster_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date, products.name as product_name, products.id as product_id, outlets.zone as zone, outlets.channel as channel,
        brands.name as brand_name, sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total,
        (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)/count(bcc_models.id)) as av,
        (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)/count(bcc_models.id)) as oos,
        (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)/count(bcc_models.id)) as ha,
        ', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        if ($zone_id != '-1') {

            //$this -> db -> where('zones.id', $zone_id);
            $this->db->where('zones.id ', $zone_id);
        }

        // if($activity!='-1'){
        // $this -> db -> where('outlets.activity', $activity);	
        // }
        // if($super_market_project!='-1'){
        // $this -> db -> where('outlets.super_market_project', 1);	
        // }

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        if ($cluster_id != '-1') {
            $this->db->where('products.cluster_id ', $cluster_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);



        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('products.id');
        $this->db->order_by('brands.id');
        return $this->db->get()->result_array();
    }

    public function get_av_oos_zone_channel($date_type, $start_date, $end_date, $zone_id, $category_id, $activity, $super_market_project, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date, products.name as product_name, products.id as product_id, outlets.zone as zone,
        outlets.channel as channel,
        brands.name as brand_name, sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total,
        (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
        (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
        (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        if ($zone_id != '-1') {

            //$this -> db -> where('zones.id', $zone_id);
            $this->db->where('zones.id ', $zone_id);
        }

        // if($activity!='-1'){
        // $this -> db -> where('outlets.activity', $activity);	
        // }
        // if($super_market_project!='-1'){
        // $this -> db -> where('outlets.super_market_project', 1);	
        // }
        $this->db->where('outlets.channel', $channel);
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);



        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('brands.id');
        return $this->db->get()->result_array();
    }

    public function get_av_oos_zone_channel_cluster($date_type, $start_date, $end_date, $zone_id, $category_id, $activity, $super_market_project, $channel, $cluster_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date, products.name as product_name, products.id as product_id, outlets.zone as zone, outlets.channel as channel,
        brands.name as brand_name, sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total,
        (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
        (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
        (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        if ($zone_id != '-1') {

            //$this -> db -> where('zones.id', $zone_id);
            $this->db->where('zones.id ', $zone_id);
        }


        $this->db->where('outlets.channel', $channel);
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        if ($cluster_id != '-1') {
            $this->db->where('products.cluster_id ', $cluster_id);
        }
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);



        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('products.id');

        $this->db->order_by('brands.id');

        return $this->db->get()->result_array();
    }

    // Stock issues reports
    public function get_shelf_data($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('quarter(' . $date . ') as date,
        product_groups.name as product_name,
        product_groups.id as product_id,
        outlets.zone as zone,
        brands.name as brand_name,
        count(bcc_models.id) as total, sum(bcc_models.shelf) as shelf
        ', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->where('visits.monthly_visit', 1);
        if ($zone_id != '-1') {


            $this->db->where('zones.id', $zone_id);
        }

        // if($activity!='-1'){
        // $this -> db -> where('outlets.activity', $activity);	
        // }
        if ($channel != '-1') {
            $this->db->where('outlets.channel', $channel);
        }
        // if($super_market_project!='-1'){
        // $this -> db -> where('outlets.super_market_project', 1);	
        // }


        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        //  $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av != ', 2);
        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id != ', 8); //
        $this->db->where('brands.active', 1);

        if ($cluster_id != '-1') {
            $this->db->where('product_groups.cluster_id ', $cluster_id);
        }
        $this->db->group_by('quarter(' . $date . ')');
        $this->db->group_by('models.product_id');
        $this->db->order_by('date', 'desc');
        $this->db->order_by('shelf', 'desc');
        return $this->db->get()->result_array();
    }

    /*
      public function load_shelf_all_zones_group_by_channel_brand($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project, $channel) {
      if ($date_type == 'month') {
      $date = 'm_date';
      } else {
      $date = 'w_date';
      }
      $this->db->select('visits.' . $date . ' as date1, products.name as product_name, products.id as product_id, outlets.zone as zone,
      brands.name as brand_name, sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total, sum(bcc_models.shelf) as shelf, outlets.channel as date,
      ', false);
      $this->db->from('visits');

      $this->db->join('outlets', 'visits.outlet_id = outlets.id');
      $this->db->join('zones', 'outlets.zone = zones.name');

      if ($channel != -1) {
      if (is_array($channel)) {
      $this->db->where_in('outlets.channel ', $channel);
      } else {
      $this->db->where('outlets.channel ', $channel);
      }
      }

      $this->db->join('models', 'models.visit_id = visits.id');
      $this->db->join('brands', 'brands.id = models.brand_id');
      $this->db->join('products', 'products.id = models.product_id');

      $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
      $this->db->where('product_groups.active', 1);

      if ($category_id != '-1') {
      $this->db->where('models.category_id', $category_id);
      }

      $this->db->where('visits.' . $date . ' >= ', $start_date);
      $this->db->where('visits.' . $date . ' <= ', $end_date);
      $this->db->where('models.brand_id != ', 8);
      $this->db->where('brands.active', 1);
      if ($zone_id != '-1') {
      if (is_array($zone_id)) {
      $this->db->where_in('zones.id ', $zone_id);
      } else {
      $this->db->where('zones.id ', $zone_id);
      }
      }
      if ($cluster_id != '-1') {
      $this->db->where('products.cluster_id ', $cluster_id);
      }
      $this->db->group_by('visits.' . $date . ' ');
      $this->db->group_by('models.brand_id');
      $this->db->order_by('shelf', 'desc');
      return $this->db->get()->result_array();
      }
     */

    public function get_shelf_data_groupezone($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date,
        products.name as product_name,
        products.id as product_id,
        zones.name as zone,
        brands.name as brand_name,
        sum(bcc_models.av) as sum_av_sku,
        count(bcc_models.id) as total,
        sum(bcc_models.shelf) as shelf
        ', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('models.av != ', 2);


        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }


        $this->db->where('product_groups.active', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);



        $this->db->where('brands.active', 1);


        $this->db->group_by('models.brand_id');


        $this->db->group_by('outlets.zone');
        $this->db->order_by('visits.' . $date, 'desc');
        $this->db->order_by('shelf', 'desc');

        return $this->db->get()->result_array();
    }

    public function get_shelf_data_groupechannel($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date, products.name as product_name, products.id as product_id, zones.name as zone, outlets.channel as channel,
        brands.name as brand_name, sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total, sum(bcc_models.shelf) as shelf
        ', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        // if($activity!='-1'){
        // $this -> db -> where('outlets.activity', $activity);	
        // }
        // if($super_market_project!='-1'){
        // $this -> db -> where('outlets.super_market_project', 1);	
        // }


        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av != ', 2);
        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);



        $this->db->where('brands.active', 1);

        if ($category_id == '-1') {
            $this->db->group_by('models.brand_id');
        } else {
            $this->db->group_by('models.product_id');
        }

        $this->db->group_by('outlets.channel');

        $this->db->order_by('visits.' . $date, 'desc');
        $this->db->order_by('shelf', 'desc');
        return $this->db->get()->result_array();
    }

    //arrete
    function get_summ($zone_id, $year, $quarter, $date_type, $category_id, $activity, $super_market_project, $channel) {

        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }


        $this->db->select('sum(bcc_models.shelf) as shelf', false);
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->where('visits.year ', $year);
        $this->db->where('quarter', $quarter);
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);

        $this->db->where('models.av != ', 2);



        $this->db->where('brands.active', 1);
        $this->db->where('brands.id != ', 8);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }



        $query = $this->db->get()->row();
        return $query;
    }

    function get_brand_multiple_date_data_for_shelf($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel) {

        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select(' visits.' . $date . ' as date, '
                . 'visits.id as visit_id, '
                . 'quarter(' . $date . ') as date2,
        count(bcc_models.id) as total,
        sum(bcc_models.shelf) as shelf,
        , brands.name as brand_name,
        brands.color as brand_color', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');
        // $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');

        $this->db->where('visits.monthly_visit', 1);

        $this->db->where('product_groups.active', 1);
        // $this->db->where('models.av != ', 2);
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }




        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);
        //   $this->db->where('brands.id != ', 8);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->order_by('visits.' . $date, 'desc');
        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('quarter(' . $date . ')');
        $this->db->group_by('models.brand_id');




        $query = $this->db->get()->result_array();




        $components = array();
        $brands = array();
        $brand_temp = array();


        $dates = array();
        $count_date = 0;


        foreach ($query as $row) {


            $date_quarter = ($row['date2']);



            if (!in_array($date_quarter, $dates)) {
                $dates[] = $date_quarter;
                $count_date += 1;
            }


            $br = array();
            if (!in_array($row['brand_name'], $brand_temp)) {
                $brand_temp[] = $row['brand_name'];
                $br['name'] = $row['brand_name'];
                $br['color'] = $row['brand_color'];
                $brands[] = $br;
            }

            if ($row['total'] != 0) {


                if ($date_type == 'month') {
                    $date = format_month($row['date']);
                } else {
                    $date = format_week($row['date']);
                }

                $year = strtotime($row['date']);


                $only_year = date("Y", strtotime("+0 month", $year));



                $tot = $this->get_summ($zone_id, $only_year, $row['date2'], $date_type, $category_id, $activity, $super_market_project, $channel)->shelf;



                $pourcentage = (($row['shelf']) / $tot) * 100;

                $components[$date_quarter] [$row['brand_name']] = number_format(($pourcentage), 2, '.', ' ');
            }
        }// end foreach query
        //
       // die();
        // $components[$date] [''] = 90;
        $br = array();
        $br['name'] = '';
        $br['color'] = 'rgba(255, 255, 255, .4)';
        $brands[] = $br;
        $data = array();



        foreach ($components as $date_quarter => $componentBrands) {


            $row_data = array();
            $row_data['date'] = $date_quarter;
            foreach ($brands as $brand) {
                if (isset($componentBrands[$brand['name']])) {
                    $row_data[$brand['name']] = $componentBrands[$brand['name']];
                }
            }
            $data[] = $row_data;
        }


        $result['brands'] = $brands;
        $result['data'] = $data;
        return $result;
    }

    function sum_get_brand_single_date_json_data_for_shelf($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel, $brand_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('sum(bcc_models.av) as oos_perc, outlets.channel as channel,
        avg(bcc_models.shelf) as shelf, count(bcc_models.id) as total,
        brands.id as brand_id, brands.name as brand_name, brands.color as color', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('models', 'models.visit_id = visits.id');

        $this->db->join('brands', 'brands.id = bcc_models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');


        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av != ', 2);

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('brands.active', 1);
        $this->db->where('brands.id', 1);
        //$this -> db -> where('models.brand_id != ',8);
        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        $this->db->order_by('brands.id', 'desc');







        $query = $this->db->get()->row();
        return $query;
    }

    function get_brand_single_date_json_data_channel($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel, $cluster_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('sum(bcc_models.av) as oos_perc,
        sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total, brands.id as brand_id, brands.name as brand_name', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av != ', 2);
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        // if($activity!='-1'){
        // $this -> db -> where('outlets.activity', $activity);	
        // }
        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }
        // if($super_market_project!='-1'){
        // $this -> db -> where('outlets.super_market_project', 1);	
        // }
        if ($cluster_id != '-1') {
            $this->db->where('products.cluster_id ', $cluster_id);
        }


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        //$this->db->where('zones.id',$zone_id);

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);
        $this->db->where('brands.id != ', 8);
        $this->db->where('brands.name != ', 'Others');

        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('brands.id');



        $query = $this->db->get()->result_array();
        $data = array();
        foreach ($query as $row) {

            $row_data = array();

            if ($row['total'] != 0) {
                $row_data['brand'] = $row['brand_name'];

                $row_data['av'] = number_format(($row['sum_av_sku'] / $row['total']) * 100, 2, '.', ' ');
                $row_data['oos'] = 100 - $row_data['av'];


                $data[] = $row_data;
            }//end if 
        }//end for
        return json_encode(array_reverse($data));
    }

    function get_brand_single_date_json_data($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('sum(bcc_models.av) as oos_perc,
        sum(bcc_models.av) as sum_av_sku, count(bcc_models.id) as total, brands.id as brand_id, brands.name as brand_name', false);



        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');



        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);

        $this->db->where('models.av != ', 2);


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }


        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);
        $this->db->where('brands.name != ', 'Others');

        if ($zone_id != -1) {
            if (is_array($zone_id)) {

                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }


        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }

        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('brands.id');
        //$this->db->group_by('zones.id');

        $this->db->from('visits');



        $query = $this->db->get()->result_array();
        $data = array();
        foreach ($query as $row) {

            if ($zone_id != -1) {
                $res = $this->get_brand_single_date_json_data_henkel_brand($row['brand_name'], $zone_id, $start_date, $end_date, $date_type, $category_id, -1, -1, $channel);
            } else {
                $res = $this->get_brand_single_date_json_data_henkel_brand_channel($row['brand_name'], $zone_id, $start_date, $end_date, $date_type, $category_id, -1, -1, $channel);
            }


            $row_data = array();

            if ($row['total'] != 0) {
                $row_data['brand'] = $row['brand_name'];

                $row_data['av'] = $res['av'];
                $row_data['oos'] = $res['oos'];


                $data[] = $row_data;
            }//end if 
        }//end for

        return json_encode(array_reverse($data));
    }

    function get_brand_single_date_json_data_henkel($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('sum(bcc_models.av) as sum_av_sku,
        (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
        (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
        (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha,
        count(bcc_models.id) as total', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id ', $zone_id);
            } else {
                $this->db->where('zones.id ', $zone_id);
            }
        }

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel ', $channel);
            } else {
                $this->db->where('outlets.channel ', $channel);
            }
        }


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('models.brand_id', 1);

        $this->db->where('visits.' . $date . ' >= ', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        if ($zone_id != -1) {
            $this->db->group_by('outlets.zone');
        }


        $query = $this->db->get()->result_array();


        $avg = 0;
        $total_av = 0;
        $total_oos = 0;
        $total_ha = 0;
        $i = 0;
        $row_data_av = array();
        $row_data_oos = array();
        $row_data_ha = array();
        $data = array();
        foreach ($query as $row) {
            $total_av = $total_av + ($row['av'] / $row['total']) * 100;
            $total_oos = $total_oos + ($row['oos'] / $row['total']) * 100;
            $total_ha = $total_ha + ($row['ha'] / $row['total']) * 100;
            $i++;
        }

        $avg_av = number_format($total_av / $i, 2, '.', ' ');
        $avg_oos = number_format($total_oos / $i, 2, '.', ' ');
        $avg_ha = number_format($total_ha / $i, 2, '.', ' ');

        $row_data_av['name'] = 'av';
        $row_data_av['color'] = '#298A08';
        $row_data_av['value'] = $avg_av;

        $row_data_oos['name'] = 'oos';
        $row_data_oos['value'] = $avg_oos;
        $row_data_oos['color'] = '#DF0101';

        $row_data_ha['name'] = 'ha';
        $row_data_ha['value'] = $avg_ha;
        $row_data_ha['color'] = '#FFFFF';

        $data[] = $row_data_av;
        $data[] = $row_data_oos;
        $data[] = $row_data_ha;

        return json_encode(array_reverse($data));
    }

    function get_brand_single_date_json_data_henkel_brand($brand_name, $zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('sum(bcc_models.av) as sum_av_sku,
		count(bcc_models.id) as total', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av !=', 2);
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id  ', $zone_id);
            } else {
                $this->db->where('zones.id  ', $zone_id);
            }
        }

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel  ', $channel);
            } else {
                $this->db->where('outlets.channel  ', $channel);
            }
        }


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('brands.name', $brand_name);

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        if ($zone_id != -1) {
            $this->db->group_by('outlets.zone');
        }


        $query = $this->db->get()->result_array();


        $avg = 0;
        $total = 0;
        $i = 0;
        $row_data_av = array();
        $row_data_oos = array();
        $data = array();
        foreach ($query as $row) {

            $total = $total + ($row['sum_av_sku'] / $row['total']) * 100;
            $i++;
        }

        $avg = number_format($total / $i, 2, '.', ' ');

        $row_data_av['name'] = 'av';
        $row_data_av['color'] = '#298A08';
        $row_data_av['value'] = $avg;

        $row_data_oos['name'] = 'oos';
        $row_data_oos['value'] = 100 - $avg;
        $row_data_oos['color'] = '#DF0101';


        $data['av'] = $avg;
        $data['oos'] = 100 - $avg;

        return $data;
    }

    function get_brand_single_date_json_data_henkel_brand_channel($brand_name, $zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('sum(bcc_models.av) as sum_av_sku,
		count(bcc_models.id) as total', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av !=', 2);
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id  ', $zone_id);
            } else {
                $this->db->where('zones.id  ', $zone_id);
            }
        }

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel  ', $channel);
            } else {
                $this->db->where('outlets.channel  ', $channel);
            }
        }


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('brands.name', $brand_name);

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        if (channel != -1) {
            $this->db->group_by('outlets.channel');
        }


        $query = $this->db->get()->result_array();


        $avg = 0;
        $total = 0;
        $i = 0;
        $row_data_av = array();
        $row_data_oos = array();
        $data = array();
        foreach ($query as $row) {

            $total = $total + ($row['sum_av_sku'] / $row['total']) * 100;
            $i++;
        }

        $avg = number_format($total / $i, 2, '.', ' ');

        $row_data_av['name'] = 'av';
        $row_data_av['color'] = '#298A08';
        $row_data_av['value'] = $avg;

        $row_data_oos['name'] = 'oos';
        $row_data_oos['value'] = 100 - $avg;
        $row_data_oos['color'] = '#DF0101';


        $data['av'] = $avg;
        $data['oos'] = 100 - $avg;

        return $data;
    }

    function get_brand_single_date_json_data_henkel_by_channel($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('sum(bcc_models.av) as sum_av_sku,
		count(bcc_models.id) as total', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av !=', 2);
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id  ', $zone_id);
            } else {
                $this->db->where('zones.id  ', $zone_id);
            }
        }

        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel  ', $channel);
            } else {
                $this->db->where('outlets.channel  ', $channel);
            }
        }


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('models.brand_id', 1);
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        $this->db->group_by('outlets.channel');
        $query = $this->db->get()->result_array();


        $avg = 0;
        $total = 0;
        $i = 0;
        $row_data_av = array();
        $row_data_oos = array();
        $data = array();
        foreach ($query as $row) {
            $total = $total + ($row['sum_av_sku'] / $row['total']) * 100;
            $i++;
        }

        $avg = number_format($total / $i, 2, '.', ' ');

        $row_data_av['name'] = 'av';
        $row_data_av['color'] = '#298A08';
        $row_data_av['value'] = $avg;

        $row_data_oos['name'] = 'oos';
        $row_data_oos['value'] = 100 - $avg;
        $row_data_oos['color'] = '#DF0101';


        $data[] = $row_data_av;
        $data[] = $row_data_oos;

        return json_encode(array_reverse($data));
    }

    function get_brand_single_date_json_data_henkel2($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('sum(bcc_models.av) as oos_perc,
		sum(bcc_models.av) as sum_av_sku,count(bcc_models.id) as total,brands.id as brand_id,brands.name as brand_name', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av !=', 2);
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id  ', $zone_id);
            } else {
                $this->db->where('zones.id  ', $zone_id);
            }
        }




        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel  ', $channel);
            } else {
                $this->db->where('outlets.channel  ', $channel);
            }
        }

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('models.brand_id', 1);
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);
        $this->db->where('brands.name !=', 'Others');

        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('brands.id');





        $query = $this->db->get()->result_array();


        $data = array();
        foreach ($query as $row) {

            $row_data = array();
            $row_data2 = array();

            if ($row['total'] != 0) {
                //  $row_data['brand']=$row['brand_name'] ;

                $row_data['name'] = 'av';
                $row_data['color'] = '#298A08';
                $row_data['value'] = number_format(($row['oos_perc'] / $row['total']) * 100, 2, '.', ' ');

                $row_data2['name'] = 'oos';
                $row_data2['value'] = 100 - $row_data['value'];
                $row_data2['color'] = '#DF0101';


                $data[] = $row_data;
                $data[] = $row_data2;
            }//end if 
        }//end for
        return json_encode(array_reverse($data));
    }

    function get_brand_single_date_json_data_henkel_bychannel($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel, $cluster_id) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('(sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)/count(bcc_models.id)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)/count(bcc_models.id)) as oos,
                 (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)/count(bcc_models.id)) as ha,  
		sum(bcc_models.av) as sum_av_sku,count(bcc_models.id) as total,
                brands.id as brand_id,brands.name as brand_name', false);
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id  ', $zone_id);
            } else {
                $this->db->where('zones.id  ', $zone_id);
            }
        }


        if ($channel != -1) {
            if (is_array($channel)) {
                $this->db->where_in('outlets.channel  ', $channel);
            } else {
                $this->db->where('outlets.channel  ', $channel);
            }
        }

        if ($cluster_id != '-1') {
            $this->db->where('products.cluster_id ', $cluster_id);
        }



        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        $this->db->where('models.brand_id', 1);
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);
        $this->db->where('brands.name !=', 'Others');
        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('brands.id');





        $query = $this->db->get()->result_array();
        $data = array();
        foreach ($query as $row) {

            $row_data = array();
            $row_data2 = array();
            $row_data3 = array();

            if ($row['total'] != 0) {
                //  $row_data['brand']=$row['brand_name'] ;

                $row_data['name'] = 'av';
                $row_data['color'] = '#298A08';
                $row_data['value'] = number_format(($row['av']) * 100, 2, '.', ' ');

                $row_data2['name'] = 'oos';
                $row_data2['value'] = number_format(($row['oos']) * 100, 2, '.', ' ');
                $row_data2['color'] = '#DF0101';

                $row_data3['name'] = 'ha';
                $row_data3['value'] = number_format(($row['ha']) * 100, 2, '.', ' ');
                $row_data3['color'] = '#FFFFF';

                $data[] = $row_data;
                $data[] = $row_data2;
                $data[] = $row_data3;
            }//end if 
        }//end for
        return json_encode(array_reverse($data));
    }

    function get_brand_multiple_date_data($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project) {

        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('sum(bcc_models.av) as oos_perc, visits.' . $date . ' as date,
		sum(bcc_models.av) as sum_av_sku,
                (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)/count(bcc_models.id)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)/count(bcc_models.id)) as oos,
                 (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)/count(bcc_models.id)) as ha,  
                count(bcc_models.id) as total
		,brands.name as brand_name,brands.color as brand_color', false);
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');


        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        //$this->db->where('models.av !=', 2);


        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id  ', $zone_id);
            } else {
                $this->db->where('zones.id  ', $zone_id);
            }
        }


        //$this->db->where('zones.id',$zone_id);

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);
        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('models.brand_id !=', 8);

        $this->db->order_by('visits.' . $date, 'desc');
        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('models.brand_id');





        $query = $this->db->get()->result_array();
        $components = array();
        $brands = array();
        $brand_temp = array();
        foreach ($query as $row) {
            $br = array();
            if (!in_array($row['brand_name'], $brand_temp)) {
                $brand_temp[] = $row['brand_name'];
                $br['name'] = $row['brand_name'];
                $br['color'] = $row['brand_color'];
                $brands[] = $br;
            }

            if ($row['total'] != 0) {

                $oos = number_format(($row['oos']) * 100, 2, '.', ' ');
                // $oos = 100 - $av;
                //create an array for every brand and the count at a outlet

                if ($date_type == 'month') {
                    $date = format_month($row['date']);
                } else {
                    $date = format_week($row['date']);
                }

                $components[$date] [$row['brand_name']] = $oos;
            }
        }// end foreach report_data

        $data = array();
        foreach ($components as $date => $componentBrands) {
            $row_data = array();
            $row_data['date'] = $date;
            foreach ($brands as $brand) {
                $row_data[$brand['name']] = $componentBrands[$brand['name']];
            }
            $data[] = $row_data;
        }
        $result['brands'] = $brands;
        $result['data'] = $data;
        return $result;
    }

    function get_brand_multiple_date_data_zone($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project) {

        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('sum(bcc_models.av) as oos_perc, visits.' . $date . ' as date,
		sum(bcc_models.av) as sum_av_sku,count(bcc_models.id) as total
		,brands.name as brand_name,brands.color as brand_color', false);
        $this->db->from('visits');


        $this->db->join('outlets', 'visits.outlet_id = outlets.id');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');




        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av !=', 2);
        if ($zone_id != '-1') {


            $this->db->where('zones.id', $zone_id);
        }

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('brands.active', 1);
        $this->db->where('models.brand_id !=', 8);
        $this->db->order_by('visits.' . $date, 'desc');
        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('date');
        $this->db->group_by('models.brand_id');





        $query = $this->db->get()->result_array();
        $components = array();
        $brands = array();
        $brand_temp = array();
        foreach ($query as $row) {
            $br = array();
            if (!in_array($row['brand_name'], $brand_temp)) {
                $brand_temp[] = $row['brand_name'];
                $br['name'] = $row['brand_name'];
                $br['color'] = $row['brand_color'];
                $brands[] = $br;
            }

            if ($row['total'] != 0) {

                $av = number_format(($row['sum_av_sku'] / $row['total']) * 100, 2, '.', ' ');
                $oos = 100 - $av;

                //create an array for every brand and the count at a outlet

                if ($date_type == 'month') {
                    $date = format_month($row['date']);
                } else {
                    $date = format_week($row['date']);
                }

                $components[$date] [$row['brand_name']] = $oos;
            }
        }// end foreach report_data

        $data = array();
        foreach ($components as $date => $componentBrands) {
            $row_data = array();
            $row_data['date'] = $date;
            foreach ($brands as $brand) {
                $row_data[$brand['name']] = $componentBrands[$brand['name']];
            }
            $data[] = $row_data;
        }
        $result['brands'] = $brands;
        $result['data'] = $data;
        return $result;
    }

    function get_brand_multiple_date_data_channel($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channels) {

        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('sum(bcc_models.av) as oos_perc, visits.' . $date . ' as date,
		sum(bcc_models.av) as sum_av_sku,count(bcc_models.id) as total
		,brands.name as brand_name,brands.color as brand_color,
                (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) as oos,
                 (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)) as ha,  ', false);
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');



        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');


        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);


        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id  ', $zone_id);
            } else {
                $this->db->where('zones.id  ', $zone_id);
            }
        }

        $this->db->where('outlets.channel', $channels);
        //$this->db->where('zones.id',$zone_id);

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);
        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('models.brand_id !=', 8);

        $this->db->order_by('visits.' . $date, 'desc');
        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('models.brand_id');





        $query = $this->db->get()->result_array();
        $components = array();
        $brands = array();
        $brand_temp = array();
        foreach ($query as $row) {
            $br = array();
            if (!in_array($row['brand_name'], $brand_temp)) {
                $brand_temp[] = $row['brand_name'];
                $br['name'] = $row['brand_name'];
                $br['color'] = $row['brand_color'];
                $brands[] = $br;
            }

            if ($row['total'] != 0) {

                $oos = number_format(($row['oos'] / $row['total']) * 100, 2, '.', ' ');


                //create an array for every brand and the count at a outlet

                if ($date_type == 'month') {
                    $date = format_month($row['date']);
                } else {
                    $date = format_week($row['date']);
                }

                $components[$date] [$row['brand_name']] = $oos;
            }
        }// end foreach report_data

        $data = array();
        foreach ($components as $date => $componentBrands) {
            $row_data = array();
            $row_data['date'] = $date;
            foreach ($brands as $brand) {
                $row_data[$brand['name']] = $componentBrands[$brand['name']];
            }
            $data[] = $row_data;
        }
        $result['brands'] = $brands;
        $result['data'] = $data;
        return $result;
    }

    function get_brand_multiple_date_data_zonn($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project) {

        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('sum(bcc_models.av) as oos_perc, visits.' . $date . ' as date,
		sum(bcc_models.av) as sum_av_sku,count(bcc_models.id) as total,
                (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)/count(bcc_models.id)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)/count(bcc_models.id)) as oos,
                 (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)/count(bcc_models.id)) as ha,  
		,brands.name as brand_name,brands.color as brand_color', false);
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');




        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');


        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);


        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id  ', $zone_id);
            } else {
                $this->db->where('zones.id  ', $zone_id);
            }
        }


        //$this->db->where('zones.id',$zone_id);

        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);
        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('models.brand_id !=', 8);

        $this->db->order_by('visits.' . $date, 'desc');
        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('visits.' . $date . ' ');
        $this->db->group_by('models.brand_id');





        $query = $this->db->get()->result_array();
        $components = array();
        $brands = array();
        $brand_temp = array();
        foreach ($query as $row) {
            $br = array();
            if (!in_array($row['brand_name'], $brand_temp)) {
                $brand_temp[] = $row['brand_name'];
                $br['name'] = $row['brand_name'];
                $br['color'] = $row['brand_color'];
                $brands[] = $br;
            }

            if ($row['total'] != 0) {

                $oos = number_format(($row['oos'] ) * 100, 2, '.', ' ');


                //create an array for every brand and the count at a outlet

                if ($date_type == 'month') {
                    $date = format_month($row['date']);
                } else {
                    $date = format_week($row['date']);
                }

                $components[$date] [$row['brand_name']] = $oos;
            }
        }// end foreach report_data

        $data = array();
        foreach ($components as $date => $componentBrands) {
            $row_data = array();
            $row_data['date'] = $date;
            foreach ($brands as $brand) {
                $row_data[$brand['name']] = $componentBrands[$brand['name']];
            }
            $data[] = $row_data;
        }
        $result['brands'] = $brands;
        $result['data'] = $data;
        return $result;
    }

    function get_brand_multiple_date_data_channel6($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channels) {


        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('sum(bcc_models.av) as oos_perc, visits.' . $date . ' as date,
		sum(bcc_models.av) as sum_av_sku,count(bcc_models.id) as total
		,brands.name as brand_name,brands.color as brand_color,outlets.channel as channel', false);
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av !=', 2);
        //$this -> db -> join('products', 'products.id = models.product_id');
        // if($activity!='-1'){
        // $this -> db -> where('outlets.activity', $activity);	
        // }
        // if($super_market_project!='-1'){
        // $this -> db -> where('outlets.super_market_project', 1);	
        // }



        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);
        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        //$this -> db -> where('brands.name !=', 'Others');


        $this->db->where('outlets.channel', $channels);




        $this->db->order_by('visits.' . $date, 'desc');
        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('date');
        $this->db->group_by('brands.id');





        $query = $this->db->get()->result_array();

        $components = array();
        $brands = array();
        $brand_temp = array();
        foreach ($query as $row) {
            $br = array();
            if (!in_array($row['brand_name'], $brand_temp)) {
                $brand_temp[] = $row['brand_name'];
                $br['name'] = $row['brand_name'];
                $br['color'] = $row['brand_color'];
                $brands[] = $br;
            }

            if ($row['total'] != 0) {

                $av = number_format(($row['sum_av_sku'] / $row['total']) * 100, 2, '.', ' ');
                $oos = 100 - $av;

                //create an array for every brand and the count at a outlet

                if ($date_type == 'month') {
                    $date = format_month($row['date']);
                } else {
                    $date = format_week($row['date']);
                }

                $components[$date] [$row['brand_name']] = $oos;
            }
        }// end foreach report_data

        $data = array();
        foreach ($components as $date => $componentBrands) {
            $row_data = array();
            $row_data['date'] = $date;
            foreach ($brands as $brand) {
                $row_data[$brand['name']] = $componentBrands[$brand['name']];
            }
            $data[] = $row_data;
        }
        $result['brands'] = $brands;
        $result['data'] = $data;
        return $result;
    }

    function get_brand_multiple_date_data2($zone_id, $start_date, $end_date, $date_type, $category_id, $activity) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }

        $this->db->select('sum(bcc_models.av) as oos_perc, visits.' . $date . ' as date,
		sum(bcc_models.av) as sum_av_sku,count(bcc_models.id) as total
		,brands.name as brand_name,brands.color as brand_color', false);
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');


        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id  ', $zone_id);
            } else {
                $this->db->where('zones.id  ', $zone_id);
            }
        }
        if ($activity != '-1') {
            $this->db->where('outlets.activity', $activity);
        }


        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active', 1);
        $this->db->where('models.av !=', 2);
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);
        $this->db->where('brands.active', 1);
        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('brands.name !=', 'Others');
        $this->db->order_by('visits.' . $date, 'desc');
        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('date');
        $this->db->group_by('brand_id');





        $query = $this->db->get()->result_array();
        $components = array();
        $brands = array();
        $brand_temp = array();
        foreach ($query as $row) {
            $br = array();
            if (!in_array($row['brand_name'], $brand_temp)) {
                $brand_temp[] = $row['brand_name'];
                $br['name'] = $row['brand_name'];
                $br['color'] = $row['brand_color'];
                $brands[] = $br;
            }

            if ($row['total'] != 0) {

                $av = number_format(($row['sum_av_sku'] / $row['total']) * 100, 2, '.', ' ');
                $oos = 100 - $av;

                //create an array for every brand and the count at a outlet

                if ($date_type == 'month') {
                    $date = format_month($row['date']);
                } else {
                    $date = format_week($row['date']);
                }

                $components[$date] [$row['brand_name']] = $oos;
            }
        }// end foreach report_data

        $data = array();
        foreach ($components as $date => $componentBrands) {
            $row_data = array();
            $row_data['date'] = $date;
            foreach ($brands as $brand) {
                $row_data[$brand['name']] = $componentBrands[$brand['name']];
            }
            $data[] = $row_data;
        }
        $result['brands'] = $brands;
        $result['data'] = $data;
        return $result;
    }

    public function count_pictures_data($start_date, $end_date) {
        $this->db->select('admin_id as admin,date as date,count(branding_images) as number_branding,count(display_images) as number_display,count(competitor_images) as number_competitor ,count(voice_images) as number_voice');
        $this->db->from('weekly_visits');
        if ($start_date != '-1' && $end_date != '-1') {
            $this->db->where('weekly_visits.date >=', $start_date);
            $this->db->where('weekly_visits.date <=', $end_date);
        }
        $this->db->group_by('weekly_visits.admin_id');

        $result = $this->db->get();
        return $result->result();
    }

    public function get_shelf_share_data($start_date, $end_date, $zone_id, $cluster_id) {

        $this->db->select('visits.m_date as date,products.id as product_id,sum(bcc_models.shelf) as shelf,
		', false);
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->where('visits.m_date >=', $start_date);
        $this->db->where('visits.m_date <= ', $end_date);
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id  ', $zone_id);
            } else {
                $this->db->where('zones.id  ', $zone_id);
            }
        }
        $this->db->where('products.cluster_id ', $cluster_id);
        $this->db->where('visits.monthly_visit ', 1);



        $this->db->group_by('visits.m_date');
        $this->db->group_by('models.product_id');



        return $this->db->get()->result_array();
    }

    public function get_shelf_share_chart($start_date, $end_date, $zone_id, $category_id) {

        $this->db->select('brands.name as brand_name,sum(bcc_models.shelf) as shelf
		', false);
        $this->db->from('visits');



        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->where('brands.selected', 1);
        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('visits.m_date >=', $start_date);
        $this->db->where('visits.m_date <= ', $end_date);
        if ($zone_id != -1) {
            $this->db->where('zones.id  ', $zone_id);
        }

        $this->db->where('visits.monthly_visit ', 1);



        $this->db->group_by('brands.id');




        return json_encode($this->db->get()->result_array());
    }

    public function get_shelf_share_chart2($start_date, $end_date, $zone_id, $category_id) {

        $this->db->select('brands.name as brand_name,sum(bcc_models.shelf) as shelf,visits.m_date as date,brands.color as brand_color
		', false);
        $this->db->from('visits');



        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->where('brands.selected', 1);
        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('visits.m_date >=', $start_date);
        $this->db->where('visits.m_date <= ', $end_date);
        if ($zone_id != -1) {
            $this->db->where('zones.id  ', $zone_id);
        }

        $this->db->where('visits.monthly_visit ', 1);



        $this->db->group_by('brands.id');
        $this->db->group_by('visits.m_date');




        return ($this->db->get()->result_array());
    }

    public function get_shelf_share_chart3($start_date, $end_date, $zones, $category_id) {

        $this->db->select('brands.name as brand_name,sum(bcc_models.shelf) as shelf,zones.name as zone_name,brands.color as brand_color
		', false);
        $this->db->from('visits');


        foreach ($zones as $zone) {
            $ids[] = $zone;
        }


        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->where('brands.selected', 1);
        $this->db->where('visits.m_date >=', $start_date);
        $this->db->where('visits.m_date <= ', $end_date);
        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        // if($zone_id !=-1)
        // {
        $this->db->where_in('zones.id  ', $ids);
        // }

        $this->db->where('visits.monthly_visit ', 1);



        $this->db->group_by('brands.id');
        $this->db->group_by('zones.id');




        return ($this->db->get()->result_array());
    }

    public function get_price_monitoring_data2($start_date) {

        $this->db->select('outlets.name as outlet_name,products.name as product_name,products.id as product_id,brands.name as brand_name,models.price as price,models.promo_price as promo_price,count(bcc_models.id) as total,(sum(bcc_models.av)/count(bcc_models.id)) as av,(1-(sum(bcc_models.av)/count(bcc_models.id))) as oos', false);
        $this->db->from('visits');



        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->where('visits.m_date', $start_date);

        //henkel
        $this->db->where('models.brand_id !=', 8);

        $this->db->where('visits.monthly_visit ', 1);



        $this->db->group_by('outlets.name');
        $this->db->group_by('models.product_id');



        return $this->db->get()->result_array();
    }

    public function get_price_compare_data($start_date, $zone_id, $cluster_id) {

        $this->db->select('outlets.name as outlet_name,products.name as product_name,products.id as product_id,brands.name as brand_name,min(bcc_models.price) as min_price,max(bcc_models.price) as max_price,avg(bcc_models.price) as avg_price,count(bcc_models.id) as total,(sum(bcc_models.av)/count(bcc_models.id)) as av,(1-(sum(bcc_models.av)/count(bcc_models.id))) as oos', false);
        $this->db->from('visits');



        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->where('visits.m_date', $start_date);
        if ($zone_id != -1) {
            $this->db->where('zones.id  ', $zone_id);
        }
        $this->db->where('models.brand_id !=', 8);
        $this->db->where('products.cluster_id ', $cluster_id);

        $this->db->where('models.price >', 0);
        $this->db->where('visits.monthly_visit ', 1);



        //$this -> db -> group_by('outlets.name');
        $this->db->group_by('models.product_id');



        return $this->db->get()->result_array();
    }

    public function get_data_collection($start_date, $end_date, $outlet_id) {

        $this->db->select('weekly_models.model_id as model_id,weekly_visits.date as date,
		SUM(' . $this->dbprefix . 'weekly_models.ws) as ws,SUM(' . $this->dbprefix . 'weekly_models.shelf) as shelf', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('weekly_visits.date >=', $start_date);
        $this->db->where('weekly_visits.date <= ', $end_date);
        if ($outlet_id != -1) {
            $this->db->where('weekly_visits.outlet_id ', $outlet_id);
        }
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_models.model_id');
        $this->db->group_by('weekly_visits.date');
        $this->db->order_by('weekly_visits.date', 'ASC');
        $this->db->order_by('models.code', 'ASC');

        return $this->db->get()->result_array();
    }

    function count_weekly_visits_pictures($search = '-1', $fo_id = '-1', $start_date = '-1', $end_date = '-1', $type = '-1') {
        $this->db->join('outlets', 'outlets.id=weekly_visits.outlet_id');
        $this->db->join('admin', 'admin.id=weekly_visits.admin_id');



        if ($search != '-1') {
            $search = strtoupper($search);
            $this->db->where("(
		 UPPER(hhp_outlets.name) LIKE '%" . $search . "%'
		
		)");
        }

        if ($fo_id != '-1') {
            $this->db->where('weekly_visits.admin_id', $fo_id);
        }

        if ($start_date != '-1' && $end_date != '-1') {
            $this->db->where('weekly_visits.date >=', $start_date);
            $this->db->where('weekly_visits.date <=', $end_date);
        }
        if ($type == 'Branding') {
            $this->db->where('LENGTH(' . $this->dbprefix . 'weekly_visits.branding_images) >', 3, FALSE);
        }
        if ($type == 'Display') {
            $this->db->where('LENGTH(' . $this->dbprefix . 'weekly_visits.display_images) >', 3, FALSE);
        }
        if ($type == 'Competitor') {
            $this->db->where('LENGTH(' . $this->dbprefix . 'weekly_visits.competitor_images) >', 3, FALSE);
        }
        if ($type == 'Voice') {
            $this->db->where('LENGTH(' . $this->dbprefix . 'weekly_visits.voice_images) >', 3, FALSE);
        }

        return $this->db->count_all_results('weekly_visits');
        //$result = $this -> db -> get('sales');
        //return count($result -> result());
    }

    function get_weekly_visits_pictures($limit = 0, $offset = 0, $order_by = 'weekly_visits.id', $direction = 'DESC', $search, $fo_id, $start_date, $end_date, $type) {

        $this->db->select('weekly_visits.id as id,outlets.name as outlet_name,admin.lastname as last,admin.firstname as first,
		weekly_visits.date as date,weekly_visits.branding_images as branding_images,weekly_visits.display_images as display_images,weekly_visits.voice_images as voice_images,weekly_visits.competitor_images as competior_images');
        $this->db->join('outlets', 'outlets.id=weekly_visits.outlet_id');
        $this->db->join('admin', 'admin.id=weekly_visits.admin_id');
        $this->db->from('weekly_visits');

        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        /* if ($this -> auth -> check_access('Salesman')) {
          $this -> db -> where('sales.active!=', 5);
          } */


        if ($search != '-1') {
            $search = strtoupper($search);
            $this->db->where("(
		 UPPER( hhp_outlets.name) LIKE '%" . $search . "%'
		
		)");
        }
        if ($fo_id != '-1') {
            $this->db->where('weekly_visits.admin_id', $fo_id);
        }
        if ($start_date != '-1' && $end_date != '-1') {
            $this->db->where('weekly_visits.date >=', $start_date);
            $this->db->where('weekly_visits.date <=', $end_date);
        }
        if ($type == 'Branding') {
            $this->db->where('LENGTH(' . $this->dbprefix . 'weekly_visits.branding_images) >', 3, FALSE);
        }
        if ($type == 'Display') {
            $this->db->where('LENGTH(' . $this->dbprefix . 'weekly_visits.display_images) >', 3, FALSE);
        }
        if ($type == 'Competitor') {
            $this->db->where('LENGTH(' . $this->dbprefix . 'weekly_visits.competitor_images) >', 3, FALSE);
        }
        if ($type == 'Voice') {
            $this->db->where('LENGTH(' . $this->dbprefix . 'weekly_visits.voice_images) >', 3, FALSE);
        }


        $result = $this->db->get();
        return $result->result();
    }

    public function get_sum_weekly_ws_data($from, $to, $outlet_id) {

        $this->db->select('COUNT(' . $this->dbprefix . 'weekly_models.id) as nb,brands.name as brand, models.name as model, models.id as model_id, models.price as std_price, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        $this->db->where('weekly_visits.outlet_id ', $outlet_id);

        $this->db->group_by('weekly_models.model_id');

        $this->db->order_by('models.code', 'DESC');

        //$this -> db -> where('weekly_visits.active', 1);

        return $this->db->get()->result_array();
    }

    function get_outlets_by_admin_zone($user_id, $channel_id) {
        $this->db->order_by('name', 'DESC');
        $this->db->where('active', 1);

        $this->db->where('admin_id', $user_id);

        if ($channel_id != -1) {
            $this->db->where('channel', $channel_id);
        }




        $result = $this->db->get('outlets');
        return $result->result();
    }

    //All models reports
    public function get_sum_weekly_model_data($from, $to, $model_id) {

        $this->db->select('COUNT(' . $this->dbprefix . 'weekly_models.id) as nb,brands.name as brand, models.name as model, models.id as model_id, models.price as std_price, SUM(' . $this->dbprefix . 'weekly_models.shelf) as shelf, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws, AVG(' . $this->dbprefix . 'weekly_models.price) as price', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('outlets.antenna', 1);
        $this->db->join('outlets', 'weekly_visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');

        $this->db->join('admin', 'weekly_visits.admin_id = admin.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        if ($model_id != -1) {
            $this->db->where('weekly_models.model_id', $model_id);
        }

        $this->db->group_by('weekly_models.model_id');

        $this->db->order_by('models.code', 'ASC');

        //$this -> db -> where('weekly_visits.active', 1);

        return $this->db->get()->result_array();
    }

    public function get_competitor_data($from, $to, $outlet_id, $admin_id) {

        $this->db->select('*');
        $this->db->from('competitors_activities');

        $this->db->where('competitors_activities.date >=', $from);
        $this->db->where('competitors_activities.date <=', $to);


        $this->db->where('competitors_activities.before_images !=', '');
        $this->db->where('competitors_activities.before_images !=', 'false');
        //	$this -> db -> where('competitors_activities.after_images !=', 'false');

        if ($outlet_id != -1) {
            $this->db->where('competitors_activities.outlet_id', $outlet_id);
        }

        if ($admin_id != -1) {
            $this->db->where('competitors_activities.admin_id', $admin_id);
        }



        $this->db->order_by('competitors_activities.date_taken', 'DESC');




        return $this->db->get()->result();
    }

    public function get_brandng_data($from, $to, $outlet_id, $admin_id) {

        $this->db->select('*');
        $this->db->from('pictures');

        $this->db->where('pictures.date >=', $from);
        $this->db->where('pictures.date <=', $to);
        $this->db->where('pictures.type', 'brand');
        $this->db->order_by('pictures.date_taken', 'DESC');


        if ($outlet_id != -1) {
            $this->db->where('pictures.outlet_id', $outlet_id);
        }

        if ($admin_id != -1) {
            $this->db->where('pictures.admin_id', $admin_id);
        }


        $where = "('.$this->dbprefix.'pictures.before_images != '' or '.$this->dbprefix.'pictures.after_images != '')  and ('.$this->dbprefix.'pictures.before_images != 'false' or '.$this->dbprefix.'pictures.after_images != 'false' ) ";

        $this->db->where($where);




        return $this->db->get()->result();
    }

    public function get_visits_data($from, $to, $admin_id, $zone, $channel_id) {

        $this->db->select('visits.id as id ,visits.date as date,outlets.name as outlet_name,outlets.zone as zone,outlets.photos as outlet_picture,admin.name as admin_name,visits.branding_pictures as branding_pictures,visits.one_pictures as one_pictures');
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('admin', 'admin.id = visits.admin_id');

        if ($from != '') {
            $this->db->where('visits.date >=', $from);
            $this->db->where('visits.date <=', $to);
        }

        if ($admin_id != -1) {
            $this->db->where('visits.admin_id', $admin_id);
        }

        if ($zone != -1) {
            $this->db->where('outlets.zone', $zone);
        }

        if ($channel_id != '-1') {
            $this->db->where('outlets.channel', $channel_id);
        }

        $where = "(bcc_visits.branding_pictures != '' or bcc_visits.one_pictures != '')  and (bcc_visits.branding_pictures != '[]' or bcc_visits.one_pictures != '[]' ) ";

        $this->db->where($where);

        $this->db->order_by('visits.outlet_id', 'desc');
        return $this->db->get()->result();
    }

    public function get_best_of_visits_data($from, $to, $admin_id, $zone, $channel_id) {

        $this->db->select('visits.id as id ,visits.date as date,outlets.zone as zone,outlets.name as outlet_name,outlets.photos as outlet_picture,admin.name as admin_name,rayons.branding_pictures as branding_pictures');
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('admin', 'admin.id = visits.admin_id');
        $this->db->join('rayons', 'rayons.visit_id = visits.id');

        if ($from != '') {
            $this->db->where('visits.date >=', $from);
            $this->db->where('visits.date <=', $to);
        }

        if ($channel_id != '-1') {
            $this->db->where('outlets.channel', $channel_id);
        }

        if ($admin_id != -1) {
            $this->db->where('visits.admin_id', $admin_id);
        }

        if ($zone != -1) {
            $this->db->where('outlets.zone', $zone);
        }





        //$this -> db -> group_by('rayons.visit_id');

        return $this->db->get()->result();
    }

    public function get_sum_worked_time_data($from, $to, $admin_id) {

        $this->db->select('sum(bcc_visits.worked_time) as worked_time,sum(last_time) as last_time,visits.id as id ,visits.date as date,
		outlets.name as outlet_name,outlets.photos as outlet_picture,admin.name as admin_name,admin.id as admin_id,visits.branding_pictures as branding_pictures,
		visits.one_pictures as one_pictures,min(bcc_visits.entry_time) as min_entry_time,max(bcc_visits.exit_time) as max_entry_time,TIMEDIFF( max(bcc_visits.exit_time),min(bcc_visits.entry_time) ) as travel_time,avg(bcc_visits.entry_time) as avg_entry,
		SEC_TO_TIME(AVG(TIME_TO_SEC(bcc_visits.entry_time))) as moyenne
		
		', false);
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('admin', 'admin.id = visits.admin_id');

        if ($from != '') {
            $this->db->where('visits.date >=', $from);
            $this->db->where('visits.date <=', $to);
        }

        if ($admin_id != -1) {
            $this->db->where('visits.admin_id', $admin_id);
        }


        $this->db->group_by('visits.admin_id');
        $this->db->group_by('visits.date');

        $result = $this->db->get()->result();


        return $result;
    }

    public function get_sum_worked_time_data_row($from, $to, $admin_id) {

        $this->db->select('sum(bcc_visits.worked_time) as worked_time,sum(last_time) as last_time,visits.id as id ,visits.date as date,
		admin.name as admin_name,admin.id as admin_id,min(bcc_visits.entry_time) as min_entry_time,max(bcc_visits.exit_time) as max_entry_time,TIMEDIFF( max(bcc_visits.exit_time),min(bcc_visits.entry_time) ) as travel_time,avg(bcc_visits.entry_time) as avg_entry,
		SEC_TO_TIME(AVG(TIME_TO_SEC((bcc_visits.entry_time)))) as moyenne
		
		', false);
        $this->db->from('visits');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('admin', 'admin.id = visits.admin_id');

        if ($from != '') {
            $this->db->where('visits.date >=', $from);
            $this->db->where('visits.date <=', $to);
        }

        if ($admin_id != -1) {
            $this->db->where('visits.admin_id', $admin_id);
        }


        // $this -> db -> group_by('visits.DATE');
        //	$this -> db -> ORDER_by('min_entry_time','desc');

        $result = $this->db->get()->result();


        return $result;
    }

    public function get_display_data($from, $to, $outlet_id, $admin_id) {

        $this->db->select('*');
        $this->db->from('pictures');

        $this->db->where('pictures.date >=', $from);
        $this->db->where('pictures.date <=', $to);
        $this->db->where('pictures.type', 'display');

        $this->db->where('pictures.after_images !=', '');
        //$this -> db -> where('pictures.after_images !=', 'false');
        $this->db->where('pictures.before_images !=', 'false');
        if ($outlet_id != -1) {
            $this->db->where('pictures.outlet_id', $outlet_id);
        }

        if ($admin_id != -1) {
            $this->db->where('pictures.admin_id', $admin_id);
        }



        $this->db->order_by('pictures.date_taken', 'DESC');




        return $this->db->get()->result();
    }

    public function get_other_data($from, $to, $outlet_id, $admin_id) {

        $this->db->select('*');
        $this->db->from('pictures');

        $this->db->where('pictures.date >=', $from);
        $this->db->where('pictures.date <=', $to);
        $this->db->where('pictures.type', 'Other');

        $this->db->where('pictures.before_images !=', '');
        $this->db->where('pictures.before_images !=', 'false');
        //$this -> db -> where('pictures.after_images !=', 'false');

        if ($outlet_id != -1) {
            $this->db->where('pictures.outlet_id', $outlet_id);
        }

        if ($admin_id != -1) {
            $this->db->where('pictures.admin_id', $admin_id);
        }







        return $this->db->get()->result();
    }

    public function get_week_weekly_model_data($from, $to, $model_id) {

        $this->db->select('weekly_visits.date as date, models.id as model_id, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws, SUM(' . $this->dbprefix . 'weekly_models.shelf) as shelf ', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        //$this -> db -> join('weekly_visits', 'outlets.id = weekly_visits.outlet_id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        if ($model_id != -1) {
            $this->db->where('weekly_models.model_id', $model_id);
        }
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_models.model_id');
        $this->db->group_by('weekly_visits.date');
        $this->db->order_by('weekly_visits.date', 'ASC');
        $this->db->order_by('models.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_count_dispo_model($from, $to, $model_id) {

        $this->db->select('COUNT(' . $this->dbprefix . 'weekly_models.id) as nb', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id = outlets.id');
        $this->db->where('outlets.antenna', 1);
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        $this->db->where('weekly_models.model_id ', $model_id);
        $this->db->where('weekly_models.shelf >', 0);

        $result = $this->db->get()->row();
        return $result;
    }

    public function count_branding($from, $to, $admin_id) {

        $this->db->select('COUNT(' . $this->dbprefix . 'pictures.id) as nb', false);
        $this->db->from('' . $this->dbprefix . 'pictures');

        $this->db->where('' . $this->dbprefix . 'pictures.date >=', $from);
        $this->db->where('' . $this->dbprefix . 'pictures.date <=', $to);
        $this->db->where('' . $this->dbprefix . 'pictures.type', 'brand');
        $this->db->where('' . $this->dbprefix . 'pictures.admin_id ', $admin_id);

        $where = "('.$this->dbprefix.'pictures.before_images != '' or '.$this->dbprefix.'pictures.after_images != '')  and ('.$this->dbprefix.'pictures.before_images != 'false' or '.$this->dbprefix.'pictures.after_images != 'false' ) ";
        $this->db->where($where);




        $result = $this->db->get()->row();
        return $result;
    }

    public function count_all_display_by_date($from, $to) {

        // $this -> db -> select('COUNT('.$this->dbprefix.'pictures.id) as nb', false);
        // $this -> db -> from(''.$this->dbprefix.'pictures');

        $this->db->where('' . $this->dbprefix . 'pictures.date >=', $from);
        $this->db->where('' . $this->dbprefix . 'pictures.date <=', $to);
        $this->db->where('' . $this->dbprefix . 'pictures.type', 'display');


        $where = "('.$this->dbprefix.'pictures.before_images != '' or '.$this->dbprefix.'pictures.after_images != '')  and ('.$this->dbprefix.'pictures.before_images != 'false' or '.$this->dbprefix.'pictures.after_images != 'false' ) ";
        $this->db->where($where);




        return $this->db->count_all_results('pictures');
    }

    public function count_all_display() {

        // $this -> db -> select('COUNT('.$this->dbprefix.'pictures.id) as nb', false);
        // $this -> db -> from(''.$this->dbprefix.'pictures');
        // $this -> db -> where(''.$this->dbprefix.'pictures.date >=', $from);
        // $this -> db -> where(''.$this->dbprefix.'pictures.date <=', $to);
        $this->db->where('' . $this->dbprefix . 'pictures.type', 'display');


        $where = "('.$this->dbprefix.'pictures.before_images != '' or '.$this->dbprefix.'pictures.after_images != '')  and ('.$this->dbprefix.'pictures.before_images != 'false' or '.$this->dbprefix.'pictures.after_images != 'false' ) ";
        $this->db->where($where);




        return $this->db->count_all_results('pictures');
    }

    public function count_all_branding_by_date($from, $to) {

        // $this -> db -> select('COUNT('.$this->dbprefix.'pictures.id) as nb', false);
        // $this -> db -> from(''.$this->dbprefix.'pictures');

        $this->db->where('' . $this->dbprefix . 'pictures.date >=', $from);
        $this->db->where('' . $this->dbprefix . 'pictures.date <=', $to);
        $this->db->where('' . $this->dbprefix . 'pictures.type', 'brand');


        $where = "('.$this->dbprefix.'pictures.before_images != '' or '.$this->dbprefix.'pictures.after_images != '')  and ('.$this->dbprefix.'pictures.before_images != 'false' or '.$this->dbprefix.'pictures.after_images != 'false' ) ";
        $this->db->where($where);




        return $this->db->count_all_results('pictures');
    }

    public function count_all_branding_by_date_admin($from, $to, $admin_id) {

        // $this -> db -> select('COUNT('.$this->dbprefix.'pictures.id) as nb', false);
        // $this -> db -> from(''.$this->dbprefix.'pictures');

        $this->db->where('' . $this->dbprefix . 'pictures.date >=', $from);
        $this->db->where('' . $this->dbprefix . 'pictures.date <=', $to);
        $this->db->where('' . $this->dbprefix . 'pictures.type', 'brand');
        $this->db->where('' . $this->dbprefix . 'pictures.admin_id', $admin_id);


        $where = "('.$this->dbprefix.'pictures.before_images != '' or '.$this->dbprefix.'pictures.after_images != '')  and ('.$this->dbprefix.'pictures.before_images != 'false' or '.$this->dbprefix.'pictures.after_images != 'false' ) ";
        $this->db->where($where);




        return $this->db->count_all_results('pictures');
    }

    public function count_all_branding() {

        // $this -> db -> select('COUNT('.$this->dbprefix.'pictures.id) as nb', false);
        // $this -> db -> from(''.$this->dbprefix.'pictures');
        // $this -> db -> where(''.$this->dbprefix.'pictures.date >=', $from);
        // $this -> db -> where(''.$this->dbprefix.'pictures.date <=', $to);
        $this->db->where('' . $this->dbprefix . 'pictures.type', 'brand');


        $where = "('.$this->dbprefix.'pictures.before_images != '' or '.$this->dbprefix.'pictures.after_images != '')  and ('.$this->dbprefix.'pictures.before_images != 'false' or '.$this->dbprefix.'pictures.after_images != 'false' ) ";
        $this->db->where($where);




        return $this->db->count_all_results('pictures');
    }

    function get_fo_list() {
        $this->db->select('*');
        $this->db->where('access', 'Field Officer');
        $this->db->where('active', 1);
        $result = $this->db->get('admin');
        $result = $result->result();

        return $result;
    }

    public function count_display($from, $to, $admin_id) {

        $this->db->select('COUNT(' . $this->dbprefix . 'pictures.id) as nb', false);
        $this->db->from('' . $this->dbprefix . 'pictures');

        $this->db->where('' . $this->dbprefix . 'pictures.date >=', $from);
        $this->db->where('' . $this->dbprefix . 'pictures.date <=', $to);
        $this->db->where('' . $this->dbprefix . 'pictures.type', 'display');
        $this->db->where('' . $this->dbprefix . 'pictures.admin_id ', $admin_id);

        $where = "('.$this->dbprefix.'pictures.before_images != '' or '.$this->dbprefix.'pictures.after_images != '')  and ('.$this->dbprefix.'pictures.before_images != 'false' or '.$this->dbprefix.'pictures.after_images != 'false' ) ";
        $this->db->where($where);




        $result = $this->db->get()->row();
        return $result;
    }

    //Shelf Share reports - Outlet / Brand
    public function get_weekly_ss_outlet_data($from, $to) {

        $this->db->select('weekly_visits.date as date, brands.name as brand, outlets.id as outlet_id, SUM(' . $this->dbprefix . 'weekly_models.shelf) as shelf', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id = outlets.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_models.brand_id');
        $this->db->group_by('weekly_visits.outlet_id');
        $this->db->order_by('brands.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_weekly_ms_outlet_data($from, $to) {

        $this->db->select('weekly_visits.date as date, brands.name as brand, outlets.id as outlet_id, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id = outlets.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_models.brand_id');
        $this->db->group_by('weekly_visits.outlet_id');
        $this->db->order_by('brands.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_compare_chart_data_all($from, $to) {

        $this->db->select('weekly_visits.date as date, brands.id as brand_id, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws, SUM(' . $this->dbprefix . 'weekly_models.shelf) as shelf, SUM(' . $this->dbprefix . 'weekly_models.price) as price, SUM(' . $this->dbprefix . 'weekly_models.amount) as amount', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_models.brand_id');
        $this->db->group_by('weekly_visits.date');
        $this->db->order_by('weekly_visits.date', 'ASC');
        $this->db->order_by('brands.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_compare_chart_data_range($from, $to, $range) {

        $this->db->select('weekly_visits.date as date, brands.id as brand_id, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws, SUM(' . $this->dbprefix . 'weekly_models.shelf) as shelf, SUM(' . $this->dbprefix . 'weekly_models.price) as price, SUM(' . $this->dbprefix . 'weekly_models.amount) as amount', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->where('models.range2', $range);
        //	$this -> db -> where('models.price_range_id ', $range);
        $this->db->group_by('weekly_models.brand_id');
        $this->db->group_by('weekly_visits.date');
        $this->db->order_by('weekly_visits.date', 'ASC');
        $this->db->order_by('brands.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_compare_chart_data_price_range_2016($from, $to, $range) {

        $this->db->select('weekly_visits.date as date, brands.id as brand_id, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws, SUM(' . $this->dbprefix . 'weekly_models.shelf) as shelf, SUM(' . $this->dbprefix . 'weekly_models.price) as price, SUM(' . $this->dbprefix . 'weekly_models.amount) as amount', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        //$this -> db -> where('weekly_visits.active', 1);
        //$this -> db -> where('models.range2', $range);
        $this->db->where('models.price_range_id ', $range);
        $this->db->group_by('weekly_models.brand_id');
        $this->db->group_by('weekly_visits.date');
        $this->db->order_by('weekly_visits.date', 'ASC');
        $this->db->order_by('brands.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_price_portion_data_all($from, $to) {

        $this->db->select('weekly_visits.date as date, price_ranges.name as price_range, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('price_ranges', 'weekly_models.price_range_id = price_ranges.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_models.price_range_id');
        $this->db->group_by('weekly_visits.date');
        $this->db->order_by('weekly_visits.date', 'ASC');
        $this->db->order_by('price_ranges.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_price_portion_data_brand($from, $to, $price_range_id) {

        $this->db->select('weekly_visits.date as date, brands.name as brand, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        $this->db->where('weekly_models.price_range_id ', $price_range_id);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_models.brand_id');
        $this->db->group_by('weekly_visits.date');
        $this->db->order_by('weekly_visits.date', 'ASC');
        $this->db->order_by('brands.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_top_sales_model($date, $price_range_id) {

        $this->db->select('models.name as model, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('weekly_visits.date', $date);
        $this->db->where('weekly_models.price_range_id ', $price_range_id);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->limit(20, 0);
        $this->db->group_by('weekly_models.model_id');
        $this->db->order_by('ws', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_date_range($from, $to) {
        $this->db->select('weekly_visits.date as date', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->order_by('weekly_visits.date', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_segment_portion_data($date) {

        $this->db->select('brands.name as brand, models.range2 as rang, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->join('brands', 'weekly_models.brand_id= brands.id');
        $this->db->where('weekly_visits.date ', $date);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('models.range2');
        $this->db->group_by('weekly_models.brand_id');
        $this->db->order_by('brands.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_shortage_data($date) {
        $this->db->select('weekly_visits.outlet_id as outlet_id, weekly_models.shortage as shortage, weekly_models.model_id as model_id', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id = outlets.id');
        $this->db->where('weekly_visits.date ', $date);
        $this->db->where('weekly_models.brand_id ', 1);
        $this->db->where('models.shortage ', 1);

        //$this -> db -> where('shortage_visits.active', 1);
        $this->db->group_by('weekly_visits.outlet_id');
        $this->db->group_by('weekly_models.model_id');
        $this->db->order_by('weekly_models.model_id', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_shortage_antenna_data($date, $antenna) {

        $this->db->select('weekly_visits.outlet_id as outlet_id, weekly_models.shortage as shortage, weekly_models.model_id as model_id', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id = outlets.id');
        $this->db->where('weekly_visits.date ', $date);
        $this->db->where('weekly_models.brand_id ', 1);
        $this->db->where('models.shortage ', 1);
        if ($antenna != -1) {
            $this->db->where('outlets.antenna  ', 1);
        } else {
            $this->db->where('outlets.antenna  ', 0);
        }


        //$this -> db -> where('shortage_visits.active', 1);
        $this->db->group_by('weekly_visits.outlet_id');
        $this->db->group_by('weekly_models.model_id');
        $this->db->order_by('weekly_models.model_id', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_shortage_report_data($visit_id) {

        $this->db->select('weekly_visits.outlet_id as outlet_id, shortage_models.shortage as shortage, shortage_models.model_id as model_id', false);
        $this->db->from('shortage_models');
        $this->db->join('weekly_visits', 'shortage_models.visit_id = weekly_visits.id');
        $this->db->where('weekly_visits.id ', $visit_id);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_visits.outlet_id');
        $this->db->group_by('shortage_models.model_id');
        $this->db->order_by('weekly_visits.outlet_id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_data_collection_anc($date) {

        $this->db->select('weekly_visits.outlet_id as outlet_id, weekly_models.model_id as model_id, SUM(' . $this->dbprefix . 'weekly_models.shelf) as shelf, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws, SUM(' . $this->dbprefix . 'weekly_models.price) as price', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('weekly_visits.date ', $date);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_visits.outlet_id');
        $this->db->group_by('weekly_models.model_id');
        $this->db->order_by('weekly_visits.outlet_id', 'ASC');
        $this->db->order_by('brands.code', 'ASC');
        $this->db->order_by('models.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_data_collection_per_outlet($start_date) {

        $this->db->select('weekly_models.model_id as model_id,weekly_visits.outlet_id as outlet_id, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id = outlets.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('weekly_visits.date', $start_date);


        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_models.model_id');
        $this->db->group_by('weekly_visits.outlet_id');
        $this->db->order_by('outlets.name', 'ASC');
        $this->db->order_by('models.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_data_collection_sis($date, $to, $outlet_id, $admin_id) {

        $this->db->select('brands.name as brand_name,models.name as model_name,
		zones.name as zone_name,outlets.name as outlet_name, admin.firstname as first,admin.lastname as last,
		SUM(' . $this->dbprefix . 'weekly_models.shelf) as shelf, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws, SUM(' . $this->dbprefix . 'weekly_models.price) as price,weekly_models.model_id as model_id', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('admin', 'weekly_visits.admin_id = admin.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone_id = zones.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('weekly_visits.date >=', $date);
        $this->db->where('weekly_visits.date <= ', $to);
        $this->db->where('outlets.sis', 1);
        if ($outlet_id != -1) {
            $this->db->where('weekly_visits.outlet_id ', $outlet_id);
        }


        if ($admin_id != -1) {
            $this->db->where('weekly_visits.admin_id ', $admin_id);
        }
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_visits.outlet_id');
        $this->db->group_by('weekly_models.model_id');
        $this->db->order_by('weekly_visits.outlet_id', 'ASC');
        $this->db->order_by('brands.code', 'ASC');
        $this->db->order_by('models.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_data_collection_outlet_shelf($from, $to, $outlet_id) {

        $this->db->select('weekly_visits.date as date, weekly_models.model_id as model_id, SUM(' . $this->dbprefix . 'weekly_models.shelf) as shelf', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id = outlets.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        $this->db->where('outlets.id ', $outlet_id);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_visits.date');
        $this->db->group_by('weekly_models.model_id');
        $this->db->order_by('weekly_visits.date', 'ASC');
        $this->db->order_by('brands.code', 'ASC');
        $this->db->order_by('models.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_data_collection_outlet_ws($from, $to, $outlet_id) {

        $this->db->select('weekly_visits.date as date, weekly_models.model_id as model_id, SUM(' . $this->dbprefix . 'weekly_models.ws) as ws', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_models.visit_id = weekly_visits.id');
        $this->db->join('outlets', 'weekly_visits.outlet_id = outlets.id');
        $this->db->join('brands', 'weekly_models.brand_id = brands.id');
        $this->db->join('models', 'weekly_models.model_id = models.id');
        $this->db->where('weekly_visits.date >=', $from);
        $this->db->where('weekly_visits.date <=', $to);
        $this->db->where('outlets.id ', $outlet_id);
        //$this -> db -> where('weekly_visits.active', 1);
        $this->db->group_by('weekly_visits.date');
        $this->db->group_by('weekly_models.model_id');
        $this->db->order_by('weekly_visits.date', 'ASC');
        $this->db->order_by('brands.code', 'ASC');
        $this->db->order_by('models.code', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_stock_issues_data2($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }
        $this->db->select('visits.' . $date . ' as date,products.name as product_name,
            products.id as product_id,outlets.zone as zone,
                (sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)/count(bcc_models.id)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)/count(bcc_models.id)) as oos,
                 (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)/count(bcc_models.id)) as ha,             

		brands.name as brand_name,sum(bcc_models.av) as sum_av_sku,count(bcc_models.id) as total', false);
        $this->db->from('visits');

        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        if ($zone_id != -1) {
            if (is_array($zone_id)) {
                $this->db->where_in('zones.id  ', $zone_id);
            } else {
                $this->db->where('zones.id  ', $zone_id);
            }
        }


        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');

        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active ', 1);

        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }
        $this->db->where('visits.' . $date . ' >=', $start_date);
        $this->db->where('visits.' . $date . ' <= ', $end_date);


        $this->db->where('models.brand_id !=', 8); //
        $this->db->where('brands.active', 1);

        if ($cluster_id != '-1') {
            $this->db->where('products.cluster_id ', $cluster_id);
        }
        $this->db->group_by('outlets.zone');
        if ($category_id == '-1') {
            $this->db->group_by('models.brand_id');
        } else {
            $this->db->group_by('models.product_id');
        }
        $this->db->order_by('models.brand_id', 'desc');
        return $this->db->get()->result_array();
    }

    public function get_stock_issues_data3($date_type, $start_date, $end_date, $selected_zones, $cluster_id, $category_id, $activity, $super_market_project) {
        if ($date_type == 'month') {
            $date = 'm_date';
        } else {
            $date = 'w_date';
        }


        $this->db->select('
                outlets.zone as zone,
		brands.name as brand_name,
		(sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)/count(bcc_models.id)) as av,
                (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)/count(bcc_models.id)) as oos,
                 (sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END)/count(bcc_models.id)) as ha,
                sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)  AS count_av,
                sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)  AS count_oos,
                ', false);
        $this->db->from('models');
        $this->db->join('visits', ' visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('zones', 'outlets.zone = zones.name');
        $this->db->join('brands', 'brands.id = models.brand_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = models.product_group_id');
        $this->db->where('product_groups.active ', 1);
// $this->db->where('models.av !=', 2);
        if ($selected_zones != -1) {
            if (is_array($selected_zones)) {
                $this->db->where_in('zones.id  ', $selected_zones);
            } else {
                $this->db->where('zones.id ', $selected_zones);
            }
        }


        if ($category_id != '-1') {
            $this->db->where('models.category_id', $category_id);
        }

        if ($start_date != '' && $end_date != '') {
            $this->db->where('visits.' . $date . ' >=', $start_date);
            $this->db->where('visits.' . $date . ' <= ', $end_date);
        }

        $this->db->where('models.brand_id !=', 8); //


        if ($cluster_id != '-1') {
            $this->db->where('products.cluster_id ', $cluster_id);
        }

        $this->db->group_by('outlets.zone');
        $this->db->group_by('brands.name');

        $this->db->order_by('brands.id');

        return $this->db->get()->result_array();
    }

    function save_fo_information($save) {
//        if ($save['id']) {
//            $this->db->where('id', $save['id']);
//            $this->db->update('fo_informations', $save);
//            return $save['id'];
//        } else {
        $this->db->insert('fo_informations', $save);
        return $this->db->insert_id();
        //}
    }

    function get_av_j($product_name, $out, $date, $nb_j) {
        $this->db->select('models.av as av', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', ' outlets.id = visits.outlet_id');
        $this->db->join('products', 'models.product_id=products.id');
        $where = "visits.date = DATE_SUB( $date, INTERVAL  $nb_j DAY )";
        $this->db->where($where);
        $this->db->where('products.id', $product_name);
        $this->db->where('outlets.id', $out);
        $this->db->where('visits.monthly_visit', 0);
        $query = $this->db->get();
        return $query->num_rows();
        //return $ret->av;
    }

    function save_oos_tracking($save) {

        $this->db->select('product_id,outlet_id');
        $this->db->from('bcc_oos_tracking');
        $this->db->where('product_id', $save['product_id']);
        $this->db->where('outlet_id', $save['outlet_id']);
        //$this->db->where('date <=', $save['date']);

        $result = $this->db->get();
//        echo $result->num_rows();
//        print_r($result->result());
//        echo $save['av'];
//        echo '<br>';
        //si le couple produit_outlet  existe deja dans la table 
        if (($result->num_rows() > 0) && ($save['av'] == 1 || $save['av'] == 2 )) {
            //si le produit dans l outlet est av de nouveau : delete
            $this->db->where('product_id', $save['product_id']);
            $this->db->where('outlet_id', $save['outlet_id']);
            $this->db->delete('bcc_oos_tracking');
            echo 'deleted ==>'.print_r($save);
        } else if (($result->num_rows() > 0) && ($save['av'] == 0 )) {
            //$this->db->set('cron_date', $save['date'], FALSE);
            $this->db->set('nb_oos', 'nb_oos+1', FALSE);
            $this->db->where('product_id', $save['product_id']);
            $this->db->where('outlet_id', $save['outlet_id']);
            $this->db->update('bcc_oos_tracking');
            
            echo 'added ==>'.print_r($save);
        } else if (($result->num_rows() == 0) && ($save['av'] == 0)) {
           // $save['cron_date']=date('Y-m-d');
            $save['cron_date']=$save['date'];
            $this->db->insert('bcc_oos_tracking', $save);
            print_r($save); echo '<br>';
            //$id = $this->db->insert_id();
        }
        
    }

    public function get_oos_tracking() {
        $query = $this->db->query("SELECT bcc_oos_tracking . *,bcc_outlets.name as outlet_name,bcc_products.name as product_name
                                    FROM  `bcc_oos_tracking` 
                                    JOIN bcc_outlets ON bcc_oos_tracking.outlet_id = bcc_outlets.id
                                    JOIN bcc_products ON bcc_oos_tracking.product_id = bcc_products.id
                                    WHERE bcc_outlets.active =1
                                    AND bcc_oos_tracking.date < ( NOW( ) - INTERVAL 4 DAY ) 
                                    ORDER BY  `bcc_oos_tracking`.`date` ,
                                    bcc_oos_tracking.outlet_id ASC
                                    ");
        return $query->result_array();
    }

    function count_oos_tracking() {

        $query = $this->db->query("SELECT bcc_oos_tracking . id
                                    FROM  `bcc_oos_tracking` 
                                    JOIN bcc_outlets ON bcc_oos_tracking.outlet_id = bcc_outlets.id
                                    JOIN bcc_products ON bcc_oos_tracking.product_id = bcc_products.id
                                    WHERE bcc_outlets.active =1
                                    AND bcc_oos_tracking.date < ( NOW( ) - INTERVAL 4 DAY )");
        return $query->num_rows();
    }

    public function get_events() {
        return $this->db->group_by('date_de_conge')->get("fo_informations")->result();
    }

    public function get_events_details() {
        $this->db->select('*');
        $this->db->from('fo_informations');

        return $this->db->get()->result_array();
    }

    public function get_events_details_by_date($date) {
        $this->db->select('*, 
                          CAST(bcc_fo_informations.created as DATE) as created_date,
                          CAST(bcc_fo_informations.created as TIME) as created_time', false);
        $this->db->from('fo_informations');
        $this->db->where("date_de_conge", $date);
        return $this->db->get()->result_array();
    }

    public function add_event($data) {
        $this->db->insert("fo_informations", $data);
    }

    public function get_event($id) {
        return $this->db->where("id", $id)->get("fo_informations");
    }

//    public function update_event($id, $data) {
//        $this->db->where("id", $id)->update("fo_informations", $data);
//    }
    function update_event($routing) {
        if ($routing['id']) {
            $this->db->where('id', $routing['id']);
            $this->db->update('fo_informations', $routing);
            return $routing['id'];
        }
    }

    public function delete_event($id) {
        $this->db->where("id", $id)->delete("fo_informations");
    }

    function update_visit_picture($visit) {
        if ($visit['id']) {
            $this->db->where('id', $visit['id']);
            $this->db->update('visits', $visit);
            //return $visit['id'];
        }
    }

}
