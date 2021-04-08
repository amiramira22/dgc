<!-- Begin: life time stats -->
<!-- BEGIN PORTLET-->
<div class="portlet light ">
    <div class="portlet-title tabbable-line">
        <div class="caption">
            <i class="icon-settings font-red"></i>
            <span class="caption-subject font-red bold uppercase">Top 5 OOS</span>
        </div>
        <ul class="nav nav-tabs">

            <li class="active">
                <a href="#<?php echo 'tab4_' . $date_this_week; ?>" id="<?php echo 'top_' . $date_this_week; ?>" data-toggle="tab"> <?php echo format_week($date_this_week); ?> </a>
            </li>

            <script type="text/javascript">

                $("#<?php echo 'top_' . $date_this_week; ?>").click(function () {
                    $('#content_top_oos_products<?php echo '_' . $date_this_week; ?>').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');

                    jQuery.ajax({
                        url: "<?php echo site_url("dashboard/load_top_oos_products"); ?>",
                        data: {date: "<?php echo $date_this_week; ?>"},
                        type: "POST",
                        success: function (data) {
                            $('#content_top_oos_products<?php echo '_' . $date_this_week; ?>').html(data);
                        }
                    });

                });

            </script>


            <li>
                <a href="#<?php echo 'tab4_' . $date_last_week; ?>" id="<?php echo 'top_' . $date_last_week; ?>" data-toggle="tab"> <?php echo format_week($date_last_week); ?> </a>
            </li>


            <script type="text/javascript">

                $("#<?php echo 'top_' . $date_last_week; ?>").click(function () {
                    $('#content_top_oos_products<?php echo '_' . $date_last_week; ?>').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');

                    jQuery.ajax({
                        url: "<?php echo site_url("dashboard/load_top_oos_products"); ?>",
                        data: {date: "<?php echo $date_last_week; ?>"},
                        type: "POST",
                        success: function (data) {
                            $('#content_top_oos_products<?php echo '_' . $date_last_week; ?>').html(data);
                        }
                    });

                });

            </script>



            <li>
                <a href="#<?php echo 'tab4_' . $date_last2_week; ?>" id="<?php echo 'top_' . $date_last2_week; ?>" data-toggle="tab"> <?php echo format_week($date_last2_week); ?> </a>
            </li>


            <script type="text/javascript">

                $("#<?php echo 'top_' . $date_last2_week; ?>").click(function () {
                    $('#content_top_oos_products<?php echo '_' . $date_last2_week; ?>').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');

                    jQuery.ajax({
                        url: "<?php echo site_url("dashboard/load_top_oos_products"); ?>",
                        data: {date: "<?php echo $date_last2_week; ?>"},
                        type: "POST",
                        success: function (data) {
                            $('#content_top_oos_products<?php echo '_' . $date_last2_week; ?>').html(data);
                        }
                    });

                });

            </script>



            <li>
                <a href="#<?php echo 'tab4_' . $date_last3_week; ?>" id="<?php echo 'top_' . $date_last3_week; ?>" data-toggle="tab"> <?php echo format_week($date_last3_week); ?> </a>
            </li>


            <script type="text/javascript">

                $("#<?php echo 'top_' . $date_last3_week; ?>").click(function () {
                    $('#content_top_oos_products<?php echo '_' . $date_last3_week; ?>').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');

                    jQuery.ajax({
                        url: "<?php echo site_url("dashboard/load_top_oos_products"); ?>",
                        data: {date: "<?php echo $date_last3_week; ?>"},
                        type: "POST",
                        success: function (data) {
                            $('#content_top_oos_products<?php echo '_' . $date_last3_week; ?>').html(data);
                        }
                    });

                });

            </script>


        </ul>
    </div>

    <div class="portlet-body">
        <div class="tab-content">

            <div class="tab-pane active" id="<?php echo 'tab4_' . $date_this_week; ?>">

                <div id="content_top_oos_products<?php echo '_' . $date_this_week; ?>">


                    <div class="mt-element-list">
                        <div class="mt-list-head list-simple font-white bg-red">
                            <div class="list-head-title-container">
                                <div class="list-date" align="right">%</div>
                                <h3 class="list-title">Product</h3>
                            </div>
                        </div>
                        <div class="mt-list-container list-simple">
                            <ul>

                                <?php
                                $i = 1;
                                foreach ($prod_this_week as $p) {
                                    ?>

                                    <li id="id_model" class="mt-list-item">
                                        <div class="list-icon-container done">
                                            <i class="icon-check"></i> <?php echo $i; ?>
                                        </div>

                                        <div class="list-datetime"> 
                                            <?php
                                            $i++;
                                            echo number_format(($p['oos']), 2);
                                            ?> 
                                        </div>

                                        <div class="list-item-content">
                                            <h3 class="uppercase">
                                                <a href="javascript:;"><?php echo $p['product_name']; ?></a>
                                            </h3>
                                        </div>
                                    </li>

                                <?php } ?>


                                <li id="id_model" class="mt-list-item">
                                    <div class="list-icon-container done">
                                    </div>

                                    <div class="list-datetime"> 
                                        <a href="<?php echo site_url('dashboard/top_oos_all_product/' . $date_this_week); ?>" target="_blank">view all</a> 
                                    </div>

                                    <div class="list-item-content">
                                        <h3 class="uppercase">
                                            <a href="javascript:;"></a>
                                        </h3>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <div class="tab-pane " id="<?php echo 'tab4_' . $date_last_week; ?>">


                <div id="content_top_oos_products<?php echo '_' . $date_last_week; ?>">


                    <div class="mt-element-list">
                        <div class="mt-list-head list-simple font-white bg-red">
                            <div class="list-head-title-container">
                                <div class="list-date">%</div>
                                <h3 class="list-title">Product</h3>
                            </div>
                        </div>
                        <div class="mt-list-container list-simple">

                        </div>
                    </div>
                </div>

            </div>

            <div class="tab-pane " id="<?php echo 'tab4_' . $date_last2_week; ?>">
                <div id="content_top_oos_products<?php echo '_' . $date_last2_week; ?>">
                    <div class="mt-element-list">
                        <div class="mt-list-head list-simple font-white bg-red">
                            <div class="list-head-title-container">
                                <div class="list-date">%</div>
                                <h3 class="list-title">Product</h3>
                            </div>
                        </div>
                        <div class="mt-list-container list-simple">

                        </div>
                    </div>
                </div>

            </div>

            <div class="tab-pane " id="<?php echo 'tab4_' . $date_last3_week; ?>">

                <div id="content_top_oos_products<?php echo '_' . $date_last3_week; ?>">

                    <div class="mt-element-list">
                        <div class="mt-list-head list-simple font-white bg-red">
                            <div class="list-head-title-container">
                                <div class="list-date">%</div>
                                <h3 class="list-title">Product</h3>
                            </div>
                        </div>
                        <div class="mt-list-container list-simple">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
