<?php
include ('header.php');
?>


<div class="row">
    <div class="col-md-12">

        <table class="table table-striped table-bordered table-hover " width="100%" >
            <thead>
                <tr>


                    <th>Date</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Zone</th>
                    <th>State</th>
                    <th>Field Officer</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($outlets as $outlet): ?>
                    <tr>
                        <td><?php echo substr($outlet->updated, 0, -8); ?></td>
                        <td><?php echo 'HCM' . str_pad($outlet->id, 3, '0', STR_PAD_LEFT); ?></td>
                        <!--<td><?php echo $outlet->name . ' -**' . $outlet->channel_id; ?></td>-->
                        <td><?php echo $outlet->name?></td>


                        <td><?php echo $outlet->zone; ?></td>
                        <td><?php echo $outlet->state; ?></td>
                        <td><?php echo $this->auth->get_admin_name($outlet->admin_id); ?></td>



                        <td>
                            <?php if ($outlet->active == 1) { ?>
                                <span class="badge badge-success">  <i class="fa fa-check"> </i> </span>            
                            <?php } else { ?>
                                <span class="badge badge-danger">  <i class="fa fa-remove"> </i> </span>            
                            <?php }
                            ?>
                        </td>



                        <td>

                            <?php if ($this->auth->check_access('Admin')) { ?>
                                <div class="btn-group btn-group-sm btn-group-solid">
                                    <a class="btn btn-sm blue filter-submit margin-bottom"  target="_blank" href="<?php echo site_url('products/ha_products/' . $outlet->id); ?>" data-toggle="tooltip" data-placement="top" title="HA Products"><i class="icon-map"></i></a>

                                </div>
                            <?php } ?>		 

                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

    </div>
</div>


<?php
include ('footer.php');
?>