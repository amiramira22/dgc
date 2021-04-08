<?php

//bcm
class Outlet_model extends CI_Model
{

    //this is the expiration for a non-remember session
    var $session_expire = 7200;

    function __construct()
    {
        parent::__construct();
    }

    public function get_outlet_numeric_distribution($start_date, $end_date, $channel_id, $category_id, $sub_category_id, $product_group_id, $product_id)
    {
        $this->db->select(
            'outlets.* ,
                sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END) as av,
                sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END) as oos,
                sum(CASE WHEN bcc_models.av = 2 THEN 1 ELSE 0 END) as ha', false);

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
        $this->db->join('brands', 'brands.id = products.brand_id');

//        if ($av_type == 1) {
//            $this->db->select('100 - ((sum(bcc_models.av_sku)/count(bcc_models.id))*100) as oos');
//        } elseif ($av_type == 2) {
//            $this->db->select('(sum(bcc_models.av_sku)/count(bcc_models.id))*100 as av');
//        }
        if ($category_id != 0) {
            $this->db->select('categories.id as category_id,');
            $this->db->where('categories.id', $category_id);
            //$this->db->group_by('categories.id');
        }
        if ($sub_category_id != 0) {
            $this->db->select('sub_categories.id as sub_category_id,');
            $this->db->where('sub_categories.id', $sub_category_id);
            //$this->db->group_by('sub_categories.id');
        }
        if ($product_group_id != 0) {
            $this->db->select('product_groups.id as product_group_id,');
            $this->db->where('product_groups.id', $product_group_id);
            //$this->db->group_by('product_groups.id');
        }
        if ($product_id != 0) {
            $this->db->select('products.id as product_id,');
            $this->db->where('products.id', $product_id);
            //$this->db->group_by('products.id');
        }
        if ($channel_id != '-1') {
            $this->db->where_in('channels.id', $channel_id);
        }
        $this->db->where('brands.id', 1);
        $this->db->where('channels.active', 1);
        $this->db->where('visits.date >=', $start_date);
        $this->db->where('visits.date <= ', $end_date);

        //$this->db->group_by('visits.date');
        $this->db->group_by('outlets.id');
        return $this->db->get()->result();
    }

    // Availibility grouped by Brands and Dates (Multi Dates) --- Boulbaba 26/01/2018
    public function get_outlet_per_satet_numeric_distribution($start_date, $end_date, $channel_id, $category_id, $sub_category_id, $product_group_id, $product_id)
    {
        $this->db->select(
            'visits.date as date,
		     outlets.* ,
             states.*,
             ((sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END))/count(bcc_models.id))*100 as av_old,
             ((sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END))/(sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)+sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)))*100 as av', false);

        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        //$this->db->join('states', 'outlets.state = states.name');
        $this->db->join('states', 'outlets.state_id = states.id');

        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('categories', 'categories.id = sub_categories.category_id');
        $this->db->join('brands', 'brands.id = products.brand_id');

        //$this->db->select('((sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END))/count(bcc_models.id))*100 as av');


        if ($category_id != 0) {
            $this->db->select('categories.id as category_id,');
            $this->db->where('categories.id', $category_id);
            //$this->db->group_by('categories.id');
        }

        if ($sub_category_id != 0) {
            $this->db->select('sub_categories.id as sub_category_id,');
            $this->db->where('sub_categories.id', $sub_category_id);
            //$this->db->group_by('sub_categories.id');
        }

        if ($product_group_id != 0) {
            $this->db->select('product_groups.id as product_group_id,');
            $this->db->where('product_groups.id', $product_group_id);
            //$this->db->group_by('product_groups.id');
        }

        if ($product_id != 0) {
            $this->db->select('products.id as product_id,');
            $this->db->where('products.id', $product_id);
            //$this->db->group_by('products.id');
        }

        if ($channel_id != '-1') {
            $this->db->where_in('channels.id', $channel_id);
        }
        $this->db->where('brands.id', 1);
        $this->db->where('channels.active', 1);
        $this->db->where('visits.date >=', $start_date);
        $this->db->where('visits.date <= ', $end_date);

        //$this->db->group_by('visits.date');
        $this->db->group_by('outlets.state_id');
        return $this->db->get()->result();
    }

    public function get_all_data($data, $type)
    {
        $this->db->select('products.id as product_id,products.name as product_name,'
            . 'product_groups.id as product_group_id, product_groups.name as product_group_name,'
            . 'clusters.id as cluster_id, clusters.name as cluster_name,'
            . 'sub_categories.id as sub_category_id, sub_categories.name as sub_category_name,'
            . ' categories.id as category_id, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id', 'LEFT');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id', 'LEFT');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id', 'LEFT');
        $this->db->join('categories', 'categories.id = sub_categories.category_id', 'LEFT');
        $this->db->join('brands', 'brands.id = products.brand_id');
        $this->db->where('brands.id', 1);

        if ($type == 'specific_category' && $data != 0) {
            $this->db->where('categories.id', $data);
        }
        if ($type == 'specific_sub_category' && $data != 0) {
            $this->db->where('sub_categories.id', $data);
        }
        if ($type == 'specific_cluster' && $data != 0) {
            $this->db->where('clusters.id', $data);
        }
        if ($type == 'specific_product_group' && $data != 0) {
            $this->db->where('product_groups.id', $data);
        }
        if ($type == 'specific_product' && $data != 0) {
            $this->db->where('products.id', $data);
        }

        //$this->db->order_by('sub_categories.id', 'ASC');
        $result = $this->db->get();
        return $result->result();
    }

    function get_number_outlet_by_channel($channel_id)
    {
        $this->db->select('count(bcc_outlets.id) as nb_active', false);
        $this->db->where('outlets.active', 1);
        if ($channel_id != -1) {
            $this->db->join('channels', 'channels.id = outlets.channel_id');
            $this->db->where('channels.id', $channel_id);
        }
        return $this->db->count_all_results('outlets');
    }

    function get_outlets_by_channel($channel_id, $limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC')
    {

        $this->db->select('outlets.*');
        $this->db->from('outlets');
        if ($channel_id != -1) {
            $this->db->join('channels', 'channels.id = outlets.channel_id');
            $this->db->where('channels.id', $channel_id);
        }
        $this->db->order_by('outlets.name', 'asc');

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->where('outlets.active', 1);
        $query = $this->db->get();
        return $query->result();
    }

    function count_active_outlets_by_admin($admin_id)
    {
        $this->db->where('admin_id', $admin_id);
        $this->db->where('active', 1);
        return $this->db->count_all_results('outlets');
    }

    function get_active_outlets_by_admin($admin_id)
    {
        $this->db->where('admin_id', $admin_id);
        $this->db->where('active', 1);

        $result = $this->db->get('outlets');
        return $result->result();
    }

    function get_outlet_by_state_classe_details()
    {
        $this->db->select('outlets.state as state_name,
                SUM( bcc_outlets.active)  AS nb_state,
		SUM(CASE WHEN bcc_outlets.channel = \'UHD\' THEN bcc_outlets.active ELSE 0 END)  AS uhd,
		SUM(CASE WHEN bcc_outlets.channel = \'Gemo\' THEN bcc_outlets.active ELSE 0 END)  AS gemo,
		SUM(CASE WHEN bcc_outlets.channel = \'MG\' THEN bcc_outlets.active ELSE 0 END)  AS mg,
                SUM(CASE WHEN bcc_outlets.channel = \'Traditional Trade\' THEN bcc_outlets.active ELSE 0 END)  AS tt,
		SUM(CASE WHEN bcc_outlets.channel = \'Uni Market\' THEN bcc_outlets.active ELSE 0 END)  AS um,
                SUM(CASE WHEN bcc_outlets.channel = \'Nawara Market\' THEN bcc_outlets.active ELSE 0 END)  AS nm
		', false);
        $this->db->where('outlets.active', 1);
        $this->db->group_by('outlets.state');

        return $this->db->get('outlets')->result();
    }

    function get_outlet_by_state_super_details()
    {
        $this->db->select('outlets.state as state_name,
	SUM( bcc_outlets.active)  AS nb_state,
		SUM(CASE WHEN bcc_outlets.super_market_project = 1 THEN bcc_outlets.active ELSE 0 END)  AS nb_super
		
		', false);
        $this->db->where('outlets.active', 1);
        $this->db->group_by('outlets.state');

        return $this->db->get('outlets')->result();
    }

    function get_number_outlet_by_classe($classe)
    {
        $this->db->select('
		count(bcc_outlets.id) as nb_active,
		', false);
        //	 $this -> db -> join('zones', 'zones.id=outlets.zone_id');
        $this->db->where('outlets.active', 1);
        if ($classe != '') {
            $this->db->where('outlets.channel', $classe);
        }
        return $this->db->count_all_results('outlets');
    }

    function count_outlets_search($search = '-1', $admin_id = '-1')
    {

        if ($admin_id != '-1') {
            $this->db->where('admin_id', $admin_id);
        }

        if ($search != '-1') {
            $search = strtoupper($search);
            $this->db->join('admin', 'admin.id=outlets.admin_id');
            $this->db->where("(
		 UPPER(bcc_outlets.name) LIKE '%" . $search . "%' 
         OR UPPER(bcc_outlets.zone) LIKE '%" . $search . "%' 
		 OR UPPER(bcc_outlets.state) LIKE '%" . $search . "%'
		 OR UPPER(bcc_admin.name) LIKE '%" . $search . "%' 
		)");
        }
        return $this->db->count_all_results('outlets');
    }

    function get_outlets_search($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC', $search, $admin_id, $henkel)
    {

        $this->db->select('outlets.*,states.name as state_name');
        $this->db->from('outlets');
        $this->db->join('states', 'states.id=outlets.state_id');

        $this->db->join('admin', 'admin.id=outlets.admin_id');

        $this->db->order_by('outlets.id', $direction);

        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        if ($search != '-1') {
            $search = strtoupper($search);
            $this->db->where("(
		 UPPER(bcc_outlets.name) LIKE '%" . $search . "%'
		 OR UPPER(bcc_outlets.zone) LIKE '%" . $search . "%' 
		 OR UPPER(bcc_outlets.state) LIKE '%" . $search . "%'
		 OR UPPER(bcc_admin.name) LIKE '%" . $search . "%'
		)");
        }

        if ($henkel == 1) {
            $this->db->where('outlets.active', 1);
        }

        if ($admin_id != '-1') {
            $this->db->where('admin_id', $admin_id);
        }

        $result = $this->db->get();
        return $result->result();
    }

    function get_outlets($limit = 0, $offset = 0, $order_by = 'id', $direction = 'ASC')
    {

        $this->db->select('outlets.* ,states.name as state_name');
        $this->db->from('outlets');
        $this->db->join('states', 'states.id=outlets.state_id');
        $this->db->order_by('id', 'desc');
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $result = $this->db->get();
        return $result->result();
    }

    function get_active_outlets($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC')
    {
        $this->db->order_by('name', $direction);
        $this->db->where('active', 1);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $result = $this->db->get('outlets');
        return $result->result();
    }

    function get_active_outlets_by_name()
    {
        $this->db->order_by('name', 'DESC');
        $this->db->where('active', 1);


        $result = $this->db->get('outlets');
        return $result->result();
    }

    function get_number_active_outlets_by_cities()
    {


        $this->db->select('
		count(bcc_outlets.id) as nb_active,
		outlets.state as state_name', false);
        //	 $this -> db -> join('zones', 'zones.id=outlets.zone_id');

        $this->db->order_by('name', 'ASC');
        $this->db->group_by('state');

        $this->db->where('outlets.active', 1);


        $result = $this->db->get('outlets');
        return $result->result();
    }

    function get_states()
    {


        $this->db->select('
		outlets.state as state_name', false);


        $this->db->order_by('name', 'ASC');
        $this->db->group_by('state');


        $result = $this->db->get('outlets');
        return $result->result();
    }

    function get_number_outlet_by_state_classe($state, $classe)
    {


        $this->db->select('
		count(bcc_outlets.id) as nb_active,
		', false);
        //	 $this -> db -> join('zones', 'zones.id=outlets.zone_id');


        $this->db->group_by('state');

        $this->db->where('outlets.active', 1);
        if ($classe != '') {
            $this->db->where('outlets.classe', $classe);
        }

        $this->db->where('outlets.state', $state);


        return $this->db->count_all_results('outlets');
    }

    function get_number_active_outlets_by_cities_ap()
    {


        $this->db->select('
		count(bcc_outlets.id) as nb_active,
		outlets.state as state_name', false);
        //	 $this -> db -> join('zones', 'zones.id=outlets.zone_id');

        $this->db->order_by('name', 'ASC');
        $this->db->group_by('state');

        $this->db->where('outlets.active', 1);
        $this->db->where('outlets.classe', 'A+');


        $result = $this->db->get('outlets');
        return $result->result();
    }

    function get_number_active_outlets_by_cities_a()
    {


        $this->db->select('
		count(bcc_outlets.id) as nb_active,
		outlets.state as state_name', false);
        //	 $this -> db -> join('zones', 'zones.id=outlets.zone_id');

        $this->db->order_by('name', 'ASC');
        $this->db->group_by('state');

        $this->db->where('outlets.active', 1);
        $this->db->where('outlets.classe', 'A');


        $result = $this->db->get('outlets');
        return $result->result();
    }

    function get_number_active_outlets_by_cities_b()
    {


        $this->db->select('
		count(bcc_outlets.id) as nb_active,
		outlets.state as state_name', false);
        //	 $this -> db -> join('zones', 'zones.id=outlets.zone_id');

        $this->db->order_by('name', 'ASC');
        $this->db->group_by('state');

        $this->db->where('outlets.active', 1);
        $this->db->where('outlets.classe', 'B');


        $result = $this->db->get('outlets');
        return $result->result();
    }

    function get_active_outlets_sis_by_name()
    {
        $this->db->order_by('name', 'DESC');
        $this->db->where('active', 1);
        $this->db->where('sis', 1);


        $result = $this->db->get('outlets');
        return $result->result();
    }

    function get_active_outlets_antenna_by_name()
    {
        $this->db->order_by('name', 'DESC');
        $this->db->where('active', 1);
        $this->db->where('antenna', 1);


        $result = $this->db->get('outlets');
        return $result->result();
    }

    function get_active_outlets_antenna($antenna)
    {
        $this->db->order_by('name', 'DESC');
        $this->db->where('active', 1);
        if ($antenna == 1) {
            $this->db->where('antenna', 1);
        } else {
            $this->db->where('antenna !=', 1);
            $this->db->where('sis', 1);
        }


        $result = $this->db->get('outlets');
        return $result->result();
    }

    public function get_outlet_id_by_name($outlet_name)
    {
        return $this->db->get_where('outlets', array('name' => $outlet_name))->row()->id;
    }

    function get_outlets_by_id($admin_id)
    {
        $this->db->where('admin_id', $admin_id);
        $this->db->order_by('name', 'ASC');
        $this->db->where('active', 1);
        $result = $this->db->get('outlets');
        return $result->result();
    }

    function count_outlets()
    {
        return $this->db->count_all_results('outlets');
    }

    function count_active_outlets()
    {
        $this->db->where('active', 1);
        return $this->db->count_all_results('outlets');
    }

    function count_outlet_by_date($date)
    {
        $this->db->where('visit_day', $date);
        $this->db->where('active', 1);
        return $this->db->count_all_results('outlets');
    }

    function get_outlet($id)
    {

        $result = $this->db->get_where('outlets', array('id' => $id));
        return $result->row();
    }

    function get_outlets_by_zone_area($zone_id)
    {
        $this->db->select('outlets.name,outlets.id');
        $this->db->from('outlets');
        $this->db->where('outlets.zone_id', $zone_id);
        $this->db->where('outlets.active', 1);
        //$this -> db -> order_by('outlets.id', 'DESC');
        return $this->db->get();
    }

    function get_outlets_by_zone($zone_id)
    {

        $this->db->select('outlets.*');
        $this->db->from('outlets');
        $this->db->where('outlets.zone_id', $zone_id);
        $this->db->where('outlets.active', 1);

        $query = $this->db->get();

        return $query->result();
    }

    function save($outlet)
    {
        if ($outlet['id']) {
            $this->db->where('id', $outlet['id']);
            $this->db->update('outlets', $outlet);
            return $outlet['id'];
        } else {
            $this->db->insert('outlets', $outlet);
            return $this->db->insert_id();
        }
    }

    function delete($id)
    {

        //this deletes the outlets record
//        $this->db->where('id', $id);
//        $this->db->delete('outlets');
        $this->db->delete('outlets', array('id' => $id));
    }

    public function get_outlet_name($outlet_id)
    {
        return $this->db->get_where('outlets', array('id' => $outlet_id))->row()->name;
    }

    public function get_outlet_image($outlet_id)
    {
        return $this->db->get_where('outlets', array('id' => $outlet_id))->row()->image;
    }

    public function get_outlet_type($outlet_id)
    {
        return $this->db->get_where('outlets', array('id' => $outlet_id))->row()->type;
    }

    public function get_outlet_city_id($outlet_id)
    {
        return $this->db->get_where('outlets', array('id' => $outlet_id))->row()->city_id;
    }

    public function get_outlet_state_id($outlet_id)
    {
        return $this->db->get_where('outlets', array('id' => $outlet_id))->row()->state;
    }

    public function get_outlet_admin_id($outlet_id)
    {
        return $this->db->get_where('outlets', array('id' => $outlet_id))->row()->admin_id;
    }

    public function get_outlet_zone_id($outlet_id)
    {
        return $this->db->get_where('outlets', array('id' => $outlet_id))->row()->zone_id;
    }

    public function get_outlet_adress($outlet_id)
    {
        return $this->db->get_where('outlets', array('id' => $outlet_id))->row()->adress;
    }

    public function get_outlet_class($outlet_id)
    {
        return $this->db->get_where('outlets', array('id' => $outlet_id))->row()->class;
    }

    public function get_visit_id($outlet_id)
    {
        return $this->db->get_where('outlets', array('id' => $outlet_id))->row()->visit_id;
    }

}
