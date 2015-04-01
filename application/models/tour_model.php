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
		$_date = ($_date != '')?$_date:date('Y-m-d');
		$this->db->select("id,DAYOFYEAR(starting_date) st, DAYOFYEAR(ending_date) ed ,DAYOFYEAR('".$_date."') cr");
		$this->db->from('business_seasons');
	
		//$this->db->where('DAYOFYEAR('.$this->db->escape($_date).') BETWEEN DAYOFYEAR(starting_date) AND DAYOFYEAR(ending_date)');
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
		$this->db->from('trips');
		$this->db->where('id',$trip_id);
		$query = $this->db->get();
		if($query->num_rows() == 1)
			return $query->row();
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

	//get trip vehicles or accommodation or services or destinatins of a particular itinerary
	function getItineraryData($itinerary_id,$table='')
	{
		if($table == '')
			return false;

		/*switch($table){
			case 'trip_destinations':$this->db->select($table.'.*,dst.name as destination_name');
						$this->db->from($table);
						$this->db->join('destinations dst',$table.'.destination_id = dst.id','left');			
						$this->db->where('itinerary_id',$itinerary_id);
						$query = $this->db->get();
						break;
			case 'trip_accommodation':$this->db->select($table.'.*,H.name as hotel_name');
						$this->db->from($table);
						$this->db->join('hotels H',$table.'.hotel_id = H.id','left');			
						$this->db->where('itinerary_id',$itinerary_id);
						$query = $this->db->get();
						break;
			case 'trip_services':$this->db->select($table.'.*,S.name as service_name');
						$this->db->from($table);
						$this->db->join('services S',$table.'.service_id = S.id','left');			
						$this->db->where('itinerary_id',$itinerary_id);
						$query = $this->db->get();
						break;
			case 'trip_vehicles':$this->db->select($table.'.*,V.registration_number');
						$this->db->from($table);
						$this->db->join('vehicles V',$table.'.vehicle_id = V.id','left');			
						$this->db->where('itinerary_id',$itinerary_id);
						$query = $this->db->get();
						break;
			default:$this->db->select($table.'.*');
				$this->db->from($table);
				$this->db->where('itinerary_id',$itinerary_id);
				$query = $this->db->get();
						
		}*/
		$this->db->from($table);
		$this->db->where('itinerary_id',$itinerary_id);
		$query = $this->db->get();
		
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


	//get trip vehicles, accommodation, services and destinatins of a trip
	function getItineraryDataAll($trip_id=0)
	{
		if(!is_numeric($trip_id))
			return false;

		$itineraries = $this->getItineraries($trip_id);

		$tbls = array('trip_destinations','trip_accommodation','trip_services','trip_vehicles');
		$retArray = array();
		foreach($itineraries as $itinerary){
			$retArray[$itinerary['id']]['label'] = $itinerary['date'];
			foreach($tbls as $tbl){
				$retArray[$itinerary['id']][$tbl]=$this->getItineraryData($itinerary['id'],$tbl);
			}
		}

		return $retArray;
		
	}


	//-----------------------------------------------------------------------

		

}
?>
