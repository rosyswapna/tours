<!--tour-voucher.js -->
<script src="<?php echo base_url();?>js/tour-voucher.js" type="text/javascript"></script>

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
			<td width="20%">
			<?php
			echo form_label(@$header['booking_source_name'],'source',array('class'=>'form-control label-value'));
			?>
			</td>

			<td><?php echo form_label('Contact','contact');?></td>
			<td width="20%">
			<?php
			echo form_label(@$header['source_contact'],'source',array('class'=>'form-control label-value'));
			?>
			</td>

			<td><?php echo form_label('Details','details');?></td>
			<td width="20%">
			<?php
			echo form_label(@$header['source_details'],'source',array('class'=>'form-control label-value'));
			?>
			</td>

			<td><?php echo form_label('Package','package');?></td>
			<td width="20%">
			<?php
			echo form_label(@$header['package_name'],'',array('class'=>'form-control label-value'));
			?>
			</td>
			</tr>
		</table>
	</fieldset>


	<fieldset class="body-border">
		<legend class="body-head">Booking Information</legend>

		<table class="tour-booking-tbl">
		<tr>
		<td><?php echo form_label('Customer');?></td>
		<td width="20%"><?php echo form_label(@$header['customer_name'],'',array('class'=>'form-control label-value'));
		?></td>

		<td><?php echo form_label('From Date');?></td>
		<td width="20%"><?php echo form_label(@$header['pick_up_date'],'',array('class'=>'form-control label-value'));
		;?></td>

		<td><?php echo form_label('Vehicle AC Type');?></td>
		<td width="20%"><?php echo form_label(@$header['vehicle_id'],'',array('class'=>'form-control label-value'));
		?></span></td>

		<td><?php echo form_label('Pickup');?></td>
		<td width="15%"><?php echo form_label(@$header['pick_up_location'],'',array('class'=>'form-control label-value'));
		?></td>

		</tr>

		<tr>
		<td><?php echo form_label('Contact');?></td>
		<td><?php echo form_label(@$header['customer_mobile'],'',array('class'=>'form-control label-value'));
		?></td>

		<td><?php echo form_label('Time');?></td>
		<td><?php echo form_label(@$header['pick_up_time'],'',array('class'=>'form-control label-value'));
		?></td>

		<td><?php echo form_label('Vehicle Model');?></td>
		<td><?php echo form_label(@$header['vehicle_model_name'],'',array('class'=>'form-control label-value'));
		?></td>

		<td><?php echo form_label('Drop');?></td>
		<td><?php echo form_label(@$header['drop_location'],'',array('class'=>'form-control label-value'));
		?></td>

		</tr>

		<tr>
		<td><?php echo form_label('Guest');?></td>
		<td><?php echo form_label(@$header['guest_name'],'',array('class'=>'form-control label-value'));
		?></td>

		<td><?php echo form_label('To Date');?></td>
		<td><?php echo form_label(@$header['drop_date'],'',array('class'=>'form-control label-value'));
		?></td>

		<td><?php echo form_label('Vehicle No.');?></td>
		<td><?php echo form_label(@$header['vehicle_no'],'',array('class'=>'form-control label-value'));
		?></td>

		<td><?php echo form_label('PAX');?></td>
		<td><?php echo form_label(@$header['pax'],'',array('class'=>'form-control label-value'));
		?></td>

		</tr>

		<tr>
		<td><?php echo form_label('Contact');?></td>
		<td><?php echo form_label(@$header['guest_mobile'],'',array('class'=>'form-control label-value'));
		?></td>
	
		<td><?php echo form_label('Time');?></td>
		<td><?php echo form_label(@$header['drop_time'],'',array('class'=>'form-control label-value'));
		?></td>

		<td><?php echo form_label('Driver');?></td>
		<td><?php echo form_label(@$header['driver_name'],'',array('class'=>'form-control label-value'));
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
		</table>
	</fieldset>


	<!--itinerary table starts here ,table illed by jquery-->
	<div class="box-body table-responsive no-padding hide-me" id="voucher-itinerary-div">
		<table id="voucher-tbl" class="table table-hover table-bordered">

		</table>

		<div class="form-submit-reset-buttons-group">
			<?php 
				echo form_open(base_url()."front-desk/voucher/save");
				echo form_submit("save-voucher","Save","class='btn btn-success hide-me save-itry'");
				echo form_close();
			?>
			<center><button class="btn btn-success save-itry-btn" type="button">Save</button></center>
		</div>
	</div>

	<br/>
	<!--itinerary table ends here-->


	<!--itinerary tabs starts-->
	<div class="nav-tabs-custom voucher-tabs">
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
			<div class="<?php echo $tabs['v_tab']['content_class'];?> voucher-vehicle-tab" id="<?php echo $tabs['v_tab']['tab_id'];?>">

				<div class="page-outer">
		   		<fieldset class="body-border">
					<div class="row-source-100-percent-width-with-margin-8">

						<div class="form-group div-with-17-percent-width-with-margin-10">
						<?php
							echo form_label('Vehicle Model','vehicle_model_id'); 
							$name = $id = 'vehicle_model_id';
							$class = 'form-control';
							$msg = "Select";
							echo $this->form_functions->populate_dropdown($name,@$vehicle_models,@$vehicle_model_id,$class,$id,$msg); 
						?>
						</div>

						<div class="form-group div-with-17-percent-width-with-margin-10">
						<?php
							echo form_label('Vehicle AC Type','vehicle_ac_type_id'); 
							$name = $id = 'vehicle_ac_type_id';
							$class = 'form-control';
							$msg = "Select";
							echo $this->form_functions->populate_dropdown($name,@$vehicle_ac_types,@$vehicle_ac_type_id,$class,$id,$msg); 
						?>
						</div>

						<div class="form-group div-with-17-percent-width-with-margin-10">
						<?php
							echo form_label('Vehicle','vehicle_id'); 
							$name = $id = 'vehicle_id';
							$class = 'form-control';
							$msg = "Select Vehicle";
							echo $this->form_functions->populate_dropdown($name,@$vehicles,@$vehicle_id,$class,$id,$msg); 
						?>
						</div>

						<div class="form-group div-with-17-percent-width-with-margin-10">
						<?php
							echo form_label('Driver','driver_id'); 
							$name = $id = 'driver_id';
							$class = 'form-control';
							$msg = "Select";
							echo $this->form_functions->populate_dropdown($name,@$drivers,@$driver_id,$class,$id,$msg); 
						?>
						</div>

						<div class="form-group div-with-17-percent-width-with-margin-10">
						<?php
							echo form_label('Tariff','vehicle_tariff_id'); 
							$name = $id = 'vehicle_tariff_id';
							$class = 'form-control';
							$msg = "Select Tariff";
							echo $this->form_functions->populate_dropdown($name,@$vehicle_tariffs,@$vehicle_tariff_id,$class,$id,$msg); 
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('From Date','vehicle_from_date'); 
					    		echo form_input(array('name'=>'vehicle_from_date','class'=>'form-control initialize-date-picker  ','id'=>'vehicle_from_date','value'=>@$vehicle_from_date));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('To Date','vehicle_to_date'); 
					    		echo form_input(array('name'=>'vehicle_to_date','class'=>'form-control initialize-date-picker  ','id'=>'vehicle_to_date','value'=>@$vehicle_to_date));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Start Time','vehicle_start_time'); 
					    		echo form_input(array('name'=>'vehicle_start_time','class'=>'form-control time-picker','id'=>'vehicle_start_time','value'=>@$vehicle_start_time));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('End Time','vehicle_end_time'); 
					    		echo form_input(array('name'=>'vehicle_end_time','class'=>'form-control time-picker  ','id'=>'vehicle_end_time','value'=>@$vehicle_end_time));
						?>
						</div>
				

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Start KM','start_km'); 
					    		echo form_input(array('name'=>'start_km','class'=>'form-control','id'=>'start_km','value'=>@$start_km));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('End KM','end_km'); 
					    		echo form_input(array('name'=>'end_km','class'=>'form-control','id'=>'end_km','value'=>@$end_km));
						?>
						</div>
			
						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('No Of Days','no_of_days'); 
					    		echo form_input(array('name'=>'no_of_days','class'=>'form-control','id'=>'no_of_days','value'=>@$no_of_days,'readonly'=>'readonly'));
						?>
						</div>
			
						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Total KM','total_km'); 
					    		echo form_input(array('name'=>'total_km','class'=>'form-control','id'=>'total_km','value'=>@$total_km,'readonly'=>'readonly'));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Total HR','total_hr'); 
					    		echo form_input(array('name'=>'total_hr','class'=>'form-control','id'=>'total_hr','value'=>@$total_hr,'disabled'=>'disabled'));
						?>
						</div>

						
					</div>

					<div class="row-source-100-percent-width-with-margin-8 vehicle-km-row">

						

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Base KM','base_km'); 
					    		echo form_input(array('name'=>'base_km','class'=>'form-control','id'=>'base_km','value'=>@$base_km));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Base KM Amount','base_km_amount'); 
					    		echo form_input(array('name'=>'base_km_amount','class'=>'form-control','id'=>'base_km_amount','value'=>@$base_km_amount));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Adt KM','adt_km'); 
					    		echo form_input(array('name'=>'adt_km','class'=>'form-control','id'=>'adt_km','value'=>@$adt_km));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Adt KM Rate','adt_km_rate'); 
					    		echo form_input(array('name'=>'adt_km_rate','class'=>'form-control','id'=>'adt_km_rate','value'=>@$adt_km_rate));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Adt KM Amount','adt_km_amount'); 
					    		echo form_input(array('name'=>'adt_km_amount','class'=>'form-control','id'=>'adt_km_amount','value'=>@$adt_km_amount));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Total Amount','total_km_amount'); 
					    		echo form_input(array('name'=>'total_km_amount','class'=>'form-control totalkmamount','id'=>'total_km_amount','value'=>@$total_km_amount));
							
						?>
						</div>

						<div class="div-with-3-percent-width-with-margin-10">
							<div class="form-group margin-top-23-px totamount-radio-container1">
							<?php echo form_radio(array('name' => 'km_hr','id' => 'km_radio')); 
							?>
							</div>
						</div>
					</div>

					<div class="row-source-100-percent-width-with-margin-8 vehicle-hr-row">

						

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Base HR','base_hr'); 
					    		echo form_input(array('name'=>'base_hr','class'=>'form-control','id'=>'base_hr','value'=>@$base_hr));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Base HR Amount','base_hr_amount'); 
					    		echo form_input(array('name'=>'base_hr_amount','class'=>'form-control','id'=>'base_hr_amount','value'=>@$base_hr_amount));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Adt HR','adt_hr'); 
					    		echo form_input(array('name'=>'adt_hr','class'=>'form-control','id'=>'adt_hr','value'=>@$adt_hr));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Adt HR Rate','adt_hr_rate'); 
					    		echo form_input(array('name'=>'adt_hr_rate','class'=>'form-control','id'=>'adt_hr_rate','value'=>@$adt_hr_rate));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Adt HR Amount','adt_hr_amount'); 
					    		echo form_input(array('name'=>'adt_hr_amount','class'=>'form-control','id'=>'adt_hr_amount','value'=>@$adt_hr_amount));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Total Amount','total_hr_amount'); 
					    		echo form_input(array('name'=>'total_hr_amount','class'=>'form-control totalhramount','id'=>'total_hr_amount','value'=>@$total_hr_amount));
						?>
						</div>

						<div class="div-with-3-percent-width-with-margin-10">
							<div class=" form-group margin-top-23-px totamount-radio-container2">
							<?php echo form_radio(array('name' => 'km_hr','id' => 'hr_radio')); 
							?>
							</div>
						</div>
					</div>

					<div class="row-source-100-percent-width-with-margin-8">

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Driver Bata','driver_bata'); 
					    		echo form_input(array('name'=>'driver_bata','class'=>'form-control','id'=>'driver_bata','value'=>@$driver_bata));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Night Halt Charge','night_halt_charge'); 
					    		echo form_input(array('name'=>'night_halt_charge','class'=>'form-control','id'=>'night_halt_charge','value'=>@$night_halt_charge));
						?>
						</div>


						<?php
						if($trip_expenses){
							foreach($trip_expenses as $expense){

							$name = "expense[".$expense['value']."]";
							$id = "expense".$expense['value'];
						?>
				
						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							$label = $expense['description'];
							$title = $expense['description'];
							if(strlen($label) > 14)
								$label = substr($label,0,14)."..";
							echo form_label($label); 
					    		echo form_input(array('name'=>$name,'class'=>'form-control trip-expense-input','id'=>$id,'value'=>'','title'=>$title));
						?>
						</div>

						
						<?php	}
						}
						?>


						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Unit Amount','vehicle_unit_amount'); 
					    		echo form_input(array('name'=>'vehicle_unit_amount','class'=>'form-control vehicletariffamount','id'=>'vehicle_unit_amount','value'=>@$vehicle_unit_amount,'amount-class-to-be-selected'=>''));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Advance','vehicle_advance_amount'); 
					    		echo form_input(array('name'=>'vehicle_advance_amount','class'=>'form-control','id'=>'vehicle_advance_amount','value'=>@$vehicle_advance_amount));
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Tax','vehicle_tax_group_id'); 
							$name = $id = 'vehicle_tax_group_id';
							$class = 'form-control';
							$msg = "Select";
							echo $this->form_functions->populate_dropdown($name,@$taxes,@$vehicle_tax_group_id,$class,$id,$msg); 
						?>
						</div>

						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Tax Amount','vehicle_tax_amount'); 
					    		echo form_input(array('name'=>'vehicle_tax_amount','class'=>'form-control','id'=>'vehicle_tax_amount','value'=>@$vehicle_tax_amount,'readonly'=>'readonly'));
						?>
						</div>
		
						<div class="form-group div-with-14-percent-width-with-margin-6">
						<?php
							echo form_label('Total Amount','vehicle_total_amount'); 
					    		echo form_input(array('name'=>'vehicle_total_amount','class'=>'form-control','id'=>'vehicle_total_amount','value'=>@$vehicle_total_amount,'disabled'=>'disabled'));
						?>
						</div>
					</div>

					<div class="row-source-100-percent-width-with-margin-8">
						<div class="box-footer "><center>
						<?php echo form_submit("add-voucher-vehicle","Add","class='btn btn-primary' id='add-voucher-vehicle'");?></center>
						</div>
					</div>
			
				</fieldset>
				</div>
			</div>
			<?php }?>
			<!--vehicle tab content ends here-->


			<!--accommodation tab content start here-->
			<?php if (array_key_exists('a_tab', $tabs)) {?>
			<div class="<?php echo $tabs['a_tab']['content_class'];?> voucher-accomodation-tab" id="<?php echo $tabs['a_tab']['tab_id'];?>">

				<div class="page-outer">
		   		<fieldset class="body-border">

					<div class="row-source-100-percent-width-with-margin-8">
						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Start Date','acmd_from_date'); 
					    		echo form_input(array('name'=>'acmd_from_date','class'=>'form-control initialize-date-picker  ','id'=>'acmd_from_date','value'=>@$acmd_from_date));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('To Date','acmd_to_date'); 
					    		echo form_input(array('name'=>'acmd_to_date','class'=>'form-control initialize-date-picker  ','id'=>'acmd_to_date','value'=>@$acmd_to_date));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Check	in','acmd_checkin'); 
					    		echo form_input(array('name'=>'acmd_checkin','class'=>'form-control time-picker','id'=>'acmd_checkin','value'=>@$acmd_checkin));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Check out','acmd_checkout'); 
					    		echo form_input(array('name'=>'acmd_checkout','class'=>'form-control time-picker  ','id'=>'acmd_checkout','value'=>@$acmd_checkout));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Hotel','acmd_hotel_id');

							$class="form-control";
						  	$msg="Select";
						  	$name=$id="acmd_hotel_id";
						  	echo $this->form_functions->populate_dropdown($name,@$tour_arrays['tour_hotels'],@$acmd_hotel_id,$class,$id,$msg); 
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Room Type','acmd_room_type_id'); 
					    		$class="form-control";
						  	$msg="Select";
						  	$name=$id="acmd_room_type_id";
						  	echo $this->form_functions->populate_dropdown($name,@$trip_hotel_rooms,@$acmd_room_type_id,$class,$id,$msg); 
						?>
						</div>
				
						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Days','acmd_days'); 
					    		echo form_input(array('name'=>'acmd_days','class'=>'form-control','id'=>'acmd_days','value'=>@$acmd_days));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Tariff','room_tariff_amt'); 
					    		echo form_input(array('name'=>'room_tariff_amt','class'=>'form-control','id'=>'room_tariff_amt','value'=>@$room_tariff_amt));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Unit Amount','acmd_unit_amount'); 
					    		echo form_input(array('name'=>'acmd_unit_amount','class'=>'form-control','id'=>'acmd_unit_amount','value'=>@$acmd_unit_amount));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Advance','acmd_advance_amount'); 
					    		echo form_input(array('name'=>'acmd_advance_amount','class'=>'form-control','id'=>'acmd_advance_amount','value'=>@$acmd_advance_amount));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Tax','acmd_tax_amount'); 
					    		echo form_input(array('name'=>'acmd_tax_amount','class'=>'form-control','id'=>'acmd_tax_amount','value'=>@$acmd_tax_amount));
						?>
						</div>
		
						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Total Amount','acmd_total_amount'); 
					    		echo form_input(array('name'=>'acmd_total_amount','class'=>'form-control','id'=>'acmd_total_amount','value'=>@$acmd_total_amount));
						?>
						</div>


						
					</div>

					<div class="row-source-100-percent-width-with-margin-8">
						<div class="box-footer "><center>
						<?php echo form_submit("add-voucher-accommodation","Add","class='btn btn-primary' id='add-voucher-accommodation'");?></center>
						</div>
					</div>



				</fieldset>
				</div>
			</div>
			<?php }?>
			<!--accommodation tab content ends here-->


			<!--services tab contents start here-->
			<?php if (array_key_exists('s_tab', $tabs)) {?>
			<div class="<?php echo $tabs['s_tab']['content_class'];?> voucher-service-tab" id="<?php echo $tabs['s_tab']['tab_id'];?>">

				<div class="page-outer">
		   		<fieldset class="body-border">

					<div class="row-source-100-percent-width-with-margin-8">
						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Start Date','service_from_date'); 
					    		echo form_input(array('name'=>'service_from_date','class'=>'form-control initialize-date-picker  ','id'=>'service_from_date','value'=>@$service_from_date));
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

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Service','service_id'); 
					    		$class="form-control";
						  	$msg="Select";
						  	$name=$id="service_id";
						  	echo $this->form_functions->populate_dropdown($name,@$tour_arrays['tour_services'],@$service_id,$class,$id,$msg); 
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Rate','service_rate'); 
					    		echo form_input(array('name'=>'service_rate','class'=>'form-control','id'=>'service_rate','value'=>@$service_rate));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Quantity','service_qty'); 
					    		echo form_input(array('name'=>'service_qty','class'=>'form-control','id'=>'service_qty','value'=>@$service_qty));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('UOM','service_uom_id'); 
					    		$class="form-control";
						  	$msg="Select";
						  	$name=$id="service_uom_id";
						  	echo $this->form_functions->populate_dropdown($name,@$uom_list,@$service_uom_id,$class,$id,$msg); 
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Unit Amount','service_unit_amount'); 
					    		echo form_input(array('name'=>'service_unit_amount','class'=>'form-control','id'=>'service_unit_amount','value'=>@$service_unit_amount));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Advance','service_advance_amount'); 
					    		echo form_input(array('name'=>'service_advance_amount','class'=>'form-control','id'=>'service_advance_amount','value'=>@$service_advance_amount));
						?>
						</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Tax','service_tax_amount'); 
					    		echo form_input(array('name'=>'service_tax_amount','class'=>'form-control','id'=>'service_tax_amount','value'=>@$service_tax_amount));
						?>
						</div>
		
						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php
							echo form_label('Total Amount','service_total_amount'); 
					    		echo form_input(array('name'=>'service_total_amount','class'=>'form-control','id'=>'service_total_amount','value'=>@$service_total_amount));
						?>
						</div>

					</div>

					<div class="row-source-100-percent-width-with-margin-8">
						<div class="box-footer "><center>
						<?php echo form_submit("add-voucher-service","Add","class='btn btn-primary' id='add-voucher-service'");?></center>
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

