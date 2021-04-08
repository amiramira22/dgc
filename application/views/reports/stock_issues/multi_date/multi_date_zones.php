<div class="row">

    <?php //print_r($json_channels); ?>
    <div class="col-md-12">
        <?php
        foreach ($selected_zone_ids as $zone_id) {
            ?>

            <div class="portlet light ">
                <div class="portlet-title tabbable-line">
                    <div class="caption">
                        <i class="icon-settings font-red"></i>
                        <span class="caption-subject font-red bold uppercase"><?php echo $this->Zone_model->get_zone_name($zone_id); ?></span>
                    </div>
                </div>
                <div style=" width:100%;" id="zone_div<?php echo $zone_id; ?>"></div>

            </div> <!-- end portlet light -->

            <script type="text/javascript">
                var channel_ids = JSON.stringify(<?php echo $json_channel_ids; ?>);
                $('#zone_div<?php echo $zone_id; ?>').html('<div class="col-md-12"><img src="<?php echo base_url('assets/img/ajax-loading.gif'); ?>" class="img-responsive img-center" /></div>');
                jQuery.ajax({
                    url: "<?php echo site_url("reports/load_av_zone"); ?>",
                    data: {
                        start_date: "<?php echo $start_date; ?>",
                        end_date: "<?php echo $end_date; ?>",
                        multi_date: "<?php echo $multi_date; ?>",
                        date_type: "<?php echo $date_type; ?>",
                        category_id: "<?php echo $category_id; ?>",
                        zone_id: "<?php echo $zone_id; ?>",
                        json_channel_ids: channel_ids,
                        zone_val: "<?php echo $zone_id; ?>",
                        out_val: "0"
                    },
                    type: "POST",
                    success: function (data) {
                        $('#zone_div<?php echo $zone_id; ?>').html(data);
                    }
                });
            </script>
        <?php } // end zones foreach     ?>
    </div> <!-- end col-md-12 -->
</div>  <!-- end row 2-->