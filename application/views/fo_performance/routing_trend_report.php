<script type="text/javascript">
    $(function () {
        $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});
        $("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
<style>
    td{
        text-align: center;
        width: 90px;
    }

    th{

        text-align: center !important;
        width: 90px;


    }
</style>
<?php
//bcm
$week = date('Y') . '-W' . date('W');
$week_ch = explode('-W', $week);
$week = getStartAndEndDate($week_ch[1], $week_ch[0]);

//date('Y-m-d', strtotime("-56 day", strtotime("")));
//***********************
//for ($i = 0; $i <= 6; $i++) {
//    echo date('l', strtotime("+$i day", strtotime("$week[0]")));
//    echo '<br>';
//    echo date('Y-m-d', strtotime($week[0] . ' + ' . $i . ' DAY'));
//    echo '<br>';
//
//    echo '<br>';
//    echo '<br>';
//}
//for ($j = 7; $j <= 56; $j += 7) {
//    echo date('Y-m-d', strtotime("-" . $j . "day", strtotime(date('Y-m-d', strtotime('2018-11-05' . ' + ' . $i . ' DAY')))));
//    //echo $j;
//    echo '<br>';
//}
?>
<?php
if ($excel != 1) {
    //hcm
    ?>
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
    <?php echo form_open('reports/Routing_trend', $attributes); ?>

                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-2">From </label>
                            <div class="col-md-4">
                                <input type="text" name="start_date" 
                                       value="<?php echo $start_date; ?>" 
                                       id="datepicker1" 
                                       class="form-control" />		
                            </div>

                            <label class="control-label col-md-2">To </label>
                            <div class="col-md-4">
                                <input type="text" name="end_date" 
                                       value="<?php echo $end_date; ?>" 
                                       id="datepicker2" 
                                       class="form-control" />		
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Day </label>
                            <div class="col-md-4">

                                <?php
                                $days = array();
                                for ($i = 0; $i <= 6; $i++) {
                                    $days[date('Y-m-d', strtotime($week[0] . ' + ' . $i . ' DAY'))] = date('l', strtotime("+$i day", strtotime("$week[0]")));
                                }
                                ?>
    <?php echo form_dropdown('day', $days, set_value('day', $day), 'class="span3 form-control"'); ?>
                            </div>

                            <label class="control-label col-md-2">Export to Excel</label>
                            <div class="col-md-4">
                                <?php
                                echo form_dropdown('excel', array(
                                    '0' => 'No',
                                    '1' => 'Yes'
                                        ), '', 'class="form-control"');
                                ?>
                            </div>
                        </div> <!-- end form-group  -->

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
} else {
    //include ('header_report.php');
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . $page_title . " - " . reverse_format(date("d/m/Y")) . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}
?>


<?php
//echo $report_data;
//echo$start_date;
//echo$end_date;
if ($day) {

    $dates = array();
    $components = array();
    $components_date = array();
    foreach ($report_data as $row) {
        $date = ($row['date']);
        if (!in_array($date, $dates)) {
            $dates[] = $date;
        }
        asort($dates);
        //create an array for every brand and the count at a outlet
        $components[$row['admin']][$date] = array($row['visits'], $row['branding'], $row['working_hours'], $row['travel_hours'], $row['entry_time'], $row['exit_time']);
    }// end foreach report_data
    ?>


    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-settings font-red"></i>
                <span class="caption-subject font-red bold uppercase">Routing Trend</span>
            </div>
        </div>

        <table class="table table-striped table-bordered table-hover " width="100%" border="1">
            <thead>
                <tr>
                    <th></th>

                        <?php foreach ($dates as $date) { ?>
                        <th class ="text-center" colspan="4"><?php
                            echo reverse_format($date);
                            ?></th>
    <?php } ?>

                </tr>
                <tr>
                    <th>Admin</th>
    <?php foreach ($dates as $date) { ?>
                        <th>W Hours</th>
                        <th>T hours</th>
                        <th>S Work</th>
                        <th>F Work</th>
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

                        <?php foreach ($dates as $date) {
                            ?>


                            <td class="Working Hours" nowrap>
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

                                    echo "$heure h : $minute m";
                                } else
                                    echo '-';
                                ?> 
                            </td>


                            <td class="Travelling hours" nowrap><?php
                                if (isset($componentDates[$date])) {
                                    $working_hours = $componentDates[$date][3];
                                    $working_hours = str_replace(" ", "", $working_hours);
                                    $total = $working_hours; //nombre de secondes 

                                    $heure = intval(abs($total / 3600));

                                    $total = $total - ($heure * 3600);

                                    $minute = intval(abs($total / 60));

                                    $total = $total - ($minute * 60);

                                    $seconde = $total;

                                    echo "$heure h : $minute m";
                                } else
                                    echo '-';
                                ?> 
                            </td>

                            <td class="Starting Work" nowrap><?php
                                if (isset($componentDates[$date])) {

                                    $hours3 = floor($componentDates[$date][4] / 3600);
                                    $mins3 = floor($componentDates[$date][4] / 60 % 60);
                                    if ($mins3 < 10) {
                                        $mins3 = '0' . $mins3;
                                    }

                                    if ($hours3 >= 9 && $mins3 >= 10) {
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

                            <td class="Finishing Work" nowrap><?php
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


                        <?php } // end foreach dates              ?>

    <?php }// end foreach components               ?>

                </tr>

            </tbody>
        </table>

    </div>
    <?php
}
