<style>
    td{
        text-align: center;
        width: 125px;
    }

    th{

        text-align: center;
        width: 125px;
    }
</style>

<style>


    .zoom:hover {
        transform: scale(1.6); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    }
</style>


<!--gdi-->
<div class="portlet light ">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-cogs"></i><?php echo $sub_title; ?>
        </div>

    </div>
    <div class="portlet-body">






        <!--tab_1_1-->

        <!--end tab-pane-->
        <!--tab_1_2-->

        <div class="row">
            <?php if ($visit->order_num == '') { ?>  

                <div class="col-md-12">
                <?php } else { ?>
                    <div class="col-md-3">  

                        <div class="mt-element-list">
                            <div class="mt-list-head list-simple ext-1 font-white bg-blue">
                                <div class="list-head-title-container">
                                    <h3 class="list-title">Details </h3>
                                </div>
                            </div>
                            <div class="mt-list-container list-simple ">
                                <ul>

                                    <li class="mt-list-item">
                                        <i class="icon-check"></i>
                                        <b>Order Num :</b> <?php echo $visit->order_num ?>

                                    </li>
                                    <li class="mt-list-item">
                                        <i class="icon-check"></i>
                                        <b>Order Amount :</b> <?php echo $visit->order_amt ?>

                                    </li>


                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">  
                    <?php } ?>
                    






                    <?php if (($visit->order_picture != '') && ($visit->order_picture != '[]')) { ?>  

                        <?php
                        //$branding_pictures = json_decode($pictures->order_picture, TRUE);
                        $orders = $visit->order_picture;
                        $orders = json_decode($orders);
                        
                       // print_r($orders);die();
                        ?>

                       


                        <div class="mt-element-list">

                            <div class="mt-list-container list-simple">
                                <ul>
                                    
                                        


                                        <?php for ($i = 0; $i < sizeof($orders); $i++) { ?>
                                            
                                                    <img  class="zoom" src="<?php echo base_url('uploads/order/' . $orders[$i]); ?> " id='pic<?php echo $orders[$i]; ?>' alt="admin-pic" download width="400px" height="400px" >
                                        <?php } ?>
                                   
                                </ul>
                            </div>
                        </div>
                        <!-- end mt-element-list -->
                      

                    <?php } ?>    



                </div>
            </div>
        </div>
        <!--end row-->

    </div>
</div>

<script>
    $("document").ready(function () {
        $("#<?php echo 'file' . ($i); ?>").change(function () {
            document.getElementById('old').value = '<?php echo $brandings[$i][0]; ?>';
            document.getElementById('id_rayon').value = '<?php echo $i; ?>';
        });
        $("#<?php echo 'file2' . ($i); ?>").change(function () {
            document.getElementById('old').value = '<?php echo $brandings[$i][1]; ?>';
            document.getElementById('id_rayon').value = '<?php echo $i; ?>';
        });
    });

</script>	

