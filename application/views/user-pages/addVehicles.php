<?php //print_r($driver);?>

<div class="page-outer">
<fieldset class="body-border">
<legend class="body-head">Manage Vehicles</legend>
	<?php  echo form_open(base_url()."vehicle/");?>
	<div class="nav-tabs-custom">
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
  		<?php if (array_key_exists('v_tab', $tabs)) {?>
         	<div class="<?php echo $tabs['v_tab']['content_class'];?>" id="<?php echo $tabs['v_tab']['tab_id'];?>">
			<?php 
			
			
			if($this->mysession->get('post_all')!=null && $this->mysession->get('post_driver')!=null && $this->mysession->get('post_device')!=null){
				$data=$this->mysession->get('post_all'); 
				$driver_data=$this->mysession->get('post_driver');
				$device_data=$this->mysession->get('post_device');
				$vehicle_id=$this->mysession->get('v_id');
				$ownership =$data['vehicle_ownership_types_id'];
				$vehicle_type=$data['vehicle_type_id'];
				$make=$data['vehicle_make_id'];
				$model=$data['vehicle_model_id'];
				$year=$data['vehicle_manufacturing_year'];
				$ac=$data['vehicle_ac_type_id'];
				$fuel=$data['vehicle_fuel_type_id'];
				$seat=$data['vehicle_seating_capacity_id'];
				$driver_id=$driver_data['driver_id'];
				$from_date=$driver_data['from_date'];
				$device_id=$device_data['device_id'];
				$from_date_device=$device_data['from_date_device'];
				$reg_number=$data['registration_number'];
				$reg_date=$data['registration_date'];
				$eng_num=$data['engine_number'];
				$chases_num=$data['engine_number'];
				$permit=$data['vehicle_permit_type_id']; 
				$permit_date=$data['vehicle_permit_renewal_date'];
				$permit_amount=$data['vehicle_permit_renewal_amount'];
				$tax_amount=$data['tax_renewal_amount'];
				$tax_date=$data['tax_renewal_date'];
				$vehicle_percentages =$data['vehicle_percentages'];
				$driver_percentages =$data['driver_percentages'];				
				$vehicle_percentage =$data['vehicle_percentage'];
				$driver_percentage =$data['driver_percentage'];
				$this->mysession->delete('post_all');
				$this->mysession->delete('post_driver');

				

			}else if(isset($vehicle)&& $vehicle!=null){ 
				$vehicle_id=$vehicle['id'];
			    	$ownership =$vehicle['vehicle_ownership_types_id'];

				if($ownership <= OWNED_VEHICLE){
					$driver_percentages = $vehicle_percentages = '';
				}
				
				$vehicle_percentage =$vehicle['vehicle_percentage'];
				$driver_percentage =$vehicle['driver_percentage'];


				$vehicle_type=$vehicle['vehicle_type_id'];
				$make=$vehicle['vehicle_make_id'];
				$model=$vehicle['vehicle_model_id'];
				$year=$vehicle['vehicle_manufacturing_year'];
				$ac=$vehicle['vehicle_ac_type_id'];
				$fuel=$vehicle['vehicle_fuel_type_id'];
				$seat=$vehicle['vehicle_seating_capacity_id'];
				$driver_id=@$driver['driver_id'];
				$from_date=@$driver['from_date'];
				if(isset($device['device_id'])){
				$device_id=$device['device_id'];
				}
				if(isset($device['from_date'])){
				$from_date_device=$device['from_date'];
				}
				$reg_number=$vehicle['registration_number'];
				$reg_date=$vehicle['registration_date'];
				$eng_num=$vehicle['engine_number'];
				$chases_num=$vehicle['chases_number'];
				$permit=$vehicle['vehicle_permit_type_id'];
				$permit_date=$vehicle['vehicle_permit_renewal_date'];
				$permit_amount=$vehicle['vehicle_permit_renewal_amount'];
				$tax_amount=$vehicle['tax_renewal_amount'];
				$tax_date=$vehicle['tax_renewal_date'];
			}else{
				$vehicle_id=gINVALID;
				$ownership ="";
				$vehicle_type="";
				$make="";
				$model="";
				$year="";
				$ac="";
				$fuel="";
				$seat="";
				$driver_id="";
				$from_date="";
				$device_id="";
				$from_date_device="";
				$reg_number="";
				$reg_date="";
				$eng_num="";
				$chases_num="";
				$permit="";
				$permit_date="";
				$permit_amount="";
				$tax_amount="";
				$tax_date="";
				$driver_percentages = $vehicle_percentages = '';
				$vehicle_percentage = $driver_percentage = '';
			}

			
			?>

		<?php if($this->mysession->get('dbSuccess') != '') { ?>
		<div class="success-message">
			<div class="alert alert-success alert-dismissable">
			<i class="fa fa-check"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
			<?php 
			echo $this->mysession->get('dbSuccess');
			$this->mysession->set('dbSuccess','');
			?>
		   	</div>
		</div>
		<?php  } ?>

		<?php if($this->mysession->get('Err_invalid_add') != ''||$this->mysession->get('Err_tab') != ''){ ?>
		<div class="alert alert-danger alert-dismissable">
                 	<i class="fa fa-ban"></i>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                        <b>Alert!</b><br>
			<?php echo $this->mysession->get('Err_tab').br();
			$this->mysession->delete('Err_tab');
			echo $this->mysession->get('Err_invalid_add');
			$this->mysession->delete('Err_invalid_add');
			?>
                  </div>
		<?php  } ?>

           	<div class="width-30-percent-with-margin-left-20-Driver-View">

		<fieldset class="body-border-Driver-View border-style-Driver-view" >
		<legend class="body-head">Vehicle Details</legend>

		<div class="form-group">
			<?php echo form_label('Vehicle Ownership Type','usernamelabel'); ?>
		     	<?php
			$class="form-control";$id = 'add-vehicle-ownership';
			$msg="Ownership Type";
			$name="ownership";
			if($ownership!=null){
				echo $this->form_functions->populate_dropdown($name,$select['vehicle_ownership_types'],$ownership,$class,$id,$msg);
			}else{
				echo $this->form_functions->populate_dropdown($name,$select['vehicle_ownership_types'],$ownership='',$class,$id,$msg);
			}?>
			<p class="text-red">
			<?php if($this->mysession->get('ownership') != ''){
				echo $this->mysession->get('ownership');
				$this->mysession->delete('ownership');
			} ?>
			</p>
		</div>
	
		<div class="form-group">
		<?php echo form_label('Vehicle Type','usernamelabel'); ?>
		   <?php $class="form-control";
			$msg="Vehicle type";
			$name="vehicle_type";
		if($vehicle_type!=null){
		echo $this->form_functions->populate_dropdown($name,$select['vehicle_types'],$vehicle_type,$class,$id='',$msg);
		}else{
		echo $this->form_functions->populate_dropdown($name,$select['vehicle_types'],$vehicle_type='',$class,$id='',$msg);
		}
	 ?>
		   <p class="text-red"><?php
		if($this->mysession->get('vehicle_type') != ''){
		echo $this->mysession->get('vehicle_type');
		$this->mysession->delete('vehicle_type');
	 }
		?></p>
		</div>

		<div class="form-group">
		<?php echo form_label('Vehicle Make','usernamelabel'); 
		 $class="form-control";
			$msg="Vehicle Make";
			$name="make";
			if($make!=null){
		echo $this->form_functions->populate_dropdown($name,$select['vehicle_makes'],$make,$class,$id='',$msg);
		}else{
		echo $this->form_functions->populate_dropdown($name,$select['vehicle_makes'],$make='',$class,$id='',$msg);
		}?>
		  <p class="text-red"><?php
		if($this->mysession->get('make') != ''){
		echo $this->mysession->get('make');
		$this->mysession->delete('make');
	 }
		?></p>
		   
		</div>

		<div class="form-group">
		<?php echo form_label('Vehicle Model','usernamelabel'); 
		 $class="form-control";
			$msg="Vehicle Model";
			$name="model";
			if($model!=null){
		echo $this->form_functions->populate_dropdown($name,$select['vehicle_models'],$model,$class,$id='',$msg);
		}else{
		echo $this->form_functions->populate_dropdown($name,$select['vehicle_models'],$model='',$class,$id='',$msg);
		}?>
		   <p class="text-red"><?php
		if($this->mysession->get('model') != ''){
		echo $this->mysession->get('model');
		$this->mysession->delete('model');
	 }
		?></p>
        	</div>

		<div class="form-group">
		<?php echo form_label(' Manufacturing Year','usernamelabel'); ?>
		   <?php  echo form_input(array('name'=>'year','class'=>'fromyearpicker form-control' ,'value'=>$year));?>
		   <?php echo $this->form_functions->form_error_session('year', '<p class="text-red">', '</p>'); ?>
		</div>
		<div class="form-group">
		<?php echo form_label('Ac Type','usernamelabel'); ?>
		   <?php $class="form-control";
			$msg="AC Type";
			$name="ac";
			if($ac!=null){
		echo $this->form_functions->populate_dropdown($name,$select['vehicle_ac_types'],$ac,$class,$id='',$msg); 
		}else{
		echo $this->form_functions->populate_dropdown($name,$select['vehicle_ac_types'],$ac='',$class,$id='',$msg); 
		}?>
		   <p class="text-red"><?php
		if($this->mysession->get('ac') != ''){
		echo $this->mysession->get('ac');
		$this->mysession->delete('ac');
	 }
		?></p>
		</div>
	
		<div class="form-group">
		<?php echo form_label(' Fuel Type','usernamelabel'); ?>
		   <?php $class="form-control";
			$msg="Fuel Type";
			$name="fuel";
			if($fuel!=null){
		echo $this->form_functions->populate_dropdown($name,$select['vehicle_fuel_types'],$fuel,$class,$id='',$msg); 
	     }else{
	   echo $this->form_functions->populate_dropdown($name,$select['vehicle_fuel_types'],$fuel='',$class,$id='',$msg);
	}	?>
		   <p class="text-red"><?php
		if($this->mysession->get('fuel') != ''){
		echo $this->mysession->get('fuel');
		$this->mysession->delete('fuel');
	 }
		?></p>
		</div>

		<div class="form-group">
		<?php echo form_label(' Seating Capacity','usernamelabel'); ?>
		   <?php $class="form-control";
			$msg="Seating Capacity";
			$name="seat";
			if($seat!=null){
		echo $this->form_functions->populate_dropdown($name,$select['vehicle_seating_capacity'],$seat,$class,$id='',$msg); 
	}else{
	echo $this->form_functions->populate_dropdown($name,$select['vehicle_seating_capacity'],$seat='',$class,$id='',$msg); 
	}	?>
		 <p class="text-red"><?php
		if($this->mysession->get('seat') != ''){
		echo $this->mysession->get('seat');
		$this->mysession->delete('seat');
	 }
		?></p>   
		</div>

		<div class="form-group">
		<?php echo form_label('Registration Number','usernamelabel'); ?>
           	<?php echo form_input(array('name'=>'reg_number','class'=>'form-control','id'=>'reg_number','value'=>$reg_number)); ?>
	   	<?php echo $this->form_functions->form_error_session('reg_number', '<p class="text-red">', '</p>'); ?>
        	</div>

		<div class="form-group">
		<?php echo form_label('Registration Date','usernamelabel'); ?>
           	<?php echo form_input(array('name'=>'reg_date','class'=>'fromdatepicker form-control' ,'value'=>$reg_date));?>
	   	<?php echo $this->form_functions->form_error_session('reg_date', '<p class="text-red">', '</p>'); ?>
        	</div> <div class="hide-me"><?php echo form_input(array('name'=>'h_reg','value'=>$reg_date));?></div>
		
		<div class="form-group">
		<?php echo form_label('Permit Type','usernamelabel'); ?>
           	<?php $class="form-control";
		$msg="Permit Type";
		$name="permit";
		if($permit!=null){
			echo $this->form_functions->populate_dropdown($name,$select['vehicle_permit_types'],$permit,$class,$id='',$msg); }else{
			echo $this->form_functions->populate_dropdown($name,$select['vehicle_permit_types'],$permit='',$class,$id='',$msg);
		}
		?>
	  
        	</div>
		</fieldset></div>
		
		<div class="width-30-percent-with-margin-left-20-Driver-View">
		<fieldset class="body-border-Driver-View border-style-Driver-view" >
		<legend class="body-head">Other Details</legend>

		<div class="form-group ">
			<?php echo form_label('Vehicle Percentage','vehicle_percentage'); ?>
		     	<?php
			$class="form-control";$id= "add-vehicle-vehicle-percentage";
			$msg="Vehicle Percentage";
			$name="vehicle_percentage";
			
			echo $this->form_functions->populate_dropdown($name,$vehicle_percentages,$vehicle_percentage,$class,$id,$msg);
			?>
			<p class="text-red">
			<?php if($this->mysession->get('vehicle_percentage') != ''){
				echo $this->mysession->get('vehicle_percentage');
				$this->mysession->delete('vehicle_percentage');
			} ?>
			</p>
		</div>
		
		<div class="form-group ">
			<?php echo form_label('Driver Percentage','driver_percentage'); ?>
		     	<?php
			$class="form-control";$id= "add-vehicle-driver-percentage";
			$msg="Driver Percentage";
			$name="driver_percentage";
			
			echo $this->form_functions->populate_dropdown($name,$driver_percentages,$driver_percentage,$class,$id,$msg);
			?>
			<p class="text-red">
			<?php if($this->mysession->get('driver_percentage') != ''){
				echo $this->mysession->get('driver_percentage');
				$this->mysession->delete('driver_percentage');
			} ?>
			</p>
		</div>


		<div class="form-group">
		<?php echo form_label('Permit Renewal Date','usernamelabel'); ?>
		   <?php  echo form_input(array('name'=>'permit_date','class'=>'fromdatepicker form-control' ,'value'=>$permit_date));?>
		   <?php echo $this->form_functions->form_error_session('permit_date', '<p class="text-red">', '</p>'); ?>
		</div>	<div class="hide-me"><?php echo form_input(array('name'=>'h_permit','value'=>$permit_date));?></div>
		<div class="form-group">
		<?php echo form_label('Permit Renewal Amount','usernamelabel'); ?>
		<?php 
		if($permit_amount=='') {
		echo form_input(array('name'=>'permit_amount','class'=>'form-control','id'=>'license_number','value'=>$permit_amount));
		}
		else {
		echo form_input(array('name'=>'permit_amount','class'=>'form-control','id'=>'license_number','value'=>number_format($permit_amount,2)));
		}	?>
		   <?php echo $this->form_functions->form_error_session('permit_amount', '<p class="text-red">', '</p>'); ?>
		<p class="text-red"><?php
		if($this->mysession->get('Err_permit_amt') != ''){
		echo $this->mysession->get('Err_permit_amt');
		$this->mysession->delete('Err_permit_amt');
	 }
		?></p>
		</div>

	<div class="form-group">
		<?php echo form_label('Select Driver','usernamelabel'); ?>
           <?php $class="form-control";
		$msg="Driver";
		$name="driver";
		
		echo $this->form_functions->populate_dropdown($name,$select['drivers'],$driver_id,$class,$id='addDriver',$msg); 
	?>
	     <p class="text-red"><?php
	if($this->mysession->get('Driver') != ''){
	echo $this->mysession->get('Driver');
	$this->mysession->delete('Driver');
 }
	?></p>
        </div>	<div class="hide-me"><?php 
		echo form_input(array('name'=>'hid_driver','value'=>$driver_id));?></div>
		<div class="form-group">
		<?php echo form_label('From Date for Driver','usernamelabel');?>
           <?php //echo form_input(array('name'=>'from_date','class'=>'fromdatepicker form-control' ,'value'=>$from_date));
		    echo form_input(array('name'=>'from_date','class'=>'form-control','id'=>'driverDate','value'=>$from_date,'readonly'=>'readonly'));?>
	   <?php echo $this->form_functions->form_error_session('from_date', '<p class="text-red">', '</p>');

	?>
		<p class="text-red"><?php
 if($this->mysession->get('Err_driver_fdate') != ''){
	echo $this->mysession->get('Err_driver_fdate');
	$this->mysession->delete('Err_driver_fdate');
 }
 if($this->mysession->get('Err_driver_name') != ''){
	echo $this->mysession->get('Err_driver_name');
	$this->mysession->delete('Err_driver_name');
 }
	?></p>
        </div> 	<div class="hide-me"><?php echo form_input(array('name'=>'h_fdate_driver','value'=>$from_date));?></div>
		<div class="form-group">
		<?php echo form_label('Select Device','usernamelabel'); ?>
           <?php $class="form-control";
		$msg="Device";
		$name="device";
		if(isset($device_id)&& $device_id!=null){
			echo $this->form_functions->populate_dropdown($name,$select['devices'],$device_id,$class,$id='addDevice',$msg); 
		}else{
			echo $this->form_functions->populate_dropdown($name,$select['devices'],$device_id='',$class,$id='addDevice',$msg); 
		}	?>
	   <p class="text-red"><?php
	if($this->mysession->get('Device') != ''){
	echo $this->mysession->get('Device');
	$this->mysession->delete('Device');
 }
	?></p>
        </div>
		<div class="hide-me"><?php 
		echo form_input(array('name'=>'hid_device','value'=>$device_id));?></div>
		<div class="form-group">
		<?php echo form_label('From Date for Device','usernamelabel');?>
           <?php //echo form_input(array('name'=>'from_date_device','class'=>'fromdatepicker form-control' ,'value'=>$from_date_device));
		   if(isset($from_date_device)&& $from_date_device!=null){
			 echo form_input(array('name'=>'from_date_device','class'=>'form-control','id'=>'deviceDate','value'=>$from_date_device,'readonly'=>'readonly'));
		   }else{
			$from_date_device='';
			 echo form_input(array('name'=>'from_date_device','class'=>'form-control','id'=>'deviceDate','value'=>$from_date_device,'readonly'=>'readonly'));
			}
		  ?>
	   <?php echo $this->form_functions->form_error_session('from_date_device', '<p class="text-red">', '</p>'); ?>
       <p class="text-red"><?php
	if($this->mysession->get('Err_device_fdate') != ''){
	echo $this->mysession->get('Err_device_fdate');
	$this->mysession->delete('Err_device_fdate');
 }
	if($this->mysession->get('Err_device_name') != ''){
	echo $this->mysession->get('Err_device_name');
	$this->mysession->delete('Err_device_name');
 }
	?></p>
	   </div>	<div class="hide-me"><?php echo form_input(array('name'=>'h_fdate_device','value'=>$from_date_device));?></div>

	<div class="form-group">
	<?php echo form_label('Engine Number','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'eng_num','class'=>'form-control','value'=>$eng_num));?>
	   <?php echo $this->form_functions->form_error_session('eng_num', '<p class="text-red">', '</p>'); ?>
        </div>
        <div class="form-group">
		<?php echo form_label('Chases Number','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'chases_num','class'=>'form-control','id'=>'chases_num','value'=>$chases_num)); ?>
	   <?php echo $this->form_functions->form_error_session('chases_num', '<p class="text-red">', '</p>'); ?>
        </div>
	
	<div class="form-group">
	<?php echo form_label('Tax Renewal Amount ','usernamelabel'); ?>
           <?php  
	   if($tax_amount==""){
	   echo form_input(array('name'=>'tax_amount','class'=>'form-control','id'=>'tax_amount','value'=>$tax_amount));
	   }
	   else{
	   echo form_input(array('name'=>'tax_amount','class'=>'form-control','id'=>'tax_amount','value'=>number_format($tax_amount,2)));
	   }?>
	   <?php echo $this->form_functions->form_error_session('tax_amount', '<p class="text-red">', '</p>'); ?>
        <p class="text-red"><?php
	if($this->mysession->get('Err_tax_amt') != ''){
	echo $this->mysession->get('Err_tax_amt');
	$this->mysession->delete('Err_tax_amt');
 }
	?></p>
		</div>
	<div class="form-group">
	<?php echo form_label('Tax Renewal Date','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'tax_date','class'=>'fromdatepicker form-control' ,'value'=>$tax_date)); ?>
	   <?php echo $this->form_functions->form_error_session('tax_date', '<p class="text-red">', '</p>'); ?>
        </div> <div class="hide-me"><?php echo form_input(array('name'=>'h_tax','value'=>$tax_date));?></div>
	<div class='hide-me'><?php  echo $vehicle_id;
		echo form_input(array('name'=>'hidden_id','class'=>'form-control','value'=>$vehicle_id));?></div>
	
   		<div class="box-footer"><?php echo br();
			if($vehicle_id==gINVALID){
			$btn_name='Save';
		 }else {
			$btn_name='Update';
			}
			echo form_submit("vehicle-submit",$btn_name,"class='btn btn-primary'"); 
			?>
        </div>

	</fieldset>


</div>
        </div>
	<?php } ?>
	
	<?php if (array_key_exists('i_tab', $tabs)) {?>
		<div class="<?php echo $tabs['i_tab']['content_class'];?>" id="<?php echo $tabs['i_tab']['tab_id'];?>">
		
	<?php

			$insurance_id=gINVALID;
			$ins_number ="";
			$ins_date="";
			$ins_renewal_date="";
			$ins_prem_amt="";
			$ins_amt="";
			$ins_agency="";
			$ins_address="";
			$ins_phn="";
			$ins_mail="";
			$ins_web="";
	if($this->mysession->get('ins_post_all')!=null ){ 
	$data=$this->mysession->get('ins_post_all');
			$insurance_id=$this->mysession->get('insurance_id');
            $ins_number =$data['insurance_number'];
			$ins_date=$data['insurance_date'];
			$ins_renewal_date=$data['insurance_renewal_date'];
			$ins_prem_amt=$data['insurance_premium_amount'];
			$ins_amt=$data['insurance_amount'];
			$ins_agency=$data['Insurance_agency'];
			$ins_address=$data['Insurance_agency_address'];
			$ins_phn=$data['Insurance_agency_phone'];
			$ins_mail=$data['Insurance_agency_email'];
			$ins_web=$data['Insurance_agency_web'];
			$this->mysession->delete('ins_post_all');
		}else if(isset($get_insurance)&& $get_insurance!=null){ 
			$insurance_id=$get_insurance['id'];
		    $ins_number =$get_insurance['insurance_number'];
			$ins_date=$get_insurance['insurance_date'];
			$ins_renewal_date=$get_insurance['insurance_renewal_date'];
			$ins_prem_amt=$get_insurance['insurance_premium_amount'];
			$ins_amt=$get_insurance['insurance_amount'];
			$ins_agency=$get_insurance['Insurance_agency'];
			$ins_address=$get_insurance['Insurance_agency_address'];
			$ins_phn=$get_insurance['Insurance_agency_phone'];
			$ins_mail=$get_insurance['Insurance_agency_email'];
			$ins_web=$get_insurance['Insurance_agency_web'];
			}
			
?>

				<?php if($this->mysession->get('ins_Success') != '') { ?>
        <div class="success-message">
			
            <div class="alert alert-success alert-dismissable">
                <i class="fa fa-check"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                <?php 
                echo $this->mysession->get('ins_Success');
                $this->mysession->set('ins_Success','');
                ?>
           </div>
       </div>
       <?php    } ?>
	   

             <div class="width-30-percent-with-margin-left-20-Driver-View insurance ">
			<fieldset class="body-border-Driver-View border-style-Driver-view" >
			<legend class="body-head">Insurance Details</legend>
			
		
			<div class="form-group">
		<?php echo form_label('Insurance Number','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'insurance_number','class'=>'form-control','id'=>'insurance_number','value'=>$ins_number));?>
	   <?php echo $this->form_functions->form_error_session('insurance_number', '<p class="text-red">', '</p>'); ?>
        </div>
		<div class="form-group">
	<?php echo form_label('Insurance Date','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'insurance_date','class'=>'fromdatepicker form-control' ,'value'=>$ins_date)); ?>
	   <?php echo $this->form_functions->form_error_session('insurance_date', '<p class="text-red">', '</p>'); ?>
        </div> <div class="hide-me"><?php echo form_input(array('name'=>'h_ins','value'=>$ins_date));?></div>
		<div class="form-group">
	<?php echo form_label('Insurance Renewal Date','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'insurance_renewal_date','class'=>'fromdatepicker form-control' ,'value'=>$ins_renewal_date)); ?>
	   <?php echo $this->form_functions->form_error_session('insurance_renewal_date', '<p class="text-red">', '</p>'); ?>
        </div>  <div class="hide-me"><?php echo form_input(array('name'=>'h_renew','value'=>$ins_renewal_date));?></div>
		<div class="form-group">
		<?php echo form_label('Insurance Premium Amount','usernamelabel'); ?>
           <?php if($ins_prem_amt==""){
	   echo form_input(array('name'=>'insurance_pre-amount','class'=>'form-control','id'=>'insurance_pre-amount','value'=>$ins_prem_amt)); 
	   }
	   else{
	   echo form_input(array('name'=>'insurance_pre-amount','class'=>'form-control','id'=>'insurance_pre-amount','value'=>number_format($ins_prem_amt,2))); 
	   }?>
	   <?php echo $this->form_functions->form_error_session('insurance_pre-amount', '<p class="text-red">', '</p>'); ?>
         <p class="text-red"><?php
	if($this->mysession->get('Err_insurance_pre_amt') != ''){
	echo $this->mysession->get('Err_insurance_pre_amt');
	$this->mysession->delete('Err_insurance_pre_amt');
 }
	?></p>
		</div>
		<div class="form-group">
		<?php echo form_label('Insurance  Amount','usernamelabel'); ?>
           <?php 
	   if($ins_amt==""){
	   echo form_input(array('name'=>'insurance_amount','class'=>'form-control','id'=>'insurance_amount','value'=>$ins_amt)); 
	   }
	   else{
	    echo form_input(array('name'=>'insurance_amount','class'=>'form-control','id'=>'insurance_amount','value'=>number_format($ins_amt,2))); 
	   }?>
	   <?php echo $this->form_functions->form_error_session('insurance_amount', '<p class="text-red">', '</p>'); ?>
       <p class="text-red"><?php
	if($this->mysession->get('Err_insurance_amt') != ''){
	echo $this->mysession->get('Err_insurance_amt');
	$this->mysession->delete('Err_insurance_amt');
 }
	?></p>  
		</div>
		<div class="form-group">
		<?php echo form_label('Insurance  Agency','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'insurance_agency','class'=>'form-control','id'=>'insurance_agency','value'=>$ins_agency)); ?>
	   <?php echo $this->form_functions->form_error_session('insurance_agency', '<p class="text-red">', '</p>'); ?>
        </div>
		<div class="form-group">
		<?php echo form_label('Insurance Agency Address','usernamelabel'); ?>
           <?php echo form_textarea(array('name'=>'insurance_agency_address','class'=>'form-control','id'=>'insurance_agency_address','value'=>$ins_address,'rows'=>4)); ?>
	   <?php echo $this->form_functions->form_error_session('insurance_agency_address', '<p class="text-red">', '</p>'); ?>
        </div>
		<div class="form-group">
		<?php echo form_label('Insurance Agency Phone','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'insurance_agency_phn','class'=>'form-control','id'=>'insurance_agency_phn','value'=>$ins_phn,'rows'=>4)); ?>
	   <?php echo $this->form_functions->form_error_session('insurance_agency_phn', '<p class="text-red">', '</p>'); ?>
        <div class="hide-me"><?php echo form_input(array('name'=>'hphone','value'=>$ins_phn));?></div>
		</div>
		<div class="form-group">
		<?php echo form_label('Insurance Agency Email','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'insurance_agency_mail','class'=>'form-control','id'=>'insurance_agency_mail','value'=>$ins_mail,'rows'=>4)); ?>
	   <?php echo $this->form_functions->form_error_session('insurance_agency_mail', '<p class="text-red">', '</p>'); ?>
        <div class="hide-me"><?php echo form_input(array('name'=>'hmail','value'=>$ins_mail));?></div>
		</div>
		<div class="form-group">
		<?php echo form_label('Insurance Agency Web','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'insurance_agency_web','class'=>'form-control','id'=>'insurance_agency_web','value'=>$ins_web,'rows'=>4)); ?>
	   <?php echo $this->form_functions->form_error_session('insurance_agency_web', '<p class="text-red">', '</p>');?>
        </div>
		<div class='hide-me'><?php 
		echo form_input(array('name'=>'hidden_ins_id','class'=>'form-control','value'=>$insurance_id));?></div>
			<div class="box-footer"><?php 
			if($insurance_id==gINVALID){
			$btn_name='Save';
		 }else {
			$btn_name='Update';
			}
			echo form_submit("insurance-submit",$btn_name,"class='btn btn-primary'"); 
			?></div>
			</fieldset>
			
			</div>
        </div>
	<?php } ?>
	
	<?php if (array_key_exists('l_tab', $tabs)) {?>
        <div class="<?php echo $tabs['l_tab']['content_class'];?>" id="<?php echo $tabs['l_tab']['tab_id'];?>">
				<?php
		
				
			$loan_id=gINVALID;
			$l_amt ="";
			$l_emi_no="";
			$l_emi_amt="";
			$l_no_paid_emi="";
			$l_payment_date="";
			$l_agency="";
			$l_addrs="";
			$l_phn="";
			$l_mail="";
			$l_web="";
if($this->mysession->get('loan_post_all')!=null ){
		$data=$this->mysession->get('loan_post_all');
			$loan_id=$this->mysession->get('loan_id');
			$l_addrs=$data['loan_agency_address'];
			$l_amt =$data['total_amount'];
			$l_emi_no=$data['number_of_emi'];
			$l_emi_amt=$data['emi_amount'];
			$l_no_paid_emi=$data['number_of_paid_emi'];
			$l_payment_date=$data['emi_payment_date'];
			$l_agency=$data['loan_agency'];
			//$l_address=$data['loan_agency_address'];
			$l_phn=$data['loan_agency_phone'];
			$l_mail=$data['loan_agency_email'];
			$l_web=$data['loan_agency_web'];
		$this->mysession->delete('loan_post_all');
		}else if(isset($get_loan)&& $get_loan!=null){
	
			$loan_id=$get_loan['id'];
			$l_addrs=$get_loan['loan_agency_address'];
			$l_amt =$get_loan['total_amount'];
			$l_emi_no=$get_loan['number_of_emi'];
			$l_emi_amt=$get_loan['emi_amount'];
			$l_no_paid_emi=$get_loan['number_of_paid_emi'];
			$l_payment_date=$get_loan['emi_payment_date'];
			$l_agency=$get_loan['loan_agency'];
			//$l_address=$data['loan_agency_address'];
			$l_phn=$get_loan['loan_agency_phone'];
			$l_mail=$get_loan['loan_agency_email'];
			$l_web=$get_loan['loan_agency_web'];
		}

?>

				<?php if($this->mysession->get('loan_Success') != '') { ?>
        <div class="success-message">
			
            <div class="alert alert-success alert-dismissable">
                <i class="fa fa-check"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                <?php 
                echo $this->mysession->get('loan_Success');
                $this->mysession->set('loan_Success','');
                ?>
           </div>
       </div>
       <?php    } ?>
	   
	  			<?php if($this->mysession->get('Err_invalid_loan_add') != ''){ ?>
	<div class="alert alert-danger alert-dismissable">
                                        <i class="fa fa-ban"></i>
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                                        <b>Alert!</b><br><?php
		echo $this->mysession->get('Err_invalid_loan_add').br();
		$this->mysession->delete('Err_invalid_loan_add');
														
														
										?>
                                    </div>
<?php  } ?>
                      <div class="width-30-percent-with-margin-left-20-Driver-View insurance ">
			<fieldset class="body-border-Driver-View border-style-Driver-view" >
			<legend class="body-head">Loan Details</legend>
				<div class="form-group">
		<?php echo form_label('Total Amount','usernamelabel'); ?>
           <?php 
	   if($l_amt==""){
	   echo form_input(array('name'=>'total_amt','class'=>'form-control','id'=>'total_amt','value'=>$l_amt)); 
	   }
	   else
	   {
	    echo form_input(array('name'=>'total_amt','class'=>'form-control','id'=>'total_amt','value'=>number_format($l_amt,2))); 
	   }?>
	   <?php echo $this->form_functions->form_error_session('total_amt', '<p class="text-red">', '</p>'); ?>
        
		    <p class="text-red"><?php
	if($this->mysession->get('Err_loan_amt') != ''){
	echo $this->mysession->get('Err_loan_amt');
	$this->mysession->delete('Err_loan_amt');
 }
	?></p>
		</div>
		
			<div class="form-group">
		<?php echo form_label('Number of EMI','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'emi_number','class'=>'form-control','id'=>'emi_number','value'=>$l_emi_no)); ?>
	   <?php echo $this->form_functions->form_error_session('emi_number', '<p class="text-red">', '</p>'); ?>
        </div>
		<div class="form-group">
		<?php echo form_label('EMI Amount','usernamelabel'); ?>
           <?php 
	   if($l_emi_amt==""){
	   echo form_input(array('name'=>'emi_amt','class'=>'form-control','id'=>'emi_amt','value'=>$l_emi_amt)); 
	   }
	   else{
	   echo form_input(array('name'=>'emi_amt','class'=>'form-control','id'=>'emi_amt','value'=>number_format($l_emi_amt,2))); 
	   }?>
	   <?php echo $this->form_functions->form_error_session('emi_amt', '<p class="text-red">', '</p>'); ?>
           <p class="text-red"><?php
	if($this->mysession->get('Err_loan_emi_amt') != ''){
	echo $this->mysession->get('Err_loan_emi_amt');
	$this->mysession->delete('Err_loan_emi_amt');
 }
	?></p>
	   </div>
		<div class="form-group">
		<?php echo form_label('Number of Paid EMI','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'no_paid_emi','class'=>'form-control','id'=>'no_paid_emi','value'=>$l_no_paid_emi)); ?>
	   <?php echo $this->form_functions->form_error_session('no_paid_emi', '<p class="text-red">', '</p>'); ?>
        </div>
		<div class="form-group">
	<?php echo form_label('EMI Payment Date','usernamelabel'); ?>
	 <?php echo form_input(array('name'=>'emi_date','class'=>'fromdatepicker form-control' ,'value'=>$l_payment_date)); ?>
	   <?php echo $this->form_functions->form_error_session('emi_date', '<p class="text-red">', '</p>'); ?>
        </div>   <div class="hide-me"><?php echo form_input(array('name'=>'h_emi','value'=>$l_payment_date));?></div>
		
		<div class="form-group">
		<?php echo form_label('Loan Agency','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'loan_agency','class'=>'form-control','id'=>'loan_agency','value'=>$l_agency)); ?>
	   <?php echo $this->form_functions->form_error_session('loan_agency', '<p class="text-red">', '</p>'); ?>
        </div>
		<div class="form-group">
		<?php echo form_label('Loan Agency Address','usernamelabel'); ?>
           <?php echo form_textarea(array('name'=>'loan_agency_address','class'=>'form-control','id'=>'loan_agency_address','value'=>$l_addrs,'rows'=>4)); ?>
	   <?php echo $this->form_functions->form_error_session('loan_agency_address', '<p class="text-red">', '</p>'); ?>
        </div>
		<div class="form-group">
		<?php echo form_label('Loan Agency Phone','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'loan_agency_phn','class'=>'form-control','id'=>'loan_agency_phn','value'=>$l_phn,'rows'=>4)); ?>
	   <?php echo $this->form_functions->form_error_session('loan_agency_phn', '<p class="text-red">', '</p>'); 
	   ?><div class="hide-me"><?php echo form_input(array('name'=>'hphone_ins','value'=>$l_phn));?>
        </div>
		<div class="form-group">
		<?php echo form_label('Loan Agency Email','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'loan_agency_mail','class'=>'form-control','id'=>'loan_agency_mail','value'=>$l_mail,'rows'=>4)); ?>
	   <?php echo $this->form_functions->form_error_session('loan_agency_mail', '<p class="text-red">', '</p>'); ?>
        </div><div class="hide-me"><?php echo form_input(array('name'=>'hmail_ins','value'=>$l_mail));?>
        </div>
		<div class="form-group">
		<?php echo form_label('Loan Agency Web','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'loan_agency_web','class'=>'form-control','id'=>'loan_agency_web','value'=>$l_web,'rows'=>4)); ?>
	   <?php echo $this->form_functions->form_error_session('loan_agency_web', '<p class="text-red">', '</p>');?>
        </div>
		<div class='hide-me'><?php 
		echo form_input(array('name'=>'hidden_loan_id','class'=>'form-control','value'=>$loan_id));?></div>
		<div class="box-footer">
		<?php 
			if($loan_id==gINVALID){
			$btn_name='Save';
		 }else {
			$btn_name='Update';
			}
			echo form_submit("loan-submit",$btn_name,"class='btn btn-primary'"); 
			?>
		</div>
			</fieldset>
			
			</div>
        </div>
	<?php } ?>
	
	<?php if (array_key_exists('o_tab', $tabs)) {?>
		<div class="<?php echo $tabs['o_tab']['content_class'];?>" id="<?php echo $tabs['o_tab']['tab_id'];?>">
	
		<?php 
			$owner_id=gINVALID;
			$own_name='';
			$own_address='';
			$own_mob='';
			$own_mail='';
			$own_dob='';
			$own_user='';
			$own_pas='';
			
if($this->mysession->get('owner_post_all')!=null ){
		$data=$this->mysession->get('owner_post_all');
			$owner_id=$this->mysession->get('owner_id');
			$own_name=$data['name'];
			$own_address=$data['address'];
			$own_mob=$data['mobile'];
			$own_mail=$data['email'];
			$own_dob=$data['dob'];
			$own_user=$data['username'];
			$own_pas=$data['password'];
			$supplier_group=$data['supplier_group'];
		$this->mysession->delete('owner_post_all');

}else if(isset($get_owner)&& $get_owner!=null){
			$owner_id=$get_owner['id'];
			$own_name=$get_owner['name'];
			$own_address=$get_owner['address'];
			$own_mob=$get_owner['mobile'];
			$own_mail=$get_owner['email'];
			$own_dob=$get_owner['dob'];
			$own_user=$get_owner['username'];
			$own_pas=$get_owner['password'];
			$h_pass=$get_owner['password'];
			
	
	}

?>

				<?php if($this->mysession->get('owner_Success') != '') { ?>
        <div class="success-message">
			
            <div class="alert alert-success alert-dismissable">
                <i class="fa fa-check"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                <?php 
                echo $this->mysession->get('owner_Success');
                $this->mysession->set('owner_Success','');
                ?>
           </div>
       </div>
       <?php    } ?>
	   <?php if($this->mysession->get('Err_invalid_owner_add') != ''){ ?>
	<div class="alert alert-danger alert-dismissable">
                                        <i class="fa fa-ban"></i>
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                                        <b>Alert!</b><br><?php
													echo $this->mysession->get('Err_invalid_owner_add').br();
													$this->mysession->delete('Err_invalid_owner_add');
														
												?>
                                    </div>
<?php  } ?>
	   

		                    <div class="width-30-percent-with-margin-left-20-Driver-View insurance ">
			<fieldset class="body-border-Driver-View border-style-Driver-view" >
			<legend class="body-head">Owner Details</legend>
				<div class="form-group">
		<?php echo form_label('Name','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'owner_name','class'=>'form-control','id'=>'total_amt','value'=>$own_name)); ?>
	   <?php echo $this->form_functions->form_error_session('owner_name', '<p class="text-red">', '</p>'); ?>
        </div>

		<div class="form-group">
		<?php echo form_label(' Address','usernamelabel'); ?>
           <?php echo form_textarea(array('name'=>'address','class'=>'form-control','id'=>'address','value'=>$own_address,'rows'=>4)); ?>
	   <?php echo $this->form_functions->form_error_session('address', '<p class="text-red">', '</p>'); ?>
        </div>
		<div class="form-group">
		<?php echo form_label('Mobile','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'mobile','class'=>'form-control','id'=>'mobile','value'=>$own_mob,'rows'=>4)); ?>
	   <?php echo $this->form_functions->form_error_session('mobile', '<p class="text-red">', '</p>'); ?>
        </div><div class="hide-me"><?php echo form_input(array('name'=>'hphone_own','value'=>$own_mob));?>
        </div>
		<div class="form-group">
		<?php echo form_label(' Email','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'mail','class'=>'form-control','id'=>'mail','value'=>$own_mail,'rows'=>4)); ?>
	   <?php echo $this->form_functions->form_error_session('mail', '<p class="text-red">', '</p>'); ?>
        </div><div class="hide-me"><?php echo form_input(array('name'=>'hmail_own','value'=>$own_mail));?>
        </div>
		<div class="form-group">
	<?php echo form_label('Date of Birth','usernamelabel'); ?>
           <?php echo form_input(array('name'=>'dob','class'=>'fromdatepicker form-control' ,'value'=>$own_dob)); ?>
	   <?php echo $this->form_functions->form_error_session('dob', '<p class="text-red">', '</p>'); ?>
        </div>
	
	<div class="form-group">
	<?php echo form_label('Supplier Group','usernamelabel'); ?>
        <?php
			$class="form-control";$id= "add-supplier";
			$msg="Supplier Group";
			$name="supplier_group";
			
			echo $this->form_functions->populate_dropdown($name,$select['supplier_groups'],@$supplier_group,$class,$id,$msg);
			?>
	   <?php echo $this->form_functions->form_error_session('dob', '<p class="text-red">', '</p>'); ?>
        </div>
	
	
	<div class="form-group">
		   <?php echo form_label('Username','usernamelabel');
			
			echo form_input(array('name'=>'username','class'=>'form-control','id'=>'username','placeholder'=>'Enter Username','value'=>$own_user));
		   ?>			
		  <?php echo $this->form_functions->form_error_session('username', '<p class="text-red">', '</p>'); ?>
	<div class="hide-me"><?php echo form_input(array('name'=>'h_user','value'=>$own_user));?></div>		
	</div>
	<div class="form-group">
		   <?php echo form_label('Password','passwordlabel');  ?>
		   <?php echo form_password(array('name'=>'password','class'=>'form-control','id'=>'password','placeholder'=>'Enter Password','value'=>$own_pas)); ?>			
			<?php echo '<p class="text-red">'.$this->mysession->get('v_pwd_err').'</p>'; ?>
			<?php echo $this->form_functions->form_error_session('password', '<p class="text-red">', '</p>'); ?>
	</div>
	<div class="hide-me"><?php echo form_input(array('name'=>'h_pass','value'=>$h_pass)); ?></div>
		 <?php if($owner_id!='' && $owner_id>gINVALID){  echo '';}else{?>
 	<div class="form-group">
 		   <?php echo form_label('Confirm Password','cpasswordlabel'); ?>
 		   <?php echo form_password(array('name'=>'cpassword','class'=>'form-control','id'=>'cpassword','placeholder'=>'Enter Confirm password')); ?>			
 			<?php echo $this->form_functions->form_error_session('cpassword', '<p class="text-red">', '</p>'); ?>
 	</div>
		
		<?php }?>
		<div class='hide-me'><?php 
		echo form_input(array('name'=>'hidden_owner_id','class'=>'form-control','value'=>$owner_id));?></div>
		<div class="box-footer">
		<?php if($owner_id==gINVALID){
			$btn_name='Save';
		 }else {
			$btn_name='Update';
			}
			echo form_submit("owner-submit",$btn_name,"class='btn btn-primary'"); ?>
		</div>
			</fieldset>
			
			
			</div>
		</div>
			<?php echo form_close();?>
			<?php } ?>
			
			<?php if (array_key_exists('t_tab', $tabs)) {?>
				<div class="<?php echo $tabs['t_tab']['content_class'];?>" id="<?php echo $tabs['t_tab']['tab_id'];?>">
					<?php //trip in vehicles ?>
						            <div class="page-outer">
	   <fieldset class="body-border">
		<legend class="body-head">Trip</legend><div class="form-group">
	<div class="box-body table-responsive no-padding"> 
	<?php  echo form_open(base_url().'organization/front-desk/vehicle/'.$ve_id.'/trip'); ?>
	<table>
	<td><?php echo form_input(array('name'=>'from_pick_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'From Date','value'=>'')); ?></td>
	<td><?php echo form_input(array('name'=>'to_pick_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'To Date','value'=>'')); ?></td>
	<td><?php echo form_submit("vdate_search","Search","class='btn btn-primary'");
				echo form_close();?></td>
	</table><br/>			

	
	<?php if($TripTableData){?>
		
			
	<?php  echo form_open(base_url()."account/driver_trip_save"); ?>

	<table class="table table-hover table-bordered">
		<tbody>
		<?php if(isset($TripTableData['theader'])){?>
		<tr style="background:#CCC">
			<?php foreach($TripTableData['theader'] as $thead){?>
			<th><?=$thead;?></th>			
			<?php }?>
		</tr>
		<?php }?>

		<?php foreach($TripTableData['tdata'] as $tr){?>
		<tr>
			<?php foreach($tr as $td){?>
			<td><?=$td;?></td>			
			<?php }?>
		</tr>
		<?php }?>
		
		<?php if(isset($TripTableData['tfooter'])){?>
		<tr style="background:#CCC">
			<?php foreach($TripTableData['tfooter'] as $td){?>
			<td><?php echo $td;?></td>			
			<?php }?>
		</tr>
		<?php }?>

		</tbody>
	</table>

	<?php if($TotalTable){?>
	<table class="table table-hover table-bordered">
		<tbody>

		<?php if(isset($TotalTable['theader'])){?>
		<tr style="background:#CCC">
			<?php foreach($TotalTable['theader'] as $thead){?>
			<?=$thead;?>			
			<?php }?>
		</tr>
		<?php }?>


		<?php foreach($TotalTable['tdata'] as $tr){?>
		<tr>
			<?php foreach($tr as $i=>$td){?>
			<td><?php echo ($i=='label')?$td:number_format($td,2);?></td>			
			<?php }?>
		</tr>
		<?php }?>

		<?php if(isset($TotalTable['tfooter'])){?>
		<tr style="background:#CCC">
			<?php foreach($TotalTable['tfooter'] as $td){?>
			<td><?php echo $td;?></td>			
			<?php }?>
		</tr>
		<?php }?>


		</tbody>
	</table>
	<?php }?>

	<?php }else{?><!--Trip table condition-->
	<div class="msg"> No Data
	</div>
	<?php }?>
	
	
			
			
		</div>
</div>
</fieldset>
</div>
				</div>
			        <?php } ?>
			<?php if (array_key_exists('p_tab', $tabs)) {?>
				<div class="<?php echo $tabs['p_tab']['content_class'];?>" id="<?php echo $tabs['p_tab']['tab_id'];?>">
						<div class="page-outer">
						<iframe src="<?php echo base_url().'account/front_desk/SupplierPayment/VW'.$owner_id.'/true';?>" height="600px" width="100%">
						<p>Browser not Support</p>
						</iframe>
						</div>
				</div>
			<?php } ?>
			
			<?php if (array_key_exists('a_tab', $tabs)) {?>
				<div class="<?php echo $tabs['a_tab']['content_class'];?>" id="<?php echo $tabs['a_tab']['tab_id'];?>">
						<div class="page-outer">
						<iframe src="<?php echo base_url().'account/front_desk/OwnerPaymentInquiry/VW'.$owner_id.'/true';?>" height="600px" width="100%">
						<p>Browser not Support</p>
						</iframe>
						</div>
				</div>
				<?php } ?>
			<?php if (array_key_exists('s_tab', $tabs)) {?>	
				<div class="<?php echo $tabs['s_tab']['content_class'];?>" id="<?php echo $tabs['s_tab']['tab_id'];?>">
				<?php 
				$service_id=gINVALID;
				$s_date='';
				$s_km='';
				$particular='';
				if($this->mysession->get('service_post')!=null){
				
				$sdata=$this->mysession->get('service_post');
				$s_date=$sdata['service_date'];
				$s_km=$sdata['service_km'];
				$particular=$sdata['particulars'];
				$this->mysession->delete('service_post');
				}
				?>
						<div class="page-outer">
						
	<?php if($this->session->userdata('dbSuccess') != '') {?>
        <div class="success-message">
			
            <div class="alert alert-success alert-dismissable">
                <i class="fa fa-check"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
                <?php 
                echo $this->session->userdata('dbSuccess');
                $this->session->set_userdata(array('dbSuccess'=>''));
                ?>
           </div>
       </div>
       <?php    } ?>
       
       <?php if(isset($s_edit)){
      $service_id=$s_edit[0]['id'];
      $s_date=$s_edit[0]['service_date'];
      $s_km=$s_edit[0]['service_km'];
      $particular=$s_edit[0]['particulars'];
       }
       
       ?>
			<div class="width-30-percent-with-margin-left-20-Service-View">
						<fieldset class="body-border border-style" >
						<legend class="body-head">Service Info</legend>
						<div class="form-group">
						<?php 
						echo form_open(base_url()."vehicle");?>
					<table>
					<tr>
					<td>
					<?php  echo form_input(array('name'=>'service_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'Service Date','value'=>$s_date)).br(1); 
					echo $this->form_functions->form_error_session('service_date', '<p class="text-red">', '</p>'); ?></td>
					
					</tr>
					<tr>
					<td><?php //echo form_label('Service Kilometers','s_km'); ?>
					<?php echo form_input(array('name'=>'service_km','class'=>'form-control','id'=>'service_km','placeholder'=>'Service Kilometers','value'=>$s_km)).br(1);
					 echo $this->form_functions->form_error_session('service_km', '<p class="text-red">', '</p>'); ?></td>
					
					</tr>
					<tr>
					<td><?php //echo form_label('Particulars','particular'); ?>
					<?php echo form_textarea(array('name'=>'particulars','class'=>'form-control','id'=>'particulars','placeholder'=>'Particulars','value'=>$particular,'rows'=>'2')).br(1); 
					echo $this->form_functions->form_error_session('particulars', '<p class="text-red">', '</p>'); ?> </td>
					<!--<td><div  class="service-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=service-add-id","class=btn");?></div>
					</td>-->
					</tr>
					<tr>
					<td><?php
					if(isset($s_edit)){
					$btn_name='Update';
					$this->mysession->set('s_id',$s_edit[0]['id']);
					}else {
					$btn_name='Save';
					$this->mysession->set('s_id',gINVALID);
					}
					echo form_submit("service-submit",$btn_name,"class='btn btn-primary'");
					?></td>	
					</tr>
					</table><?php echo form_close();?>
						</fieldset>
		</div>
		
						<?php if(isset($s_list)&& !empty($s_list)){ ?>
		<div class="width-30-percent-with-margin-left-20-Service-View">				
						<fieldset class="body-border border-style" >
						<legend class="body-head">Manage Sevice Info</legend>
						<table>
						<tr><td><?php echo form_label('Service Date','sr_dt').nbs(3); ?></td>
						<td><?php echo form_label('Service Kilometers','sr_km').nbs(3); ?></td></tr>
						<?php foreach ($s_list as $s_val): ?>
						
						<tr><td><?php  echo anchor(base_url().'organization/front-desk/vehicle/'.$this->mysession->get('vehicle_id').'/service/'.$s_val['id'],$s_val['service_date']).nbs(3); ?></td><td><?php echo $s_val['service_km']; ?></td></tr>
						<?php endforeach;?>
						</table>
						</fieldset>
					<?php }?>
					</div>
						</div>
				</div>
				<?php }?>
    </div>
</div>	
	
	
    </div>
</div>
		


</fieldset>
</div>
