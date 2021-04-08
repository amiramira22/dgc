<?php
include ('header.php');
//bcm
?>


<style>
    table, th, td {
        border: 1px solid black;
    }
</style>



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

                <?php $attributes = array('class' => 'form-horizontal'); ?>
                <?php echo form_open('reports/routing_report/', $attributes) ?>
                <div class="form-body">


                    <div class="row">




                        <div class="col-md-12">
                            <label >Field officer </label>

                            <?php
                            $admin_ids = array();


                            foreach ($admins as $ad) {
                                $admin_ids[$ad->id] = $ad->name;
                            }
                            ?>

                            <?php echo form_dropdown('user_id', $admin_ids, '', 'class="form-control"'); ?>
                        </div> <!-- end class="col-md-6"-->








                    </div>





                </div> <!--  end form-body-->
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            <input class="btn blue" type="submit" value="Search"/>
                            <button type="button" class="btn default">Cancel</button>
                        </div>
                    </div>
                </div>



                </form>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END VALIDATION STATES-->
    </div>
</div> <!-- END ROW FORM-->






</br> 


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>Routing Report <?php echo $sub_title; ?>
                </div>

            </div>
            <div class="portlet-body">


                <?php if ($user_id) {
                    ?>



                    <table class="table table-bordered " width="100%">
                        <thead>

                            <?php foreach ($dates as $date) { ?>	
                                <tr>

                                    <th class="text-center col-md-2" ><?php
                                        $outlets = ($this->Report_model->get_outlet_by_admin_date($user_id, $date));
                                        echo $date;
                                        ?></th>
                                    <?php foreach ($outlets as $outlet) { ?>
                                        <td class="text-center" style="width='100%'; "><?php echo $outlet->name; ?></td>
                                    <?php } ?>
                                </tr>


                            <?php } ?> 






                    </table>









                <?php } else { ?>
                    <div class="note note-danger">
                        <span class="label label-danger">NOTE!</span>
                        <span class="bold">No data .</span> </div>
                    <?php } ?>

                <?php
                include ('footer.php');
                ?>
