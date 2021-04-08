<?php

//BCM REPORT
class Reports extends CI_Controller {

    var $connected_user_id = false;

    function __construct() {
        parent::__construct();

        if (!$this->auth->is_logged_in(false, false)) {

            redirect('login');
        }
        $admin = $this->session->userdata('admin');
        $this->connected_user_id = $admin['id'];
        $this->load->model(array('Admin_model', 'Channel_model', 'Sub_channel_model', 'Product_group_model', 'Product_model', 'Cluster_model',
            'Sub_category_model', 'Visit_model', 'Outlet_model', 'Brand_model', 'Report_model',
            'State_model', 'Zone_model', 'Category_model', 'Dashboard_model'));

        $this->load->helper(array('formatting', 'date'));

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->lang->load('reports');
    }

    /*     * **************************************** */

    // Stock issues report (stats)
    // Edited By : Boulbaba 26/01/2018
    /*     * **************************************** */


    function stock_issues_report() {
        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "Stock issues report";
        $multi_date = 0;
        $multi_zone = 0;
        $multi_channel = 0;
        $multi_sub_channel = 0;

        $start_date = ($this->input->post("start_date")) ? $this->input->post("start_date") : "";
        $end_date = ($this->input->post("end_date")) ? $this->input->post("end_date") : "";

        $date_type = $this->input->post('date_type');

        $category_id = $this->input->post('category_id');
        $selected_zone_ids = $this->input->post('selected_zone_ids');
        $selected_channel_ids = $this->input->post('selected_channel_ids');
         $selected_sub_channel_ids = $this->input->post('selected_sub_channel_ids');


        if ($date_type == 'month') {
            $start_date = $this->input->post('start_date_m');
            $end_date = $this->input->post('end_date_m');
        } else if ($date_type == 'week') {
            $start_date = $this->input->post('start_date_w');
            $end_date = $this->input->post('end_date_w');
        } else if ($date_type == 'quarter') {
            $year1 = $this->input->post('year1');
            $quarter1 = $this->input->post('quarter1');

            $year2 = $this->input->post('year2');
            $quarter2 = $this->input->post('quarter2');

            $start_date = $year1 . '-' . $quarter1;
            $end_date = $year2 . '-' . $quarter2;
        }

        if ($start_date != $end_date) {
            $multi_date = 1;
        }
        if (!empty($selected_zone_ids)) {
            $multi_zone = 1;
        }

        if (!empty($selected_channel_ids)) {
            $multi_channel = 1;
        }
        
         if (!empty($selected_sub_channel_ids)) {
            $multi_sub_channel = 1;
        }

        $data['multi_date'] = $multi_date;
        $data['multi_zone'] = $multi_zone;
        $data['multi_channel'] = $multi_channel;
         $data['multi_sub_channel'] = $multi_sub_channel;

        $data['date_type'] = $date_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['category_id'] = $category_id;

        $data['selected_zone_ids'] = $selected_zone_ids;
        $data['selected_channel_ids'] = $selected_channel_ids;
         $data['selected_sub_channel_ids'] = $selected_sub_channel_ids;

        $data['json_zone_ids'] = json_encode($selected_zone_ids);
        $data['json_channel_ids'] = json_encode($selected_channel_ids);
         $data['json_sub_channel_ids'] = json_encode($selected_sub_channel_ids);

        $data['zones'] = $this->Zone_model->get_zones();
        $data['channels'] = $this->Channel_model->get_channels();
         $data['sub_channels'] = $this->Sub_channel_model->get_sub_channels();
        $data['categories'] = $this->Category_model->get_categories();

        if ($category_id && $category_id != '-1' && $start_date && $end_date) {
            $data['clusters'] = $this->Cluster_model->get_clusters_by_category_without_others($category_id);
        }

        $this->load->view('header', $data_header);
        $this->load->view('chart_header');
        $this->load->view('reports/stock_issues/stock_issues_report', $data);


        if ($start_date && $end_date) {

            // Multi Date 
            if ($multi_date && !$multi_zone && !$multi_channel) {
                $data['report_data'] = $this->Report_model->get_av_multi_date_brand($date_type, $start_date, $end_date, $category_id, '-1', '-1');
                $this->load->view('reports/stock_issues/multi_date/multi_date', $data);
                // Multi Date + Multi Zones
            } else if ($multi_date && $multi_zone) {
                //$data['report_data'] = $this->Report_model->get_av_multi_date_brand($date_type, $start_date, $end_date, $category_id, $selected_zone_ids, $selected_channel_ids);
                $this->load->view('reports/stock_issues/multi_date/multi_date_zones', $data);

                // Multi Date + Multi Outlet types
            } else if ($multi_date && !$multi_zone && $multi_channel) {
                //$data['report_data'] = $this->Report_model->get_av_multi_date_brand($date_type, $start_date, $end_date, $category_id, $selected_zone_ids, $selected_channel_ids);
                $this->load->view('reports/stock_issues/multi_date/multi_date_channels', $data);

                // Single Date only
            } else if (!$multi_date && !$multi_zone && !$multi_channel && !$multi_sub_channel ) {
                // Meme traitement que multi date !
                $data['report_data'] = $this->Report_model->get_av_multi_date_brand($date_type, $start_date, $end_date, $category_id, '-1', '-1');
                $this->load->view('reports/stock_issues/single_date/single_date', $data);

                // Single Date + Multi Zones ****************************
            } else if (!$multi_date && $multi_zone) {
                $data['report_data'] = $this->Report_model->get_av_single_date_brand_zones($date_type, $start_date, $end_date, $category_id, $selected_zone_ids, $selected_channel_ids);
                $this->load->view('reports/stock_issues/single_date/single_date_zones', $data);

                // Single Date + Multi Outlet Types
            } else if (!$multi_date && $multi_channel) {
                $data['report_data'] = $this->Report_model->get_av_single_date_brand_channels($date_type, $start_date, $end_date, $category_id, $selected_zone_ids, $selected_channel_ids);
                $this->load->view('reports/stock_issues/single_date/single_date_channels', $data);
                //  Single Date + Multi Zones + Multi Outlet Types
            }
            
             else if (!$multi_date && $multi_sub_channel) {
                $data['report_data'] = $this->Report_model->get_av_single_date_brand_sub_channels($date_type, $start_date, $end_date, $category_id, $selected_zone_ids, $selected_channel_ids, $selected_sub_channel_ids);
                $this->load->view('reports/stock_issues/single_date/single_date_sub_channels', $data);
                //  Single Date + Multi Zones + Multi Outlet Types
            }
        }//end if ($start_date && $end_date)

        $this->load->view('footer');
    }

    // Foreach cluster single date only or multi date only
    function load_av_cluster() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $date_type = $this->input->post('date_type');

        $category_id = $this->input->post('category_id');
        $cluster_id = $this->input->post('cluster_id');
        $zone_id = $this->input->post('zone_id');

        if (($this->input->post('json_channel_ids')))
            $channel_ids = json_decode($this->input->post('json_channel_ids'));
        else if (($this->input->post('channel_id')))
            $channel_ids = $this->input->post('channel_id');

        $data['cluster_id'] = $cluster_id;
        $data['zone_id'] = $zone_id;

        $data['out_val'] = $this->input->post('out_val');
        $data['zone_val'] = $this->input->post('zone_val');
        $data['date_type'] = $this->input->post('date_type');
        $data['report_data'] = $this->Report_model->get_av_cluster($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_id, $channel_ids);
        $this->load->view('reports/stock_issues/load_av_cluster', $data);
    }

    function load_av_cluster_zones() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $date_type = $this->input->post('date_type');
        $category_id = $this->input->post('category_id');
        $cluster_id = $this->input->post('cluster_id');
        $zone_ids = json_decode($this->input->post('json_zone_ids'));
        $channel_ids = json_decode($this->input->post('json_channel_ids'));

        $data['channel_ids'] = $channel_ids;
        $data['cluster_id'] = $cluster_id;

        $data['out_val'] = $this->input->post('out_val');
        $data['zone_val'] = $this->input->post('zone_val');
        $data['report_data'] = $this->Report_model->get_av_cluster_zones($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_ids, $channel_ids);
        $this->load->view('reports/stock_issues/single_date/load_av_cluster_zones', $data);
    }

    function load_av_cluster_channels() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $date_type = $this->input->post('date_type');
        $category_id = $this->input->post('category_id');
        $cluster_id = $this->input->post('cluster_id');

        $zone_ids = json_decode(json_decode($this->input->post('json_zone_ids')));
        $channel_ids = json_decode($this->input->post('json_channel_ids'));

        $data['cluster_id'] = $cluster_id;
        $data['out_val'] = $this->input->post('out_val');
        $data['zone_val'] = $this->input->post('zone_val');
        $data['report_data'] = $this->Report_model->get_av_cluster_channels($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_ids, $channel_ids);
        $this->load->view('reports/stock_issues/single_date/load_av_cluster_channels', $data);
    }
    
     function load_av_cluster_sub_channels() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $date_type = $this->input->post('date_type');
        $category_id = $this->input->post('category_id');
        $cluster_id = $this->input->post('cluster_id');

        $zone_ids = json_decode(json_decode($this->input->post('json_zone_ids')));
        $channel_ids = json_decode($this->input->post('json_channel_ids'));
         $sub_channel_ids = json_decode($this->input->post('json_sub_channel_ids'));

        $data['cluster_id'] = $cluster_id;
        $data['out_val'] = $this->input->post('out_val');
        $data['zone_val'] = $this->input->post('zone_val');
        $data['report_data'] = $this->Report_model->get_av_cluster_sub_channels($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_ids, $channel_ids, $sub_channel_ids);
        $this->load->view('reports/stock_issues/single_date/load_av_cluster_sub_channels', $data);
    }

    function load_av_zone() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $date_type = $this->input->post('date_type');
        $category_id = $this->input->post('category_id');

        $zone_id = $this->input->post('zone_id');

        if (($this->input->post('json_channel_ids')))
            $channel_ids = json_decode($this->input->post('json_channel_ids'));
        else if (($this->input->post('channel_id')))
            $channel_ids = $this->input->post('channel_id');

        $data['json_channel_ids'] = $this->input->post('json_channel_ids');

        $data['zone_id'] = $zone_id;
        $data['channel_ids'] = $channel_ids;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['date_type'] = $date_type;
        $data['category_id'] = $category_id;

        $data['zone_val'] = $this->input->post('zone_val');
        $data['out_val'] = $this->input->post('out_val');
        if ($category_id && $category_id != '-1' && $start_date && $end_date) {
            $data['clusters'] = $this->Cluster_model->get_clusters_by_category_without_others($category_id);
        }
        $data['report_data'] = $this->Report_model->get_av_multi_date_brand($date_type, $start_date, $end_date, $category_id, $zone_id, $channel_ids);
        $this->load->view('reports/stock_issues/multi_date/load_av_zone', $data);
    }

    function load_av_channel() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $date_type = $this->input->post('date_type');
        $category_id = $this->input->post('category_id');

        $zone_id = $this->input->post('zone_id');
        if (($this->input->post('json_channel_ids')))
            $channel_ids = json_decode($this->input->post('json_channel_ids'));
        else if (($this->input->post('channel_id')))
            $channel_ids = $this->input->post('channel_id');

        $data['zone_id'] = $zone_id;
        $data['channel_id'] = $channel_ids;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['date_type'] = $date_type;
        $data['category_id'] = $category_id;

        $data['out_val'] = $this->input->post('out_val');
        $data['zone_val'] = $this->input->post('zone_val');
        if ($category_id && $category_id != '-1' && $start_date && $end_date) {
            $data['clusters'] = $this->Cluster_model->get_clusters_by_category_without_others($category_id);
        }
        $data['report_data'] = $this->Report_model->get_av_multi_date_brand($date_type, $start_date, $end_date, $category_id, $zone_id, $channel_ids);
        $this->load->view('reports/stock_issues/multi_date/load_av_channels', $data);
    }

    /*     * **************************************** */

    // Shelf Share report (stats)
    // Edited By : Amira 29/01/2018
    /*     * **************************************** */

    function shelf_share_report() {
        $data = array();
        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "Shelf share report";

        $multi_date = 0;
        $multi_zone = 0;
        $multi_channel = 0;

        $date_type = 'quarter';

        $year1 = ($this->input->post('year1')) ? $this->input->post('year1') : date('Y');
        $quarter1 = ($this->input->post('quarter1')) ? $this->input->post('quarter1') : "";

        $year2 = ($this->input->post('year2')) ? $this->input->post('year2') : date('Y');
        $quarter2 = ($this->input->post('quarter2')) ? $this->input->post('quarter2') : "";

        $start_date = $year1 . '-' . $quarter1;
        $end_date = $year2 . '-' . $quarter2;

        $category_id = $this->input->post('category_id');
        $selected_zone_ids = $this->input->post('selected_zone_ids');
        $selected_channel_ids = $this->input->post('selected_channel_ids');

        if ($start_date != $end_date) {
            $multi_date = 1;
        }
        if (!empty($selected_zone_ids)) {
            $multi_zone = 1;
        }
        if (!empty($selected_channel_ids)) {
            $multi_channel = 1;
        }
        if ($category_id && $start_date && $end_date) {
            $data['clusters'] = $this->Cluster_model->get_clusters_by_category_without_others($category_id);
        }
        $data['multi_date'] = $multi_date;
        $data['multi_zone'] = $multi_zone;
        $data['multi_channel'] = $multi_channel;

        $data['date_type'] = $date_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $data['year1'] = $year1;
        $data['quarter1'] = $quarter1;

        $data['year2'] = $year2;
        $data['quarter2'] = $quarter2;

        $data['category_id'] = $category_id;
        $data['channel'] = $this->input->post('channel');
        $data['selected_zone_ids'] = $selected_zone_ids;
        $data['selected_channel_ids'] = $selected_channel_ids;

        $data['json_zone_ids'] = json_encode($selected_zone_ids);
        $data['json_channel_ids'] = json_encode($selected_channel_ids);

        $data['zones'] = $this->Zone_model->get_zones();
        $data['categories'] = $this->Category_model->get_categories();
        $data['channels'] = $this->Channel_model->get_channels();

        $this->load->view('header', $data_header);
        $this->load->view('chart_header');
        $this->load->view('reports/shelf_share/shelf_share_report', $data);

        if ($start_date && $end_date) {
            // Multi Date 
            if ($multi_date && !$multi_zone && !$multi_channel) {
                $data['report_data'] = $this->Report_model->get_shelf_multi_date_brand($date_type, $start_date, $end_date, $category_id, '-1', '-1');
                $this->load->view('reports/shelf_share/multi_date', $data);

                // Multi Date + Multi Zones
            } else if ($multi_date && $multi_zone && !$multi_channel) {
                $this->load->view('reports/shelf_share/multi_date_zones', $data);

                // Multi Date + Multi Outlet types
            } else if ($multi_date && !$multi_zone && $multi_channel) {
                $this->load->view('reports/shelf_share/multi_date_channels', $data);

                // Single Date only
            } else if (!$multi_date && !$multi_zone && !$multi_channel) {
                // Meme traitement que multi date !
                $data['report_data'] = $this->Report_model->get_shelf_multi_date_brand($date_type, $start_date, $end_date, $category_id, '-1', '-1');
                $this->load->view('reports/shelf_share/single_date', $data);

                // Single Date + Multi Zones 
            } else if (!$multi_date && $multi_zone) {
                $data['report_data'] = $this->Report_model->get_shelf_single_date_brand_zones($date_type, $start_date, $end_date, $category_id, $selected_zone_ids, $selected_channel_ids);
                $data['sum_metrage'] = array_sum(array_values($this->Report_model->get_total_metrage($date_type, $start_date, $end_date, $category_id, $selected_zone_ids, $selected_channel_ids)));
                $this->load->view('reports/shelf_share/single_date_zones', $data);


                // Single Date + Multi Channels
            } else if (!$multi_date && !$multi_zone && $multi_channel) {
                $data['report_data'] = $this->Report_model->get_shelf_single_date_brand_channels($date_type, $start_date, $end_date, $category_id, $selected_zone_ids, $selected_channel_ids);
                $data['sum_metrage'] = array_sum(array_values($this->Report_model->get_total_metrage($date_type, $start_date, $end_date, $category_id, $selected_zone_ids, $selected_channel_ids)));
                $this->load->view('reports/shelf_share/single_date_channels', $data);
                //  Single Date + Multi Zones + Multi Outlet Types
            }
        }//end if ($start_date && $end_date)
        $this->load->view('footer');
    }

    //multi date  verifi� le 06/07
    //single dtae 
    //load shelf zone
    //load shelf channel
    function load_shelf_cluster() {
        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $cluster_id = $this->input->post('cluster_id');
        $category_id = $this->input->post('category_id');

        $data['cluster_id'] = $cluster_id;
        $data['category_id'] = $category_id;
        $data['date_type'] = $date_type;

        if (($this->input->post('json_channel_ids')))
            $channel_ids = json_decode($this->input->post('json_channel_ids'));
        else if (($this->input->post('channel_id')))
            $channel_ids = $this->input->post('channel_id');

        $zone_ids = $this->input->post('zone_id');

        $data['out_val'] = $this->input->post('out_val');
        $data['zone_val'] = $this->input->post('zone_val');

        $data['report_data'] = $this->Report_model->get_shelf_cluster($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_ids, $channel_ids);
        $data['sum_metrage'] = $this->Report_model->get_total_metrage($date_type, $start_date, $end_date, $category_id, $zone_ids, $channel_ids);

        $this->load->view('reports/shelf_share/load_shelf_cluster', $data);
    }

    function load_shelf_zone_pie_chart() {
        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $zone_id = $this->input->post('zone_id');
        $category_id = $this->input->post('category_id');

        $data['zone_id'] = $zone_id;

        $data['report_data'] = $this->Report_model->get_shelf_zone_pie_chart($date_type, $start_date, $end_date, $category_id, $zone_id);
        $this->load->view('reports/shelf_share/load_shelf_zone_pie_chart', $data);
    }

    function load_shelf_channel_pie_chart() {
        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $channel_id = $this->input->post('channel_id');
        $category_id = $this->input->post('category_id');

        $data['channel_id'] = $channel_id;

        $data['report_data'] = $this->Report_model->get_shelf_channel_pie_chart($date_type, $start_date, $end_date, $category_id, $channel_id);
        $this->load->view('reports/shelf_share/load_shelf_channel_pie_chart', $data);
    }

    function load_shelf_cluster_zones() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $category_id = $this->input->post('category_id');
        $date_type = $this->input->post('date_type');
        $cluster_id = $this->input->post('cluster_id');

        $zone_ids = json_decode($this->input->post('json_zone_ids'));
        //$channel_ids = json_decode($this->input->post('json_channels'));
        $channel_ids = json_decode($this->input->post('json_channel_ids'));

        $data['channel_ids'] = $channel_ids;
        $data['cluster_id'] = $cluster_id;
        $data['out_val'] = $this->input->post('out_val');
        $data['zone_val'] = $this->input->post('zone_val');

        $data['report_data'] = $this->Report_model->get_shelf_cluster_zones($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_ids, $channel_ids);
        $data['sum_metrage_array'] = $this->Report_model->get_total_metrage_by_zone($date_type, $start_date, $end_date, $category_id);
        $data['sum_metrage'] = array_sum(array_values($this->Report_model->get_total_metrage_by_zone($date_type, $start_date, $end_date, $category_id)));

        $this->load->view('reports/shelf_share/load_shelf_cluster_zones', $data);
    }

    function load_shelf_cluster_channels() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $date_type = $this->input->post('date_type');
        $category_id = $this->input->post('category_id');
        $cluster_id = $this->input->post('cluster_id');

        $zone_ids = json_decode(json_decode($this->input->post('json_zone_ids')));
        $channel_ids = json_decode($this->input->post('json_channel_ids'));

        $data['cluster_id'] = $cluster_id;
        $data['out_val'] = $this->input->post('out_val');
        $data['zone_val'] = $this->input->post('zone_val');
        $data['report_data'] = $this->Report_model->get_shelf_cluster_channels($date_type, $start_date, $end_date, $category_id, $cluster_id, $zone_ids, $channel_ids);
        $data['sum_metrage_array'] = $this->Report_model->get_total_metrage_by_channels($date_type, $start_date, $end_date, $category_id);
        $data['sum_metrage'] = array_sum(array_values($this->Report_model->get_total_metrage_by_channels($date_type, $start_date, $end_date, $category_id)));
        $this->load->view('reports/shelf_share/load_shelf_cluster_channels', $data);
    }

    function load_shelf_zone() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $date_type = $this->input->post('date_type');
        $category_id = $this->input->post('category_id');

        $zone_id = $this->input->post('zone_id');

        $channel_ids = json_decode($this->input->post('json_channel_ids'));
        $data['channel_ids'] = $channel_ids;
        $data['json_channel_ids'] = $this->input->post('json_channel_ids');

        $data['zone_id'] = $zone_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['category_id'] = $category_id;
        $data['date_type'] = $date_type;

        $data['zone_val'] = $this->input->post('zone_val');
        $data['out_val'] = $this->input->post('out_val');

        if ($category_id && $category_id != '-1' && $start_date && $end_date) {
            $data['clusters'] = $this->Cluster_model->get_clusters_by_category_without_others($category_id);
        }
        $data['report_data'] = $this->Report_model->get_shelf_multi_date_brand($date_type, $start_date, $end_date, $category_id, $zone_id, $channel_ids);
        $this->load->view('reports/shelf_share/load_shelf_zone', $data);
    }

    function load_shelf_channel() {

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $date_type = $this->input->post('date_type');
        $category_id = $this->input->post('category_id');
        $channel_id = $this->input->post('channel_id');
        $zone_id = $this->input->post('zone_id');

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['date_type'] = $date_type;
        $data['category_id'] = $category_id;
        $data['channel_id'] = $channel_id;

        $data['out_val'] = $this->input->post('out_val');
        $data['channel_val'] = $this->input->post('channel_val');

        if ($category_id && $category_id != '-1' && $start_date && $end_date) {
            $data['clusters'] = $this->Cluster_model->get_clusters_by_category_without_others($category_id);
        }

        $data['report_data'] = $this->Report_model->get_shelf_multi_date_brand($date_type, $start_date, $end_date, $category_id, $zone_id, $channel_id);
        $this->load->view('reports/shelf_share/load_shelf_channels', $data);
    }

    // End Shelf share reports
    // 
    // 
    // 
    // 
    // 
    // 
    // 
    // 
    // Daily Visit Report --- Boulbaba --- 05/02/2018

    function pos_stock_issues_report() {
        $data_header['page_title'] = 'Reports';
        $data_header['sub_title'] = 'POS Stock Issues Report';


        $date_type = $this->input->post('date_type');

        if ($date_type == 'month') {
            $start_date = $this->input->post('start_date_m');
            $end_date = $this->input->post('end_date_m');
            $data_header['sub_title'] = "POS Stock Issues Report | " . format_month($start_date) . ' --> ' . format_month($end_date);
        } else {
            $start_date = $this->input->post('start_date_w');
            $end_date = $this->input->post('end_date_w');
            $data_header['sub_title'] = "POS Stock Issues Report | " . format_week($start_date) . ' --> ' . format_week($end_date);
        }

        $data['categories'] = $this->Category_model->get_categories();
        $data['channels'] = $this->Channel_model->get_active_channels();

        $data['channel_id'] = $this->input->post('channel_id');
        $data['date_type'] = $date_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $this->load->view('header', $data_header);
        $this->load->view('reports/pos_stock_issues_report', $data);
        $this->load->view('footer');
    }

    function load_pos_stock_issues_category() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $category_id = $this->input->post('category_id');
        $date_type = $this->input->post('date_type');
        $channel_id = $this->input->post('channel_id');


        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['category_id'] = $category_id;
        $data['date_type'] = $date_type;

        $data['report_data'] = $this->Report_model->get_pos_stock_issues($date_type, $start_date, $end_date, $category_id, $channel_id);
        $this->load->view('reports/load_pos_stock_issues_category', $data);
    }

    function pos_shelf_share_report() {
        $data_header['page_title'] = 'Reports';
        $data_header['sub_title'] = 'POS Shelf Share Report';

        $date_type = $this->input->post('date_type');
        $start_date = ($this->input->post("start_date")) ? $this->input->post("start_date") : "";
        $end_date = ($this->input->post("end_date")) ? $this->input->post("end_date") : "";

        if ($date_type == 'month') {
            $start_date = $this->input->post('start_date_m');
            $end_date = $this->input->post('end_date_m');
        } else if ($date_type == 'week') {
            $start_date = $this->input->post('start_date_w');
            $end_date = $this->input->post('end_date_w');
        } else if ($date_type == 'quarter') {
            $year1 = $this->input->post('year1');
            $quarter1 = $this->input->post('quarter1');

            $year2 = $this->input->post('year2');
            $quarter2 = $this->input->post('quarter2');

            $start_date = $year1 . '-' . $quarter1;
            $end_date = $year2 . '-' . $quarter2;
        }

        $data['categories'] = $this->Category_model->get_categories();
        $data['channels'] = $this->Channel_model->get_active_channels();



        $data['channel_id'] = $this->input->post('channel_id');
        $data['date_type'] = $date_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $this->load->view('header', $data_header);
        $this->load->view('reports/pos_shelf_share_report', $data);
        $this->load->view('footer');
    }

    function load_pos_shelf_share_category() {
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $category_id = $this->input->post('category_id');
        $date_type = $this->input->post('date_type');
        $channel_id = $this->input->post('channel_id');


        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['category_id'] = $category_id;
        $data['date_type'] = $date_type;

        $data['report_data'] = $this->Report_model->get_pos_shelf_share($date_type, $start_date, $end_date, $category_id, $channel_id);
        $data['sum_metrage'] = $this->Report_model->get_total_metrage_by_outlets($date_type, $start_date, $end_date, $category_id, $channel_id);
        $this->load->view('reports/load_pos_shelf_share_category', $data);
    }

    function daily_visit_report() {

        $data_header['page_title'] = 'Reports';
        $data_header['sub_title'] = 'Daily visit Report';

        $data['excel'] = 0;

        $date = $this->input->post('date');
        $merch_id = $this->input->post('merch_id');
        $category_id = $this->input->post('category_id');
        $selected_channel_id = $this->input->post('selected_channel_id');
        $excel = $this->input->post('excel');

        $data['merchandisers'] = $this->auth->get_fo_list();
        $data['channels'] = $this->Channel_model->get_channels();
        $data['zones'] = $this->Zone_model->get_zones();
        $data['categories'] = $this->Category_model->get_categories();

        $data['date'] = $date;
        $data['merch_id'] = $merch_id;
        $data['excel'] = $excel;

        $data['selected_channel_id'] = $selected_channel_id;
        $data['category_id'] = $category_id;
        if ($date != '') {
            $data_header['sub_title'] = 'Daily visit Report' . ' | ' . reverse_format($date) . ' | ';
        } else {
            $data_header['sub_title'] = 'Daily visit Report' . ' | ' . reverse_format($date);
        }
        $data['report_data'] = $this->Report_model->get_av_daily_report($date, $merch_id, $selected_channel_id);
        if ($excel == 1) {
            $data['page_title'] = 'Reports';
            $data['sub_title'] = 'Daily visit Report';
            $this->load->view('reports/daily_visit_report', $data);
        } else {
            $this->load->view('header', $data_header);
            $this->load->view('reports/daily_visit_report', $data);
            $this->load->view('footer');
        }
    }

    // bcm
    function fo_performance() {

        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "FO performance";


        $date_type = $this->input->post('date_type');

        if ($date_type == 'month') {
            $start_date = $this->input->post('start_date_m');
            $end_date = $this->input->post('end_date_m');
        } else if ($date_type == 'week') {
            $start_date = $this->input->post('start_date_w');
            $end_date = $this->input->post('end_date_w');
        } else {
            $start_date = $this->input->post("start_date");
            $end_date = $this->input->post("end_date");
        }

        $multi_date = 0;
        if ($start_date != $end_date) {
            $multi_date = 1;
        }

        $data['multi_date'] = $multi_date;
        $data['date_type'] = $date_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['report_data'] = $this->Report_model->get_fo_performance($date_type, $start_date, $end_date);


        $this->load->view('header', $data_header);
        $this->load->view('fo_performance/fo_performance_report', $data);
        $this->load->view('footer');
    }

    function Routing_trend() {

        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "Routing Trend";

        $start_date = ($this->input->post('start_date')) ? $this->input->post("start_date") : date('Y-m-d');
        $end_date = ($this->input->post('end_date')) ? $this->input->post("end_date") : date('Y-m-d');
        $day = ($this->input->post('day')) ? $this->input->post("day") : date('Y-m-d');
        $data['day'] = $day;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $data['report_data'] = $this->Report_model->get_routing_trend($day, $start_date, $end_date);


        $data['excel'] = 0;
        $excel = $this->input->post('excel');
        $data['excel'] = $excel;

        if ($excel == 1) {
            $data['page_title'] = 'Reports';
            $data['sub_title'] = '"Routing Trend"';
            $this->load->view('reports/routing_trend_report', $data);
        } else {
            $this->load->view('header', $data_header);
            $this->load->view('fo_performance/routing_trend_report', $data);
            $this->load->view('footer');
        }
    }

// bcm 28/11/2018
    function routing_survey() {

        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "Weekly Planning";
        $start_date = ($this->input->post('start_date_w')) ? $this->input->post("start_date_w") : date('Y-m-d');
        $end_date = ($this->input->post('end_date_w')) ? $this->input->post("end_date_w") : date('Y-m-d');
        $fo_id = ($this->input->post('fo_id')) ? $this->input->post("fo_id") : -1;

        $fos = $this->auth->get_fo_list();
        $data['fos'] = $fos;

        $data['fo_id'] = $fo_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $data['report_data'] = $this->Report_model->get_routing_survey_data($fo_id, $start_date, $end_date);


        $this->load->view('header', $data_header);
        $this->load->view('fo_performance/routing_survey', $data);
        $this->load->view('footer');
    }

    function update_fo_information_type() {
        $save['id'] = $_POST['id'];

        $save['type'] = $_POST['type'];

        print_r($save);
        $this->Report_model->update_event($save);
    }

    function update_fo_information_fo_id() {
        $save['id'] = $_POST['id'];
        $save['fo_id'] = $_POST['fo_id'];
        print_r($save);
        $this->Report_model->update_event($save);
    }

    function fo_information_input() {

        $data['events'] = $this->Report_model->get_events_details();
        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "FO information";
        $fos = $this->auth->get_fo_list();
        $data['fos'] = $fos;

        $date = ($this->input->post("date")) ? $this->input->post("date") : "";
        $tab_date = explode(",", $date);
        $data['date'] = $tab_date;

        $fo_id = ($this->input->post('fo_id')) ? $this->input->post("fo_id") : "";
        $data['fo_id'] = $fo_id;

        $type = ($this->input->post('type')) ? $this->input->post("type") : "";
        $data['type'] = $type;


        $note = ($this->input->post('note')) ? $this->input->post("note") : "";
        $data['note'] = $note;
//        $date_of_insertion_day = date('Y-m-d');
//        foreach ($tab_date as $date) {
//            $save['date'] = $date_of_insertion_day;
//            $save['w_date'] = firstDayOf('week', new DateTime($date_of_insertion_day));
//            $save['m_date'] = firstDayOf('month', new DateTime($date_of_insertion_day));
//            $save['date_de_conge'] = $date;
//            $save['admin_id'] = $admin_id;
//            $save['fo_id'] = $fo_id;
//            $save['type'] = $type;
//            $save['note'] = $note;
//            $inserted_id = $this->Report_model->save_fo_information($save);
        //       }

        $this->load->view('header', $data_header);
        $this->load->view('fo_information/input', $data);
        $this->load->view('footer');
    }

    function update_comment_fo_information() {
//        $save_comment['id'] = $_POST['id'];
//        $save_comment['description'] = $_POST['coment_id'];
//
//        print_r($save_comment);
//        $this->Routing_model->save($save_comment);

        if ($this->input->is_ajax_request()) {

            $valueStr = $this->input->get_post('value') ? $this->input->get_post('value') : '';
            $new_nameStr = trim($valueStr);
            $result_arr['description'] = $new_nameStr;
            $data['id'] = $this->input->get_post('pk') ? $this->input->get_post('pk') : '';
            $data['note'] = $new_nameStr;
            $result_arr['description'] = $new_nameStr;
            $this->Report_model->update_event($data);
        }
        echo json_encode($result_arr);
        exit;
    }

    function save_fo_information() {


        $this->form_validation->set_rules('date', 'date', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', 'Date required !');
            redirect('/reports/fo_information_input');
        } else {
            $date = $this->input->post("date");
            $tab_date = explode(",", $date);

            $fo_id = $this->input->post('fo_id');

            $type = $this->input->post('type');

            $note = $this->input->post('note');

            $date_of_insertion_day = date('Y-m-d');
            foreach ($tab_date as $date) {
                $save['date'] = $date_of_insertion_day;
                $save['w_date'] = firstDayOf('week', new DateTime($date_of_insertion_day));
                $save['m_date'] = firstDayOf('month', new DateTime($date_of_insertion_day));
                $save['date_de_conge'] = $date;
                $save['admin_id'] = $this->connected_user_id;
                $save['fo_id'] = $fo_id;
                $save['type'] = $type;
                $save['note'] = $note;
                $inserted_id = $this->Report_model->save_fo_information($save);
            }

            redirect('/reports/fo_information_input');
            $this->session->set_flashdata('message', 'informations bien envoy�');
        }
    }

    public function get_events() {
        // Our Start and End Dates
        $events = $this->Report_model->get_events();

        $data_events = array();
        foreach ($events as $r) {
            $data_events[] = array(
                "id" => $r->id,
                "title" => reverse_format($r->date_de_conge),
                "description" => $r->note,
                "start" => $r->date_de_conge
            );
        }
        echo json_encode(array("events" => $data_events));
        exit();
    }

    public function load_tab() {
        $fos = $this->auth->get_fo_list();
        $data['fos'] = $fos;
        $date = ($this->input->post('date')) ? $this->input->post('date') : date('Y-m-d');
        $data['date_js'] = $date;
        $events = $this->Report_model->get_events_details_by_date($date);
        $data['events'] = $events;
        $this->load->view('fo_information/load_tab_output', $data);
    }

    function fo_information_output($date = false) {
        if ($date == false)
            $date = date('Y-m-d');
        $data['default_date'] = $date;
        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "FO information";
        $this->load->view('header', $data_header);
        $this->load->view('fo_information/output', $data);
        $this->load->view('footer');
    }

    function delete_fo_information($id, $date) {

        $this->Report_model->delete_event($id);

        redirect('/reports/fo_information_output/' . $date);
        $this->session->set_flashdata('message', 'informations bien supprim�es ');
    }

    //bcm
    function branding() {

        $data['page_title'] = "Reports";
        $data['sub_title'] = "Pictures report";

        $data['excel'] = $this->input->post('excel');

        $admins = $this->auth->get_fo_list();
        $data['zones'] = $this->Zone_model->get_zones();
        $data['outlets'] = $this->Outlet_model->get_outlets();

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $admin_id = $this->input->post('user_id');
        $data['user_id'] = $admin_id;

        $zone_id = $this->input->post('zone_id');
        $data['zone_id'] = $zone_id;

        $outlet_id = $this->input->post('outlet_id');
        $data['outlet_id'] = $outlet_id;

        $data['admins'] = $admins;

        $data['visits'] = $this->Report_model->get_branding_data($start_date, $end_date, $outlet_id, $admin_id, $zone_id);

        $this->load->view('branding_report', $data);
    }

    //bcm filtre branding 
    function get_outlet_by_zone_fo() {
        $zone_id = $this->input->post("zone_id");
        $fo_id = $this->input->post("admin_id");

        header('Content-Type: application/x-json; charset=utf-8');
        $outlets = array();
        $outlets[-1] = 'All outlets';
        foreach ($this->Report_model->get_outlet_by_zone_fo($zone_id, $fo_id) as $outlet) {
            $outlets[$outlet->id] = $outlet->name;
        }
        echo(json_encode($outlets));
    }

    //bcm
    function store_album() {

        $data['page_title'] = "Reports";
        $data['sub_title'] = "Pictures report";

        $admins = $this->auth->get_fo_list();
        $data['zones'] = $this->Zone_model->get_zones();
        $data['outlets'] = $this->Outlet_model->get_outlets();

        $admin_id = $this->input->post('user_id');
        $data['user_id'] = $admin_id;

        $zone_id = $this->input->post('zone_id');
        $data['zone_id'] = $zone_id;

        $outlet_id = $this->input->post('outlet_id');
        $data['outlet_id'] = $outlet_id;

        $data['admins'] = $admins;

        $data['excel'] = $this->input->post('excel');

        $data['visits'] = $this->Report_model->get_store_album($outlet_id, $admin_id, $zone_id);
        $this->load->view('store_album', $data);
    }

    function pos_oos_report() {

        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "POS OOS ";

        $multi_date = 0;
        $date_type = $this->input->post('date_type');

        if ($date_type == 'month') {
            $start_date = $this->input->post('start_date_m');
            $end_date = $this->input->post('end_date_m');
            $data_header['sub_title'] = "POS OOS " . format_month($start_date) . ' ' . format_month($end_date);
        } else {
            $start_date = $this->input->post('start_date_w');
            $end_date = $this->input->post('end_date_w');
            $data_header['sub_title'] = "POS OOS " . format_week($start_date) . ' ' . format_week($end_date);
        }

        if ($start_date != $end_date) {
            $multi_date = 1;
        }

        $categories = $this->Category_model->get_categories();
        $data['categories'] = $categories;

        $data['multi_date'] = $multi_date;
        $data['channels'] = $this->Channel_model->get_channels();

        $channel = $this->input->post('channel');
        $data['channel1'] = $channel;
        $data['date_type'] = $date_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        //$data['report_data'] = $this->Report_model->get_pos_oos($date_type, $start_date, $end_date, $channel);

        $this->load->view('header', $data_header);
        $this->load->view('reports/pos_oos_report', $data);
        $this->load->view('footer');
    }

//*********************************************************************************************************************************************
////***********************************************old**************************************************************************
    // Shelf share report 1er tab bcm
    // Load summary cross table grouped by channel / brand
    //rectifi� le 27/01
    function load_shelf_all_zones_group_by_channel_brand() {
        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $category_id = $this->input->post('category_id');
        $channel = $this->input->post('channel');

        if ($start_date != $end_date) {
            $multi_quarter = 1;
        } else {

            $multi_quarter = 0;
        }

        $data['multi_quarter'] = $multi_quarter;
        $data['date_type'] = $date_type;
        $data['channel'] = $channel;
        $data['category_id'] = $category_id;

        $data['report_data'] = $this->Report_model->load_shelf_group_by_channel_brand($date_type, $start_date, $end_date, $category_id, $channel);
        $data['total_metrage'] = $this->Report_model->get_total_metrage_henkel_by_channels($date_type, $start_date, $end_date, $category_id, $channel);

//$data['report_data2']=$this->Report_model->get_shelf_data($date_type,$start_date,$end_date,'-1',$cluster_id,$category_id,$activity,$super_market_project);
        $this->load->view('reports/shelf_share/load_shelf_brand_channel', $data);
    }

    // Shelf share report 2eme tab par clauster bcm
    // Load for each cluster cross table grouped by channel / product
    //le 27/01
    function load_shelf_all_zones_group_by_channel_product() {

        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $category_id = $this->input->post('category_id');
        $cluster_id = $this->input->post('cluster_id');
        $channel = $this->input->post('channel');

        $data['date_type'] = $date_type;
        $data['channel'] = $channel;

        $data['report_data'] = $this->Report_model->load_shelf_all_zones_group_by_channel_product($date_type, $start_date, $end_date, $cluster_id, $category_id, $channel);
        $data['total_metrage'] = $this->Report_model->get_total_metrage_henkel_by_channels($date_type, $start_date, $end_date, $category_id, $channel);

        $this->load->view('reports/shelf_share/load_channel_product_report', $data);
    }

    // Shelf share report 1er tab bcm
    // Load summary cross table grouped by zone / brand
    function load_shelf_all_brand_zones() {

        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $zone_id = $this->input->post('zone_id');

        $category_id = $this->input->post('category_id');
        //$channel = $this->input->post('channel');

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;

        $data['report_data'] = $this->Report_model->get_shelf_brand_zone($date_type, $start_date, $end_date, $zone_id, $category_id);
        //$data['total_metrage'] = $this->Report_model->get_total_metrage_henkel_by_zone($date_type, $start_date, $end_date, $category_id, $zone_id);
        $data['total_metrage'] = array_sum($this->Report_model->get_total_metrage_henkel_by_zone($date_type, $start_date, $end_date, $category_id, $zone_id));
        $this->load->view('reports/shelf_share/load_shelf_all_brand_zones', $data);
    }

    // rectifi� le 17/01/2018 bcm 2eme tab 
    //2eme modif le 27/01
    function load_shelf_all_product_zones() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $date_type = $_POST['date_type'];

        $category_id = $_POST['category_id'];
        $zone_id = $_POST['zone_id'];
        //$activity = $_POST['activity'];
        $cluster_id = $_POST['cluster_id'];
        //$channel = $_POST['channel'];
        //$super_market_project = $_POST['super_market_project'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;

        $data['report_data'] = $this->Report_model->get_shelf_all_product_zones($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id);
        $data['total_metrage'] = $this->Report_model->get_total_metrage_henkel_by_zone($date_type, $start_date, $end_date, $category_id, $zone_id);

        $this->load->view('reports/shelf_share/load_shelf_all_product_zones', $data);
    }

    //le 27/01 
    function load_shelf_one_date() {
        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $category_id = $this->input->post('category_id');

        if ($start_date != $end_date) {
            $multi_quarter = 1;
        } else {

            $multi_quarter = 0;
        }

        $data['multi_quarter'] = $multi_quarter;
        $data['date_type'] = $date_type;
        $data['category_id'] = $category_id;

        $data['report_data'] = $this->Report_model->load_shelf_group_by_one_date($date_type, $start_date, $end_date, $category_id);
        //$data['total_metrage'] = $this->Report_model->get_total_metrage_henkel_by_channels($date_type, $start_date, $end_date, $category_id, $channel);
        //$data['report_data2']=$this->Report_model->get_shelf_data($date_type,$start_date,$end_date,'-1',$cluster_id,$category_id,$activity,$super_market_project);
        $this->load->view('reports/shelf_share/load_shelf_brand_one_date', $data);
    }

    // rectifi� le 25/01/2018 bcm chart (aucun filtre/one date)
    function load_shelf_brand_data_chart_0_0_0() {

        $date_type = $_POST['date_type'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $category_id = $_POST['category_id'];
        $multi_date = $_POST['multi_date'];
        $data['date_type'] = $date_type;

        $data['brand_data'] = $this->Report_model->get_brand_for_shelf_0_0_0($date_type, $start_date, $end_date, $category_id);
        $this->load->view('reports/shelf_share/chart_0_0_0', $data);
    }

    // rectifi� le 25/01/2018 bcm chart (aucun filtre/multi date )
    //a verifier
    function load_shelf_brand_data_chart_0_0_1() {

        $date_type = $_POST['date_type'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $multi_date = $_POST['multi_date'];

        $category_id = $_POST['category_id'];

        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);

        $date1 = ceil(date('m', $date1) / 3);
        $date2 = ceil(date('m', $date2) / 3);

        if ($date1 != $date2) {

            $multi_quarter = 1;
            $data['multi_quarter'] = $multi_quarter;

            // a verifier
            $result = $this->Report_model->get_brand_for_shelf_0_0_1($start_date, $end_date, $date_type, $category_id);
            $data['brand_data'] = json_encode(array_reverse($result['data']));
            $data['brands'] = $result['brands'];
            $this->load->view('reports/shelf_share/chart_multi_quarter_0_0_1', $data);
        } else {

            $multi_quarter = 0;
            $data['multi_quarter'] = $multi_quarter;
            $data['brand_data'] = $this->Report_model->get_brand_for_shelf_0_0_0($date_type, $start_date, $end_date, $category_id);
            $this->load->view('reports/shelf_share/chart_one_quarter_0_0_1', $data);
        }
    }

    // rectifi� le 25/01/2018 bcm chart (filtre zone)
    function load_shelf_brand_data_chart_0_1_0() {

        $date_type = $_POST['date_type'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $zone_id = $_POST['zone_id'];

        $category_id = $_POST['category_id'];
        $multi_date = $_POST['multi_date'];

        //$channel = $_POST['channel'];
        //$activity = $_POST['activity'];
        //$super_market_project = $_POST['super_market_project'];
        //echo $start_date.'***'.$end_date.'**'.$date_type;

        $data['zone_id'] = $zone_id;
        $data['zn_id'] = $zone_id;
        //$data['channel'] = $channel;

        if ($multi_date == 0) {
            //rectifi� le 25/01
            $data['brand_data'] = $this->Report_model->get_brand_single_zone_for_shelf_d0_z1_c0($date_type, $start_date, $end_date, $zone_id, $category_id);
            //$data['brand_data'] = $this->Report_model->get_brand_single_date_json_data_for_shelf($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel);

            $this->load->view('reports/shelf_share/chart_0_1_0', $data);
        } else {
            $result = $this->Report_model->get_brand_single_zone_for_shelf_d0_z1_c0($date_type, $start_date, $end_date, $zone_id, $category_id);
            $data['brand_data'] = json_encode(array_reverse($result['data']));
            $data['brands'] = $result['brands'];
            $this->load->view('reports/shelf_share/load_av_all_zones_brand_multiple_date_chart', $data);
        }
    }

    // rectifi� le 25/01/2018 bcm chart (filtre zone) faux
    function load_shelf_brand_data_chart() {

        $date_type = $_POST['date_type'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $zone_id = $_POST['zone_id'];
        $category_id = $_POST['category_id'];

        $multi_date = $_POST['multi_date'];
        //$channel = $_POST['channel'];
        $activity = $_POST['activity'];
        $super_market_project = $_POST['super_market_project'];
        //echo $start_date.'***'.$end_date.'**'.$date_type;

        $data['zone_id'] = $zone_id;
        $data['zn_id'] = $zone_id;
        //$data['channel'] = $channel;

        if ($multi_date == 0) {
            //rectifi� le 25/01
            $data['brand_data'] = $this->Report_model->get_brand_single_zone_for_shelf($date_type, $start_date, $end_date, $zone_id, $category_id);
            //$data['brand_data'] = $this->Report_model->get_brand_single_date_json_data_for_shelf($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel);

            $this->load->view('reports/shelf_share/load_av_all_zones_brand_single_date_chart', $data);
        } else {
            $result = $this->Report_model->get_brand_multiple_date_data_for_shelf($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel);
            $data['brand_data'] = json_encode(array_reverse($result['data']));
            $data['brands'] = $result['brands'];
            $this->load->view('reports/shelf_share/load_av_all_zones_brand_multiple_date_chart', $data);
        }
    }

    // rectifi� le 25/01/2018 bcm chart (filtre channel)
    function load_shelf_brand_data_chart_by_channel() {
        $date_type = $_POST['date_type'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $channel = $_POST['channel'];
        $category_id = $_POST['category_id'];
        $multi_date = $_POST['multi_date'];

        $data['category_id'] = $category_id;
        $data['channel'] = $channel;

        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);

        $date1 = ceil(date('m', $date1) / 3);
        $date2 = ceil(date('m', $date2) / 3);
        if ($date1 != $date2) {
            $multi_quarter = 1;
        } else {

            $multi_quarter = 0;
        }

        $data['multi_quarter'] = $multi_quarter;

        if ($multi_date == 0) {
            $data['zn_id'] = "all";
            $data['channel'] = $channel;

            $data['brand_data'] = $this->Report_model->get_brand_single_channel_for_shelf($date_type, $start_date, $end_date, $category_id, $channel);
            $this->load->view('reports/shelf_share/chart_all_z0_ch1_d0', $data);
        } else {
            $result = $this->Report_model->get_brand_multiple_date_data_for_shelf($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel);
            $data['brand_data'] = json_encode(array_reverse($result['data']));
            $data['brands'] = $result['brands'];
            $this->load->view('reports/shelf_share/load_av_all_zones_brand_multiple_date_chart', $data);
        }
    }

    //rectifi� le 25/01 shlef bcm 2eme tab one date and category 
    function load_shelf_all_zones_group_by_product_group_one_date() {
        $date_type = $_POST['date_type'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $cluster_id = $_POST['cluster_id'];
        $category_id = $_POST['category_id'];

        $data['cluster_id'] = $cluster_id;
        $data['category_id'] = $category_id;
        $data['date_type'] = $date_type;

        $data['report_data'] = $this->Report_model->get_shelf_product_group_one_date_by_categorie($date_type, $start_date, $end_date, $cluster_id, $category_id);
        $data['sum_metrage'] = $this->Report_model->get_total_metrage_henkel_by_one_day($date_type, $start_date, $end_date, $category_id);
        $this->load->view('reports/shelf_share/load_all_zones_product_report', $data);
    }

    //bcm shelf 1er tab filtre zone/multi date 
    //rectifi� le 27/01
    function load_shelf_one_zones_group_by_brand_date_1_1_0() {
        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $cluster_id = $this->input->post('cluster_id');
        $zone_id = $this->input->post('zone_id');
        $category_id = $this->input->post('category_id');

        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);

        $date1 = ceil(date('m', $date1) / 3);
        $date2 = ceil(date('m', $date2) / 3);

        if ($date1 != $date2) {
            $multi_quarter = 1;

            $data['multi_quarter'] = $multi_quarter;
            $data['cluster_id'] = $cluster_id;
            $data['date_type'] = $date_type;
            $data['zn_id'] = $zone_id;

            $data['report_data'] = $this->Report_model->get_shelf_data_group_by_brand_1_1_0($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id);
            $this->load->view('reports/shelf_share/load_shelf_one_zone_multi_quarter_1_1_0', $data);
        } else {

            $multi_quarter = 0;

            $data['multi_quarter'] = $multi_quarter;
            $data['cluster_id'] = $cluster_id;
            $data['date_type'] = $date_type;
            $data['zn_id'] = $zone_id;

            $data['report_data'] = $this->Report_model->get_shelf_data_group_by_brand_1_1_0($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id);
            $this->load->view('reports/shelf_share/load_shelf_one_zone_one_quarter_1_1_0', $data);
        }
    }

    //bcm 2eme tab filtre zone/multi date 
    //le 28/01
    function load_shelf_one_zones_group_by_brand_product_1_1_0() {

        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $category_id = $this->input->post('category_id');
        $zone_id = $this->input->post('zone_id');
        $cluster_id = $this->input->post('cluster_id');

        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);

        $date1 = ceil(date('m', $date1) / 3);
        $date2 = ceil(date('m', $date2) / 3);

        if ($date1 != $date2) {
            $multi_quarter = 1;

            $data['multi_quarter'] = $multi_quarter;
            $data['cluster_id'] = $cluster_id;
            $data['date_type'] = $date_type;
            $data['zn_id'] = $zone_id;

            $data['report_data'] = $this->Report_model->get_shelf_data_group_by_brand_product_1_1_0($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id);
            $data['sum_metrage'] = $this->Report_model->get_total_metrage_group_by_brand_1_1_0($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id);
            $this->load->view('reports/shelf_share/load_shelf_one_zone_multi_quarter_product_1_1_0', $data);
        } else {

            $multi_quarter = 0;

            $data['multi_quarter'] = $multi_quarter;
            $data['cluster_id'] = $cluster_id;
            $data['date_type'] = $date_type;
            $data['zn_id'] = $zone_id;

            $data['report_data'] = $this->Report_model->get_shelf_data_group_by_brand_product_1_1_0($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id);
            $data['sum_metrage'] = $this->Report_model->get_total_metrage_group_by_brand_1_1_0($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id);
            $this->load->view('reports/shelf_share/load_shelf_one_zone_one_quarter_product_1_1_0', $data);
        }
    }

    // rectifi� le 27/01/2018 
    // bcm chart zone=1 channel=0 date=1 
    function load_shelf_brand_data_chart_1_1_0() {

        $date_type = $_POST['date_type'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $zone_id = $_POST['zone_id'];
        $category_id = $_POST['category_id'];
        $multi_date = $_POST['multi_date'];
        $data['zone_id'] = $zone_id;
        $data['zn_id'] = $zone_id;

        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);

        $date1 = ceil(date('m', $date1) / 3);
        $date2 = ceil(date('m', $date2) / 3);

        if ($date1 != $date2) {

            $multi_quarter = 1;
            $data['multi_quarter'] = $multi_quarter;

            // a verifier
            $result = $this->Report_model->get_brand_for_shelf_0_0_1($start_date, $end_date, $date_type, $category_id);
            $data['brand_data'] = json_encode(array_reverse($result['data']));
            $data['brands'] = $result['brands'];
            $this->load->view('reports/shelf_share/chart_multi_quarter_0_0_1', $data);
        } else {

            $multi_quarter = 0;
            $data['multi_quarter'] = $multi_quarter;
            $data['brand_data'] = $this->Report_model->get_brand_for_shelf_1_1_0($date_type, $start_date, $end_date, $category_id, $zone_id);
            $this->load->view('reports/shelf_share/chart_one_quarter_1_1_0', $data);
        }
    }

    //bcm shelf 1er tab filtre channel/multi date 
    //rectifi� le 29/01
    function load_shelf_one_channel_group_by_brand_date_0_1_1() {
        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $cluster_id = $this->input->post('cluster_id');
        $channel = $this->input->post('channel');
        $category_id = $this->input->post('category_id');

        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);

        $date1 = ceil(date('m', $date1) / 3);
        $date2 = ceil(date('m', $date2) / 3);

        if ($date1 != $date2) {
            $multi_quarter = 1;

            $data['multi_quarter'] = $multi_quarter;
            $data['cluster_id'] = $cluster_id;
            $data['date_type'] = $date_type;
            $data['channel'] = $channel;

            $data['report_data'] = $this->Report_model->get_shelf_data_group_by_brand_0_1_1($date_type, $start_date, $end_date, $channel, $cluster_id, $category_id);
            $this->load->view('reports/shelf_share/load_shelf_one_channel_multi_quarter_0_1_1', $data);
        } else {

            $multi_quarter = 0;

            $data['multi_quarter'] = $multi_quarter;
            $data['cluster_id'] = $cluster_id;
            $data['date_type'] = $date_type;
            $data['channel'] = $channel;

            $data['report_data'] = $this->Report_model->get_shelf_data_group_by_brand_0_1_1($date_type, $start_date, $end_date, $channel, $cluster_id, $category_id);
            $this->load->view('reports/shelf_share/load_shelf_one_channel_one_quarter_0_1_1', $data);
        }
    }

    //bcm 2eme tab filtre channel/multi date 
    //le 29/01
    function load_shelf_one_chart_group_by_brand_product_0_1_1() {

        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $category_id = $this->input->post('category_id');
        $channel = $this->input->post('channel');
        $cluster_id = $this->input->post('cluster_id');

        $data['channel'] = $channel;
        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);

        $date1 = ceil(date('m', $date1) / 3);
        $date2 = ceil(date('m', $date2) / 3);

        if ($date1 != $date2) {
            $multi_quarter = 1;

            $data['multi_quarter'] = $multi_quarter;
            $data['cluster_id'] = $cluster_id;
            $data['date_type'] = $date_type;

            $data['report_data'] = $this->Report_model->get_shelf_data_group_by_brand_product_0_1_1($date_type, $start_date, $end_date, $channel, $cluster_id, $category_id);
            $data['sum_metrage'] = $this->Report_model->get_total_metrage_group_by_brand_0_1_1($date_type, $start_date, $end_date, $channel, $cluster_id, $category_id);
            $this->load->view('reports/shelf_share/load_shelf_one_channel_multi_quarter_product_0_1_1', $data);
        } else {

            $multi_quarter = 0;

            $data['multi_quarter'] = $multi_quarter;
            $data['cluster_id'] = $cluster_id;
            $data['date_type'] = $date_type;

            $data['report_data'] = $this->Report_model->get_shelf_data_group_by_brand_product_0_1_1($date_type, $start_date, $end_date, $channel, $cluster_id, $category_id);
            $data['sum_metrage'] = $this->Report_model->get_total_metrage_group_by_brand_0_1_1($date_type, $start_date, $end_date, $channel, $cluster_id, $category_id);
            $this->load->view('reports/shelf_share/load_shelf_one_channel_one_quarter_product_0_1_1', $data);
        }
    }

    // rectifi� le 29/01/2018 
    // bcm chart zone=0 channel=1 date=1 
    function load_shelf_brand_data_chart_0_1_1() {

        $date_type = $_POST['date_type'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $channel = $_POST['channel'];
        $category_id = $_POST['category_id'];
        $multi_date = $_POST['multi_date'];

        $data['channel'] = $channel;
        //$data['zn_id'] = $zone_id;

        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);

        $date1 = ceil(date('m', $date1) / 3);
        $date2 = ceil(date('m', $date2) / 3);

        if ($date1 != $date2) {

            $multi_quarter = 1;
            $data['multi_quarter'] = $multi_quarter;

            // a verifier
            $result = $this->Report_model->get_brand_for_shelf_0_0_1($start_date, $end_date, $date_type, $category_id);
            $data['brand_data'] = json_encode(array_reverse($result['data']));
            $data['brands'] = $result['brands'];
            $this->load->view('reports/shelf_share/chart_multi_quarter_0_0_1', $data);
        } else {

            $multi_quarter = 0;
            $data['multi_quarter'] = $multi_quarter;
            $data['brand_data'] = $this->Report_model->get_brand_for_shelf_0_1_1($date_type, $start_date, $end_date, $category_id, $channel);
            $this->load->view('reports/shelf_share/chart_one_quarter_0_1_1', $data);
        }
    }

    //le 27/01 zone=0 chan=0 date=1 1er tab
    function load_shelf_multi_date_0_0_1() {
        $date_type = $this->input->post('date_type');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        $category_id = $this->input->post('category_id');

        $data['date_type'] = $date_type;
        $data['category_id'] = $category_id;

        /*
          if ($start_date != $end_date) {
          $multi_quarter = 1;
          } else {

          $multi_quarter = 0;
          }
         */
        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);

        $date1 = ceil(date('m', $date1) / 3);
        $date2 = ceil(date('m', $date2) / 3);
        /*
          $quarters = $this->Report_model->get_quarter($date_type, $start_date, $end_date);
          $data['quarter'] = $quarters;
          $test = array();
          $count_date = 0;
          foreach ($quarters as $row) {
          //echo 'date'.$row['date'];
          //echo '<br>';
          $date = $row->quarter;
          if (!in_array($date, $test)) {
          $test[] = $date;
          $count_date += 1;
          }
          }
          //echo 'quarter';
          //echo $quarter;
          $data['date_q'] = $test;
         */

        $data['report_data'] = $this->Report_model->load_shelf_group_by_one_date($date_type, $start_date, $end_date, $category_id);

        if ($date1 != $date2) {
            $multi_quarter = 1;
            $data['multi_quarter'] = $multi_quarter;
            $this->load->view('reports/shelf_share/load_shelf_brand_multi_date', $data);
        } else {

            $multi_quarter = 0;
            $data['multi_quarter'] = $multi_quarter;
            $this->load->view('reports/shelf_share/load_shelf_brand_one_date', $data);
        }

        //echo $multi_quarter;
        //$data['total_metrage'] = $this->Report_model->get_total_metrage_henkel_by_channels($date_type, $start_date, $end_date, $category_id, $channel);
    }

    //zone=0 chart=0 date=1 2eme tab par cluster
    //multi date
    function load_shelf_group_by_date_poduct_0_0_1() {
        $date_type = $_POST['date_type'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        $category_id = $_POST['category_id'];
        $cluster_id = $_POST['cluster_id'];

        $data['date_type'] = $date_type;


        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);

        $date1 = ceil(date('m', $date1) / 3);
        $date2 = ceil(date('m', $date2) / 3);

        //$data['report_data'] = $this->Report_model->load_shelf_group_by_one_date($date_type, $start_date, $end_date, $category_id);

        if ($date1 != $date2) {

            $multi_quarter = 1;

            $data['multi_quarter'] = $multi_quarter;

            $data['report_data'] = $this->Report_model->get_shelf_product_group_one_date_by_categorie($date_type, $start_date, $end_date, $cluster_id, $category_id);
            $data['sum_metrage'] = $this->Report_model->get_total_metrage_henkel_by_one_day($date_type, $start_date, $end_date, $category_id);
            $this->load->view('reports/shelf_share/load_shelf_date_poduct_0_0_1', $data);
        } else {

            $multi_quarter = 0;

            $data['report_data'] = $this->Report_model->get_shelf_product_group_one_date_by_categorie($date_type, $start_date, $end_date, $cluster_id, $category_id);
            $data['sum_metrage'] = $this->Report_model->get_total_metrage_henkel_by_one_day($date_type, $start_date, $end_date, $category_id);
            $this->load->view('reports/shelf_share/load_all_zones_product_report', $data);
        }
    }

    //
    function fo_performance_old() {

        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "FO performance";

        $multi_date = 0;

        $date_type = $this->input->post('date_type');

        if ($date_type == 'month') {
            $start_date = $this->input->post('start_date_m');
            $end_date = $this->input->post('end_date_m');
        } else {
            $start_date = $this->input->post('start_date_w');
            $end_date = $this->input->post('end_date_w');
        }

        if ($start_date != $end_date) {
            $multi_date = 1;
        }

        $data['multi_date'] = $multi_date;

        $data['date_type'] = $date_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['report_data'] = $this->Report_model->get_fo_performance($date_type, $start_date, $end_date);


        $this->load->view('header', $data_header);

        $this->load->view('reports/fo_performance_report', $data);
        $this->load->view('footer');
    }

    function index() {
        $data = $this->Report_model->get_brand_single_date_json_data_for_shelf('-1', '2017-09-01', '2017-09-01', 'month', '-1', '-1', '-1', '-1');
        print_r($data);
    }

    // Tracking Shelf share visits reports
    function tracking_visits_report() {

        $data['page_title'] = "Reports";
        $data['sub_title'] = "Tracking Visits report";

        $data['merchandisers'] = $this->auth->get_fo_list();
        $merch_id = $this->input->post('merch_id');
        $data['merch_id'] = $merch_id;

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        if ($start_date != '' && $end_date != '') {
            $data['visited_outlets'] = $this->Report_model->get_tracking_visited_data($start_date, $end_date, $merch_id);
            $data['unvisited_outlets'] = $this->Report_model->get_tracking_unvisited_data($start_date, $end_date, $merch_id);
        } else {
            $data['visited_outlets'] = array();
            $data['unvisited_outlets'] = array();
        }
        $this->load->view('tracking_visits_report', $data);
    }

    // Stock issues report
    function stock_issues_report_old() {

        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "Stock issues report";

        $multi_date = 0;
        $multi_zone = 0;
        $multi_channel = 0;
        $date_type = $this->input->post('date_type');
        $super_market_project = '-1';
        $category_id = $this->input->post('category_id');
        $selected_zone = $this->input->post('selected_zone');
        $activity = '-1';
        $zones = $this->Zone_model->get_zones();
        $selected_channel = $this->input->post('selected_channel');
        $data['channels'] = $this->Channel_model->get_channels();
        if ($date_type == 'month') {
            $start_date = $this->input->post('start_date_m');
            $end_date = $this->input->post('end_date_m');
        } else {
            $start_date = $this->input->post('start_date_w');
            $end_date = $this->input->post('end_date_w');
        }

        if ($start_date != $end_date) {
            $multi_date = 1;
        }
        if (!empty($selected_zone)) {
            $multi_zone = 1;
        }

        if (!empty($selected_channel)) {
            $multi_channel = 1;
        }

        $data['super_market_project'] = $super_market_project;
        $data['multi_date'] = $multi_date;
        $data['multi_zone'] = $multi_zone;
        $data['multi_channel'] = $multi_channel;
        $data['date_type'] = $date_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['category_id'] = $category_id;
        $data['activity'] = $this->input->post('activity');
        $data['selected_zone'] = $selected_zone;
        $data['selected_channel'] = $selected_channel;
        $data['zones'] = $zones;
        $data['categories'] = $this->Category_model->get_categories();
        if ($category_id && $start_date && $end_date) {
            $data['clusters'] = $this->Cluster_model->get_clusters_by_category_without_others($category_id);
        }

        $data['henkel_data'] = $this->Report_model->get_brand_single_date_json_data_henkel2(-1, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $selected_channel);
        //  $data['brand_data'] = $this->Report_model->get_brand_single_date_json_data_channel(-1, $start_date, $end_date, $date_type, $category_id, -1, -1, $selected_channel, -1);
        $data['report_data2'] = $this->Report_model->get_stock_issues_data3($date_type, $start_date, $end_date, $selected_zone, '-1', $category_id, $activity, '-1');
        $this->load->view('header', $data_header);
        $this->load->view('chart_header');
        $this->load->view('reports/stock_issues/stock_issues_report', $data);
        $this->load->view('footer');
    }

    function load_outlets() {

        $user_id = $_POST['user_id'];
        $channel_id = $_POST['channel_id'];

        $data['outlets'] = $this->Report_model->get_outlets_by_admin_zone($user_id, $channel_id);

        $this->load->view('reports/stock_issues/load_outlets', $data);
    }

    //bcm
    function pos_data() {
        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "POS data report";

        //$date_type = $this->input->post('date_type');
        $start_date = ($this->input->post('start_date')) ? $this->input->post('start_date') : '';
        $end_date = ($this->input->post('end_date')) ? $this->input->post('end_date') : '';
        $channel_id = ($this->input->post('channel_id')) ? $this->input->post('channel_id') : -1;
        $user_id = ($this->input->post('user_id')) ? $this->input->post('user_id') : -1;
        $outlet_id = ($this->input->post('outlet_id')) ? $this->input->post('outlet_id') : -1;

        $categories = $this->Category_model->get_categories();
        $category_id = $this->input->post('category_id');
        $data['category_id'] = $category_id;
        $data['categories'] = $categories;

        $channels = $this->Channel_model->get_channels();
        $outlets = $this->Outlet_model->get_active_outlets();
        $admins = $this->auth->get_fo_list();

        //$data['date_type'] = $date_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $data['channel_id'] = $channel_id;
        $data['user_id'] = $user_id;
        $data['outlet_id'] = $outlet_id;

        $data['admins'] = $admins;
        $data['outlets'] = $outlets;
        $data['channels'] = $channels;

        $data['report_data'] = $this->Report_model->get_pos_data($outlet_id, $start_date, $end_date);
        $this->load->view('header', $data_header);

        $this->load->view('reports/pos_data_report', $data);
        $this->load->view('footer');
    }

    //filtre bcm pos_data
    function get_outlet_by_fo_channel() {

        $channel_id = $this->input->post("channel_id");
        $fo_id = $this->input->post("fo_id");
        header('Content-Type: application/x-json; charset=utf-8');
        $outlets = array();
        foreach ($this->Report_model->get_outlet_by_channel_fo($channel_id, $fo_id) as $outlet) {
            $outlets[$outlet->id] = $outlet->name;
        }
        echo(json_encode($outlets));
    }

    function load_stock_issues_all_zones() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = $_POST['zone_id'];
        $activity = $_POST['activity'];
        $super_market_project = $_POST['super_market_project'];

        $cluster_id = $_POST['cluster_id'];
        //$data['dates']=array('2017-05-01','2017-06-01');
        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;
        $data['report_data'] = $this->Report_model->get_stock_issues_data($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project);
        $this->load->view('reports/stock_issues/load_all_zones_report', $data);
    }

    function load_stock_issues_multi_zones() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = $_POST['zone_id'];
        $activity = $_POST['activity'];
        $super_market_project = $_POST['super_market_project'];

        $cluster_id = $_POST['cluster_id'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;
        $data['report_data'] = $this->Report_model->get_stock_issues_data($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project);
        $this->load->view('reports/stock_issues/load_all_zones_report', $data);
    }

    function load_av_oos_brand() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = ($_POST['zone_id']);
        $activity = $_POST['activity'];
        $super_market_project = $_POST['super_market_project'];
        $cluster_id = $_POST['cluster_id'];



        $data['cluster_id'] = $_POST['cluster_id'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;
        $data['report_data'] = $this->Report_model->get_av_oos_brand($date_type, $start_date, $end_date, ($zone_id), $category_id, $activity, $super_market_project, $cluster_id);
        $this->load->view('reports/stock_issues/load_av_oos_brand', $data);
    }

    function load_av_oos_product() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = ($_POST['zone_id']);
        $activity = $_POST['activity'];
        $super_market_project = $_POST['super_market_project'];
        $cluster_id = $_POST['cluster_id'];



        $data['cluster_id'] = $_POST['cluster_id'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;
        $data['report_data'] = $this->Report_model->get_av_oos_brand($date_type, $start_date, $end_date, ($zone_id), $category_id, $activity, $super_market_project, $cluster_id);
        $this->load->view('reports/stock_issues/load_av_oos_product', $data);
    }

    function load_av_oos_brand_channel() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = ($_POST['zone_id']);
        $activity = $_POST['activity'];
        $channel = $_POST['channel'];
        $cluster_id = $_POST['cluster_id'];
        $super_market_project = $_POST['super_market_project'];



        //$cluster_id =$_POST['cluster_id'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;


        // die();
        $data['report_data'] = $this->Report_model->get_av_oos_brand_channel($date_type, $start_date, $end_date, ($zone_id), $category_id, $activity, $super_market_project, $channel, $cluster_id);
        $this->load->view('reports/stock_issues/load_av_oos_brand_channel', $data);
    }

    function load_av_oos_brand_channel_cluster() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = ($_POST['zone_id']);
        $activity = $_POST['activity'];
        $channel = $_POST['channel'];
        $cluster_id = $_POST['cluster_id'];
        $super_market_project = $_POST['super_market_project'];



        //$cluster_id =$_POST['cluster_id'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;


        // die();
        $data['report_data'] = $this->Report_model->get_av_oos_product_channel($date_type, $start_date, $end_date, ($zone_id), $category_id, $activity, $super_market_project, $channel, $cluster_id);
        $this->load->view('reports/stock_issues/load_av_oos_brand_channel_cluster', $data);
    }

    function load_av_oos_channel() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = ($_POST['zone_id']);
        $activity = $_POST['activity'];
        $super_market_project = $_POST['super_market_project'];



        //$cluster_id =$_POST['cluster_id'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;
        $data['report_data'] = $this->Report_model->get_av_oos_channel($date_type, $start_date, $end_date, ($zone_id), $category_id, $activity, $super_market_project);
        $this->load->view('reports/stock_issues/load_av_oos_channel', $data);
    }

    function load_av_oos_by_channel() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = ($_POST['zone_id']);
        $activity = $_POST['activity'];
        $channel = $_POST['channel'];
        $super_market_project = $_POST['super_market_project'];



        //$cluster_id =$_POST['cluster_id'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;
        $data['report_data'] = $this->Report_model->get_av_oos_by_channel($date_type, $start_date, $end_date, ($zone_id), $category_id, $activity, $super_market_project, $channel);
        $this->load->view('reports/stock_issues/load_av_oos_channel', $data);
    }

    function load_av_oos_zone_cluster() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = ($_POST['zone_id']);
        $activity = $_POST['activity'];
        $super_market_project = $_POST['super_market_project'];
        $cluster_id = $_POST['cluster_id'];


        //$cluster_id =$_POST['cluster_id'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;
        $data['report_data'] = $this->Report_model->get_av_oos_zone_cluster($date_type, $start_date, $end_date, ($zone_id), $category_id, $activity, $super_market_project, $cluster_id);

        $this->load->view('reports/stock_issues/load_av_oos_product', $data);
    }

    function load_av_oos_zone() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = ($_POST['zone_id']);
        $activity = $_POST['activity'];
        $super_market_project = $_POST['super_market_project'];
        $cluster_id = $_POST['cluster_id'];


        //$cluster_id =$_POST['cluster_id'];
        $data['cluster_id'] = $cluster_id;
        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;


        $data['report_data'] = $this->Report_model->get_av_oos_zone($date_type, $start_date, $end_date, ($zone_id), $category_id, $activity, $super_market_project);

        $this->load->view('reports/stock_issues/load_av_oos_brand', $data);
    }

    function load_av_oos_zone_channel() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = ($_POST['zone_id']);
        $activity = $_POST['activity'];
        $channel = $_POST['channel'];
        $super_market_project = $_POST['super_market_project'];

        $cluster_id = $_POST['cluster_id'];

        $data['cluster_id'] = $_POST['cluster_id'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;
        $data['report_data'] = $this->Report_model->get_av_oos_zone_channel($date_type, $start_date, $end_date, ($zone_id), $category_id, $activity, $super_market_project, $channel);

        $this->load->view('reports/stock_issues/load_av_oos_brand', $data);
    }

    function load_av_oos_zone_channel_cluster() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = ($_POST['zone_id']);
        $activity = $_POST['activity'];
        $channel = $_POST['channel'];
        $cluster_id = $_POST['cluster_id'];
        $super_market_project = $_POST['super_market_project'];



        //$cluster_id =$_POST['cluster_id'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;
        $data['report_data'] = $this->Report_model->get_av_oos_zone_channel_cluster($date_type, $start_date, $end_date, ($zone_id), $category_id, $activity, $super_market_project, $channel, $cluster_id);

        $this->load->view('reports/stock_issues/load_av_oos_product', $data);
    }

    function routing_report($user_id) {
        $data['page_title'] = 'Reports';
        $data['sub_title'] = 'Routing Report';
        $data['dates'] = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $data['admins'] = $this->auth->get_fo_list();

        if ($this->input->post('user_id') == '') {
            $data['user_id'] = $user_id;
        } else {

            $data['user_id'] = $this->input->post('user_id');
        }


        $this->load->view('routing_report', $data);
    }

    function routing_report_old() {
        $data['page_title'] = 'Reports';
        $data['sub_title'] = 'Routing Report';
        $data['dates'] = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $data['admins'] = $this->auth->get_fo_list();
        $data['user_id'] = $this->input->post('user_id');

        $this->load->view('routing_report', $data);
    }

    function load_shelf_all_zones() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = $_POST['zone_id'];
        $activity = $_POST['activity'];
        $cluster_id = $_POST['cluster_id'];
        $channel = $_POST['channel'];
        $super_market_project = $_POST['super_market_project'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;
        $data['report_data'] = $this->Report_model->get_shelf_data($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project, $channel);
        //$data['report_data2']=$this->Report_model->get_shelf_data($date_type,$start_date,$end_date,'-1',$cluster_id,$category_id,$activity,$super_market_project);
        $this->load->view('reports/shelf_share/load_all_zones_report', $data);
    }

    function load_shelf_all_zones_group_by_brand() {

        $start_date = $this->input->post('date');
        $end_date = $this->input->post('end_date');
        $category_id = $this->input->post('category_id');
        $date_type = $this->input->post('date_type');
        $zone_id = $this->input->post('zone_id');
        $activity = $this->input->post('activity');
        $cluster_id = $this->input->post('cluster_id');
        $channel = $this->input->post('channel');
        $super_market_project = $this->input->post('super_market_project');

        $date1 = strtotime($start_date);
        $date2 = strtotime($end_date);
        // $diff=$date1->diff($date2);
        // print_r($diff->m);

        $date1 = ceil(date('m', $date1) / 3);
        $date2 = ceil(date('m', $date2) / 3);
        if ($date1 != $date2) {
            $multi_quarter = 1;
        } else {

            $multi_quarter = 0;
        }

        $data['multi_quarter'] = $multi_quarter;
        $data['cluster_id'] = $cluster_id;
        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;

        $data['report_data'] = $this->Report_model->get_shelf_data_group_by_brand($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project, $channel);
        //$data['report_data2']=$this->Report_model->get_shelf_data($date_type,$start_date,$end_date,'-1',$cluster_id,$category_id,$activity,$super_market_project);
        $this->load->view('reports/shelf_share/load_all_zones_brand_report', $data);
    }

    /*
      function load_shelf_all_zones_group_by_channel_brand() {

      $start_date = $_POST['start_date'];
      $end_date = $_POST['end_date'];
      $category_id = $_POST['category_id'];
      $date_type = $_POST['date_type'];
      $zone_id = $_POST['zone_id'];
      $activity = $_POST['activity'];
      $cluster_id = $_POST['cluster_id'];
      $channel = $_POST['channel'];
      $super_market_project = $_POST['super_market_project'];

      $data['cluster_id'] = $cluster_id;
      $data['date_type'] = $date_type;
      $data['zn_id'] = $zone_id;
      $data['report_data'] = $this->Report_model->load_shelf_all_zones_group_by_channel_brand($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project, $channel);

      $this->load->view('reports/shelf_share/load_all_zones_brand_report', $data);
      }
     */

    function load_shelf_all_zones_product_channel() {

        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $date_type = $_POST['date_type'];
        $zone_id = $_POST['zone_id'];
        $activity = $_POST['activity'];
        $cluster_id = $_POST['cluster_id'];
        $super_market_project = $_POST['super_market_project'];

        $data['date_type'] = $date_type;
        $data['zn_id'] = $zone_id;
        $data['report_data'] = $this->Report_model->get_shelf_data_groupechannel($date_type, $start_date, $end_date, $zone_id, $cluster_id, $category_id, $activity, $super_market_project);

        $this->load->view('reports/shelf_share/load_shelf_all_zones_product_channel', $data);
    }

    function load_av_brand_data_chart() {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $multi_date = $_POST['multi_date'];
        $date_type = $_POST['date_type'];
        $zone_id = $_POST['zone_id'];
        $activity = $_POST['activity'];
        $super_market_project = $_POST['super_market_project'];


        if ($zone_id == -1) {
            $data['zone_name'] = '';
        } else {
            $data['zone_name'] = '';
        }
        $data['zone'] = $zone_id;



        //echo $start_date.'***'.$end_date.'**'.$date_type;


        $data['zn_id'] = -1;



        if ($multi_date == 0) {

            print_r('one');
            $data['brand_data'] = array();
            $data['henkel_data'] = $this->Report_model->get_brand_single_date_json_data_henkel($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, -1);

            $this->load->view('reports/stock_issues/load_av_all_zones_brand_single_date_chart', $data);
        } else {


            $result = $this->Report_model->get_brand_multiple_date_data($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $super_market_project);

            $data['result'] = json_encode($result['data']);



            $data['brands'] = $result['brands'];


            $this->load->view('reports/stock_issues/load_av_all_zones_brand_multiple_date_chart', $data);
        }
    }

    function load_av_brand_data_chart_zone() {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $multi_date = $_POST['multi_date'];
        $date_type = $_POST['date_type'];
        $zone_id = $_POST['zone_id'];
        $activity = $_POST['activity'];
        $channel = $_POST['channel'];
        $super_market_project = $_POST['super_market_project'];



        if ($zone_id == -1) {
            $data['zone_name'] = '';
        } else {
            $data['zone_name'] = '';
        }



        //echo $start_date.'***'.$end_date.'**'.$date_type;


        $data['zn_id'] = -1;
        $data['zone'] = $zone_id;




        if ($multi_date == 0) {


            $data['brand_data'] = array();
            $data['henkel_data'] = $this->Report_model->get_brand_single_date_json_data_henkel($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel);


            $this->load->view('reports/stock_issues/load_av_all_zones_brand_single_date_chart', $data);
        } else {


            $result = $this->Report_model->get_brand_multiple_date_data_zonn($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project);

            $data['result'] = json_encode($result['data']);



            $data['brands'] = $result['brands'];


            $this->load->view('reports/stock_issues/load_av_all_zones_brand_multiple_date_chart', $data);
        }
    }

    function load_av_brand_data_chart_zone2() {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $multi_date = $_POST['multi_date'];
        $date_type = $_POST['date_type'];
        $zone_id = $_POST['zone_id'];
        $activity = $_POST['activity'];
        $channel = $_POST['channel'];
        $group_by = $_POST['group_by'];
        $super_market_project = $_POST['super_market_project'];



        if ($zone_id == -1) {
            $data['zone_name'] = '';
        } else {
            $data['zone_name'] = '';
        }



        //echo $start_date.'***'.$end_date.'**'.$date_type;


        $data['zn_id'] = -1;
        $data['zone'] = $zone_id;




        if ($multi_date == 0) {


            $data['brand_data'] = $this->Report_model->get_brand_single_date_json_data($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, -1);
            if ($group_by == "C") {
                $data['henkel_data'] = $this->Report_model->get_brand_single_date_json_data_henkel_by_channel($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, -1);
            } else {
                $data['henkel_data'] = $this->Report_model->get_brand_single_date_json_data_henkel($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, -1);
            }

            // print_r($data['brand_data']);
            // die();

            $this->load->view('reports/stock_issues/load_av_all_zones_brand_single_date_chart2', $data);
        } else {


            $result = $this->Report_model->get_brand_multiple_date_data_zone($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $super_market_project);

            $data['result'] = json_encode($result['data']);



            $data['brands'] = $result['brands'];


            $this->load->view('reports/stock_issues/load_av_all_zones_brand_multiple_date_chart', $data);
        }
    }

    function load_av_brand_data_chart_by_channel() {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $multi_date = $_POST['multi_date'];
        $date_type = $_POST['date_type'];
        $zone_id = $_POST['zone_id'];
        $activity = $_POST['activity'];
        $channel = $_POST['channel'];
        $cluster_id = $_POST['cluster_id'];
        $super_market_project = $_POST['super_market_project'];



        $data['zone_name'] = $channel;


        //echo $start_date.'***'.$end_date.'**'.$date_type;


        $data['zn_id'] = $channel;
        $data['zone'] = $channel;



        if ($multi_date == 0) {

            $data['henkel_data'] = $this->Report_model->get_brand_single_date_json_data_henkel_bychannel($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel, $cluster_id);
            $data['brand_data'] = array();



            $this->load->view('reports/stock_issues/load_av_all_zones_brand_single_date_chart_by_channel', $data);
        } else {

            echo $channel;

            $result = $this->Report_model->get_brand_multiple_date_data_channel($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel);
            print_r($result);


            $data['result'] = json_encode($result['data']);



            $data['brands'] = $result['brands'];




            $this->load->view('reports/stock_issues/load_av_all_zones_brand_multiple_date_chart', $data);
        }
    }

    /*
      function load_shelf_brand_data_chart_by_channel() {
      $start_date = $_POST['start_date'];
      $end_date = $_POST['end_date'];
      $category_id = $_POST['category_id'];
      $multi_date = $_POST['multi_date'];
      $date_type = $_POST['date_type'];
      $zone_id = $_POST['zone_id'];
      $channel = $_POST['channel'];
      $activity = $_POST['activity'];
      $super_market_project = $_POST['super_market_project'];
      //echo $start_date.'***'.$end_date.'**'.$date_type;


      $data['zn_id'] = $zone_id;
      $data['zone_id'] = $zone_id;
      $data['channel'] = $channel;
      if ($multi_date == 0) {
      $data['zn_id'] = "all";
      $data['brand_data'] = $this->Report_model->get_brand_single_date_json_data_for_shelf($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel);
      $this->load->view('reports/shelf_share/load_av_all_zones_brand_single_date_chart', $data);
      } else {
      $result = $this->Report_model->get_brand_multiple_date_data_for_shelf($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel);


      $data['brand_data'] = json_encode(array_reverse($result['data']));
      $data['brands'] = $result['brands'];


      $this->load->view('reports/shelf_share/load_av_all_zones_brand_multiple_date_chart', $data);
      }
      }
     */

    function load_shelf_brand_data_chart_by_channel_all() {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $category_id = $_POST['category_id'];
        $multi_date = $_POST['multi_date'];
        $date_type = $_POST['date_type'];
        $zone_id = $_POST['zone_id'];
        $channel = $_POST['channel'];
        $activity = $_POST['activity'];
        $super_market_project = $_POST['super_market_project'];
        //echo $start_date.'***'.$end_date.'**'.$date_type;

        $data['zn_id'] = $zone_id;
        $data['zone_id'] = $zone_id;
        $data['channel'] = $channel;
        if ($multi_date == 0) {
            $data['zn_id'] = "all";
            $data['brand_data'] = $this->Report_model->get_brand_single_date_json_data_for_shelf($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel);
            $this->load->view('reports/shelf_share/load_av_all_zones_brand_single_date_chart2', $data);
        } else {
            $result = $this->Report_model->get_brand_multiple_date_data_for_shelf($zone_id, $start_date, $end_date, $date_type, $category_id, $activity, $super_market_project, $channel);


            $data['brand_data'] = json_encode(array_reverse($result['data']));
            $data['brands'] = $result['brands'];


            $this->load->view('reports/shelf_share/load_av_all_zones_brand_multiple_date_chart', $data);
        }
    }

    function picture_report() {

        $data['page_title'] = "Reports";
        $data['sub_title'] = "Pictures report";

        $data['excel'] = $this->input->post('excel');
        $best_of = $this->input->post('best_of');
        $data['best_of'] = $best_of;
        $data['zones'] = $this->Zone_model->get_zones();
        $data['admins'] = $this->auth->get_fo_list();
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $super_market_project = $this->input->post('super_market_project');

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $zone = $this->input->post('zone');
        $data['zone'] = $zone;

        $activity = $this->input->post('activity');
        $data['activity'] = $activity;

        $admin_id = $this->input->post('user_id');
        $data['user_id'] = $admin_id;
        $channel_id = $this->input->post('channel_id');
        $data['channel_id'] = $channel_id;
        $data['channels'] = $this->Channel_model->get_channels();

        if ($best_of == 0) {
            $data['visits'] = $this->Report_model->get_visits_data($start_date, $end_date, $admin_id, $zone, $channel_id);
        } else {

            $data['visits'] = $this->Report_model->get_best_of_visits_data($start_date, $end_date, $admin_id, $zone, $channel_id);
        }


        $this->load->view('pictures_report', $data);
    }

    //bcm
    function price_monitoring_report() {
        $data['page_title'] = "Reports";
        $data['sub_title'] = "Price monitoring report";

        $start_date = $this->input->post('start_date');
        $category_id = $this->input->post('category_id');
        $channel_id = $this->input->post('channel_name');
        $excel = $this->input->post('excel');


        //$data['categories']=$this->Sub_category_model->get_sub_categories();
        $data['categories'] = $this->Category_model->get_categories();
        $data['clusters'] = $this->Cluster_model->get_clusters_by_category($category_id);
        $data['channels'] = $this->Channel_model->get_channels();

        $data['start_date'] = $start_date;
        $data['category_id'] = $category_id;
        $data['channel_id'] = $channel_id;
        $data['excel'] = $excel;


        //print_r($this->Category_model->get_categories());        
        //print_r($this->Cluster_model->get_clusters_by_category($category_id));
        //print_r($this->Channel_model->get_channels());

        $this->load->view('price_monitoring_report', $data);
    }

    function price_monitoring_report2() {
        $data['page_title'] = "Reports";
        $data['sub_title'] = "Price monitoring report";
        $data['excel'] = $this->input->post('excel');
        $start_date = $this->input->post('start_date');

        $data['start_date'] = $start_date;

        $category_id = $this->input->post('category_id');
        $data['category_id'] = $category_id;
        $data['categories'] = $this->Sub_category_model->get_sub_categories();
        $data['clusters'] = $this->Cluster_model->get_clusters_by_category($category_id);
        $zone_id = $this->input->post('zone_id');
        $data['zone_id'] = $zone_id;
        $data['zones'] = $this->Zone_model->get_zones();

        $this->load->view('price_monitoring_report2', $data);
    }

    function price_compare_report() {
        $data['page_title'] = "Reports";
        $data['sub_title'] = "Price compare report";
        $data['excel'] = $this->input->post('excel');
        $start_date = $this->input->post('start_date');

        $data['start_date'] = $start_date;

        $category_id = $this->input->post('category_id');
        $data['category_id'] = $category_id;
        //$data['categories']=$this->Sub_category_model->get_sub_categories();
        $data['categories'] = $this->Category_model->get_categories();
        $data['clusters'] = $this->Cluster_model->get_clusters_by_category($category_id);
        $zone_id = $this->input->post('zone_id');
        $data['zone_id'] = $zone_id;
        $data['zones'] = $this->Zone_model->get_zones();

        $this->load->view('price_compare_report', $data);
    }

    function test_date() {
        $date1 = strtotime('2017-11-05');

        $final = date("m", strtotime("+0 month", $date1));
        echo round($final / 3);
    }

    ////////////////////////////////////////
//    function tracking_oos() {
//        $data_header['page_title'] = "Reports";
//        $data_header['sub_title'] = "TRACKING OOS";
//
//        $channels = $this->Channel_model->get_channels();
//        $data['channels'] = $channels;
//
//        $channel_id = ($this->input->post("channel_id")) ? $this->input->post("channel_id") : "-1";
//        $channel_id = ($this->uri->segment(3)) ? $this->uri->segment(3) : $channel_id;
//        $data['channel_id'] = $channel_id;
//
//        //pagination settings
//        $config['base_url'] = site_url('reports/Tracking_oos/' . $channel_id);
//
//        //Total row
//        $nb_out = $this->Outlet_model->get_number_outlet_by_channel($channel_id);
//        $data['nb_out'] = $nb_out;
//        $config['total_rows'] = $nb_out;
//
//        //$config['total_rows'] = $this->Outlet_model->count_active_outlets();
//        $config['per_page'] = "5";
//        $config["uri_segment"] = 4;
//        $config["num_links"] = 5;
//        //floor($choice);
//        //config for bootstrap pagination class integration
//        $config['full_tag_open'] = '<ul class="pagination">';
//        $config['full_tag_close'] = '</ul>';
//        $config['first_link'] = false;
//        $config['last_link'] = false;
//        $config['first_tag_open'] = '<li>';
//        $config['first_tag_close'] = '</li>';
//        $config['prev_link'] = '&laquo';
//        $config['prev_tag_open'] = '<li class="prev">';
//        $config['prev_tag_close'] = '</li>';
//        $config['next_link'] = '&raquo';
//        $config['next_tag_open'] = '<li>';
//        $config['next_tag_close'] = '</li>';
//        $config['last_tag_open'] = '<li>';
//        $config['last_tag_close'] = '</li>';
//        $config['cur_tag_open'] = '<li class="active"><a href="#">';
//        $config['cur_tag_close'] = '</a></li>';
//        $config['num_tag_open'] = '<li>';
//        $config['num_tag_close'] = '</li>';
//
//        $this->pagination->initialize($config);
//        $data['page'] = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
//
//        $data['outlets'] = $this->Outlet_model->get_outlets_by_channel($channel_id, $config["per_page"], $data['page'], 'id', 'DESC');
//        $data['pagination'] = $this->pagination->create_links();
//
//        $this->load->view('header', $data_header);
//        $this->load->view('tracking_oos', $data);
//        $this->load->view('footer');
//    }
//    function tracking_oos() {
//        $data_header['page_title'] = "Reports";
//        $data_header['sub_title'] = "TRACKING OOS";
//
//        //pagination settings
//        $config['base_url'] = site_url('reports/Tracking_oos');
//
//        //Total row
//        $nb_products = $this->Product_model->count_acrive_products();
//        $data['nb_products'] = $nb_products;
//        $config['total_rows'] = $nb_products;
//
//        //$config['total_rows'] = $this->Outlet_model->count_active_outlets();
//        $config['per_page'] = "20";
//        $config["uri_segment"] = 3;
//        $config["num_links"] = 4;
//        //floor($choice);
//        //config for bootstrap pagination class integration
//        $config['full_tag_open'] = '<ul class="pagination">';
//        $config['full_tag_close'] = '</ul>';
//        $config['first_link'] = false;
//        $config['last_link'] = false;
//        $config['first_tag_open'] = '<li>';
//        $config['first_tag_close'] = '</li>';
//        $config['prev_link'] = '&laquo';
//        $config['prev_tag_open'] = '<li class="prev">';
//        $config['prev_tag_close'] = '</li>';
//        $config['next_link'] = '&raquo';
//        $config['next_tag_open'] = '<li>';
//        $config['next_tag_close'] = '</li>';
//        $config['last_tag_open'] = '<li>';
//        $config['last_tag_close'] = '</li>';
//        $config['cur_tag_open'] = '<li class="active"><a href="#">';
//        $config['cur_tag_close'] = '</a></li>';
//        $config['num_tag_open'] = '<li>';
//        $config['num_tag_close'] = '</li>';
//
//        $this->pagination->initialize($config);
//        $data['page'] = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
//
//        $data['products'] = $this->Product_model->get_all_active_products($config["per_page"], $data['page']);
//        $data['pagination'] = $this->pagination->create_links();
//
//        $data['outlets'] = $this->Outlet_model->get_outlets();
//
//        $this->load->view('header', $data_header);
//        $this->load->view('tracking_oos', $data);
//        $this->load->view('footer');
//    }

    function tracking_oos() {
        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "TRACKING OOS";

        $data['report_data'] = $this->Report_model->get_oos_tracking();

        $this->load->view('header', $data_header);
        $this->load->view('reports/tracking_oos', $data);
        $this->load->view('footer');
    }

    function test_array() {
        $os = array("Mac", "NT", "Irix", "Linux");
        if (in_array("Irix", $os) == true) {
            echo "true";
        }
        if (in_array("amira", $os) == true) {
            echo "false";
        }
    }

    function up_tof() {
        $this->db->select('id,branding_pictures,one_pictures');
        $this->db->from('visits');
        $this->db->order_by('id', 'desc');
        $this->db->where('m_date >=', '2018-06-01');

        $result = $this->db->get();
        $visits = $result->result();
        foreach ($visits as $visit) {
//            echo 'visit_id***'.$visit->visit_id;
//            print_r(json_decode($visit->branding_pictures));
//            echo '<br>';
//            echo '<br>';

            $branding_pictures = array_unique(json_decode($visit->branding_pictures), SORT_REGULAR);
            print_r($branding_pictures);
            echo '<br>';
            echo '<br>';

            $one_pictures = array_unique(json_decode($visit->one_pictures), SORT_REGULAR);
            print_r($one_pictures);

            echo '<br>';
            echo '<br>';
            $save['id'] = $visit->id;
            $save['branding_pictures'] = json_encode($branding_pictures);
            $save['one_pictures'] = json_encode($one_pictures);
            $this->Report_model->update_visit_picture($save);
        }
    }

}
