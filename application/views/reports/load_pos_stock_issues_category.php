<?php
$product_ids = array();
$components = array();
$count_products = 0;

foreach ($report_data as $row) {
    $product_id = $row['product_id'];
    if (!in_array($product_id, $product_ids)) {
        $product_ids[] = $product_id;
        $count_products += 1;
    }
    //create an array for every brand and the count at a outlet
    $components[$row['outlet_id']][$product_id] = array($row['oos'], $row['ha'], $row['total'], $row['av']);
}// end foreach report_data
?>


<style>
    td{
        text-align: center !important;
        width: 125px !important;
        text-overflow: ellipsis;
        word-wrap: break-word;
    }

    th{

        text-align: center !important;
        width: 125px !important;
        text-overflow: ellipsis;
        word-wrap: break-word;
    }
</style>




<table class="table table-striped table-bordered table-hover " width="100%">
    <thead>


        <tr>
            <th></th>
            <?php foreach ($product_ids as $product_id) { ?>
                <th class ="text-center" ><?php echo $this->Product_model->get_product($product_id)->name; ?></th>
            <?php } ?>

        </tr>

        <tr>


            <th>Outlet</th>

            <?php foreach ($product_ids as $product_id) { ?>
                <th class ="text-center"> AV%</th>
                <?php } ?>


        </tr>

    <thead>

    <tbody>

        <?php
        $i = 0;
        $sum_products = array();

        foreach ($components as $outlet_id => $componentPds) {
            $i++;
            ?>
            <tr>



                <td><?php echo $this->Outlet_model->get_outlet_name($outlet_id); ?></td>


                <?php foreach ($product_ids as $product_id) { ?>

                    <td clas="col-md-1">
        <?php
        if (isset($componentPds[$product_id])) {
            if (($componentPds[$product_id][1] != 0) && ($componentPds[$product_id][0] == 0) && ($componentPds[$product_id][3] == 0)) {
                echo '<p style="color:blue">HA</p>';
            } else {
                //echo number_format($componentOutlets[$outlet_name][0], 2, '.', ' ');
                $perc = number_format(($componentPds[$product_id][3] / ($componentPds[$product_id][0] + $componentPds[$product_id][3])) * 100, 2, '.', ' ');
                if (isset($sum_products[$product_id])) {
                    $sum_products[$product_id] = $sum_products[$product_id] + $perc;
                } else {
                    $sum_products[$product_id] = $perc;
                }

                if($perc=='0.00'){
                    echo '<p style="color:red">';
                }else{
                    echo '<p style="color:black">';
                }
                echo $perc.'</p>';
            }
        } else
            echo '-';
        ?> 

                    </td>

    <?php } // end foreach outlet_names   ?>
<?php }// end foreach components   ?>
        </tr>

        <tr>

            <td  ><b>Overage</b></td>
<?php foreach ($product_ids as $product_id) { ?>

                <td clas="col-md-1">

    <?php
    if ($i != 0 && (isset($sum_products[$product_id]))) {
        echo number_format($sum_products[$product_id] / $i, 2, '.', ' ');
    } else {
        echo '-';
    }
    ?> 

                </td>





<?php } // end foreach $product_id   ?>

        </tr>

    </tbody>



</table>
