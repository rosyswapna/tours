<?php 


	$customer_id		=	'-1';
	$name				=	'';	
	$dob				=	'';
	$customer_group_id		= 	'';
	$customer_type_id		= 	'';
	
	$email				= 	'';
	$mobile				= 	'';
	$address			= 	'';
	$username			=	'';
	$password			=	'';
	if($this->mysession->get('post')!=NULL || $values!=false){
	
	if($this->mysession->get('post')!=NULL){
	$data						=	$this->mysession->get('post');//print_r($data);
	if(isset($data['customer_id'])){
	$customer_id = $data['customer_id'];
	}
	
	}else if($values!=false){
	$data =$values;
	$customer_id = $data['id'];
	
	}
	$name				=	$data['name'];	
	$dob				=	$data['dob'];
	$customer_group_id		= 	$data['customer_group_id'];
	if($customer_group_id==gINVALID){$customer_group_id		='';}
	$customer_type_id		= 	$data['customer_type_id'];
	if($customer_type_id==gINVALID){$customer_type_id		='';}
	$email				= 	$data['email'];
	$mobile				= 	$data['mobile'];
	$address			= 	$data['address'];
	$username			=	$data['username'];
	$password			=	$data['password'];
	}
	$this->mysession->delete('post');
?>
<div class="page-outer">
	   <fieldset class="body-border">
		<legend class="body-head">Customers</legend>
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
	<?php if (array_key_exists('c_tab', $tabs)) {?>
       <div class="<?php echo $tabs['c_tab']['content_class'];?>" id="<?php echo $tabs['c_tab']['tab_id'];?>">
            		 <div class="profile-body width-80-percent-and-margin-auto">
			<fieldset class="body-border">
   			 <legend class="body-head">Personal Details</legend>
			<div class="div-with-50-percent-width-with-margin-10">
				<?php echo form_open(base_url().'customers/AddUpdate');?>
				
				<div class="form-group">
					<?php echo form_label('Name','namelabel'); ?>
				    <?php $input = array('name'=>'name','class'=>'form-control',
						'placeholder'=>'Enter Name','value'=>$name); 
					if(!$edit_profile)					
					$input['disabled'] ='';
				echo form_input($input);?>
					
					<?php echo $this->form_functions->form_error_session('name', '<p class="text-red">', '</p>'); ?>
				</div>
			
				<div class="form-group">
					<?php echo form_label('Email','emaillabel'); ?>
				    <?php $input = array('name'=>'email',
						'class'=>'form-control',
						'placeholder'=>'Enter email','value'=>$email);
					if(!$edit_profile)					
						$input['disabled'] ='';
					echo form_input($input);
					if($customer_id!='' && $customer_id>0) {  ?>
					<div class="hide-me"> 
					<?php echo form_input(array('name'=>'h_email','class'=>'form-control','value'=>$email)); ?>
					</div>
					<?php } ?>
					<?php echo $this->form_functions->form_error_session('email', '<p class="text-red">', '</p>'); ?>
				</div>
				<div class="form-group">
					<?php echo form_label('Date Of Birth ','doblabel'); ?>
				    <?php $input = array('name'=>'dob',
						'class'=>'form-control initialize-date-picker',
						'placeholder'=>'Enter DOB','value'=>$dob);
					if(!$edit_profile)					
						$input['disabled'] ='';
					echo form_input($input);

					echo $this->form_functions->form_error_session('dob', '<p class="text-red">', '</p>'); ?>
				</div>
				<div class="form-group">
					<?php echo form_label('Phone','phonelabel'); ?>
				    <?php $input = array('name'=>'mobile',
							'class'=>'form-control','placeholder'=>'Enter Phone',
							'value'=>$mobile);
							if(!$edit_profile)					
							$input['disabled'] ='';
						echo form_input($input);
					if($customer_id!='' && $customer_id>0) {  ?>
					<div class="hide-me"> 
					<?php echo form_input(array('name'=>'h_phone','value'=>$mobile)); ?>
					</div>
					<?php } ?>
					<?php echo $this->form_functions->form_error_session('mobile', '<p class="text-red">', '</p>'); ?>
				</div>
			
				<div class="form-group">
					<?php echo form_label('Customer Type','ctypelabel'); 
				   $class="form-control customer-type";
				   $disabled=($edit_profile)?'':'disabled';
					echo $this->form_functions->populate_dropdown('customer_type_id',$customer_types,$customer_type_id,$class,$id='',$msg="Customer type",$disabled);?> 
				</div>
				<div class="form-group">
					<?php echo form_label('Customer Group','cgrouplabel'); ?>
				   <?php echo $this->form_functions->populate_dropdown('customer_group_id',$customer_groups,$customer_group_id,$class ='form-control',$id='',$msg="Groups",$disabled); ?>
					
				</div>
			</div>
			<div class="div-with-50-percent-width-with-margin-10">
				
				<div class="form-group">
					<?php echo form_label('Address','addresslabel'); ?>
				    <?php   $input = array('name'=>'address','class'=>'form-control',
							'placeholder'=>'Enter Address','value'=>$address,'rows'=>4);
						if(!$edit_profile)					
						$input['disabled'] ='';
						echo form_textarea($input); ?>
					<?php echo form_error('address', '<p class="text-red">', '</p>'); ?>
				</div>
				
			<?php if(!$edit_profile){?> 
				<div class="form-group">
				   <?php echo form_label('Username','usernamelabel');
					
					echo form_input(array('name'=>'username','class'=>'form-control','id'=>'username','placeholder'=>'Enter Username','value'=>$username,'readonly'=>'readonly'));
				   ?>
					<div class="hide-me"><?php echo form_input(array('name'=>'h_user','value'=>$username)); ?>
				   
				   </div>
				  <?php echo $this->form_functions->form_error_session('username', '<p class="text-red">', '</p>'); ?>
				</div>
					
				<?php }else{?>
			
				<div class="form-group">
				   <?php echo form_label('Username','usernamelabel');
					
					echo form_input(array('name'=>'username','class'=>'form-control','id'=>'username','placeholder'=>'Enter Username','value'=>$username));
				   ?>	<div class="hide-me"><?php echo form_input(array('name'=>'h_user','value'=>$username)); ?>
				   
				   </div>		
				   <?php echo $this->form_functions->form_error_session('username', '<p class="text-red">', '</p>'); ?>
				</div>

				<div class="form-group">
				   <?php 
				   echo form_label('Password','passwordlabel'); ?>
				   <?php echo form_password(array('name'=>'password','class'=>'form-control','id'=>'password','placeholder'=>'Enter Password','value'=>$password)); 
				   ?><div class="hide-me"><?php echo form_input(array('name'=>'h_pass','value'=>$password)); ?>
				   
				   </div>
				  	<?php echo '<p class="text-red">'.$this->mysession->get('c_pwd_err').'</p>'; ?> 		
					<?php echo $this->form_functions->form_error_session('password', '<p class="text-red">', '</p>'); ?>
				</div>
				<?php if($customer_id!='' && $customer_id>gINVALID){  echo '';}else{?>
				<div class="form-group">
				   <?php echo form_label('Confirm Password','cpasswordlabel'); ?>
				   <?php echo form_password(array('name'=>'cpassword','class'=>'form-control','id'=>'cpassword','placeholder'=>'Enter Confirm password')); ?>			
					<?php echo $this->form_functions->form_error_session('cpassword', '<p class="text-red">', '</p>'); ?>
				</div>
				<?php 
					}
				}?>
		   		<div class="box-footer">
				<?php if($customer_id!='' && $customer_id>gINVALID){ 
				
				$save_update_button='UPDATE';$class_save_update_button="class='btn btn-primary'"; 
				}
				else{
				$save_update_button='SAVE';$class_save_update_button="class='btn btn-success'"; 
				}?>
				<?php if(!$this->session->userdata('customer')){echo form_submit("customer-add-update",$save_update_button,$class_save_update_button).nbs(2).form_reset("customer_reset","RESET","class='btn btn-danger'"); }?> 
				<div class="hide-me"> <?php echo form_input(array('name'=>'customer_id','class'=>'form-control','value'=>$customer_id)); 
				?></div>
			 <?php echo form_close(); ?>
			</div>
			
		 
			</fieldset>
		</div>
        </div>
		<?php }?>
		
		<?php if (array_key_exists('t_tab', $tabs)) {?>
		 <div class="<?php echo $tabs['t_tab']['content_class'];?>" id="<?php echo $tabs['t_tab']['tab_id'];?>">
            <div class="page-outer">
	   <fieldset class="body-border">
		<legend class="body-head">Trip</legend>
		<div class="form-group">
	<div class="box-body table-responsive no-padding">
	
	<?php //for search ?>
	<?php  echo form_open(base_url()."organization/front-desk/customer/".$c_id); ?>
	<table>
	<td><?php echo form_input(array('name'=>'from_pick_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'From Date','value'=>'')); ?></td>
	<td><?php echo form_input(array('name'=>'to_pick_date','class'=>'pickupdatepicker initialize-date-picker form-control' ,'placeholder'=>'To Date','value'=>'')); ?></td>
	<td><?php echo form_submit("cdate_search","Search","class='btn btn-primary'");
				echo form_close();?></td>
	</table>
			<table class="table table-hover table-bordered">
				<tbody>
					<tr>
						<th>SlNo</th>
					    <th>Date</th>
					    <th>Route</th>
						<th>Kilometers</th>
						<th>No Of Days</th>
						<!--<th>Releasing Place</th>-->
						<th>Trip Amount</th>
					    
					</tr>
					<?php	
						$full_tot_km=$total_trip_amount=0;
					if(isset($trips) && $trips!=false){
						for($trip_index=0;$trip_index<count($trips);$trip_index++){
						$tot_km=$trips[$trip_index]['end_km_reading']-$trips[$trip_index]['start_km_reading'];
						$full_tot_km=$full_tot_km+$tot_km;
						$total_trip_amount=$total_trip_amount+$trips[$trip_index]['total_trip_amount'];
						
						
						$date1 = date_create($trips[$trip_index]['pick_up_date'].' '.$trips[$trip_index]['pick_up_time']);
						$date2 = date_create($trips[$trip_index]['drop_date'].' '.$trips[$trip_index]['drop_time']);
						
						$diff= date_diff($date1, $date2);
						$no_of_days=$diff->d;
						if($no_of_days==0){
							$no_of_days='1 Day';
							
						}else{
							$no_of_days.=' Days';
							
						}

						?>
						<tr>
							<td><?php echo $trip_index+1; ?></td>
							<td><?php echo $trips[$trip_index]['pick_up_date']; ?></td>
							<td><?php echo $trips[$trip_index]['pick_up_city'].' to '.$trips[$trip_index]['drop_city']; ?></td>
							<td><?php echo number_format($tot_km,2); ?></td>
							<td><?php echo $no_of_days; ?></td>
							<!--<td><?php //echo $trips[$trip_index]['releasing_place'];?></td>-->
							<td><?php echo number_format($trips[$trip_index]['total_trip_amount'],2); ?></td>
						
						</tr>
						<?php } 
						}					
					?>
					<tr>
					<td>Total</td>
					<td></td>
					<td></td>
					<td><?php echo $full_tot_km; ?></td>
					<td></td>
					<td><?php echo number_format($total_trip_amount,2); ?></td>
					</tr>
					<?php //endforeach;
					//}
					?>
				</tbody>
			</table><?php //echo $page_links;?>
			
			<?php if(!empty($trips)){?>
		<!--	<table class="table table-hover table-bordered">
				<tbody>
				
					<tr style="background:#CCC">
						<th>Heading1</th>
						<th>Heading2</th>
						<th>Heading3</th>
						
					    
					</tr>
					<tr><td>Value1</td><td>Value2</td><td>Value3</td></tr>
					
				</tbody>
			</table>-->
			<?php  }?>
			
			
			
		</div>
</div>
</fieldset>
</div>
        </div>
		<?php }?>
		
		<?php if (array_key_exists('p_tab', $tabs)) {?>
		<div class="<?php echo $tabs['p_tab']['content_class'];?>" id="<?php echo $tabs['p_tab']['tab_id'];?>">
            <iframe src="<?php echo base_url().'account/front_desk/CustomerPayment/C'.$customer_id.'/true';?>" height="600px" width="100%">
		<p>Browser not Support</p>
		</iframe>
        </div>
			<?php }?>
		<?php if (array_key_exists('a_tab', $tabs)) {?>	
		<div class="<?php echo $tabs['a_tab']['content_class'];?>" id="<?php echo $tabs['a_tab']['tab_id'];?>">		
        <iframe src="<?php echo base_url().'account/front_desk/CustomerPaymentInquiry/C'.$customer_id.'/true';?>" height="600px" width="100%">
		<p>Browser not Support</p>
		</iframe>
        </div>
		<?php }?>
		<?php if (array_key_exists('tr_tab', $tabs)) {?>	
		<div class="<?php echo $tabs['tr_tab']['content_class'];?>" id="<?php echo $tabs['tr_tab']['tab_id'];?>">	
		<?php
					if(isset($tariffs) && $tariffs!=false){	?>
      	<fieldset class="body-border">
   			 <legend class="body-head">Customer Tariff Details</legend>
			
			<table class="table table-hover table-bordered">
				<tbody>
					<tr>
						<td>Sl No</td>
						<td>Tariff Master</td>
						<td>Vehicle Models</td>
						<td>Ac type</td>
						<td>From Date</td>
						<td>Rate</td>
						<td>Ad KM Rate</td>
						<td>Ad Hr Rate</td>
						<td>Driver Bata</td>
						<td>Night Halt</td>

					    
					</tr>
					<?php	
					
						for($tariff_index=0;$tariff_index<count($tariffs);$tariff_index++){
						
						?>
						<tr>
							<td><?php echo $tariff_index+1; ?></td>
							<td><?php echo $tariffs[$tariff_index]['title']; ?></td>
							<td><?php echo $tariffs[$tariff_index]['vehicle_model'] ?></td>
							<td><?php echo $tariffs[$tariff_index]['vehicle_ac_type'] ?></td>
							<td><?php echo $tariffs[$tariff_index]['from_date'] ?></td>
							<td><?php echo $tariffs[$tariff_index]['rate'] ?></td>
							<td><?php echo $tariffs[$tariff_index]['additional_kilometer_rate'] ?></td>
							<td><?php echo $tariffs[$tariff_index]['additional_hour_rate'] ?></td>
							<td><?php echo $tariffs[$tariff_index]['driver_bata'] ?></td>
							<td><?php echo $tariffs[$tariff_index]['night_halt'] ?></td>
						
						</tr>
						<?php } 
										
					?>
					
					<?php //endforeach;
					//}
					?>
				</tbody>
			</table>
		</fieldset>
		<?php 	}else{
						echo "<p class='text-red'>No Tariffs Available For this Customer</p>";
				}	
?>
        </div>
		<?php }?>
    </div>
</div>
	
</fieldset>
</div>
