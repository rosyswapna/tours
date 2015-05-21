<div class="trips">
     <fieldset class="body-border">
     <legend class="body-head">Trips</legend>
     
     		<div class="box-body table-responsive no-padding">
			
			<?php echo form_open(base_url()."organization/front-desk/trips"); ?>
			<table class="table list-trip-table no-border">
				<tbody>
					<tr>
						<!---->
					    <td>
						<?php 
						$class = 'pickupdatepicker initialize-date-picker form-control';
						echo form_input(array('name'=>'trip_pick_date','class'=>$class ,'placeholder'=>'Pick up Date','value'=>@$trip_pick_date)); ?></td>
					    <td><?php  
						$class = 'dropdatepicker initialize-date-picker form-control';
						echo form_input(array('name'=>'trip_drop_date','class'=>'dropdatepicker initialize-date-picker form-control' ,'placeholder'=>'Drop Date','value'=>@$trip_drop_date)); ?></td>
						 <td><?php 
						$class="form-control";$id='vehicles';
						echo $this->form_functions->populate_dropdown('vehicles',$vehicles,@$vehicle_id,$class,$id,$msg="Vehicle");?></td>
						 <td><?php 
						$class="form-control";$id='drivers';
						echo $this->form_functions->populate_dropdown('drivers',$drivers,@$driver_id,$class,$id,$msg="Driver");?></td>
						<td><?php 
						$class="form-control"; $id='trip-status';
						echo $this->form_functions->populate_dropdown('trip_status_id',$trip_statuses,@$trip_status_id,$class,$id,$msg="Trip Status");?></td>
					     <td><?php $class="form-control"; $id='cgroups';
						echo $this->form_functions->populate_dropdown('cgroups',$customer_groups,@$customer_group_id,$class,$id,$msg="Company");?></td>
						<td><?php 
						$class =  'customer form-control';
						echo form_input(array('name'=>'customer','class'=>$class ,'placeholder'=>'Name','value'=>@$customer_name,'id'=>'c_name')); ?></td>
						<td><?php echo form_submit("trip_search","Search","class='btn btn-primary'");
echo form_close();?></td>
					<td><?php
						if((!$this->session->userdata('driver'))&&(!$this->session->userdata('customer'))){					
						echo form_button('print-trip','Print',"class='btn btn-primary print-trip'");
						} ?></td>
						
					</tr>
				</tbody>
			</table>
		</div>
     
     
     
     
<div class="box-body table-responsive no-padding trips-table">
	<table class="table table-hover table-bordered">
	<tbody>
	<tr>
		<th style="width:2%">Trip ID </th>
		<th style="width:4%">Booking Date</th>
		<th style="width:4%">Pickup Date</th>
		<th style="width:4%">Pickup Time</th>
		<th  style="width:11%">Pickup Location</th>
		<th  style="width:11%">Guest</th>
		<th style="width:11%">Called By</th>						
		<th style="width:11%">Transport</th>
		<th style="width:11%">Status</th>
		<th style="width:13%">Action</th>
        <tr>
	
	<?php if(isset($trips)){
			foreach($trips as $trip){?>
			<tr>
				<td><?php echo $trip['id']; ?> </td>
				<td><?php echo $trip['booking_date']; ?> </td>
				<td><?php echo $trip['pick_up_date']; ?> </td>
				<td> <?php echo $trip['pick_up_time']; ?></td>
				<td> <?php echo $trip['pick_up_location']; ?></td>
				<td> <?php echo $trip['guest_name'].br().$trip['guest_mobile']; ?></td>
				<td> <?php echo $trip['customer_name'].br().$trip['customer_mobile']; ?></td>
				<td> </td>
				<td><span class="label <?php echo $status_class[$trip['trip_status_id']]; ?>"><?php echo $trip_statuses[$trip['trip_status_id']];?></span>  </td>
				<td>
				<?php 	if($trip['trip_status_id']==TRIP_STATUS_CONFIRMED || $trip['trip_status_id']==TRIP_STATUS_PENDING ) { 
						echo anchor_popup_default(base_url().'front-desk/tour/booking/'.$trip['id'],'<span></span>',array('class'=>' fa fa-edit ','title'=>'Edit')).nbs(5);
					}
					if($trip['trip_status_id']==TRIP_STATUS_CONFIRMED  ) { 
						echo anchor_popup_default(base_url().'front-desk/tour/booking/'.$trip['id'],'<span></span>',array('class'=>' fa fa-caret-square-o-right ','title'=>'Complete')).nbs(5);
					}
				 ?>
				</td>
				
			</tr>
			<?php }
	}?>
	
	
	
	</tbody>
        </table>	
</div>
	</fieldset>
</div>