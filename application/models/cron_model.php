<?php

//bcm
Class Cron_model extends CI_Model {

    //this is the expiration for a non-remember session
    var $dbprefix;

    function __construct() {
        parent::__construct();
        $this->dbprefix = $this->db->dbprefix;
    }

    /*     * ******************************************************************

     * ****************************************************************** */

    public function get_oos($admin_id, $date, $channel_id) {

        $this->db->select('( (sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)) / count(bcc_models.id) )*100 as oos', false);
        $this->db->from('models');

        $this->db->join('visits', 'visits.id = models.visit_id');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id');
        $this->db->join('channels', 'channels.id = outlets.channel_id');
        $this->db->join('products', 'products.id = models.product_id');
        $this->db->join('product_groups', 'product_groups.id = products.product_group_id');
        ;
        $this->db->join('brands', 'brands.id = product_groups.brand_id');
        $this->db->join('admin', 'admin.id = visits.admin_id');

        //$this->db->where('products.active', 1);

        $this->db->where('channels.id', $channel_id);
        $this->db->where('admin.id', $admin_id);
        $this->db->where('visits.date', $date);
        $this->db->where('brands.id ', 1); //

        return $this->db->get()->row();
    }

    function save($model) {
        if ($model['id']) {
            $this->db->where('id', $model['id']);
            $this->db->update('fo_performance', $model);
            return $model['id'];
        } else {

            $this->db->insert('fo_performance', $model);
            return $this->db->insert_id();
        }
    }

    function get_visits_by_admin($current_admin_id, $date) {

        $this->db->select('*, CAST(bcc_visits.created as TIME) as system_exit_time', false);


        $this->db->where('visits.date', $date);
        $this->db->where('visits.admin_id', $current_admin_id);
        $this->db->where('visits.monthly_visit', 0);

        $this->db->order_by('visits.entry_time', 'ASC');

        $result = $this->db->get('visits');
        return $result->result();
    }

}
