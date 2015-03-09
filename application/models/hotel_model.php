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
}
?>
