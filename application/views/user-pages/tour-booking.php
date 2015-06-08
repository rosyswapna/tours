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
	echo form_hidden('trip_id',$trip_id);
	
?>


	<fieldset class="body-border">
	<legend class="body-head"><?php if($flag=='PA'){ echo "Package Add";}
					elseif($flag=='TA'){echo "Trip Add";} 
					elseif($flag=='PE'){echo "Package Edit";} 
					elseif($flag=='TE'){echo "Trip Edit";} 
					?></legend>
	<table class="tour-booking-tbl">
	<tr>
	<?php if($flag=='TA' ||$flag=='TE'){ ?>
	<td class="td-10">
	<?php  echo form_label('Source','source');?></td>
	<td class="td-15"><?php $class="form-control";
		  $msg="Select";
		  $name="source_id";
		  echo $this->form_functions->populate_dropdown($name,$booking_sources,@$header['trip_source_id'],$class,$id='source_id',$msg);?></td>
		  
	<td class="td-10"><?php echo form_label('Contact','contact');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'source_contact','class'=>'form-control','id'=>'source_contact','value'=>@$header['source_contact']));?>
	<?php echo  $this->form_functions->form_error_session('source_contact','<p class="text-red">', '</p>');?>
	</td>
	<td class="td-10"><?php echo form_label('Details','details');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'source_details','class'=>'form-control','id'=>'source_details','value'=>@$header['source_details']));?></td>

	<?php //} if(!is_numeric($trip_id) || $package_id > 0 ){?>
	<?php } if($flag=='TA' || $flag=='PA' || $flag=='PE'){?>
	<td class="td-10"><?php echo form_label('Package','package');?></td>
	<td class="td-15"><?php 
		if($flag=='PE' ||$flag=='TA'){
		        $class="form-control";
		        $msg="Package";
		        $id = $name ="package_id";
			echo $this->form_functions->populate_editable_dropdown($name, $packages,$class,'Packages',array(),$msg,@$header['package_id'],$id);
		}elseif($flag=='PA'){
			echo form_input(array('name'=>'package_id','class'=>'form-control','id'=>'package_id','placeholder'=>'Package Name'));
		}
	?></td>
	<?php }?>

	</tr>
	</table>
	</fieldset>
	<?php if($flag=='TA' ||$flag=='TE'){?>
	<fieldset class="body-border">
	<legend class="body-head">Booking Information</legend>
	<table class="tour-booking-tbl">
	<tr>
	<td class="td-10"><?php echo form_label('Customer');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'customer','class'=>'form-control mandatory','id'=>'customer','value'=>@$header['customer_name']));
	echo  $this->form_functions->form_error_session('customer','<p class="text-red">', '</p>');?></td>
	<td class="td-10"><?php echo form_label('From Date');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'pick_up_date','class'=>'form-control  mandatory','id'=>'tour-pickupdatepicker','value'=>@$header['pick_up_date']));?>
	<?php echo  $this->form_functions->form_error_session('pick_up_date','<p class="text-red">', '</p>');?></td>

	<td class="td-10"><?php echo form_label('AC Type');?></td>
	<td class="td-15"><?php $class="form-control";
		  $msg="Select";
		  $name="vehicle_ac_type_id";
		  echo $this->form_functions->populate_dropdown($name,$vehicle_ac_types,@$header['vehicle_ac_type_id'],$class,$id='vehicle_ac_type',$msg);?>
		  <span class="text-red"><?php
			if($this->mysession->get('Err_V_Ac') != ''){
				echo $this->mysession->get('Err_V_Ac');
				$this->mysession->delete('Err_V_Ac');
			} 
			?></span></td>
	<td class="td-10"><?php echo form_label('Pickup');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'pick_up','class'=>'form-control','id'=>'pick_up','placeholder'=>'','value'=>@$header['pick_up_location'])).form_input(array('name'=>'pick_up_lat','class'=>'form-control hide-me','id'=>'pick_up_lat','placeholder'=>'','value'=>@$pick_up_lat)).form_input(array('name'=>'pick_up_lng','class'=>'form-control hide-me','id'=>'pick_up_lng','placeholder'=>'','value'=>@$pick_up_lng));?></td>
	</tr>

	<tr>
	<td class="td-10"><?php echo form_label('Contact');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'customer_contact','class'=>'form-control','id'=>'customer_contact','value'=>@$header['customer_mobile']));?>
	<?php echo  $this->form_functions->form_error_session('customer_contact','<p class="text-red">', '</p>');?>
	<div><?php 
	if(isset($header['customer_id'])){ 
			$customer_id=$header['customer_id'];
			}else{ 
			$customer_id=gINVALID;
			}
	echo form_input(array('name'=>'customer_id','id'=>'customer_id','class'=>'form-control hide-me','value'=>$customer_id));
	?></div></td>
	<td class="td-10"><?php echo form_label('Time');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'pick_up_time','class'=>'form-control ','id'=>'tour-pickuptimepicker','value'=>@$header['pick_up_time']));?></td>
	<td class="td-10"><?php echo form_label('Vehicle Model');?></td>
	<td class="td-15"><?php $class="form-control";
		  $msg="Select";
		  $name="vehicle_model_id";
		  echo $this->form_functions->populate_dropdown($name,$vehicle_models,@$header['vehicle_model_id'],$class,$id='vehicle_model',$msg);?>
		  <span class="text-red"><?php
			if($this->mysession->get('Err_Vmodel') != ''){
				echo $this->mysession->get('Err_Vmodel');
				$this->mysession->delete('Err_Vmodel');
			 }
		 ?></span>
	</td>
	<td class="td-10"><?php echo form_label('Drop');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'drop','class'=>'form-control','id'=>'drop','placeholder'=>'','value'=>@$header['drop_location'])).form_input(array('name'=>'drop_lat','class'=>'form-control hide-me','id'=>'drop_lat','placeholder'=>'','value'=>@$drop_lat)).form_input(array('name'=>'drop_lng','class'=>'form-control hide-me','id'=>'drop_lng','placeholder'=>'','value'=>@$drop_lng));?></td>
	</tr>

	<tr>
	<td class="td-10"><?php echo form_label('Guest');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'guest_name','class'=>'form-control','id'=>'guest_name','value'=>@$header['guest_name']));?></td>
	<td class="td-10"><?php echo form_label('To Date');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'drop_date','class'=>'form-control  mandatory','id'=>'tour-dropdatepicker','value'=>@$header['drop_date']));?>
	<?php echo  $this->form_functions->form_error_session('drop_date','<p class="text-red">', '</p>');?></td>
	<td class="td-10"><?php echo form_label('Vehicle No.');?></td>
	<td class="td-15"><?php $class="form-control vehicle-list";
		  $id="vehicle_id";
		  $msg=' ';
	echo $this->form_functions->populate_editable_dropdown('vehicle_id', @$available_vehicles,$class,'vehicles',array(),$msg,@$header['vehicle_id'],$id);?><span class="text-red"><?php
							if($this->mysession->get('Err_reg_num') != ''){
								echo $this->mysession->get('Err_reg_num');
								$this->mysession->delete('Err_reg_num');
							} 
						
						?></span></td>
	<td class="td-10"><?php echo form_label('PAX');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'pax','class'=>'form-control','id'=>'pax','value'=>@$header['pax']));?></td>
	</tr>

	<tr>
	<td class="td-10"><?php echo form_label('Contact');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'guest_contact','class'=>'form-control','id'=>'guest_contact','value'=>@$header['guest_mobile'])); echo  $this->form_functions->form_error_session('guest_contact','<p class="text-red">', '</p>');?>
	<div><?php 
	
	if(isset($header['guest_id'])){
			$guest_id=$header['guest_id'];
			}else{
			$guest_id=gINVALID;
			}
	echo form_input(array('name'=>'guest_id','id'=>'guest_id','class'=>'form-control hide-me','value'=>$guest_id));
	?></div></td>
	<td class="td-10"><?php echo form_label('Time');?></td>
	<td class="td-15"><?php echo form_input(array('name'=>'drop_time','class'=>'form-control ','id'=>'tour-droptimepicker','value'=>@$header['drop_time']));?></td>
	<td class="td-10"><?php echo form_label('Driver');?></td>
	<td class="td-15"><?php $class="form-control";
		  $msg=' ';

		  echo $this->form_functions->populate_editable_dropdown('driver_id', @$driver_availability,$class,'drivers',array(),$msg,@$header['driver_id']);?></td>
	<td class="td-10"><?php echo " ";?></td>
	<td class="td-15"><div class="tour-advanced-container"><?php echo form_checkbox(array('name'=>'advanced-option','id'=>'advanced-check-box','class'=>'advanced-check-box flat-red','checked'=>@$header['advanced_option'])).nbs(4);?>
	<?php echo form_label('Advanced');?></div></td>
	</tr>
	<tbody style="display:none;" class="tbody-toggle">
	<tr>
	<td class="td-10"><?php echo form_checkbox(array('name'=> 'vehicle_beacon_light_option_id','class'=>'beacon-light-chek-box flat-red','checked'=>@$header['vehicle_beacon_light_option_id']));
		  ?></td>
	<td class="td-15"><?php echo form_label('Red Beacon Light');?></td>
	<td class="td-10"><?php echo form_checkbox(array('name'=> 'pluckcard','class'=>'pluckcard-chek-box flat-red','checked'=>@$header['pluckcard']));?></td>
	<td class="td-15"><?php echo form_label('Placard');?></td>
	<td class="td-10"><?php echo form_checkbox(array('name'=> 'uniform','class'=>'uniform-chek-box flat-red','checked'=>@$header['uniform']));?></td>
	<td class="td-15"><?php echo form_label('Uniform');?></td>
	<td class="td-10"><?php echo form_label('Languages');?></td>
	<td class="td-15"><?php $class="form-control";
		  $msg="Select";
		  $name="driver_language_id";
	echo $this->form_functions->populate_dropdown($name,@$languages,@$header['driver_language_id'],$class,$id='',$msg);?>
	</td>
	</tr>
	</tbody>
	<table><tr><td>
		<?php if(isset($trip_id) && $trip_id>0){
			$save="UPDATE";
		}else{

			$save="ADD";
		}?>

		<button class="btn btn-success book-tour-validate" type="button" enable_redirect='false'>
		<?php echo $save; ?></button>
		
		
		
		<div class="hide-me">
		<?php $save_update_button='SAVE';
			$class_save_update_button="class='btn btn-success trip-save-update'";

			echo form_submit("trip-add",$save_update_button,$class_save_update_button).nbs(7);
		?>
		</div>
		
	</td></tr></table>
	</table>

	</fieldset> <?php }?>
	

	
<?php 
	echo form_close(); 
?>

<!--itinerary table starts here ,table illed by jquery-->

<div class="box-body table-responsive no-padding hide-me" id="itinerary-div">
	<table id="itinerary-tbl" class="table table-hover table-bordered table-with-20-percent-td">
		<?php 
			/*if($itrTable){
				echo "<tr>";
				foreach($itrTable['th'] as $td){
					echo "<td ".$td['attr'].">".$td['label']."</td>";
				}
				echo "</tr>";

				foreach($itrTable['tr'] as $tr){
					echo "<tr>";

					foreach($tr as $data){
						echo "<td>".$data."</td>";
					}
					echo "</tr>";
				}
			}*/
		?>
	</table>

	<div class="form-submit-reset-buttons-group">
		<?php 	if($flag=='TA' ||$flag=='TE'){ 
				echo form_open(base_url()."front-desk/tour/save_cart/TA/".$trip_id);
			}elseif($flag=='PA' ||$flag=='PE'){
				echo form_open(base_url()."front-desk/tour/save_cart/PA/".$trip_id);
			}
			echo form_hidden('hid_package','');
			echo form_submit("save-itry","Save","class='btn btn-success hide-me save-itry'");
			echo form_close();
		?>
		<button class="btn btn-success save-itry-btn" type="button">Save</button>
		<button class="btn btn-success tour-estimate" type="button">Estimate</button>
		<?php if($flag=='TE' ||$flag=='PE'){?>
	
		<button class="btn btn-success " type="button" id="show-tab" >
		<?php echo "Add Itinerary" ?></button>
		<?php }?>
	</div>
	
</div>

<br/>
<!--itinerary table ends here-->


<!--itinerary tabs starts-->

<div class="nav-tabs-custom itinerary" id="tab-display">
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
		<div class="<?php echo $tabs['t_tab']['content_class'];?> tour-travel-tab" id="<?php echo $tabs['t_tab']['tab_id'];?>">

			<div class="page-outer">
	   		<fieldset class="body-border">
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php 
					//if(is_numeric($trip_id) && $trip_id > 0){
					if($flag=='TA' || $flag=='TE' ){
						
							?><div class="from-to-date">
							<div class="from-date-field">
								<?php echo form_label(' From Date','travel_date'); 
								echo form_input(array('name'=>'travel_date','class'=>'form-control initialize-date-picker  ','id'=>'travel_date','value'=>@$travel_date));
								?>
							</div>
							<div class="to-date-field">
								<?php
								echo form_label(' To Date','travel_date'); 
								echo form_input(array('name'=>'travel_to_date','class'=>'form-control initialize-date-picker  ','id'=>'travel_to_date','value'=>@$travel_to_date));
								?>
							</div>
							</div>
							<?php 
						
					}elseif($flag=='PA' ||$flag=='PE'){
						?><div class="from-to-date"><div class="from-date-field"><?php
							echo form_label('From Day','travel_date'); 
							$name = $id = 'travel_date';$class = 'form-control';
							$msg = "Select Day";
							
							echo $this->form_functions->populate_dropdown($name,$days,@$travel_date,$class,$id,$msg); 
							?></div><div class="to-date-field"><?php 
							echo form_label('To Day','travel_to_date'); 
							$name = $id = 'travel_to_date';$class = 'form-control';
							$msg = "Select Day";
							
							echo $this->form_functions->populate_dropdown($name,$days,@$travel_to_date,$class,$id,$msg); 
						?>  </div></div><?php
					}
					echo $this->form_functions->form_error_session('travel_date', '<p class="text-red">', '</p>'); 
					?>			
				</div>

				</div>
				<div class="row-source-100-percent-width-with-margin-8">

					<div id="div-via-destination">
						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php echo form_label('Destination','destination_id'); 
					    	
						$class="form-control";
						$msg="Select Destination";
						$name = $id = "destination_id";
						echo $this->form_functions->populate_dropdown($name,$destinations,@$destination_id,$class,$id,$msg); 
						echo $this->form_functions->form_error_session('destination_id', '<p class="text-red">', '</p>'); ?>			</div>

						<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php echo form_label('Priority','destination_priority'); 
					    	echo form_input(array('name'=>'destination_priority','class'=>'form-control  ','id'=>'destination_priority','value'=>@$destination_priority));
					
						echo $this->form_functions->form_error_session('priority', '<p class="text-red">', '</p>'); ?>			</div>
					</div>
					<div  class="form-group div-with-20-percent-width-with-margin-10" >
						<?php echo form_label(''); ?><br/>
						<?php echo form_submit("via-add","ADD VIA","class='hide-me btn btn-primary' id='add-travel'");?>
					</div>
				</div>
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-46-percent-width-with-margin-10">
						<?php echo form_label('Description','travel_description');
					    	$input = array('name'=>'travel_description','class'=>'form-control',
								'value'=>@$travel_description,'rows'=>4,'id'=>'travel_description');
						echo form_textarea($input); 
						echo form_error('travel_description', '<p class="text-red">', '</p>'); ?>
					</div>
				</div>
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="box-footer ">
					<?php 
					echo form_input(array('name'=>'destination_section_id','class'=>'form-control hide-me ','id'=>'destination_section_id','value'=>gINVALID));
					echo form_input(array('name'=>'destination_row_id','class'=>'form-control hide-me','id'=>'destination_row_id','value'=>gINVALID));
					echo form_input(array('name'=>'destination_itinerary_id','class'=>'form-control hide-me','id'=>'destination_itinerary_id'));
					echo form_submit("add-travel","Add","class='btn btn-primary' id='add-travel'").nbs(5);
					echo form_submit("delete-travel","Delete","class='btn btn-danger hide-me' id='delete-travel'");
					
					?>
					
					</div>
				</div>
			
			</fieldset>
			</div>
		</div>
		<?php }?>


		<?php if (array_key_exists('a_tab', $tabs)) {?>
		<div class="<?php echo $tabs['a_tab']['content_class'];?> tour-accomodation-tab" id="<?php echo $tabs['a_tab']['tab_id'];?>">

			<div class="page-outer">
	   		<fieldset class="body-border">
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
				
					
					<?php 
					//if(is_numeric($trip_id) && $trip_id > 0){
					if($flag=='TA' || $flag=='TE' ){
						
							?><div class="from-to-date">
							<div class="from-date-field">
								<?php echo form_label(' From Date','accommodation_date'); 
								echo form_input(array('name'=>'accommodation_date','class'=>'form-control initialize-date-picker  ','id'=>'accommodation_date','value'=>@$accommodation_date));
								?>
							</div>
							<div class="to-date-field">
								<?php
								echo form_label(' To Date','accommodation_to_date'); 
								echo form_input(array('name'=>'accommodation_to_date','class'=>'form-control initialize-date-picker  ','id'=>'accommodation_to_date','value'=>@$accommodation_to_date));
								?>
							</div>
							</div>
							<?php 
						
					}elseif($flag=='PA' ||$flag=='PE'){
						?><div class="from-to-date"><div class="from-date-field"><?php
							echo form_label('From Day','accommodation_date'); 
							$name = $id = 'accommodation_date';$class = 'form-control';
							$msg = "Select Day";
							
							echo $this->form_functions->populate_dropdown($name,$days,@$accommodation_date,$class,$id,$msg); 
							?></div><div class="to-date-field"><?php
							echo form_label('To Day','accommodation_to_date'); 
							$name = $id = 'accommodation_to_date';$class = 'form-control';
							$msg = "Select Day";
							
							echo $this->form_functions->populate_dropdown($name,$days,@$accommodation_to_date,$class,$id,$msg); 
						?>  </div></div><?php
					}
					echo $this->form_functions->form_error_session('accommodation_date', '<p class="text-red">', '</p>'); 
					?>
					
					
					
							</div>
				</div>
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Destination','hotel_destination_id'); 
				    	
					$class="form-control";
					$msg="Select Destination";
					$name = $id = "hotel_destination_id";
					echo $this->form_functions->populate_dropdown($name,$destinations,@$hotel_destination_id,$class,$id,$msg); 
					echo $this->form_functions->form_error_session('hotel_destination_id', '<p class="text-red">', '</p>'); ?>		</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Category','hotel_category_id'); 
				    	
					$class="form-control";
					$msg="Select Category";
					$name = $id = "hotel_category_id";
					echo $this->form_functions->populate_dropdown($name,$hotel_categories,@$hotel_category_id,$class,$id,$msg); 
					echo $this->form_functions->form_error_session('hotel_category_id', '<p class="text-red">', '</p>'); ?>		</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Hotel','hotel_id'); 
				    	
					$class="form-control";
					$msg="Select Hotel";
					$name = $id = "hotel_id";
					echo $this->form_functions->populate_dropdown($name,null,@$hotel_id,$class,$id,$msg); 
					echo $this->form_functions->form_error_session('hotel_id', '<p class="text-red">', '</p>'); ?>			</div>
				</div>

				<div class="row-source-100-percent-width-with-margin-8">

					<div class="div-with-20-percent-width-with-margin-10">
						<div class="form-group">
						<?php echo form_label('Room Type','room_type_id'); 
					    	
						$class="form-control";
						$msg="Select Room Type";
						$name = $id = "room_type_id";
						echo $this->form_functions->populate_dropdown($name,null,@$room_type_id,$class,$id,$msg); 
						echo $this->form_functions->form_error_session('room_type_id', '<p class="text-red">', '</p>'); ?>			</div>

						<div class="form-group">
						<?php echo form_label('Room Quantity','room_quantity');
						echo form_input(array('name'=>'room_quantity','class'=>'form-control','id'=>'room_quantity','value'=>@$room_quantity));
						?>
						</div>

					</div>

					
					<div class="div-with-20-percent-width-with-margin-10">

						<div class="form-group">
						<?php 
						echo form_label('Room Attributes');
						$class="form-control";
						$msg="Room Attributes";
						$name="room_attributes";
						echo $this->form_functions->populate_multiselect($name,$room_attributes,@$accomodation['room_attributes'],$class,$id='room_attributes',$msg)?>
						</div>

					</div>
					<?php if($meals_options){?>
					<div class="div-with-20-percent-width-with-margin-10">

						<div class="form-group">
						<?php echo form_label('Meals Package','meals_package'); 
						foreach($meals_options as $meals_id=>$meals_name){
							$data = array(
						    'name'        => 'meals_package[]',
						    'id'          => 'meals_package'.$meals_id,
						    'value'       => $meals_id,
						    'style'       => 'margin:10px',
						    'class'	  =>'meals_package',
						    );
						echo br();
						echo form_checkbox($data).nbs(2).$meals_name;
						}
					
						?>
						</div>

						<div class="form-group">
						<?php echo form_label('Meals Quantity','meals_quantity');
						echo form_input(array('name'=>'meals_quantity','class'=>'form-control','id'=>'meals_quantity','value'=>@$meals_quantity));
						?>
						</div>
					</div>
					<?php }?>

				</div>

				<div class="row-source-100-percent-width-with-margin-8">
					<div class="box-footer ">
					<?php 
					echo form_input(array('name'=>'accommodation_section_id','class'=>'form-control hide-me','id'=>'accommodation_section_id','value'=>gINVALID));
					echo form_input(array('name'=>'accommodation_row_id','class'=>'form-control hide-me','id'=>'accommodation_row_id','value'=>gINVALID));
					echo form_input(array('name'=>'accommodation_itinerary_id','class'=>'form-control hide-me','id'=>'accommodation_itinerary_id'));
					echo form_submit("add-accommodation","Add","class='btn btn-primary' id='add-accommodation'").nbs(5);
					echo form_submit("delete-accommodation","Delete","class='btn btn-danger hide-me' id='delete-accommodation'");?>
					</div>
				</div>

			</fieldset>
			</div>
		</div>
		<?php }?>


		<!--services tab contents-->
		<?php if (array_key_exists('s_tab', $tabs)) {?>
		<div class="<?php echo $tabs['s_tab']['content_class'];?> tour-service-tab" id="<?php echo $tabs['s_tab']['tab_id'];?>">

			<div class="page-outer">
	   		<fieldset class="body-border">

				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php 
					if($flag=='TA' || $flag=='TE' ){
						echo form_label('Date','service_date'); 
				    		echo form_input(array('name'=>'service_date','class'=>'form-control initialize-date-picker  ','id'=>'service_date','value'=>@$service_date));
					
					}elseif($flag=='PA' || $flag=='PE' ){
						echo form_label('Day','service_date'); 
						$name = $id = 'service_date';$class = 'form-control';
						$msg = "Select Day";
						
						echo $this->form_functions->populate_dropdown($name,$days,@$service_date,$class,$id,$msg); 
					}
					
					
					echo $this->form_functions->form_error_session('service_date', '<p class="text-red">', '</p>'); ?>			</div>
				</div>
				
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Service','service_id'); 
				    	
					$class="form-control";
					$msg="Select Service";
					$name = $id = "service_id";
					echo $this->form_functions->populate_dropdown($name,$services,@$service_id,$class,$id,$msg); 
					echo $this->form_functions->form_error_session('service_id', '<p class="text-red">', '</p>'); ?>			</div>
					
					<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php echo form_label('Rate','service_rate');
						echo form_input(array('name'=>'service_rate','class'=>'form-control','id'=>'service_rate','value'=>@$service_rate));
						?>
					</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php echo form_label('Location','service_location');
						echo form_input(array('name'=>'service_location','class'=>'form-control','id'=>'service_location','value'=>@$service_location));
						?>
					</div>
					
					<div class="form-group div-with-20-percent-width-with-margin-10">
						<?php echo form_label('Quantity','service_quantity');
						echo form_input(array('name'=>'service_quantity','class'=>'form-control','id'=>'service_quantity','value'=>@$service_quantity));
						?>
					</div>

				</div>


				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-46-percent-width-with-margin-10">
						<?php echo form_label('Description','service_description');
					    	$input = array('name'=>'service_description','class'=>'form-control',
								'value'=>@$service_description,'rows'=>4,'id'=>'service_description');
						echo form_textarea($input); 
						echo form_error('service_description', '<p class="text-red">', '</p>'); ?>
					</div>
				</div>


				<div class="row-source-100-percent-width-with-margin-8">
					<div class="box-footer ">
					<?php 
					echo form_input(array('name'=>'service_section_id','class'=>'form-control hide-me','id'=>'service_section_id','value'=>gINVALID));
					echo form_input(array('name'=>'service_row_id','class'=>'form-control hide-me','id'=>'service_row_id','value'=>gINVALID));
					echo form_input(array('name'=>'service_itinerary_id','class'=>'form-control hide-me','id'=>'service_itinerary_id'));
					echo form_submit("add-service","Add","class='btn btn-primary' id='add-service'").nbs(5);
					echo form_submit("delete-service","Delete","class='btn btn-danger hide-me' id='delete-service'");?>
					</div>
				</div>
				

			</fieldset>
			</div>
		</div>
		<?php }?>

		<!--vehicles tab contents-->
		<?php if (array_key_exists('v_tab', $tabs)) {?>
		<div class="<?php echo $tabs['v_tab']['content_class'];?> tour-vehicle-tab" id="<?php echo $tabs['v_tab']['tab_id'];?>">

			<div class="page-outer">
	   		<fieldset class="body-border">
				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php 
					
					if($flag=='TA' || $flag=='TE' ){
						
							?><div class="from-to-date">
							<div class="from-date-field">
								<?php echo form_label(' From Date','vehicle_date'); 
								echo form_input(array('name'=>'vehicle_date','class'=>'form-control initialize-date-picker  ','id'=>'vehicle_date','value'=>@$vehicle_date));
								?>
							</div>
							<div class="to-date-field">
								<?php
								echo form_label(' To Date','vehicle_to_date'); 
								echo form_input(array('name'=>'vehicle_to_date','class'=>'form-control initialize-date-picker  ','id'=>'vehicle_to_date','value'=>@$vehicle_to_date));
								?>
							</div>
							</div>
							<?php 
						
					}elseif($flag=='PA' ||$flag=='PE'){
						?><div class="from-to-date"><div class="from-date-field"><?php
							echo form_label('From Day','vehicle_date'); 
							$name = $id = 'vehicle_date';$class = 'form-control';
							$msg = "Select Day";
							
							echo $this->form_functions->populate_dropdown($name,$days,@$vehicle_date,$class,$id,$msg); 
							?></div><div class="to-date-field"><?php
							echo form_label('To Day','vehicle_to_date'); 
							$name = $id = 'vehicle_to_date';$class = 'form-control';
							$msg = "Select Day";
							
							echo $this->form_functions->populate_dropdown($name,$days,@$vehicle_to_date,$class,$id,$msg); 
						?>  </div></div><?php
					}
					echo $this->form_functions->form_error_session('vehicle_date', '<p class="text-red">', '</p>');
					
					 ?>			</div>
				</div>


				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Vehicle Type','vehicle_type_id'); 
				    	
					$class="form-control";
					$msg="Select Vehicle Type";
					$name = $id = "vehicle_type_id";
					echo $this->form_functions->populate_dropdown($name,$vehicle_types,@$vehicle_type_id,$class,$id,$msg); 
					echo $this->form_functions->form_error_session('vehicle_type_id', '<p class="text-red">', '</p>'); ?>		</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Vehicle AC Type','vehicle_ac_type_id'); 
				    	
					$class="form-control";
					$msg="Select Vehicle AC Type";
					$name = $id = "vehicle_ac_type_id";
					echo $this->form_functions->populate_dropdown($name,$vehicle_ac_types,@$vehicle_ac_type_id,$class,$id,$msg); 
					echo $this->form_functions->form_error_session('vehicle_ac_type_id', '<p class="text-red">', '</p>'); ?>		</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Vehicle Model','vehicle_model_id'); 
				    	
					$class="form-control";
					$msg="Select Vehicle Model";
					$name = $id = "vehicle_model_id";
					echo $this->form_functions->populate_dropdown($name,$vehicle_models,@$vehicle_model_id,$class,$id,$msg); 
					echo $this->form_functions->form_error_session('vehicle_model_id', '<p class="text-red">', '</p>'); ?>		</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Vehicle No','vehicle_id'); 
				    	
					$class="form-control";
					$msg="Select Vehicle";
					$name = $id = "vehicle_id";
					echo $this->form_functions->populate_dropdown($name,$vehicles,@$vehicle_id,$class,$id,$msg); 
					echo $this->form_functions->form_error_session('vehicle_id', '<p class="text-red">', '</p>'); ?>			</div>

					
				</div>

				<div class="row-source-100-percent-width-with-margin-8">
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Driver','driver_id'); 
				    	
					$class="form-control";
					$msg="Select Driver";
					$name = $id = "driver_id";
					echo $this->form_functions->populate_dropdown($name,$available_drivers,@$driver_id,$class,$id,$msg); 
					echo $this->form_functions->form_error_session('driver_id', '<p class="text-red">', '</p>'); ?>			</div>
					
					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Vehicle Contact','vehicle_contact'); 
				    	echo form_input(array('name'=>'vehicle_contact','class'=>'form-control','id'=>'vehicle_contact','value'=>@$vehicle_contact));
					
					echo $this->form_functions->form_error_session('vehicle_contact', '<p class="text-red">', '</p>'); ?>		</div>

					<div class="form-group div-with-20-percent-width-with-margin-10">
					<?php echo form_label('Tariff','vehicle_tariff_id'); 
				    	
					$class="form-control";
					$msg="Select Tariff";
					$name = $id = "vehicle_tariff_id";
					echo $this->form_functions->populate_dropdown($name,null,@$vehicle_tariff_id,$class,$id,$msg); 
					echo $this->form_functions->form_error_session('vehicle_tariff_id', '<p class="text-red">', '</p>'); ?>			</div>
				</div>

				<div class="row-source-100-percent-width-with-margin-8">
					<div class="box-footer ">
					<?php 
					echo form_input(array('name'=>'vehicle_section_id','class'=>'form-control hide-me','id'=>'vehicle_section_id','value'=>gINVALID));
					echo form_input(array('name'=>'vehicle_row_id','class'=>'form-control hide-me','id'=>'vehicle_row_id','value'=>gINVALID));
					echo form_input(array('name'=>'vehicle_itinerary_id','class'=>'form-control hide-me','id'=>'vehicle_itinerary_id'));
					echo form_submit("add-vehicle","Add","class='btn btn-primary' id='add-vehicle'").nbs(5);
					echo form_submit("delete-vehicle","Delete","class='btn btn-danger hide-me' id='delete-vehicle'");?>
					</div>
				</div>
			</fieldset>
			</div>
		</div>
		<?php }?>

	</div>
</div>

<div class="rough-estimate">
<fieldset class="body-border">
<legend class="body-head">Estimate</legend>
	<table class="table table-hover table-bordered table-with-20-percent-td" id="estimate-tbl"> 
	<tr>
	<th style="width:25%">Items</th>
	<th style="width:40%">Particulars</th>
	<th style="width:15%">Unit Amount</th>
	<th style="width:10%">Tax</th>
	<th style="width:10%">Total</th>
	</tr>
	
	
	</table>
</fieldset>
</div>

<!--itinerary tabs ends here-->


</div>
