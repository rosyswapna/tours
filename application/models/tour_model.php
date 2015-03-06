<?php 
class Tour_model extends CI_Model {

	//---------------------------business season start-----------------------------
	function addBusinessSeason($data = false)
	{
		if(!$data)
			return false;
		
		$this->db->set('created', 'NOW()', FALSE);
		$data['organisation_id'] = $this->session->userdata('organisation_id'); 
		$data['user_id'] = $this->session->userdata('user_id'); 
		$this->db->insert('business_seasons',$data);
		$insert_id = $this->db->insert_id();
		if($insert_id > 0){
			return $insert_id;
		}else{
			return false;
		}
	}

	function updateBusinessSeason($data = false){

		if(!$data)
			return false;
		$this->db->set('updated', 'NOW()', FALSE);
		$this->db->where('id',$id);
		$this->db->update("business_seasons",$data);
		return $data['id'];
		
		
	}

	function getBusinessSeason($id)
	{
		$this->db->from('business_seasons');
		$this->db->where('id',$id);
		$row = $this->db->get()->row_array();
		if($this->db->num_rows() == 1){
			return $row;
		}else{
			return false;
		}
	}

	function getBusinessSeasonList()
	{
		$this->db->from('business_seasons');
		$retArray = $this->db->get()->result_array();
		if($this->db->num_rows() > 0){
			return $retArray;
		}else{
			return false;
		}
	}
	//---------------------------business season ends -----------------------------
		

}
?>
