<?php
if ($excel != 1) {

    //bcm
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



<style>
    td{
        text-align: center;
     
    }

    th{

        text-align: center;
  
    }
</style>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN VALIDATION STATES-->
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-magnifier font-red"></i>
                        <span class="caption-subject bold uppercase"> Search</span>
                        <span class="caption-helper"><?php echo $sub_title; ?></span>
                    </div>
                </div> <!-- end portlet-title -->
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <?php $attributes = array('class' => 'form-horizontal', 'autocomplete' => 'off'); ?>
                    <?php echo form_open('reports/daily_visit_report', $attributes); ?>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-2">Start Date</label>
                            <div class=" col-md-10">
                                <?php
                                $data = array('id' => 'datepicker1', '', 'class' => 'form-control', 'placeholder' => "Date");
                                echo form_input($data);
                                ?>
                                <input type="hidden" name="date" value="" id="datepicker1_alt" />
                            </div>

                           
                        </div> <!--   end form-group 1 -->

                        <div class="form-group">
                             <label class="control-label col-md-2">Merchandiser</label>
                            <div class="col-md-4">
                                <?php
                                $merchandiser_ids = array();
                                $merchandiser_ids[-1] = 'None';


                                foreach ($merchandisers as $merchandiser) {
                                    $merchandiser_ids[$merchandiser->id] = $merchandiser->name;
                                }
                                ?>
                                <?php echo form_dropdown('merch_id', $merchandiser_ids, '', 'class="form-control"'); ?>
                            </div>

                            <label class="control-label col-md-2">Channel</label>
                            <div class="col-md-4">
                                <?php
                                $channel_ids = array();
                                $channel_ids[-1] = 'None';
                                foreach ($channels as $channel) {
                                    $channel_ids[$channel->id] = $channel->name;
                                }
                                ?>
                                <?php echo form_dropdown('selected_channel_id', $channel_ids, '', 'class="form-control"'); ?>
                            </div>

                        </div> <!--   end form-group 2 -->
                        <div class="form-group">
                            <label class="control-label col-md-2">Export to Excel</label>
                            <div class="col-md-10">
                                <?php
                                echo form_dropdown('excel', array(
                                    '0' => 'No',
                                    '1' => 'Yes'
                                        ), '', 'class="form-control"');
                                ?>
                            </div>


                        </div> <!-- end form-group 3 -->

                    </div> <!-- end form-body -->
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-circle red-mint btn-outline sbold uppercase">Search</button>
                                <button type="reset" class="btn btn-circle blue-hoki btn-outline sbold uppercase">Cancel</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div> <!-- end portlet-body form -->
            </div>  <!-- end portlet box blue -->
        </div> <!-- end col-md-12 -->
    </div>  <!-- end row 1-->

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
if (!empty($report_data)) {
    ?>
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption font-red-sunglo">
                <i class="icon-settings font-red"></i>
                <span class="caption-subject bold uppercase"> 
                    <?php 
                    if($selected_channel_id!='-1'){
                echo $this->Channel_model->get_channel_name($selected_channel_id);
                    }else{
                        echo 'All Channels';
                    }
                ?></span>
            </div>
        </div> <!-- end portlet-title -->
        <div class="row">
            <div class="col-md-12">

                <table class="table table-striped table-bordered table-hover dt-responsive" cellpadding="0" cellspacing="0" border="1">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <?php
                            $products = array();
                            $components = array();
                            foreach ($report_data as $row) {
                                //create an array with all the models
                                if (!in_array($row['product'], $products)) {
                                    $products[] = $row['product'];
                                }
                                //create an array for every date and the count at a location
                                $components[$row['outlet_id']][$row['product']] = $row['av'];
                            }
                            foreach ($products as $pd) {
                                ?>

                                <th><?php
                               echo $this->Product_model->get_product($pd)->code;
                               ?></th>

                            <?php } ?>
                        </tr>

                        <tr>
                            <th>Zone</th>
                            <th>Outlet</th>
                            <?php
                            foreach ($products as $pd) {
                                ?>			
                                <th ><?php echo  $this->Product_model->get_product($pd)->name; ?></th>
                            <?php } ?>
                        </tr>


                    </thead>
                    <tbody>

                        <?php foreach ($components as $outlet_id => $componentPds) { ?>
                            <tr>
                                <td><?php
                                    $outlet= $this->Outlet_model->get_outlet($outlet_id);
                                        echo $outlet->zone;
                                    
                                    ?>
                                </td>

                                <td><?php echo $outlet->name; ?></td>
                                <?php
                                foreach ($products as $pd) {
                                    if (isset($componentPds[$pd])) {
                                        $av = $componentPds[$pd];
                                    } else
                                        $av = '-';
                                    ?>
                                    <td><?php
                                        if ($av == 1) {
                                            echo '-';
                                        } else if($av==0) {
                                            echo 'OOS';
                                        }
                                        else echo 'HA';
                                        ;
                                        ?> </td>


                                <?php } ?>
                            </tr>
                        <?php } ?>


                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <?php
}//end if !empty $report_data
else {
    ?>
    <div class="alert alert-error">

        <strong>Sorry! </strong> No data available 
    </div>

    <?php
}//end if !empty $report_data
?>


