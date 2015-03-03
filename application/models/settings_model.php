<?php 
class Settings_model extends CI_Model {
	
	
	public function addValues($tbl,$data){
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert($tbl,$data); 
	return true;
	}

	public function addValues_returnId($tbl,$data){
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert($tbl,$data);
	return $this->db->insert_id();
	}

	public function getValues($id,$tbl){ 
	$this->db->select('id,description,name');
	$this->db->from($tbl);
	$this->db->where('id',$id );
	 //newly added-to be organisation based
		$org_id=$this->session->userdata('organisation_id');
		$this->db->where( 'organisation_id', $org_id );
		//---
	return $this->db->get()->result_array();
	
	}
	public function updateValues($tbl,$data,$id){
	$this->db->where('id',$id );
	//newly added-to be organisation based
		$org_id=$this->session->userdata('organisation_id');
		$this->db->where( 'organisation_id', $org_id );
	//---
	 $this->db->set('updated', 'NOW()', FALSE);
	$this->db->update($tbl,$data);
	return true;
	}
	public function deleteValues($tbl,$id){
	$this->db->where('id',$id );
	 //newly added-to be organisation based
		$org_id=$this->session->userdata('organisation_id');
		$this->db->where( 'organisation_id', $org_id );
		//---
	$this->db->delete($tbl);
	return true;
	}
	
	
}
?>
