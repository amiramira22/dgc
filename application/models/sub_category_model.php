<?php
//bcs
Class Sub_category_model extends CI_Model {

    //this is the expiration for a non-remember session

    function __construct() {
        parent::__construct();
    }

    /*     * ******************************************************************

     * ****************************************************************** */
 function get_active_henkel_sub_categories($id = 0, $limit = 0, $offset = 0, $order_by = 'sub_categories.id', $direction = 'DESC') {
        $this->db->select('sub_categories.*');
        $this->db->from('sub_categories');
        $this->db->join('categories', 'sub_categories.category_id=categories.id');
        $this->db->join('clusters', 'sub_categories.id = clusters.sub_category_id');
        $this->db->join('product_groups', 'clusters.id = product_groups.cluster_id');
        $this->db->join('products', 'product_groups.id = products.product_group_id');
        $this->db->join('brands', 'brands.id = products.brand_id');
        $this->db->where('brands.id', 1);

        $this->db->where('sub_categories.active', 1);
        if ($id != 0) {
            $this->db->where('categories.id', $id);
        }
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get();
        return $result->result();
    }

    function get_sub_categories($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get('sub_categories');
        return $result->result();
    }

    function get_sub_categories_by_category($category_id) {

        $this->db->where('category_id', $category_id);

        $result = $this->db->get('sub_categories');
        return $result->result();
    }

    function count_sub_categories() {
        return $this->db->count_all_results('sub_categories');
    }

    function get_sub_category($id) {

        $result = $this->db->get_where('sub_categories', array('id' => $id));
        return $result->row();
    }

    function save($sub_category) {
        if ($sub_category['id']) {
            $this->db->where('id', $sub_category['id']);
            $this->db->update('sub_categories', $sub_category);
            return $sub_category['id'];
        } else {
            $this->db->insert('sub_categories', $sub_category);
            return $this->db->insert_id();
        }
    }

    function delete($id) {

        //this deletes the sub_categories record
        $this->db->where('id', $id);
        $this->db->delete('sub_categories');
    }

    public function get_sub_category_name($sub_category_id) {

        return $this->db->get_where('sub_categories', array('id' => $sub_category_id))->row()->name;
    }

}
