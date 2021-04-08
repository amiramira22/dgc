<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>




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
$products = array();
$components = array();

foreach ($report_data as $row) {


    $product = $row['product_name'];

    if (!in_array($product, $products)) {
        $products[] = $product;
    }
    //create an array for every brand and the count at a outlet
    $components[$row['outlet_name']][$product] = array($row['date'], $row['nb_oos']);
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
                    <th>Outlet</th>
                    <?php foreach ($products as $pd) { ?>
                        <th align="center"><?php echo $pd; ?></th>
                    <?php } ?>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($components as $outlet_name => $componentDates) { ?>
                    <tr>

                        <?php
                        //$product = $this->Product_model->get_product($product_id);
                        ?>
                        <td><?php echo $outlet_name; ?></td>

                        <?php foreach ($products as $pd) { ?>
                            <td align="center">
                                <?php
                                if (isset($componentDates[$pd]) && ($componentDates[$pd][1] >= 3)) {
                                    //$av_j_1 = $this->Report_model->get_av_j(24, 101, $componentDates[$out][0], 1);
                                    //$av_j_2 = $this->Report_model->get_av_j($product_name, $out, $componentDates[$out], 2);
                                    //if ($av_j_1 == 0 and $av_j_2 == 0)
                                    //echo 'num_rows'.$av_j_1;
                                    echo reverse_format($componentDates[$pd][0]);
                                    echo '  ';
                                    echo '(' . $componentDates[$pd][1] . ')';
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
            filename: "OOS TRACKING_" + date //do not include extension
        });
    }
    );
</script>