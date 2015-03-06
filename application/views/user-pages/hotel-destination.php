<div class="hotel-destination-body">
	<fieldset class="body-border" >
	<legend class="body-head">Add Business Season</legend>
	<div class="div-with-50-percent-width-with-margin-10">
	
	 <div class="form-group">
		<?php echo form_label('Season');
		$class="form-control";
		$msg="-Select Season-";
		$name="seasons";
		echo $this->form_functions->populate_dropdown($name,$seasons,$season_id,$class,$id='saesons',$msg)?>
        </div>
	
	</div>
	
	<div class="div-with-50-percent-width-with-margin-10">
	
	</div>
	</fieldset>
</div>