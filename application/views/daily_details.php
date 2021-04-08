<?php include('header.php'); //bcm      ?>
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
<script type="text/javascript">
    $(function () {
        $("#datepicker1").datepicker({

            dateFormat: 'dd/mm/yy',
            altField: '#datepicker1_alt',
            altFormat: 'yy-mm-dd'
        });
    });


    $(function () {
        $("#datepicker2").datepicker({

            dateFormat: 'dd/mm/yy',
            altField: '#datepicker2_alt',
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
                    <span class="caption-subject bold uppercase"> Search</span>
                    <span class="caption-helper"></span>
                </div>
            </div> <!-- end portlet-title -->

            <div class="portlet-body form">
                <?php $attributes = array('class' => 'form-horizontal', 'autocomplete' => 'off'); ?>
                <?php echo form_open('dashboard/daily_details', $attributes) ?>
                <div class="form-body">

                    <div class="form-group">
                        <label class="control-label col-md-2">Start Day <span class="required">* </span> </label>
                        <div class="col-md-3">
                            <?php
                            $data = array('id' => 'datepicker1', 'value' => set_value('start_date', reverse_format($start_date)), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="start_date" value="<?php echo set_value('start_date', $start_date) ?>" id="datepicker1_alt" /> 
                        </div>


                        <label class="control-label col-md-2">End Day <span class="required">* </span> </label>
                        <div class="col-md-3">
                            <?php
                            $data = array('id' => 'datepicker2', 'value' => set_value('end_date', reverse_format($end_date)), 'class' => 'form-control');
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="end_date" value="<?php echo set_value('end_date', $end_date) ?>" id="datepicker2_alt" /> 
                        </div>
                    </div>
                    <br>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-circle red-mint btn-outline sbold uppercase">Search</button>
                                <button type="reset" class="btn btn-circle btn-default sbold uppercase">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div> <!--  end form-body-->

                </form>
                <!-- END FORM-->
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-settings font-red"></i>
                    <span class="caption-subject font-red bold uppercase">Daily  Visits Details</span>
                </div>
                <br>

                <table class="table table-striped table-bordered table-hover " width="100%" >
                    <thead>
                        <tr>

                            <th>FO</th>
                            <th>States</th>
                            <th>Stock issue</th>
                            <th>Shelf_Price</th>
    <!--                        <th>Number of visits</th>-->
                            <th>Target</th>
                            <th>%</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($fos as $fo): ?>
                            <tr>

                                <td><?php echo $this->auth->get_admin_name($fo->id); ?></td>
                                 <td><?php echo $fo->states; ?></td>

                                <td>
                                    <?php
                                    $dailies = $this->Dashboard_model->get_daily_visit_by_admin_daily($fo->id, $start_date, $end_date);
                                    foreach ($dailies as $daily) {
                                        $daily = $daily->nb_visits;
                                        echo $daily;
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $shelfs = $this->Dashboard_model->get_daily_visit_by_admin_shelf($fo->id, $start_date, $end_date);
                                    foreach ($shelfs as $shelf) {
                                        echo($shelf->nb_visits);
                                    }
                                    ?>
                                </td>

    <!--                            <td><?php
                                $daily_visit = $this->Dashboard_model->get_daily_visit_by_admin($fo->id, $start_date, $end_date);
                                echo $daily_visit;
                                ?>
                                </td>-->


                                <td>
                                    <?php
                                    $daily_target = 0;
                                    $start_date_traitement = $start_date;
                                    while (strtotime($start_date_traitement) <= strtotime($end_date)) {

                                        $today_letter = date('l', strtotime($start_date_traitement));

                                        $daily_target = $daily_target + $this->Dashboard_model->get_target_visit_by_admin($fo->id, $today_letter);

                                        $start_date_traitement = date('Y-m-d', strtotime($start_date_traitement . '+ 1 days'));
                                    }

                                    echo $daily_target;
                                    ?>
                                </td>
                                <td><?php
                                    if ($daily_target != 0) {
                                        $perc = ($daily / $daily_target) * 100;
                                        echo number_format($perc, 2, ',', ' ') . '%';
                                    } else
                                        echo '-';
                                    ?></td>


                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <table class="table table-striped table-bordered table-hover " width="100%" >
                <thead>
                    <tr>
                        <th>Channels</th>
                        <th>Number of visits</th>
                        <th>Target</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($channels as $Channel): ?>
                        <tr>
                            <td><?php echo $Channel->name; ?></td>

                            <td><?php
                                $daily_visit = $this->Dashboard_model->get_daily_visit_by_channel($Channel->id, $start_date, $end_date);
                                echo $daily_visit;
                                ?>
                            </td>

                            <td><?php
                                $daily_target = 0;
                                $start_date_traitement = $start_date;
                                while (strtotime($start_date_traitement) <= strtotime($end_date)) {
                                    $today_letter = date('l', strtotime($start_date_traitement));

                                    $daily_target = $daily_target + $this->Dashboard_model->get_target_visit_by_channel($Channel->id, $today_letter);

                                    $start_date_traitement = date('Y-m-d', strtotime($start_date_traitement . '+ 1 days'));
                                }
                                echo $daily_target;
                                ?>
                            </td>

                            <td><?php
                                if ($daily_target != 0) {
                                    $perc = ($daily_visit / $daily_target) * 100;
                                    echo number_format($perc, 2, ',', ' ') . '%';
                                } else
                                    echo '-';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
