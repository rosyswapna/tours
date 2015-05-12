<?php 
class Hotel_model extends CI_Model {
	
	//get hotel details with its id
	function getHotelProfile($id)
	{
		$this->db->from('hotels');
		$this->db->where('id',$id);
		$query = $this->db->get();
		$retArray = array();
		if($query->num_rows() == 1){
			$row = $query->row();
			$retArray['id'] = $row->id;
			$retArray['name'] = $row->name;
			$retArray['address'] = $row->address;
			$retArray['city'] = $row->city;
			$retArray['state'] = $row->state;
			$retArray['contact_person'] = $row->contact_person;
			$retArray['mobile'] = $row->mobile;
			$retArray['land_line_number'] = $row->land_line_number;
			$retArray['hotel_category_id'] = $row->hotel_category_id;
			$retArray['hotel_owner_id'] = $row->hotel_owner_id;
			$retArray['destination_id'] = $row->destination_id;
			$retArray['hotel_rating_id'] = $row->hotel_rating_id;
			$retArray['seasons'] = ($row->seasons !='')?unserialize($row->seasons):'';
			$retArray['no_of_rooms'] = $row->no_of_rooms;
			return $retArray;
			
		}else{
			return false;
		}
	}

	//get hotel list with owners joined
	function getHotel($id)
	{
		$this->db->select('hotel.*, owner.name as owner_name,owner.mobile as owner_mobile,category.name as category,rating.name as rating');
		$this->db->from('hotels as hotel');
		$this->db->join('hotel_owners as owner', 'owner.id = hotel.hotel_owner_id','left');
		$this->db->join('hotel_categories as category', 'category.id = hotel.hotel_category_id','left');
		$this->db->join('hotel_ratings as rating', 'rating.id = hotel.hotel_rating_id','left');
		$this->db->where('hotel.id',$id);
		
		$query = $this->db->get();//echo $this->db->last_query();exit;
		if($query->num_rows() > 0){
			return $query->row_array();
		}else{
			return false;
		}
	}

	//get hotel list with owners joined
	function getHotelList($condition=array())
	{
		$this->db->select('hotel.*, owner.name as owner_name,owner.mobile as owner_mobile,category.name as category,rating.name as rating');
		$this->db->from('hotels as hotel');
		$this->db->join('hotel_owners as owner', 'owner.id = hotel.hotel_owner_id','left');
		$this->db->join('hotel_categories as category', 'category.id = hotel.hotel_category_id','left');
		$this->db->join('hotel_ratings as rating', 'rating.id = hotel.hotel_rating_id','left');
		if($condition){
			$condition['hotel.organisation_id'] = $this->session->userdata('organisation_id');
			$this->db->where($condition);
		}else{
			$this->db->where('hotel.organisation_id',$this->session->userdata('organisation_id'));
		}
		
		$query = $this->db->get();//echo $this->db->last_query();exit;
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

	//get current season hotels
	function getSeasonHotels($condition=array()){ 
		
		$current_season = @$this->session->userdata('current_season');
		$filtered_hotels = array();
		if($current_season){
			$hotels  = $this->getHotelList($condition);
			if($hotels){
				foreach($hotels as $hotel){
				
					if(is_array($hotel['seasons']) && in_array($current_season['id'],$hotel['seasons'])){
						$filtered_hotels[$hotel['id']] = $hotel['name'];
					}
				}
				return $filtered_hotels;
			}
			
		}
		
		return false;
		
	
	}

	//get season hotels with season id array as parameter ,and condition
	function getDateSeasonHotels($condition = array(),$seasonIds= array()){ 
		
		$filtered_hotels = array();
		$hotels  = $this->getHotelList($condition); 
		foreach($hotels as $hotel){

			if($hotel['seasons'] == ''){
				$filtered_hotels[] = array('id'=>$hotel['id'],
							'name'=>$hotel['name']);
			}elseif(is_array($hotel['seasons']) && is_array($seasonIds)){
				if(count(array_intersect($hotel['seasons'],$seasonIds)) > 0){
					$filtered_hotels[] = array('id'=>$hotel['id'],
							'name'=>$hotel['name']);
				}
			}

		}
		
		return $filtered_hotels;
	
	}

	//--------------------------------------------------------------------------------------------

	//get owner details
	function getHotelOwner($id)
	{
		$this->db->from('hotel_owners');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if($query->num_rows() == 1){
			return $query->row_array();
		}else{
			return false;
		}
	}
	//---------------------------------------------------------------------------------------------
	//get list of rooms with hotel id
	function getHotelRooms($hotel_id)
	{
		$this->db->select('hotel_rooms.room_type_id,hotel_rooms.no_of_rooms,room_types.name as room_type_name');
		$this->db->from('hotel_rooms');
		$this->db->join('room_types','room_types.id=hotel_rooms.room_type_id','left');
		$this->db->where('hotel_id',$hotel_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

	//get hotel room row with hotel id and room type id
	function getHotelRoomType($hotel_id,$room_type_id)
	{
		$this->db->from('hotel_rooms');
		$this->db->where(array('hotel_id'=>$hotel_id,'room_type_id'=>$room_type_id));
		$query = $this->db->get();
		if($query->num_rows() == 1){
			return $query->row_array();
		}else{
			return false;
		}
	}
	//-------------------------------------------------------------------------

	//get hotel's room tariffs list
	function getHotelRoomTariffs($hotel_id)
	{
		$this->db->from('room_tariffs');
		$this->db->where('hotel_id',$hotel_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

	//get hote room tariff row with condition supplied
	function getHotelRoomTariff($condition=array())
	{
		$this->db->from('room_tariffs');
		$this->db->where($condition);
		$query = $this->db->get();
		if($query->num_rows() == 1){
			return $query->row();
		}else{
			return false;
		}
	}
	//----------------------------------------------------------------------
	
	//get all room tariffs for a hotel with season id
	function getHotelRoomSeasonTariffs($hotel_id,$season_id)
	{
		$this->db->from('room_tariffs');
		$this->db->where(array('hotel_id'=>$hotel_id,'season_id'=>$season_id));
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

	//get all room attribute tariffs for a hotel 
	function getHotelRoomAttrTariffs($hotel_id)
	{
		$this->db->from('room_attribute_tariffs');
		$this->db->where('hotel_id',$hotel_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

	//get all room attribute tariffs for a hotel with season id
	function getHotelRoomAttrSeasonTariffs($hotel_id,$season_id)
	{
		$this->db->from('room_attribute_tariffs');
		$this->db->where(array('hotel_id'=>$hotel_id,'season_id'=>$season_id));
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

	//get room attribute row with condition as supplied
	function getAttributeTariff($condition= array())
	{
		$this->db->select('RAT.*,RA.name as attr_name,M.name as meals_name');
		$this->db->from('room_attribute_tariffs RAT');
		$this->db->join('room_attributes RA','RAT.attribute_id = RA.id','left');
		$this->db->join('meals_options M','RAT.meals_id = M.id','left');
		$this->db->where($condition);
		$query = $this->db->get();//echo $this->db->last_query();exit;
		if($query->num_rows() == 1){
			return $query->row();
		}else{
			return false;
		}
	}

	//----------------------------------------------------------------------------

	//insert if no record else update hotel rooms
	function updateHotelRooms($data)
	{
		$condition = array('hotel_id'=>$data['hotel_id'],'room_type_id'=>$data['room_type_id']);

		$this->db->where($condition);
		$q = $this->db->get('hotel_rooms');

		if ( $q->num_rows() > 0 ) 
		{
			$this->db->where($condition);
			$this->db->set('no_of_rooms', $data['no_of_rooms']);
			$this->db->set('updated', 'NOW()', FALSE);
			$this->db->update('hotel_rooms');
			return true;

		} else {
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('hotel_rooms',$data);
			return true;
		}

		return false;
	}

	//insert if no record else update hotel tariffs with table and data array
	function updateHotelTariffs($data,$table)
	{	
		if($table == 'room_tariffs'){
			$condition = array('season_id'=>$data['season_id'],'hotel_id'=>$data['hotel_id'],'room_type_id'=>$data['room_type_id']);

			$this->db->where($condition);
			$q = $this->db->get('room_tariffs');
		}else{
			$condition = array('season_id'=>$data['season_id'],'hotel_id'=>$data['hotel_id']);
			if(isset($data['room_attr_id']))
			$condition['attribute_id'] = $data['room_attr_id'];
			else
			$condition['meals_id'] = $data['meals_package_id'];
			
			$this->db->where($condition);
			$q = $this->db->get('room_attribute_tariffs');
		}

		if ( $q->num_rows() > 0 ) 
		{
			$this->db->where($condition);
			$this->db->set('updated', 'NOW()', FALSE);
			$this->db->update($table,$data);
			return true;
		} else {
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert($table,$data);
			return true;
		}

		return false;

		
	}
}
?>
