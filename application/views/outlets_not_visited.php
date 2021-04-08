<?php
include ('header.php');
 ?>


 
 
   
 

							
<div class="row">
      <div class="col-md-12">

<table class="table table-striped table-bordered table-hover " width="100%" >
	<thead>
		<tr>
			
		
            <th>Outlet</th>
			
		</tr>
	</thead>
	
	<tbody>
		
<?php foreach ($outlets as $outlet):?>
		<tr>
            <td><?php echo $outlet; ?></td>
			
		</tr>
<?php endforeach;?>
		
	</tbody>
</table>

</div>
</div>

 <div class="row">
        <div class="col-md-12 text-center">
            <?php echo $pagination; ?>
        </div>
    </div>
<?php
	include ('footer.php');
 ?>