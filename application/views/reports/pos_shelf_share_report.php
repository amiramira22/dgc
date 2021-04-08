<script type="text/javascript">
    Date.prototype.getWeek = function () {
        var date = new Date(this.getTime());
        date.setHours(0, 0, 0, 0);
        // Thursday in current week decides the year.
        date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
        // January 4 is always in week 1.
        var week1 = new Date(date.getFullYear(), 0, 4);
        // Adjust to Thursday in week 1 and count number of weeks from date to week1.
        return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000
                - 3 + (week1.getDay() + 6) % 7) / 7);
    }

    Date.prototype.getWeekYear = function () {
// Returns the four-digit year corresponding to the ISO week of the date.
        var date = new Date(this.getTime());
        date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
        return date.getFullYear();
    }



    $(function () {
        $("#datepicker_w1").datepicker({
            showWeek: true,
            firstDay: 1,
            onSelect: function (date) {
                var d = new Date(date);
                var index = d.getDay();
                if (index == 0) {
                    d.setDate(d.getDate() - 6);
                } else if (index == 1) {
                    d.setDate(d.getDate());
                } else if (index != 1 && index > 0) {
                    d.setDate(d.getDate() - (index - 1));
                }





                $(this).val(d.getWeekYear() + '-W' + d.getWeek());

                var curr_date = d.getDate();
                var curr_month = d.getMonth() + 1; //Months are zero based
                var curr_year = d.getFullYear();
                $("#datepicker_w1_alt").val(curr_year + "-" + curr_month + "-" + curr_date);

            }

        });

        $("#datepicker_w2").datepicker({
            showWeek: true,
            firstDay: 1,
            onSelect: function (date) {
                var d = new Date(date);
                var index = d.getDay();
                if (index == 0) {
                    d.setDate(d.getDate() - 6);
                } else if (index == 1) {
                    d.setDate(d.getDate());
                } else if (index != 1 && index > 0) {
                    d.setDate(d.getDate() - (index - 1));
                }





                $(this).val(d.getWeekYear() + '-W' + d.getWeek());

                var curr_date = d.getDate();
                var curr_month = d.getMonth() + 1; //Months are zero based
                var curr_year = d.getFullYear();
                $("#datepicker_w2_alt").val(curr_year + "-" + curr_month + "-" + curr_date);

            }

        });
    });


    $(function () {
        $("#datepicker_m1").datepicker({
            changeMonth: true,
            changeYear: true,

            dateFormat: 'MM yy',
            altField: '#datepicker_m1_alt',
            altFormat: 'yy-mm-dd',
            onClose: function (dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
            },

        });
    });



    $(function () {
        $("#datepicker_m2").datepicker({
            changeMonth: true,
            changeYear: true,

            dateFormat: 'MM yy',
            altField: '#datepicker_m2_alt',
            altFormat: 'yy-mm-dd',
            onClose: function (dateText, inst) {
                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                $(this).datepicker('setDate', new Date(year, month, 1));
            },

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
                    <span class="caption-subject bold uppercase"> Search</span>
                    <span class="caption-helper"></span>
                </div>
            </div> <!-- end portlet-title -->
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal'); ?>
                <?php echo form_open('reports/pos_shelf_share_report', $attributes); ?>

                <div class="form-body">

                    <div class="form-group">

                        <label class="control-label col-md-2">Date Type </label>
                        <div class="col-md-4">

                            <?php
                            echo form_dropdown('date_type', array(
                                'quarter' => 'Quarter',
                                'month' => 'Monthly',
                                'week' => 'Weekly'
                                    ), '', 'class="form-control" id="date_type"');
                            ?>
                        </div>

                        <label class="control-label col-md-2">Channel </label>
                        <div class="col-md-4">

                            <?php
                            $channel_ids = array();
                            foreach ($channels as $channel) {
                                $channel_ids[$channel->id] = $channel->name;
                            }
                            echo form_dropdown('channel_id', $channel_ids, '', 'class="form-control" id="channel"');
                            ?>
                        </div>
                    </div> <!-- end form-group  -->

                  <div class="form-group" id="month">
                        <label class="control-label col-md-2">Start date 
                        </label>
                        <div class="col-md-4">
                            <?php
                            $data = array('id' => 'datepicker_m1', 'class' => 'form-control', 'value' => format_qmw_date($date_type, $start_date));
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="start_date_m"  value="<?php echo set_value('start_date', $start_date) ?>" id="datepicker_m1_alt" />		
                        </div>
                        <label class="control-label col-md-2">End date 
                        </label>
                        <div class="col-md-4">
                            <?php
                            $data = array('id' => 'datepicker_m2', 'class' => 'form-control', 'value' => format_qmw_date($date_type, $end_date));
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="end_date_m"  value="<?php echo set_value('end_date', $end_date) ?>" id="datepicker_m2_alt" />		
                        </div>
                    </div>

                    <div class="form-group" id="week">
                        <label class="control-label col-md-2">Start date 
                        </label>
                        <div class="col-md-4">
                            <?php
                            $data = array('id' => 'datepicker_w1', 'class' => 'form-control', 'value' => format_qmw_date($date_type, $start_date));
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="start_date_w" value="<?php echo set_value('start_date', $start_date) ?>" id="datepicker_w1_alt" />		
                        </div>
                        <label class="control-label col-md-2">End date 
                        </label>
                        <div class="col-md-4">
                            <?php
                            $data = array('id' => 'datepicker_w2', 'class' => 'form-control', 'value' => format_qmw_date($date_type, $end_date));
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="end_date_w" value="<?php echo set_value('endt_date', $end_date) ?>" id="datepicker_w2_alt" />		
                        </div>
                    </div>

                    <div class="form-group" id="quarter">
                        <label class="control-label col-md-2">Start Date</label>
                        <div class="col-md-2">
                            <?php
                            $data = array('name' => 'year1', 'type' => 'number', 'value' => set_value('year1', date('Y')), 'class' => 'form-control');
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
                            echo form_dropdown('quarter1', $options, set_value('start_date', $start_date), 'class="form-control"');
                            ?>
                        </div>


                        <label class="control-label col-md-2">End Date</label>
                        <div class="col-md-2">
                            <?php
                            $data = array('name' => 'year2', 'type' => 'number', 'value' => set_value('year2*', date('Y')), 'class' => 'form-control');
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
                            echo form_dropdown('quarter2', $options, set_value('end_date', $end_date), 'class="form-control"');
                            ?>
                        </div>

                    </div>






                </div> <!-- end form-body -->
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn btn-circle red-mint btn-outline sbold uppercase">Search</button>
                            <button type="reset" class="btn btn-circle btn-default sbold uppercase">Cancel</button>
                        </div>
                    </div>
                </div>
                </form>
            </div> <!-- end portlet-body form -->
        </div>  <!-- end portlet box blue -->
    </div> <!-- end col-md-12 -->
</div>  <!-- end row 1-->

<?php
if ($start_date && $end_date) {
    foreach ($categories as $category) {
        $category_id = $category->id;
        $category_name = $category->name;
        ?> 
        <div class="portlet light ">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-settings font-red"></i>
                    <span class="caption-subject font-red bold uppercase"><?php echo $category_name; ?></span>
                </div>
            </div>
            <div id="content_category<?php echo $category_id; ?>"> </div>
            <script type="text/javascript">
                $('#content_category<?php echo $category_id; ?>').html('<div class="col-md-12"><img src="<?php echo base_url('assets/img/ajax-loading.gif'); ?>" class="img-responsive img-center" /></div>');
                jQuery.ajax({
                    url: "<?php echo site_url("reports/load_pos_shelf_share_category"); ?>",
                    data: {
                        start_date: "<?php echo $start_date; ?>",
                        end_date: "<?php echo $end_date; ?>",
                        category_id: "<?php echo $category_id; ?>",
                        channel_id: "<?php echo $channel_id; ?>",
                        date_type: "<?php echo $date_type; ?>"
                    },
                    type: "POST",
                    success: function (data) {
                        $('#content_category<?php echo $category_id; ?>').html(data);
                    }
                });
            </script>
        </div> <!-- end portlet light -->


        <?php
    }
}
?>
<script>
    $(document).ready(function () {
        $("#week").hide();
        $("#quarter").show();
        $("#month").hide();
        $('#date_type').on('change', function () {
      
            if (this.value == 'month')
            {
                $("#month").show();
                $("#week").hide();
                $("#quarter").hide();
            } else if (this.value == 'week')
            {
                $("#month").hide();
                $("#week").show();
                $("#quarter").hide();
            } else {
                $("#month").hide();
                $("#week").hide();
                $("#quarter").show();
            }
        });
    });
</script>
