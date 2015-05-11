<div class="page-outer">
     <fieldset class="body-border">
     <legend class="body-head">Packages</legend>
     <div class="box-body table-responsive no-padding trips-table"><?php echo form_open( base_url().'front-desk/tour/booking');
		  echo form_submit("add","Manage","class='btn btn-primary'").br(2);
		  echo form_close(); 
								  
						?></div>
						
<div class="box-body table-responsive no-padding trips-table">
	<table class="table table-hover table-bordered" >
	<tbody>
	<tr>
		
		<th style="width:30%">Package Name</th>
		<th style="width:40%">Package Description</th>
		<th style="width:10%">No:of Days</th>
		<th  style="width:10%">Amount</th>
		<th  style="width:10%">Status</th>
        <tr>
	
	<?php if(isset($package_lists)){ 
	foreach ($package_lists as $list){ 
	?>
	<tr class="row_click common" limited="true">
	<td><?php  echo $list['package'];?></td>
	<td><?php if(!empty($list['destination_arry'])){
	
		$numItems = count($list['destination_arry']);
		$i = 0;
		foreach ($list['destination_arry'] as $destination){
			if(++$i === $numItems) {
				echo nbs(1).$destination['name']. nbs(1);
			}else{
				echo nbs(1).$destination['name']. nbs(1).'-'. nbs(1);
			}	
		}
	}
	
	?></td>
	<td><?php echo $list['days'];?></td>
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