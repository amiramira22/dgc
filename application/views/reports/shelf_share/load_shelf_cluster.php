<?php
$dates = array();
$components = array();
$count_date = 0;
$components_date = array();

foreach ($report_data as $row) {
    $date = ($row['date']);
    if (!in_array($date, $dates)) {
        $dates[] = $date;
        $count_date += 1;
    }
    //create an array for every brand and the count at a outlet
    $components[$row['product_id']][$date] = array($row['shelf'], $row['metrage']);
    $components_date [$row['date']] [$row['product_id']] = $row['metrage'];
}// end foreach report_data
?>

<table class="table table-striped table-bordered table-hover " width="100%">
    <thead>
        <tr>
            <th colspan="3"></th>
            <?php
            foreach ($dates as $date) {

                if (isset($channel)) {
                    ?>
                    <th colspan="3"><?php echo format_quarter($date); ?></th>
                <?php } else { ?>
                    <th colspan="3"><?php echo format_quarter($date); ?></th>
                    <?php
                }
            }
            ?>
            <th class ="text-center" colspan="3">Average</th>
        </tr>

        <tr>
            <th>Rank</th>
            <th>Brand</th>
            <th>Product</th>

            <?php foreach ($dates as $date) { ?>

                <th>Shelf</th>
                <th>Metrage</th>
                <th>%</th>
            <?php } ?>
            <th>Shelf</th>
            <th>Metrage</th>
            <th>%</th>
        </tr>

    <thead>

    <tbody>

        <?php
        $i = 0;
        foreach ($components as $product_id => $componentDates) {
            $i++;
            ?>
            <tr>

                <?php
                $product = $this->Product_group_model->get_product_group($product_id);
                $brand_id = $product->brand_id;
                $brand_name = $this->Brand_model->get_brand_name($brand_id);
                ?>
                <td><b><?php echo $i; ?></b></td>
                <td><?php echo $brand_name; ?></td>
                <td><?php echo $product->name; ?></td>


                <?php foreach ($dates as $date) { ?>

                    <td class="shelf"><?php
                        if (isset($componentDates[$date])) {
                            echo $componentDates[$date][0];
                        } else
                            echo '-';
                        ?> </td>

                    <td class="metrage"><?php
                        if (isset($componentDates[$date])) {
                            echo number_format(($componentDates[$date][1]), 2);
                        } else
                            echo '-';
                        ?> 
                    </td>


                    <td class="perc"><?php
                        if (isset($componentDates[$date])) {
                            //echo  ($componentDates[$date][0]/ $sum_shelf_date[$date])*100 .'%';
                            // echo $componentDates[$date][0];
                            if ($sum_metrage[$date] > 0) {
                                echo number_format(($componentDates[$date][1] / $sum_metrage[$date]) * 100, 2);
                                //print_r($sum_shelf_date[$date]);
                            } else {
                                echo ' 0';
                            }
                        } else
                            echo '-';
                        ?> </td>

                <?php } // end foreach dates   ?>
                <td class="total-shelf_row"></td>
                <td class="total-metrage_row"></td>
                <td class="total-perc_row"></td>
            <?php }// end foreach components   ?>
        </tr>
    </tbody>
</table>


<script>
    $(document).ready(function () {
        //iterate through each row in the table
        $('tr').each(function () {
            //the value of sum needs to be reset for each row, so it has to be set inside the row loop
            var sum = 0
            var n = 0
            //find the combat elements in the current row and sum it 
            $(this).find('.shelf').each(function () {
                var shelf = $(this).text();
                if (!isNaN(shelf) && shelf.length !== 0) {
                    sum += parseFloat(shelf);
                    n++;
                }
            });
            //set the value of currents rows sum to the total-combat element in the current row
            $('.total-shelf_row', this).html(Math.round(sum));
        });

        $('tr').each(function () {
            //the value of sum needs to be reset for each row, so it has to be set inside the row loop
            var sum = 0
            var n = 0
            //find the combat elements in the current row and sum it 
            $(this).find('.metrage').each(function () {
                var metrage = $(this).text();
                if (!isNaN(metrage) && metrage.length !== 0) {
                    sum += parseFloat(metrage);
                    n++;
                }
            });
            //set the value of currents rows sum to the total-combat element in the current row
            $('.total-metrage_row', this).html(Math.round(sum));
        });

        $('tr').each(function () {
            //the value of sum needs to be reset for each row, so it has to be set inside the row loop
            var sum = 0
            var n = 0
            //find the combat elements in the current row and sum it 
            $(this).find('.perc').each(function () {
                var perc = $(this).text();

                if (!isNaN(perc) && perc.length !== 0) {
                    sum += parseFloat(perc);
                    n++;
                }
            });


            //set the value of currents rows sum to the total-combat element in the current row
            $('.total-perc_row', this).html(parseFloat(sum / n).toFixed(2));
        });
    });
</script>




