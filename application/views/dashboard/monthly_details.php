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
            changeMonth: true,
            changeYear: true,
            dateFormat: 'MM yy',
            altField: '#datepicker1_alt',
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
                <?php $attributes = array('class' => 'form-horizontal','autocomplete'=>'off'); ?>
                <?php echo form_open('dashboard/monthly_details', $attributes) ?>
                <div class="form-body">

                    <div class="form-group">
                        <label class="control-label col-md-3">Month <span class="required">
                                * </span>
                        </label>
                        <div class="col-md-6">
                            <?php
                            $data = array('id' => 'datepicker1', 'value' => set_value('date', format_month($date)), 'class' => 'span8 form-control');
                            echo form_input($data);
                            ?>
                            <input type="hidden" name="date" value="<?php echo set_value('date', $date) ?>" id="datepicker1_alt" />		
                        </div>
                    </div>

                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-7 col-md-5">
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
                    <span class="caption-subject font-red bold uppercase">Monthly  Visits Details</span>
                </div>
                <br>
                <table class="table table-striped table-bordered table-hover " width="100%" >
            <thead>
                <tr>

                    <th>FO</th>
                    <th>Zone</th>
                    <th>Number of visits</th>
                    <th>Target</th>
                    <th>%</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($fos as $fo): ?>
                    <tr>

                        <td><?php echo $this->auth->get_admin_name($fo->id); ?></td>
                        <td><?php echo $fo->states; ?></td>
                        <td><?php
                            $daily_visit = $this->Dashboard_model->get_monthly_visit_by_admin($fo->id, $date);
                            echo $daily_visit;
                            ?>
                        </td>

                        <td>
                            <?php
                           
                            $tab_nom_jour = array('Monday' => 2, 'Tuesday' => 3, 'Wednesday' => 4, 'Thursday' => 5, 'Friday' => 6, 'Saturday' => 7, 'Sunday' => 1);


                            $total = 0;
                            //$fos = $this->auth->get_fo_list();
                            $days[] = array();

                            //tt les outlets d'un fo 
                            $outlets = $this->Outlet_model->get_outlets_by_id($fo->id);
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

                            $perc = ($daily_visit / $target_meurch * 100);
                            echo $target_meurch;
                            ?>
                        </td>
                        <td><?php echo number_format($perc, 2, ',', '.') . '%'; ?></td>

                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

    </div>
</div>
    </div>
</div>


