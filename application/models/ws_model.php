<?php

// BCM Project
Class Ws_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function save_visit_picture($picture) {
        $this->db->insert('visit_pictures', $picture);
        return $this->db->insert_id();
    }

    // List of products
    function get_ws_products() {
        $this->db->select(' products.id,'
                . 'products.name, '
                . 'products.nb_sku,'
                . 'products.image,'
                . 'products.active,'
                . 'products.code_gemo,'
                . 'products.code_mg, '
                . 'products.code_uhd,'
                . 'product_groups.id as product_group_id,'
                . 'clusters.id as cluster_id,'
                . 'sub_categories.id as sub_category_id ,'
                . 'categories.id as category_id,'
                . 'brands.id as brand_id ', false);

        $this->db->from('products');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id', 'left');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id', 'left');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id', 'left');
        $this->db->join('categories', 'categories.id = sub_categories.category_id', 'left');
        $this->db->join('brands', 'brands.id = product_groups.brand_id', 'left');

        $this->db->where('products.active', 1);
        $this->db->where('products.brand_id', 1);
        
         $this->db->order_by('products.code','ASC');
        $result = $this->db->get();
        return $result->result_array();
    }

    function get_product_groups() {
        $this->db->select(' product_groups.id,'
                . 'product_groups.name,'
                . 'product_groups.code,'
                . 'product_groups.active,'
                . 'product_groups.metrage,'
                . 'product_groups.shelf_unit,'
                . 'clusters.id as cluster_id,'
                . 'sub_categories.id as sub_category_id ,'
                . 'categories.id as category_id,'
                . 'brands.id as brand_id ', false);

        $this->db->from('product_groups');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id', 'left');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id', 'left');
        $this->db->join('categories', 'categories.id = sub_categories.category_id', 'left');
        $this->db->join('brands', 'brands.id = product_groups.brand_id', 'left');

        $result = $this->db->get();
        return $result->result_array();
    }

    // List of product_groups
    function get_ws_product_groups() {
        $this->db->select(' product_groups.id,'
                . 'product_groups.name,'
                . 'product_groups.code,'
                . 'product_groups.active,'
                . 'product_groups.metrage,'
                . 'product_groups.shelf_unit,'
                . 'clusters.id as cluster_id,'
                . 'sub_categories.id as sub_category_id ,'
                . 'categories.id as category_id,'
                . 'brands.id as brand_id ', false);


        $this->db->from('product_groups');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id', 'left');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id', 'left');
        $this->db->join('categories', 'categories.id = sub_categories.category_id', 'left');
        $this->db->join('brands', 'brands.id = product_groups.brand_id', 'left');
        
        $this->db->where('product_groups.active', 1);
         $this->db->order_by('product_groups.code','ASC');

        $result = $this->db->get();
        return $result->result_array();
    }

    //save outlet
    function save_outlet($outlet) {
        $this->db->insert('outlets', $outlet);
        return $this->db->insert_id();
    }

    //return yes if visit is already uploaded
    function is_visit_uploaded($visit_uniqueId, $monthly_visit) {
        $this->db->where('uniqueId', $visit_uniqueId);
        $this->db->where('monthly_visit', $monthly_visit);
        $result = $this->db->get('visits');
        return $result->num_rows();
    }

    //delete model
    function delete_model_unique_id($visit_uniqueId) {
        $this->db->where('visit_uniqueId', $visit_uniqueId);
        $this->db->delete('models');
    }

    //delete visit
    function delete_visit_unique_id($uniqueId) {
        $this->db->where('uniqueId', $uniqueId);
        $this->db->delete('visits');
    }

    //save visit
    function save_visit($visit) {

        $this->db->insert('visits', $visit);
        return $this->db->insert_id();
    }

    //save model
    function save_model($model) {

        $this->db->insert('models', $model);
        return $this->db->insert_id();
    }

    //save hors assortiment
    function save_ha($ha) {
        if (!$this->is_ha_uploaded($ha)) {
            $this->db->insert('ha', $ha);
            return $this->db->insert_id();
        }
    }

    //delete model
    function delete_ha($ha) {
        $this->db->where('product_id', $ha['product_id']);
        $this->db->where('outlet_id', $ha['outlet_id']);
        $this->db->delete('ha');
    }

    function login($email, $password) {
        $this->db->select();
        $this->db->from('admin');
        $this->db->where('email', $email);
        $this->db->where('password', sha1($password));
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function get_ws_rayons() {

        $result = $this->db->get('rayons');
        return $result->result_array();
    }

    function get_ws_ha() {

        $ha = $this->db->get('ha');
        return $ha->result_array();
    }

    function get_ws_one_pictures() {

        $result = $this->db->get('one_pictures');
        return $result->result_array();
    }

    // List of brands
    function get_ws_brands() {

        $result = $this->db->get('brands');
        return $result->result_array();
    }

    //get clients for vivo
    function get_ws_clients() {

        $result = $this->db->get('client');
        return $result->result_array();
    }

    // List of users
    function get_ws_users() {

        $result = $this->db->get('admin');
        return $result->result_array();
    }

    // List of outlets
    function get_ws_outlets() {

        $this->db->where('active', 1);
        $result = $this->db->get('outlets');
        return $result->result_array();
    }
    
     // List of outlets
    function get_ws_outlets_by_user($user_id) {

        $this->db->where('active', 1);
        $this->db->where('admin_id', $user_id);
        $result = $this->db->get('outlets');
        return $result->result_array();
    }

    function get_ws_categories() {

        $result = $this->db->get('categories');
        return $result->result_array();
    }

    function get_ws_channel() {

        $result = $this->db->get('channels');
        return $result->result_array();
    }

    function get_ws_sub_channel() {

        $result = $this->db->get('sub_channels');
        return $result->result_array();
    }

    function get_ws_sub_categories() {

        $result = $this->db->get('sub_categories');
        return $result->result_array();
    }

    function get_product_types() {

        $result = $this->db->get('product_types');
        return $result->result_array();
    }

    function get_clusters() {

        $result = $this->db->get('clusters');
        return $result->result_array();
    }

    function get_ws_zones() {

        $result = $this->db->get('zones');
        return $result->result_array();
    }

    function get_ws_states() {

        $result = $this->db->get('states');
        return $result->result_array();
    }

    // List of product_zones
    function get_ws_product_zones($zones) {
        $this->db->where_in('zone', $zones);
        $result = $this->db->get('product_zones');
        return $result->result_array();
    }

    // List of zones
    function get_ws_zones2($zones) {
        $this->db->where_in('name', $zones);
        $result = $this->db->get('zones');
        return $result->result_array();
    }

    // List of states
    function get_ws_states2($zones) {
        $this->db->where_in('zone', $zones);
        $result = $this->db->get('states');
        return $result->result_array();
    }

    // List of sectors
    function get_ws_sectors($zones) {
        $this->db->select('sectors.*', false);
        $this->db->from('sectors');
        $this->db->where_in('states.zone', $zones);
        $this->db->join('states', 'states.name = sectors.state');
        $result = $this->db->get();
        return $result->result_array();
    }

    //List of all visits
    function get_ws_visits() {
        $result = $this->db->get('visits');
        return $result->result_array();
    }

//List of models by visit_id
    function get_models_by_visit_id($visit_id) {

        $this->db->where('visit_id', $visit_id);
        $result = $this->db->get('models');
        return $result->result_array();
    }

    // List of cities
    function get_ws_cities($zones) {

        $this->db->select('cities.*', false);
        $this->db->from('cities');
        $this->db->where_in('states.zone', $zones);
        $this->db->join('sectors', 'sectors.name = cities.sector');
        $this->db->join('states', 'states.name = sectors.state');

        $result = $this->db->get();
        return $result->result_array();
    }

    //delete model
    function delete_models($id) {
        $this->db->where('visit_id', $id);
        $this->db->delete('models');
    }

    //delete visit
    function delete_visit($id) {
        $this->db->where('id', $id);
        $this->db->delete('visits');
    }

    //delete visit
    function delete_visitgroup($id) {
        $this->db->where('id', $id);
        $this->db->delete('visits_monthly');
    }

    //save visit
    function save_visitgroup($visit) {

        $this->db->insert('visits_monthly', $visit);
        return $this->db->insert_id();
    }

    function update_visit($visit) {
        if ($visit['id']) {

            $this->db->where('id', $visit['id']);
            $this->db->update('visits', $visit);
            return $visit['id'];
        }
    }

    function update_visit2($visit) {
        if ($visit['id']) {

            $this->db->trans_start();

            $this->db->where('id', $visit['id']);
            $this->db->update('visits', $visit);

            $this->db->trans_complete();



            return $visit['id'];
        }
    }

    function update_outlet($outlet) {
        if ($outlet['id']) {
            $this->db->where('id', $outlet['id']);
            $this->db->update('outlets', $outlet);
            return $outlet['id'];
        }
    }

    function save_rayon($rayon) {

        $this->db->insert('rayons', $rayon);
        return $this->db->insert_id();
    }

    function save_picture($picture) {

        $this->db->insert('one_pictures', $picture);
        return $this->db->insert_id();
    }

    function save_email($email) {
        $orig_db_debug = $this->db->db_debug;
        $this->db->db_debug = false;


        $this->db->trans_begin();
        $this->db->insert('email', $email);
        $this->db->db_debug = $orig_db_debug;
        return $this->db->insert_id();
    }

    function update_admin($id, $register_id) {

        $this->db->set('register_id', $register_id);
        $this->db->where('id', $id);
        return $this->db->update('admin');
    }

    function get_messages_by_receiver_id($id) {
        $this->db->select('messages.id as message_id,admin.name as sender_name,messages.message as message,messages.created as created', false);
        $this->db->join('admin', 'admin.id=messages.sender_id');

        $this->db->where('messages.receiver_id', $id);

        $this->db->limit(5);
        $this->db->order_by('messages.created','DESC');

        $this->db->from('messages');
        $query = $this->db->get()->result_array();

        return $query;
    }

    function get_outlet($id) {

        $result = $this->db->get_where('outlets', array('id' => $id));
        return $result->row();
    }

    function get_responsible_mail($responsible_id) {
        $result = $this->db->get_where('admin', array('id' => $responsible_id));
        return $result->row()->email;
    }

    public function get_product_name($product_id) {
        return $this->db->get_where('products', array('id' => $product_id))->row()->name;
    }

    function is_ha_uploaded($ha) {
        $this->db->where('product_id', $ha['product_id']);
        $this->db->where('outlet_id', $ha['outlet_id']);
        $result = $this->db->get('ha');
        return $result->num_rows();
    }

    function get_ws_ha_products($admin_id) {

        $this->db->select('ha.id as id,ha.product_id as product_id,ha.outlet_id as outlet_id', false);
        $this->db->join('outlets', 'outlets.id=ha.outlet_id');

        $this->db->where('outlets.admin_id', $admin_id);

        $this->db->order_by('ha.id');

        $this->db->from('ha');
        $query = $this->db->get()->result_array();

        return $query;
    }

    function update_visit_monthly($visit_id) {
        $this->db->set('monthly_visit', 1); //value that used to update column  
        $this->db->where('visit_id', $visit_id); //which row want to upgrade  
        $this->db->update('visits');

        return $query;
    }

    // List of av visits -- edited by boulbaba 12-03-2018
    function get_ws_av_visits() {
        $current_date = date('Y-m-d');
        $this->db->select('visits.*,outlets.name as outlet_name, outlets.photos as outlet_photo,'
                . ' outlets.longitude as outlet_longitude,outlets.latitude as outlet_latitude,admin.name as merch_name'
                . ', outlets.contact_pdv, outlets.contact as contact_tel', false);
        $this->db->from('visits');
        //$this->db->where_in('visits.monthly_visit', array('1','3'));
        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('admin', 'admin.id = visits.admin_id');
        $this->db->where('visits.date', $current_date);
        $result = $this->db->get();
        return $result->result_array();
    }

    // List of av models -- edited by boulbaba 28-03-2018
    function get_av_models($visit_id) {
        $this->db->select('products.name as product_name, categories.name as category_name, categories.id as category_id', false);
        $this->db->from('models');
        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('categories', 'categories.id = models.category_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->where('models.av', 0);
        $this->db->where('models.brand_id', 18);
        $this->db->where('visits.id', $visit_id);
        $this->db->order_by("categories.id", "asc");
        $result = $this->db->get();
        return $result->result_array();
    }

    function get_ha_products_by_outlet($outlet_id = false) {
        $this->db->select('product_id', false);
        $this->db->from('ha');
        $this->db->where('outlet_id', $outlet_id);
        $result = $this->db->get();
        $products = $result->result();
        $results = array();
        foreach ($products as $product) {
            $results[] = $product->product_id;
        }
        return $results;
    }

    // Team Leader Project


    function save_tl_visit($visit) {

        $this->db->insert('tl_visits', $visit);
        return $this->db->insert_id();
    }

    function save_tl_intervention($intervention) {

        $this->db->insert('tl_interventions', $intervention);
        return $this->db->insert_id();
    }

    function save_tl_action($action) {

        $this->db->insert('tl_actions', $action);
        return $this->db->insert_id();
    }

    function get_zone_id($zone_name) {
        $result = $this->db->get_where('zones', array('name' => $zone_name));
        return $result->row()->id;
    }

    function get_channel_id($channel_name) {
        $result = $this->db->get_where('channels', array('name' => $channel_name));
        return $result->row()->id;
    }

    function get_state_id($state_name) {
        $result = $this->db->get_where('states', array('name' => $state_name));
        return $result->row()->id;
    }

    function get_history_visits($user_id = false) {
        $current_date = date('Y-m-d');
        $this->db->select('visits.oos_perc,outlets.name as outlet_name, visits.date as date,'
                . ' visits.entry_time as entry_time, visits.exit_time as exit_time', false);
        $this->db->from('visits');
        $this->db->where('visits.monthly_visit', 0);
        $this->db->where('visits.admin_id', $user_id);
        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('admin', 'admin.id = visits.admin_id');
        $this->db->where('visits.date', $current_date);
        $result = $this->db->get();
        return $result->result_array();
    }

    function get_monthly_history_visits($user_id = false) {
        $current_date = date('Y-m-d');
        $this->db->select('visits.oos_perc as oos_perc,outlets.name as outlet_name, visits.date as date,'
                . ' visits.entry_time as entry_time', false);
        $this->db->from('visits');
        $this->db->where_in('visits.monthly_visit', array('1', '2', '3'));
        $this->db->where('visits.admin_id', $user_id);
        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('admin', 'admin.id = visits.admin_id');
        $this->db->where('visits.date', $current_date);
        $result = $this->db->get();
        return $result->result_array();
    }

    function count_visits($user_id = false, $monthly = false) {
        $current_date = date('Y-m-d');
        $this->db->where_in('visits.monthly_visit', $monthly);
        $this->db->where('visits.admin_id', $user_id);
         $this->db->where('visits.date', $current_date);
        return $this->db->count_all_results('visits');
    }

    function get_oos_tracking($outlet_id = false) {
        $channel=$this->db->get_where('outlets', array('id' => $outlet_id))->row()->channel;
        
        $current_date = date('Y-m-d');
        if($channel=='MG'){
        $this->db->select('products.name as product_name,products.code_mg as product_code, oos_tracking.date as date', false);
        }else  if($channel=='Gemo'){
         $this->db->select('products.name as product_name,products.code_gemo as product_code, oos_tracking.date as date', false);
        }else{
           $this->db->select('products.name as product_name,products.code_uhd as product_code, oos_tracking.date as date', false);
        }
        $this->db->from('oos_tracking');
        $this->db->join('outlets', 'outlets.id = oos_tracking.outlet_id');
        $this->db->join('products', 'products.id = oos_tracking.product_id');
        $this->db->where('outlets.id', $outlet_id);

        $result = $this->db->get();
        return $result->result_array();
    }
    
    function get_last_apk_url(){
        $file=$this->db->get_where('downloads', array('active' => 1))->row()->file;
        return base_url().'uploads/apk/'.$file;
    }

    function count_messages($user_id) {
        $this->db->where('messages.receiver_id', $user_id);
        $this->db->where('messages.viewed', 0);
        return $this->db->count_all_results('messages');
    }
    
    function update_messages($user_id){
        $this->db->set('viewed', 1);
        $this->db->where('receiver_id', $user_id);
        return $this->db->update('messages');

    }

}
