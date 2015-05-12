<div class="page-outer">
     <fieldset class="body-border">
     <legend class="body-head">Packages</legend>
     <div class="box-body table-responsive no-padding">		
	<?php echo form_open(base_url()."front-desk/tour/packages"); ?>
	<table class="table list-trip-table no-border">
	<tbody>
		<tr>
			
		    	<td>
			<?php echo form_input(array('name'=>'package_name','id'=>'package_name','class'=>'form-control' ,'placeholder'=>'Package Name','value'=>@$package_name)); ?>
			</td>
		
			<td>
			<?php $class="form-control";
				  	$id='c_type';
					echo $this->form_functions->populate_dropdown('status_id',$statuses,@$status_id,$class,$id,$msg="Status");?>
			</td>

		    	<td>
			<?php echo form_submit("package_search","Search","class='btn btn-primary'");
echo form_close();?>	</td>

			<td>
			<?php echo form_open(  base_url().'front-desk/tour/booking');
				echo form_submit("add","Add","class='btn btn-primary'");
				echo form_close(); 
			?>
			</td>
			
			<td>
			<?php echo form_button('print-packages','Print',"class='btn btn-primary print-packages'"); ?>
			</td>
			
		</tr>
	</tbody>
</table>
</div>
						
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
	
	<?php if(!empty($package_lists)){ 
	foreach ($package_lists as $list){ 
	?>
	<tr class="row_click common" limited="true">
	<td>
	<?php
		$data = array(
			    'name' => 'button',
			    'id' => 'package-edit',
			    'value' => 'true',
			    'class' => 'btn-edit packages-edit',
			    'content' => $list['package'],
			    'edit-id'=> $list['id']
			);

		echo form_button($data);
  
		//echo $list['package'];
	?>
	</td>
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
