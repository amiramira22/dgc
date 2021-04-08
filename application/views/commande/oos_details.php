<style>
    td{
        text-align: center;
        width: 125px;
    }

    th{

        text-align: center;
        width: 125px;
    }

    .img-responsive {
        display: block;
        max-width: 100%;
        height: 400px;
    }
</style>


<!--BCM-->
<div class="portlet light ">
    <div class="portlet-title">
        <div class="caption">
            <?php echo $sub_title; ?>
        </div>

    </div>
    <div class="portlet-body">
        <div class="tabbable tabbable-tabdrop">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab1" data-toggle="tab">OOS Products</a>
                </li>
                <li>
                    <a href="#tab2" data-toggle="tab">Commande</a>
                </li>
                <li>
                    <a href="#tab3" data-toggle="tab">Branding</a>
                </li>

            </ul>
        </div>


        <div class="tab-content">

            <div class="tab-pane active" id="tab1">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                        <tr>
                            <th><i class="fa fa-bars"> OOS Products </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($models as $model): ?>
                            <tr>
                                <td><?php echo $model->product_name; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>											
            </div> <!-- end tab 1 -->


            <div class="tab-pane" id="tab2">
                <div class="row">
                    <div class="col-md-12">
                        <?php if (($pictures->order_picture != '') && ($pictures->order_picture != '[]')) { ?>  
                            <center>
                                <?php if ($pictures->order_num != '') { ?>
                                    <h4><b>Remark : </b><?php echo $pictures->order_num ?></h4>
                                <?php } ?>
                                <img class="img-responsive" src="<?php echo base_url('uploads/order/' . $pictures->order_picture); ?> " alt="admin-pic" download >
                            </center>

                        <?php } else {
                            ?>
                            <img class="img-responsive" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+images" alt="" id="def5" >
                        <?php } ?>
                    </div>
                </div>
            </div> <!-- end tab 2 -->


            <div class="tab-pane" id="tab3">
                <div class="row">
                    <?php if ($pictures->remark == '') { ?>  

                        <div class="col-md-12">
                        <?php } else { ?>
                            <div class="col-md-3">  

                                <div class="mt-element-list">
                                    <div class="mt-list-head list-simple ext-1 font-white bg-blue">
                                        <div class="list-head-title-container">
                                            <h3 class="list-title">Reamrks </h3>
                                        </div>
                                    </div>
                                    <div class="mt-list-container list-simple ">
                                        <ul>

                                            <li class="mt-list-item">
                                                <i class="icon-check"></i>
                                                <?php echo $pictures->remark ?>

                                            </li>


                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-9">  
                                <?php
                            }
                            if (($pictures->branding_pictures != '') && ($pictures->branding_pictures != '[]')) {

                                $branding_pictures = json_decode($pictures->branding_pictures, TRUE);
                                $brandings = $pictures->branding_pictures;
                                $brandings = json_decode($brandings);
                                ?>
                                <div class="mt-element-list">

                                    <div class="mt-list-container list-simple">
                                        <ul>
                                            <div class="table-responsive">
                                                <table class="table table-striped  table-hover "  border="1" width="100%">
                                                    <tr>
                                                        <th>  
                                                            <div class="mt-list-head list-simple font-white bg-red">
                                                                <div class="list-head-title-container">

                                                                    <h3 class="list-title">Before </h3>


                                                                </div>
                                                            </div> 
                                                        </th>
                                                        <th>  
                                                            <div class="mt-list-head list-simple font-white bg-red">
                                                                <div class="list-head-title-container">

                                                                    <h3 class="list-title">After </h3>


                                                                </div>
                                                            </div> 
                                                        </th>
                                                    </tr>
                                                    <?php for ($i = 0; $i < sizeof($brandings); $i++) { ?>
                                                        <tr>
                                                            <td align="center">
                                                                <img  class="img-responsive" src="<?php echo base_url('uploads/branding/' . $brandings[$i][0]); ?> " id='pic<?php echo $brandings[$i][0]; ?>' alt="admin-pic" download  >

                                                            </td>
                                                            <td align="center">	 
                                                                <img class="img-responsive" src="<?php echo base_url('uploads/branding/' . $brandings[$i][1]); ?> " id='pic<?php echo $brandings[$i][1]; ?>' alt="admin-pic" download >

                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                                <!-- end mt-element-list -->


                            <?php } else {
                                ?>
                                <img class="img-responsive" src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+images" alt="" id="def5" width="100%" >
                            <?php } ?>
                        </div> 
                    </div>
                </div>
            </div> <!-- end tab 3 -->



        </div>
    </div>
</div>

