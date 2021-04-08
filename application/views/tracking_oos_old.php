<?php // bcm                         ?>
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


<?php
//echo $nb_out;
//echo $channel_id;


foreach ($outlets as $out) {
    $outlet_id = $out->id;

    $report_data = $this->Report_model->get_tracking_oos($outlet_id);
    $dates = array();
    $components = array();

    foreach ($report_data as $row) {
        $date = $row['date'];

        if (!in_array($date, $dates)) {
            $dates[] = $date;
        }

        $components[$row['product_id']][$date] = array($row['av']);
    }
    //print_r($dates);
    //echo '<br>';
    //echo '<br>';
    //print_r($components);
    ?>
    <div class="portlet light">
        <div class="portlet-title">
            <div class="caption font-red-sunglo">
                <span class="caption-subject bold uppercase">tracking oos</span>
                <span class="caption-helper bold uppercase">
                    <?php
                    if ($outlet_id != -1) {
                        $outlet = $this->Outlet_model->get_outlet($outlet_id);
                        echo '|' . $outlet->name;
                    }
                    ?>
                </span>
            </div>
        </div> <!-- end portlet-title -->
        <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover dt-responsive" id="myTable<?php echo $outlet_id; ?>">
                <thead>
                    <tr>
                        <th>Brand</th>
                        <th>Product</th>
                        <th>date </th>
                    </tr>
                <thead>
                <tbody>
                    <?php foreach ($components as $product_id => $componentDates) { ?>
                        <?php
                        $product = $this->Product_model->get_product($product_id);
                        $brand_id = $product->brand_id;
                        $brand_name = $this->Brand_model->get_brand_name($brand_id);
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
                        if ($is_oos == 0) {
                            ?>
                            <tr>
                                <td><?php echo $brand_name; ?></td>
                                <td>
                                    <?php
                                    echo $product->name;

                                    //echo '<br>';
                                    //echo $date_os;
                                    ?>
                                </td>
                                <td>  <?php echo $date_os; ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
     <script>
        $(document).ready(function () {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("myTable<?php echo $outlet_id; ?>");
            switching = true;
            /* Make a loop that will continue until
             no switching has been done: */
            while (switching) {
                // Start by saying: no switching is done:
                switching = false;
                rows = table.getElementsByTagName("TR");
                /* Loop through all table rows (except the
                 first, which contains table headers): */
                for (i = 1; i < (rows.length - 1); i++) {
                    // Start by saying there should be no switching:
                    shouldSwitch = false;
                    /* Get the two elements you want to compare,
                     one from current row and one from the next: */
                    x = rows[i].getElementsByTagName("TD")[2];
                    y = rows[i + 1].getElementsByTagName("TD")[2];
                    // Check if the two rows should switch place:
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    /* If a switch has been marked, make the switch
                     and mark that a switch has been done: */
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        });
    </script>
<?php } ?>


<div class="row">
    <div class="col-md-12 text-center">
        <?php echo $pagination; ?>
    </div>
</div>
