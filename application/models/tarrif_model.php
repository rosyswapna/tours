<?php 
class Tarrif_model extends CI_Model {
	public function addValues($data){
	//print_r($data);exit;
	$tbl="tariff_masters";
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert($tbl,$data);
	return true;
	}
	public function editValues($data,$id){
	$tbl="tariff_masters";
	
	$this->db->where('id',$id );
		//newly added-to be organisation based
		$org_id=$this->session->userdata('organisation_id');
		$this->db->where( 'organisation_id', $org_id );
		//---
	$this->db->set('updated', 'NOW()', FALSE);
	$this->db->update($tbl,$data);
	return true;
	}
	public function deleteValues($id){
	$tbl="tariff_masters";
	$this->db->where('id',$id );
		//newly added-to be organisation based
		$org_id=$this->session->userdata('organisation_id');
		$this->db->where( 'organisation_id', $org_id );
		//---
	$this->db->delete($tbl);
	return true;
	}
	public function addTariff($data){
	$id=$data['tariff_master_id'];
	$where_data['tariff_master_id']=$data['tariff_master_id'];
	$where_data['vehicle_model_id']=$data['vehicle_model_id'];
	$where_data['vehicle_ac_type_id']=$data['vehicle_ac_type_id'];
	$where_data['customer_id']=$data['customer_id'];
	$where_data['organisation_id']=$data['organisation_id'];
	$where_data['to_date']='9999-12-30';
	$to_date='9999-12-30';
	$tbl="tariffs";
	$tariff_id=$this->chekckTariff($where_data);
	if($tariff_id!=gINVALID){
		$date=explode("-",$data['from_date']);
		$year=$date[0];
		$month=$date[1];
		$day=$date[2];
		$date=$data['from_date'];
		$date_result=$this->date_check($date);
		if( $date_result==true ) {;
			$from_unix_time = mktime(0, 0, 0, $month, $day, $year);
			$day_before = strtotime("yesterday", $from_unix_time);
			$formatted_date = date('Y-m-d', $day_before);
			
			$this->db->where('id',$tariff_id);
			$this->db->set('updated', 'NOW()', FALSE);
			$this->db->update($tbl,array('to_date'=>$formatted_date));
	
		}
	}
	$this->db->set('to_date', $to_date);
	$this->db->set('created', 'NOW()', FALSE);
	$this->db->insert($tbl,$data);
	return true;
	
	}
	
	
	/*public function edit_tarrifValues($data,$id){
	$tbl="tariffs";
	$this->db->where('id',$id );
		//newly added-to be organisation based
		$org_id=$this->session->userdata('organisation_id');
		$this->db->where( 'organisation_id', $org_id );
		//---
	$this->db->set('updated', 'NOW()', FALSE);
	$this->db->update($tbl,$data);
	return true;
	}*/
	public function chekckTariff($where_data){
		$this->db->from('tariffs');
		$this->db->where($where_data);
		$this->db->order_by("id", "desc"); 
		$this->db->limit(1);
		$results = $this->db->get()->result();
		if(count($results)>0){
			return $results[0]->id;
		}else{
			return gINVALID; 
		}
	}
	public function getFromDate($id){
		$this->db->from('tariffs');
		$this->db->where('id',$id);
		$results = $this->db->get()->result();
		if(count($results)>0){
			return $results[0]->from_date;
		}else{
			return false; 
		}
	}
	public function edit_tarrifValues($data,$id){ 
    $where_data['tariff_master_id']=$data['tariff_master_id'];
	$where_data['vehicle_model_id']=$data['vehicle_model_id'];
	$where_data['vehicle_ac_type_id']=$data['vehicle_ac_type_id'];
	$where_data['customer_id']=$data['customer_id'];
	$where_data['organisation_id']=$this->session->userdata('organisation_id');
	$original_from_date=$this->getFromDate($id);
	if($original_from_date!=false){
			$date=explode("-",$original_from_date);
			$year=$date[0];
			$month=$date[1];
			$day=$date[2];
			$where_data['to_date'] = $this->getFormattedDate($month,$day,$year);
	$tariff_id=$this->chekckTariff($where_data);
	$tbl="tariffs";
		if($tariff_id!=gINVALID){
			$date=explode("-",$data['from_date']);
			$year=$date[0];
			$month=$date[1];
			$day=$date[2];
			$date=$data['from_date'];
			$date_result=$this->date_check($date);
			if( $date_result==true ) {
				$formatted_date = $this->getFormattedDate($month,$day,$year);
				$to_date='9999-12-30';
			
				$this->db->where('id',$tariff_id);
				$this->db->set('updated', 'NOW()', FALSE);
				$this->db->update($tbl,array('to_date'=>$formatted_date));
	
			}
		}
	}
	
    $this->db->where('id',$id );
    $this->db->set('updated', 'NOW()', FALSE);
    $this->db->update($tbl,$data);
    return true;
    
    }
	
	public function getFormattedDate($month,$day,$year){
		$from_unix_time = mktime(0, 0, 0, $month, $day, $year);
		$day_before = strtotime("yesterday", $from_unix_time);
		return $formatted_date = date('Y-m-d', $day_before);
	}
	public function delete_tarrifValues($id){
	$tbl="tariffs";
	$this->db->where('id',$id );
		//newly added-to be organisation based
		$org_id=$this->session->userdata('organisation_id');
		$this->db->where( 'organisation_id', $org_id );
		//---
	$this->db->delete($tbl);
	return true;
	}

	public function selectAvailableTariff($data){
	//$qry='SELECT T.rate,T.additional_kilometer_rate, max( T.to_date ) ,TM.minimum_kilometers,T.vehicle_model_id,TM.vehicle_make_id,TM.title, T.tariff_master_id, T.id FROM tariffs AS T, tariff_masters AS TM WHERE T.tariff_master_id = TM.id
//AND T.organisation_id ='.$this->session->userdata('organisation_id').' AND TM.organisation_id ='.$this->session->userdata('organisation_id').' AND  TM.vehicle_ac_type_id ='.$data['vehicle_ac_type'].' AND T.vehicle_model_id ='.$data['vehicle_model'].' AND T.to_date ="9999-12-30" GROUP BY T.tariff_master_id';
	$qry='SELECT T.driver_bata,T.night_halt,T.rate,T.additional_kilometer_rate,T.additional_hour_rate, max( T.to_date ),TM.minimum_kilometers,TM.minimum_hours,T.vehicle_model_id,T.vehicle_ac_type_id,TM.title, T.tariff_master_id as tariff_master_id , T.id FROM tariffs AS T LEFT JOIN tariff_masters AS TM ON TM.id = T.tariff_master_id WHERE T.organisation_id ='.$this->session->userdata('organisation_id').' AND TM.organisation_id ='.$this->session->userdata('organisation_id').' AND  T.vehicle_ac_type_id ='.$data['vehicle_ac_type'].' AND T.vehicle_model_id ='.$data['vehicle_model'].' AND T.customer_id = '.gINVALID.' AND CURDATE() Between T.from_date and T.to_date GROUP BY T.tariff_master_id ';//echo $qry;exit;
	$result=$this->db->query($qry);
	$result=$result->result_array();
	return $result;

	}

	public function getCustomerTariff($data){
		
	$qry='SELECT T.driver_bata,T.night_halt,T.rate,T.additional_kilometer_rate,T.additional_hour_rate, max( T.to_date ),TM.minimum_kilometers,TM.minimum_hours,T.vehicle_model_id,T.vehicle_ac_type_id,TM.title, T.tariff_master_id as tariff_master_id , T.id FROM tariffs AS T LEFT JOIN tariff_masters AS TM ON TM.id = T.tariff_master_id WHERE T.organisation_id ='.$this->session->userdata('organisation_id').' AND TM.organisation_id ='.$this->session->userdata('organisation_id').' AND  T.vehicle_ac_type_id ='.$data['vehicle_ac_type_id'].' AND T.vehicle_model_id ='.$data['vehicle_model_id'].' AND T.tariff_master_id='.$data['tariff_master_id'].' AND T.customer_id = '.$data['customer_id'].' AND CURDATE() Between T.from_date and T.to_date GROUP BY T.tariff_master_id';//echo $qry;exit;
		$result=$this->db->query($qry);
		$result=$result->result_array();
		return $result;

	}

	public function selectTariffDetails($id){
	$qry='SELECT T.rate,T.driver_bata,T.additional_kilometer_rate,TM.minimum_kilometers, T.tariff_master_id, T.id FROM tariffs AS T, tariff_masters AS TM WHERE T.tariff_master_id = TM.id
AND T.organisation_id ='.$this->session->userdata('organisation_id').' AND T.id ='.$id;
	$result=$this->db->query($qry);
	$result=$result->result_array();
	return $result;

	}
	public function date_check($date){
	if( strtotime($date) >= strtotime(date('Y-m-d')) ){
	return true;
	}
	}
	}
	?>
