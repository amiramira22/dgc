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
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-search"></i>Search
                </div>

            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal', 'autocomplete' => 'off'); ?>
                <?php echo form_open('reports/pos_data', $attributes); ?>

                <div class="form-body">



                    <div class="form-group">
                        <label class="control-label col-md-2">Start date 
                        </label>

                        <div class="col-md-4">
                            <input type="text" name="start_date" value="<?php echo $start_date; ?>" id="datepicker1" class="form-control" />		
                        </div>


                        <label class="control-label col-md-2">End date 
                        </label>
                        <div class="col-md-4">

                            <input type="text" name="end_date" value="<?php echo $end_date; ?>" id="datepicker2" class="form-control" />	
                        </div>
                    </div>

                    <div class="row">

                        <label class="control-label col-md-2">FO</label>


                        <div class="col-md-4">


                            <?php
                            $admin_ids = array();


                            foreach ($admins as $ad) {
                                $admin_ids[$ad->id] = $ad->name;
                            }
                            ?>

                            <?php echo form_dropdown('user_id', $admin_ids, $user_id, 'class=form-control id=specific_user'); ?>
                        </div> 

                        <div id="specific_outlet">
                            <label class="control-label col-md-2">Outlet</label>
                            <div class="col-md-4">
                                <?php
                                $admin_ids = array();
                                $admin_ids[-1] = 'All Outlets';

                                foreach ($outlets as $ad) {
                                    $admin_ids[$ad->id] = $ad->name;
                                }
                                ?>

                                <?php echo form_dropdown('outlet_id', $admin_ids, $outlet_id, 'class="form-control"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="control-label col-md-2">Zone</label>
                        <div class="col-md-4">

                            <?php
                            $admin_ids = array();
                            $admin_ids[-1] = 'All zones';

                            foreach ($zones as $ad) {
                                $admin_ids[$ad->name] = $ad->name;
                            }
                            ?>

                            <?php echo form_dropdown('zone_id', $admin_ids, '', 'class=form-control id="specific_zone"'); ?>
                        </div>
                    </div>






                </div> <!-- end form-body -->

                <div class="form-actions right">
                    <button class="btn btn-danger" id="submit" name="submit" value="search" >Submit</button>
                </div>

                </form>
            </div> <!-- end portlet-body form -->
        </div>  <!-- end portlet box blue -->
    </div> <!-- end col-md-12 -->
</div>  <!-- end row 1-->


<?php
if ($start_date && $end_date) {

    $dates = array();
    $components = array();
    $count_date = 0;
    foreach ($report_data2 as $row) {

        $date = $row['date'];

        if (!in_array($date, $dates)) {
            $dates[] = $date;
            $count_date += 1;
        }
        //create an array for every brand and the count at a outlet
        $components[$row['product_id']][$date] = array($row['av']);
    }// end foreach report_data
    ?>


    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">
        <thead>
            <tr>
                <th colspan="2"></th>
                <?php foreach ($dates as $date) { ?>
                    <th ><?php echo $date; ?></th>
                <?php } ?>
            </tr>

            <tr>
                <th>Brand</th>
                <th>Product</th>
                <?php foreach ($dates as $date) { ?>
                    <th>AV%</th>

                <?php } ?>


            </tr>

        <thead>

        <tbody>

            <?php foreach ($components as $product_id => $componentDates) { ?>
                <tr>

                    <?php
                    $product = $this->Product_model->get_product($product_id);
                    $brand_id = $product->brand_id;
                    $brand_name = $this->Brand_model->get_brand_name($brand_id);
                    ?>
                    <td><?php echo $brand_name; ?></td>
                    <td><?php echo $product->name; ?></td>


                    <?php foreach ($dates as $date) { ?>
                        <td class="shelf"><?php
                            if (isset($componentDates[$date])) {
                                if ($componentDates[$date][0] == 0) {
                                    echo 'OOS';
                                } else {

                                    echo '-';
                                }
                            } else
                                echo '-';
                            ?> </td>




                    <?php } // end foreach dates  ?>



                <?php }// end foreach components ?>


            </tr>

        </tbody>



    </table>




<?php } ?>


<script>
    $(document).ready(function () {

        $('#specific_user').on('change', function () {
            var user_id = document.getElementById('specific_user').value;
            var zone_id = document.getElementById('specific_zone').value;
            jQuery.ajax({
                url: "<?php echo site_url("reports/load_outlets"); ?>",

                data: {user_id: user_id, zone_id: zone_id},
                type: "POST",
                success: function (data) {
                    $('#specific_outlet').html(data);
                }
            });

        });


        $('#specific_zone').on('change', function () {
            var user_id = document.getElementById('specific_user').value;
            var zone_id = document.getElementById('specific_zone').value;
            jQuery.ajax({
                url: "<?php echo site_url("reports/load_outlets"); ?>",

                data: {user_id: user_id, zone_id: zone_id},
                type: "POST",
                success: function (data) {
                    $('#specific_outlet').html(data);
                }
            });

        });
    });
</script>
