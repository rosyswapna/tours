<div class="tour-booking-outer">
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
<td><?php echo form_input(array('name'=>'pickup_date','class'=>'form-control width-60-percent-with-margin-10','id'=>'pickupdatepicker','value'=>@$pickup_date));?></td>
<td style="margin-left:40px;"><?php echo form_label('Pickup');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
	  $name="trip_from_destination_id";
	  echo $this->form_functions->populate_dropdown($name,$destinations='',@$trip_from_destination_id,$class,$id='trip_from_destination_id',$msg);?></td>
<td><?php echo form_label('Vehicle Type');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
	  $name="vehicle_type_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicle_types,@$vehicle_type_id,$class,$id='vehicle_type_id',$msg);?></td>
</tr>

<tr>
<td><?php echo form_label('Contact');?></td>
<td><?php echo form_input(array('name'=>'customer_contact','class'=>'form-control','id'=>'customer_contact','value'=>@$customer_contact));?></td>
<td><?php echo form_label('Time');?></td>
<td><?php echo form_input(array('name'=>'pickup_time','class'=>'form-control width-60-percent-with-margin-10','id'=>'pickuptimepicker','value'=>@$pickup_time));?></td>
<td style="margin-left:40px;"><?php echo form_label('Drop');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
	  $name="trip_to_destination_id";
	  echo $this->form_functions->populate_dropdown($name,$destinations='',@$trip_to_destination_id,$class,$id='trip_to_destination_id',$msg);?></td>
<td><?php echo form_label('Vehicle Model');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
	  $name="vehicle_type_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicle_models,@$vehicle_model_id,$class,$id='vehicle_model_id',$msg);?></td>
</tr>

<tr>
<td><?php echo form_label('Guest');?></td>
<td><?php echo form_input(array('name'=>'guest_contact','class'=>'form-control','id'=>'guest_contact','value'=>@$guest_contact));?></td>
<td><?php echo form_label('To Date');?></td>
<td><?php echo form_input(array('name'=>'drop_date','class'=>'form-control width-60-percent-with-margin-10','id'=>'pickupdatepicker','value'=>@$drop_date));?></td>
<td style="margin-left:40px;"><?php echo form_label('PAX');?></td>
<td><?php echo form_input(array('name'=>'pax','class'=>'form-control','id'=>'pax','value'=>@$pax));?></td>
<td><?php echo form_label('Vehicle No.');?></td>
<td><?php $class="form-control";
	  $msg="-Select-";
	  $name="vehicle_type_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicles='',@$vehicle_id,$class,$id='vehicle_type_id',$msg);?></td>
</tr>
</table>
</fieldset>
</div>