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
                <?php echo form_open('commande_report/historique_cmd_per_pos', $attributes) ?>


                <div class="form-body">

                    <div class="form-group">
                        <label class="control-label col-md-2">From </label>
                        <div class="col-md-4">
                            <input type="text" name="start_date" 
                                   value="<?php echo $start_date; ?>" 
                                   id="datepicker1" 
                                   class="form-control" />		
                        </div>

                        <label class="control-label col-md-2">To</label>
                        <div class="col-md-4">
                            <input type="text" name="end_date" 
                                   value="<?php echo $end_date; ?>" 
                                   id="datepicker2" 
                                   class="form-control" />		
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-2">Channel </label>
                        <div class="col-md-4">
                            <?php
                            $channel_ids = array();
                            $channel_ids[-1] = 'Please Select';
                            foreach ($channels as $ch) {
                                $channel_ids[$ch->id] = $ch->name;
                            }
                            ?>
                            <?php echo form_dropdown('channel_id', $channel_ids, set_value('channel_id', $channel_id), 'id= specific_channel class=form-control'); ?>
                        </div> <!-- end class="col-md-6"-->

                        <label class="control-label col-md-2">Zone </label>
                        <div class="col-md-4">
                            <?php
                            $zone_ids = array();
                            $zone_ids[-1] = 'Please Select';
                            foreach ($zones as $z) {
                                $zone_ids[$z->id] = $z->name;
                            }
                            echo form_dropdown('zone_id', $zone_ids, set_value('zone_id', $zone_id), 'id=specific_zone class=form-control');
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Outlet</label>
                        <div class="col-md-4">
                            <?php
                            $outlet_ids = array();
                            $outlet_ids[-1] = 'Please Select';
                            foreach ($outlets as $outlet) {
                                $outlet_ids[$outlet->id] = $outlet->name;
                            }
                            echo form_dropdown('outlet_id', $outlet_ids, set_value('outlet_id', $outlet_id), 'id=specific_outlet class=form-control');
                            ?>
                        </div>
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


<?php if ($outlet_id != -1) { ?>
    <div class="row">
        <div class="col-md-12">
            <!--BEGIN VALIDATION STATES-->
            <div class="portlet light">

                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-magnifier font-red"></i>
                        <span class="caption-subject bold uppercase"> Historique des Cde per POS</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>  

                <div class="portlet-body form">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" width="100%" >
                            <?php
                            $i = 1;
                            foreach ($data as $row) {
                                ?>
                                <thead>
                                <th>
                                    <h5><b><?php echo $i . '  ' . $row->outlet_name . ' || ' . $row->fo . ' || '; ?><font color="red"><?php echo reverse_format($row->date); ?></font></b></h5>
                                </th>
                                </thead>
                                <tbody>
                                <td align="center">   
                                    <?php if ($row->order_num != '') { ?>
                                        <h4><b>Remark : </b><?php echo $row->order_num ?></h4>
                                    <?php } ?>
                                    <img class="img-responsive" src="<?php echo base_url('uploads/order/' . $row->order_picture); ?>" alt="admin-pic" download >
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
<?php //print_r($data); ?>



<script type="text/javascript" language="javascript">
    $(document).ready(function () {
        $('#specific_channel,#specific_zone').change(function () {
            $("#specific_outlet > option").remove();
            //first of all clear select items
            var channel_id = $('#specific_channel').val();
            var zone_id = $('#specific_zone').val();

            // here we are taking country id of the selected one.
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('commande_report/get_outlet_by_zone_channel'); ?>",
                data: {zone_id: zone_id, channel_id: channel_id},
                success: function (data)
                {
                    $.each(data, function (id, out)
                    {
                        var opt = $('<option />');
                        opt.val(id);
                        opt.text(out);
                        $('#specific_outlet').append(opt);
                    });
                    //console.log(data);
                }
            });

        });

    });
</script>

