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

				if(isset($row['trip_expense'])){
					$row['trip_expense'] = ($row['trip_expense'] != '')?unserialize($row['trip_expense']):'';
				}
				if(isset($row['room_attributes'])){
					$row['room_attributes'] = ($row['room_attributes'] != '')?unserialize($row['room_attributes']):'';
				}
				if(isset($row['meals_package'])){
					$row['meals_package'] = ($row['meals_package'] != '')?unserialize($row['meals_package']):'';
				}
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

	
	function updateVoucher($dataArray = false,$id)
	{
		if(is_array($dataArray)){
			$this->db->set('updated', 'NOW()', FALSE);
			$this->db->update('trip_vouchers', $dataArray);
			$this->db->where('id',$id);
			return true;
		}else{
			return false;
		}
	}


	//insert voucher data from cart with trip voucher id
	function insertVoucherData($voucherData=false,$trip_voucher_id=gINVALID)
	{	//echo "<pre>";print_r($voucherData);echo "</pre>";exit;
		if($voucherData!= false && $trip_voucher_id > 0){
			//============set insert and update array======
			foreach($voucherData as $tble => $ItrArray){
				static $insertArray = array();
				static $updateArray = array();
				foreach($ItrArray as $dataArray){
					//if found array values serialize them
					foreach($dataArray as $colName=>$colVal){
						if(is_array($colVal)){
							$dataArray[$colName] = serialize($colVal);
						}
					}
					$dataArray['trip_voucher_id'] = $trip_voucher_id;
					$itemId = $dataArray['id'];
					unset($dataArray['id']);
					if(is_numeric($itemId) && $itemId > 0){//edit item
						$dataArray['updated'] = date("Y-m-d H:i:s");

						$updateArray[$tble][$itemId] = $dataArray;
					}else{//new item
						$dataArray['created'] = date("Y-m-d H:i:s");
						$dataArray['organisation_id'] = $this->session->userdata('organisation_id');
						$dataArray['user_id'] = $this->session->userdata('id');
						$insertArray[$tble][] = $dataArray;
					}	
					
				}	
			}
			//=========================================
//echo "<pre>";print_r($insertArray);echo "</pre>";exit;
			//insert batch
			if($insertArray){
				foreach($insertArray as $tbl=>$dataArray){
					//echo "<pre>";print_r($dataArray);echo "</pre>";exit;
					$this->db->insert_batch($tbl,$dataArray);
				
				}
			}

			//update batch
			if($updateArray){

				foreach($updateArray as $tbl=>$dataBatch){

					foreach($dataBatch as $id=>$dataArray){
					
						$this->db->where('id', $id);
						$this->db->set('updated', 'NOW()', FALSE);
						$this->db->update($tbl, $dataArray); 
					}
				
				}
				
			}
			return true;
		}else{
			return false;
		}
	}

	function checkVoucherExists($trip_id){
		
		$this->db->from('trip_vouchers');
		$this->db->where('trip_id',$trip_id);
		$result = $this->db->get();
		if($result->num_rows() > 0){
			return $result->row();
		}else{
			return false;
		}
		
	}

	//trip voucher save function
	function saveVoucherCart($voucherCart)
	{
		//echo "<pre>";print_r($voucherCart);echo "</pre>";exit;
		$trip_id = $voucherCart->tripId();
		if(is_numeric($trip_id) && $trip_id > 0){

			$voucher['trip_id'] = $trip_id;
			$totals = $voucherCart->totals();

			//echo "<pre>";print_r($totals);echo "</pre>";exit;
			foreach($totals as $colName=>$colVal){
				$voucher[$colName] = $colVal;
			}
			
			if($tourVoucher = $this->checkVoucherExists($trip_id)){//edit
				if($this->updateVoucher($voucher,$tourVoucher->id))
					$trip_voucher_id = $tourVoucher->id;
				else
					$trip_voucher_id = false;	
			}else{//new
				$trip_voucher_id = $this->addVoucher($voucher);
			}
				
			//add or update voucher itineraries
			if($trip_voucher_id){
				$voucherData = $voucherCart->contents();
				$this->insertVoucherData($voucherData,$trip_voucher_id);
			}
			return $trip_voucher_id;

		}else{
			return false;
		}
		
	}

	function getService($service_id ,$trip_id){
		$sqlVoucher = "SELECT rate,quantity,uom_id FROM trip_voucher_services WHERE service_id= ".$this->db->escape($service_id);
		$sqlVoucher .= " AND trip_voucher_id IN 
			(SELECT id FROM trip_vouchers WHERE trip_id = ".$this->db->escape($trip_id).")";

		$result = $this->db->query($sqlVoucher);
		if($result->num_rows() > 0){
			return $result->row_array();
		}else{//get from trip
			$sqlTrip = "SELECT amount as rate,quantity,-1 AS uom_id FROM trip_services WHERE service_id = ".$this->db->escape($service_id);
			$sqlTrip .= " AND itinerary_id IN 
			(SELECT id FROM itinerary WHERE trip_id = ".$this->db->escape($trip_id).")";

			$result = $this->db->query($sqlTrip);
			if($result->num_rows() > 0){
				return $result->row_array();
			}else{
				return false;
			}
		}

		
	}

	
}
?>
