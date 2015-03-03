<?php    if($this->session->userdata('dbSuccess') != '') { ?>
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
		if(!isset($sname)){
			$sname='';
		}
		if(!isset($status_id)){
			$status_id='';
		}
		?>

<div class="page-outer">    
	<fieldset class="body-border">
		<legend class="body-head">List Organizations</legend>
		<div class="box-body table-responsive no-padding">
			<?php echo form_open(base_url().'admin/organization/list/');?>
			<table class="table list-org-table">
				<tbody>
					<tr>
					    <td><?php echo form_input(array('name'=>'sname','class'=>'form-control','id'=>'sname','placeholder'=>'By Name','size'=>30,'value'=>$sname));?> </td>
					    <td><?php 	$class="form-control";
									$data=$this->form_functions->populate_dropdown('status',$org_status,$status_id,$class,$id='',$msg='Select Status');
									echo $data;?>
					</td>
					    <td><?php echo form_submit("search","Search","class='btn btn-primary'");?></td>
					    <?php echo form_close();?>
						<td><?php echo nbs(55); ?></td>
						<td><?php echo nbs(35); ?></td>
						<td><?php echo nbs(25); ?></td>
					</tr>
				</tbody>
			</table>
				<div class="msg"> <?php 
			if (isset($result)){ echo $result;} else {?></div>
		</div>
		<div class="box-body table-responsive no-padding">
			<table class="table table-hover table-bordered">
				<tbody>
					<tr>
					    <th>Organization Name</th>
					    <th>Address</th>
					    <th>Status</th>
					    <th>Action</th>
					</tr>
					<?php
					//$status=array('1'=>'Active','2'=>'Inactive');
					
					foreach($values as $row):
					?>
					<tr>
					    <td><?php echo $row['name'];?></td>
					    <td><?php echo $row['address'] ;?></td>
					    <td><?php if($row['status_id'] == 1) { ?>
							<span class="label label-success"><?php echo $status[$row['status_id']];?></span> 
						<?php } else {?>
							<span class="label label-danger"> <?php echo $status[$row['status_id']];?></span>
						<?php } ?>
						</td>	
						<td><?php echo anchor(base_url().'admin/organization/'.$row['id'],'Edit','class="btn btn-primary"').nbs(3).anchor(base_url().'admin/organization/'.$row['id'].'/password-reset','Change Password','class="btn btn-primary"');?>
					<?php echo ($row['fa_account'])?"":nbs(3).anchor(base_url().'account/add_accounts/'.$row['id'],'Add Accounts','class="btn btn-primary"');?>
						</td>
					</tr>
					<?php endforeach;
					?>
				</tbody>
			</table><?php echo $page_links;?>
		</div>
		<?php } ?>
	</fieldset>
</div>

