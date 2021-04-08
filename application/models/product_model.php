<?php

//bcs
Class Product_model extends CI_Model {

    //this is the expiration for a non-remember session
    var $session_expire = 7200;

    function __construct() {
        parent::__construct();
    }

    /*     * ******************************************************************

     * ****************************************************************** */

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

    function get_products($limit = 0, $offset = 0, $order_by = 'id', $direction = 'DESC') {
        $this->db->select(
                'products.*,
                product_groups.name as product_group_name,
                brands.name as brand_name,
                clusters.name as cluster_name,
                sub_categories.name as sub_category_name,
                categories.name as category_name,
                ', false);
        $this->db->from('products');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id', 'left');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id', 'left');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id', 'left');
        $this->db->join('categories', 'categories.id = sub_categories.category_id', 'left');
        $this->db->join('brands', 'brands.id = product_groups.brand_id', 'left');
        $this->db->order_by($order_by, $direction);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        $result = $this->db->get();
        return $result->result();
    }

    function get_active_products() {
        $this->db->select(
                'products.*,
                product_groups.name as product_group_name,
                brands.name as brand_name,
                clusters.name as cluster_name,
                sub_categories.name as sub_category_name,
                categories.name as category_name,
                ', false);
        $this->db->from('products');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id', 'left');
        $this->db->join('clusters', 'clusters.id = product_groups.cluster_id', 'left');
        $this->db->join('sub_categories', 'sub_categories.id = clusters.sub_category_id', 'left');
        $this->db->join('categories', 'categories.id = sub_categories.category_id', 'left');
        $this->db->join('brands', 'brands.id = product_groups.brand_id', 'left');
        //$this->db->where('products.active', 1);
        $this->db->order_by('products.name', 'ASC');


        $result = $this->db->get();
        return $result->result();
    }

    function get_all_active_products($limit = 0, $offset = 0) {
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('products.active', 1);
        $this->db->order_by('products.name', 'ASC');
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        $result = $this->db->get();
        return $result->result();
    }

    function get_ha_products($outlet_id = false) {
        $this->db->select(
                'products.id as prod_id
                ', false);
        $this->db->from('products');
        $this->db->join('ha', 'products.id=ha.product_id ', 'left');
        $this->db->where('ha.outlet_id', $outlet_id);
        //$this->db->where('products.active', 1);
        $results = $this->db->get()->result();
        $data = array();
        foreach ($results as $row) {
            $data[] = $row->prod_id;
        }
        return $data;
    }

    function delete_ha_product($product_id, $outlet_id) {
        $this->db->where('product_id', $product_id);
        $this->db->where('outlet_id', $outlet_id);
        $this->db->delete('ha');
    }

    function add_ha_product($product) {

        $this->db->insert('ha', $product);
        return $this->db->insert_id();
    }

    function get_top5_oss_henkel() {
        $this->db->select('
		count(hcs_models.id) as total,
		products.name as product_name ,
		
		count(CASE WHEN hcs_models.av = 0 THEN hcs_models.av ELSE 0 END)  AS count_av
		
		', false);
        $this->db->join('models', 'models.product_id=products.id');
        $this->db->where('models.av', 0);
        $this->db->where('products.brand_id', 1);
        $this->db->limit(5);

        $this->db->group_by('products.name');
        $this->db->order_by('count_av');
        //$result = $this -> db -> get('products');
        $this->db->from('products');
        $query = $this->db->get()->result_array();


        foreach ($query as $row) {


            if ($row['total'] != 0) {
                $row['percentage'] = number_format(($row['count_av'] / $row['total']) * 100, 2, ',', ' ');
            } else {

                $row['percentage'] = '-';
            }
            $data[] = $row;
        }
        return json_encode(array_reverse($data));
    }

    function get_top5_av_henkel() {

        $this->db->select('
		count(hcs_models.id) as total,
		products.name as product_name ,
		
		count(CASE WHEN hcs_models.av = 1 THEN hcs_models.av ELSE 0 END)  AS count_av
		
		', false);
        $this->db->join('models', 'models.product_id=products.id');

        $this->db->where('products.brand_id', 1);
        $this->db->where('models.av', 1);
        $this->db->limit(5);

        $this->db->group_by('products.name');
        $this->db->order_by('count_av');
        //$result = $this -> db -> get('products');
        $this->db->from('products');
        $query = $this->db->get()->result_array();


        foreach ($query as $row) {


            if ($row['total'] != 0) {
                $row['percentage'] = number_format(($row['count_av'] / $row['total']) * 100, 2, ',', ' ');
            } else {

                $row['percentage'] = '-';
            }
            $data[] = $row;
        }
        return json_encode(array_reverse($data));
    }

    function get_top5_oss_competitor() {
        $this->db->select('
		count(hcs_models.id) as total,
		products.name as product_name ,
		
		count(CASE WHEN hcs_models.sku_display = 0 THEN hcs_models.av ELSE 0 END)  AS count_av
		
		', false);
        $this->db->join('models', 'models.product_id=products.id');
        $this->db->where('models.sku_display', 0);
        //$this -> db -> where('products.brand_id !=',1);
        $this->db->limit(5);

        $this->db->group_by('products.name');
        $this->db->order_by('count_av');
        //$result = $this -> db -> get('products');
        $this->db->from('products');
        $query = $this->db->get()->result_array();


        foreach ($query as $row) {


            if ($row['total'] != 0) {
                $row['percentage'] = number_format(($row['count_av'] / $row['total']) * 100, 2, ',', ' ');
            } else {

                $row['percentage'] = '-';
            }
            $data[] = $row;
        }
        return json_encode(array_reverse($data));
    }

    function get_top5_av_competitor() {

        $this->db->select('
		count(hcs_models.id) as total,
		products.name as product_name ,
		
		count(CASE WHEN hcs_models.sku_display != 0 THEN hcs_models.av ELSE 0 END)  AS count_av
		
		', false);
        $this->db->join('models', 'models.product_id=products.id');

        //$this -> db -> where('products.brand_id !=',1);
        $this->db->where('models.sku_display !=', 0);
        $this->db->limit(5);

        $this->db->group_by('products.name');
        $this->db->order_by('count_av');
        //$result = $this -> db -> get('products');
        $this->db->from('products');
        $query = $this->db->get()->result_array();


        foreach ($query as $row) {


            if ($row['total'] != 0) {
                $row['percentage'] = number_format(($row['count_av'] / $row['total']) * 100, 2, ',', ' ');
            } else {

                $row['percentage'] = '-';
            }
            $data[] = $row;
        }
        return json_encode(array_reverse($data));
    }

    function get_products_by_code() {
        $this->db->order_by('code', 'ASC');
        $result = $this->db->get('products');
        return $result->result();
    }

    function count_products() {
        return $this->db->count_all_results('products');
    }

    function count_acrive_products() {
        $this->db->where('active', 1);
        return $this->db->count_all_results('products');
    }

    function get_product($id) {

        $result = $this->db->get_where('products', array('id' => $id));
        return $result->row();
    }

    function save($product) {
        if ($product['id']) {
            $this->db->where('id', $product['id']);
            $this->db->update('products', $product);
            return $product['id'];
        } else {
            $this->db->insert('products', $product);
            return $this->db->insert_id();
        }
    }

    function deactivate($id) {
        $product = array('id' => $id, 'active' => 0);
        $this->save_product($product);
    }

    function delete($id) {
        /*
          deleting a product will remove all their orders from the system
          this will alter any report numbers that reflect total sales
          deleting a customer is not recommended, deactivation is preferred
         */

        //this deletes the products record
        $this->db->where('id', $id);
        $this->db->delete('products');
    }

    public function get_product_name($product_id) {
        return $this->db->get_where('products', array('id' => $product_id))->row()->name;
    }

}
