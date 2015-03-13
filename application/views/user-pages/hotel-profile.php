<div class="page-outer">
<fieldset class="body-border">
<legend class="body-head">Hotel</legend>
<?php echo form_open(base_url()."hotel/manage_hotel_profile"); ?>
<div class="nav-tabs-custom">
  <!--  <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Profile</a></li>
        <li class=""><a href="#tab_2" data-toggle="tab">Owner</a></li>
        <li class=""><a href="#tab_3" data-toggle="tab">Rooms</a></li>
	<li class=""><a href="#tab_4" data-toggle="tab">Tariff</a></li>
	<li class=""><a href="#tab_5" data-toggle="tab">Payment</a></li>
	<li class=""><a href="#tab_6" data-toggle="tab">Accounts</a></li>
      
    </ul>-->
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
    <?php if (array_key_exists('h_tab', $tabs)) {?>
         	<div class="<?php echo $tabs['h_tab']['content_class'];?>" id="<?php echo $tabs['h_tab']['tab_id'];?>">
        <!--<div class="tab-pane active" id="tab_1">-->
          <div class="hotel-profile-body">
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
	  
	  
	  <div class="width-30-percent-with-margin-left-20-Hotel-Profile-View">

		<fieldset class="body-border-Driver-View border-style-Driver-view" >
		<legend class="body-head">Profile</legend>
		    <div class="form-group">
				<?php echo form_label('Name'); ?>
				<?php echo form_input(array('name'=>'hotel_name','class'=>'form-control','id'=>'hotel_name','value'=>@$profile['name'])); 
				echo  $this->form_functions->form_error_session('hotel_name','<p class="text-red">', '</p>');?>
		    
		    </div>
		    <div class="form-group">
				<?php echo form_label('Address'); ?>
				<?php echo form_textarea(array('name'=>'hotel_address','rows'=>'4','class'=>'form-control','id'=>'hotel_address','value'=>@$profile['address'])); 
				echo  $this->form_functions->form_error_session('hotel_address','<p class="text-red">', '</p>');?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('City'); ?>
				<?php echo form_input(array('name'=>'city','class'=>'form-control','id'=>'city','value'=>@$profile['city'])); ?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('State'); ?>
				<?php echo form_input(array('name'=>'state','class'=>'form-control','id'=>'state','value'=>@$profile['state'])); ?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('Hotel Categories'); ?>
				<?php $class="form-control";
				$msg="-Select-";
				$name="category";
			echo $this->form_functions->populate_dropdown($name,$hotel_categories,@$profile['hotel_category_id'],$class,$id='category',$msg); ?>
		   <p class="text-red"><?php
			if($this->mysession->get('Err_category') != ''){
			echo $this->mysession->get('Err_category');
			$this->mysession->delete('Err_category');
			}
	           ?></p>
		   </div>
		    <div class="form-group">
				<?php echo form_label('No.of Rooms'); ?>
				<?php echo form_input(array('name'=>'no_of_rooms','class'=>'form-control','id'=>'no_of_rooms','value'=>@$profile['no_of_rooms']));
				echo  $this->form_functions->form_error_session('no_of_rooms','<p class="text-red">', '</p>');?>
		    </div>
		</fieldset>
		
		</div>
		 <div class="width-30-percent-with-margin-left-20-Hotel-Profile-View" style="margin-top:60px">
		
		<fieldset class="body-border-Driver-View border-style-Driver-view" >
		<legend class="body-head"></legend>
		    <div class="form-group">
				<?php echo form_label('Destination'); ?>
				<?php $class="form-control";
				$msg="-Select-";
				$name="destination";
			echo $this->form_functions->populate_dropdown($name,$destinations,@$profile['destination_id'],$class,$id='',$msg); ?>
		   <p class="text-red"><?php
			if($this->mysession->get('Err_destination') != ''){
			echo $this->mysession->get('Err_destination');
			$this->mysession->delete('Err_destination');
			}
	           ?></p>
		   </div>
		   <div class="form-group">
			<?php 
			echo form_label('Season');
			$class="form-control";
			$msg="Season";
			$name="seasons";
			echo $this->form_functions->populate_multiselect($name,$business_seasons,@$profile['seasons'],$class,$id='seasons',$msg)?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('Contact Person'); ?>
				<?php echo form_input(array('name'=>'contact_person','class'=>'form-control','id'=>'contact_person','value'=>@$profile['contact_person']));
				echo  $this->form_functions->form_error_session('contact_person','<p class="text-red">', '</p>');?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('Mobile'); ?>
				<?php echo form_input(array('name'=>'mobile','class'=>'form-control','id'=>'mobile','value'=>@$profile['mobile'])); 
				echo form_input(array('name'=>'h_mobile','class'=>'hide-me','id'=>'mobile','value'=>@$profile['mobile']));
				echo  $this->form_functions->form_error_session('mobile','<p class="text-red">', '</p>');?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('Phone'); ?>
				<?php echo form_input(array('name'=>'land_line_number','class'=>'form-control','id'=>'land_line_number','value'=>@$profile['land_line_number'])); ?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('Rating'); ?>
				<?php $class="form-control";
				$msg="-Select-";
				$name="rating";
			echo $this->form_functions->populate_dropdown($name,$hotel_ratings,@$profile['hotel_rating_id'],$class,$id='',$msg); ?>
		    </div>
		    <div class="form-group">
			<?php 
			if( isset($profile['id'])&& $profile['id']!=''){
			$save_update_button='UPDATE';
			}else{
			$save_update_button='SAVE';
			}
			$class_save_update_button="class='btn btn-success'";
			echo form_submit("h-profile-add-update",$save_update_button,$class_save_update_button).nbs(7);
			//echo form_reset("customer_reset","RESET","class='btn btn-danger'");
			echo form_input(array('name'=>'id','value'=>@$profile['id'],'class'=>'hide-me'));
			echo form_close();
			?>
		</div>
		</fieldset>
	  </div>
	  
	  </div>
	</div>
	<?php } ?>
	
	<?php if (array_key_exists('o_tab', $tabs)) {?>
         	<div class="<?php echo $tabs['o_tab']['content_class'];?>" id="<?php echo $tabs['o_tab']['tab_id'];?>">
	<?php if($this->session->userdata('O_dbSuccess') != '') { ?>
		<div class="success-message">
			<div class="alert alert-success alert-dismissable">
			<i class="fa fa-check"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?php 
			echo $this->session->userdata('O_dbSuccess');
			$this->session->set_userdata('O_dbSuccess','');
			?>
		   	</div>
		</div>
		<?php  } ?>
            <div class="width-30-percent-with-margin-left-20-Hotel-Profile-View">
	<?php echo form_open(base_url()."hotel/manage_hotel_owner/".$profile['id']); ?>
	<fieldset class="body-border-Driver-View border-style-Driver-view" >
	<legend class="body-head">Owner Details</legend>
	
	<table>
	<tr>
	<td><div class="form-group">
				<?php echo form_label('Name'); ?>
				<?php echo form_input(array('name'=>'owner-name','class'=>'form-control','id'=>'owner-name','value'=>@$owner['name']));
					echo  $this->form_functions->form_error_session('owner-name','<p class="text-red">', '</p>');?>
		    </div></td>
	</tr>
	<td><div class="form-group">
				<?php echo form_label('Mobile'); ?>
				<?php echo form_input(array('name'=>'mob-no','class'=>'form-control','id'=>'mob-no','value'=>@$owner['mobile']));
				echo form_input(array('name'=>'h-mob-no','class'=>'hide-me','id'=>'mob-no','value'=>@$owner['mobile']));
				echo  $this->form_functions->form_error_session('mob-no','<p class="text-red">', '</p>');				?>
		    </div></td>
	</tr>	    
	<td><div class="form-group">
				<?php echo form_label('Email'); ?>
				<?php echo form_input(array('name'=>'mail-id','class'=>'form-control','id'=>'mail-id','value'=>@$owner['email']));
                                echo form_input(array('name'=>'h-mail-id','class'=>'hide-me','id'=>'mail-id','value'=>@$owner['email']));
				echo  $this->form_functions->form_error_session('mail-id','<p class="text-red">', '</p>');?>
		    </div></td>
	</tr>	    
	<td><div class="form-group">
				<?php echo form_label('Username'); ?>
				<?php echo form_input(array('name'=>'username','class'=>'form-control','id'=>'username','value'=>'')); ?>
		    </div></td>
	</tr>	    
	<td><div class="form-group">
				<?php echo form_label('Password'); ?>
				<?php echo form_password(array('name'=>'password','class'=>'form-control','id'=>'password','value'=>'')); ?>
		    </div></td>
	</tr>	    
	<td><div class="form-group">
				<?php echo form_label('Confirm Password'); ?>
				<?php echo form_password(array('name'=>'cpassword','class'=>'form-control','id'=>'cpassword','value'=>@$owner[''])); ?>
		    </div></td>
	</tr>	    
	<td><div class="form-group">
			<?php if( isset($owner['id'])&& $owner['id']!=''){
			$save_update_button='UPDATE';
			}else{
			$save_update_button='SAVE';
			}$class_save_update_button="class='btn btn-success'";
			echo form_submit("h-owner-add-update",$save_update_button,$class_save_update_button).nbs(7);
			// echo form_reset("customer_reset","RESET","class='btn btn-danger'");
			echo form_input(array('name'=>'owner_id','value'=>@$owner['id'],'class'=>'hide-me'));
			echo form_close();
			?>
		</div></td>
	</tr>	
	
	</table>
	</fieldset>
	<?php echo form_close();?>
		</div>
        </div>
	<?php } ?>
	
	<?php if (array_key_exists('r_tab', $tabs)) {?>
         	<div class="<?php echo $tabs['r_tab']['content_class'];?>" id="<?php echo $tabs['r_tab']['tab_id'];?>">
       <!-- <div class="tab-pane" id="tab_3">-->
           <div class="hotel-profile-body">
	   <?php if($this->session->userdata('R_dbSuccess') != '') { ?>
		<div class="success-message">
			<div class="alert alert-success alert-dismissable">
			<i class="fa fa-check"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?php 
			echo $this->session->userdata('R_dbSuccess');
			$this->session->set_userdata('R_dbSuccess','');
			?>
		   	</div>
		</div>
		<?php  } ?>
		<fieldset class="body-border border-style" >
		<legend class="body-head">Add Room Availability</legend>
		<?php echo form_open(base_url()."hotel/manage_hotel_rooms/".$profile['id']); ?>
		<table>
		<tr>
		<td><?php echo form_label('Room Type').nbs(5); ?></td>
		<td><?php $class="form-control";
		$msg="-Select-";
		$name="room_type_id";
		echo $this->form_functions->populate_dropdown($name,$room_types,@$room_type_id,$class,$id='room_type',$msg);?>
		 <p class="text-red"><?php
			if($this->mysession->get('Err_room_type') != ''){
			echo $this->mysession->get('Err_room_type');
			$this->mysession->delete('Err_room_type');
			}?></td>
		<td><?php echo nbs(10);?></td>
		<td><?php echo form_label('Number of Rooms').nbs(5); ?></td>
		<td><?php echo form_input(array('name'=>'no_of_rooms','class'=>'form-control','id'=>'no_of_rooms','value'=>'','style'=>'margin-top:20px')).nbs(5); 
		echo  $this->form_functions->form_error_session('no_of_rooms','<p class="text-red">', '</p>');?></td>
		<td><div  class="tarrif-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("room-add","Add","id=tarrif-add-id","class=btn");?></td>
		</tr>
		</table>
		<?php echo form_close();?>
		</fieldset>
		<?php if(!empty($rooms)){ ?>
		<fieldset class="body-border border-style">
		<legend class="body-head">Manage Room Availability</legend>
		<?php echo br(1);?>
		
		<table>
		<tr style="text-align: center;">
			
			<td><?php echo form_label('Room Type '); ?></td>
			<td><?php echo nbs(10);?></td>
			<td><?php echo form_label('Number of Rooms'); ?></td>
			<td><?php echo nbs(10);?></td>
			<td colspan="2"><?php echo form_label('Action','action'); ?></td>
		</tr>
		
		<?php  foreach($rooms as $values):
		echo form_open(base_url()."hotel/manage_hotel_rooms/".$profile['id']); ?>
		
		<tr>
			<td><?php $class="form-control";
				$msg="-Select-";
				$name="room_type";
				$disabled='disabled';
				echo $this->form_functions->populate_dropdown($name,$room_types,$values['room_type_id'],$class,$id='m_room_type',$msg,$disabled);
				echo form_input(array('name'=>'room_type_id','value'=>$values['room_type_id'],'class'=>'hide-me'));
				?>
				</td>
			<td><?php echo nbs(10);?></td>
			<td><?php echo form_input(array('name'=>'no_of_rooms','class'=>'form-control' ,'id'=>'m_no_room','value'=>$values['no_of_rooms']));?></td>
			<td><?php echo nbs(10);?></td>
			<td><div  class="tarrif-edit" ><?php echo nbs(5);?><i class="fa fa-edit cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("room-edit","Edit","id=tarrif-edit-id","class=btn");?></div></td>
			<td><div  class="tarrif-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=tarrif-delete-id","class=btn");?></div></td>
		</tr>
		
		<?php
		      echo form_close();
		      endforeach;?>
		</table>
		</fieldset>
		<?php }?>
	   </div>
        </div>
	<?php } ?>
	
	<?php if (array_key_exists('t_tab', $tabs)) {?>
         	<div class="<?php echo $tabs['t_tab']['content_class'];?>" id="<?php echo $tabs['t_tab']['tab_id'];?>">
       <!-- <div class="tab-pane" id="tab_4">-->
       <?php $attributes = array(
			'style' => 'margin-top: 15px;');?>
          <div class="hotel-profile-body">
	  
	  <?php if($this->session->userdata('T_dbSuccess') != '') { ?>
		<div class="success-message">
			<div class="alert alert-success alert-dismissable">
			<i class="fa fa-check"></i>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<?php 
			echo $this->session->userdata('T_dbSuccess');
			$this->session->set_userdata('T_dbSuccess','');
			?>
		   	</div>
		</div>
		<?php  } ?>
		<fieldset class="body-border border-style" >
		<legend class="body-head">Add Tariff</legend>
		<table>
		<tbody>
		<?php echo form_open(base_url()."hotel/manage_hotel_rooms_tariff/".$profile['id']); ?>
		<tr><?php $attributes = array(
			'style' => 'margin-top: 15px;',
);		    ?>
		<td><?php echo form_label('Room Type ','room_type',$attributes).nbs(5); ?></td><td><?php $class="form-control";
				$msg="-Select-";
				$name="room_type_id";
				echo $this->form_functions->populate_dropdown($name,$room_types,$room_type_id='',$class,$id='room_type_id',$msg);?></td><td>
		</td>
		<td><?php echo nbs(10);?></td>
		<td><?php echo form_label('Season ','season',$attributes).nbs(5); ?></td><td><?php $class="form-control";
				$msg="-Select-";
				$name="season_id1";
				echo $this->form_functions->populate_dropdown($name,$business_seasons,$season_id='',$class,$id='season_id1',$msg);?></td>
				<td><?php echo nbs(10);?></td>
		<td><?php echo form_label('Amount ','amount',$attributes).nbs(5); ?></td><td><?php echo form_input(array('name'=>'amount1','class'=>'form-control' ,'id'=>'amount1','value'=>''));?></td>

		<td><div  class="room-tariff" style="margin-top: 15px;" ><?php echo nbs(5);?><i class="fa fa-plus-circle cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("room-type-tariff-add","Add","id=room-tariff-id","class=btn");?></td>
		</tr>
	
		<?php echo form_close(); ?>
		<?php echo form_open(base_url()."hotel/manage_hotel_rooms_tariff/".$profile['id']); ?>
		<tr><?php $attributes = array(
			'style' => 'margin-top: 15px;',
);		    ?>
		<td><?php echo form_label('Room Attributes ','room_attributes',$attributes).nbs(5); ?></td><td><?php $class="form-control";
				$msg="-Select-";
				$name="room_attr_id";
				echo $this->form_functions->populate_dropdown($name,$room_attributes,$room_type_id='',$class,$id='room_attr_id',$msg);?></td><td>
		</td>
		<td><?php echo nbs(10);?></td>
		<td><?php echo form_label('Season ','season',$attributes).nbs(5); ?></td><td><?php $class="form-control";
				$msg="-Select-";
				$name="season_id2";
				echo $this->form_functions->populate_dropdown($name,$business_seasons,$season_id='',$class,$id='season_id2',$msg);?></td>
				<td><?php echo nbs(10);?></td>
		<td><?php echo form_label('Amount ','amount',$attributes).nbs(5); ?></td><td><?php echo form_input(array('name'=>'amount2','class'=>'form-control' ,'id'=>'t_amount','value'=>''));?></td>

		<td><div  class="attribute-tariff" style="margin-top: 15px;" ><?php echo nbs(5);?><i class="fa fa-plus-circle cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("attr-tariff-add","Add","id=attribute-tariff-id","class=btn");?></td>
		</tr>
		<?php echo form_close(); ?>
		<?php echo form_open(base_url()."hotel/manage_hotel_rooms_tariff/".$profile['id']); ?>
		<tr><?php $attributes = array(
			'style' => 'margin-top: 15px;',
);		    ?>
		<td><?php echo form_label('Meals Package ','meals_package',$attributes).nbs(5); ?></td><td><?php $class="form-control";
				$msg="-Select-";
				$name="meals_package_id";
				echo $this->form_functions->populate_dropdown($name,$meals_options,$room_type_id='',$class,$id='meals_package_id',$msg);?></td><td>
		</td>
		<td><?php echo nbs(10);?></td>
		<td><?php echo form_label('Season ','season',$attributes).nbs(5); ?></td><td><?php $class="form-control";
				$msg="-Select-";
				$name="season_id3";
				echo $this->form_functions->populate_dropdown($name,$business_seasons,$season_id='',$class,$id='season_id3',$msg);?></td>
				<td><?php echo nbs(10);?></td>
		<td><?php echo form_label('Amount ','amount',$attributes).nbs(5); ?></td><td><?php echo form_input(array('name'=>'amount3','class'=>'form-control' ,'id'=>'amount3','value'=>''));?></td>

		<td><div  class="meals-tariff" style="margin-top: 15px;" ><?php echo nbs(5);?><i class="fa fa-plus-circle cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("meals-tariff-add","Add","id=meals-tariff-id","class=btn");?></td>
		</tr>
		<?php echo form_close(); ?>
		</tbody>
		</table>
		</fieldset>
		
		
	  </div>
        </div>
	<?php } ?>
	
	<?php if (array_key_exists('p_tab', $tabs)) {?>
         	<div class="<?php echo $tabs['p_tab']['content_class'];?>" id="<?php echo $tabs['p_tab']['tab_id'];?>">
       <!-- <div class="tab-pane" id="tab_5">-->
           hi,Its me P
        </div>
	<?php } ?>

	<?php if (array_key_exists('a_tab', $tabs)) {?>
         	<div class="<?php echo $tabs['a_tab']['content_class'];?>" id="<?php echo $tabs['a_tab']['tab_id'];?>">
       <!-- <div class="tab-pane" id="tab_6">-->
           hi,Its me A
        </div>
	<?php } ?>
    </div>
</div>
</fieldset>
</div>