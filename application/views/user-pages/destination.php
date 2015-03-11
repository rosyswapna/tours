<div class="hotel-destination-body">
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
	<fieldset class="body-border" >
	<legend class="body-head">Destinations</legend>
	<?php echo form_open(base_url()."tour/manage_destination"); ?>
	<div class="div-with-28-percent-width-with-margin-20">
		
	 <div class="form-group">
	 
			<?php echo form_label('Name');
			echo form_input(array('name'=>'dest_name','class'=>'form-control','id'=>'dest_name','placeholder'=>'','value'=>''));?>
	 
	
	</div>
	   <div class="form-group">
			<?php echo form_label('Latitude');
			echo form_input(array('name'=>'dest_lat','class'=>'form-control','id'=>'dest_lat','placeholder'=>'','value'=>''));?>
	   </div>
	
	</div>
	
	<div class="div-with-28-percent-width-with-margin-20">
		
		<div class="form-group">
			<?php echo form_label('Description');
			echo form_input(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'','value'=>''));?>
		</div>
		
		<div class="form-group">
			<?php echo form_label('Longitude');
			echo form_input(array('name'=>'dest_long','class'=>'form-control','id'=>'dest_long','placeholder'=>'','value'=>''));?>
		</div>
		
		<div class="form-group">
			<?php $save_update_button='SAVE';$class_save_update_button="class='btn btn-success'"; $status='DISABLE';
			echo form_submit("destination-add",$save_update_button,$class_save_update_button).nbs(7).form_reset("destination-enable-disable",$status,"class='btn btn-danger'");
			echo form_close();
			?>
		</div>
	
	</div>
	
	<div class="div-with-28-percent-width-with-margin-20">
		
	   <div class="form-group">
		<?php 
		echo form_label('Season');
		$class="form-control";
		$msg="Season";
		$name="seasons";
		echo $this->form_functions->populate_multiselect($name,$business_seasons,$business_seasons_id='',$class,$id='seasons',$msg)?>
	   </div>
   
	</div>
	<?php echo form_close();?>
	</fieldset>
	<?php if(isset($destination_list)){ ?>
	<fieldset class="body-border" >
	<legend class="body-head">List Destinations</legend>
		<div class="box-body table-responsive no-padding">
			
			<table class="table table-hover table-bordered">
				<tbody>
					<tr>
					    <th>Name</th>
					    <th>Latitude</th>
					    <th>Longitude</th>
					    <th>Season</th>
					    <th colspan="2">Action</th>
					</tr>
				<?php foreach($destination_list as $list_val): ?>
					<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style="width:10%"><div  class="tarrif-edit" ><?php echo nbs(5);?><i class="fa fa-edit cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("business-season-edit","Edit","id=tarrif-edit-id","class=btn");?></div></td>
		<td style="width:10%"><div  class="tarrif-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("business-season-delete","Delete","id=tarrif-delete-id","class=btn");?></div></td>
					</tr>
				     <?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</fieldset>
	<?php } ?>
</div>