<div class="business-season-body">
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
	<fieldset class="body-border border-style" >
	<legend class="body-head">Add Business Season</legend>
		<div>
		<table>
		<tr>
		<td><?php echo form_open(base_url()."tour/manage_business_season");  echo form_label("Name","season_name").nbs(7);?> </td>
		<td><?php echo form_input(array('name'=>'season_name','class'=>'form-control','id'=>'season_name','placeholder'=>'','value'=>''));?></td>
		<td><?php echo form_label("Starting","starting").nbs(7);?></td>
		<td><?php echo form_input(array('name'=>'starting','class'=>'fromday-monthpicker form-control' ,'value'=>''));?></td>
		<td><?php echo form_label("Ending","ending").nbs(7);?></td>
		<td><?php echo form_input(array('name'=>'ending','class'=>'fromday-monthpicker form-control' ,'value'=>''));?>
		<?php echo form_input(array('name'=>'id','class'=>'form-control hide-me' ,'value'=>''));?>
		</td>
		<td><div  class="tarrif-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("business-season-add","Add","id=tarrif-add-id","class=btn");?>
		<?php echo form_close();?></td>
		</tr>
		</table>
		</div>
	</fieldset>
	<?php if(isset($season_list)){?>
	<fieldset class="body-border border-style" >
	<legend class="body-head">Manage Business Season</legend>
		<?php echo br();?>
	<table>
	<tr style="text-align: center;">
		<td><?php echo form_label('Name ','name'); ?></td>
		<td><?php echo form_label('Starting ','start'); ?></td>
		<td><?php echo form_label('Ending','end'); ?></td>
		<td colspan="2"><?php echo form_label('Action','action'); ?></td>
		
	</tr>
	<?php foreach($season_list as $value):?>
	<tr>
		<td><?php echo form_input(array('name'=>'season','class'=>'form-control','id'=>'season','placeholder'=>'','value'=>$value['name']));?></td>
		<td><?php echo form_input(array('name'=>'start','class'=>'fromday-monthpicker form-control' ,'id'=>'start','value'=>$value['starting_date']));?></td>
		<td><?php echo form_input(array('name'=>'end','class'=>'fromday-monthpicker form-control' ,'id'=>'end','value'=>$value['ending_date']));?></td>
		<td><div  class="tarrif-edit" ><?php echo nbs(5);?><i class="fa fa-edit cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=tarrif-edit-id","class=btn");?></div></td>
		<td><div  class="tarrif-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=tarrif-delete-id","class=btn");?></div></td>
		<td><div class="hide-me"><?php echo form_input(array('name'=>'id','class'=>'form-control hide-me' ,'value'=>$value['id']));?></div></td>
	</tr>
	<?php endforeach;?>
	</table>
	</fieldset>
	<?php }?>
</div>