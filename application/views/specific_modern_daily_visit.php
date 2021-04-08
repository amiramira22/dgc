<?php
if ($excel != 1) {
    include ('header.php');
    ?>

    <script type="text/javascript">
        $(function () {
            $("#datepicker1").datepicker({

                dateFormat: 'dd/mm/yy',
                altField: '#datepicker1_alt',
                altFormat: 'yy-mm-dd'
            });
        });
    </script>




    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN VALIDATION STATES-->
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-search"></i>Search_test
                    </div>

                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <?php $attributes = array('class' => 'form-horizontal'); ?>
                    <?php echo form_open($this->config->item('modern_folder') . '/reports/modern_daily_visit_report'); ?>


                    <div class="form-body">

                        <div class="form-group">

                            <div class="control-label col-md-4">
                                <label style="font-weight:bold;">Start Date</label>
                                <?php
                                $data = array('id' => 'datepicker1', '', 'class' => 'form-control', 'placeholder' => "Date");
                                echo form_input($data);
                                ?>
                                <input type="hidden" name="date" value="" id="datepicker1_alt" />
                            </div>


                            <div class="control-label col-md-4">
                                <label style="font-weight:bold;">FO</label>
                                <?php
                                $chef_ids = array();
                                $chef_ids[-1] = 'None';


                                foreach ($chefs as $chef) {
                                    $chef_ids[$chef->id] = $chef->name;
                                }
                                ?>
                                <?php echo form_dropdown('chef_id', $chef_ids, '', 'class="span4 form-control"'); ?>
                            </div>


                            <div class="control-label col-md-4">
                                <label style="font-weight:bold;">Responsible </label>
                                <?php
                                $res_ids = array();
                                $res_ids[-1] = 'None';


                                foreach ($responsibales as $res) {
                                    $res_ids[$res->id] = $res->name;
                                }
                                ?>
                                <?php echo form_dropdown('res_id', $res_ids, $res_id, 'class="span4 form-control"'); ?>
                            </div>

                            <div class="control-label col-md-4">
                                <label style="font-weight:bold;">Export to Excel</label>
                                <?php
                                echo form_dropdown('excel', array(
                                    '0' => 'No',
                                    '1' => 'Yes'
                                        ), '', 'class="form-control"');
                                ?>
                            </div>
                            <div class="control-label col-md-4">
                                <label style="font-weight:bold;">Channel </label>
                                <?php
                                $channel_ids = array();
                                foreach ($channels as $channel) {
                                    $channel_ids[$channel->id] = $channel->name;
                                }
                                ?>
                                <?php echo form_dropdown('selected_channel', $channel_ids, $selected_channel, 'class="span4 form-control"'); ?>
                            </div>

                        </div>

                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>
                        </br>


                        <p>

                    </div>
                    <div class="form-actions right">
                        <button class="btn btn-danger" id="submit" name="submit" value="search" >Submit</button>
                    </div>

                    </form>

                </div> <!-- end portlet-body form -->
            </div>  <!-- end portlet box blue -->
        </div> <!-- end col-md-12 -->
    </div>  <!-- end row 1-->
    </br></br>

    <?php
} else {
    //include ('header_report.php');
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . $page_title . " - " . reverse_format($date) . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}
?>


<?php
if (($chef_id != -1)) {
    $report_data = $this->Report_model->get_av_daily_data_by_chef($date, $chef_id, $selected_channel, $res_id);
    if (!empty($report_data)) {
        ?>

        <div class="row">
            <div class="span12">
                <h1><?php echo $this->Channel_model->get_channel_name($selected_channel); ?></h1>
                <table class="table table-striped" cellpadding="0" cellspacing="0" border="1">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <?php
                            $outlets = array();
                            $components = array();
                            foreach ($report_data as $row) {
                                //create an array with all the models
                                if (!in_array($row['outlet_id'], $outlets)) {
                                    $outlets[] = $row['outlet_id'];
                                }
                                //create an array for every date and the count at a location
                                $components[$row['product']][$row['outlet_id']] = array($row['av'], $row['shelf']);
                            }
                            foreach ($outlets as $out) {
                                ?>

                                <th colspan="1"><?php echo $this->Outlet_model->get_outlet($out)->zone; ?></th>

                            <?php } ?>
                        </tr>

                        <tr>
                            <th>Code</th>
                            <th rowspan="2">Product</th>
                            <?php
                            foreach ($outlets as $out) {
                                ?>			
                                <th ><?php echo $this->Outlet_model->get_outlet_name($out); ?></th>
                            <?php } ?>
                        </tr>


                    </thead>
                    <tbody>

                        <?php foreach ($components as $product1 => $componentOuts) { ?>
                            <tr>
                                <td><?php
                                    $product = $this->Product_model->get_product($product1);
                                    if ($selected_channel == "Gemo") {
                                        echo $product->code_gemo;
                                    } else if ($selected_channel == "MG") {
                                        echo $product->code_mg;
                                    } else {
                                        echo $product->code_uhd;
                                    }
                                    ?>
                                </td>

                                <td><?php echo $product->name; ?></td>
                                <?php
                                foreach ($outlets as $out) {
                                    if (isset($componentOuts[$out][0])) {
                                        $av = $componentOuts[$out][0];
                                    } else
                                        $av = '-';

                                    if (isset($componentOuts[$out][1])) {
                                        $shelf = $componentOuts[$out][1];
                                    } else
                                        $shelf = 0;
                                    ?>
                                    <td><?php
                                        if ($av == 1) {
                                            echo '-';
                                        } else {
                                            echo 'OOS';
                                        }
                                        ;
                                        ?> </td>


                                <?php } ?>
                            </tr>
                        <?php } ?>


                    </tbody>
                </table>
            </div>

        </div>
        </br>

        <?php
    }//end if !empty $report_data
    else {
        ?>
        <div class="alert alert-error">

            <strong>Sorry! </strong> No data available 
        </div>

        <?php
    }// end else !empty $report_data
}//end if $chef_id=-1
else if (($chef_id == -1)) {


    foreach ($chefs as $chef) {
        $chef_id = $chef->id;
        $chef_name = $chef->name;
        $report_data = $this->Report_model->get_av_daily_data_by_chef($date, $chef_id, $selected_channel, $res_id);
        //	print_r($report_data);
        ?>	
        <h3 class="title11"> <?php echo $chef_name; ?></h3>
        <?php
        if (!empty($report_data)) {
            //print_r($report_data);
            ?>



            <div class="row">
                <div class="span12">
                    <table class="table table-striped" cellpadding="0" cellspacing="0" border="2">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th rowspan="1">Products</th> 

            <?php
            $outlets = array();
            $components = array();
            foreach ($report_data as $row) {
                //create an array with all the models
                if (!in_array($row['outlet_id'], $outlets)) {
                    $outlets[] = $row['outlet_id'];
                }
                //create an array for every date and the count at a location
                $components[$row['product']][$row['outlet_id']] = array($row['av'], $row['shelf']);
            }
            foreach ($outlets as $out) {
                ?>

                                    <th colspan="1" width="120px"><?php echo $this->Outlet_model->get_outlet($out)->zone; ?></th>

            <?php } ?>
                            </tr>


                            <tr>
                                <th></th>
                                <th></th>
            <?php
            foreach ($outlets as $out) {
                ?>			
                                    <th colspan="1"><?php echo $this->Outlet_model->get_outlet_name($out); ?></th>
                                <?php } ?>
                            </tr>


                        </thead>
                        <tbody>

            <?php foreach ($components as $product1 => $componentOuts) { ?>
                                <tr>
                                    <td><?php
                $product = $this->Product_model->get_product($product1);
                //echo $selected_channel; 
                if ($selected_channel == 2) {

                    echo $product->code_gemo;
                } else if ($selected_channel == 1) {
                    echo $product->code_mg;
                } else if ($selected_channel == 3) {
                    echo $product->code_uhd;
                }
                ?>
                                    </td>
                                    <td><?php echo $this->Product_model->get_product_name($product1); ?></td>
                <?php
                foreach ($outlets as $out) {
                    if (isset($componentOuts[$out][0])) {
                        $av = $componentOuts[$out][0];
                    } else
                        $av = '-';

                    if (isset($componentOuts[$out][1])) {
                        $shelf = $componentOuts[$out][1];
                    } else
                        $shelf = 0;



                    // if($shelf!=0){
                    // $perc_shelf= number_format(($real_shelf/$shelf)*100,2).'%';
                    // }else{
                    // $perc_shelf='-';
                    // }
                    ?>
                                        <td><?php
                                        if ($av == 1) {
                                            echo '-';
                                        } else {
                                            echo 'OOS';
                                        }
                                        ?> </td>


                                        <?php } ?>
                                </tr>
                                <?php } ?>


                        </tbody>
                    </table>
                </div>

            </div>
            </br>







            <?php
        }//end if !empty $report_data
        else {
            ?>
            <div class="alert alert-error">

                <strong>Sorry! </strong> No data available 
            </div>





            <?php
        }// end else !empty $report_data
    }//end foreach
}// end else $chef_id=-1
?>

<?php
include ('footer.php');
?>
 

