<div class="trips">
     <fieldset class="body-border">
     <legend class="body-head">Packages</legend>
<div class="box-body table-responsive no-padding trips-table">
	<table class="table table-hover table-bordered" style="width:88%">
	<tbody>
	<tr>
		
		<th style="width:9%">Package Name</th>
		<th style="width:17%">Package Description</th>
		<th style="width:4%">No:of Days</th>
		<th  style="width:6%">Amount</th>
		<th  style="width:6%">Status</th>
        <tr>
	
	<?php if(isset($package_lists)){ 
	foreach ($package_lists as $list){ 
	?>
	<tr class="row_click common" limited="true">
	<td><?php echo anchor(base_url().'front-desk/tour/booking/'.$list['id'],$list['package']).br();?></td>
	<td><?php echo '';?></td>
	<td><?php echo '';?></td>
	<td><?php echo ' ';?></td>
	<td><?php if( $list['status_id']== STATUS_ACTIVE)
			echo '<span class="label label-success">'.$list['name'].'</span>';
		  else
			echo '<span class="label label-danger">'.$list['name'].'</span>';
	?></td>
	</tr>
	<?php
	}
	}else{
	echo "<span style='color:red;'>No Results Found</span>";
	} ?>
	
	
	
	</tbody>
        </table>	
</div>
	</fieldset>
</div>