<?php
$outlets = array();
$components = array();
$count_outlets = 0;
foreach ($report_data as $row) {
    $outlet_name = ($row['outlet_name']);
    if (!in_array($outlet_name, $outlets)) {
        $outlets[] = $outlet_name;
        $count_outlets += 1;
    }
    //create an array for every brand and the count at a outlet
    $components[$row['product_id']][$outlet_name] = array($row['shelf'],$row['metrage']);
}// end foreach report_data
?>







<table class="table table-striped table-bordered table-hover " width="100%">
    <thead>
        <tr>
            <th colspan="2"></th>
            <?php foreach ($outlets as $outlet_name) { ?>
                <th class ="text-center" colspan="3"><?php echo ($outlet_name); ?></th>
            <?php } ?>

        </tr>

        <tr>

            <th>Brand</th>
            <th>Product</th>

            <?php foreach ($outlets as $outlet_name) { ?>

                <th class ="text-center">Shelf</th>
                <th class ="text-center">Metrage</th>
                <th class ="text-center">%</th>
                <?php } ?>


        </tr>

    <thead>

    <tbody>

        <?php
        $i = 0;
        foreach ($components as $product_id => $componentOutlets) {
            $i++;
            ?>
            <tr>

                <?php
                $product = $this->Product_group_model->get_product_group($product_id);
                $brand_id = $product->brand_id;
                $brand_name = $this->Brand_model->get_brand_name($brand_id);
                ?>

                <td><?php echo $brand_name; ?></td>
                <td><?php echo $product->name; ?></td>


                <?php foreach ($outlets as $outlet_name) { ?>
                    <td>
                        <?php
                        if (isset($componentOutlets[$outlet_name])) {


                            echo $componentOutlets[$outlet_name][0];
                        } else
                            echo '-';
                        ?> 
                    </td>
                    
                     <td>
                        <?php
                        if (isset($componentOutlets[$outlet_name])) {


                            echo number_format($componentOutlets[$outlet_name][1],3);
                        } else
                            echo '-';
                        ?> 
                    </td>
                    
                     <td>
                        <?php
                        if (isset($componentOutlets[$outlet_name][1]) && $sum_metrage[$outlet_name]!=0 ) {


                            echo number_format($componentOutlets[$outlet_name][1]*100/$sum_metrage[$outlet_name], 2, '.', ' ');
                        } else
                            echo '-';
                        ?> 
                    </td>



                <?php } // end foreach outlet_names  ?>




            <?php }// end foreach components  ?>


        </tr>

    </tbody>



</table>
