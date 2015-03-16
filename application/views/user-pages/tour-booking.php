<div class="tour-booking-outer">
<?php echo form_open(base_url()."tour/manage_tour_booking"); ?>
<fieldset class="body-border">
<legend class="body-head">Booking Source</legend>
<table class="tour-booking-tbl">
<tr>
<td><?php echo form_label('Source','source');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
	  $name="source_id";
	  echo $this->form_functions->populate_dropdown($name,$booking_sources,@$source_id,$class,$id='source_id',$msg);?></td>
<td><?php echo form_label('Contact','contact');?></td>
<td><?php echo form_input(array('name'=>'source_contact','class'=>'form-control','id'=>'source_contact','value'=>@$source_contact));?></td>
<td><?php echo form_label('Details','details');?></td>
<td><?php echo form_input(array('name'=>'source_details','class'=>'form-control','id'=>'source_details','value'=>@$source_details));?></td>
<td><?php echo form_label('Package','package');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
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
<td><?php echo form_input(array('name'=>'customer','class'=>'form-control','id'=>'customer','value'=>@$customer));?></td>
<td><?php echo form_label('From Date');?></td>
<td><?php echo form_input(array('name'=>'pickup_date','class'=>'form-control width-60-percent-with-margin-10','id'=>'tour-pickupdatepicker','value'=>@$pickup_date));?></td>
<td><?php echo form_label('Pickup');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
	  $name="trip_from_destination_id";
	  echo $this->form_functions->populate_dropdown($name,$destinations='',@$trip_from_destination_id,$class,$id='trip_from_destination_id',$msg);?></td>
<td><?php echo form_label('Vehicle AC Type');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
	  $name="vehicle_ac_type_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicle_ac_types,@$vehicle_ac_type_id,$class,$id='vehicle_ac_type_id',$msg);?></td>
</tr>

<tr>
<td><?php echo form_label('Contact');?></td>
<td><?php echo form_input(array('name'=>'customer_contact','class'=>'form-control','id'=>'customer_contact','value'=>@$customer_contact));?>
<div><?php $new_customer='true'; echo form_input(array('name'=>'newcustomer','class'=>'form-control newcustomer hide-me','value'=>$new_customer)).form_input(array('name'=>'customer_id','id'=>'customer_id','class'=>'form-control hide-me','value'=>'')); ?></div></td>
<td><?php echo form_label('Time');?></td>
<td><?php echo form_input(array('name'=>'pickup_time','class'=>'form-control width-60-percent-with-margin-10','id'=>'tour-pickuptimepicker','value'=>@$pickup_time));?></td>
<td><?php echo form_label('Drop');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
	  $name="trip_to_destination_id";
	  echo $this->form_functions->populate_dropdown($name,$destinations='',@$trip_to_destination_id,$class,$id='trip_to_destination_id',$msg);?></td>
<td><?php echo form_label('Vehicle Model');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
	  $name="vehicle_model_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicle_models,@$vehicle_model_id,$class,$id='vehicle_model_id',$msg);?></td>
</tr>

<tr>
<td><?php echo form_label('Guest');?></td>
<td><?php echo form_input(array('name'=>'guest_name','class'=>'form-control','id'=>'guest_name','value'=>@$guest_name));?></td>
<td><?php echo form_label('To Date');?></td>
<td><?php echo form_input(array('name'=>'drop_date','class'=>'form-control width-60-percent-with-margin-10','id'=>'tour-dropdatepicker','value'=>@$drop_date));?></td>
<td><?php echo form_label('PAX');?></td>
<td><?php echo form_input(array('name'=>'pax','class'=>'form-control','id'=>'pax','value'=>@$pax));?></td>
<td><?php echo form_label('Vehicle No.');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
	  $name="vehicle_type_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicles='',@$vehicle_id,$class,$id='vehicle_type_id',$msg);?></td>
</tr>

<tr>
<td><?php echo form_label('Contact');?></td>
<td><?php echo form_input(array('name'=>'guest_contact','class'=>'form-control','id'=>'guest_contact','value'=>@$guest_contact));?>
<div><?php $new_guest='true'; echo form_input(array('name'=>'newguest','class'=>'form-control newguest hide-me','value'=>$new_guest)).form_input(array('name'=>'guest_id','id'=>'guest_id','class'=>'form-control hide-me','value'=>'')); ?></div></td>
<td><?php echo form_label('Time');?></td>
<td><?php echo form_input(array('name'=>'drop_time','class'=>'form-control width-60-percent-with-margin-10','id'=>'tour-droptimepicker','value'=>@$drop_time));?></td>
<td><?php echo form_label('  ');?></td>
<td><?php echo form_hidden(array('name'=>'pax','class'=>'form-control','id'=>'pax','value'=>@$pax));?></td>
<td><?php echo form_label('Vehicle Contact');?></td>
<td><?php echo form_input(array('name'=>'vehicle_contact','class'=>'form-control','id'=>'vehicle_contact','value'=>@$vehicle_contact));?></td>
</tr>

<table><tr><td><?php $save_update_button='SAVE';$class_save_update_button="class='btn btn-success trip-save-update'";
echo form_submit("trip-add",$save_update_button,$class_save_update_button).nbs(7);?></td></tr></table>
</table>
</fieldset>
<?php echo form_close(); ?>
</div>