<?php //hcs            ?>

<script src="<?php echo base_url('assets/plugins/excel_jquery/src/jquery.table2excel.js'); ?>" type="text/javascript"></script>

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



</script>
<style>

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        white-space: nowrap;
    }

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
                    <span class="caption-helper"></span>
                </div>
            </div> <!-- end portlet-title -->

            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <?php $attributes = array('class' => 'form-horizontal', 'autocomplete' => 'off'); ?>
                <?php echo form_open('reports/routing_survey', $attributes); ?>

                <div class="form-body">

                    <div class="form-group" id="week">
                        <label for="datepicker_w1" class="control-label col-md-1">Start date </label>
                        <div class="col-md-3">
                            <?php
                            $data = array('type' => 'text', 'name' => 'start_format_week', 'id' => 'datepicker_w1', 'class' => 'form-control', 'value' => format_qmw_date('week', $start_date));
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="start_date_w" value="<?php echo set_value('start_date', $start_date) ?>" id="datepicker_w1_alt" />		
                        </div>


                        <label for="datepicker_w2" class="control-label col-md-1">End date</label>
                        <div class="col-md-3">
                            <?php
                            $data = array('type' => 'text', 'name' => 'end_format_week', 'id' => 'datepicker_w2', 'class' => 'form-control', 'value' => format_qmw_date('week', $end_date));
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="end_date_w" value="<?php echo set_value('end_date', $end_date) ?>" id="datepicker_w2_alt" />		
                        </div>


                        <label for="fo" class="control-label col-md-1">Fo </label>
                        <div class="col-md-3">
                            <?php
                            $fo_ids = array();
                            foreach ($fos as $fo) {
                                //$fo_ids[-1] = 'All Field Officer';
                                $fo_ids[$fo->id] = $fo->name;
                            }
                            ?>
                            <?php echo form_dropdown('fo_id', $fo_ids, $fo_id, 'class="form-control" id="fo"'); ?>
                        </div> <!-- end class="col-md-6"-->
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
$w_dates = array();
foreach ($report_data as $row) {
    $w_date = $row['w_date'];
    if (!in_array($w_date, $w_dates)) {
        $w_dates[] = $w_date;
    }
    asort($w_dates);
}// end foreach report_data
?>
<?php if (isset($report_data) && !(empty($report_data))) { ?>
    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-settings font-red"></i>
                <span class="caption-subject font-red bold uppercase">Routing Trend</span>
            </div>
            <button class="btn btn-circle red-mint btn-outline sbold uppercase pull-right" id="button_excel">Export EXCEL</button>
        </div>

        <table class="table table-striped table-bordered table-hover dt-responsive" border="1" id="table2excel">


            <thead>
                <tr>

                    <th></th>
                    <?php for ($i = 0; $i <= 6; $i++) { ?>
                        <th>
                            <?php
                            echo date('l', strtotime("+$i day", strtotime("$start_date")));
                            ?>
                        </th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody id="">

                <?php
                $j = 1;
                foreach ($w_dates as $w_date) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            echo 'week ' . $j;
                            $j++;
                            echo '<br>';
                            echo reverse_format($w_date);
                            ?>
                        </td>
                        <?php for ($i = 0; $i <= 6; $i++) { ?>
                            <td>
                                <?php
                                $date = date('Y-m-d', strtotime("+$i day", strtotime("$w_date")));
                                echo '(' . reverse_format($date) . ')';
                                echo '<br>';

                                foreach ($report_data as $row) {
                                    if ($row['date'] == $date) {
                                        echo $row['outlet_name'];
                                        echo '<br>';
                                    }
                                }
                                ?>
                            </td>
                        <?php }
                        ?>

                    <?php }// end foreach components                   ?>
                </tr>

            </tbody>

        </table>

    </div>
<?php } ?>
<script>
    var d = new Date();
    var date = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
    //var day = d.getDate();
    $("#button_excel").click(function () {

        $("#table2excel").table2excel({

            name: "Worksheet Name",
            filename: "HCM_Rouring_Survey" + date //do not include extension
        });
    }
    );
</script>