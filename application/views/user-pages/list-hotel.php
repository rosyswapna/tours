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
<legend class="body-head">List Vehicles</legend>
<div class="box-body table-responsive no-padding">
<table class="table list-org-table">
	<tbody>
	<tr>
	<th>Name</th>
	<th>Address</th>
	<th>Contact Details</th>
	</tr>
	
	<tr>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	
	</tbody>
</table>
</div>
</fieldset>
</div>