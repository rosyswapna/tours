<div class="page-outer">
<fieldset class="body-border">
<legend class="body-head">Hotel</legend>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Profile</a></li>
        <li class=""><a href="#tab_2" data-toggle="tab">Owner</a></li>
        <li class=""><a href="#tab_3" data-toggle="tab">Rooms</a></li>
	<li class=""><a href="#tab_4" data-toggle="tab">Tariff</a></li>
	<li class=""><a href="#tab_5" data-toggle="tab">Payment</a></li>
	<li class=""><a href="#tab_6" data-toggle="tab">Accounts</a></li>
      
    </ul>
    
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="hotel-profile-body">
	  
	  <div class="width-30-percent-with-margin-left-20-Hotel-Profile-View">

		<fieldset class="body-border-Driver-View border-style-Driver-view" >
		<legend class="body-head">Profile</legend>
		    <div class="form-group">
				<?php echo form_label('Name'); ?>
				<?php echo form_input(array('name'=>'hotel_name','class'=>'form-control','id'=>'hotel_name','value'=>'')); ?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('Address'); ?>
				<?php echo form_textarea(array('name'=>'hotel_address','rows'=>'4','class'=>'form-control','id'=>'hotel_address','value'=>'')); ?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('City'); ?>
				<?php echo form_input(array('name'=>'city','class'=>'form-control','id'=>'city','value'=>'')); ?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('State'); ?>
				<?php echo form_input(array('name'=>'state','class'=>'form-control','id'=>'state','value'=>'')); ?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('Category'); ?>
				<?php $class="form-control";
				$msg="-Select-";
				$name="category";
			echo $this->form_functions->populate_dropdown($name,$category='',$category_id='',$class,$id='category',$msg); ?>
		   </div>
		    <div class="form-group">
				<?php echo form_label('No.of Rooms'); ?>
				<?php echo form_input(array('name'=>'no','class'=>'form-control','id'=>'no','value'=>'')); ?>
		    </div>
		</fieldset>
		
		<fieldset class="body-border-Driver-View border-style-Driver-view" >
		    <div class="form-group">
				<?php echo form_label('Destination'); ?>
				<?php $class="form-control";
				$msg="-Select-";
				$name="destination";
			echo $this->form_functions->populate_dropdown($name,$destinations='',$destination='',$class,$id='',$msg); ?>
		   </div>
		   <div class="form-group">
			<?php  echo form_open(base_url()."controller/action");
			echo form_label('Season');
			$class="form-control";
			$msg="-Select Season-";
			$name="seasons[]";
			$seasons=array('1'=>'Season','2'=>'Mid-season','3'=>'Off-season');
			
			echo $this->form_functions->populate_multiselect($name,$seasons,$season_ids=-1,$class,$id='seasons',$msg)?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('Contact Person'); ?>
				<?php echo form_input(array('name'=>'contact_person','class'=>'form-control','id'=>'contact_person','value'=>'')); ?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('Mobile'); ?>
				<?php echo form_input(array('name'=>'mobile','class'=>'form-control','id'=>'mobile','value'=>'')); ?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('Phone'); ?>
				<?php echo form_input(array('name'=>'phone','class'=>'form-control','id'=>'phone','value'=>'')); ?>
		    </div>
		    <div class="form-group">
				<?php echo form_label('Rating'); ?>
				<?php echo form_input(array('name'=>'rating','class'=>'form-control','id'=>'rating','value'=>'')); ?>
		    </div>
		</fieldset>
	  </div>
	  
	  </div>
	</div>
	
        <div class="tab-pane" id="tab_2">
            hi,Its me O
        </div>
        <div class="tab-pane" id="tab_3">
           hi,Its me R
        </div>
	<div class="tab-pane" id="tab_4">
           hi,Its me T
        </div>
	<div class="tab-pane" id="tab_5">
           hi,Its me P
        </div>
	<div class="tab-pane" id="tab_6">
           hi,Its me A
        </div>
    </div>
</div>
</fieldset>
</div>