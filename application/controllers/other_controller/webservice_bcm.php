<?php

class Webservices_bcm extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array('Ws_model_bcm', 'Outlet_model'));
        $this->load->helper(array('date'));
        $this->load->helper('formatting_helper');
    }

    function login() {

        if (isset($_POST['email']) && isset($_POST['password'])) {
            $save['email'] = $_POST['email'];
            $save['password'] = $_POST['password'];


            $result = $this->Ws_model_bcm->login($save['email'], $save['password']);

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

    function insert_outlet() {

        header('Content-type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        $save['admin_id'] = $data['admin_id'];
        $save['name'] = $data['name'];
        $save['state'] = $data['state'];
        $save['adress'] = $data['adress'];
        $save['delegation'] = $data['delegation'];
        $save['contact_pdv'] = $data['contact_pdv'];
        $save['contact'] = $data['contact'];
        $save['longitude'] = $data['longitude'];
        $save['latitude'] = $data['latitude'];

        $result = $this->Ws_model_bcm->save_outlet($save);


        if ($result) {
            // successfully inserted into database
            $response["success"] = 1;
            $response["message"] = "Outlet successfully created.";

            // echoing JSON response
            echo json_encode($response);
        } else {
            // failed to insert row
            $response["success"] = 0;
            $response["message"] = "Outlet name already existed";

            // echoing JSON response
            echo json_encode($response);
        }
    }

    function saveOutlet() {


        if (isset($_POST['outlet'])) {

            if (isset($_FILES['image'])) {

                $file_path = "./uploads/outlet/";
                $file_path = $file_path . basename($_FILES['image']['name']);

                if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                    $save['photos'] = $_FILES['image']['name'];
                }
            }

            $visit_days = array(json_decode($_POST['outlet'])->visit_day);

            $save['zone'] = json_decode($_POST['outlet'])->zone;
            $save['visit_day'] = json_encode($visit_days);
            $save['adress'] = json_decode($_POST['outlet'])->adress;
            $save['source'] = json_decode($_POST['outlet'])->sub_channel;
            $save['sub_channel'] = json_decode($_POST['outlet'])->sub_channel;
            $save['channel'] = json_decode($_POST['outlet'])->channel;
            $save['contact'] = json_decode($_POST['outlet'])->contact;
            $save['contact_pdv'] = json_decode($_POST['outlet'])->contact_pdv;
            $save['delivery_day'] = json_decode($_POST['outlet'])->delivery_day;
            $save['state'] = json_decode($_POST['outlet'])->state;
            $save['latitude'] = json_decode($_POST['outlet'])->latitude;
            $save['longitude'] = json_decode($_POST['outlet'])->longitude;
            $save['caisse_number'] = json_decode($_POST['outlet'])->caisse_number;

            $save['admin_id'] = json_decode($_POST['outlet'])->admin_id;
            $save['name'] = json_decode($_POST['outlet'])->name;


            $result = $this->Ws_model_bcm->save_outlet($save);


            if ($result) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "Outlet successfully uploaded.";

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
            $response["message"] = "Required field(s) is missing";

            // echoing JSON response
            echo json_encode($response);
        }
    }

    function saveVisitWithMail() {
        if (isset($_POST['visit'])) {
            $uniqueId = json_decode($_POST['visit'])->uniqueId;

            if ($this->Ws_model_bcm->is_visit_uploaded($uniqueId)) {
                $this->Ws_model_bcm->delete_model_unique_id($uniqueId);
                $this->Ws_model_bcm->delete_visit_unique_id($uniqueId);
            }
            //save visit
            $visit = json_decode($_POST['visit']);
            $outlet_id = $visit->outlet_id;
            $visit_date = $visit->date;

            $save_visit['admin_id'] = $visit->admin_id;
            $save_visit['uniqueId'] = $visit->uniqueId;
            $save_visit['outlet_id'] = $outlet_id;

            $save_visit['entry_time'] = $visit->entry_time;
            $save_visit['exit_time'] = $visit->exit_time;
            $save_visit['worked_time'] = $visit->worked_time;
            //$visit['last_time']=$visit->last_time;
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
            $save_visit['monthly_visit'] = $visit->monthly_visit;
            $save_visit['was_there'] = $visit->was_there;
            $save_visit['branding_pictures'] = '';
            $save_visit['one_pictures'] = '';

            $visit_id = $this->Ws_model_bcm->save_visit($save_visit);

            if ($visit_id) {

                $i = 0;
                $oos_array = array();
                $ha_array = array();
                $nb_oos = 0;
                $nb_models = 0;
                while (isset($visit->models[$i])) {
                    $model = $visit->models[$i];
                    $product_id = $model->product_id;
                    $brand_id = $model->brand_id;
                    $av = $model->av;
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
                    //$save_model['metrage']=$model->metrage;

                    $result = $this->Ws_model_bcm->save_model($save_model);
                    if (($brand_id == 18) && ($av == 0)) {
                        $oos_array[] = $product_id;
                        $nb_oos++;
                    }

                    if (($brand_id == 18) && ($av != 2)) {
                        $nb_models++;
                    }
                    $i++;

                    if ($av == 2) {
                        $ha_array[] = $product_id;
                        $ha['product_id'] = $product_id;
                        $ha['outlet_id'] = $outlet_id;
                        if(!($this->Ws_model_bcm->is_ha_uploaded($ha)) {
                            $this->Ws_model_bcm->save_ha($ha);
                        }
                    } else {
                        $ha['product_id'] = $product_id;
                        $ha['outlet_id'] = $visit->outlet_id;
                        $this->Ws_model_bcm->delete_ha($ha);
                    }
                } //end foreach models

                $outlet_save['id'] = $outlet_id;
                $outlet_save['ha'] = json_encode($ha_array);

                $this->Outlet_model->save($outlet_save);


                $j = 0;
                $branding_pictures = array();
                $one_pictures = array();
                while (isset($_FILES['before' . $j])) {

                    $file_path = "./uploads/branding/";
                    $file_path1 = $file_path . basename($_FILES['before' . $j]['name']);
                    $file_path2 = $file_path . basename($_FILES['after' . $j]['name']);
                    if ((move_uploaded_file($_FILES['before' . $j]['tmp_name'], $file_path1)) && (move_uploaded_file($_FILES['after' . $j]['tmp_name'], $file_path2))) {

                        //$rayon['visit_id']=$visit_id;
                        //$rayon['visit_uniqueId']=$visit->rayons[$j]->visit_uniqueId;
                        //$rayon['before']=$_FILES['before'.$j]['name'];
                        //$rayon['after']=$_FILES['after'.$j]['name'];
                        $branding[] = $_FILES['before' . $j]['name'];
                        $branding[] = $_FILES['after' . $j]['name'];

                        //$branding_pictures[]=$branding;
                        array_push($branding_pictures, $branding);
                        $branding = array();
                        //$result2=$this -> Ws_model_bcm -> save_rayon($rayon);
                    }
                    $j++;
                }


                $y = 0;
                while (isset($_FILES['picture' . $y])) {

                    $file_path4 = "./uploads/branding/";
                    $file_path4 = $file_path4 . basename($_FILES['picture' . $y]['name']);
                    if ((move_uploaded_file($_FILES['picture' . $y]['tmp_name'], $file_path4))) {

                        //$picture['visit_id']=$visit_id;
                        //$picture['visit_uniqueId']=$visit->pictures[$y]->visit_uniqueId;
                        //$picture['picture']=$_FILES['picture'.$y]['name'];

                        $one_pictures[] = $_FILES['picture' . $y]['name'];

                        //$result3=$this -> Ws_model_bcm -> save_picture($picture);
                    }
                    $y++;
                }


                // save pictures
                //$branding_pictures = substr(json_encode($branding_pictures),1);
                //$branding_pictures = substr($branding_pictures, 0, -1);





                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.capesolution.tn';
                $config['smtp_port'] = 587;
                $config['smtp_user'] = 'hcs@capesolution.tn';
                $config['smtp_pass'] = 'henkel2016';
                $config['mailtype'] = 'html';
                $this->load->library('email', $config);
                $responsible_id = $this->Ws_model_bcm->get_outlet($outlet_id)->responsible_id;
                $outlet_name = $this->Ws_model_bcm->get_outlet($outlet_id)->name;



                if (($responsible_id > 0) && ($oos_perc > 0)) {

                    $responsible_mail = $this->Ws_model_bcm->get_responsible_mail($responsible_id);

                    $this->email->set_newline("\r\n");

                    $this->email->from('hcs@capesolution.tn', 'Henkel HCS');

                    $this->email->to($responsible_mail);

                    $this->email->subject('OOS Reports ' . $outlet_name . ' ' . $visit_date);


                    $message = '';
                    $message = $message . '<p> Bonjour; <br><br> Veuillez trouver ci-dessous la liste de nos produits en rupture: <br>';
                    $i = 0;
                    foreach ($oos_array as $row) {
                        $i++;
                        $message = $message . '<p>' . $i . '- ' . $this->Ws_model_bcm->get_product_name($row) . '<br>';
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
                    $this->Ws_model_bcm->save_email($save_email);
                }


                $save_oos['id'] = $visit_id;
                $save_oos['oos_perc'] = $nb_oos / $nb_models;

                $this->Ws_model_bcm->update_visit2($save_oos);

                $save_pictures['id'] = $visit_id;
                $save_pictures['branding_pictures'] = json_encode($branding_pictures);
                $save_pictures['one_pictures'] = json_encode($one_pictures);

                $this->Ws_model_bcm->update_visit2($save_pictures);


                $response["success"] = 1;
                $response["message"] = "Visit successfully uploaded.";

                echo json_encode($response);
            } else {
                $this->Ws_model_bcm->delete_visit($visit_id);
                $this->Ws_model_bcm->delete_models($visit_id);
                $response["success"] = 0;
                $response["message"] = "Oops! An error occurred.";

                echo json_encode($response);
            }
        } else {

            $response["success"] = 0;
            $response["message"] = "Oops! Temporary server error.";
            $this->Ws_model_bcm->delete_visit($visit_id);
            $this->Ws_model_bcm->delete_models($visit_id);
            echo json_encode($response);
        }
    }

    function saveVisitWithMailTest() {


        if (isset($_POST['visit'])) {
            //save visit

            $outlet_id = json_decode($_POST['visit'])->outlet_id;
            $visit_date = json_decode($_POST['visit'])->date;
            $visit['admin_id'] = json_decode($_POST['visit'])->admin_id;
            $visit['uniqueId'] = json_decode($_POST['visit'])->uniqueId;
            $visit['outlet_id'] = $outlet_id;
            $visit['entry_time'] = json_decode($_POST['visit'])->entry_time;
            $visit['exit_time'] = json_decode($_POST['visit'])->exit_time;
            $visit['worked_time'] = json_decode($_POST['visit'])->worked_time;
            //$visit['last_time']=json_decode($_POST['visit'])->last_time;
            $oos_perc = json_decode($_POST['visit'])->oos_perc;
            if (isset(json_decode($_POST['visit'])->remark)) {
                $visit['remark'] = json_decode($_POST['visit'])->remark;
            }
            $visit['oos_perc'] = $oos_perc;
            $visit['date'] = $visit_date;
            $visit['w_date'] = firstDayOf('week', new DateTime($visit['date']));
            $visit['m_date'] = firstDayOf('month', new DateTime($visit['date']));
            $visit['longitude'] = json_decode($_POST['visit'])->longitude;
            $visit['latitude'] = json_decode($_POST['visit'])->latitude;
            $visit['monthly_visit'] = json_decode($_POST['visit'])->monthly_visit;
            $visit['was_there'] = json_decode($_POST['visit'])->was_there;
            $visit['branding_pictures'] = '';
            $visit['one_pictures'] = '';

            $visit_id = $this->Ws_model_bcm->save_visit($visit);

            if ($visit_id) {

                $i = 0;
                $oos_array = array();
                $ha_array = array();
                while (isset(json_decode($_POST['visit'])->models[$i])) {

                    $brand_id = json_decode($_POST['visit'])->models[$i]->brand_id;
                    $av = json_decode($_POST['visit'])->models[$i]->av;
                    $model['visit_id'] = $visit_id;
                    $model['visit_uniqueId'] = json_decode($_POST['visit'])->models[$i]->visit_uniqueId;
                    $model['product_id'] = json_decode($_POST['visit'])->models[$i]->product_id;
                    $model['brand_id'] = $brand_id;
                    $model['category_id'] = json_decode($_POST['visit'])->models[$i]->category_id;
                    $model['cluster_id'] = json_decode($_POST['visit'])->models[$i]->cluster_id;
                    $model['product_group_id'] = json_decode($_POST['visit'])->models[$i]->product_group_id;
                    $model['target'] = json_decode($_POST['visit'])->models[$i]->target;
                    $model['av'] = $av;
                    $model['av_sku'] = json_decode($_POST['visit'])->models[$i]->av_sku;
                    $model['nb_sku'] = json_decode($_POST['visit'])->models[$i]->nb_sku;
                    $model['sku_display'] = json_decode($_POST['visit'])->models[$i]->sku_display;
                    $model['shelf'] = json_decode($_POST['visit'])->models[$i]->shelf;
                    $model['promo_price'] = json_decode($_POST['visit'])->models[$i]->promo_price;
                    $model['price'] = json_decode($_POST['visit'])->models[$i]->standard_price;
                    //$model['metrage']=json_decode($_POST['visitGroup'])->models[$i]->metrage;


                    $result = $this->Ws_model_bcm->save_model($model);
                    if (($brand_id == 1) && ($av == 0)) {
                        $oos_array[] = json_decode($_POST['visit'])->models[$i]->product_id;
                    }
                    $i++;
                    if ((isset(json_decode($_POST['visit'])->models[$i]->product_id))) {
                        if (json_decode($_POST['visit'])->models[$i]->av == 2) {
                            $ha_array[] = json_decode($_POST['visit'])->models[$i]->product_id;
                            $ha['product_id'] = json_decode($_POST['visit'])->models[$i]->product_id;
                            $ha['outlet_id'] = json_decode($_POST['visit'])->outlet_id;
                            $this->Ws_model_bcm->save_ha($ha);
                        } else {
                            $ha['product_id'] = json_decode($_POST['visit'])->models[$i]->product_id;
                            $ha['outlet_id'] = json_decode($_POST['visit'])->outlet_id;
                            $this->Ws_model_bcm->delete_ha($ha);
                        }
                    }
                } //end foreach models

                $outlet_save['id'] = $outlet_id;
                $outlet_save['ha'] = json_encode($ha_array);

                $this->Outlet_model->save($outlet_save);


                $j = 0;
                $branding_pictures = array();
                $one_pictures = array();
                while (isset($_FILES['before' . $j])) {

                    $file_path = "./uploads/branding/";
                    $file_path1 = $file_path . basename($_FILES['before' . $j]['name']);
                    $file_path2 = $file_path . basename($_FILES['after' . $j]['name']);
                    if ((move_uploaded_file($_FILES['before' . $j]['tmp_name'], $file_path1)) && (move_uploaded_file($_FILES['after' . $j]['tmp_name'], $file_path2))) {

                        //$rayon['visit_id']=$visit_id;
                        //$rayon['visit_uniqueId']=json_decode($_POST['visit'])->rayons[$j]->visit_uniqueId;
                        //$rayon['before']=$_FILES['before'.$j]['name'];
                        //$rayon['after']=$_FILES['after'.$j]['name'];
                        $branding[] = $_FILES['before' . $j]['name'];
                        $branding[] = $_FILES['after' . $j]['name'];

                        //$branding_pictures[]=$branding;
                        array_push($branding_pictures, $branding);
                        $branding = array();
                        //$result2=$this -> Ws_model_bcm -> save_rayon($rayon);
                    }
                    $j++;
                }


                $y = 0;
                while (isset($_FILES['picture' . $y])) {

                    $file_path4 = "./uploads/branding/";
                    $file_path4 = $file_path4 . basename($_FILES['picture' . $y]['name']);
                    if ((move_uploaded_file($_FILES['picture' . $y]['tmp_name'], $file_path4))) {

                        //$picture['visit_id']=$visit_id;
                        //$picture['visit_uniqueId']=json_decode($_POST['visit'])->pictures[$y]->visit_uniqueId;
                        //$picture['picture']=$_FILES['picture'.$y]['name'];

                        $one_pictures[] = $_FILES['picture' . $y]['name'];

                        //$result3=$this -> Ws_model_bcm -> save_picture($picture);
                    }
                    $y++;
                }


                // save pictures
                //$branding_pictures = substr(json_encode($branding_pictures),1);
                //$branding_pictures = substr($branding_pictures, 0, -1);





                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.capesolution.tn';
                $config['smtp_port'] = 587;
                $config['smtp_user'] = 'hcs@capesolution.tn';
                $config['smtp_pass'] = 'henkel2016';
                $config['mailtype'] = 'html';
                $this->load->library('email', $config);
                $responsible_id = $this->Ws_model_bcm->get_outlet($outlet_id)->responsible_id;
                $outlet_name = $this->Ws_model_bcm->get_outlet($outlet_id)->name;



                if (($responsible_id > 0) && ($oos_perc > 0)) {

                    $responsible_mail = $this->Ws_model_bcm->get_responsible_mail($responsible_id);

                    $this->email->set_newline("\r\n");

                    $this->email->from('hcs@capesolution.tn', 'Henkel HCS');

                    $this->email->to($responsible_mail);

                    $this->email->subject('OOS Reports ' . $outlet_name . ' ' . $visit_date);


                    $message = '';
                    $message = $message . '<p> Bonjour; <br><br> Veuillez trouver ci-dessous la liste de nos produits en rupture: <br>';
                    $i = 0;
                    foreach ($oos_array as $row) {
                        $i++;
                        $message = $message . '<p>' . $i . '- ' . $this->Ws_model_bcm->get_product_name($row) . '<br>';
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
                    $this->Ws_model_bcm->save_email($save_email);
                }


                $save_pictures['id'] = $visit_id;
                $save_pictures['branding_pictures'] = json_encode($branding_pictures);
                $save_pictures['one_pictures'] = json_encode($one_pictures);

                $this->Ws_model_bcm->update_visit2($save_pictures);


                $response["success"] = 1;
                $response["message"] = "Visit successfully uploaded.";

                echo json_encode($response);
            } else {
                $this->Ws_model_bcm->delete_visit($visit_id);
                $this->Ws_model_bcm->delete_models($visit_id);
                $response["success"] = 0;
                $response["message"] = "Oops! An error occurred.";

                echo json_encode($response);
            }
        } else {

            $response["success"] = 0;
            $response["message"] = "Oops! Temporary server error.";
            $this->Ws_model_bcm->delete_visit($visit_id);
            $this->Ws_model_bcm->delete_models($visit_id);
            echo json_encode($response);
        }
    }

    function saveVisitGroupWithMail() {


        if (isset($_POST['visitGroup'])) {



            $outlet_id = json_decode($_POST['visitGroup'])->outlet_id;
            $visit_date = json_decode($_POST['visitGroup'])->date;
            $visit['admin_id'] = json_decode($_POST['visitGroup'])->admin_id;
            $visit['uniqueId'] = json_decode($_POST['visitGroup'])->uniqueId;
            $visit['outlet_id'] = $outlet_id;
            $visit['entry_time'] = json_decode($_POST['visitGroup'])->entry_time;
            $visit['exit_time'] = json_decode($_POST['visitGroup'])->exit_time;
            $visit['worked_time'] = json_decode($_POST['visitGroup'])->worked_time;
            //$visit['last_time']=json_decode($_POST['visit'])->last_time;
            $oos_perc = json_decode($_POST['visitGroup'])->oos_perc;
            if (isset(json_decode($_POST['visitGroup'])->remark)) {
                $visit['remark'] = json_decode($_POST['visitGroup'])->remark;
            }
            $visit['oos_perc'] = $oos_perc;
            $visit['date'] = $visit_date;
            $visit['w_date'] = firstDayOf('week', new DateTime($visit['date']));
            $visit['m_date'] = firstDayOf('month', new DateTime($visit['date']));
            $visit['longitude'] = json_decode($_POST['visitGroup'])->longitude;
            $visit['latitude'] = json_decode($_POST['visitGroup'])->latitude;
            $visit['monthly_visit'] = json_decode($_POST['visitGroup'])->monthly_visit;
            $visit['was_there'] = json_decode($_POST['visitGroup'])->was_there;
            $visit['branding_pictures'] = '';
            $visit['one_pictures'] = '';

            $visit_id = $this->Ws_model_bcm->save_visitgroup($visit);

            if ($visit_id) {

                $i = 0;
                $oos_array = array();
                $ha_array = array();
                while (isset(json_decode($_POST['visitGroup'])->modelsGroup[$i])) {

                    $brand_id = json_decode($_POST['visitGroup'])->modelsGroup[$i]->brand_id;
                    $av = json_decode($_POST['visitGroup'])->modelsGroup[$i]->av;
                    $model['visit_id'] = $visit_id;
                    $model['visit_uniqueId'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->visit_uniqueId;
                    //$model['product_id']=json_decode($_POST['visitGroup'])->modelsGroup[$i]->product_id;
                    $model['brand_id'] = $brand_id;
                    $model['category_id'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->category_id;
                    $model['cluster_id'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->cluster_id;
                    $model['product_group_id'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->product_group_id;
                    $model['target'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->target;
                    $model['av'] = $av;
                    $model['av_sku'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->av_sku;
                    $model['nb_sku'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->nb_sku;
                    $model['sku_display'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->sku_display;
                    $model['shelf'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->shelf;
                    $model['promo_price'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->promo_price;
                    $model['price'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->standard_price;
                    $model['metrage'] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->metrage;


                    $result = $this->Ws_model_bcm->save_model($model);
                    if (($brand_id == 1) && ($av == 0)) {
                        $oos_array[] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->product_id;
                    }
                    $i++;


                    if ($av == 2) {
                        $ha_array[] = json_decode($_POST['visitGroup'])->modelsGroup[$i]->product_id;
                    }
                } //end foreach models

                $outlet_save['id'] = $outlet_id;
                $outlet_save['ha'] = json_encode($ha_array);

                $this->Outlet_model->save($outlet_save);


                $j = 0;
                $branding_pictures = array();
                $one_pictures = array();
                while (isset($_FILES['before' . $j])) {

                    $file_path = "./uploads/branding/";
                    $file_path1 = $file_path . basename($_FILES['before' . $j]['name']);
                    $file_path2 = $file_path . basename($_FILES['after' . $j]['name']);
                    if ((move_uploaded_file($_FILES['before' . $j]['tmp_name'], $file_path1)) && (move_uploaded_file($_FILES['after' . $j]['tmp_name'], $file_path2))) {

                        //$rayon['visit_id']=$visit_id;
                        //$rayon['visit_uniqueId']=json_decode($_POST['visit'])->rayons[$j]->visit_uniqueId;
                        //$rayon['before']=$_FILES['before'.$j]['name'];
                        //$rayon['after']=$_FILES['after'.$j]['name'];
                        $branding[] = $_FILES['before' . $j]['name'];
                        $branding[] = $_FILES['after' . $j]['name'];

                        //$branding_pictures[]=$branding;
                        array_push($branding_pictures, $branding);
                        $branding = array();
                        //$result2=$this -> Ws_model_bcm -> save_rayon($rayon);
                    }
                    $j++;
                }


                $y = 0;
                while (isset($_FILES['picture' . $y])) {

                    $file_path4 = "./uploads/branding/";
                    $file_path4 = $file_path4 . basename($_FILES['picture' . $y]['name']);
                    if ((move_uploaded_file($_FILES['picture' . $y]['tmp_name'], $file_path4))) {

                        //$picture['visit_id']=$visit_id;
                        //$picture['visit_uniqueId']=json_decode($_POST['visit'])->pictures[$y]->visit_uniqueId;
                        //$picture['picture']=$_FILES['picture'.$y]['name'];

                        $one_pictures[] = $_FILES['picture' . $y]['name'];

                        //$result3=$this -> Ws_model_bcm -> save_picture($picture);
                    }
                    $y++;
                }


                // save pictures
                //$branding_pictures = substr(json_encode($branding_pictures),1);
                //$branding_pictures = substr($branding_pictures, 0, -1);





                $config['protocol'] = 'smtp';
                $config['smtp_host'] = 'smtp.capesolution.tn';
                $config['smtp_port'] = 587;
                $config['smtp_user'] = 'hcs@capesolution.tn';
                $config['smtp_pass'] = 'henkel2016';
                $config['mailtype'] = 'html';
                $this->load->library('email', $config);
                $responsible_id = $this->Ws_model_bcm->get_outlet($outlet_id)->responsible_id;
                $outlet_name = $this->Ws_model_bcm->get_outlet($outlet_id)->name;



                if (($responsible_id > 0) && ($oos_perc > 0)) {

                    $responsible_mail = $this->Ws_model_bcm->get_responsible_mail($responsible_id);

                    $this->email->set_newline("\r\n");

                    $this->email->from('hcs@capesolution.tn', 'Henkel HCS');

                    $this->email->to($responsible_mail);

                    $this->email->subject('OOS Reports ' . $outlet_name . ' ' . $visit_date);


                    $message = '';
                    $message = $message . '<p> Bonjour; <br><br> Veuillez trouver ci-dessous la liste de nos produits en rupture: <br>';
                    $i = 0;
                    foreach ($oos_array as $row) {
                        $i++;
                        $message = $message . '<p>' . $i . '- ' . $this->Ws_model_bcm->get_product_name($row) . '<br>';
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
                    $this->Ws_model_bcm->save_email($save_email);
                }


                $save_pictures['id'] = $visit_id;
                $save_pictures['branding_pictures'] = json_encode($branding_pictures);
                $save_pictures['one_pictures'] = json_encode($one_pictures);

                $this->Ws_model_bcm->update_visit2($save_pictures);


                $response["success"] = 1;
                $response["message"] = "Visit successfully uploaded.";

                echo json_encode($response);
            } else {

                $response["success"] = 0;
                $response["message"] = "Oops! An error occurred.";
                // Delete models && visit
                $this->Ws_model_bcm->delete_visit($visit_id);
                $this->Ws_model_bcm->delete_models($visit_id);

                echo json_encode($response);
            }
        } else {

            $response["success"] = 0;
            $response["message"] = "Oops! Temporary server error.";
            // Delete models && visit
            $this->Ws_model_bcm->delete_visit($visit_id);
            $this->Ws_model_bcm->delete_models($visit_id);
            echo json_encode($response);
        }
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


            $result = $this->Ws_model_bcm->update_admin($id, $register_id);

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


            $result = $this->Ws_model_bcm->get_messages_by_receiver_id($id);

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
        $users = $this->Ws_model_bcm->get_ws_clients();
        //print(json_encode($users));
        print(json_encode(array('clients' => $clients)));
    }

    function initialize() {
        $users = $this->Ws_model_bcm->get_ws_users();
        //print(json_encode($users));
        print(json_encode(array('users' => $users)));

        /*

          $outlets= $this->Ws_model_bcm->get_ws_outlets();
          print(json_encode(array('outlets' => $outlets)));

          $products = $this->Ws_model_bcm->get_ws_products();
          print(json_encode(array('products' => $products)));
         */
    }

    //get users
    function users() {
        $users = $this->Ws_model_bcm->get_ws_users();
        print(json_encode($users));
    }

    function ha() {
        $ha = $this->Ws_model_bcm->get_ws_ha();
        print(json_encode($ha));
    }

    function clients() {
        $users = $this->Ws_model_bcm->get_ws_clients();
        print(json_encode($clients));
    }

    //get outlets
    function outlets() {
        $outlets = $this->Ws_model_bcm->get_ws_outlets();
        print(json_encode($outlets));
    }

    //get products
    function products() {
        $products = $this->Ws_model_bcm->get_ws_products();
        print(json_encode($products));
    }

    function categories() {
        $categories = $this->Ws_model_bcm->get_ws_categories();
        print(json_encode($categories));
    }

    function subCategories() {
        $subCategories = $this->Ws_model_bcm->get_ws_sub_categories();
        print(json_encode($subCategories));
    }

    function productTypes() {
        $productTypes = $this->Ws_model_bcm->get_product_types();
        print(json_encode($productTypes));
    }

    function productGroups() {
        $productGroups = $this->Ws_model_bcm->get_product_groups();
        print(json_encode($productGroups));
    }

    function clusters() {
        $clusters = $this->Ws_model_bcm->get_clusters();
        print(json_encode($clusters));
    }

    function brands() {
        $brands = $this->Ws_model_bcm->get_ws_brands();
        print(json_encode($brands));
    }

    function zones() {
        $zones = $this->Ws_model_bcm->get_ws_zones();
        print(json_encode($zones));
    }

    function states() {
        $states = $this->Ws_model_bcm->get_ws_states();
        print(json_encode($states));
    }

    //get Ha_products
    function getHaByAdmin($admin_id) {
        $ha_products = $this->Ws_model_bcm->get_ws_ha_products($admin_id);
        print(json_encode($ha_products));
    }

    function insert_all() {

        if (isset($_POST['req'])) {
            $res = 0;
            $req = "";


            $result = json_decode(stripslashes($_POST['req']));

            //models
            $models = $result->models;
            foreach ($models as $model) {
                if (!empty($model)) {
                    $save = array();
                    $save['id'] = false;
                    $save['visit_id'] = $model->visit_id;
                    $save['av'] = $model->av;
                    $save['shelf'] = $model->shelf;
                    $save['sales'] = $model->sales;
                    $save['price'] = $model->price;
                    $save['price_unit'] = $model->price_unit;
                    $save['score'] = $model->score;
                    $save['product_id'] = $model->product_id;
                    $save['brand_id'] = $model->brand_id;
                    $save['product_group_id'] = $model->product_group_id;

                    $res = $this->Ws_model_bcm->save_model($save);
                }
            }

            //visits
            $visits = $result->visits;
            foreach ($visits as $visit) {

                if (!empty($visit)) {
                    $save = array();
                    $save['id'] = $visit->id;
                    $save['admin_id'] = $visit->admin_id;
                    $save['outlet_id'] = $visit->outlet_id;
                    $save['zone'] = $visit->zone;
                    $save['date'] = $visit->date;
                    $save['entry_time'] = $visit->entry_time;
                    $save['exit_time'] = $visit->exit_time;
                    $save['remark'] = $visit->remark;
                    $save['before'] = $visit->before;
                    $save['after'] = $visit->after;
                    $save['before1'] = $visit->before1;
                    $save['after1'] = $visit->after1;
                    $save['before2'] = $visit->before2;
                    $save['after2'] = $visit->after2;
                    $save['total_sales'] = $visit->total_sales;
                    $save['total_score'] = $visit->total_score;
                    $save['active'] = 0;

                    //$req=implode("-",array_values($save));

                    $res = $this->Ws_model_bcm->save_visit($save);
                }//end if empty
            } // end foreach
            //outlets
            $outlets = $result->outlets;
            foreach ($outlets as $outlet) {
                if (!empty($outlet)) {
                    $save = array();
                    $save['admin_id'] = $outlet->admin_id;
                    $save['name'] = $outlet->name;
                    $save['id'] = $outlet->outlet_id;
                    $save['zone'] = $outlet->zone;
                    $save['state'] = $outlet->state;
                    $save['sector'] = $outlet->sector;
                    $save['city'] = $outlet->city;
                    $save['phone'] = $outlet->phone;
                    $save['long'] = $outlet->long;
                    $save['lat'] = $outlet->lat;
                    $save['image'] = $outlet->image;

                    $res = $this->Ws_model_bcm->save_outlet($save);
                }
            }

            // Get data from object
            //$name = $result->name; // Get name you send


            if ($res) {
                // successfully inserted into database
                $response["success"] = 1;
                $response["message"] = "all data successfully created. " . $req;

                // echoing JSON response
                echo json_encode($response);
            } else {
                // failed to insert row
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

}
