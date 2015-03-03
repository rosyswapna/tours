
	<?php	if(isset($hsms)){
		}else{
		$hsms='';
		}
	
		if(!isset($org_id) && !isset($user_id) && !isset($status)) {  
		$url='admin/organization/new';
		$page_cap='Add Organization';
		}else{ 
		$url='admin/organization/'.$org_id;
		$page_cap='Update Organization';
		}?>
<div class="new-org-body">
		<fieldset class="body-border">
   			 <legend class="body-head"><?php echo $page_cap; ?></legend>
		<?php echo form_open(base_url().$url);?>
	<div class="div-with-50-percent-width-with-margin-10">
        <div class="form-group">
		   <?php echo form_label('Organization Name');?>
           <?php echo form_input(array('name'=>'name','class'=>'form-control','id'=>'name','placeholder'=>'Enter Organization Name','value'=>$name)); ?>
			<?php if(isset($org_id) && isset($user_id)) {  echo form_hidden('hname',$hname); } ?>
	   <?php echo form_error('name', '<p class="text-red">', '</p>'); ?>
        </div>
	<div class="form-group">
		   <?php echo form_label('First Name');?>
           <?php echo form_input(array('name'=>'fname','class'=>'form-control','id'=>'fname','placeholder'=>'Enter First Name','value'=>$fname)); ?>
	   <?php echo form_error('fname', '<p class="text-red">', '</p>'); ?>
        </div>
	<div class="form-group">
		   <?php echo form_label('Last Name');?>
           <?php echo form_input(array('name'=>'lname','class'=>'form-control','id'=>'lname','placeholder'=>'Enter Last Name','value'=>$lname)); ?>
	  	  <?php echo form_error('lname', '<p class="text-red">', '</p>'); ?>
        </div>
    <div class="form-group">
			<?php echo form_label('Email-ID'); ?>
            <?php echo form_input(array('name'=>'mail','class'=>'form-control','id'=>'mail','placeholder'=>'Enter E-mail ID','value'=>$mail)); ?>
	   <?php if(isset($org_id) && isset($user_id)) {  echo form_hidden('hmail',$hmail); } ?>
	   <?php echo form_error('mail', '<p class="text-red">', '</p>'); ?>
        </div>

	<div class="form-group">
			<?php echo form_label('SMS Gateway'); ?>
            <?php echo form_input(array('name'=>'sms','class'=>'form-control','id'=>'sms','placeholder'=>'Enter SMS Gateway','value'=>$sms)); ?>
			 <?php if(isset($org_id) && isset($user_id)) {  echo form_hidden('hsms',$hsms); } ?>
		<?php echo form_error('sms', '<p class="text-red">', '</p>'); ?>
        </div> 
		<div class="form-group">
			<?php echo form_label('System Email'); ?>
            <?php echo form_input(array('name'=>'sys_mail','class'=>'form-control','id'=>'sys_mail','placeholder'=>'Enter System Email','value'=>$sys_mail)); ?>
			 <?php if(isset($org_id) && isset($user_id)) {  echo form_hidden('hsys_mail',$hsys_mail); } ?>
		<?php echo form_error('sys_mail', '<p class="text-red">', '</p>'); ?>
        </div>
		</div>
		<div class="div-with-50-percent-width-with-margin-10">
	<div class="form-group">
			<?php echo form_label('Username');if(isset($org_id) && isset($user_id) && isset($status)) { 
			echo form_input(array('name'=>'uname','class'=>'form-control','id'=>'uname','placeholder'=>'Enter Username','value'=>$uname,'readonly'=>'readonly'));
		 }else{
			echo form_input(array('name'=>'uname','class'=>'form-control','id'=>'uname','placeholder'=>'Enter Username','value'=>$uname));
		 } ?>
			 <?php echo form_error('uname', '<p class="text-red">', '</p>'); ?>
        </div>
	<?php if(!isset($org_id) && !isset($user_id) && !isset($status)) { ?>
	<div class="form-group">
			<?php echo form_label('Password'); ?>
            <?php echo form_password(array('name'=>'pwd','class'=>'form-control','id'=>'pwd','placeholder'=>'Enter Password','value'=>$pwd)); ?>
	    <?php echo form_error('pwd', '<p class="text-red">', '</p>'); ?>
        </div>
	<div class="form-group">
			<?php echo form_label('Confirm Password'); ?>
            <?php echo form_password(array('name'=>'cpwd','class'=>'form-control','id'=>'cpwd','placeholder'=>'Confirm Password','value'=>$cpwd)); ?>
	    <?php echo form_error('cpwd', '<p class="text-red">', '</p>'); ?>
        </div>
		<?php } ?>
	<div class="form-group">
			<?php echo form_label('Phone Number'); ?>
            <?php echo form_input(array('name'=>'phn','class'=>'form-control','id'=>'phn','placeholder'=>'Enter Phone Number','value'=>$phn)); ?>
			 <?php if(isset($org_id) && isset($user_id)) {  echo form_hidden('hphone',$hphone); } ?>
		<?php echo form_error('phn', '<p class="text-red">', '</p>'); ?>
        </div>
	<div class="form-group">
			<?php echo form_label('Address','addresslabel'); ?>
            <?php echo form_textarea(array('name'=>'addr','class'=>'form-control','placeholder'=>'Enter Address','rows' => '4','value'=>$addr)); ?>
	    <?php echo form_error('addr', '<p class="text-red">', '</p>'); ?>
        </div>
	

		<div class="form-group">
		<?php if(isset($org_id) && isset($user_id) && isset($status)) {  echo form_hidden('user_id',$user_id).form_hidden('org_id',$org_id).form_hidden('status',$status);
		 } ?>
		</div>
   		<div class="box-footer">
		<?php // echo validation_errors();?>
		<?php if(!isset($org_id) && !isset($user_id)) {
		echo form_submit("submit","Save","class='btn btn-primary'");
		 }else {
		if($status==STATUS_ACTIVE){
		$cap_status="Disable";
		}else if($status==STATUS_INACTIVE){
		$cap_status="Enable";
		} 
		 echo form_submit("admin-org-profile-update","Update","class='btn btn-primary'").nbs(3).form_submit("admin-org-profile-status-change",$cap_status,"class='btn btn-primary'");}  ?>  
        </div>
	 <?php echo form_close(); ?>
	</div>
	</fieldset>
</div><!-- body -->
