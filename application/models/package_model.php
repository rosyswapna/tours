<?php 
class Package_model extends CI_Model {

	//add single package itineray with dayno and package_id
	function addPckItinerary($dayno,$package_id = 0)
	{
		if(is_numeric($dayno) && $dayno > 0){
			$insertData = array(
				'package_id' => $package_id,
				'day_no'	  => $dayno,
				'user_id' => $this->session->userdata('id'),
				'organisation_id' => $this->session->userdata('organisation_id')
				);
			$this->db->set('created','NOW()',FALSE);
			$this->db->insert('package_itinerary', $insertData);
			return $this->db->insert_id();
		}else{
			return false;
		}
		
		
	}

	//get single itinerary with id
	function getPackageItinerary($day_no = '',$trip_id)
	{
		$this->db->from('package_itinerary');
		$this->db->where(array('package_id'=>$trip_id,'day_no'=>$day_no));
		$query = $this->db->get();
		if($query->num_rows() == 1)
			return $query->row_array();
		else
			return false;
	}

	function addPackage($data=false){
		if($data){
			$data['user_id']= $this->session->userdata('id');
			$data['organisation_id'] = $this->session->userdata('organisation_id');
			$this->db->set('created','NOW()',FALSE);
			$this->db->insert('packages',$data);
			return $this->db->insert_id();
		}else{
			return false;
		}
	}

	//get trip itineraries with trip id as parameter
	function getPckItineraries($package_id = 0)
	{
		$this->db->from('package_itinerary');
		$this->db->where('package_id',$package_id);
		$query = $this->db->get();
		if($query->num_rows() > 0)
			return $query->result_array();
		else
			return false;
	}


	//get trip vehicles or accommodation or services or destinatins of a particular itinerary
	function getPckItineraryData($pck_itinerary_id,$table='')
	{
		if($table == '')
			return false;

		switch($table){
			case 'package_destinations':$this->db->select('id,package_itinerary_id as itinerary_id,destination_id,destination_priority,description');
						$this->db->from($table);
						$this->db->where('package_itinerary_id',$pck_itinerary_id);
						$query = $this->db->get();
						if($query->num_rows() > 0){
							$return_arry= $query->result_array();
						}
						else{
							$return_arry=false;
						}
						break;
			case 'package_accommodation':$this->db->select('id,package_itinerary_id as itinerary_id,hotel_id,room_type_id,room_quantity,room_attributes,meals_package,meals_quantity');
						$this->db->from($table);
						
						$this->db->where('package_itinerary_id',$pck_itinerary_id);
						$query = $this->db->get();
						if($query->num_rows() > 0){
							
							foreach($query->result_array() as $value){
							
							$value['room_attributes']=($value['room_attributes']!='')?unserialize($value['room_attributes']):'';
							$value['meals_package']=($value['room_attributes']!='')?unserialize($value['meals_package']):'';
							$return_arry[]=$value;
							
							}
						}else{
							$return_arry=false;
						}
						break;
			case 'package_services':$this->db->select('id,package_itinerary_id as itinerary_id, service_id, description, location, quantity ,amount');
						$this->db->from($table);
						
						$this->db->where('package_itinerary_id',$pck_itinerary_id);
						$query = $this->db->get();
						if($query->num_rows() > 0){
							$return_arry= $query->result_array();
						}else{
							$return_arry=false;
						}
						break;
			case 'package_vehicles':$this->db->select('id,package_itinerary_id as itinerary_id,vehicle_type_id,vehicle_ac_type_id, vehicle_model_id, vehicle_id, driver_id, tariff_id');
						$this->db->from($table);
						
						$this->db->where('package_itinerary_id',$pck_itinerary_id);
						$query = $this->db->get();
						if($query->num_rows() > 0){
							$return_arry= $query->result_array();
						}else{
							$return_arry=false;
						}
						break;
			default:$this->db->select($table.'.*');
				$this->db->from($table);
				$this->db->where('package_itinerary_id',$pck_itinerary_id);
				$query = $this->db->get();
				if($query->num_rows() > 0){
					$return_arry= $query->result_array();
				}else{
					$return_arry=false;
						}		
		}
		
		
			return $return_arry;
		
	}

	//get full package details
	function getPackage($package_id=0)
	{
		if(!is_numeric($package_id) || $package_id <= 0)
			return false;

		$pck_itineraries = $this->getPckItineraries($package_id);

		$tbls = array('package_destinations','package_accommodation','package_services','package_vehicles');
		$retArray = array();
		foreach($pck_itineraries as $pck_itinerary){
			foreach($tbls as $pck_tbl){
				$tbl = $this->getTripTable($pck_tbl);
				$tbleData = $this->getPckItineraryData($pck_itinerary['id'],$pck_tbl);
				if($tbleData)
					$retArray[$pck_itinerary['day_no']][$tbl]=$tbleData;
			}
		}

		return $retArray;
		
	}


	//get package table with respect to trip table
	function getPackageTable($trip_table){
		switch($trip_table){
			case 'trip_destinations':$package_table = 'package_destinations';break;
			case 'trip_accommodation':$package_table = 'package_accommodation';break;
			case 'trip_services':$package_table = 'package_services';break;
			case 'trip_vehicles':$package_table = 'package_vehicles';break;
			default:$package_table = '';
		}
		return $package_table;
	}

	//get package table with respect to trip table
	function getTripTable($package_table){
		switch($package_table){
			case 'package_destinations':$trip_table = 'trip_destinations';break;
			case 'package_accommodation':$trip_table = 'trip_accommodation';break;
			case 'package_services':$trip_table = 'trip_services';break;
			case 'package_vehicles':$trip_table = 'trip_vehicles';break;
			default:$trip_table = '';
		}
		return $trip_table;
	}
	
	// get all packages
	function getAllPackages(){
		$this->db->select('p.id,p.name as package,p.status_id,st.name');
		$this->db->from('packages p');
		$this->db->join('statuses st','p.status_id = st.id','left');
		$qry=$this->db->get();
			if($qry->num_rows() > 0){
				return $qry->result_array();
			}else{
				return false;
			}
	}

	//save package
	function save_package($cartClass,$package){
		
		$cart=$cartClass->contents();
		$deleteData=$cartClass->delete_itineraries();
		if(is_numeric($package) && $package > 0){//edit package
			$package_id = $package;
		}else{//new package
			$insertPackage = array('name' => $package,'status_id' => STATUS_ACTIVE);
			$package_id = $this->addPackage($insertPackage);
		}

		if(is_numeric($package_id) && $package_id > 0){		
			//create insert and update array
			foreach($cart as $dayno=>$itry){
				//get itinerary id or get from insert
				$pck_itinerary = $this->getPackageItinerary($dayno,$package_id);
				if($pck_itinerary){
					$pck_itry_id = $pck_itinerary['id'];
				}else{
					$pck_itry_id = $this->addPckItinerary($dayno,$package_id);
				}
			
				if(is_numeric($pck_itry_id) && $pck_itry_id > 0){
					foreach($itry as $trip_table=>$tableData){
						$table = $this->getPackageTable($trip_table);
						if($table != "" && $tableData!=null){
							foreach($tableData as $data){
								$data['package_itinerary_id'] = $pck_itry_id;
								$id = $data['id'];
								unset($data['id']);
								unset($data['itinerary_id']);
												
								//if found array values serialize them
								foreach($data as $colName=>$colVal){
									if(is_array($colVal)){
										$data[$colName] = serialize($colVal);}
								}
	
								if($id == gINVALID) {
									$data['created'] = date("Y-m-d H:i:s");
									$data['organisation_id'] = $this->session->userdata('organisation_id');
									$data['user_id'] = $this->session->userdata('id');
									$insertData[$table][] = $data;
								}else{
									$updateData[$table][$id] = $data;
								}
							
							}
						}
					}
				}

			
			}
		}

		//echo "<pre>";print_r($insertData);echo "</pre>";exit;

		//insert batch
		if($insertData){
			foreach($insertData as $tbl=>$dataArray){
				
				$this->db->insert_batch($tbl,$dataArray);
			}
		}

		//update batch
		if($updateData){

			foreach($updateData as $tbl=>$dataBatch){

				foreach($dataBatch as $id=>$dataArray){
					
					$this->db->where('id', $id);
					$this->db->set('updated', 'NOW()', FALSE);
					$this->db->update($tbl, $dataArray); 
				}
				
			}
				
		}
		if($deleteData){
			foreach($deleteData as $tbl=>$dataBatch){
					
					$tbl=$this->getPackageTable($tbl);
					
					$this->db->where_in('id', $dataBatch);
					
					$this->db->delete($tbl); 
				
				
			}
		
		}
	}
	
	

}
?>
