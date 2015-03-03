<?php
set_time_limit(0);
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=vehicles.xls");
header("Cache-Control: cache, must-revalidate");
header("Pragma: public");
?>
<table class="table table-hover table-bordered table-with-20-percent-td">
				<tbody>
					<tr><?php if(isset($msg)){
					echo 'No Results Found';
					}
					else{?>
					    <th>Registration Number </th>
					    <th>Model</th>
					    <th>Make</th>
					    <th>Owner Name</th>
					    <th>Contact Info</th>
					    <th>Address</th>
						<!--<th>Driver Details</th>
						<th>Current Status</th>-->
						
					    
					</tr>
					<?php
					
					if(isset($values)){  
					foreach ($values as $det): 
				
					?>
					<tr> 
					    <td><?php   echo $vehicles[$det['id']]['registration_number'].br();
						
						?></td>
						<td><?php if( !isset($vehicle_models[$vehicles[$det['id']]['model_id']]) || $vehicle_models[$vehicles[$det['id']]['model_id']]==''){ echo '';}else{echo $vehicle_models[$vehicles[$det['id']]['model_id']].br();}?></td>
						<td><?php if( !isset($vehicle_makes[$vehicles[$det['id']]['make_id']]) || $vehicle_makes[$vehicles[$det['id']]['make_id']]==''){ echo '';}else{echo $vehicle_makes[$vehicles[$det['id']]['make_id']].br();}?></td>
						<td><?php  if( !isset($owners[$det['id']]['name']) || $owners[$det['id']]['name']==''){ echo '';}else{echo $owners[$det['id']]['name'].br();}?></td>
						<td><?php if( !isset($owners[$det['id']]['mobile']) || $owners[$det['id']]['mobile']==''){ echo '';}else{echo $owners[$det['id']]['mobile'].br();} ?></td>
						<td><?php if( !isset($owners[$det['id']]['address']) || $owners[$det['id']]['address']==''){ echo '';}else{echo $owners[$det['id']]['address'].br();} ?></td>
						<!--<td><?php if(!isset($drivers[$det['id']]['driver_name']) || $drivers[$det['id']]['driver_name']==''){ echo '';}else{echo $drivers[$det['id']]['driver_name'].br();}
						if(!isset($drivers[$det['id']]['mobile']) || $drivers[$det['id']]['mobile']==''){ echo '';}else{echo $drivers[$det['id']]['mobile'].br();}
						if(!isset($drivers[$det['id']]['from_date']) || $drivers[$det['id']]['from_date']==''){ echo '';}else{echo $drivers[$det['id']]['from_date']; } ?></td>
						<td><?php if($vehicle_statuses[$det['id']]!='Available'){ echo '<span class="label label-info">'.$vehicle_statuses[$det['id']].'</span>'.br(); }else{ echo '<span class="label label-success">'.$vehicle_statuses[$det['id']].'</span>'.br(); } if($vehicle_trips[$det['id']]!=gINVALID){ echo anchor(base_url().'organization/front-desk/trip-booking/'.$vehicle_trips[$det['id']],'Trip ID :'.$vehicle_trips[$det['id']]); } else{ echo ''; } ?></td>-->	
					</tr>
					<?php endforeach;
					}
					}
					?>
				</tbody>
			</table>