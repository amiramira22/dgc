<?php
date_default_timezone_set('Europe/Amsterdam');
$date = new DateTime();
$date->modify('this week -7 days');
$date_last3_week = $date->format('Y-m-d');
?>
<div class="row">
    <div id="indice" ></div>
</div> 


<div class="row">
    <div class="col-md-6" id="oos_peer_channel">
    </div>

    <div class="col-md-6" id="oos_per_category">
    </div>  

</div>  


<div class="row">
    <div class="col-md-6" id="top_5_oos"> 
    </div>  <!-- End: first col-md-6 --> 


    <div class="col-md-6" id="stock_issue">
    </div>
</div> <!--  row top oos-->

<div class="row">
    <div class="col-md-12" id="oos_trend">

    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <!-- BEGIN MARKERS PORTLET-->
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-red"></i>
                    <span class="caption-subject font-red bold uppercase"> daily visits</span>
                </div>

            </div>
            <div class="portlet-body">

                <?php echo $map['js']; ?>
                <?php echo $map['html']; ?>
            </div>
        </div>
        <!-- END MARKERS PORTLET-->
    </div>
    <div id="feeds">
    </div>

</div>


<script type="text/javascript">
    $('#indice').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');
    jQuery.ajax({
        url: "<?php echo site_url("dashboard/load_indice"); ?>",

        success: function (data) {
            $('#indice').html(data);
        }
    });
</script>

<script type="text/javascript">
    $('#oos_peer_channel').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');
    jQuery.ajax({
        url: "<?php echo site_url("dashboard/load_oos_peer_channel"); ?>",
        success: function (data) {
            $('#oos_peer_channel').html(data);
        }
    });
</script>

<script type="text/javascript">
    $('#oos_per_category').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');
    jQuery.ajax({
        url: "<?php echo site_url("dashboard/load_oos_per_category"); ?>",

        success: function (data) {
            $('#oos_per_category').html(data);
        }
    });
</script>
<script type="text/javascript">
    $('#top_5_oos').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');
    jQuery.ajax({
        url: "<?php echo site_url("dashboard/load_top_5_oos"); ?>",

        success: function (data) {
            $('#top_5_oos').html(data);
        }
    });
</script>

<script type="text/javascript">
    $('#stock_issue').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');
    jQuery.ajax({
        url: "<?php echo site_url("dashboard/load_stock_issue"); ?>",

        success: function (data) {
            $('#stock_issue').html(data);
        }
    });
</script>

<script type="text/javascript">
    $('#oos_trend').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');
    jQuery.ajax({
        url: "<?php echo site_url("dashboard/load_oos_trend"); ?>",

        success: function (data) {
            $('#oos_trend').html(data);
        }
    });
</script>

<script type="text/javascript">
    $('#feeds').html('<div class="col-md-12"><img style="margin-left: 30%;" src="<?php echo base_url('assets/img/plus-loader.gif'); ?>" class="img-responsive img-center" /></div>');
    jQuery.ajax({
        url: "<?php echo site_url("dashboard/feeds"); ?>",
        data: {date: "<?php echo $date_last3_week; ?>"},
        type: "POST",
        success: function (data) {

            $('#feeds').html(data);
        }
    });

</script>s

