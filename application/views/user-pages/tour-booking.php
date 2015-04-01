<div class="tour-booking-outer">
<?php if($this->session->userdata('dbSuccess') != '') { ?>
		<div class="success-message">
			<div class="alert alert-success alert-dismissable">
			<i class="fa fa-check"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?php 
			echo $this->session->userdata('dbSuccess');
			$this->session->set_userdata('dbSuccess','');
			?>
		   	</div>
		</div>
		<?php  } ?>
<?php  
echo form_open(base_url()."tour/manage_tour_booking");
echo form_hidden('trip_id',@$header['trip_id']);
?>
<fieldset class="body-border">
<legend class="body-head">Booking Source</legend>
<table class="tour-booking-tbl">
<tr>
<td><?php echo form_label('Source','source');?></td>
<td><?php $class="form-control";
	  $msg="Select";
	  $name="source_id";
	  echo $this->form_functions->populate_dropdown($name,$booking_sources,@$source_id,$class,$id='source_id',$msg);?></td>
<td><?php echo form_label('Contact','contact');?></td>
<td><?php echo form_input(array('name'=>'source_contact','class'=>'form-control','id'=>'source_contact','value'=>@$source_contact));?>
<?php echo  $this->form_functions->form_error_session('source_contact','<p class="text-red">', '</p>');?>
</td>
<td><?php echo form_label('Details','details');?></td>
<td><?php echo form_input(array('name'=>'source_details','class'=>'form-control','id'=>'source_details','value'=>@$source_details));?></td>
<td><?php echo form_label('Package','package');?></td>
<td><?php $class="form-control";
	  $msg="Select";
	  $name="package_id";
	  echo $this->form_functions->populate_dropdown($name,$packages='',@$package_id,$class,$id='package_id',$msg);?></td>
</tr>
</table>
</fieldset>

<fieldset class="body-border">
<legend class="body-head">Booking Information</legend>
<table class="tour-booking-tbl">
<tr>
<td><?php echo form_label('Customer');?></td>
<td><?php echo form_input(array('name'=>'customer','class'=>'form-control mandatory','id'=>'customer','value'=>@$customer));
echo  $this->form_functions->form_error_session('customer','<p class="text-red">', '</p>');?></td>
<td><?php echo form_label('From Date');?></td>
<td><?php echo form_input(array('name'=>'pick_up_date','class'=>'form-control  mandatory','id'=>'tour-pickupdatepicker','value'=>@$header['pick_up_date']));?>
<?php echo  $this->form_functions->form_error_session('pick_up_date','<p class="text-red">', '</p>');?></td>

<td><?php echo form_label('Vehicle AC Type');?></td>
<td><?php $class="form-control";
	  $msg="Select";
	  $name="vehicle_ac_type_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicle_ac_types,@$vehicle_ac_type_id,$class,$id='vehicle_ac_type_id',$msg);?>
	  <span class="text-red"><?php
		if($this->mysession->get('Err_V_Ac') != ''){
			echo $this->mysession->get('Err_V_Ac');
			$this->mysession->delete('Err_V_Ac');
		} 
		?></span></td>
<td><?php echo form_label('Pickup');?></td>
<td><?php echo form_input(array('name'=>'pick_up','class'=>'form-control','id'=>'pick_up','placeholder'=>'','value'=>@$pick_up_location)).form_input(array('name'=>'pick_up_lat','class'=>'form-control hide-me','id'=>'pick_up_lat','placeholder'=>'','value'=>@$pick_up_lat)).form_input(array('name'=>'pick_up_lng','class'=>'form-control hide-me','id'=>'pick_up_lng','placeholder'=>'','value'=>@$pick_up_lng));?></td>
</tr>

<tr>
<td><?php echo form_label('Contact');?></td>
<td><?php echo form_input(array('name'=>'customer_contact','class'=>'form-control','id'=>'customer_contact','value'=>@$customer_contact));?>
<?php echo  $this->form_functions->form_error_session('customer_contact','<p class="text-red">', '</p>');?>
<div><?php $new_customer='true'; echo form_input(array('name'=>'newcustomer','class'=>'form-control newcustomer hide-me','value'=>$new_customer)).form_input(array('name'=>'customer_id','id'=>'customer_id','class'=>'form-control hide-me','value'=>'')); ?></div></td>
<td><?php echo form_label('Time');?></td>
<td><?php echo form_input(array('name'=>'pick_up_time','class'=>'form-control ','id'=>'tour-pickuptimepicker','value'=>@$pick_up_time));?></td>
<td><?php echo form_label('Vehicle Model');?></td>
<td><?php $class="form-control";
	  $msg="Select";
	  $name="vehicle_model_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicle_models,@$vehicle_model_id,$class,$id='vehicle_model_id',$msg);?>
	  <span class="text-red"><?php
		if($this->mysession->get('Err_Vmodel') != ''){
			echo $this->mysession->get('Err_Vmodel');
			$this->mysession->delete('Err_Vmodel');
		 }
	 ?></span>
</td>
<td><?php echo form_label('Drop');?></td>
<td><?php echo form_input(array('name'=>'drop','class'=>'form-control','id'=>'drop','placeholder'=>'','value'=>@$drop_location)).form_input(array('name'=>'drop_lat','class'=>'form-control hide-me','id'=>'drop_lat','placeholder'=>'','value'=>@$drop_lat)).form_input(array('name'=>'drop_lng','class'=>'form-control hide-me','id'=>'drop_lng','placeholder'=>'','value'=>@$drop_lng));?></td>
</tr>

<tr>
<td><?php echo form_label('Guest');?></td>
<td><?php echo form_input(array('name'=>'guest_name','class'=>'form-control','id'=>'guest_name','value'=>@$guest_name));?></td>
<td><?php echo form_label('To Date');?></td>
<td><?php echo form_input(array('name'=>'drop_date','class'=>'form-control  mandatory','id'=>'tour-dropdatepicker','value'=>@$header['drop_date']));?>
<?php echo  $this->form_functions->form_error_session('drop_date','<p class="text-red">', '</p>');?></td>
<td><?php echo form_label('Vehicle No.');?></td>
<td><?php $class="form-control vehicle-list";
	  $id="vehicle_id";
	  $msg=' ';
echo $this->form_functions->populate_editable_dropdown('vehicle_id', @$available_vehicles,$class,'vehicles',array(),$msg,@$vehicle_id,$id);?><span class="text-red"><?php
						if($this->mysession->get('Err_reg_num') != ''){
							echo $this->mysession->get('Err_reg_num');
							$this->mysession->delete('Err_reg_num');
						} 
						
					?></span></td>
<td><?php echo form_label('PAX');?></td>
<td><?php echo form_input(array('name'=>'pax','class'=>'form-control','id'=>'pax','value'=>@$pax));?></td>
</tr>

<tr>
<td><?php echo form_label('Contact');?></td>
<td><?php echo form_input(array('name'=>'guest_contact','class'=>'form-control','id'=>'guest_contact','value'=>@$guest_contact)); echo  $this->form_functions->form_error_session('guest_contact','<p class="text-red">', '</p>');?>
<div><?php $new_guest='true'; echo form_input(array('name'=>'newguest','class'=>'form-control newguest hide-me','value'=>$new_guest)).form_input(array('name'=>'guest_id','id'=>'guest_id','class'=>'form-control hide-me','value'=>'')); ?></div></td>
<td><?php echo form_label('Time');?></td>
<td><?php echo form_input(array('name'=>'drop_time','class'=>'form-control ','id'=>'tour-droptimepicker','value'=>@$drop_time));?></td>
<td><?php echo form_label('Driver');?></td>
<td><?php $class="form-control";
	  $msg=' ';

	  echo $this->form_functions->populate_editable_dropdown('driver_id', @$driver_availability,$class,'drivers',array(),$msg,@$driver_id);?></td>
<td><?php echo " ";?></td>
<td><div class="tour-advanced-container"><?php echo form_checkbox(array('name'=>'advanced-option','id'=>'advanced-check-box','class'=>'advanced-check-box flat-red','checked'=>@$advanced_option)).nbs(4);?>
<?php echo form_label('Advanced');?></div></td>
</tr>
<tbody style="display:none;" class="tbody-toggle">
<tr>
<td><?php echo form_checkbox(array('name'=> 'vehicle_beacon_light_option_id','class'=>'beacon-light-chek-box flat-red','checked'=>@$vehicle_beacon_light_option_id));
	  ?></td>
<td><?php echo form_label('Red Beacon Light');?></td>
<td><?php echo form_checkbox(array('name'=> 'pluckcard','class'=>'pluckcard-chek-box flat-red','checked'=>@$pluckcard));?></td>
<td><?php echo form_label('Placard');?></td>
<td><?php echo form_checkbox(array('name'=> 'uniform','class'=>'uniform-chek-box flat-red','checked'=>@$uniform));?></td>
<td><?php echo form_label('Uniform');?></td>
<td><?php echo form_label('Languages');?></td>
<td><?php $class="form-control";
	  $msg="Select";
	  $name="driver_language_id";
echo $this->form_functions->populate_dropdown($name,@$languages,@$driver_language_id,$class,$id='',$msg);?>
</td>
</tr>
</tbody>
<table><tr><td><?php $save_update_button='SAVE';$class_save_update_button="class='btn btn-success trip-save-update'";
echo form_submit("trip-add",$save_update_button,$class_save_update_button).nbs(7);?></td></tr></table>
</table>

</fieldset>
<?php echo form_close(); ?>

<!--itinerary table starts here-->
<?php if($itineraries){?>
<div class="box-body table-responsive no-padding">
<table id="itinerary-tbl" class="table table-hover table-bordered table-with-20-percent-td">
	<tr>
	<?php foreach($itineraries['th'] as $th){?>
	<th <?php echo $th['attr'];?>><?php echo $th['label'];?></th>
	<?php }?>
	</tr>

	<?php foreach($itineraries['tr'] as $tr){?>
	<tr>
		<?php foreach($tr as $td){?>
		<td><?php echo $td;?></td>
		<?php }?>
	</tr>
	<?php }?>
	
</table>
</div>

<?php }?>
<!--itinerary table ends here-->

<!--itinerary tabs starts-->

<div class="nav-tabs-custom itinerary">
	<ul class="nav nav-tabs">
	<?php 
		foreach($tabs as $tab=>$attr){
			echo '<li class="'.$attr['class'].'">
				<a href="#'.$attr['tab_id'].'" data-toggle="tab">'.$attr['text'].'</a>
			      </li>';
		}
	?>
	</ul>
	<div class="tab-content">
		<?php if (array_key_exists('t_tab', $tabs)) {?>
		<div class="<?php echo $tabs['t_tab']['content_class'];?>" id="<?php echo $tabs['t_tab']['tab_id'];?>">

			<div class="page-outer">
	   		<fieldset class="body-border">
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Date','travel_date'); 
				    	echo form_input(array('name'=>'travel_date','class'=>'form-control initialize-date-picker  ','id'=>'tour-travel_date','value'=>@$travel_date));
					
					echo $this->form_functions->form_error_session('travel_date', '<p class="text-red">', '</p>'); ?>			</div>
				</div>
				<div class="row-source-100-percent-width-with-margin-8">

					<div id="div-via-destination">
						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php echo form_label('Destination','destination_id'); 
					    	
						$class="form-control";
						$msg="Select Destination";
						$name = $id = "destination_id";
						echo $this->form_functions->populate_dropdown($name,$destinations,@$destination_id,$class,$id,$msg); 
						echo $this->form_functions->form_error_session('travel_date', '<p class="text-red">', '</p>'); ?>			</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php echo form_label('Priority','destination_priority'); 
					    	echo form_input(array('name'=>'destination_priority','class'=>'form-control  ','id'=>'destination_priority','value'=>@$travel_date));
					
						echo $this->form_functions->form_error_session('priority', '<p class="text-red">', '</p>'); ?>			</div>
					</div>
					<div  class="form-group div-with-20-percent-width-with-margin-10" >
						<?php echo form_label(''); ?><br/>
						<?php echo form_submit("via-add","ADD VIA","class='btn btn-primary' id='add-travel'");?>
					</div>
				</div>
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-46-percent-width-with-margin-10">
						<?php echo form_label('Particulars','particulars');
					    	$input = array('name'=>'particulars','class'=>'form-control',
								'value'=>@$particulars,'rows'=>4,'id'=>'particulars');
						echo form_textarea($input); 
						echo form_error('particulars', '<p class="text-red">', '</p>'); ?>
					</div>
				</div>
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="box-footer ">
					<?php echo form_submit("add-travel","Add","class='btn btn-primary' id='add-travel'");?>
					</div>
				</div>
			
			</fieldset>
			</div>
		</div>
		<?php }?>
	</div>
</div>


<!--itinerary tabs ends here-->


</div>
