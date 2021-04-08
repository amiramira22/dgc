<?php

//bcm
Class Category_model extends CI_Model {

    //this is the expiration for a non-remember session

    function __construct() {
        parent::__construct();
    }

    /*     * ******************************************************************

     * ****************************************************************** */

    function get_active_henkel_categories($limit = 0, $offset = 0, $order_by = 'categories.id', $direction = 'DESC') {
        $this->db->select('categories.*');
        $this->db->from('categories');
       // $this->db->join('sub_categories', 'categories.id = sub_categories.category_id');
       // $this->db->join('clusters', 'sub_categories.id = clusters.sub_category_id');
       // $this->db->join('product_groups', 'clusters.id = product_groups.cluster_id');
     //   $this->db->join('brands', 'brands.id = product_groups.brand_id');

       /// $this->db->where('categories.active', 1);

       // $this->db->where('brands.id', 1);
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get();
        return $result->result();
    }

    function get_categories($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get('categories');
        return $result->result();
    }

    function get_active_categories() {
        $this->db->where('active', 1);

        $result = $this->db->get('categories');
        return $result->result();
    }

    function count_categories() {
        return $this->db->count_all_results('categories');
    }

    function get_category($id) {

        $result = $this->db->get_where('categories', array('id' => $id));
        return $result->row();
    }

    function save($category) {
        if ($category['id']) {
            $this->db->where('id', $category['id']);
            $this->db->update('categories', $category);
            return $category['id'];
        } else {
            $this->db->insert('categories', $category);
            return $this->db->insert_id();
        }
    }

    function delete($id) {
        /*
          deleting a category will remove all their orders from the system
          this will alter any report numbers that reflect total sales
          deleting a customer is not recommended, deactivation is preferred
         */

        //this deletes the categories record
        $this->db->where('id', $id);
        $this->db->delete('categories');
    }

    public function get_category_name($category_id) {

        return $this->db->get_where('categories', array('id' => $category_id))->row()->name;
    }

}
