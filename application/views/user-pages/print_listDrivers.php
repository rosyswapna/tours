<?php 
set_time_limit(0);
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=drivers.xls");
header("Cache-Control: cache, must-revalidate");
header("Pragma: public");
?>
	<?php //print_r($drivers);exit;?>
	<table class="table table-hover table-bordered">
	<tbody>
		<tr><?php if(isset($msg)){
					echo 'No Results Found';
					}
					else{?>
		<th>Name</th>
		<th>Landline Number</th>
		<th>Mobile Number</th>
		<th>Address</th>
		<th>District</th>
		</tr>
		<?php if(isset($values)){ 
					foreach ($values as $det):
					$phone_numbers=''; ?>
		<tr>
		<td><?php  echo $drivers[$det['id']]['name'].nbs(3); ?></td>
		<td><?php if( !isset($drivers[$det['id']]['phone']) || $drivers[$det['id']]['phone']==''){ echo '';}else{echo $drivers[$det['id']]['phone'].br();} ?></td>
		<td><?php if( !isset($drivers[$det['id']]['mobile']) || $drivers[$det['id']]['mobile']==''){ echo '';}else{echo $drivers[$det['id']]['mobile'].br();} ?></td>
		<td><?php if( !isset($drivers[$det['id']]['present_address']) || $drivers[$det['id']]['present_address']==''){ echo '';}else{echo $drivers[$det['id']]['present_address'].br();}?></td>
		<td><?php if( !isset($drivers[$det['id']]['district']) || $drivers[$det['id']]['district']==''){ echo '';}else{echo $drivers[$det['id']]['district'].br();}?></td>
		</tr>
		<?php endforeach;
		}
		}?>
	</tbody>
	</table>
