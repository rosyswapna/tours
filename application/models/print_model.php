<?php
class print_model extends CI_Model {
public function all_details($qry){
	$query=$this->db->query($qry); 
	$result=$query->result_array();  
	if(count($result)>0){
		return $result;
	}else{
		return false;
	}
}
public function getModels(){
	$qry=$this->db->select('id,name');
	$qry=$this->db->from('vehicle_models');
	//newly added-to be organisation based
		$org_id=$this->session->userdata('organisation_id');
	$qry=$this->db->where( 'organisation_id', $org_id );
		//---
	$qry=$this->db->get();
	$result=$qry->result_array();
	return $result;
	}
}
?>