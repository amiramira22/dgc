<?php //bcm                           ?>
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

<style>
    td{
        text-align: center;

    }

    th{

        text-align: center !important;

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
                <?php echo form_open('reports/fo_performance', $attributes); ?>

                <div class="form-body">


                    <!--                    <div class="form-group">
                                            <label class="control-label col-md-2">Date Type </label>
                                            <div class="col-md-10">
                    <?php
                    echo form_dropdown('date_type', array(
                        'daily' => 'Daily',
                        'month' => 'Monthly',), set_value('date_type', $date_type), 'class="form-control" id="date_type"');
                    ?>
                                            </div>  end form-group  
                                        </div>-->

                    <div class="form-group">
                        <label class="control-label col-md-2">Date Type </label>
                        <div class="col-md-10">
                            <?php
                            echo form_dropdown('date_type', array(
                                'daily' => 'Daily',
                                'month' => 'Monthly',
                                'week' => 'Weekly'
                                    ), set_value('date_type', $date_type), 'class="form-control" id="date_type"');
                            ?>
                        </div>
                    </div> <!-- end form-group  -->


                    <div class="form-group" id="daily">
                        <label class="control-label col-md-2">Start date </label>
                        <div class="col-md-4">
                            <input type="text" name="start_date" 
                                   value="<?php echo $start_date; ?>" 
                                   id="datepicker1" 
                                   class="form-control" />		
                        </div>

                        <label class="control-label col-md-2">End date </label>
                        <div class="col-md-4">
                            <input type="text" name="end_date" 
                                   value="<?php echo $end_date; ?>" 
                                   id="datepicker2" 
                                   class="form-control" />		
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

                    <div class="form-group" id="month">
                        <label class="control-label col-md-2">Start date 
                        </label>
                        <div class="col-md-4">
                            <?php
                            $data = array('id' => 'datepicker_m1', 'class' => 'form-control', 'value' => format_month($start_date));
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="start_date_m"  value="<?php echo set_value('start_date', $start_date) ?>" id="datepicker_m1_alt" />		
                        </div>
                        <label class="control-label col-md-2">End date 
                        </label>
                        <div class="col-md-4">
                            <?php
                            $data = array('id' => 'datepicker_m2', 'class' => 'form-control', 'value' => format_month($end_date));
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="end_date_m"  value="<?php echo set_value('end_date', $end_date) ?>" id="datepicker_m2_alt" />		
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
//echo $report_data;
//echo$start_date;
//echo$end_date;
if ($start_date && $end_date && $date_type) {

    $dates = array();
    $components = array();
    $components_date = array();
    $count_date = 0;
    foreach ($report_data as $row) {
        $date = ($row['date']);
        if (!in_array($date, $dates)) {
            $dates[] = $date;
            $count_date += 1;
        }
        //create an array for every brand and the count at a outlet
        $components[$row['admin']][$date] = array($row['visits'], $row['branding'], $row['working_hours'], $row['travel_hours'], $row['entry_time'], $row['exit_time'], $row['gemo'], $row['mg'], $row['uhd'], $row['admin_id']);
    }// end foreach report_data
    ?>


    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-settings font-red"></i>
                <span class="caption-subject font-red bold uppercase">Merchandiser performance</span>
            </div>
        </div>

        <table class="table table-striped table-bordered table-hover " width="100%">
            <thead>
                <tr>
                    <th></th>

                    <?php foreach ($dates as $date) { ?>
                        <th class ="text-center" colspan="10"><?php
                            if ($date_type == "month") {
                                echo format_month($date);
                            } else if ($date_type == "week") {
                                echo format_week($date);
                            } else {
                                echo ($date);
                            }
                            ?></th>
                    <?php } ?>

                </tr>
                <tr>
                    <th colspan=""></th>
                    <?php foreach ($dates as $date) { ?>   

                        <th colspan="7" class ="text-center">Merchendiser Performance</th>
                        <th colspan="3" class ="text-center">OOS</th>

                    <?php } ?>
                </tr>
                <tr>

                    <th>Admin</th>

                    <?php foreach ($dates as $date) { ?>
                        <th rowspan="1" class ="text-center">visits</th>
                           <th rowspan="1" class ="text-center">target</th>
                        <th rowspan="1" class ="text-center">Brandings</th>
                        <th rowspan="1" class ="text-center">Working Hours</th>
                        <th class ="text-center">Travelling hours</th>
                        <th class ="text-center">Starting Work</th>
                        <th class ="text-center">Finishing Work</th>
                        <th class ="text-center">Gemo</th>
                        <th class ="text-center">MG</th>
                        <th class ="text-center">UHD</th>
                    <?php } ?>

                </tr>

            <thead>

            <tbody>

                <?php
                $i = 0;
                foreach ($components as $admin => $componentDates) {
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $admin; ?></td>

                        <?php foreach ($dates as $date) { ?>
                            <td class="visits">
                                <?php
                                if (isset($componentDates[$date])) {
                                    echo $componentDates[$date][0];
                                }
                                ?>
                            </td>

                            <?php if ($date_type == "month") { ?>
                                <td>
                                    <?php
                                    if (isset($componentDates[$date])) {
                                        $tab_nom_jour = array('Monday' => 2, 'Tuesday' => 3, 'Wednesday' => 4, 'Thursday' => 5, 'Friday' => 6, 'Saturday' => 7, 'Sunday' => 1);
                                        $total = 0;
                                        //$fos = $this->auth->get_fo_list();
                                        $days[] = array();

                                        //tt les outlets d'un fo 
                                        $outlets = $this->Outlet_model->get_outlets_by_id($componentDates[$date][9]);
                                        $target_meurch = 0;
                                        foreach ($outlets as $outlet) {

                                            $days = (json_decode($outlet->visit_day));

                                            if (!empty($days)) {
                                                $s = 0;
                                                foreach ($days as $day) {
                                                    $numeroJour = $tab_nom_jour[$day];
                                                    //$mois = date("n", strtotime($date));
                                                    //$annee = date("Y", strtotime($date));

                                                    $J1 = 1;
                                                    $M1 = date("n", strtotime($date));
                                                    $A1 = date("Y", strtotime($date));

                                                    $J2 = date('t', mktime(0, 0, 0, $M1, 1, $A1));
                                                    $M2 = date("n", strtotime($date));
                                                    $A2 = date("Y", strtotime($date));


                                                    $nbJour = 0;
                                                    $Date1 = mktime(0, 0, 0, $M1, $J1, $A1);
                                                    $Date2 = mktime(0, 0, 0, $M2, $J2, $A2);
                                                    $nbJourDiff = ($Date2 - $Date1) / (60 * 60 * 24);
                                                    for ($i = 0; $i < $nbJourDiff + 1; $i++) {
                                                        $Date1 = mktime(0, 0, 0, $M1, $J1 + $i, $A1);
                                                        if (date("w", $Date1) == $numeroJour - 1)
                                                            $nbJour++;
                                                    }
                                                    //echo $nbJour;
                                                    //chaque meurch chaque outlet
                                                    $s = $s + $nbJour;

                                                    //chaque meurch ces outlet
                                                    $target_meurch = $target_meurch + $nbJour;

                                                    //tt les meurch 
                                                    $total = $total + $nbJour;
                                                }
                                            }
                                        }

                                        //$perc = ($daily_visit / $target_meurch * 100);
                                        echo $target_meurch;
                                    }
                                    ?>
                                </td>
                            <?php } else if ($date_type == "daily") { ?>
                                <td>
                                    <?php
                                    if (isset($componentDates[$date])) {
                                        $today_letter = date('l', strtotime($date));
//                                    echo $today_letter;
//                                    echo '<br>';
//                                    echo $componentDates[$date][9];
//                                    echo '<br>';
                                        $daily_target = $this->Dashboard_model->get_target_visit_by_admin($componentDates[$date][9], $today_letter);
                                        echo $daily_target;
                                    }
                                    ?>
                                </td>
                            <?php } else if ($date_type == "week") { ?>
                                <td><?php
                                    if (isset($componentDates[$date])) {
                                        $daily_target = 0;
                                        $start_date_traitement = $date;
                                        $end_date_of_week = date("Y-m-d", strtotime($date . "+ 6 days"));
                                        while (strtotime($start_date_traitement) <= strtotime($end_date_of_week)) {

                                            $today_letter = date('l', strtotime($start_date_traitement));

                                            $daily_target = $daily_target + $this->Dashboard_model->get_target_visit_by_admin($componentDates[$date][9], $today_letter);

                                            $start_date_traitement = date('Y-m-d', strtotime($start_date_traitement . '+ 1 days'));
                                        }

                                        echo $daily_target;
                                    }
                                    ?>
                                </td>
                            <?php } ?>

                            <td class="branding">
                                <?php
                                if (isset($componentDates[$date])) {   //$branding = json_decode(
                                    echo $componentDates[$date][1];
                                    //echo sizeof($branding) ;
                                }
                                ?>

                            </td>

                            <td class="Working Hours">
                                <?php
                                if (isset($componentDates[$date])) {
                                    $working_hours = $componentDates[$date][2];
                                    $working_hours = str_replace(" ", "", $working_hours);
                                    $total = $working_hours; //nombre de secondes 

                                    $heure = intval(abs($total / 3600));

                                    $total = $total - ($heure * 3600);

                                    $minute = intval(abs($total / 60));

                                    $total = $total - ($minute * 60);

                                    $seconde = $total;

                                    echo "$heure h : $minute min : $seconde sec";
                                }
                                ?> 
                            </td>


                            <td class="Travelling hours"><?php
                                if (isset($componentDates[$date])) {
                                    $working_hours = $componentDates[$date][3];
                                    $working_hours = str_replace(" ", "", $working_hours);
                                    $total = $working_hours; //nombre de secondes 

                                    $heure = intval(abs($total / 3600));

                                    $total = $total - ($heure * 3600);

                                    $minute = intval(abs($total / 60));

                                    $total = $total - ($minute * 60);

                                    $seconde = $total;

                                    echo "$heure H : $minute min : $seconde sec";
                                }
                                ?> 
                            </td>

                            <td class="Starting Work"><?php
                                if (isset($componentDates[$date])) {

                                    $hours3 = floor($componentDates[$date][4] / 3600);
                                    $mins3 = floor($componentDates[$date][4] / 60 % 60);
                                    if ($mins3 < 10) {
                                        $mins3 = '0' . $mins3;
                                    }

                                    if ($hours3 >= 9 && $mins3 >= 10 && $date_type == 'daily') {
                                        ?>
                                        <font color="#F6DC12"><B><?php echo ($hours3 . ':' . $mins3) . ' ! '; ?></B></font>

                                        <?php
                                    } else {
                                        print_r($hours3 . ':' . $mins3);
                                    }
                                } else
                                    echo '-';
                                ?>
                            </td>

                            <td class="Finishing Work"><?php
                                if (isset($componentDates[$date])) {

                                    $hours4 = floor($componentDates[$date][5] / 3600);
                                    $mins4 = floor($componentDates[$date][5] / 60 % 60);
                                    if ($mins4 < 10) {
                                        $mins4 = '0' . $mins4;
                                    }

                                    print_r($hours4 . ':' . $mins4);
                                } else
                                    echo '-';
                                ?>
                            </td>
                            <td class="Gemo"><?php
                                if (isset($componentDates[$date])) {


                                    echo number_format($componentDates[$date][6], 2, '.', ' ');
                                } else
                                    echo '-';
                                ?> 
                            </td>
                            <td class="MG"><?php
                                if (isset($componentDates[$date])) {


                                    echo number_format($componentDates[$date][7], 2, '.', ' ');
                                } else
                                    echo '-';
                                ?>
                            </td>


                            <td class="UHD"><?php
                                if (isset($componentDates[$date])) {


                                    echo number_format($componentDates[$date][8], 2, '.', ' ');
                                } else
                                    echo '-';
                                ?>
                            </td>

                        <?php } // end foreach dates       ?>

                    <?php }// end foreach components       ?>

                </tr>

            </tbody>
        </table>

    </div>
    <?php
}
?>

<script>
    $(document).ready(function () {
        $("#daily").show();
        $("#month").hide();
        $("#week").hide();

        $('#date_type').on('change', function () {

            if (this.value == 'month')
            {
                $("#month").show();
                $("#daily").hide();
                $("#week").hide();

            } else if (this.value == 'week')
            {
                $("#month").hide();
                $("#week").show();
                $("#daily").hide();
            } else
            {
                $("#month").hide();
                $("#daily").show();
                $("#week").hide();

            }
        });
    });
</script>

