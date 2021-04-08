<?php
//bcm
class Reports extends CI_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->auth->is_logged_in(false, false)) {

            redirect('login');
        }


        $this->load->model(array('Channel_model', 'Product_model', 'Cluster_model', 'Sub_category_model',
            'Visit_model', 'Outlet_model', 'Brand_model', 'Admin_model', 'Report_model',
            'State_model', 'Zone_model', 'Category_model'));

        $this->load->helper(array('formatting', 'date'));

        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('pagination');

        $this->lang->load('reports');
    }

    function index() {
        
    }

    function modern_daily_visit_report() {

        $data['title1'] = 'Reports';
        $data['title2'] = 'Daily visit Report';

        $data['excel'] = 0;
        $data['page_title'] = 'Daily Visit Report';
        $data['sub_title'] = 'Daily Visit Report';
        $data['chefs'] = $this->auth->get_fo_list();

        $date = $this->input->post('date');
        $data['date'] = $date;

        $chef_id = $this->input->post('chef_id');
        $data['chef_id'] = $chef_id;
        $excel = $this->input->post('excel');
        $data['excel'] = $excel;

        $res_id = $this->input->post('res_id');
        $data['res_id'] = $res_id;
        $data['responsibales'] = $this->auth->get_responsible_list();
        $data['channels'] = $this->Channel_model->get_channels();
        $super_market_project = $this->input->post('super_market_project');
        $category_id = $this->input->post('category_id');
        $selected_channel = $this->input->post('selected_channel');
        $activity = $this->input->post('activity');
        $zones = $this->Zone_model->get_zones();


        $data['super_market_project'] = $super_market_project;
        $data['selected_channel'] = $selected_channel;
        $data['category_id'] = $category_id;
        $data['activity'] = $this->input->post('activity');

        $data['zones'] = $zones;
        $data['categories'] = $this->Category_model->get_categories();


        if ($date != '' && $chef_id != -1) {


            $data['page_title'] = 'Daily visit Report' . ' | ' . reverse_format($date) . ' | ';
        } else {

            $data['page_title'] = 'Daily visit Report' . ' | ' . reverse_format($date);
        }

        $this->load->view('specific_modern_daily_visit', $data);
    }

    function pos_data() {
        $data_header['page_title'] = "Reports";
        $data_header['sub_title'] = "POS data report";


        $date_type = $this->input->post('date_type');
        $super_market_project = $this->input->post('super_market_project');
        $category_id = $this->input->post('category_id');
        $channel_id = $this->input->post('channel_id');
        $activity = $this->input->post('activity');
        $channels = $this->Channel_model->get_channels();

        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');


        $data['super_market_project'] = $super_market_project;
        $data['admins'] = $this->auth->get_fo_list();
        $data['user_id'] = $this->input->post('user_id');
        $outlet_id = $this->input->post('outlet_id');
        $data['outlets'] = $this->Outlet_model->get_outlets();
        $data['outlet_id'] = $outlet_id;


        $data['date_type'] = $date_type;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['category_id'] = $category_id;
        $data['activity'] = $this->input->post('activity');
        $data['channel_id'] = $channel_id;
        $data['channels'] = $channels;
        $data['categories'] = $this->Category_model->get_categories();


        $data['report_data2'] = $this->Report_model->get_pos_data($outlet_id, $start_date, $end_date, -1);
        $this->load->view('header', $data_header);

        $this->load->view('reports/stock_issues/pos_data_report', $data);
        $this->load->view('footer');
    }

    function routing_report() {
        $data['page_title'] = 'Reports';
        $data['sub_title'] = 'Routing Report';
        $data['dates'] = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $data['admins'] = $this->auth->get_fo_list();
        $data['user_id'] = $this->input->post('user_id');

        $this->load->view('routing_report', $data);
    }

    function picture_report() {

        $data['page_title'] = "Reports";
        $data['sub_title'] = "Pictures report";

        $data['excel'] = $this->input->post('excel');
        $best_of = $this->input->post('best_of');
        $data['best_of'] = $best_of;
        $data['zones'] = $this->Zone_model->get_zones();
        $data['outlets'] = $this->Outlet_model->get_outlets();
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
        $outlet_id = $this->input->post('outlet_id');
        $data['outlet_id'] = $outlet_id;
        $data['admins'] = $this->auth->get_fo_list();





        if ($best_of == 0) {
            $data['visits'] = $this->Report_model->get_visits_data($start_date, $end_date, $admin_id, $zone, $outlet_id);
        } else {

            $data['visits'] = $this->Report_model->get_best_of_visits_data($start_date, $end_date, $admin_id, $zone, $outlet_id);
        }


        $this->load->view('pictures_report', $data);
    }

    function download_zip($visit_id) {

        $visit = $this->Visit_model->get_visit($visit_id);
        $outlet = $this->Outlet_model->get_outlet($visit->outlet_id);
        $outlet_name = $outlet->name;
        $file_name = $outlet_name . ' ' . $visit->date;
        $zip = new ZipArchive();
        $tmp_file = tempnam('.', '');
        $zip->open($tmp_file, ZipArchive::CREATE);


        $pictures = json_decode($visit->branding_pictures);


        $size_pictures = sizeof(($pictures));


        for ($i = 0; $i < sizeof($pictures); $i++) {





            $file1 = (base_url('uploads/branding/' . $pictures[$i][0]));
            $file2 = (base_url('uploads/branding/' . $pictures[$i][1]));

            # download file
            $download_file1 = file_get_contents($file1);
            $download_file2 = file_get_contents($file2);

            #add it to the zip
            $zip->addFromString(basename($file1), $download_file1);
            $zip->addFromString(basename($file2), $download_file2);
        }

        $outlet_picture = (base_url('uploads/outlet/' . $outlet->photos));

        $download_file_outlet = file_get_contents($outlet_picture);
        $zip->addFromString(basename($outlet_picture), $download_file_outlet);

//die();		






        $zip->close();

# send the file to the browser as a download
        header('Content-disposition: attachment; filename=' . $file_name . '.zip');
        header('Content-type: application/zip');
        readfile($tmp_file);
    }

    function shelf_share_report() {
        $data['page_title'] = "Reports";
        $data['sub_title'] = "Shelf share report";
        $data['excel'] = $this->input->post('excel');
        $data['zoness'] = $this->input->post('zoness');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $category_id = $this->input->post('category_id');
        $data['category_id'] = $category_id;
        $data['categories'] = $this->Category_model->get_categories();
        $data['clusters'] = $this->Cluster_model->get_clusters_by_category($category_id);
        $zone_id = $this->input->post('zone_id');
        $data['zone_id'] = $zone_id;
        $data['zones'] = $this->Zone_model->get_zones();

        $this->load->view('shelf_share_report', $data);
    }

    function stock_issues_report() {
        $data['page_title'] = "Reports";
        $data['sub_title'] = "Stock issues report";
        $data['excel'] = $this->input->post('excel');
        $data['zoness'] = $this->input->post('zoness');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $category_id = $this->input->post('category_id');
        $data['category_id'] = $category_id;
        $data['categories'] = $this->Sub_category_model->get_sub_categories();
        $data['clusters'] = $this->Cluster_model->get_clusters_by_category($category_id);
        $zone_id = $this->input->post('zone_id');
        $data['zone_id'] = $zone_id;
        $data['zones'] = $this->Zone_model->get_zones();
        //$data['report_data']=$this->Report_model->get_stock_issues_data($start_date,$end_date,$zone_id,$cluster_id);
        $this->load->view('stock_issues_report', $data);
    }

    function price_monitoring_report() {
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

        $this->load->view('price_monitoring_report', $data);
    }

    function price_compare_report() {
        $data['page_title'] = "Reports";
        $data['sub_title'] = "Price compare report";
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

        $this->load->view('price_compare_report', $data);
    }

    // Data Collection Report

    function data_collection() {
        $data = array();
        $report_data = array();
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $outlet_id = $this->input->post('outlet_id');
        $user_id = $this->input->post('user_id');
        $excel = $this->input->post('excel');
        $type = $this->input->post('type');
        $antenna = $this->input->post('antenna');


        if ($start_date != '' && $end_date != '') {

            $data['page_title'] = ' Data Collection Report';
            $data['sub_title'] = ' Data Collection Report' . ' | ' . format_week($start_date) . ' | ' . format_week($end_date);
        } else {
            $data['page_title'] = ' Data Collection Report';
            $data['sub_title'] = ' ';
        }



        if ($type == 'sum') {
            $report_data = $this->Report_model->get_data_collection_sum($start_date, $end_date, $outlet_id, $user_id, $antenna);
        } else if (($type == 'per week') && ($outlet_id == -1)) {
            $report_data = $this->Report_model->get_data_collection($start_date, $end_date, $outlet_id);
        } else if ($type == 'per week') {
            $report_data = $this->Report_model->get_data_collection($start_date, $end_date, $outlet_id);
        } else if ($outlet_id != -1) {
            $report_data = $this->Report_model->get_data_collection_per_outlet($start_date);
        }

        $data['report_data'] = $report_data;
        $data['outlet_id'] = $outlet_id;
        $data['user_id'] = $user_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['excel'] = $excel;
        $data['antenna'] = $antenna;
        $data['type'] = $this->input->post('type');
        $data['admins'] = $this->auth->get_admin_list();
        $data['outlets'] = $this->Outlet_model->get_active_outlets_by_name();

        $this->load->view('data_collection_report', $data);
    }

    function data_collection_sis() {
        $data = array();
        $date = $this->input->post('start_date');
        $to = $this->input->post('end_date');
        $data['type'] = $this->input->post('type');
        $outlet_id = $this->input->post('outlet_id');
        $admin_id = $this->input->post('adminn_id');
        $excel = $this->input->post('excel');
        $data['models'] = $this->Model_model->get_active_models();
        if ($date != '') {
            $data['page_title'] = ' Data Collection SIS Report';
            $data['sub_title'] = ' Data Collection SIS Report' . ' | ' . format_week($date) . ' | ' . format_week($to) . '|' . $data['type'];
        } else {
            $data['page_title'] = ' Data Collection SIS Report';
            $data['sub_title'] = '';
        }
        $data['outlet_id'] = $outlet_id;
        $data['adminn_id'] = $admin_id;
        $data['start_date'] = $date;
        $data['end_date'] = $to;
        $data['excel'] = $excel;
        $data['outlets'] = $this->Outlet_model->get_active_outlets_sis_by_name();
        $data['admins'] = $this->auth->get_admin_list();

        $this->load->view('data_collection_sis_report', $data);
    }

    function price_portion() {
        $data = array();
        $from = $this->input->post('start_date');
        $to = $this->input->post('end_date');
        $excel = $this->input->post('excel');

        if ($from != '' && $to != '') {
            $report_data = $this->Report_model->get_price_portion_data_all($from, $to);
            $data['page_title'] = ' Price portion Report ';

            $data['sub_title'] = ' Price portion Report | ' . format_week($from) . '|' . format_week($to);
        } else {
            $data['page_title'] = ' Price portion Report ';
            $data['sub_title'] = ' ';

            $report_data = array();
        }

        $data['report_data'] = $report_data;
        $data['from'] = $from;
        $data['to'] = $to;
        $data['excel'] = $excel;
        $data['price_ranges'] = $this->Price_range_model->get_price_ranges_by_code();

        $this->load->view('price_portion_report', $data);
    }

    //******************************************************************

    function weekly_model_report() {
        ini_set("memory_limit", -1);
        $data = array();
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $type = $this->input->post('type');
        $excel = $this->input->post('excel');

        $data['active_models'] = $this->Model_model->get_active_models();
        $data['model_id'] = $this->input->post('model_id');
        $model_id = $this->input->post('model_id');
        if ($type == 'SUM') {


            $report_data = $this->Report_model->get_sum_weekly_model_data($from, $to, $model_id);
        } else {
            $report_data = $this->Report_model->get_week_weekly_model_data($from, $to, $model_id);
        }


        $data['from'] = $from;
        $data['to'] = $to;
        $data['type'] = $type;
        $data['excel'] = $excel;

        $data['report_data'] = $report_data;
        if ($from != '' && $to != '') {

            $data['page_title'] = ' Weekly Models Report  ';

            $data['sub_title'] = ' Weekly Models Report | ' . format_week($from) . '|' . format_week($to);
        } else {
            $data['page_title'] = ' Weekly Models Report ';
            $data['sub_title'] = '';
        }


        $this->load->view($this->config->item('admin_folder') . '/weekly_model_report', $data);
    }

    function weekly_ws_report() {
        $data = array();

        $admin = $this->admin_session->userdata('admin');
        $admin_id = $admin['id'];
        $data['outlets'] = $this->Outlet_model->get_outlets_by_id($admin_id);

        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $outlet_id = $this->input->post('outlet_id');


        $report_data = $this->Report_model->get_sum_weekly_ws_data($from, $to, $outlet_id);




        $data['from'] = $from;
        $data['to'] = $to;
        $data['outlet_id'] = $outlet_id;

        $data['report_data'] = $report_data;
        if ($from != '' && $to != '') {

            $outlet_name = $this->Outlet_model->get_outlet_name($outlet_id);

            if ($from != $to) {
                $data['page_title'] = ' Weekly Report | ' . $outlet_name . ' | ' . format_week($from) . ' ---> ' . format_week($to);
            } else {
                $data['page_title'] = ' Weekly Report | ' . format_week($from);
            }
        } else {
            $data['page_title'] = ' Weekly Report ';
        }

        $this->load->view($this->config->item('admin_folder') . '/weekly_ws_report', $data);
    }

    function weekly_outlet_report() {
        $data = array();
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $type = $this->input->post('type');
        $excel = $this->input->post('excel');

        if ($from != '' && $type == 'ss') {
            $report_data = $this->Report_model->get_weekly_ss_outlet_data($from, $to);
            $data['page_title'] = ' Weekly Report | Shelf Share ' . format_week($from) . 'To ' . format_week($to);
        } else if ($from != '' && $type == 'ms') {
            $report_data = $this->Report_model->get_weekly_ms_outlet_data($from, $to);
            $data['page_title'] = ' Weekly Report | Market Share ' . format_week($from) . 'To ' . format_week($to);
        } else {
            $data['page_title'] = ' Weekly Report ';
            $report_data = array();
        }

        $data['report_data'] = $report_data;
        $data['date'] = $from;
        $data['type'] = $type;
        $data['excel'] = $excel;

        $this->load->view($this->config->item('admin_folder') . '/weekly_outlet_report', $data);
    }

    function compare_chart() {
        $data = array();
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $excel = $this->input->post('excel');

        if ($from != '' && $to != '') {
            $report_data = $this->Report_model->get_compare_chart_data_all($from, $to);
            $data['page_title'] = ' M.S compare chart Report  ';
            $data['sub_title'] = ' M.S compare chart Report | ' . format_week($from) . '|' . format_week($to);
        } else {
            $data['page_title'] = ' M.S compare chart Report ';
            $data['sub_title'] = ' ';

            $report_data = array();
        }

        $data['report_data'] = $report_data;
        $data['from'] = $from;
        $data['to'] = $to;
        $data['excel'] = $excel;
        $data['ranges'] = array('ULC', 'Smart Phone', 'Features');
        $data['price_ranges'] = $this->Price_range_model->get_price_ranges_by_code();
        //print_r($this -> Price_range_model -> get_price_ranges_by_code());

        $this->load->view($this->config->item('admin_folder') . '/compare_chart_report', $data);
    }

    function market_share() {
        $data = array();
        $from = $this->input->post('from');
        $to = $this->input->post('to');

        $excel = $this->input->post('excel');

        if ($from != '' && $to != '') {
            $report_data = $this->Report_model->get_compare_chart_data_all($from, $to);
            $data['sub_title'] = ' Market Share Report | ' . format_week($from) . ' | ' . format_week($to);
            $data['page_title'] = ' Market Share Report ';
        } else {
            $data['page_title'] = ' Market Share Report ';
            $data['sub_title'] = '';

            $report_data = array();
        }

        $data['report_data'] = $report_data;
        $data['from'] = $from;
        $data['to'] = $to;
        $data['excel'] = $excel;
        $data['ranges'] = array('ULC', 'Smart Phone', 'Features');
        $data['price_ranges'] = $this->Price_range_model->get_price_ranges_by_code();

        $this->load->view($this->config->item('admin_folder') . '/market_share_report', $data);
    }

    function top_sales_model() {
        $data = array();
        $date = $this->input->post('date');
        $excel = $this->input->post('excel');

        if ($date != '') {

            $data['page_title'] = ' Weekly Report | Top Sales Models | ' . format_week($date);
        } else {
            $data['page_title'] = ' Weekly Report | Top Sales Models';
        }

        $data['date'] = $date;
        $data['excel'] = $excel;
        $data['price_ranges'] = $this->Price_range_model->get_price_ranges_by_code();

        $this->load->view($this->config->item('admin_folder') . '/top_sales_model_report', $data);
    }

    function segment_portion() {
        $data = array();
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $excel = $this->input->post('excel');

        if ($from != '' && $to != '') {
            $report_data = $this->Report_model->get_compare_chart_data_all($from, $to);
            $data['page_title'] = ' Segment portion per Maker | ' . format_week($from) . ' / ' . format_week($to);
        } else {
            $data['page_title'] = ' Segment portion per Maker ';
            $report_data = array();
        }

        $data['report_data'] = $report_data;
        $data['from'] = $from;
        $data['to'] = $to;
        $data['excel'] = $excel;
        $data['ranges'] = $this->Range_model->get_ranges_by_code();
        $data['brands'] = $this->Brand_model->get_brands_by_code();

        $this->load->view($this->config->item('admin_folder') . '/segment_portion_report', $data);
    }

    function shortage() {
        $data = array();
        $date = $this->input->post('date');
        $excel = $this->input->post('excel');
        $antenna = $this->input->post('antenna');

        if ($date != '') {
            $data['page_title'] = ' Shortage Report';

            $data['sub_title'] = ' Shortage Report | ' . format_week($date);
        } else {
            $data['page_title'] = ' Shortage Report';
            $data['sub_title'] = '';
        }
        $data['antenna'] = $antenna;
        $data['date'] = $date;
        $data['excel'] = $excel;


        $this->load->view($this->config->item('admin_folder') . '/shortage_report', $data);
    }

    function number_branding_report() {
        $data = array();
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $outlet_id = $this->input->post('outlet_id');
        $adminn_id = $this->input->post('adminn_id');
        $excel = $this->input->post('excel');

        $data['adminss'] = $this->Report_model->get_fo_list();

        if ($start_date != '' && $end_date != '') {

            $data['page_title'] = ' Data Collection Report | ' . format_week($start_date);
        } else {
            $data['page_title'] = ' Data Collection Report';
        }
        $data['outlet_id'] = $outlet_id;
        $data['adminn_id'] = $adminn_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['excel'] = $excel;
        $data['outlets'] = $this->Outlet_model->get_active_outlets_by_name();
        $data['report_data'] = $this->Report_model->get_brandng_data($start_date, $end_date, $outlet_id, $adminn_id);
        $this->load->view($this->config->item('admin_folder') . '/number_branding_report', $data);
    }

    function number_Pictures_report() {
        $data = array();
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $excel = $this->input->post('excel');


        if ($start_date != '' && $end_date != '') {

            $data['page_title'] = ' Pictures Report';
            $data['sub_title'] = ' Pictures Report | ' . format_week($start_date) . '|' . format_week($end_date);
        } else {
            $data['page_title'] = ' Pictures Report';
            $data['sub_title'] = ' Pictures Report';
        }
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['excel'] = $excel;

        $data['report_data'] = $this->Report_model->count_pictures_data($start_date, $end_date);
        $this->load->view($this->config->item('admin_folder') . '/number_pictures_report', $data);
    }

    function voice_report() {
        $data = array();
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $outlet_id = $this->input->post('outlet_id');
        $adminn_id = $this->input->post('adminn_id');
        $excel = $this->input->post('excel');

        $data['admins'] = $this->auth->get_admin_list();

        if ($start_date != '' && $end_date != '') {

            $data['page_title'] = ' Voice of dealer Report | ' . format_week($start_date);
        } else {
            $data['page_title'] = ' Voice of dealer Report';
        }
        $data['outlet_id'] = $outlet_id;
        $data['adminn_id'] = $adminn_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['excel'] = $excel;
        $data['outlets'] = $this->Outlet_model->get_active_outlets_by_name();
        $data['report_data'] = $this->Report_model->get_voice_data($start_date, $end_date, $outlet_id, $adminn_id);
        $this->load->view($this->config->item('admin_folder') . '/voice_report', $data);
    }

    function competitor_report() {
        $data = array();
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $outlet_id = $this->input->post('outlet_id');
        $adminn_id = $this->input->post('adminn_id');
        $excel = $this->input->post('excel');

        $data['admins'] = $this->auth->get_admin_list();

        if ($start_date != '' && $end_date != '') {

            $data['page_title'] = ' Data Collection Report | ' . format_week($start_date);
        } else {
            $data['page_title'] = ' Data Collection Report';
        }
        $data['outlet_id'] = $outlet_id;
        $data['adminn_id'] = $adminn_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['excel'] = $excel;
        $data['outlets'] = $this->Outlet_model->get_active_outlets_by_name();
        $data['report_data'] = $this->Report_model->get_competitor_data($start_date, $end_date, $outlet_id, $adminn_id);
        $this->load->view($this->config->item('admin_folder') . '/competitor_report', $data);
    }

    function display_report() {
        $data = array();
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $outlet_id = $this->input->post('outlet_id');
        $adminn_id = $this->input->post('adminn_id');
        $excel = $this->input->post('excel');

        $data['admins'] = $this->auth->get_admin_list();

        if ($start_date != '' && $end_date != '') {

            $data['page_title'] = ' Data Collection Report | ' . format_week($start_date);
        } else {
            $data['page_title'] = ' Data Collection Report';
        }
        $data['outlet_id'] = $outlet_id;
        $data['adminn_id'] = $adminn_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['excel'] = $excel;
        $data['outlets'] = $this->Outlet_model->get_active_outlets_by_name();
        $data['report_data'] = $this->Report_model->get_display_data($start_date, $end_date, $outlet_id, $adminn_id);
        $this->load->view($this->config->item('admin_folder') . '/display_report', $data);
    }

    function other_report() {
        $data = array();
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $outlet_id = $this->input->post('outlet_id');
        $adminn_id = $this->input->post('adminn_id');
        $excel = $this->input->post('excel');

        $data['admins'] = $this->auth->get_admin_list();

        if ($start_date != '' && $end_date != '') {

            $data['page_title'] = ' Data Collection Report | ' . format_week($start_date);
        } else {
            $data['page_title'] = ' Data Collection Report';
        }
        $data['outlet_id'] = $outlet_id;
        $data['adminn_id'] = $adminn_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['excel'] = $excel;
        $data['outlets'] = $this->Outlet_model->get_active_outlets_by_name();
        $data['report_data'] = $this->Report_model->get_other_data($start_date, $end_date, $outlet_id, $adminn_id);
        $this->load->view($this->config->item('admin_folder') . '/other_report', $data);
    }

    function data_collection_outlet() {
        $data = array();
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        $outlet_id = $this->input->post('outlet_id');
        $type = $this->input->post('type');
        $excel = $this->input->post('excel');



        if ($from != '' && $to != '') {
            $outlet_name = $this->Outlet_model->get_outlet_name($outlet_id);
            $data['page_title'] = ' Data Collection | ' . $type . ' | ' . $outlet_name . ' | ' . format_week($from) . ' / ' . format_week($to);
        } else {
            $data['page_title'] = ' Data Collection Outlet Report';
        }

        $data['from'] = $from;
        $data['to'] = $to;
        $data['outlet_id'] = $outlet_id;
        $data['type'] = $type;
        $data['excel'] = $excel;
        $data['outlets'] = $this->Outlet_model->get_outlets();


        $this->load->view($this->config->item('admin_folder') . '/data_collection_outlet_report', $data);
    }

    
    
    function test_array() {
        $os = array("Mac", "NT", "Irix", "Linux");
        if (in_array("Irix", $os) == true) {
            echo "true";
        }
        if (in_array("amira", $os) == false) {
            echo "false";
        }
    }

}
