<?php
include ('header.php');
//bcm
?>



<div class="row">
    <div class="col-md-12">

        <table class="table table-striped table-bordered table-hover " id="table" width="100%" >
            <thead>

                <tr>	
                    <th>ID</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Product.Group</th>
                    <th>Custering</th>
                    <th>Sub.category</th>
                    <th>Category</th>

                    <th class="col-md-2">HA</th>
                </tr>
            </thead>

            <tbody>




                <?php foreach ($products as $product): ?>
                    <tr class="odd gradeX">
                        <td><?php echo $product->id; ?></td>
                        <td><?php echo $product->code; ?></td>
                        <td><?php echo $product->name; ?></td>
                        <td><?php echo $product->brand_name; ?></td>
                        <td><?php echo $product->product_group_name; ?></td>
                        <td><?php echo $product->cluster_name; ?></td>
                        <td><?php echo $product->sub_category_name; ?></td>
                        <td><?php echo $product->category_name; ?></td>

                        <td class="col-md-2">


                            <div  id="status<?php echo $product->id; ?>" style="display: inline">
                                <?php if (!in_array($product->id, $ha_product_ids)) { ?>
                                
                                    <a onclick=" enable('<?php echo $product->id; ?>', '<?php echo $outlet_id; ?>')" class="btn btn-circle red btn-outline"  data-toggle="tooltip" data-placement="top" title="Disable"> <i class="fa fa-thumbs-down"></i> </a>	
                                <?php } else { ?>
                                    <a onclick=" disable('<?php echo $product->id; ?>', '<?php echo $outlet_id; ?>')" class="btn btn-circle green btn-outline"  data-toggle="tooltip" data-placement="top" title="Enable">  <i class="fa fa-thumbs-up"></i> </a>		
                                <?php } ?>
                            </div>


                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    function enable(product_id, outlet_id) {

        $.ajax({
            url: "<?php echo site_url('products/enable/'); ?>",
            type: "POST",
            data: {product_id: product_id, outlet_id: outlet_id},
            success: function (data) {
                console.log(data);
                $('#status' + product_id).html(data);
            }
        });
    }
    function disable(product_id, outlet_id) {

        $.ajax({
            url: "<?php echo site_url('products/disable/'); ?>",
            type: "POST",
            data: {product_id: product_id, outlet_id: outlet_id},
            success: function (data) {
                console.log(data);
                $('#status' + product_id).html(data);
            }
        });
    }
</script>	

<?php
include ('footer.php');
?>