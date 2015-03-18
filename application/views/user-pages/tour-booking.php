<div class="tour-booking-outer">
<?php echo form_open(base_url()."tour/manage_tour_booking"); ?>
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
<td><?php echo form_input(array('name'=>'source_contact','class'=>'form-control','id'=>'source_contact','value'=>@$source_contact));?></td>
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
<td><?php echo form_input(array('name'=>'customer','class'=>'form-control','id'=>'customer','value'=>@$customer));?></td>
<td><?php echo form_label('From Date');?></td>
<td><?php echo form_input(array('name'=>'pickup_date','class'=>'form-control width-60-percent-with-margin-10','id'=>'tour-pickupdatepicker','value'=>@$pickup_date));?></td>

<td><?php echo form_label('Vehicle AC Type');?></td>
<td><?php $class="form-control";
	  $msg="Select";
	  $name="vehicle_ac_type_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicle_ac_types,@$vehicle_ac_type_id,$class,$id='vehicle_ac_type_id',$msg);?></td>
<td><?php echo form_label('Pickup');?></td>
<td><?php echo form_input(array('name'=>'pickup','class'=>'form-control','id'=>'pickup','placeholder'=>'','value'=>@$pickup)).form_input(array('name'=>'pickup_lat','class'=>'form-control hide-me','id'=>'pickup_lat','placeholder'=>'','value'=>@$pickup_lat)).form_input(array('name'=>'pickup_lng','class'=>'form-control hide-me','id'=>'pickup_lng','placeholder'=>'','value'=>@$pickup_lng));?></td>
</tr>

<tr>
<td><?php echo form_label('Contact');?></td>
<td><?php echo form_input(array('name'=>'customer_contact','class'=>'form-control','id'=>'customer_contact','value'=>@$customer_contact));?>
<div><?php $new_customer='true'; echo form_input(array('name'=>'newcustomer','class'=>'form-control newcustomer hide-me','value'=>$new_customer)).form_input(array('name'=>'customer_id','id'=>'customer_id','class'=>'form-control hide-me','value'=>'')); ?></div></td>
<td><?php echo form_label('Time');?></td>
<td><?php echo form_input(array('name'=>'pickup_time','class'=>'form-control width-60-percent-with-margin-10','id'=>'tour-pickuptimepicker','value'=>@$pickup_time));?></td>
<td><?php echo form_label('Vehicle Model');?></td>
<td><?php $class="form-control";
	  $msg="Select";
	  $name="vehicle_model_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicle_models,@$vehicle_model_id,$class,$id='vehicle_model_id',$msg);?></td>
<td><?php echo form_label('Drop');?></td>
<td><?php echo form_input(array('name'=>'drop','class'=>'form-control','id'=>'drop','placeholder'=>'','value'=>@$drop)).form_input(array('name'=>'drop_lat','class'=>'form-control hide-me','id'=>'drop_lat','placeholder'=>'','value'=>@$drop_lat)).form_input(array('name'=>'drop_lng','class'=>'form-control hide-me','id'=>'drop_lng','placeholder'=>'','value'=>@$drop_lng));?></td>
</tr>

<tr>
<td><?php echo form_label('Guest');?></td>
<td><?php echo form_input(array('name'=>'guest_name','class'=>'form-control','id'=>'guest_name','value'=>@$guest_name));?></td>
<td><?php echo form_label('To Date');?></td>
<td><?php echo form_input(array('name'=>'drop_date','class'=>'form-control width-60-percent-with-margin-10','id'=>'tour-dropdatepicker','value'=>@$drop_date));?></td>
<td><?php echo form_label('Vehicle No.');?></td>
<td><?php $class="form-control vehicle-list";
	  $id="available_vehicle";
	  $msg=' ';
echo $this->form_functions->populate_editable_dropdown('available_vehicle', @$available_vehicles,$class,'vehicles',array(),$msg,@$available_vehicle,$id);?></td>
<td><?php echo form_label('PAX');?></td>
<td><?php echo form_input(array('name'=>'pax','class'=>'form-control','id'=>'pax','value'=>@$pax));?></td>
</tr>

<tr>
<td><?php echo form_label('Contact');?></td>
<td><?php echo form_input(array('name'=>'guest_contact','class'=>'form-control','id'=>'guest_contact','value'=>@$guest_contact));?>
<div><?php $new_guest='true'; echo form_input(array('name'=>'newguest','class'=>'form-control newguest hide-me','value'=>$new_guest)).form_input(array('name'=>'guest_id','id'=>'guest_id','class'=>'form-control hide-me','value'=>'')); ?></div></td>
<td><?php echo form_label('Time');?></td>
<td><?php echo form_input(array('name'=>'drop_time','class'=>'form-control width-60-percent-with-margin-10','id'=>'tour-droptimepicker','value'=>@$drop_time));?></td>
<td><?php echo form_label('Driver');?></td>
<td><?php $class="form-control";
	  $msg=' ';

	  echo $this->form_functions->populate_editable_dropdown('driver_list', @$driver_availability,$class,'drivers',array(),$msg,@$available_driver);?></td>
<td><?php echo " ";?></td>
<td><?php echo form_checkbox(array('name'=>'advanced','id'=>'advanced-check-box','class'=>'advanced-check-box flat-red','checked'=>@$advanced)).nbs(4);?>
<?php echo form_label('Advanced');?></td>
</tr>
<tbody style="display:none;" class="tbody-toggle">
<tr>
<td><?php echo form_checkbox(array('name'=> 'beacon_light','class'=>'beacon-light-chek-box flat-red','checked'=>@$beacon_light,'radio_to_be_selected'=>@$beacon_light_radio));
	  ?></td>
<td><?php echo form_label('Beacon Light');?></td>
<td><?php echo form_checkbox(array('name'=> 'pluck_card','class'=>'pluckcard-chek-box flat-red','checked'=>@$pluck_card));?></td>
<td><?php echo form_label('Placard');?></td>
<td><?php echo form_checkbox(array('name'=> 'uniform','class'=>'uniform-chek-box flat-red','checked'=>@$uniform));?></td>
<td><?php echo form_label('Uniform');?></td>
<td><?php echo form_label('Languages');?></td>
<td><?php $class="form-control";
	  $msg="Select";
	  $name="language";
echo $this->form_functions->populate_dropdown($name,@$languages,@$language,$class,$id='',$msg);?>
</td>
</tr>
</tbody>
<table><tr><td><?php $save_update_button='SAVE';$class_save_update_button="class='btn btn-success trip-save-update'";
echo form_submit("trip-add",$save_update_button,$class_save_update_button).nbs(7);?></td></tr></table>
</table>

</fieldset>
<?php echo form_close(); ?>
</div>