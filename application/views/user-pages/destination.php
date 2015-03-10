<div class="hotel-destination-body">
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
			<?php $save_update_button='SAVE';$class_save_update_button="class='btn btn-success'";
			echo form_submit("destination-add",$save_update_button,$class_save_update_button).nbs(7).form_reset("customer_reset","RESET","class='btn btn-danger'");
			echo form_close();
			?>
		</div>
	
	</div>
	
	<div class="div-with-28-percent-width-with-margin-20">
		
	   <div class="form-group">
		<?php  echo form_open(base_url()."controller/action");
		echo form_label('Season');
		$class="form-control";
		$msg="-Select Season-";
		$name="seasons";
		echo $this->form_functions->populate_multiselect($name,$business_seasons,$business_seasons_id='',$class,$id='seasons',$msg)?>
	   </div>
   
	</div>
	<?php echo form_close();?>
	</fieldset>
	
	<fieldset class="body-border" >
	<legend class="body-head">List Destinations</legend>
		<div class="box-body table-responsive no-padding">
			<?php echo form_open(base_url().'controller_name/admin/front-desk/list/');?>
			<table class="table table-hover table-bordered">
				<tbody>
					<tr>
					    <th>Name</th>
					    <th>Latitude</th>
					    <th>Longitude</th>
					    <th>Season</th>
					    <th colspan="3">Action</th>
					</tr>
					<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>
					<?php $enable_disable_button='Disable';
					echo anchor(base_url().'organization/admin/front-desk/','Edit','class="btn btn-primary"').nbs(3).anchor(base_url().'organization/admin/front-desk/','Delete','class="btn btn-primary"').nbs(3).anchor(base_url().'organization/admin/front-desk/',$enable_disable_button,'class="btn btn-primary"'); ?>
					</td>
					</tr>
				</tbody>
			</table>
		</div>
	</fieldset>
</div>