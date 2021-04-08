<style>
    td{
        text-align: center;
        width: 125px;
    }

    th{

        text-align: center;
        width: 125px;
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
                    <span class="caption-helper">Shelf_share</span>
                </div>
            </div> <!-- end portlet-title -->
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal','autocomplete'=>'off'); ?>
                <?php echo form_open('reports/shelf_share_report', $attributes); ?>
                <div class="form-body">

                    <div class="form-group">
                        <label class="control-label col-md-2">Start Date</label>
                        <div class="col-md-2">
                            <?php
                            $data = array('name' => 'year1', 'type' => 'number', 'value' => $year1, 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                        <div class="col-md-2">
                            <?php
                            $options = array(
                                '01-01' => 'Q1',
                                '04-01' => 'Q2',
                                '07-01' => 'Q3',
                                '10-01' => 'Q4'
                            );
                            echo form_dropdown('quarter1', $options, $quarter1, 'class="form-control"');
                            ?>
                        </div>


                        <label class="control-label col-md-2">End Date</label>
                        <div class="col-md-2">
                            <?php
                            $data = array('name' => 'year2', 'type' => 'number', 'value' => $year2, 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                        </div>
                        <div class="col-md-2">
                            <?php
                            $options = array(
                                '01-01' => 'Q1',
                                '04-01' => 'Q2',
                                '07-01' => 'Q3',
                                '10-01' => 'Q4'
                            );
                            echo form_dropdown('quarter2', $options, $quarter2, 'class="form-control"');
                            ?>
                        </div>

                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-2">Category</label>
                        <div class="col-md-10">
                            <?php
                            $category_ids = array();
                            $category_ids['-1'] = 'All Categories';
                            foreach ($categories as $category) {
                                $category_ids[$category->id] = $category->name;
                            }
                            ?>
                            <?php echo form_dropdown('category_id', $category_ids, set_value('category_id', $category_id), 'class="form-control"'); ?>
                        </div>
                    </div> <!-- end form-group  -->
                    <div class="form-group">
                        <label class="control-label col-md-2">Zone</label>
                        <div class="col-md-4">
                            <select multiple="multiple" class="multi-select" id="my_multi_select1" name="selected_zone_ids[]">
                                <?php foreach ($zones as $zone) { ?>
                                    <option  value="<?php echo $zone->id ?>" ><?php echo $zone->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <label class="control-label col-md-2">Channel </label>
                        <div class="col-md-4">
                            <select multiple="multiple" class="multi-select" id="my_multi_select2" name="selected_channel_ids[]">
                                <?php foreach ($channels as $channel) { ?>
                                    <option  value="<?php echo $channel->id ?>" ><?php echo $channel->name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div><!-- end form-group  -->
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