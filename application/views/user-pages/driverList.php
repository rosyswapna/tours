<?php    if($this->session->userdata('dbSuccess') != '') { 
?>

        <div class="success-message">
            <div class="alert alert-success alert-dismissable">
                <i class="fa fa-check"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php 
                echo $this->session->userdata('dbSuccess');
                $this->session->set_userdata(array('dbSuccess'=>''));
                ?>
           </div>
       </div>
       <?php    } ?>
	  <?php 
	  //search?>

<div class="page-outer">    
	<fieldset class="body-border">
		<legend class="body-head">List Drivers</legend>
		<div class="box-body table-responsive no-padding">
			<?php echo form_open(base_url().'organization/front-desk/list-driver');?>
			<table class="table list-org-table">
				<tbody> 
					<tr>
					    <td><?php echo form_input(array('name'=>'driver_name','class'=>'form-control','id'=>'driver_name','placeholder'=>'By Name','size'=>30,'value'=>$driver_name));?> </td>
						<td><?php echo form_input(array('name'=>'driver_city','class'=>'form-control','id'=>'driver_city','placeholder'=>'By City','size'=>30,'value'=>$driver_city));?> </td>
						<td><?php $class="form-control ";
							  $id='status';
							  $status[0]='Available';
							  $status[1]='On-Trip';
							  if(isset($status_id)){
							  $status_id=$status_id;
							  }
							  else{
							   $status_id='';
							  }
						echo $this->form_functions->populate_dropdown('status',$status,$status_id,$class,$id,$msg="Status");?> </td>
						<!--<td><?php// $class="form-control";
						//echo $this->form_functions->populate_dropdown('model',$v_models,$selected='',$class,$id='',$msg='Select Vehicle Model')?> </td>-->
					    
						<td><?php echo form_submit("search","Search","class='btn btn-primary'");?></td>
						
					    <?php echo form_close();?>
						<td><?php echo nbs(55); ?></td>
						<td><?php echo nbs(35); ?></td>
						
						<td><?php echo form_open( base_url().'organization/front-desk/driver-profile');
								  echo form_submit("add","Add","class='btn btn-primary'");
								  echo form_close(); ?></td>
						<td><?php echo form_button('print-driver','Print',"class='btn btn-primary print-driver'"); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="msg"> <?php 
			if (isset($result)){ echo $result;} else {?></div>
	
		
		<div class="box-body table-responsive no-padding driver-list-div">
			<table class="table table-hover table-bordered table-with-20-percent-td">
				<tbody>
					<tr>
					    <th>Driver</th>
					    <th>Contact Details</th>
					    <th>Vehicle Details</th>
						<th>Current Status</th>
						<th> Account Statement</th>
					</tr>
					<?php 
					$attributes=array('class'=>'label-font-style');
					if(isset($values)){ 
					foreach ($values as $det):
					$phone_numbers='';
					?>
					<tr>
					    <td><?php echo anchor(base_url().'organization/front-desk/driver-profile/'.$det['id'],$drivers[$det['id']]['name']).nbs(3);?>
					    <?php if( !isset($drivers[$det['id']]['place_of_birth']) || $drivers[$det['id']]['place_of_birth']==''){ echo '';}else{echo br().$drivers[$det['id']]['place_of_birth'].br();}  ?>
					    </td>
					    <td><?php if( !isset($drivers[$det['id']]['phone']) || $drivers[$det['id']]['phone']==''){ echo '';}else{echo $drivers[$det['id']]['phone'].br();}
								  if( !isset($drivers[$det['id']]['mobile']) || $drivers[$det['id']]['mobile']==''){ echo '';}else{echo $drivers[$det['id']]['mobile'].br();}
								  if( !isset($drivers[$det['id']]['present_address']) || $drivers[$det['id']]['present_address']==''){ echo '';}else{echo $drivers[$det['id']]['present_address'].br();}
								  if( !isset($drivers[$det['id']]['district']) || $drivers[$det['id']]['district']==''){ echo '';}else{echo $drivers[$det['id']]['district'].br();}?></td>	
						<td><?php if( !isset($vehicles[$det['id']]['registration_number']) || $vehicles[$det['id']]['registration_number']==''){ echo '';}else{echo $vehicles[$det['id']]['registration_number'].br();}
						if(!isset($vehicles[$det['id']]['vehicle_model_id']) || $vehicles[$det['id']]['vehicle_model_id']==gINVALID){ echo '';}else{echo $v_models[$vehicles[$det['id']]['vehicle_model_id']].br();}
						if(!isset($vehicles[$det['id']]['vehicle_make_id']) || $vehicles[$det['id']]['vehicle_make_id']==gINVALID){ echo '';}else{echo $v_makes[$vehicles[$det['id']]['vehicle_make_id']];}?></td>
						<td><?php if($driver_statuses[$det['id']]!='Available'){ echo '<span class="label label-info">'.$driver_statuses[$det['id']].'</span>'.br(); }else{ echo '<span class="label label-success">'.$driver_statuses[$det['id']].'</span>'.br(); } if($driver_trips[$det['id']]!=gINVALID){ echo anchor(base_url().'organization/front-desk/trip-booking/'.$driver_trips[$det['id']],'Trip ID :'.$driver_trips[$det['id']]); } else{ echo ''; } ?>
						<?php echo br().form_label('License','license',$attributes).nbs(2);?>: <span><?php if( !isset($drivers[$det['id']]['license_renewal_date']) || $drivers[$det['id']]['license_renewal_date']==''){ echo '';}else{echo $drivers[$det['id']]['license_renewal_date'];}?></span>
						<?php echo br().form_label('Badge','badge',$attributes).nbs(2);?>: <span><?php if( !isset($drivers[$det['id']]['badge_renewal_date']) || $drivers[$det['id']]['badge_renewal_date']==''){ echo '';}else{echo $drivers[$det['id']]['badge_renewal_date'];}?></span>
						</td>
						<td> 
						<div>

						<?php echo form_label('Total Trips','total_trips',$attributes).nbs(2);?> :<?php if( !isset($trip_info[$det['id']]['no_of_trips']) || $trip_info[$det['id']]['no_of_trips']==''){ echo '';}else{echo nbs(2).$trip_info[$det['id']]['no_of_trips'];}?>
						<?php echo br().form_label('Outstanding','outstanding',$attributes).nbs(2);?> :<?php if( !isset($trip_info[$det['id']]['outstanding']) || $trip_info[$det['id']]['outstanding']==''){ echo '';}else{ echo nbs(2).number_format($trip_info[$det['id']]['outstanding']+$drivers[$det['id']]['salary'],2);}?>

						<?php echo  br().form_label('Trip Advance','trip_advance',$attributes).nbs(2);?> :
						<?php echo br().form_label('Current Balance','current_balance',$attributes).nbs(2);?> :
						</div>
						</td>
					</tr>
					<?php endforeach;
					}
					?>
				</tbody>
			</table><?php echo $page_links;?>
		</div>
		<?php } ?>
	</fieldset>
</div>

