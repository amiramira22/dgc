<?php
//bcm
Class Product_group_model extends CI_Model {

    //this is the expiration for a non-remember session

    function __construct() {
        parent::__construct();
    }

    /*     * ******************************************************************

     * ****************************************************************** */
 function get_active_henkel_product_groups($sub_category_id = 0, $limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {

        $this->db->select('product_groups.*,'
                . 'clusters.name as cluster_name,'
                . 'sub_categories.name as sub_category_name,'
                . 'categories.name as category_name');

        $this->db->from('product_groups');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id', 'left');
        $this->db->join('sub_categories', 'sub_categories.id=clusters.sub_category_id', 'left');
        $this->db->join('categories', 'categories.id = sub_categories.category_id ', 'left');
        $this->db->join('products', 'product_groups.id = products.product_group_id');
        $this->db->join('brands', 'brands.id = product_groups.brand_id');
        $this->db->where('brands.id', 1);

        $this->db->where('product_groups.active', 1);
        if ($sub_category_id != 0) {
            $this->db->where('sub_categories.id', $sub_category_id);
        }
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get();
        return $result->result();
    }

    
    function get_product_groups($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
        $this->db->select(
               'product_groups.*,
                brands.name as brand_name,
                clusters.name as cluster_name,
                sub_categories.name as sub_category_name,
                categories.name as category_name', false);
        
        $this->db->from('product_groups');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id','left');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id','left');
        $this->db->join('categories', 'categories.id = sub_categories.category_id','left');
        $this->db->join('brands', 'brands.id = product_groups.brand_id','left');
        
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get();
        return $result->result();
    }

    function count_product_groups() {
        return $this->db->count_all_results('product_groups');
    }

    function get_product_group($id) {

        $result = $this->db->get_where('product_groups', array('id' => $id));
        return $result->row();
    }

    function save($product_group) {
        if ($product_group['id']) {
            $this->db->where('id', $product_group['id']);
            $this->db->update('product_groups', $product_group);
            return $product_group['id'];
        } else {
            $this->db->insert('product_groups', $product_group);
            return $this->db->insert_id();
        }
    }

    function delete($id) {
        /*
          deleting a product_group will remove all their orders from the system
          this will alter any report numbers that reflect total sales
          deleting a customer is not recommended, deactivation is preferred
         */

        //this deletes the product_groups record
        $this->db->where('id', $id);
        $this->db->delete('product_groups');
    }

    public function get_product_group_name($product_group_id) {

        return $this->db->get_where('product_groups', array('id' => $product_group_id))->row()->name;
    }

}
