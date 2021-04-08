<div class="portlet light">

    <div class="row">
        <div class="col-md-12">

            <table class="table table-striped table-bordered table-hover dt-responsive" cellpadding="0" cellspacing="0" border="1">
                <thead>
                    <tr>
                        <th>products</th>
                        <?php
                        foreach ($outlets as $out) {
                            ?>
                            <th><?php echo $out->name ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    foreach ($products as $pr) {
                        ?>
                        <tr> 
                            <td><?php echo $pr->name ?></td>
                            <?php
                            foreach ($outlets as $out) {
                                $report_data = $this->Report_model->get_tracking_oos($out->id, $pr->id);
                                if (!empty($report_data)) {
                                    $dates = array();
                                    $components = array();
                                    foreach ($report_data as $row) {
                                        $date = $row['date'];

                                        if (!in_array($date, $dates)) {
                                            $dates[] = $date;
                                        }

                                        $components[$row['product_id']][$date] = array($row['av']);
                                    }
                                    ?>
                                    <?php foreach ($components as $product_id => $componentDates) { ?>
                                        <?php
                                        $i = 0;
                                        $is_oos = 1;
                                        $dates_oos = array();
                                        $date_os = 0;
                                        ?>
                                        <?php foreach ($dates as $date) { ?>

                                            <?php
                                            if (isset($componentDates[$date])) {
                                                //if the product is oos
                                                if ($componentDates[$date][0] == 0) {
                                                    $dates_oos[$i] = $date;
                                                    //sort($dates_oos);
                                                    $i++;
                                                    //if the product is for the 3 time oos 
                                                    if ($i >= 3) {
                                                        $is_oos = 0;
                                                        $date_os = $dates_oos[0];
                                                    }
                                                }

                                                //if the product is av 
                                                else {
                                                    $i = 0;
                                                    $is_oos = 1;
                                                    $dates_oos = array();
                                                    $date_os = 0;
                                                }
                                            }
                                        }
                                    }
                                    ?>

                                    <?php
                                }//end if report

                                if ($is_oos == 0) {
                                    ?>
                                    <td>  <?php echo $date_os; ?></td>
                                    <?php
                                } else if ($is_oos == 1) {
                                    ?>
                                    <td>  <?php echo '-'; ?></td>
                                    <?php
                                }
                            }//endforeach
                            ?>

                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12 text-center">
        <?php echo $pagination; ?>
    </div>
</div>