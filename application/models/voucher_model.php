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

	
}
?>
