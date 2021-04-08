<script type="text/javascript">
    $(function () {
        $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});
        $("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
<style>

    .img-responsive {
        display: block;
        max-width: 100%;
        height: 400px;
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
                    <span class="caption-helper"></span>
                </div>
            </div> <!-- end portlet-title -->

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal', 'autocomplete' => 'off'); ?>
                <?php echo form_open('commande_report/historique_cmd_per_fo', $attributes) ?>
                <div class="form-body">

                    <div class="form-group">

                        <label class="control-label col-md-1">FO </label>
                        <div class="col-md-3">
                            <?php
                            $admin_ids = array();
                            foreach ($admins as $ad) {
                                $admin_ids[-1] = 'Please select';
                                $admin_ids[$ad->id] = $ad->name;
                            }
                            ?>
                            <?php echo form_dropdown('fo_id', $admin_ids, $fo_id, 'class="form-control"'); ?>
                        </div> <!-- end class="col-md-6"-->

                        <label class="control-label col-md-1">From </label>
                        <div class="col-md-3">
                            <input type="text" name="start_date" 
                                   value="<?php echo $start_date; ?>" 
                                   id="datepicker1" 
                                   class="form-control" />		
                        </div>

                        <label class="control-label col-md-1">To</label>
                        <div class="col-md-3">
                            <input type="text" name="end_date" 
                                   value="<?php echo $end_date; ?>" 
                                   id="datepicker2" 
                                   class="form-control" />		
                        </div>
                    </div>
                    <div class="form-group">
                    </div>



                </div> <!--  end form-body-->

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle red-mint btn-outline sbold uppercase">Search</button>
                            <button type="reset" class="btn btn-circle btn-default sbold uppercase">Cancel</button>
                        </div>
                    </div>
                </div>

                </form>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END VALIDATION STATES-->
    </div>
</div> <!-- END ROW FORM-->

<?php if ($fo_id != -1) { ?>

    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN VALIDATION STATES-->
            <div class="portlet light">

                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-magnifier font-red"></i>
                        <span class="caption-subject bold uppercase"> Historique des Cde per FO</span>
                        <span class="caption-helper"></span>
                    </div>
                </div> <!-- end portlet-title -->

                <div class="portlet-body form">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" width="100%" >
                            <?php
                            $i = 1;
                            foreach ($data as $row) {
                                ?>
                                <thead>
                                <th>
                                    <h5><b><?php echo $i . '  ' ?><font color="red"><?php echo $row->outlet_name; ?></font><?php echo ' || ' . $row->fo . ' || ' . reverse_format($row->date); ?></b></h5>
                                </th>
                                </thead>
                                <tbody>
                                <td align="center"> 
                                    <?php if ($row->order_num != '') { ?>
                                        <h4><b>Remark : </b><?php echo $row->order_num ?></h4>
                                    <?php } ?>

                                    <img class="img-responsive"  src="<?php echo base_url('uploads/order/' . $row->order_picture); ?>" alt="admin-pic" download >

                                </td>
                                </tbody>
                                <?php
                                $i++;
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>
<?php
//print_r($data); ?>