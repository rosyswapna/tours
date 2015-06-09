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
			<?php 
			$page_flag='PA';
			echo form_open(  base_url().'front-desk/tour/booking/'.$page_flag);
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
		
		<th style="width:10%">No:of Days</th>
		<th  style="width:10%">Amount</th>
		<th  style="width:10%">Status</th>
        <tr>
	
	<?php if(!empty($packages)){ 
	foreach ($packages as $package){ 
	?>
	<tr class="row_click common" limited="true">

		<td>
		<?php
			$data = array(
				    'name' => 'button',
				    'id' => 'package-edit',
				    'value' => 'true',
				    'class' => 'btn-edit packages-edit',
				    'content' => $package['package'],
				    'edit-id'=> $package['id']
				);

			echo form_button($data);
		?>
		</td>

		

		<td><?php echo $package['days'];?></td>

		<td><?php echo ' ';?></td>

		<td>
			<?php if( $package['status_id']== STATUS_ACTIVE)
				echo '<span class="label label-success">'.$package['name'].'</span>';
			  else
				echo '<span class="label label-danger">'.$package['name'].'</span>';
			?>
		</td>

	</tr>
	<?php
	}
	}else{
		echo "<span style='color:red;'>No Results Found</span>";
	} ?>
	
	
	
	</tbody>
        </table><?php echo $page_links;?>	
</div>
	</fieldset>
</div>
