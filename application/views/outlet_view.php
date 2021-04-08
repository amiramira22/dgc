
<?php include('header.php'); ?>
<style>

    .row-center{text-align: center}

</style>


<div class='row '>
    <div class="col-md-12">
        <!-- BEGIN MARKERS PORTLET-->
        <div class="portlet light portlet-fit bordered">

            <div class="portlet-body">

                <?php echo $map['js']; ?>
                <?php echo $map['html']; ?>
            </div>
        </div>
        <!-- END MARKERS PORTLET-->
    </div>


</div>



<div class='row'>
    <div class="col-md-12">
        <table class="table table-striped table-bordered table-hover" width="100%">




            <tr>
                <td><b>Name :</b></td> <td><?php echo $outlet->name; ?></td>
                <td><b>Zone :</b></td> <td><?php echo $outlet->zone; ?></td>
            <tr>

            <tr>
                <td><b>State :</b></td> <td><?php echo $outlet->state; ?></td>
                <td><b>Adress :</b></td> <td><?php echo $outlet->adress; ?></td>
            <tr>

            <tr>
                <td><b>Contact PDV :</b></td> <td><?php echo $outlet->contact_pdv; ?></td>
                <td><b>Contact :</b></td> <td><?php echo $outlet->contact; ?></td>
            <tr>







            <tr>

                <td><b>Caisse Number :</b></td> <td><?php echo $outlet->caisse_number; ?></td>
            <tr>

            <tr>
                <td><b>Visit Day :</b></td> <td><?php echo $outlet->visit_day; ?></td>
                <td><b>Delivery Day :</b></td> <td><?php echo $outlet->delivery_day; ?></td>
            <tr>
        </table>


    </div>
</div>

<div class='row row-center'>
    <div class="col-md-12">
        <center><img width="800px" height="400px" src="<?php echo base_url('uploads/outlet') . '/' . $outlet->photos; ?>"></center>

    </div>
</div>


<?php include('footer.php'); ?>