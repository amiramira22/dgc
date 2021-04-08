<?php

Class Dashboard_model extends CI_Model {

    var $dbprefix;

    function __construct() {
        parent::__construct();
        $this->dbprefix = $this->db->dbprefix;
        $this->db->query("SET SESSION sql_mode = 'TRADITIONAL'");
    }

    //verifi�
    function get_oos_per_channel($brand_id, $m_date) {
        $this->db->select('channels.name as channel ,
                          sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)as oos
                          ,sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)as av', false);

        $this->db->from('models');

        $this->db->join('visits', 'models.visit_id=visits.id', 'INNER');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id', 'INNER');
        $this->db->join('channels', 'channels.id = outlets.channel_id', 'INNER');

        $this->db->join('products', 'models.product_id=products.id', 'INNER');
        $this->db->join('product_groups', 'products.product_group_id=product_groups.id', 'INNER');
        $this->db->join('brands', 'product_groups.brand_id=brands.id', 'INNER');

        $this->db->where('visits.m_date', $m_date);
        $this->db->where('brands.id', $brand_id);

        $this->db->group_by('channels.id');
        $query = $this->db->get()->result_array();
        
        $data = array();
        $row2 = array();
        $row2['channel'] = '';
        $row2['inv'] = 80;
        $data[] = $row2;

        foreach ($query as $row) {
            $row_data = array();
            $row_data['channel'] = $row['channel'];
            $row_data['oos'] = number_format(($row['oos']/($row['oos']+$row['av']))*100, 2, '.', '');
            $data[] = $row_data;
        }
        
          

        return json_encode(array_reverse($data));
    }

    function get_oos_per_category($brand_id, $m_date) {

        $this->db->select( 'categories.name as category_name,
                          sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END)as oos
                          ,sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END)as av', false);

        $this->db->from('models');
        $this->db->join('visits', 'models.visit_id=visits.id', 'INNER');
        $this->db->join('products', 'models.product_id=products.id');
        $this->db->join('product_groups', 'models.product_group_id=product_groups.id', 'INNER');
        $this->db->join('clusters', 'product_groups.cluster_id=clusters.id', 'INNER');
        $this->db->join('sub_categories', 'clusters.sub_category_id=sub_categories.id', 'INNER');
        $this->db->join('categories', 'sub_categories.category_id=categories.id ', 'INNER');
        $this->db->join('brands', 'product_groups.brand_id=brands.id', 'INNER');

        $this->db->where('brands.id', $brand_id);
        $this->db->where('brands.selected', 1);
        $this->db->where('visits.m_date', $m_date);

        $this->db->order_by('categories.id', 'desc');
        $this->db->group_by('categories.id');

        $query = $this->db->get()->result_array();
        return $query;
    }

    // just 5 produits verifié
    function get_top_products_by_date($w_date) {

        $this->db->select('products.name as product_name ,'
                . '((sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END))/(count(bcc_models.id)))*100 as oos', false);

        $this->db->from('models');
        $this->db->join('visits', 'models.visit_id=visits.id', 'INNER');
        $this->db->join('products', 'models.product_id=products.id', 'INNER');
        $this->db->join('product_groups', 'models.product_group_id=product_groups.id', 'INNER');
        $this->db->join('brands', 'product_groups.brand_id=brands.id', 'INNER');

        $this->db->where('visits.w_date', $w_date);
        $this->db->where('brands.id', 1);
        $this->db->group_by('products.id');
        $this->db->where('models.av !=', 2);
        $this->db->order_by('oos', 'desc');
        $this->db->limit(5);

        return $this->db->get()->result_array();
    }

    //tt les produit verifi�
    function get_top_products_by_date_all($w_date) {

        $this->db->select('products.name as product_name ,
                ((sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END))/(count(bcc_models.id)))*100 as oos', false);

        $this->db->from('models');
        $this->db->join('visits', 'models.visit_id=visits.id', 'INNER');
        $this->db->join('products', 'models.product_id=products.id', 'INNER');
        $this->db->join('product_groups', 'models.product_group_id=product_groups.id', 'INNER');
        $this->db->join('brands', 'product_groups.brand_id=brands.id', 'INNER');

        $this->db->where('visits.w_date', $w_date);
        $this->db->where('brands.id', 1);
         $this->db->where('models.av !=', 2);
        $this->db->group_by('products.id');

        $this->db->order_by('oos', 'desc');

        return $this->db->get()->result_array();
    }

    //verifi�
    function get_stock_issue_data($brand_id, $m_date) {
        $this->db->select('brands.name as brand_name,
                          sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END) as av,
                          sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END) oos', false);

        $this->db->from('models');
        $this->db->join('visits', 'models.visit_id=visits.id', 'INNER');
        $this->db->join('product_groups', 'models.product_group_id=product_groups.id', 'INNER');
        $this->db->join('brands', 'product_groups.brand_id=brands.id', 'INNER');

        $this->db->where('visits.m_date', $m_date);
        $this->db->where('brands.id', $brand_id);
        $this->db->group_by('brands.id');

        $query = $this->db->get()->result_array();
        $data = array();
        foreach ($query as $row) {

            $row_data = array();

            $row_data['brand_name'] = $row['brand_name'];

            $row_data['av'] = number_format($row['av'], 2, '.', '');
            $row_data['oos'] = number_format($row['oos'], 2, '.', '');
           // $row_data['ha'] = number_format($row['ha'], 2, '.', '');

            $row_data2['title'] = "AV";
            $row_data2['color'] = "#08AF02";
            $row_data2['value'] = number_format(($row['av']/($row['av']+$row['oos']))*100, 2, '.', '');

            //$row_data4['title'] = "HA";
            //$row_data4['color'] = "#32a0d1";
            //$row_data4['value'] = number_format($row['ha'], 2, '.', '');

            $row_data3['title'] = "OOS";
            $row_data3['color'] = "#FF0000";
            $row_data3['value'] = number_format(($row['oos']/($row['av']+$row['oos']))*100, 2, '.', '');

            $data[] = $row_data2;
            $data[] = $row_data3;
            //$data[] = $row_data4;
        }
        return json_encode(array_reverse($data));
    }

    function get_data_oos_of_trend($category_id) {

        $current_date_time = new DateTime();

        $first_day_week_std = firstDayOf('week', $current_date_time);
        $first_day_two_last_8week_std = date('Y-m-d', strtotime("-56 day", strtotime("$first_day_week_std")));

        $this->db->select(' visits.w_date as date,
                            channels.name as channel_name,
                            channels.color as channel_color,
                            sum(CASE WHEN bcc_models.av = 0 THEN 1 ELSE 0 END) as oos,
                            sum(CASE WHEN bcc_models.av = 1 THEN 1 ELSE 0 END) as av', false);

        $this->db->from('models');

        $this->db->join('visits', 'models.visit_id=visits.id', 'INNER');
        $this->db->join('outlets', 'visits.outlet_id = outlets.id', 'INNER');
        $this->db->join('channels', 'outlets.channel_id=channels.id', 'INNER');

        $this->db->join('product_groups', 'models.product_group_id=product_groups.id', 'INNER');
        $this->db->join('clusters', 'product_groups.cluster_id=clusters.id', 'INNER');
        $this->db->join('sub_categories', 'clusters.sub_category_id=sub_categories.id', 'INNER');
        $this->db->join('categories', 'sub_categories.category_id=categories.id', 'INNER');
        $this->db->join('brands', 'product_groups.brand_id=brands.id', 'INNER');

        $this->db->where('visits.w_date >= ', $first_day_two_last_8week_std);
        $this->db->where('visits.w_date <=', $first_day_week_std);
        $this->db->where('categories.id', $category_id);

        $this->db->order_by('visits.w_date', 'asc');
        $this->db->order_by('brands.id', 'desc');

        $this->db->group_by('visits.w_date');
        $this->db->group_by('channels.id');

        $query = $this->db->get()->result_array();
        $components = array();
        $brands = array();
        $brand_temp = array();
        $dates = array();
        $count_date = 0;

        foreach ($query as $row) {
            $br = array();
            if (!in_array($row['channel_name'], $brand_temp)) {
                $brand_temp[] = $row['channel_name'];
                $br['name'] = $row['channel_name'];
                $br['color'] = $row['channel_color'];
                $brands[] = $br;
            }

            $oos = number_format(($row['oos']/($row['oos']+$row['av']))*100, 2, '.', '');

            //create an array for every brand and the count at a outlet
            $date = format_week($row['date']);
            $components[$date] [$row['channel_name']] = $oos;
        }// end foreach report_data

        $data = array();
        foreach ($components as $date => $componentBrands) {
            $row_data = array();
            $row_data['date'] = $date;
            foreach ($brands as $brand) {
                if (isset($componentBrands[$brand['name']])) {
                    $oos = $componentBrands[$brand['name']];
                } else {
                    $oos = 0;
                }
                $row_data[$brand['name']] = $oos;
            }
            $data[] = $row_data;
        }
        $result['brands'] = $brands;
        $result['data'] = $data;
        return $result;
    }

//**************************************************************************************************************************


    function get_target_visit_by_admin($admin_id, $today) {

        $this->db->select('count(bcc_outlets.id) as nb_visits', false);

        $this->db->like('outlets.visit_day', $today);

        if ($admin_id != '') {
            $this->db->where('outlets.admin_id', $admin_id);
        }
        $this->db->where('outlets.active', 1);

        return $this->db->count_all_results('outlets');
    }

    function get_monthly_visit_by_admin($admin_id, $month = '') {
        $this->db->select('count(bcc_visits.id) as nb_visits', false);
        if ($month != '') {
            
        } else {
            $month = date('Y-m-01');
        }
        $this->db->where('visits.m_date', $month);
        $this->db->where('visits.admin_id', $admin_id);
        $this->db->where('visits.monthly_visit', 0);


        return $this->db->count_all_results('visits');
    }

    function get_daily_visit_by_admin($admin_id, $start_date, $end_date) {

        $this->db->select('count(bcc_visits.id) as nb_visits', false);

        $this->db->where('visits.date >=', $start_date);
        $this->db->where('visits.date <=', $end_date);
        $this->db->where('visits.admin_id', $admin_id);

        return $this->db->count_all_results('visits');

        //$result = $this->db->get('visits');
        //return $result->result();
    }

    function get_daily_visit_by_admin_shelf($admin_id, $start_date, $end_date) {

        $this->db->select('count(bcc_visits.id) as nb_visits', false);

        $this->db->where('visits.date >=', $start_date);
        $this->db->where('visits.date <=', $end_date);
        $this->db->where('visits.admin_id', $admin_id);

        $this->db->where_in('visits.monthly_visit', array(1, 2, 3));
        //return $this->db->count_all_results('visits');

        $result = $this->db->get('visits');
        return $result->result();
    }

    function get_daily_visit_by_admin_daily($admin_id, $start_date, $end_date) {

        $this->db->select('count(bcc_visits.id) as nb_visits', false);

        $this->db->where('visits.date >=', $start_date);
        $this->db->where('visits.date <=', $end_date);
        $this->db->where('visits.admin_id', $admin_id);

        $this->db->where('visits.monthly_visit', 0);

        //return $this->db->count_all_results('visits');

        $result = $this->db->get('visits');
        return $result->result();
    }

    //edit AMIRA ZAGHDOUDI
    function get_daily_visit_by_channel($channel_id, $start_date, $end_date) {


        $this->db->select('
		count(bcc_visits.id) as nb_visits,
		', false);

        $this->db->join('outlets', 'visits.outlet_id=outlets.id');
        $this->db->join('channels', 'outlets.channel=channels.name');

        $this->db->where('visits.date >=', $start_date);
        $this->db->where('visits.date <=', $end_date);
        $this->db->where('channels.id', $channel_id);

        return $this->db->count_all_results('visits');
    }

    //edit AMIRA ZAGHDOUDI
    function get_target_visit_by_channel($channel_id, $today) {


        $this->db->select('count(bcc_outlets.id) as nb_visits,', false);


        $ddate = date('Y-m-d');
        $date = new DateTime($ddate);
        $week = $date->format("W");
        $week_of_work = 0;

        if ($week % 2 == 0) {
            $week_of_work = 2;
        } else {
            $week_of_work = 1;
        }

        $this->db->like('outlets.visit_day', $today);
        if ($channel_id != '') {
            $this->db->join('channels', 'outlets.channel=channels.name');
            $this->db->where('channels.id', $channel_id);
        }

        $this->db->where('outlets.active', 1);

        //$where = "(bcc_outlets.week_of_work  =".$week_of_work." OR bcc_outlets.week_of_work = 0)  ";
        $where = 'bcc_outlets.week_of_work =0  ';
        $this->db->where($where);

        return $this->db->count_all_results('outlets');
    }

    //edit AMIRA ZAGHDOUDI
    function get_channel_list() {
        $this->db->select('*');
        $this->db->where('active', 1);
        $result = $this->db->get('channels');
        $result = $result->result();

        return $result;
    }

    function get_admins_messages() {

        $this->db->join('admin', 'admin.id=messages.sender_id');
        $this->db->limit(20);
        $this->db->where('access', 'Admin');
        $this->db->order_by('created', 'DESC');
        $this->db->group_by('message');
        $result = $this->db->get('messages');
        return $result->result();
    }

    function get_monthly_remarks_visits() {
        $this->db->select('visits.id as id,visits.monthly_visit as monthly_visit,outlets.name as outlet_name,outlets.id as outlet_id,outlets.zone as outlet_zone,outlets.state as outlet_state,admin.name as name,
		visits.date as date,visits.active as active,outlets.super_market_project as super_market_project,admin.photos as photos,
		remark as remark,visits.oos_perc as oos_perc,visits.entry_time as entry_time,visits.exit_time as exit_time,visits.was_there as was_there');
        $this->db->join('outlets', 'outlets.id=visits.outlet_id');
        $this->db->join('admin', 'admin.id=visits.admin_id');
        $this->db->from('visits');



        $this->db->where('visits.remark !=', '');
        $this->db->where('visits.m_date', date('Y-m-01'));

        $this->db->order_by('visits.id', 'desc');

        $result = $this->db->get();
        return $result->result();
    }

    function get_outlets_by_states() {
        $this->db->select('outlets.state as state,count(id) as value', false);


        $this->db->from('outlets');
        $this->db->group_by('state');
        $query = $this->db->get()->result_array();

        return json_encode(array_reverse($query));
    }

    function get_outlets_by_channels() {
        $this->db->select('outlets.channel as channel,count(bcc_outlets.id) as value,channels.color as color', false);
        $this->db->join('channels', 'channels.name=outlets.channel');

        $this->db->from('outlets');
        $this->db->group_by('channel');
        $query = $this->db->get()->result_array();

        return json_encode(array_reverse($query));
    }

    function get_zone_json_data($zone_id, $m_date) {

        $this->db->select('outlets.zone as zone,models.brand_id as brand_id,sum(bcc_models.av) as oos_perc,sum(bcc_models.av_sku) as sum_av_sku,count(bcc_models.id) as total,brands.name as brand_name,categories.name as category_name', false);
        $this->db->from('visits');



        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('zones', 'outlets.zone = zones.name');


        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('categories', 'categories.id = models.category_id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');

        $this->db->where('zones.id', $zone_id);
        $this->db->where('zones.zone_for_chart', 1);
        $this->db->where('brands.selected', 1);
        $this->db->where('visits.m_date', $m_date);
        $this->db->order_by('brands.id', 'desc');
        $this->db->group_by('categories.id');





        $query = $this->db->get()->result_array();
        $data = array();
        $back = array();
        foreach ($query as $row) {

            $row_data = array();

            if ($row['total'] != 0) {
                $row_data['brand'] = $row['category_name'];
                if ($row['brand_id'] == 1) {
                    $row_data['av'] = number_format(($row['oos_perc'] / $row['total']) * 100, 2, '.', ' ');
                    $row_data['oos'] = 100 - $row_data['av'];
                } else {

                    $row_data['av'] = number_format(($row['sum_av_sku'] / $row['total']) * 100, 2, '.', ' ');
                    $row_data['oos'] = 100 - $row_data['av'];
                }
            }//end if 
        }//end for
        return json_encode(array_reverse($data));
    }

    function get_top5_oss_henkel() {
        $this->db->select('
		count(bcc_models.id) as total,
		products.name as product_name ,
		
		count(CASE WHEN bcc_models.av = 0 THEN bcc_models.av ELSE 0 END)  AS count_av
		
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
		count(bcc_models.id) as total,
		products.name as product_name ,
		
		count(CASE WHEN bcc_models.av = 1 THEN bcc_models.av ELSE 0 END)  AS count_av
		
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
		count(bcc_models.id) as total,
		products.name as product_name ,
		
		count(CASE WHEN bcc_models.sku_display = 0 THEN bcc_models.av ELSE 0 END)  AS count_av
		
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
		count(bcc_models.id) as total,
		products.name as product_name ,
		
		count(CASE WHEN bcc_models.sku_display != 0 THEN bcc_models.av ELSE 0 END)  AS count_av
		
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

        $this->db->select('sum(bcc_models.av) as oos_perc,sum(CASE WHEN bcc_models.sku_display != 0 THEN 1 ELSE 0 END) as oos_perc_other,count(bcc_models.id) as total,brands.name as brand_name', false);
        $this->db->from('visits');



        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');

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
        $this->db->select('sum(bcc_models.av) as oos_perc,sum(CASE WHEN bcc_models.sku_display != 0 THEN 1 ELSE 0 END) as oos_perc_other,count(bcc_models.id) as total,brands.name as brand_name', false);
        $this->db->from('visits');



        $this->db->join('outlets', 'outlets.id = visits.outlet_id');
        $this->db->join('zones', 'outlets.zone = zones.name');

        $this->db->join('models', 'models.visit_id = visits.id');
        $this->db->join('brands', 'brands.id = bcc_models.brand_id');

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

    // Count Daily visits
    function count_daily_visits() {

        $current_date = date('Y-m-d');
        $this->db->where('date', $current_date);
        return $this->db->count_all_results('visits');
    }

    // Count Daily target 
    // Param : Label of day example (Monday)
    function count_daily_target($date_lib) {
        //$this->db->select('count(bcc_outlets.id) as nb_visits',false);
        $this->db->like('outlets.visit_day', $date_lib);
        $this->db->where('outlets.active', 1);
        return $this->db->count_all_results('outlets');
    }

    function count_month_visits() {

        $month = date('Y-m-01');
        $this->db->where('m_date =', $month);
        return $this->db->count_all_results('visits');
    }

    function get_visit_oos() {

        $this->db->select('sum(bcc_visits.oos_perc) as oos_perc,count(bcc_visits.id) as total', false);
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

        $this->db->select('sum(CASE WHEN bcc_models.sku_display != 0 THEN 1 ELSE 0 END) as oos_perc,count(bcc_models.id) as total,zones.name as zone_name', false);
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

        $this->db->select('sum(CASE WHEN bcc_models.sku_display != 0 THEN 0 ELSE 1 END) as oos_perc,count(bcc_models.id) as total', false);
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

    function get_today_visits($limit = 0, $offset = 0, $order_by = 'visits.id', $direction = 'DESC', $current_admin_id = -1) {
        $this->db->select('visits.longitude as longitude,visits.latitude as latitude,visits.id as id,visits.monthly_visit as monthly_visit,outlets.name as outlet_name,outlets.id as outlet_id,outlets.zone as outlet_zone,outlets.state as outlet_state,admin.name as name,
		visits.date as date,visits.active as active,
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

    function get_visit_av() {

        $this->db->select('sum(bcc_visits.oos_perc) as oos_perc,count(bcc_visits.id) as total', false);
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

    function get_top_products_by_category($category_id, $m_date) {

        $data = array();
        $this->db->select('
		count(bcc_models.id) as total,
		products.name as product_name ,
		 brands.id as brand_id ,
		count(CASE WHEN bcc_models.av = 1 THEN bcc_models.av ELSE 0 END)  AS count_av,
		sum(bcc_models.av)  AS count_av2,
		sum(bcc_models.av_sku)  AS count_av_sku
		
		', false);
        $this->db->join('products', 'models.product_id=products.id');
        $this->db->join('visits', 'visits.id=models.visit_id');
        $this->db->join('brands', 'brands.id=models.brand_id');
        $this->db->where('visits.m_date', $m_date);
        $this->db->where('models.brand_id !=', 8);
        //$this -> db -> where('models.av',0);
        $this->db->where('models.category_id', $category_id);


        $this->db->group_by('products.name');
        //$this -> db -> group_by('brands.name');
        $this->db->order_by('count_av2');
        $this->db->order_by('count_av_sku');
        $this->db->limit(5);
        //$result = $this -> db -> get('products');
        $this->db->from('models');
        $query = $this->db->get()->result_array();
        $query = array_reverse($query);

        foreach ($query as $row) {


            if ($row['total'] != 0) {
                if ($row['brand_id'] == 1) {
                    $row['percentage'] = number_format(($row['count_av2'] / $row['total']) * 100, 2, ',', ' ');
                } else {
                    $row['percentage'] = number_format(($row['count_av_sku'] / $row['total']) * 100, 2, ',', ' ');
                }
            } else {

                $row['percentage'] = '-';
            }
            $data[] = $row;
        }

        return array_reverse($data);
    }

    function get_brands_for_charts() {

        $this->db->select('brands.name as name');
        $this->db->from('brands');
        $this->db->where('brand_for_chart', 1);

        $result = $this->db->get();
        $brands = ($result->result());


        foreach ($brands as $brand) {
            $data[] = $brand->name;
        }
        return json_encode($data, JSON_HEX_TAG);
    }

    //INDICES

    function get_weekly_indices($first_day_week_std = '') {
        $this->db->select('
		SUM(' . $this->dbprefix . 'weekly_models.ws) as tot_weekly_ws,
		SUM(' . $this->dbprefix . 'weekly_models.amount) as tot_weekly_amount,
		SUM(CASE WHEN ' . $this->dbprefix . 'weekly_models.brand_id = 1 THEN ' . $this->dbprefix . 'weekly_models.ws ELSE 0 END)  AS samsung_weekly_ws,
		SUM(CASE WHEN ' . $this->dbprefix . 'weekly_models.brand_id = 1 THEN ' . $this->dbprefix . 'weekly_models.amount ELSE 0 END)  AS samsung_weekly_amount
		', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        return $this->db->get()->row();
    }

    //INDICES_sfo
    function get_weekly_outlet_by_shortage_models($first_day_week_std = '', $model_id) {
        $this->db->select('*');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        $this->db->where('weekly_models.model_id ', $model_id);
        $this->db->where('weekly_models.shortage ', 0);

        $result = $this->db->get('weekly_models');
        return $result->result();
    }

    function get_weekly_indices_sfo($first_day_week_std = '', $admin_id) {
        $this->db->select('
		
		SUM(CASE WHEN ' . $this->dbprefix . 'weekly_models.shortage = 0 THEN  1  ELSE 0 END)  AS shortage_sfo', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        $this->db->where('weekly_visits.admin_id ', $admin_id);

        return $this->db->get()->row();
    }

    //Brand Stats

    function get_brand_weekly_sales($first_day_week_std = '') {
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.ws) as total, brands.sub_name as brand_name, brands.color as color', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->join('brands', 'brands.id=weekly_models.brand_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        $this->db->group_by('brands.sub_name');
        $this->db->order_by('total', 'DESC');
        $query = $this->db->get()->result_array();
        $result = json_encode($query);
        return $result;
    }

    function get_brand_weekly_amount($first_day_week_std = '') {
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.amount) as total, brands.sub_name as brand_name, brands.color as color', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->join('brands', 'brands.id=weekly_models.brand_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        $this->db->group_by('brands.sub_name');
        $this->db->order_by('total', 'DESC');
        $query = $this->db->get()->result_array();
        $result = json_encode($query);
        return $result;
    }

    function get_brand_weekly_smart_sales($first_day_week_std = '') {
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.amount) as total, brands.sub_name as brand_name, brands.color as color', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->join('brands', 'brands.id=weekly_models.brand_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        $this->db->where('weekly_models.range_id ', 2);
        $this->db->group_by('brands.sub_name');
        $this->db->order_by('total', 'DESC');
        $query = $this->db->get()->result_array();
        $result = json_encode($query);
        return $result;
    }

    function get_brand_weekly_smart_quantity($first_day_week_std = '') {
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.ws) as total, brands.sub_name as brand_name, brands.color as color', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->join('brands', 'brands.id=weekly_models.brand_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        $this->db->where('weekly_models.range_id ', 2);
        $this->db->group_by('brands.sub_name');
        $this->db->order_by('total', 'DESC');
        $query = $this->db->get()->result_array();
        $result = json_encode($query);
        return $result;
    }

    ///shortage by sfo
    function get_weekly_shortage_by_sfo($first_day_week_std = '', $admin_id) {

        // $this->db->select('count('.$this->dbprefix.'weekly_models.id)  AS total_shortage_user',false);

        $this->db->select('SUM(CASE WHEN ' . $this->dbprefix . 'weekly_models.shortage = 0 THEN 1 ELSE 0 END)  AS total_shortage');

        // $this->db->from('weekly_models');


        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');

        $this->db->having('total_shortage > 0', false);

        $this->db->where('weekly_visits.date ', $first_day_week_std);
        $this->db->where('weekly_visits.admin_id ', $admin_id);
        $this->db->group_by('weekly_models.visit_id');

        //$result = $this -> db -> get('weekly_models');

        $result = $this->db->get('weekly_models');
        return $result->result();
    }

    ////image by sfo
    function get_weekly_images_by_sfo($first_day_week_std = '', $admin_id) {

        $this->db->select('SUM(CASE WHEN ' . $this->dbprefix . 'weekly_visits.branding_images !="" or ' . $this->dbprefix . 'weekly_visits.branding_images !="" or ' . $this->dbprefix . 'weekly_visits.competitor_images !="" or ' . $this->dbprefix . 'weekly_visits.voice_images !=""  THEN 1 ELSE 0 END)  AS total_images', false);
        $this->db->from('weekly_visits');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        $this->db->where('weekly_visits.admin_id ', $admin_id);

        return $this->db->get()->row();
    }

    //////
    //Top Models



    function get_shortage_weekly_top_models($first_day_week_std = '', $limit) {


        $this->db->select('
				SUM(CASE WHEN ' . $this->dbprefix . 'weekly_models.shortage = 0 THEN 1 ELSE 0 END)  AS total_shortage,

		COUNT(' . $this->dbprefix . 'weekly_models.shortage) as total,
		weekly_models.model_name as model_name,weekly_models.model_id as model_id', false);


        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        $this->db->where('weekly_models.brand_id ', 1);

        $this->db->group_by('weekly_models.model_name');
        $this->db->order_by('total_shortage', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, 0);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    function get_market_share_weekly_top_models($first_day_week_std = '', $limit) {
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.ws) as total, weekly_models.model_name as model_name', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        //$this -> db -> where('weekly_models.brand_id ', 1);

        $this->db->group_by('weekly_models.model_name');
        $this->db->order_by('total', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, 0);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    function get_market_share_weekly_top_models_amount($first_day_week_std = '', $limit) {
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.amount) as total, weekly_models.model_name as model_name', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        //$this -> db -> where('weekly_models.brand_id ', 1);

        $this->db->group_by('weekly_models.model_name');
        $this->db->order_by('total', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, 0);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    function get_market_share_weekly_top_models_amount_smart($first_day_week_std = '', $limit) {
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.amount) as total, weekly_models.model_name as model_name', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        $this->db->where('weekly_models.range_id ', 2);

        $this->db->group_by('weekly_models.model_name');
        $this->db->order_by('total', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, 0);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    function get_market_share_weekly_top_models_quuantity_smart($first_day_week_std = '', $limit) {
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.ws) as total, weekly_models.model_name as model_name', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->where('weekly_visits.date ', $first_day_week_std);
        $this->db->where('weekly_models.range_id ', 2);

        $this->db->group_by('weekly_models.model_name');
        $this->db->order_by('total', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, 0);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }

    // Trend Stats
    function get_trend_weekly_amount($limit = 0) {
        $data = array();
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.amount) as total,
			      	       SUM(CASE WHEN ' . $this->dbprefix . 'weekly_models.brand_id = 1 THEN ' . $this->dbprefix . 'weekly_models.amount ELSE 0 END)  AS samsung,
                           weekly_visits.date as date', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->group_by('weekly_visits.date');
        $this->db->order_by('weekly_visits.date', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, 0);
        }
        $query = $this->db->get()->result_array();



        foreach ($query as $row) {
            $row['date'] = format_week($row['date']);
            $row['lineColor'] = '#ff7400'; // red
            $row['samsungColor'] = '#0c4da2'; // blue
            if ($row['total'] != 0) {
                $row['percentage'] = number_format(($row['samsung'] / $row['total']) * 100, 2, ',', ' '); // blue
            } else {
                $row['percentage'] = '-';
            }
            $data[] = $row;
        }
        $result = json_encode(array_reverse($data));
        return $result;
    }

    function get_trend_weekly_smart_amount($limit = 0) {
        $data = array();
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.amount) as total,
         SUM(CASE WHEN ' . $this->dbprefix . 'weekly_models.brand_id = 1 THEN ' . $this->dbprefix . 'weekly_models.amount ELSE 0 END)  AS samsung,
		weekly_visits.date as date', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->where('weekly_models.range_id ', 2);
        $this->db->group_by('weekly_visits.date');
        $this->db->order_by('weekly_visits.date', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, 0);
        }
        $query = $this->db->get()->result_array();
        foreach ($query as $row) {
            $row['date'] = format_week($row['date']);
            $row['lineColor'] = '#ff7400'; // 
            $row['samsungColor'] = '#0c4da2'; // blue
            if ($row['total'] != 0) {
                $row['percentage'] = number_format(($row['samsung'] / $row['total']) * 100, 2, ',', ' '); // blue
            } else {
                $row['percentage'] = '-';
            }
            $data[] = $row;
        }
        $result = json_encode(array_reverse($data));
        return $result;
    }

    function get_trend_weekly_sales($limit = 0) {
        $data = array();
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.ws) as total,
        SUM(CASE WHEN ' . $this->dbprefix . 'weekly_models.brand_id = 1 THEN ' . $this->dbprefix . 'weekly_models.ws ELSE 0 END)  AS samsung,
		weekly_visits.date as date', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        //$this -> db -> where('weekly_models.range_id ', 2);
        $this->db->group_by('weekly_visits.date');
        $this->db->order_by('weekly_visits.date', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, 0);
        }
        $query = $this->db->get()->result_array();
        foreach ($query as $row) {
            $row['date'] = format_week($row['date']);
            $row['lineColor'] = '#ff7400'; // red
            $row['samsungColor'] = '#0c4da2'; // blue
            if ($row['total'] != 0) {
                $row['percentage'] = number_format(($row['samsung'] / $row['total']) * 100, 2, ',', ' '); // blue
            } else {
                $row['percentage'] = '-';
            }
            $data[] = $row;
        }
        $result = json_encode(array_reverse($data));

        return $result;
    }

    function get_trend_weekly_smart_sales($limit = 0) {
        $data = array();
        $this->db->select('SUM(' . $this->dbprefix . 'weekly_models.ws) as total,
        SUM(CASE WHEN ' . $this->dbprefix . 'weekly_models.brand_id = 1 THEN ' . $this->dbprefix . 'weekly_models.ws ELSE 0 END)  AS samsung,
		weekly_visits.date as date', false);
        $this->db->from('weekly_models');
        $this->db->join('weekly_visits', 'weekly_visits.id=weekly_models.visit_id');
        $this->db->where('weekly_models.range_id ', 2);
        $this->db->group_by('weekly_visits.date');
        $this->db->order_by('weekly_visits.date', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, 0);
        }
        $query = $this->db->get()->result_array();
        foreach ($query as $row) {
            $row['date'] = format_week($row['date']);
            $row['lineColor'] = '#ff7400'; // yellow
            $row['samsungColor'] = '#0c4da2'; // blue

            if ($row['total'] != 0) {
                $row['percentage'] = number_format(($row['samsung'] / $row['total']) * 100, 2, ',', ' '); // blue
            } else {
                $row['percentage'] = '-';
            }
            $data[] = $row;
        }
        $result = json_encode(array_reverse($data));
        return $result;
    }

    //total_visits by user
    function get_total_visits($current_admin_id) {
        $this->db->select('
		Count(' . $this->dbprefix . 'weekly_visits.id) as total', false);
        $this->db->from('weekly_visits');
        $this->db->where('weekly_visits.admin_id ', $current_admin_id);
        return $this->db->get()->row();
    }

    //total_weekly_visits by user
    function get_total_weekly_visits($current_admin_id, $first_day_week_std) {
        $this->db->select('
		Count(' . $this->dbprefix . 'weekly_visits.id) as total_weekly_visits', false);
        $this->db->from('weekly_visits');
        $this->db->where('weekly_visits.admin_id ', $current_admin_id);
        $this->db->where('weekly_visits.date ', $first_day_week_std);

        return $this->db->get()->row();
    }

    //total_outlet_actif by user
    function get_total_outlet_actif_by_user($current_admin_id) {
        $this->db->select('
		Count(' . $this->dbprefix . 'outlets.id) as total_outlet_actif', false);
        $this->db->from('outlets');
        $this->db->where('outlets.admin_id ', $current_admin_id);
        $this->db->where('outlets.active ', 1);

        return $this->db->get()->row();
    }

}
