<?php //bcm  ?>


<div class="row">
    <div class="col-md-12">
        <?php
        //print_r($selected_channel_ids);
        //echo '<br>';
        //print_r($selected_channel_ids);
        foreach ($selected_channel_ids as $channel_id) {
            ?>

            <div class="portlet light ">
                <div class="portlet-title tabbable-line">
                    <div class="caption">
                        <i class="icon-settings font-red"></i>
                        <span class="caption-subject font-red bold uppercase"><?php echo $this->Channel_model->get_channel_name($channel_id); ?></span>
                    </div>
                </div>
                <div style=" width:100%;" id="channel_div<?php echo $channel_id; ?>"></div>

            </div> <!-- end portlet light -->

            <script type="text/javascript">

                $('#channel_div<?php echo $channel_id; ?>').html('<div class="col-md-12"><img src="<?php echo base_url('assets/img/ajax-loading.gif'); ?>" class="img-responsive img-center" /></div>');
                jQuery.ajax({
                    url: "<?php echo site_url("reports/load_shelf_channel"); ?>",
                    data: {
                        start_date: "<?php echo $start_date; ?>",
                        end_date: "<?php echo $end_date; ?>",
                        date_type: "<?php echo $date_type; ?>",
                        multi_date: "<?php echo $multi_date; ?>",
                        category_id: "<?php echo $category_id; ?>",
                        channel_id: "<?php echo $channel_id; ?>",
                        zone_id: "-1",
                        channel_val: "<?php echo $channel_id; ?>",
                        out_val: "0"

                    },
                    type: "POST",
                    success: function (data) {
                        $('#channel_div<?php echo $channel_id; ?>').html(data);
                    }
                });
            </script>

        <?php } // end channels foreach      ?>
    </div> <!-- end col-md-12 -->
</div>  <!-- end row 2-->