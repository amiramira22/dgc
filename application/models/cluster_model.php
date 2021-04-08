<?php

Class Cluster_model extends CI_Model {

    //this is the expiration for a non-remember session
    var $session_expire = 7200;

    function __construct() {
        parent::__construct();
    }

    /*     * ******************************************************************

     * ****************************************************************** */
   function get_active_henkel_clusters($id = 0, $limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {

        $this->db->select('clusters.*,'
                . 'sub_categories.name as sub_category_name,'
                . 'categories.name as category_name');

        $this->db->from('clusters');
        $this->db->join('sub_categories', 'clusters.sub_category_id = sub_categories.id');
        $this->db->join('categories', 'sub_categories.category_id = categories.id');

        $this->db->join('product_groups', 'clusters.id = product_groups.cluster_id');
        $this->db->join('products', 'product_groups.id = products.product_group_id');
        $this->db->join('brands', 'brands.id = products.brand_id');
        $this->db->where('brands.id', 1);


        $this->db->where('clusters.active', 1);
        if ($id != 0) {
            $this->db->where('sub_categories.id', $id);
        }
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get();
        return $result->result();
    }

    function get_clusters($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get('clusters');
        return $result->result();
    }

    function get_clusters_by_category($category_id) {
        $this->db->select('clusters.id,clusters.name')->from('clusters');
        $this->db->join('sub_categories', 'clusters.sub_category_id = sub_categories.id');
        $this->db->where('sub_categories.category_id', $category_id);
        //$this->db->where('clusters.active', 1);

        $result = $this->db->get();
        return $result->result();
    }

    function get_clusters_by_code() {
        $this->db->order_by('code', 'ASC');
        $result = $this->db->get('clusters');
        return $result->result();
    }

    function get_clusters_without_others() {
        $this->db->order_by('code', 'ASC');
        $this->db->where('clusters.name!=', 'Others');
        $result = $this->db->get('clusters');
        return $result->result();
    }

    function get_clusters_by_category_without_others($category_id) {
        $this->db->select('clusters.id,clusters.name')->from('clusters');
        $this->db->join('sub_categories', 'clusters.sub_category_id = sub_categories.id');
        $this->db->where('sub_categories.category_id', $category_id);
        $this->db->where('clusters.other', 0);

        $result = $this->db->get();
        return $result->result();
    }

    function count_clusters() {
        return $this->db->count_all_results('clusters');
    }

    function get_cluster($id) {

        $result = $this->db->get_where('clusters', array('id' => $id));
        return $result->row();
    }

    function save($cluster) {
        if ($cluster['id']) {
            $this->db->where('id', $cluster['id']);
            $this->db->update('clusters', $cluster);
            return $cluster['id'];
        } else {
            $this->db->insert('clusters', $cluster);
            return $this->db->insert_id();
        }
    }

    function deactivate($id) {
        $cluster = array('id' => $id, 'active' => 0);
        $this->save_cluster($cluster);
    }

    function delete($id) {
        /*
          deleting a cluster will remove all their orders from the system
          this will alter any report numbers that reflect total sales
          deleting a customer is not recommended, deactivation is preferred
         */

        //this deletes the clusters record
        $this->db->where('id', $id);
        $this->db->delete('clusters');
    }

    public function get_cluster_name($cluster_id) {
        return $this->db->get_where('clusters', array('id' => $cluster_id))->row()->name;
    }

}
