
<?php include('header.php'); ?>

<div class='row'>
    <div class="col-md-12">
        <!-- BEGIN MARKERS PORTLET-->
        <div class="portlet light portlet-fit bordered">
            <div class="portlet-title">
                <div class="caption">

                    <span class="caption-subject font-red bold uppercase">Outlet Geolocalisation</span>
                </div>

            </div>
            <div class="portlet-body">

                <?php echo $map['js']; ?>
                <?php echo $map['html']; ?>
            </div>
        </div>
        <!-- END MARKERS PORTLET-->
    </div>
</div>

<?php include('footer.php'); ?>