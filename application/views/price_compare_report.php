

<?php
if ($excel != 1) {
    include ('header.php');
    ?>

    <script type="text/javascript">

        Date.prototype.getWeek = function () {
            var date = new Date(this.getTime());
            date.setHours(0, 0, 0, 0);
            // Thursday in current week decides the year.
            date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
            // January 4 is always in week 1.
            var week1 = new Date(date.getFullYear(), 0, 4);
            // Adjust to Thursday in week 1 and count number of weeks from date to week1.
            return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000
                    - 3 + (week1.getDay() + 6) % 7) / 7);
        }

    // Returns the four-digit year corresponding to the ISO week of the date.
        Date.prototype.getWeekYear = function () {
            var date = new Date(this.getTime());
            date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
            return date.getFullYear();
        }


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
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-search"></i>Search
                    </div>

                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <?php $attributes = array('class' => 'form-horizontal','autocomplete'=>'off'); ?>
                    <?php echo form_open('reports/price_compare_report', $attributes); ?>

                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-2">Start date 
                            </label>

                            <div class="col-md-4">
                                <?php
                                $data = array('id' => 'datepicker1', 'class' => 'form-control');
                                echo form_input($data);
                                ?>
                                <input type="hidden" name="start_date"  id="datepicker1_alt" />		
                            </div>

                            <label class="control-label col-md-2">Zone 
                            </label>
                            <div class="col-md-4">


                                <?php
                                $zone_ids = array();
                                $zone_ids[-1] = 'All';
                                foreach ($zones as $zone) {
                                    $zone_ids[$zone->id] = $zone->name;
                                }
                                ?>
                                <?php echo form_dropdown('zone_id', $zone_ids, set_value('zone_id', $zone_id), 'class="span3 form-control"'); ?>

                            </div>

                        </div>


                        <div class="form-group">


                            <label class="control-label col-md-2">Category
                            </label>
                            <div class="col-md-4">


                                <?php
                                $category_ids = array();

                                foreach ($categories as $category) {
                                    $category_ids[$category->id] = $category->name;
                                }
                                ?>
                                <?php echo form_dropdown('category_id', $category_ids, set_value('category_id', $category_id), 'class="span3 form-control"'); ?>

                            </div>





                            <label class="control-label col-md-2">Export excel 
                            </label>
                            <div class="col-md-4">

    <?php
    echo form_dropdown('excel', array(
        '0' => 'No',
        '1' => 'Yes'
            ), '', 'class="form-control"');
    ?>
                            </div>


                        </div>





                        <div class="btn-group pull-right">
                            <div class="span3">
                                <button class="btn btn-danger" name="submit" value="search" >Submit</button>


                            </div>

                        </div>
                        <fieldset>


                            </br>








                            </br>

                            <div class="row">

                            </div>



                        </fieldset>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>




<?php
} else {

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=" . $page_title . " - " . format_week($start_date) . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}
?>


<?php
if ($start_date) {
    ?>
    <h3 class="alert alert-danger"><?php echo $sub_title . ' date : ' . reverse_format($start_date); ?></h3>

    <?php
    foreach ($clusters as $cluster) {
        ?>
        <div class="portlet light ">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-settings font-red"></i>

                    <span class="caption-subject font-red bold uppercase"><?php echo $this->Cluster_model->get_cluster_name($cluster->id); ?></span>
                </div>

            </div>





        <?php
        $report_data = $this->Report_model->get_price_compare_data($start_date, $zone_id, $cluster->id);




        $outlets = array();
        $components = array();

        $count_outlet = 0;

        foreach ($report_data as $row) {


            if (!in_array($row['outlet_name'], $outlets)) {
                $outlets[] = $row['outlet_name'];
                $count_outlet += 1;
            }
            //create an array for every brand and the count at a outlet
            $components[$row['product_id']][$row['outlet_name']] = array($row['min_price'], $row['max_price'])
            ;
        }
        ?>


            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">
                <tr>
                    <th colspan="2"></th>

            <?php foreach ($outlets as $outlet) { ?>
                        <th colspan="2"><?php echo $outlet; ?></th>
        <?php } ?>
                </tr>

                <tr>
                    <th>Brand</th>
                    <th>Product</th>
                    <?php foreach ($outlets as $outlet) { ?>
                        <th>Min price</th>
                        <th>Max price</th>
        <?php } ?>
                </tr>

        <?php foreach ($components as $product_id => $componentOutlets) { ?>
                    <tr>

            <?php
            $product = $this->Product_model->get_product($product_id);
            $brand_id = $product->brand_id;
            $brand_name = $this->Brand_model->get_brand_name($brand_id);
            ?>
                        <td><?php echo $brand_name; ?></td>
                        <td><?php echo $product->name; ?></td>


                        <?php foreach ($outlets as $outlet) { ?>
                            <td><?php
                            if (isset($componentOutlets[$outlet][0])) {
                                echo $componentOutlets[$outlet][0];
                            } else
                                echo '-';
                            ?> </td>


                            <td><?php
                                if (isset($componentOutlets[$outlet][1])) {
                                    echo $componentOutlets[$outlet][1];
                                } else
                                    echo '-';
                                ?> </td>

                            <?php } ?>

        <?php } ?>


                </tr>



            </table>

                    <?php
                    print_r('<br>');
                    ?>
        </div>
        <?php
    }
}
?>



    <?php
    include ('footer.php');
    ?>