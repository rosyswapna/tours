<?php 
class Tour_model extends CI_Model {

	//---------------------------business season start-----------------------------
	function getBusinessSeason($id)
	{
		$this->db->select('id,name,starting_date,ending_date');		
		$this->db->from('business_seasons');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if($query->num_rows() == 1){
			return $query->row_array();
		}else{
			return false;
		}
	}

	function getBusinessSeasonList()
	{
		$this->db->select('id,name,starting_date,ending_date');
		$this->db->from('business_seasons');
		$this->db->where('organisation_id',$this->session->userdata('organisation_id'));
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}
	//---------------------------------------------------------

	//---------------------------destinations start-----------------------------
	
	function getDestination($id)
	{
		$this->db->select('dst.*,st.name as status');
		$this->db->from('destinations dst');
		$this->db->join('statuses st','st.id=dst.status_id');
		$this->db->where('dst.id',$id);
		$query = $this->db->get();
		$retArray = array();
		if($query->num_rows() == 1){
			$row = $query->row();
			$retArray['id'] = $row->id;
			$retArray['name'] = $row->name;
			$retArray['lat'] = $row->lat;
			$retArray['lng'] = $row->lng;
			$retArray['description'] = $row->description;
			$retArray['status_id'] = $row->status_id;
			$retArray['status'] = $row->status;
			$retArray['seasons'] = ($row->seasons !='')?unserialize($row->seasons):'';
			return $retArray;
		}else{
			return false;
		}
	}

	function getDestinationList()
	{
		$this->db->select('dst.*,st.name as status');
		$this->db->from('destinations dst');
		$this->db->join('statuses st','st.id=dst.status_id');
		$this->db->where('organisation_id',$this->session->userdata('organisation_id'));
		$query = $this->db->get();
		$retArray = array();
		if($query->num_rows() > 0){
			foreach ($query->result_array() as $row){
				$retArray[] = array('id' => $row['id'],
						'name'	=> $row['name'],
						'lat'	=> $row['lat'],
						'lng'	=> $row['lng'],
						'description'	=> $row['description'],
						'status_id' => $row['status_id'],
						'status' => $row['status'],
						'seasons' => ($row['seasons'] !='')?unserialize($row['seasons']):''
						);
			}
			return $retArray;
		}else{
			return false;
		}
	}

	//function returns season ids as array (date format 'yyyy-mm-dd')
	function getSeasonIdssWithDate($_date = '')//not completed
	{
		
		$this->db->select("id,DAYOFYEAR(starting_date) st, DAYOFYEAR(ending_date) ed ,DAYOFYEAR('".$_date."') cr");
		$this->db->from('business_seasons');
	
		$query = $this->db->get();//echo $this->db->last_query();exit;
		if($query->num_rows() > 0){
			$result = $query->result();
			$idArray = array();
			foreach($result as $row){//print_r($row);exit;
				$cr = ($row->st > $row->ed)?$row->cr+366:$row->cr;
				$st = $row->st;
				$ed = ($row->st > $row->ed)?$row->ed+366:$row->ed;
				
				if($cr >= $st && $cr <= $ed){
					array_push($idArray,$row->id);
				}
				
			}
			return $idArray;
		}else{
			return false;
		}
			
	}
	//---------------------------------------------------------
	


	//get current season
	function getCurrentSeason()
	{
		$this->db->from('business_seasons');
		$this->db->where('DAYOFYEAR(NOW()) BETWEEN DAYOFYEAR(starting_date) AND DAYOFYEAR(ending_date)');
		$query = $this->db->get();
		if($query->num_rows() == 1)
			return $query->row();
		else
			return false;	
		
	}
	
	//get season destinations with season id array as parameter ,default with current season
	function getDateSeasonDestinations($_date=''){ 
		
		$seasonIds = $this->getSeasonIdssWithDate($_date);
		$filtered_destinations = array();
		$destinations  = $this->getDestinationList(); 
		foreach($destinations as $destination){

			if($destination['seasons'] == ''){
				$filtered_destinations[$destination['id']] = $destination['name'];
			}elseif(is_array($destination['seasons']) && is_array($seasonIds)){
				if(count(array_intersect($destination['seasons'],$seasonIds)) > 0){
					$filtered_destinations[$destination['id']] = $destination['name'];
				}
			}

		}
		
		return $filtered_destinations;
	
	}

	//get current season destinations
	function getCurrentSeasonDestinations(){ 
	
		$current_season = @$this->session->userdata('current_season');
		$crn_s_id = @$current_season['id'];
		
		$filtered_destinations = array();
		$destinations  = $this->getDestinationList(); 
		foreach($destinations as $destination){
			if($destination['seasons'] == '' || in_array($destination['seasons'],$crn_s_id)){
				$filtered_destinations[$destination['id']] = $destination['name'];
			}
		}
		
		return $filtered_destinations;
	}


	//---------------------tour functions------------------------------------

	function getRoomOccupancyCount($hotel_id,$room_type_id,$_date)
	{
		$itinerarySQL = "SELECT id FROM itinerary WHERE trip_id IN (SELECT id FROM trips WHERE trip_status_id = ".TRIP_STATUS_PENDING ." OR trip_status_id = ".TRIP_STATUS_CONFIRMED.") AND date = ".$this->db->escape($_date);

		$accomodationSQL = "SELECT SUM(acm.room_quantity) as occupancy FROM trip_accommodation acm";
		
		$accomodationSQL .= " WHERE acm.room_type_id = ".$this->db->escape($room_type_id)." AND acm.hotel_id = ".$this->db->escape($hotel_id)." AND acm.itinerary_id IN (".$itinerarySQL.")";

		$result = $this->db->query($accomodationSQL);
		$row = $result->row();
		return ($row->occupancy!=null)?$row->occupancy:0;
		

	}

	function getTrip($trip_id = 0){
		$this->db->select('T.*,C.name as customer_name,C.mobile as customer_mobile,G.name as guest_name,G.mobile as guest_mobile,TS.name as trip_status_name,BS.name as booking_source_name,P.name as package_name');
		$this->db->from('trips T');
		$this->db->join('customers C','T.customer_id = C.id','left');
		$this->db->join('customers G','T.guest_id = G.id','left');
		$this->db->join('trip_statuses TS','T.trip_status_id = TS.id','left');
		$this->db->join('booking_sources BS','T.trip_source_id = BS.id','left');
		$this->db->join('packages P','T.package_id = P.id','left');

		$this->db->where('T.id',$trip_id);
		$query = $this->db->get();
		if($query->num_rows() == 1)
			return $query->row_array();
		else
			return false;	
	}

	//build itinerary with pickup and drop date of a trip data
	function buildItinerary($trip_id=0)
	{
		/*$filter = array(
				'id' => $trip_id,
				'DATEADD(DAY,number+1,@Date1) < ' => 'drop_date');
		$this->db->select('DATEADD(DAY,number+1,pickup_date) [itinerary]');
		$this->db->from('trips');
		$this->db->where($filter);*/
		$this->db->select('pick_up_date,drop_date');
		$this->db->from('trips');
		$this->db->where('id',$trip_id);
		$query = $this->db->get();
		$itinerary = array();
		if($query->num_rows() == 1)
		{
			$row = $query->row();
			
			$itinerary[] = $row->pick_up_date;

			$next = date('Y-m-d', strtotime($row->pick_up_date . ' + 1 day'));
			while(strtotime($row->drop_date) >= strtotime($next)){
				$itinerary[] = $next;
				$next = date('Y-m-d', strtotime($next . ' + 1 day'));
			}
			return $itinerary;
			
		}else{
			return false;
		}

	}

	//add itineraries with trip dates
	function addItineraries($trip_id = 0)
	{ 
		$itineraries = $this->buildItinerary($trip_id);
		if(!$itineraries)
			return false;

		$insertData = array();
		foreach($itineraries as $itinerary){
			$insertData[] = array(
					'trip_id' => $trip_id,
					'date'	  => $itinerary,
					'user_id' => $this->session->userdata('id'),
					'organisation_id' => $this->session->userdata('organisation_id')
					);
		}

		if($insertData){
			$this->db->insert_batch('itinerary', $insertData);
			return true;
		}else{
			return false;
		}
	}

	//add single itineray with date and trip id
	function addItinerary($_date,$trip_id = 0)
	{

		$is_valid = $this->validateItinerary($_date);
		if($is_valid){
			$insertData = array(
				'trip_id' => $trip_id,
				'date'	  => $_date,
				'user_id' => $this->session->userdata('id'),
				'organisation_id' => $this->session->userdata('organisation_id'),
				'created' => 'NOW()'
				);
			$this->db->insert('itinerary', $insertData);
			return $this->db->insert_id();
		}else{
			return false;
		}
		
		
	}

	//validate itinerary before add 
	function validateItinerary($input=''){
		$date_format = 'Y-m-d';
		$input = trim($input);
		$time = strtotime($input);

		$is_valid = date($date_format, $time) == $input;

		return $is_valid;
		
	}

	//get trip itineraries with trip id as parameter
	function getItineraries($trip_id = 0)
	{
		$this->db->from('itinerary');
		$this->db->where('trip_id',$trip_id);
		$query = $this->db->get();
		if($query->num_rows() > 0)
			return $query->result_array();
		else
			return false;
	}

	

	//get single itinerary with id
	function getItinerary($id = 0)
	{
		$this->db->from('itinerary');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if($query->num_rows() == 1)
			return $query->row();
		else
			return false;
	}

	//get single itinerary with id
	function getItineraryWithDate($date = '',$trip_id)
	{
		$this->db->from('itinerary');
		$this->db->where(array('trip_id'=>$trip_id,'date'=>$date));
		$query = $this->db->get();
		if($query->num_rows() == 1)
			return $query->row_array();
		else
			return false;
	}

	//adding Trip Vehicles with trip id and vehicle array
	function addTripVehicles($vehicleData,$trip_id)
	{
		$itineraries = $this->getItineraries($trip_id);
		if(!$itineraries || count($vehicleData) == 0)
			return false;

		$vehicleData['user_id'] = $this->session->userdata('id');
		$vehicleData['organisation_id'] = $this->session->userdata('organisation_id');

		$insertData = array();$i = 0;
		foreach($itineraries as $itinerary){
			$insertData[$i] = $vehicleData;
			$insertData[$i]['itinerary_id'] = $itinerary['id'];
			$i++;
		}

		if($insertData){
			$this->db->insert_batch('trip_vehicles', $insertData);
			return true;
		}else{
			return false;
		}
	}

	//adding trip destinations trip id and destination array
	function addTripDestinations($destinationData,$trip_id)
	{
		$itineraries = $this->getItineraries($trip_id);
		if(!$itineraries || count($destinationData) == 0)
			return false;

		$destinationData['user_id'] = $this->session->userdata('id');
		$destinationData['organisation_id'] = $this->session->userdata('organisation_id');

		$insertData = array();$i = 0;
		foreach($itineraries as $itinerary){
			$insertData[$i] = $destinationData;
			$insertData[$i]['itinerary_id'] = $itinerary['id'];
			$i++;
		}

		if($insertData){
			$this->db->insert_batch('trip_destinations', $insertData);
			return true;
		}else{
			return false;
		}
	}

	//adding trip accomodation trip id and accommodation array
	function addTripAccommodation($accommodationData,$trip_id)
	{
		$itineraries = $this->getItineraries($trip_id);
		if(!$itineraries || count($accommodationData) == 0)
			return false;

		$accommodationData['user_id'] = $this->session->userdata('id');
		$accommodationData['organisation_id'] = $this->session->userdata('organisation_id');

		$insertData = array();$i = 0;
		foreach($itineraries as $itinerary){
			$insertData[$i] = $accommodationData;
			$insertData[$i]['itinerary_id'] = $itinerary['id'];
			$i++;
		}

		if($insertData){
			$this->db->insert_batch('trip_accommodation', $insertData);
			return true;
		}else{
			return false;
		}
	}

	//adding trip services trip id and services array
	function addTripServices($serviceData,$trip_id)
	{
		$itineraries = $this->getItineraries($trip_id);
		if(!$itineraries || count($serviceData) == 0)
			return false;

		$serviceData['user_id'] = $this->session->userdata('id');
		$serviceData['organisation_id'] = $this->session->userdata('organisation_id');

		$insertData = array();$i = 0;
		foreach($itineraries as $itinerary){
			$insertData[$i] = $serviceData;
			$insertData[$i]['itinerary_id'] = $itinerary['id'];
			$i++;
		}

		if($insertData){
			$this->db->insert_batch('trip_services', $insertData);
			return true;
		}else{
			return false;
		}
	}

	//delete trip itinerary data 
	function resetTripItineraryData($table='',$trip_id=''){
		if($table !='' && $trip_id != ''){
			$this->db->select('id');
			$this->db->from('itinerary');
			$this->db->where('trip_id',$trip_id);
			$qry = $this->db->get();
			$rows = $qry->result_array();
			$itineraries = array();
			if($rows){
				foreach($rows as $row){
				array_push($itineraries,$row['id']);
				}
			}
			
			if($itineraries){
				$this->db->where_in('itinerary_id', $itineraries);
				$this->db->delete($table); 
			}
		}
	}



	//save tour cart with tour cart class
	function save_tour_cart($cartClass,$trip_id){
		//echo '<pre>';print_r($cart);echo '</pre>';exit;
		$cart=$this->pckCart_to_tourCart($cartClass->contents(),$trip_id);
		$deleteData=$cartClass->delete_itineraries();
		//create insert and update array
		foreach($cart as $_date=>$itry){
			//get itinerary id or get from insert
			$itinerary = $this->getItineraryWithDate($_date,$trip_id);
			if($itinerary){
				$itinerary_id = $itinerary['id'];
			}else{
				$itinerary_id = $this->addItinerary($_date,$trip_id);
			}
			
			if(is_numeric($itinerary_id) && $itinerary_id > 0){
				foreach($itry as $table=>$tableData){
					if($table != "label" && $tableData!=null){
						foreach($tableData as $data){
							$data['itinerary_id'] = $itinerary_id;
							$id = $data['id'];
							unset($data['id']);
						
						
							//if found array values serialize them
							foreach($data as $colName=>$colVal){
							
								if(is_array($colVal)){
									$data[$colName] = serialize($colVal);
								}
							
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

		//echo "<pre>";print_r($insertData);echo "</pre>";exit;

		//insert batch
		if($insertData){
			foreach($insertData as $tbl=>$dataArray){
				//echo "<pre>";print_r($dataArray);echo "</pre>";exit;
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
		return true;
	}
	//---------------------------------------------------------

	//get trip vehicles or accommodation or services or destinatins of a particular itinerary
	function getItineraryData($itinerary_id,$table='')
	{
		if($table == '')
			return false;

		switch($table){
			case 'trip_destinations':$this->db->select('id,itinerary_id,destination_id,destination_priority,description');
						$this->db->from($table);
						$this->db->where('itinerary_id',$itinerary_id);
						$query = $this->db->get();
						break;
			case 'trip_accommodation':$this->db->select('id,itinerary_id,hotel_id,room_type_id,room_quantity,from_date,to_date,check_in_time,check_out_time,room_attributes,meals_package,meals_quantity,amount');
						$this->db->from($table);
						
						$this->db->where('itinerary_id',$itinerary_id);
						$query = $this->db->get();
						break;
			case 'trip_services':$this->db->select('id,itinerary_id, service_id, description, location, quantity, amount');
						$this->db->from($table);
						
						$this->db->where('itinerary_id',$itinerary_id);
						$query = $this->db->get();
						break;
			case 'trip_vehicles':$this->db->select('id,itinerary_id,vehicle_id,vehicle_ac_type_id, vehicle_beacon_light_option_id,vehicle_type_id,vehicle_model_id, pluckcard, uniform, tariff_id, driver_id, driver_language_id, driver_language_proficiency_id, start_km_reading, end_km_reading, driver_bata, night_halt_charges, trip_expense');
						$this->db->from($table);
						
						$this->db->where('itinerary_id',$itinerary_id);
						$query = $this->db->get();
						break;
			default:$this->db->select($table.'.*');
				$this->db->from($table);
				$this->db->where('itinerary_id',$itinerary_id);
				$query = $this->db->get();
						
		}
		//$this->db->from($table);
		//$this->db->where('itinerary_id',$itinerary_id);
		//$query = $this->db->get();
		
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}
	
	function getNamesByIds($table,$select,$idArray){

		$this->db->select($select);
		$this->db->from($table);
		$this->db->where_in('id', $idArray);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$ret[]=$row[$select];
			}
			return $ret;
		}else{
			return array();
		}
	}


	function getItineraryDataLink($table,$select,$trip_sections,$tab='',$itinerary){
	$ret=array();
		foreach ($trip_sections as $section){
			$row_id=$section[0];
			$id=$section[1];
			$this->db->select($select);
			$this->db->from($table);
			$this->db->where(array('id'=>$id));
			$query = $this->db->get(); 
			if($query->num_rows() > 0){
			
				$section_val=$query->row()->$select;
				
				$ret[]= '<a href="#" row_id="'.$row_id.'" table="'.$table.'" tab="'.$tab.'" itinerary="'.$itinerary.'" class="edit_data">'.$section_val."</a>";
				
			}
			
		}
		return $ret;
		
	}


	//get trip vehicles, accommodation, services and destinatins of a trip
	function getItineraryDataAll($trip_id=0)
	{
		if(!is_numeric($trip_id))
			return false;

		$itineraries = $this->getItineraries($trip_id);

		$tbls = array('trip_destinations','trip_accommodation','trip_services','trip_vehicles');
		$retArray = array();
		if($itineraries!=false){
		foreach($itineraries as $itinerary){
			foreach($tbls as $tbl){
				$tbleData = $this->getItineraryData($itinerary['id'],$tbl);
				if($tbleData)
					$retArray[$itinerary['date']][$tbl]=$tbleData;
			}
		}

		return $retArray;
		}
		
	}
	//package cart to tour cart converting function
	public function pckCart_to_tourCart($cartFromPCk,$trip_id){

		//echo "<pre>";print_r($cartFromPCk);echo "</pre>";exit;
		$buildItrs = $this->tour_model->buildItinerary($trip_id);
		$tourCart = array();
		if($buildItrs!= false && count($cartFromPCk) == count($buildItrs)){
			$i=0;//index throungh buildItries
			foreach($cartFromPCk as $dayno=>$itryData){
				$tour_dataArray = array();
				foreach($itryData as $tbl=>$dataArray){
					foreach($dataArray as $data){
						unset($data['itinerary_id']);
						$data['id'] = gINVALID;
						$tour_dataArray[$tbl][] = $data;
						
					}
					
				}
				$tourCart[$buildItrs[$i]] = $tour_dataArray;
				$i++;
			}
		}	
		//echo "<pre>";print_r($tourCart);echo "</pre>";exit;	
		
		return $tourCart;
	}


	//-----------------------------------------------------------------------
	
	function getVehicleType($vehicle_type_id){ 
		$this->db->select('name');
		$this->db->from('vehicle_types');
		$this->db->where('id',$vehicle_type_id);
		$qry=$this->db->get(); 
			if($qry->num_rows() > 0){
				return $qry->row();
			}else{
				return false;
			}
	}


	function getTripExpenses(){
		$org_id=$this->session->userdata('organisation_id');
		$this->db->from('trip_expenses');
    		$this->db->where( 'organisation_id', $org_id );
		//---
    		$results = $this->db->get()->result_array();
		if(count($results)>0){//print_r($results);
			return $results;
		}else{
			return gINVALID;
		}
	}


	//get tour params array for voucher
	function getTourValues($trip_id){

		$itineraries = $this->getItineraries($trip_id);
		$retArray = array('tour_vehicles'=>array(),
				'tour_hotels'=>array(),
				'tour_services'=>array()
				);
		if($itineraries){
			$itryIds = array();
			foreach($itineraries as $itry){
				array_push($itryIds,$itry['id']);
			}

			//tour vehicles
			$this->db->select('v.id,v.registration_number');
			$this->db->from('trip_vehicles tv');
			$this->db->join('vehicles v','v.id = tv.vehicle_id','left');
			$qry=$this->db->where_in('itinerary_id',$itryIds);
			$qry=$this->db->get();
			if($qry->num_rows() > 0){
				$list = $qry->result_array();
				foreach($list as $row){
					$retArray['tour_vehicles'][$row['id']]=$row['registration_number'];
				}
			}

			//tour hotels
			$this->db->select('h.id,h.name');
			$this->db->from('trip_accommodation ta');
			$this->db->join('hotels h','h.id = ta.hotel_id','left');
			$qry=$this->db->where_in('itinerary_id',$itryIds);
			$qry=$this->db->get();
			if($qry->num_rows() > 0){
				$list = $qry->result_array();
				foreach($list as $row){
					$retArray['tour_hotels'][$row['id']]=$row['name'];
				}
			}


			//tour services
			$this->db->select('s.id,s.name');
			$this->db->from('trip_services ts');
			$this->db->join('services s','s.id = ts.service_id','left');
			$qry=$this->db->where_in('itinerary_id',$itryIds);
			$qry=$this->db->get();
			if($qry->num_rows() > 0){
				$list = $qry->result_array();
				foreach($list as $row){
					$retArray['tour_services'][$row['id']]=$row['name'];
				}
			}


			
		}

		//echo "<pre>";print_r($retArray);echo "</pre>";exit;
		return $retArray;

	}
	
	//check and to be deleted function
	//fetch editable values for each trip sections using section id and table name
	function get_trip_section_values($trip_section_id,$tbl){ 
		$this->db->where('id',$trip_section_id);
		$qry=$this->db->get($tbl);
		
		if($qry->num_rows() > 0){
			$editable_values=$qry->row_array();
			if($tbl=='trip_accommodation' || $tbl=='package_accommodation'){ 
			$this->db->select('hotel_category_id,destination_id');
			$this->db->where('id',$editable_values['hotel_id']);
			$this->db->from('hotels');
			$qry=$this->db->get()->row(); 
			$editable_values['hotel_category_id']=$qry->hotel_category_id;
			$editable_values['destination_id']=$qry->destination_id;
			$editable_values['room_attributes']=unserialize($editable_values['room_attributes']);
			$editable_values['meals_package']=unserialize($editable_values['meals_package']);
			}
			return $editable_values;
		}else{
			return array();
		}
	}
	
	function  getHotelAttributes($hotel_id){
		$this->db->select('hotel_category_id,destination_id');
		$this->db->where('id',$hotel_id);
		$this->db->from('hotels');
		$qry=$this->db->get(); 
		if($qry->num_rows() > 0){
			$result=$qry->row();
			$hotel_array['hotel_category_id']=$result->hotel_category_id;
			$hotel_array['destination_id']=$result->destination_id;
			return $hotel_array;
		}else{
			return false;
		}
		
	
	}

	//get selected room for a trip with hotel id
	function getTourRoomWithHotel($trip_id,$hotel_id,$season)
	{

		$qry = "SELECT TA.room_type_id,RRT.name AS room_type_name,TA.room_attributes,TA.room_quantity,TA.meals_package,TA.meals_quantity,RT.amount
			FROM trip_accommodation TA
			LEFT JOIN room_types RRT ON RRT.id = TA.room_type_id
			LEFT JOIN room_tariffs RT ON TA.hotel_id = RT.hotel_id AND TA.room_type_id = RT.room_type_id AND season_id = ".$this->db->escape($season)."
			WHERE TA.itinerary_id IN(SELECT id FROM itinerary WHERE trip_id = ".$this->db->escape($trip_id).")  AND TA.hotel_id = ".$this->db->escape($hotel_id)." AND RT.organisation_id = ".$this->session->userdata('organisation_id');

		$result = $this->db->query($qry);
		if($result->num_rows() > 0){
			$row = $result->row_array();
			if($row['room_attributes'] != '')
				$row['room_attributes'] = unserialize($row['room_attributes']);

			if($row['meals_package'] != '')
				$row['meals_package'] = unserialize($row['meals_package']);

			//get room attibutes and meals package tariff amount (pending)

			return $row;
		}else{
			return false;
		}
		
	}	
	//check the relevance of this function in tour controller and  remove it
	function  getDistinctVehicles($package_id){ 
	$this->db->select('v.registration_number,v.id as vehicle_id');
	$this->db->distinct();
	$this->db->where('p.id',$package_id);
	$this->db->from('packages p');
	$this->db->join('package_itinerary pi','pi.package_id = p.id','left');
	$this->db->join('package_vehicles pv','pv.package_itinerary_id = pi.id','left');
	$this->db->join('vehicles v','pv.vehicle_id = v.id','left');
	$qry=$this->db->get(); 
		if($qry->num_rows() > 0){
			
			$v_array= $qry->result_array();
			foreach ($v_array as $key=>$vehicle){
				echo $vehicle['vehicle_id'];
				
			}
			
		}else{
			return false;
		}
	}
}
?>
