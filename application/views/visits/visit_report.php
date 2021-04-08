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

<!--BCM-->
<div class="portlet light ">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-cogs"></i><?php echo $sub_title; ?>
        </div>

    </div>
    <div class="portlet-body">
        <div class="tabbable tabbable-tabdrop">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab1" data-toggle="tab">Model Data</a>
                </li>
                <li>
                    <a href="#tab2" data-toggle="tab">Pictures</a>
                </li>

            </ul>
        </div>


        <div class="tab-content">

            <div class="tab-pane active" id="tab1">
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                        <tr>
                            <th><i class="fa fa-bars"> Brand</th>
                            <th><i class="fa fa-bars"> Product</th>

                            <?php if ($monthly == 0) { ?>
                                <th><i class="fa fa-bars"> Av</th>
                            <?php } ?>

                            <?php if ($monthly == 1 || $monthly == 3) { ?>
                                <th > Shelf</th>
                                <th > NY</th>
                                <th > Price</th>
                                <th > Promo Price</th>

                            <?php } ?>

                            <?php if ($monthly == 2 || $monthly == 3) { ?>                                                                               
                                                                                                                                                                                               <!--<th > Price</th>-->
                            <?php } ?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($models as $model): ?>
                            <tr>
                                <td><?php echo $model->brand_name; ?></td>
                                <td><?php echo $model->product_name; ?></td>
                                <?php if ($monthly == 0) { ?>
                                    <td>
                                        <?php
                                        if ($model->brand_id == 1) {
                                            if ($model->av == 1) {
                                                echo '-';
                                            } else if ($model->av == 0) {
                                                echo '<FONT color="red"><b>OOS</b></FONT>';
                                            } else {
                                                echo '<FONT color="black"><b>HA</b></FONT>';
                                            }
                                        } else {
                                            if ($model->sku_display == 0) {
                                                echo '<FONT color="red"><b>OOS</b></FONT>';
                                            } else {
                                                echo '-';
                                            }
                                        }
                                        ?>
                                    </td>
                                <?php } ?>
                                <?php if ($monthly == 1 || $monthly == 3) { ?>
                                    <td>
                                        <?php echo $model->shelf; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if($model->ny=='-10'){
                                            echo '0';
                                        }else{
                                             echo $model->ny;
                                        }
                                       
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($model->price != 0)
                                            echo number_format($model->price, 3, '.', '');
                                        else
                                            echo '-';
                                        ?>   
                                    </td>

                                    <td>
                                        <?php
                                        if ($model->promo_price != 0)
                                            echo number_format($model->price, 3, '.', '');
                                        else
                                            echo '-';
                                        ?>
                                    </td>
                                <?php } ?>

                                <?php if ($monthly == 2 || $monthly == 3) { ?>

                                <?php } ?>	

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>											
            </div>

            <div class="tab-pane" id="tab2">
                <div class="profile">
                    <div class="tabbable-line tabbable-full-width">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1_1" data-toggle="tab"> Branding </a>
                            </li>
                            <li>
                                <a href="#tab_1_3" data-toggle="tab"> One picture </a>
                            </li>
                            <li>
                                <a href="#tab_1_2" data-toggle="tab"> Pointing picture </a>
                            </li>
                            <li>
                                <a href="#tab_1_4" data-toggle="tab"> Order picture </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!--tab_1_1-->
                            <div class="tab-pane active" id="tab_1_1">
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
                                            <?php } ?>
                                            <?php if (($pictures->branding_pictures != '') && ($pictures->branding_pictures != '[]')) { ?>  

                                                <?php
                                                $branding_pictures = json_decode($pictures->branding_pictures, TRUE);
                                                $brandings = $pictures->branding_pictures;
                                                $brandings = json_decode($brandings);
                                                ?>

                                                <?php
                                                $id_rayon == '';
                                                echo form_open_multipart('visits/upload_change/' . $id . '/' . $old . '/' . $id_rayon);
                                                ?>
                                                <!--                                                <div class="portlet light ">
                                                                                                    <div class="portlet-title tabbable-line">
                                                                                                        <div class="caption">
                                                                                                            <i class="icon-settings font-red"></i>
                                                                                                            <span class="caption-subject font-red bold uppercase">Before after visits</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <input type="hidden" name="old" id="old"   >
                                                                                                    <input type="hidden" name="id_rayon" id="id_rayon"   >-->


                                                <div class="mt-element-list">

                                                    <div class="mt-list-container list-simple">
                                                        <ul>
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
                                                                            <img  class="zoom" src="<?php echo base_url('uploads/branding/' . $brandings[$i][0]); ?> " id='pic<?php echo $brandings[$i][0]; ?>' alt="admin-pic" download width="400px" height="400px" >
        <?php if ($this->auth->check_access('Admin')) { ?>
                                                                                <input type="file" style="width:200px" id="file<?php echo $i; ?>" name="file<?php echo $i; ?>" >
                                                                                <div class="form-actions">
                                                                                    <input class="btn btn-danger" type="submit" value="<?php echo ('Update'); ?>"/>
                                                                                </div>
        <?php } ?>
                                                                        </td>
                                                                        <td align="center">	 
                                                                            <img class="zoom" src="<?php echo base_url('uploads/branding/' . $brandings[$i][1]); ?> " id='pic<?php echo $brandings[$i][1]; ?>' alt="admin-pic" download width="400px" height="400px"  >
        <?php if ($this->auth->check_access('Admin')) { ?>
                                                                                <input  type="file" style="width:200px" id="file2<?php echo $i; ?>" name="file2<?php echo $i; ?>" >

                                                                                <div class="form-actions">
                                                                                    <input class="btn btn-danger" type="submit" value="<?php echo ('Update'); ?>"/>
                                                                                </div>
        <?php } ?>
                                                                        </td>
                                                                    </tr>
    <?php } ?>
                                                            </table>	
                                                        </ul>
                                                    </div>
                                                </div>
                                                <!-- end mt-element-list -->
                                                <!--                                                </div>-->
                                                </form>

<?php } ?>    
                                        </div> 
                                    </div>
                                </div>

                                <!--tab_1_3-->
                                <div class="tab-pane" id="tab_1_3">
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
<?php } ?>

                                                <?php if (($pictures->one_pictures != '') && ($pictures->one_pictures != '[]')) { ?>  

                                                    <div id="myCarousel3" class="carousel slide" data-ride="carousel">
    <?php
    $one_pictures = json_decode($pictures->one_pictures, TRUE);
    ?>

                                                        <ol class="carousel-indicators">
                                                            <li data-target="#myCarousel3" data-slide-to="0" class="active"></li>

    <?php for ($i = 1; $i < sizeof($one_pictures); $i++) { ?>

                                                                <li data-target="#myCarousel3" data-slide-to="$i"></li>
    <?php } ?>
                                                        </ol>

                                                        <!-- Wrapper for slides -->
                                                        <div class="carousel-inner" role="listbox">
                                                            <div class="item active">
                                                                <center>
                                                                    <img class="zoom" src="<?php echo base_url('uploads/branding/' . $one_pictures[0]); ?> " alt="admin-pic" download  width="400px" height="400px">
                                                                </center>
                                                            </div>
    <?php for ($i = 1; $i < sizeof($one_pictures); $i++) { ?>
                                                                <div class="item">
                                                                    <center>
                                                                        <img class="zoom" src="<?php echo base_url('uploads/branding/' . $one_pictures[$i]); ?> " alt="admin-pic" download  width="400px" height="400px">
                                                                    </center>	 
                                                                </div>

    <?php } ?>
                                                        </div>

                                                        <!-- Left and right controls -->
                                                        <a class="left carousel-control" href="#myCarousel3" role="button" data-slide="prev">
                                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                        <a class="right carousel-control" href="#myCarousel3" role="button" data-slide="next">
                                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </div>
<?php } else { ?>
                                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+images" alt="" id="def5" width="100%" style="height:350px;">
                                                <?php } ?>

                                            </div>
                                        </div>
                                    </div>     


                                    <!--tab_1_2-->
                                    <div class="tab-pane" id="tab_1_2">
                                        <div class="row">
                                            <div class="col-md-12">
<?php if (($pictures->photos != '') && ($pictures->photos != '[]')) { ?>  
                                                    <center>
                                                        <img class="zoom" src="<?php echo base_url('uploads/pointing/' . $pictures->photos); ?> " alt="admin-pic" download  style="width:50%;height:475px;">
                                                    </center>

<?php } else {
    ?>
                                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+images" alt="" id="def5" width="100%" style="height:350px;">
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end tab-pane-->
                                    <!--tab_1_2-->
                                    <div class="tab-pane" id="tab_1_4">
                                        <div class="row">
<?php if ($pictures->order_num == '') { ?>  

                                                <div class="col-md-12">
<?php } else { ?>
                                                    <div class="col-md-3">  

                                                        <div class="mt-element-list">
                                                            <div class="mt-list-head list-simple ext-1 font-white bg-blue">
                                                                <div class="list-head-title-container">
                                                                    <h3 class="list-title">Order Details </h3>
                                                                </div>
                                                            </div>
                                                            <div class="mt-list-container list-simple ">
                                                                <ul>

                                                                    <li class="mt-list-item">
                                                                        <i class="icon-check"></i>
                                                                        <b>Order Num :</b> <?php echo $pictures->order_num ?>

                                                                    </li>
                                                                    <li class="mt-list-item">
                                                                        <i class="icon-check"></i>
                                                                        <b>Order Amount :</b> <?php echo $pictures->order_amt ?>

                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-9">  
<?php } ?>
                                                    <?php if (($pictures->order_picture != '') && ($pictures->order_picture != '[]')) { ?>  
                                                        <center>
                                                            <img class="zoom" src="<?php echo base_url('uploads/order/' . $pictures->order_picture); ?> " alt="admin-pic" download  style="width:50%;height:475px;">
                                                        </center>

<?php } else {
    ?>
                                                        <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+images" alt="" id="def5" width="100%" style="height:350px;">
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end tab-pane-->
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>			
                </div>
            </div>
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

