<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="<?php echo base_url('assets/plugins/excel_jquery/src/jquery.table2excel.js'); ?>" type="text/javascript"></script>




<?php
//    //print_r($row);
//    echo $row['product_name'];
//    echo '<br>';
//    echo $row['outlet_name'];
//    echo '<br>';
//    echo $row['date'];
//    echo '<br>';
//    echo '<br>';
//print_r($nb_oos_tracking);
$outlets = array();
$components = array();

foreach ($report_data as $row) {

    $outlet = $row['outlet_name'];

    if (!in_array($outlet, $outlets)) {
        $outlets[] = $outlet;
    }
    //create an array for every brand and the count at a outlet
    $components[$row['product_name']][$outlet] = array($row['date'], $row['nb_oos']);
}
//print_r($components);
?>
<div class="portlet light">

    <div class="portlet-title">
        <div class="caption font-red-sunglo">

            <span class="caption-subject bold uppercase">TRACKING OOS</span>
            <span class="caption-helper"></span>
        </div>

    </div> <!-- end portlet-title -->
    <button class="btn btn-circle red-mint btn-outline sbold uppercase">Export EXCEL</button>
    <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover dt-responsive" id="table2excel">
            <thead>
                <tr>
                    <th>Product</th>
                    <?php foreach ($outlets as $out) { ?>
                        <th align="center"><?php echo $out; ?></th>
                    <?php } ?>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($components as $product_name => $componentDates) { ?>
                    <tr>

                        <?php
                        //$product = $this->Product_model->get_product($product_id);
                        ?>
                        <td><?php echo $product_name; ?></td>

                        <?php foreach ($outlets as $out) { ?>
                            <td align="center">
                                <?php
                                if (isset($componentDates[$out]) && ($componentDates[$out][1] >= 3)) {
                                    //$av_j_1 = $this->Report_model->get_av_j(24, 101, $componentDates[$out][0], 1);
                                    //$av_j_2 = $this->Report_model->get_av_j($product_name, $out, $componentDates[$out], 2);
                                    //if ($av_j_1 == 0 and $av_j_2 == 0)
                                    //echo 'num_rows'.$av_j_1;
                                    echo reverse_format($componentDates[$out][0]);
                                    echo '<br>';
                                    echo '(' . $componentDates[$out][1] . ')';
                                } else
                                    echo '-';
                                ?> 
                            </td>

                        <?php } // end foreach dates    ?>
                    <?php }// end foreach components  ?>
                </tr>
            </tbody>
        </table>

    </div>
</div>
<!--<div class="row">
    <div class="col-md-12 text-center">
<?php echo $pagination; ?>
    </div>
</div>-->

<script>
    var d = new Date();
    var date = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear();
    //var day = d.getDate();
    $("button").click(function () {
        $("#table2excel").table2excel({

            name: "Worksheet Name",
            filename: "BCM_OOS TRACKING_" + date //do not include extension
        });
    }
    );
</script>