<?php

// GDI project
class Webservices extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array('Ws_model', 'Outlet_model', 'Category_model'));
        $this->load->helper(array('date'));
    }

    function test22() {
        log_message('message', 'Some variable did not contain a value.');
    }

//Save The outlet into database
    function saveOutlet() {
        
        
        log_message('error', $_POST['outlet']);
        if (isset($_POST['outlet'])) {
            $outlet_object = json_decode($_POST['outlet']);
            $save_outlet = array();

            // save the  file into server
            if (isset($_FILES['image'])) {
                $file_path = "./uploads/outlet/";
                $file_name = date('Y') . '-' . time() . '.jpg';
                $file_path = $file_path . $file_name;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                    $save_outlet['photos'] = $file_name;
                }
            }

            $visit_days = array($outlet_object->visit_day);
            $save_outlet['visit_day'] = json_encode($visit_days);
            $save_outlet['zone'] = $outlet_object->zone;
            $save_outlet['adress'] = $outlet_object->adress;
            $save_outlet['source'] = $outlet_object->sub_channel;
            $save_outlet['sub_channel'] = $outlet_object->sub_channel;
            $save_outlet['channel'] = $outlet_object->channel;
            $save_outlet['contact'] = $outlet_object->contact;     
            $save_outlet['contact_pdv'] = $outlet_object->contact_pdv;
            $save_outlet['delivery_day'] = $outlet_object->delivery_day;
            $save_outlet['state'] = $outlet_object->state;
            $save_outlet['latitude'] = $outlet_object->latitude;
            $save_outlet['longitude'] = $outlet_object->longitude;
            $save_outlet['caisse_number'] = $outlet_object->caisse_number;
            $save_outlet['admin_id'] = $outlet_object->admin_id;
            $save_outlet['name'] = $outlet_object->name;

            $save_outlet['zone_id'] = $this->Ws_model->get_zone_id($save_outlet['zone']);
            $save_outlet['channel_id'] = $this->Ws_model->get_channel_id($save_outlet['channel']);
            $save_outlet['state_id'] = $this->Ws_model->get_state_id($save_outlet['state']);



            $result = $this->Ws_model->save_outlet($save_outlet);
            if ($result) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "Outlet has been successfully saved.";

                // echoing JSON response
                echo json_encode($response);
            } else {
                // failed to insert row
                $response["success"] = 0;
                $response["message"] = $result;

                // echoing JSON response
                echo json_encode($response);
            }
        } else {
            // required field is missing
            $response["success"] = 0;
            $response["message"] = "Outlet is empty !";

            // echoing JSON response
            echo json_encode($response);
        }
    }

    function get_current_time() {
        // required field is missing
        $result["success"] = 1;
        $result["message"] = date('H:i:s');
        // echoing JSON response
        echo json_encode($result);
    }

    //save visit to the database and send mail to the responsable
    function save_daily_visit() {
        log_message('error', json_encode($_POST));
        $pointing_img = '';
        $order_img = '';



        if (isset($_POST['visit'])) {

            if (isset($_FILES['pointing'])) {
                $file_path = "./uploads/pointing/";
                $pointing_img = date('Y') . '-pointing' . time() . '.jpg';
                $file_pointing_path = $file_path . $pointing_img;
                move_uploaded_file($_FILES['pointing']['tmp_name'], $file_pointing_path);
            }

          /*  if (isset($_FILES['order'])) {
                $file_path = "./uploads/order/";
                $order_img = date('Y') . '-order' . time() . '.jpg';
                $file_order_path = $file_path . $order_img;
                move_uploaded_file($_FILES['order']['tmp_name'], $file_order_path);
            }
           * 
           */

            $visit = json_decode($_POST['visit']);

            $admin_id = $visit->admin_id;
            $outlet_id = $visit->outlet_id;
            //Was there
            $outlet = $this->Outlet_model->get_outlet($outlet_id);
            $was_there = was_there($outlet->latitude, $outlet->longitude, $visit->latitude, $visit->longitude);

            $visit_date = $visit->date;
            $visitUniqueId = $outlet_id . $admin_id . str_replace("-", "", $visit_date) . "0";

            if ($this->Ws_model->is_visit_uploaded($visitUniqueId, 0)) {
                //$this->Ws_model->delete_model_unique_id($visitUniqueId);
                //$this->Ws_model->delete_visit_unique_id($visitUniqueId);
            }

            // Real exit time (from server)
            $entry_time = $visit->entry_time;
            $worked_time = $visit->worked_time;
            $exit_time = millisecondes_to_time(time_to_millisecondes($entry_time) + $worked_time);

            $ha_product_ids = $this->Ws_model->get_ha_products_by_outlet($outlet_id);



            $save_visit['admin_id'] = $admin_id;
            $save_visit['uniqueId'] = $visitUniqueId;
            $save_visit['outlet_id'] = $outlet_id;
            $save_visit['entry_time'] = $visit->entry_time;
            if (isset($visit->mobile_entry_time)) {
                $save_visit['mobile_entry_time'] = $visit->mobile_entry_time;
            }
            $save_visit['exit_time'] = $exit_time;
            $save_visit['mobile_exit_time'] = $visit->exit_time;
            $save_visit['worked_time'] = $visit->worked_time;
            if (isset($visit->remark)) {
                $save_visit['remark'] = $visit->remark;
            }

            if (isset($visit->exit_longitude)) {
                $save_visit['exit_longitude'] = $visit->exit_longitude;
            }
            if (isset($visit->exit_latitude)) {
                $save_visit['exit_latitude'] = $visit->exit_latitude;
            }
            $save_visit['oos_perc'] = $visit->oos_perc;
            $save_visit['date'] = $visit_date;
            $save_visit['w_date'] = firstDayOf('week', new DateTime($visit_date));
            $save_visit['m_date'] = firstDayOf('month', new DateTime($visit_date));
            $save_visit['q_date'] = firstDayOf('quarter', new DateTime($visit_date));
            $save_visit['longitude'] = $visit->longitude;
            $save_visit['latitude'] = $visit->latitude;
            // Availibility 
            $save_visit['monthly_visit'] = 0;
            $save_visit['was_there'] = $was_there;
            $save_visit['branding_pictures'] = '';
            $save_visit['one_pictures'] = '';
            $save_visit['photos'] = $pointing_img;
           // $save_visit['order_picture'] = $order_img;

            if (isset($visit->order_num)) {
                $save_visit['order_num'] = $visit->order_num;
            }
            if (isset($visit->order_amt)) {
                $save_visit['order_amt'] = $visit->order_amt;
            }

            $visit_id = $this->Ws_model->save_visit($save_visit);

            if ($visit_id) {
                $i = 0;
                $oos_array = array();
                $nb_oos = 0;
                $nb_models = 0;
                while (isset($visit->models[$i])) {
                    $model = $visit->models[$i];
                    $product_id = $model->product_id;
                    $brand_id = $model->brand_id;

                    $av = $model->av;

                    //print_r($product_ids);
                    //  if (in_array($product_id, $ha_product_ids)) {
                    //  $av = 2;
                    // }
                    $save_model['visit_id'] = $visit_id;
                    $save_model['visit_uniqueId'] = $model->visit_uniqueId;
                    $save_model['product_id'] = $product_id;
                    $save_model['brand_id'] = $brand_id;
                    $save_model['category_id'] = $model->category_id;
                    $save_model['cluster_id'] = $model->cluster_id;
                    $save_model['product_group_id'] = $model->product_group_id;
                    $save_model['target'] = $model->target;
                    $save_model['av'] = $av;
                    $save_model['av_sku'] = $model->av_sku;
                    $save_model['nb_sku'] = $model->nb_sku;
                    $save_model['sku_display'] = $model->sku_display;

                    $save_model['shelf'] = $model->shelf;
                    $save_model['promo_price'] = $model->promo_price;
                    $save_model['price'] = $model->standard_price;

                    $result = $this->Ws_model->save_model($save_model);
                    if (($brand_id == 1) && ($av != 2) ) {
                        $nb_models = $nb_models + 1;
                    }
                    if (($brand_id == 1) && ($av == 0)) {
                        $oos_array[] = $product_id;
                        $nb_oos = $nb_oos + 1;
                    }

                    $i++;

                   /* if (isset($product_id)) {
                        if ($av == 2) {
                            $ha['id'] = false;
                            $ha['product_id'] = $product_id;
                            $ha['outlet_id'] = $outlet_id;
                            $this->Ws_model->save_ha($ha);
                        } else {
                            $ha['product_id'] = $product_id;
                            $ha['outlet_id'] = $outlet_id;
                            $this->Ws_model->delete_ha($ha);
                        }
                    }
                    * *
                    */
                    
                }

                $j = 0;
                $branding_pictures = array();
                $one_pictures = array();
                 $order_pictures = array();


                while (isset($_FILES['before' . $j])) {

                    $file_path = "./uploads/branding/";
                    $before_img = date('Y') . '-before' . time() . $j . '.jpg';
                    $after_img = date('Y') . '-after' . time() . $j . '.jpg';
                    $file_path1 = $file_path . $before_img;
                    $file_path2 = $file_path . $after_img;

                    if ((move_uploaded_file($_FILES['before' . $j]['tmp_name'], $file_path1)) && (move_uploaded_file($_FILES['after' . $j]['tmp_name'], $file_path2))) {
                        $branding[] = $before_img;
                        $branding[] = $after_img;
                        if (in_array($branding, $branding_pictures) == false)
                            array_push($branding_pictures, $branding);
                        $branding = array();
                    }
                    $j++;
                }

                $y = 0;
                while (isset($_FILES['picture' . $y])) {

                    $file_path4 = "./uploads/branding/";
                    $one_img = date('Y') . '-one' . time() . $y . '.jpg';
                    $file_path4 = $file_path4 . $one_img;
                    if ((move_uploaded_file($_FILES['picture' . $y]['tmp_name'], $file_path4))) {

                        if (in_array($one_img, $one_pictures) == false)
                            $one_pictures[] = $one_img;
                    }
                    $y++;
                }
                
                $z = 0;
                while (isset($_FILES['order' . $z])) {

                    $file_path5 = "./uploads/order/";
                    $order_img = date('Y') . '-order' . time() . $z . '.jpg';
                    $file_path5 = $file_path5 . $order_img;
                    if ((move_uploaded_file($_FILES['order' . $z]['tmp_name'], $file_path5))) {

                        if (in_array($order_img, $order_pictures) == false)
                            $order_pictures[] = $order_img;
                    }
                    $z++;
                }



                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.capesolution.tn';
                $config['smtp_port'] = 587;
                $config['smtp_user'] = 'hcs@capesolution.tn';
                $config['smtp_pass'] = 'henkel2016';
                $config['mailtype'] = 'html';
                $this->load->library('email', $config);
                $responsible_id = $this->Ws_model->get_outlet($outlet_id)->responsible_id;
                $outlet_name = $this->Ws_model->get_outlet($outlet_id)->name;


                if (($responsible_id > 0) && ($oos_perc > 0)) {

                    $responsible_mail = $this->Ws_model->get_responsible_mail($responsible_id);
                    $this->email->set_newline("\r\n");
                    $this->email->from('hcs@capesolution.tn', 'Henkel BCM');
                    //$this->email->to("raafet.dhaouadi@esprit.tn");
                    $this->email->to($responsible_mail);
                    $this->email->subject('OOS Reports ' . $outlet_name . ' ' . $visit_date);

                    $message = '';
                    $message = $message . '<p> Bonjour; <br><br> Veuillez trouver ci-dessous la liste de nos produits en rupture: <br>';
                    $i = 0;

                    foreach ($oos_array as $row) {
                        $i++;
                        $product = $this->Ws_model->get_product($row);
                        $message = $message . '<p>' . $i . '- ' . $product->cab . ' ' . $product->name . '<br>';
                    }
                    $message = $message . '<br> Cordialement,';
                    $this->email->message($message);
                    //$this->email->send();
                    //save email in database

                    $save_email['responsible_email'] = $responsible_mail;
                    $save_email['message'] = $message;
                    $save_email['date'] = date('Y-m-d H:i:s');
                    $save_email['outlet_name'] = $outlet_name;
                    $save_email['outlet_id'] = $outlet_id;
                    $save_email['responsible_id'] = $responsible_id;
                    $this->Ws_model->save_email($save_email);
                }

                //$save_pictures['id'] = false;
                $save_pictures['id'] = $visit_id;
                $save_pictures['branding_pictures'] = json_encode($branding_pictures);
                $save_pictures['one_pictures'] = json_encode($one_pictures);
                $save_pictures['order_picture'] = json_encode($order_pictures);

                $save_pictures1['visit_id'] = $visit_id;
                $save_pictures1['branding_pictures'] = json_encode($branding_pictures);
                $save_pictures1['one_pictures'] = json_encode($one_pictures);
                $this->Ws_model->save_visit_picture($save_pictures1);

                $oos_perc_pe = ($nb_oos / $nb_models) * 100;  
                $save_pictures['oos_perc'] = number_format((float) $oos_perc_pe, 2, '.', '');

                $this->Ws_model->update_visit2($save_pictures);


                //$response["success"] = 1;
                //$response["message"] = "ddd";
                //echo json_encode($response);
                //die();
                $response["success"] = 1;
                $response["message"] = "Visit successfully uploaded.";

                echo json_encode($response);
            } else {

                $response["success"] = 0;
                $response["message"] = "Oops! An error occurred.";

                echo json_encode($response);
            }
        } else {

            $response["success"] = 0;
            $response["message"] = "Oops! Temporary server error.";

            echo json_encode($response);
        }
    }

    function save_monthly_visit() {
        if (isset($_POST['visitGroup'])) {
            $visit_object = json_decode($_POST['visitGroup']);
            $uniqueId = $visit_object->uniqueId;
            $nb_models = 0;
            $nb_shelf_henkel = 0;
            $nb_all_shelf = 0;
            if ($this->Ws_model->is_visit_uploaded($uniqueId, 1)) {
                $this->Ws_model->delete_model_unique_id($uniqueId);
                $this->Ws_model->delete_visit_unique_id($uniqueId);
            }
            //save visit
            $outlet_id = $visit_object->outlet_id;
            $visit_date = $visit_object->date;
            $admin_id = $visit_object->admin_id;
            $visitUniqueId = $outlet_id . $admin_id . str_replace("-", "", $visit_date);

            if ($this->Ws_model->is_visit_uploaded($visitUniqueId, 1)) {
                $this->Ws_model->delete_model_unique_id($visitUniqueId);
                $this->Ws_model->delete_visit_unique_id($visitUniqueId);
            }
            $visit['admin_id'] = $visit_object->admin_id;
            $visit['uniqueId'] = $visit_object->uniqueId;
            $visit['outlet_id'] = $outlet_id;
            $visit['entry_time'] = $visit_object->entry_time;
            $visit['exit_time'] = $visit_object->exit_time;
            if (isset($visit_object->worked_time)) {
                $visit['worked_time'] = $visit_object->worked_time;
            }
            $oos_perc = $visit_object->oos_perc;
            if (isset($visit_object->remark)) {
                $visit['remark'] = $visit_object->remark;
            }
            $visit['oos_perc'] = $oos_perc;
            $visit['date'] = $visit_date;
            $visit['w_date'] = firstDayOf('week', new DateTime($visit['date']));
            $date1 = strtotime($visit['date']);



            $final2 = date("m", strtotime("+0 month", $date1));


            $visit['m_date'] = firstDayOf('month', new DateTime($visit['date']));
            $visit['q_date'] = firstDayOf('quarter', new DateTime($visit['date']));
            $visit['longitude'] = $visit_object->longitude;
            $visit['latitude'] = $visit_object->latitude;
            $visit['monthly_visit'] = 1;
            $visit['was_there'] = $visit_object->was_there;
            $visit['branding_pictures'] = '';
            $visit['one_pictures'] = '';

            $visit_id = $this->Ws_model->save_visit($visit);

            if ($visit_id) {

                $i = 0;
                $oos_array = array();
                $ha_array = array();
                while (isset(json_decode($_POST['visitGroup'])->modelsGroup[$i])) {
                    $model_object = $visit_object->modelsGroup[$i];
                    $product_group_id = $model_object->product_group_id;
                    $brand_id = $model_object->brand_id;
                    $av = $model_object->av;
                    $model['visit_id'] = $visit_id;
                    $model['visit_uniqueId'] = $visitUniqueId;
                    $model['brand_id'] = $brand_id;
                    $model['category_id'] = $model_object->category_id;
                    $model['cluster_id'] = $model_object->cluster_id;
                    $model['product_group_id'] = $product_group_id;
                    $model['target'] = $model_object->target;
                    $model['y'] = $model_object->y - 10;
                    $model['av'] = $av;
                    $model['av_sku'] = $model_object->av_sku;
                    $model['nb_sku'] = $model_object->nb_sku;
                    $model['sku_display'] = $model_object->sku_display;
                    $model['shelf'] = $model_object->shelf;
                    $model['promo_price'] = $model_object->promo_price;
                    $model['price'] = $model_object->standard_price;
                    $model['metrage_unit'] = $model_object->metrage;
                    $model['total_metrage'] = $model['shelf'] * $model['metrage_unit'];
                    $result = $this->Ws_model->save_model($model);
                    if ($brand_id == 18) {
                        $nb_models = $nb_models + 1;
                        $nb_shelf_henkel = $nb_shelf_henkel + $model_object->shelf;
                    }

                    $nb_all_shelf = $nb_all_shelf + $model_object->shelf;
                    if (($brand_id == 18) && ($av == 0)) {
                        $oos_array[] = $product_group_id;
                    }
                    $i++;
                } //end foreach models
                // C'est quoi ca !!!!!!!!!!!!!!!!!!!!!!
                $outlet_save['id'] = $outlet_id;

                $this->Outlet_model->save($outlet_save);


                $j = 0;
                $branding_pictures = array();
                $one_pictures = array();
                while (isset($_FILES['before' . $j])) {

                    $file_path = "./uploads/branding/";
                    $file_path1 = $file_path . basename($_FILES['before' . $j]['name']);
                    $file_path2 = $file_path . basename($_FILES['after' . $j]['name']);
                    if ((move_uploaded_file($_FILES['before' . $j]['tmp_name'], $file_path1)) && (move_uploaded_file($_FILES['after' . $j]['tmp_name'], $file_path2))) {

                        $branding[] = $_FILES['before' . $j]['name'];
                        $branding[] = $_FILES['after' . $j]['name'];
                        array_push($branding_pictures, $branding);
                        $branding = array();
                    }
                    $j++;
                }


                $y = 0;
                while (isset($_FILES['picture' . $y])) {

                    $file_path4 = "./uploads/branding/";
                    $file_path4 = $file_path4 . basename($_FILES['picture' . $y]['name']);
                    if ((move_uploaded_file($_FILES['picture' . $y]['tmp_name'], $file_path4))) {

                        $one_pictures[] = $_FILES['picture' . $y]['name'];
                    }
                    $y++;
                }


                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.capesolution.tn';
                $config['smtp_port'] = 587;
                $config['smtp_user'] = 'hcs@capesolution.tn';
                $config['smtp_pass'] = 'henkel2016';
                $config['mailtype'] = 'html';
                $this->load->library('email', $config);
                $responsible_id = $this->Ws_model->get_outlet($outlet_id)->responsible_id;
                $outlet_name = $this->Ws_model->get_outlet($outlet_id)->name;

                if (($responsible_id > 0) && ($oos_perc > 0)) {

                    $responsible_mail = $this->Ws_model->get_responsible_mail($responsible_id);

                    $this->email->set_newline("\r\n");

                    $this->email->from('hcs@capesolution.tn', 'Henkel HCS');

                    $this->email->to($responsible_mail);

                    $this->email->subject('OOS Reports ' . $outlet_name . ' ' . $visit_date);


                    $message = '';
                    $message = $message . '<p> Bonjour; <br><br> Veuillez trouver ci-dessous la liste de nos produits en rupture: <br>';
                    $i = 0;
                    foreach ($oos_array as $row) {
                        $i++;
                        $message = $message . '<p>' . $i . '- ' . $this->Ws_model->get_product_name($row) . '<br>';
                    }
                    $message = $message . '<br> Cordialement,';
                    $this->email->message($message);
                    $this->email->send();

                    //save email in database

                    $save_email['responsible_email'] = $responsible_mail;
                    $save_email['message'] = $message;
                    $save_email['date'] = date('Y-m-d H:i:s');
                    $save_email['outlet_name'] = $outlet_name;
                    $save_email['outlet_id'] = $outlet_id;
                    $save_email['responsible_id'] = $responsible_id;
                    $this->Ws_model->save_email($save_email);
                }


                $save_pictures['id'] = $visit_id;
                $save_pictures['branding_pictures'] = json_encode($branding_pictures);
                $save_pictures['one_pictures'] = json_encode($one_pictures);
                if ($nb_all_shelf > 0) {
                    $perc_shelf = ($nb_shelf_henkel / $nb_all_shelf) * 100;
                    $save_pictures['shelf_perc'] = number_format((float) $perc_shelf, 2, '.', '');
                }
                $this->Ws_model->update_visit2($save_pictures);


                $response["success"] = 1;
                $response["message"] = "Visit successfully uploaded.";

                echo json_encode($response);
            } else {

                $response["success"] = 0;
                $response["message"] = "Oops! An error occurred.";
                // Delete models && visit
                $this->Ws_model->delete_visit($visit_id);
                $this->Ws_model->delete_models($visit_id);

                echo json_encode($response);
            }
        } else {

            $response["success"] = 0;
            $response["message"] = "Oops! Temporary server error.";
            // Delete models && visit
            $this->Ws_model->delete_visit($visit_id);
            $this->Ws_model->delete_models($visit_id);
            echo json_encode($response);
        }
    }

    // Authentification
    function login() {

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $save['email'] = $_POST['email'];
            $save['password'] = $_POST['password'];
            $result = $this->Ws_model->login($save['email'], $save['password']);

            if ($result) {
                $result["success"] = 1;
                $result["message"] = "success login.";

                // echoing JSON response
                echo json_encode($result);
            } else {

                $result["success"] = 0;
                $result["message"] = "Oops! An error occurred.";

                // echoing JSON response
                echo json_encode($result);
            }
        } else {
            // required field is missing
            $result["success"] = 0;
            $result["message"] = "Required field(s) is missing";

            // echoing JSON response
            echo json_encode($result);
        }
    }

    function update_messages() {

        if (isset($_POST['user_id'])) {
            $result = $this->Ws_model->update_messages($_POST['user_id']);
            if ($result) {
                $result_arr["success"] = 1;
                $result_arr["message"] = "success update.";
                // echoing JSON response
                echo json_encode($result_arr);
            } else {
                $result_arr["success"] = 0;
                $result_arr["message"] = "Oops! An error occurred.";
                // echoing JSON response
                echo json_encode($result_arr);
            }
        } else {
            // required field is missing
            $result_arr["success"] = 0;
            $result_arr["message"] = "Required field(s) is missing";

            // echoing JSON response
            echo json_encode($result_arr);
        }
    }

    //correct monthly_visit_visits
    function save_visit_into_db($visit_object) {

        $uniqueId = json_decode($_POST['visit'])->uniqueId;
        $nb_models = 0;
        $nb_shelf_henkel = 0;
        $nb_all_shelf = 0;
        if ($this->Ws_model->is_visit_uploaded($uniqueId, 0)) {
            $this->Ws_model->delete_model_unique_id($uniqueId);
            $this->Ws_model->delete_visit_unique_id($uniqueId);
        }
        //save visit
        $visit = json_decode($_POST['visit']);
        $outlet_id = $visit->outlet_id;
        $visit_date = $visit->date;
        $admin_id = $visit->admin_id;
        $visitUniqueId = $outlet_id . $admin_id . str_replace("-", "", $visit_date);
        if ($this->Ws_model->is_visit_uploaded($visitUniqueId, 0)) {
            $this->Ws_model->delete_model_unique_id($visitUniqueId);
            $this->Ws_model->delete_visit_unique_id($visitUniqueId);
        }
        $save_visit['admin_id'] = $admin_id;
        $save_visit['uniqueId'] = $visitUniqueId;
        $save_visit['outlet_id'] = $outlet_id;

        $save_visit['entry_time'] = $visit->entry_time;
        $save_visit['exit_time'] = $visit->exit_time;
        $save_visit['worked_time'] = $visit->worked_time;
        $oos_perc = $visit->oos_perc;
        if (isset($visit->remark)) {
            $save_visit['remark'] = $visit->remark;
        }
        $save_visit['oos_perc'] = $oos_perc;
        $save_visit['date'] = $visit_date;
        $save_visit['w_date'] = firstDayOf('week', new DateTime($visit_date));
        $save_visit['m_date'] = firstDayOf('month', new DateTime($visit_date));
        $save_visit['longitude'] = $visit->longitude;
        $save_visit['latitude'] = $visit->latitude;
        $save_visit['monthly_visit'] = 0;
        $save_visit['was_there'] = $visit->was_there;
        $save_visit['branding_pictures'] = '';
        $save_visit['one_pictures'] = '';
        return $this->Ws_model->save_visit($save_visit);
    }

    function save_models_into_db($model, $visit_id) {
        $visit = json_decode($_POST['visit']);
        $outlet_id = $visit->outlet_id;
        $visit_date = $visit->date;
        $admin_id = $visit->admin_id;
        $visitUniqueId = $outlet_id . $admin_id . str_replace("-", "", $visit_date);
        $product_id = $model->product_id;
        $brand_id = $model->brand_id;
        $av = $model->av;
        $save_model['visit_id'] = $visit_id;
        $save_model['visit_uniqueId'] = $visitUniqueId;
        $save_model['product_id'] = $product_id;
        $save_model['brand_id'] = $brand_id;
        $save_model['category_id'] = $model->category_id;
        $save_model['cluster_id'] = $model->cluster_id;
        $save_model['product_group_id'] = $model->product_group_id;
        $save_model['target'] = $model->target;
        $save_model['av'] = $av;
        $save_model['av_sku'] = $model->av_sku;
        $save_model['nb_sku'] = $model->nb_sku;
        $save_model['sku_display'] = $model->sku_display;
        $save_model['shelf'] = $model->shelf;
        $save_model['promo_price'] = $model->promo_price;
        $save_model['price'] = $model->standard_price;
        return $this->Ws_model->save_model($save_model);
    }

    // pint error to android
    function error_reports($error_msg) {

        $response["success"] = 0;
        $response["message"] = $error_msg;
        echo json_encode($response);
        die();
    }

    function send_email($outlet_id) {
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'smtp.capesolution.tn';
        $config['smtp_port'] = 587;
        $config['smtp_user'] = 'hcs@capesolution.tn';
        $config['smtp_pass'] = 'henkel2016';
        $config['mailtype'] = 'html';
        $this->load->library('email', $config);
        $responsible_id = $this->Ws_model->get_outlet($outlet_id)->responsible_id;
        $outlet_name = $this->Ws_model->get_outlet($outlet_id)->name;



        if (($responsible_id > 0) && ($oos_perc > 0)) {

            $responsible_mail = $this->Ws_model->get_responsible_mail($responsible_id);

            $this->email->set_newline("\r\n");

            $this->email->from('hcs@capesolution.tn', 'Henkel HCS');

            $this->email->to($responsible_mail);

            $this->email->subject('OOS Reports ' . $outlet_name . ' ' . $visit_date);


            $message = '';
            $message = $message . '<p> Bonjour; <br><br> Veuillez trouver ci-dessous la liste de nos produits en rupture: <br>';
            $i = 0;
            foreach ($oos_array as $row) {
                $i++;
                $message = $message . '<p>' . $i . '- ' . $this->Ws_model->get_product_name($row) . '<br>';
            }
            $message = $message . '<br> Cordialement,';
            $this->email->message($message);
            $this->email->send();

            //save email in database

            $save_email['responsible_email'] = $responsible_mail;
            $save_email['message'] = $message;
            $save_email['date'] = date('Y-m-d H:i:s');
            $save_email['outlet_name'] = $outlet_name;
            $save_email['outlet_id'] = $outlet_id;
            $save_email['responsible_id'] = $responsible_id;
            $this->Ws_model->save_email($save_email);
        }
    }

//save pictures into database
    function savePictures() {
        $j = 0;
        $branding_pictures = array();
        $one_pictures = array();
        while (isset($_FILES['before' . $j])) {
            $file_path = "./uploads/branding/";
            $file_path1 = $file_path . basename($_FILES['before' . $j]['name']);
            $file_path2 = $file_path . basename($_FILES['after' . $j]['name']);
            if ((move_uploaded_file($_FILES['before' . $j]['tmp_name'], $file_path1)) && (move_uploaded_file($_FILES['after' . $j]['tmp_name'], $file_path2))) {
                $branding[] = $_FILES['before' . $j]['name'];
                $branding[] = $_FILES['after' . $j]['name'];
                array_push($branding_pictures, $branding);
                $branding = array();
            }
            $j++;
        }
        $y = 0;
        while (isset($_FILES['picture' . $y])) {

            $file_path4 = "./uploads/branding/";
            $file_path4 = $file_path4 . basename($_FILES['picture' . $y]['name']);
            if ((move_uploaded_file($_FILES['picture' . $y]['tmp_name'], $file_path4))) {
                $one_pictures[] = $_FILES['picture' . $y]['name'];
            }
            $y++;
        }
        $save_pictures['id'] = $visit_id;
        $save_pictures['branding_pictures'] = json_encode($branding_pictures);
        $save_pictures['one_pictures'] = json_encode($one_pictures);
        $this->Ws_model->update_visit2($save_pictures);
    }

    function upload_branding() {

        $images = array();

        $i = 0;
        while (isset($_FILES['image' . $i])) {
            print_r($_FILES['image' . $i]);

            $file_path = 'hcs/uploads/';
            $file_path = $file_path . basename($_FILES['image' . $i]['name']);
            if (move_uploaded_file($_FILES['image' . $i]['tmp_name'], $file_path)) {
                array_push($images, $_FILES['image' . $i]['name']);
                echo "success";
            } else {
                echo "fail";
            }
            $i++;
        }
        print_r($images);
        print_r($_POST['test']);
        //print_r($obj->description);
        print_r(json_decode($_POST['test'])->name);
    }

    function registerId() {

        if (isset($_POST['id']) && isset($_POST['register_id'])) {
            $id = $_POST['id'];
            $register_id = $_POST['register_id'];


            $result = $this->Ws_model->update_admin($id, $register_id);

            if ($result) {
                $response["success"] = 1;
                $response["message"] = "success initialize.";

                // echoing JSON response
                echo json_encode($response);
            } else {

                $response["success"] = 0;
                $response["message"] = "Oops! An error occurred.";

                // echoing JSON response
                echo json_encode($response);
            }
        } else {
            // required field is missing
            $response["success"] = 0;
            $response["message"] = "Required field(s) is missing";

            // echoing JSON response
            echo json_encode($response);
        }
    }

    function getMessagesByReceiverId() {

        if (isset($_POST['id'])) {
            $id = $_POST['id'];


            $result = $this->Ws_model->get_messages_by_receiver_id($id);

            echo json_encode($result);
        } else {
            // required field is missing
            $response["success"] = 0;
            $response["message"] = "Required field(s) is missing";

            // echoing JSON response
            echo json_encode($response);
        }
    }

    function initializeVIVO() {
        $users = $this->Ws_model->get_ws_clients();
        //print(json_encode($users));
        print(json_encode(array('clients' => $clients)));
    }

    function initialize() {
        $users = $this->Ws_model->get_ws_users();
        //print(json_encode($users));
        print(json_encode(array('users' => $users)));

        /*

          $outlets= $this->Ws_model->get_ws_outlets();
          print(json_encode(array('outlets' => $outlets)));

          $products = $this->Ws_model->get_ws_products();
          print(json_encode(array('products' => $products)));
         */
    }

    function get_history_visits($user_id) {
        $visits = $this->Ws_model->get_history_visits($user_id);
        print(json_encode($visits));
    }

    function get_monthly_history_visits($user_id) {
        $visits = $this->Ws_model->get_monthly_history_visits($user_id);
        print(json_encode($visits));
    }

    function get_oos_tracking($outlet_id) {
        $products = $this->Ws_model->get_oos_tracking($outlet_id);
        print(json_encode($products));
    }

    function count_visits($user_id) {
        $result['daily'] = $this->Ws_model->count_visits($user_id, array('0'));
        $result['shelf'] = $this->Ws_model->count_visits($user_id, array('1', '3'));
        $result['price'] = $this->Ws_model->count_visits($user_id, array('2', '3'));
        $result['messages'] = $this->Ws_model->count_messages($user_id);
        $result['apk_url'] = $this->Ws_model->get_last_apk_url();
        print(json_encode($result));
    }

    //get users
    function users() {
        $users = $this->Ws_model->get_ws_users();
        print(json_encode($users));
    }

    function ha() {
        $ha = $this->Ws_model->get_ws_ha();
        print(json_encode($ha));
    }

    function channels() {
        $channels = $this->Ws_model->get_ws_channel();
        print(json_encode($channels));
    }

    function sub_channels() {
        $sub_channels = $this->Ws_model->get_ws_sub_channel();
        print(json_encode($sub_channels));
    }

    function clients() {
        $users = $this->Ws_model->get_ws_clients();
        print(json_encode($clients));
    }

    //get outlets
    function outlets() {
        $outlets = $this->Ws_model->get_ws_outlets();
        print(json_encode($outlets));
    }

    //get outlets
    function outlets_by_user($user_id) {
        $outlets = $this->Ws_model->get_ws_outlets_by_user($user_id);
        print(json_encode($outlets));
    }

    //get products
    function products() {
        $products = $this->Ws_model->get_ws_products();
        print(json_encode($products));
    }

    function categories() {
        $categories = $this->Ws_model->get_ws_categories();
        print(json_encode($categories));
    }

    function subCategories() {

        $subCategories = $this->Ws_model->get_ws_sub_categories();
        print(json_encode($subCategories));
    }

    function productTypes() {
        $productTypes = $this->Ws_model->get_product_types();

        print(json_encode($productTypes));
    }

    function productGroups() {
        $productGroups = $this->Ws_model->get_product_groups();
        print(json_encode($productGroups));
    }

    function clusters() {
        $clusters = $this->Ws_model->get_clusters();
        print(json_encode($clusters));
    }

    function brands() {
        $brands = $this->Ws_model->get_ws_brands();
        print(json_encode($brands));
    }

    function zones() {
        $zones = $this->Ws_model->get_ws_zones();
        print(json_encode($zones));
    }

    function states() {
        $states = $this->Ws_model->get_ws_states();
        print(json_encode($states));
    }

    //get Ha_products
    function get_ha_by_user($user_id) {
        $ha_products = $this->Ws_model->get_ws_ha_products($user_id);
        print(json_encode($ha_products));
    }

    //correct monthly_visit_visits
    function correctDBMonthly() {
        $visit_array = $this->Ws_model->get_ws_visits();
        foreach ($visit_array as $visit) {
            if (isset($visit->id)) {

                $model_array = $this->Ws_model->get_models_by_visit_id($visit->id);
                foreach ($model_array as $model) {
                    if (($model->shelf) > 1 && $visit->monthly_visit = 0) {
                        $this->Ws_model->update_visit_monthly($visit_id);
                    }
                }
            }
        }
    }

    function av_visits() {
        $visits = $this->Ws_model->get_ws_av_visits();
        print(json_encode($visits));
    }

    function av_models() {

        if (isset($_POST['visit_id'])) {
            $visit_id = $_POST['visit_id'];


            $result = $this->Ws_model->get_av_models($visit_id);

            echo json_encode($result);
        } else {
            // required field is missing
            $response["success"] = 0;
            $response["message"] = "Required field(s) is missing";

            // echoing JSON response
            echo json_encode($response);
        }
    }

    // Team Leader Project


    function saveTeamLeaderVisit() {

        if (isset($_POST['visit'])) {
            $visit = json_decode($_POST['visit']);

            $save_visit['visit_id'] = $visit->visit_id;
            $save_visit['outlet_id'] = $visit->outlet_id;
            $save_visit['user_id'] = $visit->user_id;
            $save_visit['app_name'] = $visit->app_name;
            $save_visit['outlet_name'] = $visit->outlet_name;
            $save_visit['date'] = $visit->visit_date;

            $visit_date = $visit->visit_date;
            $save_visit['w_date'] = firstDayOf('week', new DateTime($visit_date));
            $save_visit['m_date'] = firstDayOf('month', new DateTime($visit_date));
            $save_visit['q_date'] = firstDayOf('quarter', new DateTime($visit_date));

            $save_visit['entry_time'] = $visit->entry_time;
            $save_visit['exit_time'] = $visit->exit_time;
            $save_visit['entry_location'] = $visit->entry_location;
            $save_visit['exit_location'] = $visit->exit_location;
            $save_visit['longitude'] = $visit->longitude;
            $save_visit['latitude'] = $visit->latitude;
            $save_visit['type'] = $visit->type;
            $save_visit['rating'] = $visit->rating;

            $visit_id = $this->Ws_model->save_tl_visit($save_visit);


            if ($visit_id) {
                $i = 0;
                $file_path = "./uploads/teamleader/";

                while (isset($visit->interventions[$i])) {

                    $intervention = $visit->interventions[$i];
                    $save_intervention['visit_id'] = $visit_id;
                    $save_intervention['type'] = $intervention->type;
                    $save_intervention['name'] = $intervention->name;
                    $save_intervention['remark'] = $intervention->remark;

                    if ($intervention->type == 4) {
                        if (!empty($_FILES['before' . $i]) && !empty($_FILES['after' . $i])) {
                            $file_path1 = $file_path . basename($_FILES['before' . $i]['name']);
                            $file_path2 = $file_path . basename($_FILES['after' . $i]['name']);
                            if ((move_uploaded_file($_FILES['before' . $i]['tmp_name'], $file_path1)) && (move_uploaded_file($_FILES['after' . $i]['tmp_name'], $file_path2))) {

                                $save_intervention['before'] = $_FILES['before' . $i]['name'];
                                $save_intervention['after'] = $_FILES['after' . $i]['name'];
                                $save_intervention['photo'] = null;
                            }
                        }
                    } else {
                        if (!empty($_FILES['picture' . $i])) {
                            $file_path3 = $file_path . basename($_FILES['picture' . $i]['name']);

                            if (move_uploaded_file($_FILES['picture' . $i]['tmp_name'], $file_path3)) {

                                $save_intervention['photo'] = $_FILES['picture' . $i]['name'];
                                $save_intervention['before'] = null;
                                $save_intervention['after'] = null;
                            }
                        }
                    }

                    $intervention_id = $this->Ws_model->save_tl_intervention($save_intervention);

                    if ($intervention_id && isset($intervention->action)) {

                        $action = $intervention->action;
                        $save_action['intervention_id'] = $intervention_id;
                        $save_action['remark'] = $action->remark;
                        if (!empty($_FILES['action' . $i])) {
                            $file_path4 = $file_path . basename($_FILES['action' . $i]['name']);

                            if (move_uploaded_file($_FILES['action' . $i]['tmp_name'], $file_path4)) {

                                $save_action['photo'] = $_FILES['action' . $i]['name'];
                            }
                        }
                        $action_id = $this->Ws_model->save_tl_action($save_action);
                    }
                    $i++;
                }
            }

            $response["success"] = 1;
            $response["message"] = "Visit successfully uploaded.";

            echo json_encode($response);
        } else {

            $response["success"] = 0;
            $response["message"] = "Oops! Temporary server error.";

            echo json_encode($response);
        }
    }

}
