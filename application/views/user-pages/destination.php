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
	 
			<?php echo form_label('Location Name');
			echo form_input(array('name'=>'dest_name','class'=>'form-control','id'=>'dest_name','placeholder'=>'','value'=>$name));
			echo  $this->form_functions->form_error_session('dest_name','<p class="text-red">', '</p>');?>
			
	 
	
	</div>
	   <div class="form-group">
			<?php echo br(1);?>
			<?php echo form_label('Latitude');
			echo form_input(array('name'=>'dest_lat','class'=>'form-control','id'=>'dest_lat','placeholder'=>'','value'=>$latitude));?>
	   </div>
	
	</div>
	
	<div class="div-with-28-percent-width-with-margin-20">
		
		<div class="form-group">
			<?php echo form_label('Description(Itinerary)');
			echo form_textarea(array('name'=>'description','class'=>'form-control','id'=>'description','placeholder'=>'','value'=>$description,'rows'=>1));
			echo  $this->form_functions->form_error_session('description','<p class="text-red">', '</p>');?>
		</div>
		
		<div class="form-group">
			<?php echo form_label('Longitude');
			echo form_input(array('name'=>'dest_long','class'=>'form-control','id'=>'dest_long','placeholder'=>'','value'=>$longitude));?>
		</div>
		
		<div class="form-group"><div class="hide-me"><?php echo form_input(array('name'=>'id','value'=>$id)); ?></div>
			<?php 
			if($id==''){
			$save_update_button='SAVE';
			$btn_name="destination-add";
			$class_save_update_button="class='btn btn-success'"; 
			echo form_submit($btn_name,$save_update_button,$class_save_update_button).nbs(7);
			}else{
			$save_update_button='UPDATE';
			$btn_name="destination-edit";
			$class_save_update_button="class='btn btn-success'"; 
			if($status_id==STATUS_ACTIVE){ 
			$enable_disable="destination-disable";
			$status='DISABLE';
			}else{ 
			$enable_disable="destination-enable";
			$status='ENABLE';
			}
			echo form_submit($btn_name,$save_update_button,$class_save_update_button).nbs(7);
			echo form_submit($enable_disable,$status,"class='btn btn-danger'");
			}
			
			
			
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
		echo $this->form_functions->populate_multiselect($name,$business_seasons,$seasons,$class,$id='seasons',$msg)?>
	   </div>
   
	</div>
	<?php echo form_close();?>
	</fieldset>
	<?php if(!empty($destination_list)){ ?>
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
					<?php foreach($destination_list as $list_val):?>
				<?php echo form_open(base_url()."tour/manage_destination"); ?>
				
					<tr>
					<td><?php echo  $list_val['name'];?></td>
					<td><?php echo  $list_val['lat'];?></td>
					<td><?php echo  $list_val['lng'];?></td>
					<td><?php if($list_val['seasons']==''){echo $seasons_list='All Season';}
					else{ 
					for($i=0;$i<count($list_val['seasons']);$i++){
					echo $business_seasons[$list_val['seasons'][$i]].',';
					}
					}?></td>
					<td style="width:10%"><div  class="tarrif-edit" ><?php echo nbs(5);?><?php echo  anchor(base_url()."front-desk/tour/destination/".$list_val['id'], '<i class="fa fa-edit cursor-pointer"></i>')?><?php echo nbs(5);?></div></td>
					<td style="width:10%"><div  class="tarrif-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("destination-delete","Delete","id=tarrif-delete-id","class=btn");?></div></td>
					
					</tr>
				     
				   <?php echo form_input(array('name'=>'id','value'=>$list_val['id'],'class'=>'hide-me'));echo form_close();?>
				     <?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</fieldset>
	<?php } ?>
</div>