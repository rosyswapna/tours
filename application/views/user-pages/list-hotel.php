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
<?php echo form_open(base_url()."front-desk/hotel/list"); ?>
	<table class="table list-trip-table no-border">
	<tbody>
		<tr>
			
		    	<td>
			<?php echo form_input(array('name'=>'hotel_name','id'=>'hotel_name','class'=>'form-control' ,'placeholder'=>'Hotel Name','value'=>@$hotel_name)); ?>
			</td>
		
			<td>
			<?php $class="form-control";
				  	$id='c_type';
					echo $this->form_functions->populate_dropdown('hotel_rating_id',$hotel_ratings,@$hotel_rating_id,$class,$id,$msg="Hotel Rating");?>
			</td>

			 <td><?php $class="form-control";
				  $id='c_group';
				 
				echo $this->form_functions->populate_dropdown('hotel_category_id',$hotel_categories,@$hotel_category_id,$class,$id,$msg="Hotel Category");?> 
			</td>

		    	<td>
			<?php echo form_submit("hotel_search","Search","class='btn btn-primary'");
echo form_close();?>	</td>

			<td>
			<?php echo form_open(  base_url().'front-desk/hotel/profile');
				echo form_submit("add","Add","class='btn btn-primary'");
				echo form_close(); 
			?>
			</td>
			
			<td>
			<?php echo form_button('print-hotel','Print',"class='btn btn-primary print-hotel'"); ?>
			</td>
			
		</tr>
	</tbody>
</table>
</div>
	


<div class="box-body table-responsive no-padding">
<table class="table table-hover table-bordered table-with-20-percent-td" >
	<tbody>
	<tr>
	<th>Name</th>
	<th>Address</th>
	<th>Ratings</th>
	<th>Categories</th>
	<th>Contact Details</th>
	
	</tr>
<?php if(!empty($hotels)){  
	foreach($hotels as $values):?>
	<tr>
	<td><?php  echo anchor(base_url().'front-desk/hotel/profile/'.$values['id'],$values['name']).br();?><?php echo $values['city'];?> </td>
	<td><?php echo $values['address'];?></td>
	<td><?php echo $values['rating'];?></td>
	<td><?php echo $values['category'];?></td>
	<td><?php echo $values['contact_person'].br().$values['mobile'].br().$values['land_line_number'];?></td>
	</tr>
	<?php endforeach; }?>
	</tbody>
</table>
<?php echo $page_links;?>
</div>
</fieldset>
</div>
