<?php
class Vehicle_model extends CI_Model {


	public function getVehiclePercentages($regNo = null)
	{

		if($regNo != null){
			$qry='SELECT v.registration_number,v.driver_percentage ,v.vehicle_percentage 
FROM vehicles v WHERE v.registration_number = '.$this->db->escape($regNo);

			$result=$this->db->query($qry);
			$result=$result->result_array();
			if(count($result)>0)
				return $result;	
		}
		return false;
	}


	public function insertVehicle($data,$driver_data,$device_data){
	$qry=$this->db->set('created', 'NOW()', FALSE);
	$qry=$this->db->insert('vehicles',$data);
	$v_id=mysql_insert_id();
	if($qry>0){
		
		$this->mysession->set('vehicle_id',$v_id);
		return true;
		}
		else
		{
		$this->mysession->set('date_err','Invalid Date');
		}


	}
	//add new vehicle from trip booiking
	public function addVehicleFromTripBooking($value = null){

		if($value !=null){
			
			$value['organisation_id']=$this->session->userdata('organisation_id');
			$value['user_id']=$this->session->userdata('id');
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('vehicles',$value);
			return mysql_insert_id();
		}
		return -1;
	}


public function getVehicles(){ 
	$qry='SELECT V.registration_number,V.id,V.vehicle_model_id,V.vehicle_make_id,VD.from_date,VD.to_date,VD.driver_id,VD.vehicle_id FROM vehicles AS V LEFT JOIN vehicle_drivers AS VD ON  V.id =VD.vehicle_id AND V.organisation_id = '.$this->session->userdata('organisation_id').' WHERE VD.organisation_id = '.$this->session->userdata('organisation_id').' AND VD.to_date="9999-12-30"';
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(count($results)>0){
	for($i=0;$i<count($results);$i++){
		$vehicles[$results[$i]['driver_id']]['registration_number']=$results[$i]['registration_number'];
		$vehicles[$results[$i]['driver_id']]['vehicle_model_id']=$results[$i]['vehicle_model_id'];
		$vehicles[$results[$i]['driver_id']]['vehicle_make_id']=$results[$i]['vehicle_make_id'];
		
		}
		return $vehicles;
	}else{
		return false;
	}
	}
	
public function getDriversInfo(){ 
	
	$qry='select id,name,phone,mobile,present_address,district,license_renewal_date,badge_renewal_date,place_of_birth,salary from drivers where organisation_id = '.$this->session->userdata('organisation_id');
	$qry=$this->db->query($qry);
	$results=$qry->result_array();
	if(count($results)>0){
	for($i=0;$i<count($results);$i++){
		$drivers[$results[$i]['id']]['name']=$results[$i]['name'];
		$drivers[$results[$i]['id']]['phone']=$results[$i]['phone'];
		$drivers[$results[$i]['id']]['mobile']=$results[$i]['mobile'];
		$drivers[$results[$i]['id']]['district']=$results[$i]['district'];
		$drivers[$results[$i]['id']]['present_address']=$results[$i]['present_address'];
		$drivers[$results[$i]['id']]['license_renewal_date']=$results[$i]['license_renewal_date'];
		$drivers[$results[$i]['id']]['badge_renewal_date']=$results[$i]['badge_renewal_date'];
		$drivers[$results[$i]['id']]['place_of_birth']=$results[$i]['place_of_birth'];
		$drivers[$results[$i]['id']]['salary']=$results[$i]['salary'];
		} 
		return $drivers;
	}else{
		return false;
	}
	}
	
public function getOwners(){ 
	$qry='select vehicle_id,name,mobile,address from vehicle_owners where organisation_id = '.$this->session->userdata('organisation_id') ;
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(count($results)>0){
	for($i=0;$i<count($results);$i++){
		$owners[$results[$i]['vehicle_id']]['name']=$results[$i]['name'];
		$owners[$results[$i]['vehicle_id']]['mobile']=$results[$i]['mobile'];
		$owners[$results[$i]['vehicle_id']]['address']=$results[$i]['address'];
		
		}
		return $owners;
	}else{
		return false;
	}
	}
	public function getListVehicles(){ 
	$qry='select V.id,V.supplier_group_id,V.registration_number,V.vehicle_permit_renewal_date,V.tax_renewal_date,VI.insurance_renewal_date,VMA.id as make_id,VMO.id as model_id from vehicles V LEFT JOIN vehicle_models as VMO ON V.vehicle_model_id=VMO.id LEFT JOIN vehicle_makes as VMA ON V.vehicle_make_id=VMA.id LEFT JOIN vehicles_insurance as VI ON VI.vehicle_id=V.id where V.organisation_id='.$this->session->userdata('organisation_id');//echo $qry;exit;
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(count($results)>0){
	for($i=0;$i<count($results);$i++){
		$vehicles[$results[$i]['id']]['id']=$results[$i]['make_id'];
		$vehicles[$results[$i]['id']]['registration_number']=$results[$i]['registration_number'];
		$vehicles[$results[$i]['id']]['make_id']=$results[$i]['make_id'];
		$vehicles[$results[$i]['id']]['model_id']=$results[$i]['model_id'];
		$vehicles[$results[$i]['id']]['vehicle_permit_renewal_date']=$results[$i]['vehicle_permit_renewal_date'];
		$vehicles[$results[$i]['id']]['tax_renewal_date']=$results[$i]['tax_renewal_date'];
		$vehicles[$results[$i]['id']]['insurance_renewal_date']=$results[$i]['insurance_renewal_date'];
		$vehicles[$results[$i]['id']]['supplier_group_id']=$results[$i]['supplier_group_id'];
		} 
		return $vehicles;
	}else{
		return false;
	}
	}
	
	public function getCurrentStatuses($id){ 
	$qry='SELECT * FROM trips WHERE CONCAT(pick_up_date," ",pick_up_time) <= "'.date("Y-m-d H:i").'" AND CONCAT(drop_date," ",drop_time) >= "'.date("Y-m-d H:i").'" AND vehicle_id="'.$id.'" AND organisation_id = '.$this->session->userdata('organisation_id').' AND trip_status_id='.TRIP_STATUS_CONFIRMED;
	$results=$this->db->query($qry);
	$results=$results->result_array();
	if(count($results)>0){
	
		return $results;
	}else{
		return false;
	}
	}
	
public function insertInsurance($data){
$v_id=$this->mysession->get('vehicle_id');
$qry=$this->db->set('vehicle_id', $v_id);
$qry=$this->db->insert('vehicles_insurance',$data);
$in_id=mysql_insert_id();
$map_qry=$this->db->set('vehicles_insurance_id', $in_id);
$map_qry=$this->db->where('id',$v_id);
//newly added-to be organisation based
	$org_id=$this->session->userdata('organisation_id');
	$map_qry=$this->db->where('organisation_id', $org_id );
	//---
$map_qry=$this->db->update('vehicles');
return true;

}
public function insertLoan($data){
$qry=$this->db->set('created', 'NOW()', FALSE);
$v_id=$this->mysession->get('vehicle_id');
$qry=$this->db->set('vehicle_id', $v_id);
$qry=$this->db->insert('vehicle_loans',$data);
$l_id=mysql_insert_id();
$map_qry=$this->db->set('vehicle_loan_id', $l_id);
$map_qry=$this->db->where('id',$v_id);
//newly added-to be organisation based
	$org_id=$this->session->userdata('organisation_id');
	$map_qry=$this->db->where('organisation_id', $org_id );
	//---
$map_qry=$this->db->update('vehicles');
return true;

}

	public function getLoginId($data,$login)
	{
		$this->load->model('organization_model');
		if($login['username']!='' && $login['password'] != ''){
			$login_id = $this->organization_model->insertUser($data['name'],
							'',$data['address'],$login['username'],
							$login['password'],$data['email'],
							$data['mobile'],VEHICLE_OWNER);
		}else{
			$login_id = 0;
		}
		return $login_id;
	}

	

	//----------insert owner--------------------------------------------
	public function mapDetails($v_owner){
		
		/*$login_id = $this->getLoginId($data,$login);
		$data['login_id'] = $login_id;
		$qry=$this->db->set('created', 'NOW()', FALSE);
		$v_id=$this->mysession->get('vehicle_id');
		$qry=$this->db->set('vehicle_id', $v_id);
		$qry=$this->db->insert('vehicle_owners',$data);*/
	if(!empty($v_owner)){
		if(isset($v_owner['vehicle_owner_id'])){ 
			$map_qry=$this->db->set('vehicle_owner_id', $v_owner['vehicle_owner_id']);
			}
		if(isset($v_owner['supplier_group_id'])){ 
			$map_qry=$this->db->set('supplier_group_id', $v_owner['supplier_group_id']);
			}
			$v_id=$this->mysession->get('vehicle_id');
			$map_qry=$this->db->where('id',$v_id);
			//newly added-to be organisation based
			$org_id=$this->session->userdata('organisation_id');
			$map_qry=$this->db->where('organisation_id', $org_id );
			//---
			$map_qry=$this->db->update('vehicles');
			return true;
		}else{
			return false;
		}
	}
	public function insertOwner($data,$login){
		$login_id = $this->getLoginId($data,$login);
		$data['login_id'] = $login_id;
		$qry=$this->db->set('created', 'NOW()', FALSE);
		$v_id=$this->mysession->get('vehicle_id');
		$qry=$this->db->set('vehicle_id', $v_id);
		$qry=$this->db->insert('vehicle_owners',$data);
		$o_id=$this->db->insert_id();
		   if($o_id>0){
		   return $o_id;
		   }
		   else{
		   return false;
		   }
	}
	//------------------------------------------------------------------

	//-----update owner details and user details ----------------------------
	public function UpdateOwnerdetails($data,$id,$login='',$flag=''){ 

		$qry=$this->db->where('id',$id );
		$qry=$this->db->get("vehicle_owners");
		if(count($qry)>0){
			$login_id=$qry->row()->login_id;
		}else{
			$login_id = 0;
		}

		if($login_id > 0){//user exists
			if($flag==0)
				$login['password'] = md5($login['password']);
			$this->db->set('updated', 'NOW()', FALSE);
			$this->db->where('id',$login_id );
			$this->db->update("users",$login);
		}else{//new user
			$login_id = $this->getLoginId($data,$login);
		}

		$this->db->set('updated', 'NOW()', FALSE);
		$this->db->set('login_id', $login_id);
		//newly added-to be organisation based
		$org_id=$this->session->userdata('organisation_id');
		$this->db->where('organisation_id', $org_id );
		//---
		$this->db->where('id',$id);
		$this->db->update('vehicle_owners',$data);  
		return true;

	}
	//-----------------------------------------------------------
	
	
public function  UpdateVehicledetails($data,$v_id){
	
	$this->db->where('id',$v_id );
	//newly added-to be organisation based
	$org_id=$this->session->userdata('organisation_id');
	$this->db->where('organisation_id', $org_id );
	//---
	$this->db->set('updated', 'NOW()', FALSE);
	$this->db->update('vehicles',$data); 
	return true;


}

public function map_drivers($driver_id,$from_date,$updated_date) {
	$v_id=$this->mysession->get('vehicle_id');
	$to_date='9999-12-30';
	$tbl="vehicle_drivers";
	$qry=$this->db->where(array('vehicle_id'=>$v_id,'organisation_id'=>$this->session->userdata('organisation_id'),'to_date'=>$to_date));
	$qry=$this->db->get($tbl);
	$result=$qry->result_array();
	if($qry->num_rows()>0){
	$this->db->where('id',$result[0]['id']);
	$this->db->set('updated', 'NOW()', FALSE);
	$this->db->update($tbl,array('to_date'=>$updated_date));
	}

	$arry=array('vehicle_id'=>$v_id,'driver_id'=>$driver_id,'from_date'=>$from_date,'organisation_id'=>$this->session->userdata('organisation_id'),'user_id'=>$this->session->userdata('id'),'to_date'=>$to_date);
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert($tbl,$arry);

	}

	public function map_devices($device_id,$from_date,$updated_date) {
	$v_id=$this->mysession->get('vehicle_id');
	$to_date='9999-12-30';
	$tbl="vehicle_devices";
	$qry=$this->db->where(array('vehicle_id'=>$v_id,'organisation_id'=>$this->session->userdata('organisation_id'),'to_date'=>$to_date));
	$qry=$this->db->get($tbl);
	$result=$qry->result_array();
	if($qry->num_rows()>0){
	$this->db->where('id',$result[0]['id']);
	$this->db->set('updated', 'NOW()', FALSE);
	$this->db->update($tbl,array('to_date'=>$updated_date));
	}

	$arry=array('vehicle_id'=>$v_id,'device_id'=>$device_id,'from_date'=>$from_date,'organisation_id'=>$this->session->userdata('organisation_id'),'user_id'=>$this->session->userdata('id'),'to_date'=>$to_date);
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert($tbl,$arry);

	}



public function UpdateInsurancedetails($data,$id){

$this->db->where('id',$id);
$this->db->update('vehicles_insurance',$data); 
return true;

}
public function UpdateLoandetails($data,$id){
$this->db->set('updated', 'NOW()', FALSE);
$this->db->where('id',$id);
//newly added-to be organisation based
	$org_id=$this->session->userdata('organisation_id');
	$this->db->where('organisation_id', $org_id );
	//---
$this->db->update('vehicle_loans',$data); 
return true;

}
	


  public function insert_service($data){
	$org_id=$this->session->userdata('organisation_id');
	$data['organisation_id']=$org_id;
	$result=$this->db->insert('service',$data);
	if(count($result)>0){
	return true;
	}
	else {
	return false;
	}
	}
  public function get_listService($vid){
	$array=array('organisation_id'=>$this->session->userdata('organisation_id'),'vehicle_id'=>$vid);
	
	$query=$this->db->where($array);
	$query=$this->db->get('service');
	if(count($query)>0){
	return $query->result_array();
	}
	else
	{
	return false;
	}
	}
 public function get_Service($sid){
	$array=array('organisation_id'=>$this->session->userdata('organisation_id'),'id'=>$sid);
	
	$query=$this->db->where($array);
	$query=$this->db->get('service');
	if(count($query)>0){
	return $query->result_array();
	}
	else
	{
	return false;
	}
  }
  
 public function update_service($data,$id){
	$org_id=$this->session->userdata('organisation_id');
	$array=array('organisation_id'=>$org_id,'id'=>$id);
	$query=$this->db->where($array);
	$query=$this->db->update('service',$data);
	if(count($query)>0){
	return true;
	}
	else {
	return false;
	}
	}

}?>
