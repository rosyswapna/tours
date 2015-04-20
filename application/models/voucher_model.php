<?php 
class Voucher_model extends CI_Model {

	function getVoucher($id)
	{
		$this->db->from('trip_vouchers');
		$this->db->where('id',$id);
		$qry = $this->db->get();
		if($qry->num_rows() == 1){
			return $qry->row_array();
		}else{
			return false;
		}
	}

	//get voucher with trip id
	function getTripVoucher($trip_id)
	{
		$this->db->from('trip_vouchers');
		$this->db->where('trip_id',$trip_id);
		$qry = $this->db->get();
		if($qry->num_rows() == 1){
			return $qry->row_array();
		}else{
			return false;
		}
	}

	function getVouchers()
	{
		$this->db->from('trip_vouchers');
		$this->db->where('organisation_id',$this->session->userdata('organisation_id'));
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}


	function getVoucherItineraryData($table = '',$trip_voucher_id = gINVALID)
	{
		if($table == '' || (int)$trip_voucher_id <= 0)
			return false;
		
		$this->db->select($table.'.*');
		$this->db->from($table);
		$this->db->where('trip_voucher_id',$trip_voucher_id);
		$query = $this->db->get();
		
		
		if($query->num_rows() > 0){
			$rows = $query->result_array();
			$retArray = array();
			foreach($rows as $row){
				unset($row['user_id']);
				unset($row['organisation_id']);
				unset($row['created']);
				unset($row['updated']);
				$retArray[] = $row;
			}
			return $retArray;

			
		}else{
			return false;
		}
	}

	function getVoucherDataAll($trip_voucher_id=gINVALID)
	{
		if(!is_numeric($trip_voucher_id))
			return false;

		$tbls = array('trip_voucher_accommodation','trip_voucher_vehicles','trip_voucher_services');
		$retArray = array();
		foreach($tbls as $tbl){
			$tblData = $this->getVoucherItineraryData($tbl,$trip_voucher_id);
			if($tblData){
				$retArray[$tbl]=$tblData;
			}
				
		}

		return $retArray;
	}

	//--------------------------------------------------------------------------------------

	function addVoucher($dataArray = false)
	{
		if(is_array($dataArray)){
			$dataArray['user_id'] = $this->session->userdata('id');
			$dataArray['organisation_id'] = $this->session->userdata('organisation_id');
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('trip_vouchers', $dataArray);
			return $this->db->insert_id();
		}else{
			return false;
		}
	}

	//insert voucher data from cart with trip voucher id
	function insertVoucherData($voucherData=false,$trip_voucher_id=gINVALID)
	{	
		if($voucherData!= false && $trip_voucher_id > 0){
			foreach($voucherData as $tble => $ItrArray){

				$insertArray = array();
				foreach($ItrArray as $dataArray){

					//if found array values serialize them
					foreach($dataArray as $colName=>$colVal){
						if(is_array($colVal)){
							$dataArray[$colName] = serialize($colVal);
						}
					}
					$dataArray['trip_voucher_id'] = $trip_voucher_id;
					unset($dataArray['id']);
					$dataArray['created'] = date("Y-m-d H:i:s");
					$dataArray['organisation_id'] = $this->session->userdata('organisation_id');
					$dataArray['user_id'] = $this->session->userdata('id');
					$insertArray[] = $dataArray;
				}
				
				//echo "<pre>";print_r($insertArray);echo "</pre>";exit;
				$this->db->insert_batch($tble,$insertArray);
			}
			return true;
		}else{
			return false;
		}
	}

	//trip voucher save function
	function saveVoucherCart($voucherCart,$trip_voucher_id = gINVALID)
	{
		if($trip_voucher_id == gINVALID){//new voucher
			//echo "<pre>";print_r($voucherCart);echo "</pre>";exit;
			$trip_id = $voucherCart->tripId();
			if(is_numeric($trip_id) && $trip_id > 0){
				$voucher['trip_id'] = $trip_id;
				$totals = $voucherCart->totals();
				foreach($totals as $colName=>$colVal){
					$voucher[$colName] = $colVal;
				}

				//echo "<pre>";print_r($voucher);echo "</pre>";exit;
				$trip_voucher_id = $this->addVoucher($voucher);
			
				$voucherData = $voucherCart->contents();
				$this->insertVoucherData($voucherData,$trip_voucher_id);
				return true;

			}else{
				return false;
			}
			
		}else{//update voucher
		
		}
	}

	
}
?>
