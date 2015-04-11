
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


	<fieldset class="body-border">
	<legend class="body-head">Booking Source</legend>
	<table class="tour-booking-tbl">
	<tr>
	<td><?php echo form_label('Source','source');?></td>
	<td><?php $class="form-control";
		  $msg="Select";
		  $name="source_id";
		  echo $this->form_functions->populate_dropdown($name,$booking_sources,@$header['trip_source_id'],$class,$id='source_id',$msg,'disabled');?></td>
	<td><?php echo form_label('Contact','contact');?></td>
	<td><?php echo form_input(array('name'=>'source_contact','class'=>'form-control','id'=>'source_contact','value'=>@$header['source_contact'],'disabled'=>'disabled'));?>
	<?php echo  $this->form_functions->form_error_session('source_contact','<p class="text-red">', '</p>');?>
	</td>
	<td><?php echo form_label('Details','details');?></td>
	<td><?php echo form_input(array('name'=>'source_details','class'=>'form-control','id'=>'source_details','value'=>@$header['source_details'],'disabled'=>'disabled'));?></td>

	<?php if(!is_numeric($trip_id)){?>
	<td><?php echo form_label('Package','package');?></td>
	<td><?php $class="form-control";
		  $msg="Package";
		  $id = $name ="package_id";
		echo $this->form_functions->populate_editable_dropdown($name, $packages,$class,'Packages',array(),$msg,@$package_id,$id);
	?></td>
	<?php }?>

	</tr>
	</table>
	</fieldset>
</fieldset>

<fieldset class="body-border">
<legend class="body-head">Booking Information</legend>
<table class="tour-booking-tbl">
<tr>
<td><?php echo form_label('Customer');?></td>
<td><?php echo form_input(array('name'=>'customer','class'=>'form-control mandatory','id'=>'customer','value'=>@$header['customer_name'],'disabled'=>'disabled'));
echo  $this->form_functions->form_error_session('customer','<p class="text-red">', '</p>');?></td>
<td><?php echo form_label('From Date');?></td>
<td><?php echo form_input(array('name'=>'pick_up_date','class'=>'form-control  mandatory','id'=>'tour-pickupdatepicker','value'=>@$header['pick_up_date'],'disabled'=>'disabled'));?>
<?php echo  $this->form_functions->form_error_session('pick_up_date','<p class="text-red">', '</p>');?></td>

<td><?php echo form_label('Vehicle AC Type');?></td>
<td><?php $class="form-control";
	  $msg="Select";
	  $name="vehicle_ac_type_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicle_ac_types,@$vehicle_ac_type_id,$class,$id='vehicle_ac_type_id',$msg,'disabled');?>
	  <span class="text-red"><?php
		if($this->mysession->get('Err_V_Ac') != ''){
			echo $this->mysession->get('Err_V_Ac');
			$this->mysession->delete('Err_V_Ac');
		} 
		?></span></td>
<td><?php echo form_label('Pickup');?></td>
<td><?php echo form_input(array('name'=>'pick_up','class'=>'form-control','id'=>'pick_up','placeholder'=>'','value'=>@$pick_up_location,'disabled'=>'disabled')).form_input(array('name'=>'pick_up_lat','class'=>'form-control hide-me','id'=>'pick_up_lat','placeholder'=>'','value'=>@$pick_up_lat)).form_input(array('name'=>'pick_up_lng','class'=>'form-control hide-me','id'=>'pick_up_lng','placeholder'=>'','value'=>@$pick_up_lng));?></td>
</tr>

<tr>
<td><?php echo form_label('Contact');?></td>
<td><?php echo form_input(array('name'=>'customer_contact','class'=>'form-control','id'=>'customer_contact','value'=>@$header['customer_mobile'],'disabled'=>'disabled'));?>
<?php echo  $this->form_functions->form_error_session('customer_contact','<p class="text-red">', '</p>');?>
<div><?php $new_customer='true'; echo form_input(array('name'=>'newcustomer','class'=>'form-control newcustomer hide-me','value'=>$new_customer)).form_input(array('name'=>'customer_id','id'=>'customer_id','class'=>'form-control hide-me','value'=>'')); ?></div></td>
<td><?php echo form_label('Time');?></td>
<td><?php echo form_input(array('name'=>'pick_up_time','class'=>'form-control ','id'=>'tour-pickuptimepicker','value'=>@$header['pick_up_time'],'disabled'=>'disabled'));?></td>
<td><?php echo form_label('Vehicle Model');?></td>
<td><?php $class="form-control";
	  $msg="Select";
	  $name="vehicle_model_id";
	  echo $this->form_functions->populate_dropdown($name,$vehicle_models,@$vehicle_model_id,$class,$id='vehicle_model_id',$msg,'disabled');?>
	  <span class="text-red"><?php
		if($this->mysession->get('Err_Vmodel') != ''){
			echo $this->mysession->get('Err_Vmodel');
			$this->mysession->delete('Err_Vmodel');
		 }
	 ?></span>
</td>
<td><?php echo form_label('Drop');?></td>
<td><?php echo form_input(array('name'=>'drop','class'=>'form-control','id'=>'drop','placeholder'=>'','value'=>@$drop_location,'disabled'=>'disabled')).form_input(array('name'=>'drop_lat','class'=>'form-control hide-me','id'=>'drop_lat','placeholder'=>'','value'=>@$drop_lat)).form_input(array('name'=>'drop_lng','class'=>'form-control hide-me','id'=>'drop_lng','placeholder'=>'','value'=>@$drop_lng));?></td>
</tr>

<tr>
<td><?php echo form_label('Guest');?></td>
<td><?php echo form_input(array('name'=>'guest_name','class'=>'form-control','id'=>'guest_name','value'=>@$header['guest_name'],'disabled'=>'disabled'));?></td>
<td><?php echo form_label('To Date');?></td>
<td><?php echo form_input(array('name'=>'drop_date','class'=>'form-control  mandatory','id'=>'tour-dropdatepicker','value'=>@$header['drop_date'],'disabled'=>'disabled'));?>
<?php echo  $this->form_functions->form_error_session('drop_date','<p class="text-red">', '</p>');?></td>
<td><?php echo form_label('Vehicle No.');?></td>
<td><?php $class="form-control vehicle-list";
	  $id = $name = "vehicle_id";
	  $msg=' ';
	 
	echo $this->form_functions->populate_dropdown($name,@$available_vehicles,@$vehicle_id,$class,$id,$msg,'disabled');

		?>
	<span class="text-red"><?php
		if($this->mysession->get('Err_reg_num') != ''){
			echo $this->mysession->get('Err_reg_num');
			$this->mysession->delete('Err_reg_num');
		} 
						
	?></span></td>
<td><?php echo form_label('PAX');?></td>
<td><?php echo form_input(array('name'=>'pax','class'=>'form-control','id'=>'pax','value'=>@$pax,'disabled'=>'disabled'));?></td>
</tr>

<tr>
<td><?php echo form_label('Contact');?></td>
<td><?php echo form_input(array('name'=>'guest_contact','class'=>'form-control','id'=>'guest_contact','value'=>@$header['guest_mobile'],'disabled'=>'disabled')); echo  $this->form_functions->form_error_session('guest_contact','<p class="text-red">', '</p>');?>
<div><?php $new_guest='true'; echo form_input(array('name'=>'newguest','class'=>'form-control newguest hide-me','value'=>$new_guest)).form_input(array('name'=>'guest_id','id'=>'guest_id','class'=>'form-control hide-me','value'=>'')); ?></div></td>
<td><?php echo form_label('Time');?></td>
<td><?php echo form_input(array('name'=>'drop_time','class'=>'form-control ','id'=>'tour-droptimepicker','value'=>@$header['drop_time'],'disabled'=>'disabled'));?></td>
<td><?php echo form_label('Driver');?></td>
<td><?php $class="form-control";
	  $msg=' ';
		$id=$name='driver_id';
	echo $this->form_functions->populate_dropdown($name,@$driver_availability,@$header['driver_id'],$class,$id,$msg,'disabled');

	  
	?></td>
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
</fieldset>


<!--itinerary table starts here ,table illed by jquery-->

<div class="box-body table-responsive no-padding hide-me" id="voucher-itinerary-div">
	<table id="voucher-itinerary-tbl" class="table table-hover table-bordered table-with-20-percent-td">

	</table>

	<div class="form-submit-reset-buttons-group">
		<?php 
			echo form_open(base_url()."front-desk/tour/save_cart/".$trip_id);
			echo form_hidden('trip_id',$trip_id);
			echo form_submit("save-itry","Save","class='btn btn-success hide-me save-itry'");
			echo form_close();
		?>
		<button class="btn btn-success save-voucher-btn" type="button">Save</button>
	</div>
	
</div>

<br/>
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

		<!--vehicle tab content start here-->
		<?php if (array_key_exists('v_tab', $tabs)) {?>
		<div class="<?php echo $tabs['v_tab']['content_class'];?>" id="<?php echo $tabs['v_tab']['tab_id'];?>">

			<div class="page-outer">
	   		<fieldset class="body-border">
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Start Date','vehicle_start_date'); 
				    		echo form_input(array('name'=>'vehicle_start_date','class'=>'form-control initialize-date-picker  ','id'=>'vehicle_start_date','value'=>@$vehicle_start_date));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('To Date','vehicle_to_date'); 
				    		echo form_input(array('name'=>'vehicle_to_date','class'=>'form-control initialize-date-picker  ','id'=>'vehicle_to_date','value'=>@$vehicle_to_date));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Start Time','vehicle_start_time'); 
				    		echo form_input(array('name'=>'vehicle_start_time','class'=>'form-control time-picker','id'=>'vehicle_start_time','value'=>@$vehicle_start_time));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('End Time','vehicle_end_time'); 
				    		echo form_input(array('name'=>'vehicle_end_time','class'=>'form-control time-picker  ','id'=>'vehicle_end_time','value'=>@$vehicle_end_time));
					?>
					</div>
				

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Start KM','start_km'); 
				    		echo form_input(array('name'=>'start_km','class'=>'form-control','id'=>'start_km','value'=>@$start_km));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('End KM','end_km'); 
				    		echo form_input(array('name'=>'end_km','class'=>'form-control','id'=>'end_km','value'=>@$end_km));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Vehicle','vehicle_id'); 
						$name = $id = 'vehicle_id';
						$class = 'form-control';
						$msg = "Select Vehicle";
						echo $this->form_functions->populate_dropdown($name,@$vehicles,@$vehicle_id,$class,$id,$msg); 
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Tariff','vehicle_tariff_id'); 
						$name = $id = 'vehicle_tariff_id';
						$class = 'form-control';
						$msg = "Select Tariff";
						echo $this->form_functions->populate_dropdown($name,@$vehicle_tariffs,@$vehicle_tariff_id,$class,$id,$msg); 
					?>
					</div>


					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Base KM','base_km'); 
				    		echo form_input(array('name'=>'base_km','class'=>'form-control','id'=>'base_km','value'=>@$base_km));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Base KM Amount','base_km_amount'); 
				    		echo form_input(array('name'=>'base_km_amount','class'=>'form-control','id'=>'base_km_amount','value'=>@$base_km_amount));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Adt KM','adt_km'); 
				    		echo form_input(array('name'=>'adt_km','class'=>'form-control','id'=>'adt_km','value'=>@$adt_km));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Adt KM Amount','adt_km_amount'); 
				    		echo form_input(array('name'=>'adt_km_amount','class'=>'form-control','id'=>'adt_km_amount','value'=>@$adt_km_amount));
					?>
					</div>


					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Base HR','base_hr'); 
				    		echo form_input(array('name'=>'base_hr','class'=>'form-control','id'=>'base_hr','value'=>@$base_hr));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Base HR Amount','base_hr_amount'); 
				    		echo form_input(array('name'=>'base_hr_amount','class'=>'form-control','id'=>'base_hr_amount','value'=>@$base_hr_amount));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Adt HR','adt_hr'); 
				    		echo form_input(array('name'=>'adt_hr','class'=>'form-control','id'=>'adt_hr','value'=>@$adt_hr));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Adt HR Amount','adt_hr_amount'); 
				    		echo form_input(array('name'=>'adt_hr_amount','class'=>'form-control','id'=>'adt_hr_amount','value'=>@$adt_hr_amount));
					?>
					</div>

					<?php
					if($trip_expenses){
						foreach($trip_expenses as $expense){

						$name = "expense[".$expense['value']."]";
						$id = "expense".$expense['value'];
					?>
				
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label($expense['description'],$expense['value']); 
				    		echo form_input(array('name'=>$name,'class'=>'form-control trip-expense-input','id'=>$id,'value'=>''));
					?>
					</div>

						
					<?php	}
					}
					?>


					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Unit Amount','vehicle_unit_amount'); 
				    		echo form_input(array('name'=>'vehicle_unit_amount','class'=>'form-control','id'=>'vehicle_unit_amount','value'=>@$vehicle_unit_amount));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Advance','vehicle_advance_amount'); 
				    		echo form_input(array('name'=>'vehicle_advance_amount','class'=>'form-control','id'=>'vehicle_advance_amount','value'=>@$vehicle_advance_amount));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Tax','vehicle_tax'); 
				    		echo form_input(array('name'=>'vehicle_tax','class'=>'form-control','id'=>'vehicle_tax','value'=>@$vehicle_tax));
					?>
					</div>
		
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Total Amount','vehicle_total_amount'); 
				    		echo form_input(array('name'=>'vehicle_total_amount','class'=>'form-control','id'=>'vehicle_total_amount','value'=>@$vehicle_total_amount));
					?>
					</div>
				</div>
			
			</fieldset>
			</div>
		</div>
		<?php }?>
		<!--vehicle tab content ends here-->


		<!--accommodation tab content start here-->
		<?php if (array_key_exists('a_tab', $tabs)) {?>
		<div class="<?php echo $tabs['a_tab']['content_class'];?>" id="<?php echo $tabs['a_tab']['tab_id'];?>">

			<div class="page-outer">
	   		<fieldset class="body-border">

				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Start Date','accommodation_start_date'); 
				    		echo form_input(array('name'=>'accommodation_start_date','class'=>'form-control initialize-date-picker  ','id'=>'accommodation_start_date','value'=>@$accommodation_start_date));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('To Date','accommodation_to_date'); 
				    		echo form_input(array('name'=>'accommodation_to_date','class'=>'form-control initialize-date-picker  ','id'=>'accommodation_to_date','value'=>@$accommodation_to_date));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Check	in','accommodation_checkin'); 
				    		echo form_input(array('name'=>'accommodation_checkin','class'=>'form-control time-picker','id'=>'accommodation_checkin','value'=>@$accommodation_checkin));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Check out','accommodation_checkout'); 
				    		echo form_input(array('name'=>'accommodation_checkout','class'=>'form-control time-picker  ','id'=>'accommodation_checkout','value'=>@$accommodation_checkout));
					?>
					</div>
				</div>

				

			</fieldset>
			</div>
		</div>
		<?php }?>
		<!--accommodation tab content ends here-->


		<!--services tab contents start here-->
		<?php if (array_key_exists('s_tab', $tabs)) {?>
		<div class="<?php echo $tabs['s_tab']['content_class'];?>" id="<?php echo $tabs['s_tab']['tab_id'];?>">

			<div class="page-outer">
	   		<fieldset class="body-border">

				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Start Date','service_start_date'); 
				    		echo form_input(array('name'=>'service_start_date','class'=>'form-control initialize-date-picker  ','id'=>'service_start_date','value'=>@$service_start_date));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('To Date','service_to_date'); 
				    		echo form_input(array('name'=>'service_to_date','class'=>'form-control initialize-date-picker  ','id'=>'service_to_date','value'=>@$service_to_date));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Check in','service_checkin'); 
				    		echo form_input(array('name'=>'service_checkin','class'=>'form-control time-picker','id'=>'service_checkin','value'=>@$service_checkin));
					?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php
						echo form_label('Check out','service_checkout'); 
				    		echo form_input(array('name'=>'service_checkout','class'=>'form-control time-picker  ','id'=>'service_checkout','value'=>@$service_checkout));
					?>
					</div>
				</div>
				

			</fieldset>
			</div>
		</div>
		<?php }?>
		<!--services tab contents ends here-->

	</div><!--tab contents ends-->

	
</div>

<!--itinerary tabs ends-->
</div>

