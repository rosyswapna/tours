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
		$this->db->select('id,name,lat,lng,seasons,description');
		$this->db->from('destinations');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$retArray = array();
		if($query->num_rows() == 1){
			$row = $query->row();
			$retArray['id'] = $row->id;
			$retArray['name'] = $row->name;
			$retArray['lat'] = $row->lat;
			$retArray['lng'] = $row->lng;
			$retArray['description'] = $row->description;
			$retArray['seasons'] = ($row->seasons !='')?unserialize($row->seasons):'';
			return $retArray;
		}else{
			return false;
		}
	}

	function getDestinationList()
	{
		$this->db->select('id,name,lat,lng,seasons,description');
		$this->db->from('destinations');
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
						'seasons' => ($row['seasons'] !='')?unserialize($row['seasons']):''
						);
			}
			return $retArray;
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
	
	//get current season destinations
	function getSeasonDestinations(){ 
		
		$current_season = @$this->session->userdata('current_season');
		$filtered_destinations = array();
		if($current_season){
			$destinations  = $this->getDestinationList(); 
			foreach($destinations as $destination){
				
				if(is_array($destination['seasons']) && in_array($current_season['id'],$destination['seasons'])){
					$filtered_destinations[$destination['id']] = $destination['name'];
				}
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
		$filter = array(
				'id' => $trip_id,
				'DATEADD(DAY,number+1,@Date1) < ' => 'drop_date');
		$this->db->select('DATEADD(DAY,number+1,pickup_date) [itinerary]');
		$this->db->from('trips');
		$this->db->where($filter);
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
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


	//-----------------------------------------------------------------------

		

}
?>
