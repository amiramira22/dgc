<?php
include ('header.php');
//bcm
?>

</br> 


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>Routing
                </div>

            </div>
            <div class="portlet-body">

                <?php if ($user_id) {
                    ?>

                    <style>
                        table td {
                            width: 50px;
                            overflow-x:auto;

                            white-space: nowrap;
                        }

                        table th {
                            width: 10px;

                        }
                    </style>
                    <div class="table-responsive">
                        <table class="table table-bordered " width="100%">
                            <thead>
                                <tr>
                                     <?php foreach ($dates as $date) { ?>	

                                        <th class="text-left " ><?php echo $date; ?></th>
                                        <?php } ?>
                                </tr>
                               
                                    <tr>
	
                                        

                                        <?php foreach ($dates as $date) { ?>
                                            <td class="text-left" >
                                                 
                                                <?php
                                        $outlets = ($this->Report_model->get_outlet_by_admin_date($user_id, $date));
                                        foreach ($outlets as $outlet) {
                                            ?>
                                                <font size="2">
                                                <?php
                                                if (isset($outlet->name)) {
                                                    echo $outlet->name;
                                                } else
                                                    echo '-';
                                                ?>
                                                </font>
                                                <br>
                                                 <?php } ?>
                                            </td>

                                        <?php } ?> 
                                    </tr>
                               

                            </thead>
                        </table>
                    </div>			

                <?php } else { ?>
                    <div class="note note-danger">
                        <span class="label label-danger">NOTE!</span>
                        <span class="bold">No data .</span> 
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php
include ('footer.php');
?>
