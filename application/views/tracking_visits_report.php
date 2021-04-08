<?php
include ('header.php');
?>

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

// Returns the four-digit year corresponding to the ISO week of the date.
    Date.prototype.getWeekYear = function () {
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

    $(function () {
        $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});
        $("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
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
                <?php $attributes = array('class' => 'form-horizontal','autocomplete' => 'off'); ?>
                <?php echo form_open('reports/tracking_visits_report', $attributes); ?>

                <div class="form-body">

                    <div class="form-group">
                        <label class="control-label col-md-2">Start date </label>

                        <div class="col-md-4">
                            <input type="text" name="start_date" value="<?php echo $start_date; ?>" id="datepicker1" class="form-control" />		
                        </div>


                        <label class="control-label col-md-2">End date 
                        </label>
                        <div class="col-md-4">

                            <input type="text" name="end_date" value="<?php echo $end_date; ?>" id="datepicker2" class="form-control" />	
                        </div>
                    </div> <!--  end form-group date  -->


                    <div class="form-group">
                        <label class="control-label col-md-2">Merchandiser</label>
                        <div class="col-md-10">
                            <?php
                            $merchandiser_ids = array();
                            $merchandiser_ids[-1] = 'None';

                            foreach ($merchandisers as $merchandiser) {
                                $merchandiser_ids[$merchandiser->id] = $merchandiser->name;
                            }
                            ?>
                            <?php echo form_dropdown('merch_id', $merchandiser_ids, $merch_id, 'class="form-control" id=specific_fo'); ?>
                        </div>
                    </div> <!--   end form-group 2 -->
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
//print_r($visited_outlets);
//print_r($merch_id);
?>

<div class="row">
    <div class="col-md-6">
        <table class="table table-striped table-hover">
            <caption  ><span class="caption-subject font-red bold uppercase"  > <h3>Visited Outlets (<?php echo count($visited_outlets); ?>) </h3></span>  </caption>

            <thead>
                <tr>
                    <th> Outlet </th>
                    <th> Fo </th>
                    <th> State </th>
                    <th> Zone</th>
                    <th> Visit Date </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visited_outlets as $v) { ?>
                    <tr>
                        <td><?php echo $v->outlet_name; ?></td>
                        <td><?php echo $v->hfo; ?></td>
                        <td><?php echo $v->state_name; ?></td>
                        <td><?php echo $v->zone_name; ?></td>
                        <td><?php echo reverse_format($v->date); ?></td>
                    </tr>

                <?php } // end foreach  ?>
            </tbody>
        </table>
    </div> <!-- end col-md-6 -->


    <div class="col-md-6">
        <table class="table table-striped table-hover">
            <caption  ><span class="caption-subject font-red bold uppercase"  > <h3>Unvisited Outlets (<?php echo count($unvisited_outlets); ?>)</h3></span>  </caption>
            <thead>
                <tr>
                    <th> Outlet </th>
                    <th> Fo </th>
                    <th> State </th>
                    <th> Zone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($unvisited_outlets as $unv) { ?>
                    <tr>
                        <td><?php echo $unv->outlet_name; ?></td>
                        <td><?php echo $unv->hfo; ?></td>
                        <td><?php echo $unv->state_name; ?></td>
                        <td><?php echo $unv->zone_name; ?></td>
                    </tr>

                <?php } // end foreach  ?>
            </tbody>
        </table>
    </div> <!-- end col-md-6 -->
</div>  <!-- end row 2-->

<?php
include ('footer.php');
?>		 


