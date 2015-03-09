<?php 
class Hotel_model extends CI_Model {
	
	function getHotelProfile($id)
	{
		$this->db->from('hotels');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if($query->num_rows() == 1){
			return $query->row_array();
		}else{
			return false;
		}
	}

	//get hotels with owners
	function getHotelList()
	{
		$this->db->select('hotel.*, owner.name as owner_name,owner.mobile as owner_mobile');
		$this->db->from('hotels as hotel');
		$this->db->join('hotel_owners as owner', 'owner.id = hotel.hotel_owner_id');
		$this->db->where('hotel.organisation_id',$this->session->userdata('organisation_id'));
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

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

	function getHotelRooms($hotel_id)
	{
		$this->db->from('hotel_rooms');
		$this->db->where('hotel_id',$hotel_id);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

	//get room type with hotel id
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


	function updateHotelRooms($data)
	{
		$condition = array('season_id'=>$data['season_id'],'hotel_id'=>$data['hotel_id'],'room_type_id'=>$data['room_type_id']);

		$this->db->where($condition);
		$q = $this->db->get('hotel_rooms');

		if ( $q->num_rows() > 0 ) 
		{
			$this->db->where($condition);
			$this->db->set('updated', 'NOW()', FALSE);
			$this->db->update('hotel_rooms',$data);
			return true;

		} else {
			$this->db->set('created', 'NOW()', FALSE);
			$this->db->insert('hotel_rooms',$data);
			return true;
		}

		return false;
	}

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
