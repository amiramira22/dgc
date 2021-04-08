

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
        <div class="portlet light">

            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-magnifier font-red"></i>
                    <span class="caption-subject bold uppercase"> <?php echo $sub_title; ?></span>
                    <span class="caption-helper"></span>
                </div>
            </div> <!-- end portlet-title -->

            <div class="portlet-body form">
                <?php $attributes = array('class' => 'form-horizontal'); ?>
                <?php echo form_open('visits/form/' . $id, $attributes) ?>
                <div class="form-body">

                    <div class="form-group">
                        <label class="control-label col-md-3">Field Officer  </label>
                        <div class="col-md-6">
                            <?php
                            $admin_ids = array();
                            foreach ($admins as $ad) {
                                $admin_ids[$ad->id] = $ad->name;
                            }
                            ?>
                            <?php echo form_dropdown('user_id', $admin_ids, set_value('user_id', $user_id), 'class="form-control"'); ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-3">Outlet <span class="required">* </span> </label>
                        <div class="col-md-6">
                            <?php
                            $outlet_ids = array();
                            foreach ($outlets as $out) {
                                $outlet_ids[$out->id] = $out->name;
                            }
                            ?>
                            <?php echo form_dropdown('outlet_id', $outlet_ids, set_value('outlet_id', $outlet_id), 'class="form-control"'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Day of Visit <span class="required">* </span> </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('id' => 'datepicker1', 'value' => set_value('date', reverse_format($date)), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="date" value="<?php echo set_value('date', $date) ?>" id="datepicker1_alt" /> 
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3">Remarque <span class="required"> * </span>  </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('name' => 'remark', 'value' => set_value('remark', $remark), 'class' => 'form-control', 'placeholder' => "Remark");
                            echo form_textarea($data);
                            ?>
                        </div>
                    </div>

                </div> <!--  end form-body-->

                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle red-mint btn-outline sbold uppercase">Save</button>
                            <button type="reset" class="btn btn-circle btn-default sbold uppercase">Cancel</button>
                        </div>
                    </div>
                </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div> <!-- END ROW FOR
