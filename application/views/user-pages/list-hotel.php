<div class="page-outer"> 
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
<fieldset class="body-border">
<legend class="body-head">List Hotels</legend>
<div class="box-body table-responsive no-padding">
<table class="table table-hover table-bordered table-with-20-percent-td" style="width:70%">
	<tbody>
	<tr>
	<th>Name</th>
	<th>Address</th>
	<th>Contact Details</th>
	</tr>
<?php if(!empty($hotels)){  
	foreach($hotels as $values):?>
	<tr>
	<td><?php  echo anchor(base_url().'front-desk/hotel/profile/'.$values['id'],$values['name']).br();?> </td>
	<td><?php echo $values['address'];?></td>
	<td><?php echo $values['mobile'].','.$values['mobile'];?></td>
	</tr>
	<?php endforeach; }?>
	</tbody>
</table>
</div>
</fieldset>
</div>